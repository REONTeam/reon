const fs = require("fs");
const path = require("path");
const mysql = require("mysql2/promise");
const { Command } = require("commander");

const program = new Command();

// Main Reon config (DB, etc.) – usually reon/config.json
const defaultMainConfigPath = path.resolve(__dirname, "..", "..", "config.json");
// Separate news config – lives next to this script by default
const defaultNewsConfigPath = path.resolve(__dirname, "news_cycle.config.json");

program
  .option("-c, --config <path>", "Main Reon config file path.", defaultMainConfigPath)
  .option("-n, --news-config <path>", "News cycle config file path.", defaultNewsConfigPath)
  .parse(process.argv);

const options = program.opts();
const mainConfig = JSON.parse(fs.readFileSync(options.config, "utf8"));
const newsConfigRaw = JSON.parse(fs.readFileSync(options.newsConfig, "utf8"));

/**
 * Helper: create a MySQL connection pool using the standard Reon config keys.
 */
function createPool(cfg) {
  return mysql.createPool({
    host: cfg.mysql_host,
    user: cfg.mysql_user,
    password: cfg.mysql_password,
    database: cfg.mysql_database,
    waitForConnections: true,
    connectionLimit: 5,
    queueLimit: 0,
  });
}

/**
 * Load the shared bxt_encoding.json.
 */
function loadEncoding(rootDir) {
  const encPath = path.resolve(rootDir, "web", "scripts", "bxt_encoding.json");
  const raw = fs.readFileSync(encPath, "utf8");
  return JSON.parse(raw);
}

/**
 * Default configuration for the news cycle app.
 * These can be overridden / extended in the *news* config file
 * (by default app/pokemon-news/news_cycle.config.json).
 */
const defaultNewsConfig = {
  // Relative to this index.js; normally app/pokemon-news/articles
  articles_dir: path.resolve(__dirname, "articles"),

  /**
   * Map game_region letters to folder names under articles/.
   * Can be customized; missing regions will be skipped.
   */
  region_folder_map: {
    j: "j",
    e: "e",
    f: "f",
    d: "d",
    s: "s",
    i: "i",
    p: "p",
    u: "u",
  },

  /**
   * Map game_region letters to encoding table names for message_decode.
   * The requirement is:
   *   - jp table for Japanese
   *   - en table for any other language
   */
  region_message_encoding: {
    j: "jp",
    e: "en",
    f: "en",
    d: "en",
    s: "en",
    i: "en",
    p: "en",
    u: "en",
  },

  /**
   * Map game_region to the ranking-category table name prefix in bxt_encoding.json.
   * The actual key used is `${prefix}_ranking_category`.
   */
  region_ranking_prefix: {
    e: "btxe_btxp_btxu",
    p: "btxe_btxp_btxu",
    u: "btxe_btxp_btxu",
    f: "btxf",
    d: "btxd",
    s: "btxs",
    i: "btxi",
    j: "btxj",
  },

  /**
   * Optional mapping from (tableId, categoryIndex) pairs to actual
   * ranking category numbers per region.
   */
  ranking_map: {},

  /**
   * Schedule mapping. See news_cycle.config.json for examples.
   */
  schedule: {},
};

/**
 * Merge user news_cycle config (from the *news* config) over defaults.
 *
 * The news config file can be either:
 *   {
 *     "news_cycle": { ... }
 *   }
 * or:
 *   {
 *     ...direct news_cycle keys here...
 *   }
 */
function loadNewsConfig(rootDir) {
  const root =
    newsConfigRaw &&
    typeof newsConfigRaw === "object" &&
    newsConfigRaw.news_cycle &&
    typeof newsConfigRaw.news_cycle === "object"
      ? newsConfigRaw.news_cycle
      : newsConfigRaw;

  const merged = { ...defaultNewsConfig };

  for (const key of [
    "articles_dir",
    "region_folder_map",
    "region_message_encoding",
    "region_ranking_prefix",
    "ranking_map",
    "schedule",
  ]) {
    if (Object.prototype.hasOwnProperty.call(root, key)) {
      if (
        typeof merged[key] === "object" &&
        merged[key] !== null &&
        !Array.isArray(merged[key]) &&
        typeof root[key] === "object" &&
        root[key] !== null &&
        !Array.isArray(root[key])
      ) {
        merged[key] = { ...merged[key], ...root[key] };
      } else {
        merged[key] = root[key];
      }
    }
  }

  // global override
  const overrideRegion = loadGlobalDisplayRegion(rootDir);
  if (overrideRegion && (!merged.global_display_region || merged.global_display_region === null || merged.global_display_region === '')) {
    merged.global_display_region = overrideRegion;
  }

  // Normalize articles_dir.
  // Rules:
  // - Absolute path: use as-is.
  // - If it starts with "app/" or "web/", treat as relative to the Reon rootDir.
  // - Otherwise, treat as relative to this script directory.
  if (typeof merged.articles_dir === "string" && !path.isAbsolute(merged.articles_dir)) {
    const norm = merged.articles_dir.replace(/^[./\\]+/, "");
    if (
      norm.startsWith("app/") ||
      norm.startsWith("web/") ||
      norm.startsWith("app\\") ||
      norm.startsWith("web\\")
    ) {
      merged.articles_dir = path.resolve(rootDir, norm);
    } else {
      merged.articles_dir = path.resolve(__dirname, norm);
    }
  }

  return merged;
}

/**
 * Decode a buffer of single-byte character codes using bxt_encoding.json.
 * `tableName` is "jp" or "en".
 */
function decodeMessageBytes(buf, encoding, tableName) {
  const table = encoding[tableName];
  if (!table) return null;

  let out = "";
  for (let i = 0; i < buf.length; i++) {
    const hex = buf[i].toString(16).toUpperCase().padStart(2, "0");
    const ch = table[hex];
    out += ch !== undefined ? ch : "?";
  }
  return out;
}

/**
 * Find up to three ranking category slots in a news binary.
 *
 * We treat a "slot" as the sequence:
 *
 *   23 <table_addr_lo> CD <table_id>
 *   23 <cat_addr_lo>   CD <category_index>
 *
 * Where:
 *   - Japanese binaries use table_addr_lo = 0x62, cat_addr_lo = 0x63
 *   - International binaries use table_addr_lo = 0x6E, cat_addr_lo = 0x6F
 */
function findRankingSlots(buf) {
  const results = [];
  const patterns = [
    { tableLo: 0x6e, catLo: 0x6f },
    { tableLo: 0x62, catLo: 0x63 },
  ];

  for (const pat of patterns) {
    for (let i = 0; i + 8 < buf.length; i++) {
      if (
        buf[i] === 0x23 && // setval
        buf[i + 1] === pat.tableLo &&
        buf[i + 2] === 0xcd &&
        buf[i + 4] === 0x23 &&
        buf[i + 5] === pat.catLo &&
        buf[i + 6] === 0xcd
      ) {
        const tableId = buf[i + 3];
        const categoryIndex = buf[i + 7];

        results.push({
          offset: i,
          tableId,
          categoryIndex,
        });
      }
    }
    if (results.length) break; // prefer the first pattern that matches
  }

  // Sort by offset and return at most 3.
  results.sort((a, b) => a.offset - b.offset);
  return results.slice(0, 3);
}

/**
 * Resolve a (region, tableId, categoryIndex) tuple to a concrete ranking
 * category number using the config's ranking_map.
 */
function resolveRankingCategory(region, tableId, categoryIndex, rankingMap) {
  const regionMap = rankingMap[region] || {};
  const key = `${tableId}:${categoryIndex}`;
  if (Object.prototype.hasOwnProperty.call(regionMap, key)) {
    return regionMap[key];
  }
  return null;
}

/**
 * Given a game_region and a ranking category number, decode its label
 * using the appropriate ranking_category table in bxt_encoding.json.
 */
function decodeRankingCategory(region, categoryNumber, encoding, newsCfg) {
  if (categoryNumber === null || categoryNumber === undefined) return null;

  const displayRegion =
    newsCfg.global_display_region && newsCfg.global_display_region.length
      ? newsCfg.global_display_region
      : region;

  const prefix =
    (newsCfg.region_ranking_prefix &&
      newsCfg.region_ranking_prefix[displayRegion]) ||
    (newsCfg.region_ranking_prefix &&
      newsCfg.region_ranking_prefix[region]);

  if (!prefix) return null;

  const key = `${prefix}_ranking_category`;
  const table = encoding[key];
  if (!table) return null;

  const label = table[String(categoryNumber)];
  return label !== undefined ? label : null;
}

/**
 * Read message bytes from a news binary:
 *   from offset 0x18 up to but NOT including the first 0x50 byte.
 */
function extractMessageBytes(buf) {
  const start = 0x18;
  if (buf.length <= start) return Buffer.alloc(0);

  let end = start;
  while (end < buf.length && buf[end] !== 0x50) {
    end++;
  }
  if (end <= start) return Buffer.alloc(0);
  return buf.slice(start, end);
}

/**
 * Convert Date -> midnight date-only (for comparisons).
 */
function toDateOnly(d) {
  return new Date(d.getFullYear(), d.getMonth(), d.getDate());
}

/**
 * Parse a schedule entry, which can be:
 *   - "YYYY-MM-DD" (absolute date)
 *   - "MM-DD"      (recurring every year)
 *   - { "date": "MM-DD", "slot": N } (recurring with cycle slot)
 */
function parseScheduleEntry(entry) {
  if (typeof entry === "string") {
    // Full "YYYY-MM-DD"
    if (/^\d{4}-\d{2}-\d{2}$/.test(entry)) {
      const d = new Date(entry);
      if (Number.isNaN(d.getTime())) return null;
      return {
        kind: "full",
        date: new Date(d.getFullYear(), d.getMonth(), d.getDate()),
        month: d.getMonth() + 1,
        day: d.getDate(),
        slot: null,
        rankingCategories: null,
      };
    }

    // Yearless "MM-DD"
    if (/^\d{2}-\d{2}$/.test(entry)) {
      const [mm, dd] = entry.split("-");
      const month = Number(mm);
      const day = Number(dd);
      if (!Number.isInteger(month) || !Number.isInteger(day)) return null;
      if (month < 1 || month > 12 || day < 1 || day > 31) return null;
      return {
        kind: "md",
        date: null,
        month,
        day,
        slot: null,
        rankingCategories: null,
      };
    }

    return null;
  }

  if (entry && typeof entry === "object") {
    const dateStr = entry.date;
    if (typeof dateStr !== "string") return null;

    // Only support "MM-DD" in the object form (recurring).
    if (!/^\d{2}-\d{2}$/.test(dateStr)) return null;
    const [mm, dd] = dateStr.split("-");
    const month = Number(mm);
    const day = Number(dd);
    if (!Number.isInteger(month) || !Number.isInteger(day)) return null;
    if (month < 1 || month > 12 || day < 1 || day > 31) return null;

    const slot =
      Number.isInteger(entry.slot) && entry.slot >= 0 ? entry.slot : null;

    let rankingCategories = null;
    if (Array.isArray(entry.ranking_categories)) {
      rankingCategories = entry.ranking_categories
        .slice(0, 3)
        .map((v) =>
          typeof v === "number" && Number.isInteger(v) ? v : null
        );
    }

    return {
      kind: "md",
      date: null,
      month,
      day,
      slot,
      rankingCategories,
    };
  }

  return null;
}

/**
 * Compute whole-month difference between two date-only values: how many
 * calendar month boundaries between a and b.
 */
function monthsBetween(a, b) {
  return (
    (b.getFullYear() - a.getFullYear()) * 12 + (b.getMonth() - a.getMonth())
  );
}

/**
 * Find the best scheduled article for a region WITHOUT slots.
 * Uses:
 *   - absolute dates: date <= today && date > lastDate
 *   - MM-DD dates: treat as recurring each year:
 *       candidate = this year's MM-DD if <= today; else last year's
 *       require candidate <= today && candidate > lastDate
 */
function selectArticleForRegionDateOnly(regionEntries, lastTimestamp, todayDate) {
  const lastDate = lastTimestamp ? toDateOnly(lastTimestamp) : null;

  let bestId = null;
  let bestCandidateDate = null;

  for (const { articleId, parsed } of regionEntries) {
    if (!parsed) continue;
    if (parsed.slot !== null) continue; // date-only mode ignores slot entries

    let candidateDate = null;

    if (parsed.kind === "full" && parsed.date) {
      const d = parsed.date;
      if (d > todayDate) continue;
      candidateDate = d;
    } else if (parsed.kind === "md") {
      const year = todayDate.getFullYear();
      let d = new Date(year, parsed.month - 1, parsed.day);
      if (d > todayDate) {
        d = new Date(year - 1, parsed.month - 1, parsed.day);
      }
      candidateDate = d;
    } else {
      continue;
    }

    if (!candidateDate) continue;

    if (lastDate && candidateDate <= lastDate) {
      continue;
    }
    if (candidateDate > todayDate) {
      continue;
    }

    if (!bestCandidateDate || candidateDate > bestCandidateDate) {
      bestCandidateDate = candidateDate;
      bestId = articleId;
    }
  }

  if (!bestId) return null;
  return { articleId: bestId, date: bestCandidateDate };
}

/**
 * Load/save cycle state from a JSON log file in the articles root.
 * Shape:
 *   {
 *     "e": { "lastSlot": 5 },
 *     "j": { "lastSlot": 12 }
 *   }
 */
const STATE_FILENAME = "news_cycle_state.json";

function loadCycleState(articlesDir) {
  const p = path.join(articlesDir, STATE_FILENAME);
  if (!fs.existsSync(p)) return {};
  try {
    const raw = fs.readFileSync(p, "utf8");
    const parsed = JSON.parse(raw);
    if (parsed && typeof parsed === "object") return parsed;
  } catch (e) {
    console.error("Failed to parse news cycle state:", e);
  }
  return {};
}

function saveCycleState(articlesDir, state) {
  const p = path.join(articlesDir, STATE_FILENAME);
  const tmp = p + ".tmp";
  fs.writeFileSync(tmp, JSON.stringify(state, null, 2), "utf8");
  fs.renameSync(tmp, p);
}

/**
 * Find a .bin file in an article directory. Returns abs path or null.
 */
function findBinInDir(dir) {
  if (!fs.existsSync(dir) || !fs.statSync(dir).isDirectory()) {
    return null;
  }
  const entries = fs.readdirSync(dir);
  for (const name of entries) {
    if (name.toLowerCase().endsWith(".bin")) {
      const full = path.join(dir, name);
      if (fs.statSync(full).isFile()) {
        return full;
      }
    }
  }
  return null;
}

async function main() {
  const rootDir = path.resolve(__dirname, "..", "..");
  const encoding = loadEncoding(rootDir);
  const newsCfg = loadNewsConfig(rootDir);
  console.log("[news] rootDir:", rootDir);
  console.log("[news] articles_dir:", newsCfg.articles_dir);

  const pool = createPool(mainConfig);

  const todayDate = toDateOnly(new Date());
  console.log(
    "[news] today (server local):",
    todayDate.toISOString().slice(0, 10)
  );
  const cycleState = loadCycleState(newsCfg.articles_dir);

  try {
    const conn = await pool.getConnection();

    try {
      for (const [region, folderName] of Object.entries(
        newsCfg.region_folder_map
      )) {
        const regionDir = path.join(newsCfg.articles_dir, folderName);

        if (
          !fs.existsSync(regionDir) ||
          !fs.statSync(regionDir).isDirectory()
        ) {
          continue; // silently skip missing region folders
        }

        // Look up last bxt_news row for this region.
        const [rows] = await conn.execute(
          "SELECT id, timestamp FROM bxt_news WHERE game_region = ? ORDER BY timestamp DESC LIMIT 1",
          [region]
        );

        let existingId = null;
        let lastTs = null;
        if (rows.length > 0) {
          existingId = rows[0].id;
          lastTs =
            rows[0].timestamp instanceof Date
              ? rows[0].timestamp
              : new Date(rows[0].timestamp);
        }

        const regionScheduleRaw = newsCfg.schedule[region] || {};
        const regionEntries = [];
        let hasSlots = false;

        for (const [articleId, rawEntry] of Object.entries(regionScheduleRaw)) {
          const parsed = parseScheduleEntry(rawEntry);
          if (!parsed) continue;
          if (parsed.slot !== null) hasSlots = true;
          regionEntries.push({ articleId, parsed });
        }

        if (!regionEntries.length) {
          continue;
        }

        let chosenArticleId = null;

        if (hasSlots) {
          // Slot-based multi-year cycle, using JSON log file.
          let lastSlot = -1;
          if (
            cycleState[region] &&
            Number.isInteger(cycleState[region].lastSlot)
          ) {
            lastSlot = cycleState[region].lastSlot;
          }

          let monthsPassed = 0;
          if (!lastTs) {
            // If we have never set news before, treat as 1 month passed
            // to start at slot 0.
            monthsPassed = 1;
          } else {
            monthsPassed = monthsBetween(toDateOnly(lastTs), todayDate);
          }

          if (monthsPassed <= 0) {
            // Nothing to do for this region on this run.
            continue;
          }
          // Determine cycle length from the highest configured slot for this region.
          let maxSlot = -1;
          for (const entry of regionEntries) {
            if (entry.parsed.slot !== null && entry.parsed.slot > maxSlot) {
              maxSlot = entry.parsed.slot;
            }
          }
          const cycleLength = maxSlot >= 0 ? maxSlot + 1 : 24;

          // Clamp to avoid absurd jumps; skip at most one full cycle.
          if (monthsPassed > cycleLength) {
            monthsPassed = cycleLength;
          }

          const nextSlot =
            ((lastSlot >= 0 ? lastSlot : -1) + monthsPassed) % cycleLength;

          // Find article matching this slot.
          let candidate = null;
          for (const entry of regionEntries) {
            if (entry.parsed.slot === nextSlot) {
              candidate = entry;
              break;
            }
          }

          if (!candidate) {
            // No article for this slot; skip.
            continue;
          }

          const mdMonth = candidate.parsed.month;
          const mdDay = candidate.parsed.day;
          if (!mdMonth || !mdDay) {
            continue;
          }

          const todayMonth = todayDate.getMonth() + 1;
          const todayDay = todayDate.getDate();
          const isOnOrAfter =
            todayMonth > mdMonth ||
            (todayMonth === mdMonth && todayDay >= mdDay);

          if (!isOnOrAfter) {
            // Scheduled later in this month; do nothing yet.
            continue;
          }

          chosenArticleId = candidate.articleId;

          // Update cycle state for this region.
          cycleState[region] = { lastSlot: nextSlot };
        } else {
          // Pure date-based mode, no slots.
          const selection = selectArticleForRegionDateOnly(
            regionEntries,
            lastTs,
            todayDate
          );
          if (!selection) {
            continue;
          }
          chosenArticleId = selection.articleId;
          // No cycle state to update in this mode.
        }

        if (!chosenArticleId) {
          continue;
        }

        // Per-article schedule entry for this chosen article (for ranking_categories overrides)
        let chosenEntry = null;
        for (const entry of regionEntries) {
          if (entry.articleId === chosenArticleId) {
            chosenEntry = entry;
            break;
          }
        }
        const chosenRankingCategories =
          chosenEntry && chosenEntry.parsed && chosenEntry.parsed.rankingCategories
            ? chosenEntry.parsed.rankingCategories
            : null;

        const binPath = path.join(regionDir, String(chosenArticleId));
        if (!fs.existsSync(binPath) || !fs.statSync(binPath).isFile()) {
          console.warn(
            `[news] configured article ${chosenArticleId} for region=${region} but file not found at ${binPath}`
          );
          continue; // configured but no .bin present
        }

        const binData = fs.readFileSync(binPath);

        // 1) message + message_decode
        const messageBuf = extractMessageBytes(binData);
        const encTableName =
          newsCfg.region_message_encoding[region] || "en";
        const messageDecode = decodeMessageBytes(
          messageBuf,
          encoding,
          encTableName
        );

        // 2) ranking categories
        const slots = findRankingSlots(binData);
        const rankingNumbers = [null, null, null];
        const rankingDecodes = [null, null, null];

        if (chosenRankingCategories && chosenRankingCategories.length) {
          // Per-article override: ranking_categories array in schedule
          for (let idx = 0; idx < 3; idx++) {
            const catNum =
              idx < chosenRankingCategories.length
                ? chosenRankingCategories[idx]
                : null;
            rankingNumbers[idx] = catNum;
            if (catNum !== null) {
              rankingDecodes[idx] = decodeRankingCategory(
                region,
                catNum,
                encoding,
                newsCfg
              );
            }
          }
        } else {
          // No per-article categories defined: leave rankingNumbers/Decodes null.
          // This means no ranking categories will be set for this news entry.
        }

        // 3) Upsert into bxt_news
        if (existingId != null) {
          await conn.execute(
            "UPDATE bxt_news SET ranking_category_1 = ?, ranking_category_1_decode = ?, " +
              "ranking_category_2 = ?, ranking_category_2_decode = ?, " +
              "ranking_category_3 = ?, ranking_category_3_decode = ?, " +
              "message = ?, message_decode = ?, news_binary = ?, timestamp = CURRENT_TIMESTAMP() " +
              "WHERE id = ?",
            [
              rankingNumbers[0],
              rankingDecodes[0],
              rankingNumbers[1],
              rankingDecodes[1],
              rankingNumbers[2],
              rankingDecodes[2],
              messageBuf,
              messageDecode,
              binData,
              existingId,
            ]
          );
          console.log(
            `Updated bxt_news for region=${region}, id=${existingId}, article=${chosenArticleId}`
          );
        } else {
          const [res] = await conn.execute(
            "INSERT INTO bxt_news " +
              "(game_region, ranking_category_1, ranking_category_1_decode, " +
              " ranking_category_2, ranking_category_2_decode, " +
              " ranking_category_3, ranking_category_3_decode, " +
              " message, message_decode, news_binary) " +
              "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
              region,
              rankingNumbers[0],
              rankingDecodes[0],
              rankingNumbers[1],
              rankingDecodes[1],
              rankingNumbers[2],
              rankingDecodes[2],
              messageBuf,
              messageDecode,
              binData,
            ]
          );
          console.log(
            `Inserted bxt_news for region=${region}, id=${res.insertId}, article=${chosenArticleId}`
          );
        }
      }

      // Persist cycle state (for slot-based regions).
      saveCycleState(newsCfg.articles_dir, cycleState);
    } finally {
      conn.release();
    }
  } finally {
    await pool.end();
  }
}

main().catch((err) => {
  console.error("pokemon-news failed:", err);
  process.exit(1);
});
// ---------- PHP BXT CONFIG: global_table_display ----------

function loadGlobalDisplayRegion(rootDir) {
  try {
    const cfgPath = path.resolve(rootDir, "web", "cgb", "pokemon", "bxt_config.php");
    const txt = fs.readFileSync(cfgPath, "utf8");
    const m = txt.match(/'global_table_display'\s*=>\s*\[([^\]]*)\]/);
    if (!m) return null;

    const inside = m[1];
    const codes = [];
    for (const part of inside.split(",")) {
      const mm = part.match(/'([a-zA-Z])'/);
      if (mm) {
        codes.push(mm[1].toLowerCase());
      }
    }
    if (!codes.length) return null;
    return codes[0];
  } catch (e) {
    return null;
  }
}



const fs = require("fs");
const path = require("path");
const mysql = require("mysql2/promise");
const nodemailer = require("nodemailer");

const { Command } = require("commander");
const { loadBxtConfig } = require("../bxt_config_loader");

// ------------------------------
// Config
// ------------------------------

const defaultPath = path.resolve(__dirname, "..", "..", "config.json");

const program = new Command();
program
  .option("-c, --config <path>", "Config file path.", defaultPath)
  .parse(process.argv);

const options = program.opts();
const config = JSON.parse(fs.readFileSync(options.config, "utf8"));

const phpConfigPath = path.resolve(
  __dirname,
  "..,
  "..",
  "web",
  "cgb",
  "pokemon",
  "bxt_config.php"
);
const bxtConfig = loadBxtConfig(phpConfigPath);

const dbConfig = {
  host: config["mysql_host"],
  port: config["mysql_port"] || 3306,
  user: config["mysql_user"],
  password: config["mysql_password"],
  database: config["mysql_database"],
};

// ------------------------------
// SMTP transport – mirror PHP UserUtil.php config
// ------------------------------

let mailTransport;

const smtpHost = config["smtp_host"];
const smtpPort = config["smtp_port"];
const smtpAuth = config["smtp_auth"];
const smtpSecure = config["smtp_secure"];

if (!smtpHost || smtpHost === "") {
  // Sendmail mode (PHP isSendmail())
  mailTransport = nodemailer.createTransport({
    sendmail: true,
    newline: "unix",
    path: "/usr/sbin/sendmail", // adjust if different on your system
  });
} else {
  // SMTP mode (PHP isSMTP())
  const transportOptions = {
    host: smtpHost,
    port: smtpPort || 587,
    secure: smtpSecure === "smtps", // implicit TLS
    requireTLS: smtpSecure === "starttls", // STARTTLS
    auth: smtpAuth
      ? {
          user: config["smtp_user"],
          pass: config["smtp_pass"],
        }
      : undefined,
    // allow self-signed like your PHP setup
    tls: {
      rejectUnauthorized: false,
    },
  };

  mailTransport = nodemailer.createTransport(transportOptions);
}

// ------------------------------
// Encoding JSON + helpers
// ------------------------------

const ENCODING_JSON_PATH = path.join(
  __dirname,
  "../../web/scripts/bxt_encoding.json"
);
let ENCODING_CACHE = null;

function loadEncodingJson() {
  if (ENCODING_CACHE) return ENCODING_CACHE;
  try {
    const raw = fs.readFileSync(ENCODING_JSON_PATH, "utf8");
    ENCODING_CACHE = JSON.parse(raw);
  } catch (e) {
    ENCODING_CACHE = {};
  }
  return ENCODING_CACHE;
}

function getEncodingTable(id) {
  const cfg = loadEncodingJson();
  if (!cfg || typeof cfg !== "object") return null;
  const t = cfg[id];
  if (!t || typeof t !== "object") return null;
  return t;
}

// One-byte-per-char decode using table id; stops at 0x50/0x00.
function simpleDecodeBytesToText(buf, tableId) {
  if (!Buffer.isBuffer(buf)) return "";
  const table = getEncodingTable(tableId);
  if (!table) return "";
  let out = "";
  for (let i = 0; i < buf.length; i++) {
    const b = buf[i];
    if (b === 0x50 || b === 0x00) break;
    const hex = b.toString(16).toUpperCase().padStart(2, "0");
    const ch = table[hex];
    if (typeof ch === "string") out += ch;
  }
  return out;
}

// Reverse: plain text -> bytes via table; pad with 0x50 to maxBytes.
const SIMPLE_ENCODE_CACHE = new Map();

function simpleEncodeTextToBytes(text, tableId, maxBytes) {
  const table = getEncodingTable(tableId);
  if (!table) return Buffer.alloc(maxBytes, 0x50);

  let reverse = SIMPLE_ENCODE_CACHE.get(tableId);
  if (!reverse) {
    reverse = new Map();
    for (const [hex, val] of Object.entries(table)) {
      if (typeof val === "string" && val.length === 1 && !reverse.has(val)) {
        reverse.set(val, parseInt(hex, 16));
      }
    }
    SIMPLE_ENCODE_CACHE.set(tableId, reverse);
  }

  let fallback = null;
  for (const [hex, val] of Object.entries(table)) {
    if (val === "?") {
      fallback = parseInt(hex, 16);
      break;
    }
  }

  const out = [];
  const src = String(text);
  for (let i = 0; i < src.length && out.length < maxBytes; i++) {
    const ch = src[i];
    if (reverse.has(ch)) {
      out.push(reverse.get(ch));
    } else if (fallback !== null) {
      out.push(fallback);
    }
  }
  while (out.length < maxBytes) out.push(0x50);
  return Buffer.from(out.slice(0, maxBytes));
}

function nameTableForRegion(region) {
  const r = String(region || "").toLowerCase();
  if (r === "e" || r === "p" || r === "u") return "en";
  if (r === "f" || r === "d") return "fr_de";
  if (r === "s" || r === "i") return "es_it";
  if (r === "j") return "jp";
  return null;
}

// ------------------------------
// JP -> EN transliteration
// ------------------------------

let JP_EN_PRIMARY_MAP = null;
let JP_EN_THIRD_MAP = null;

function ensureJpEnMaps() {
  if (JP_EN_PRIMARY_MAP) return;
  const primary = {};

  // digraphs (hiragana + katakana, including F-forms)
  Object.assign(primary, {
    "ぴょ": "PYO",
    "ぴゅ": "PYU",
    "ぴゃ": "PYA",
    "びょ": "BYO",
    "びゅ": "BYU",
    "びゃ": "BYA",
    "じょ": "JO",
    "じゅ": "JU",
    "じゃ": "JA",
    "ぎょ": "GYO",
    "ぎゅ": "GYU",
    "ぎゃ": "GYA",
    "りょ": "RYO",
    "りゅ": "RYU",
    "りゃ": "RYA",
    "みょ": "MYO",
    "みゅ": "MYU",
    "みゃ": "MYA",
    "ひょ": "HYO",
    "ひゅ": "HYU",
    "ひゃ": "HYA",
    "にょ": "NYO",
    "にゅ": "NYU",
    "にゃ": "NYA",
    "ちょ": "CHO",
    "ちゅ": "CHU",
    "ちゃ": "CHA",
    "しょ": "SHO",
    "しゅ": "SHU",
    "しゃ": "SHA",
    "きょ": "KYO",
    "きゅ": "KYU",
    "きゃ": "KYA",

    "ピョ": "PYO",
    "ピュ": "PYU",
    "ピャ": "PYA",
    "ビョ": "BYO",
    "ビュ": "BYU",
    "ビャ": "BYA",
    "ジョ": "JO",
    "ジュ": "JU",
    "ジャ": "JA",
    "ギョ": "GYO",
    "ギュ": "GYU",
    "ギャ": "GYA",
    "リョ": "RYO",
    "リュ": "RYU",
    "リャ": "RYA",
    "ミョ": "MYO",
    "ミュ": "MYU",
    "ミャ": "MYA",
    "ヒョ": "HYO",
    "ヒュ": "HYU",
    "ヒャ": "HYA",
    "ニョ": "NYO",
    "ニュ": "NYU",
    "ニャ": "NYA",
    "チョ": "CHO",
    "チュ": "CHU",
    "チャ": "CHA",
    "ショ": "SHO",
    "シュ": "SHU",
    "シャ": "SHA",
    "キョ": "KYO",
    "キュ": "KYU",
    "キャ": "KYA",

    "ファ": "FA",
    "フィ": "FI",
    "フェ": "FE",
    "フォ": "FO",
  });

  // single kana hiragana
  Object.assign(primary, {
    ぽ: "PO",
    ぺ: "PE",
    ぷ: "PU",
    ぴ: "PI",
    ぱ: "PA",
    ぼ: "BO",
    べ: "BE",
    ぶ: "BU",
    び: "BI",
    ば: "BA",
    ど: "DO",
    で: "DE",
    づ: "ZU",
    ぢ: "JI",
    だ: "DA",
    ぞ: "ZO",
    ぜ: "ZE",
    ず: "ZU",
    じ: "JI",
    ざ: "ZA",
    ご: "GO",
    げ: "GE",
    ぐ: "GU",
    ぎ: "GI",
    が: "GA",
    わ: "WA",
    ろ: "RO",
    れ: "RE",
    る: "RU",
    り: "RI",
    ら: "RA",
    よ: "YO",
    ゆ: "YU",
    や: "YA",
    も: "MO",
    め: "ME",
    む: "MU",
    み: "MI",
    ま: "MA",
    ほ: "HO",
    へ: "HE",
    ふ: "FU",
    ひ: "HI",
    は: "HA",
    の: "NO",
    ね: "NE",
    ぬ: "NU",
    に: "NI",
    な: "NA",
    と: "TO",
    て: "TE",
    つ: "TSU",
    ち: "CHI",
    た: "TA",
    そ: "SO",
    せ: "SE",
    す: "SU",
    さ: "SA",
    こ: "KO",
    け: "KE",
    く: "KU",
    き: "KI",
    か: "KA",
  });

  // single kana katakana
  Object.assign(primary, {
    ポ: "PO",
    ペ: "PE",
    プ: "PU",
    ピ: "PI",
    パ: "PA",
    ボ: "BO",
    ベ: "BE",
    ブ: "BU",
    ビ: "BI",
    バ: "BA",
    ド: "DO",
    デ: "DE",
    ヅ: "ZU",
    ヂ: "JI",
    ダ: "DA",
    ゾ: "ZO",
    ゼ: "ZE",
    ズ: "ZU",
    ジ: "JI",
    ザ: "ZA",
    ゴ: "GO",
    ゲ: "GE",
    グ: "GU",
    ギ: "GI",
    ガ: "GA",
    ワ: "WA",
    ロ: "RO",
    レ: "RE",
    ル: "RU",
    リ: "RI",
    ラ: "RA",
    ヨ: "YO",
    ユ: "YU",
    ヤ: "YA",
    モ: "MO",
    メ: "ME",
    ム: "MU",
    ミ: "MI",
    マ: "MA",
    ホ: "HO",
    ヘ: "HE",
    フ: "FU",
    ヒ: "HI",
    ハ: "HA",
    ノ: "NO",
    ネ: "NE",
    ヌ: "NU",
    ニ: "NI",
    ナ: "NA",
    ト: "TO",
    テ: "TE",
    タ: "TA",
    ソ: "SO",
    セ: "SE",
    ス: "SU",
    サ: "SA",
    コ: "KO",
    ケ: "KE",
    ク: "KU",
    キ: "KI",
    カ: "KA",
    ン: "N",
    ん: "N",
    ヲ: "O",
    を: "O",
    お: "O",
    え: "E",
    う: "U",
    い: "I",
    あ: "A",
    オ: "O",
    エ: "E",
    ウ: "U",
    イ: "I",
    ア: "A",
  });

  const third = { ...primary };
  third["ツ"] = "SU";
  third["チ"] = "CH";
  third["シ"] = "SH";

  JP_EN_PRIMARY_MAP = primary;
  JP_EN_THIRD_MAP = third;
}

function splitCodepoints(str) {
  return Array.from(str || "");
}

function jpToEnCore(jp, map, useSpecialRules) {
  if (!jp) return "";
  const chars = splitCodepoints(jp);
  let out = "";
  for (let i = 0; i < chars.length; i++) {
    const ch = chars[i];

    // small tsu
    if (useSpecialRules && (ch === "っ" || ch === "ッ")) {
      let romajiNext = "";
      for (let j = i + 1; j < chars.length; j++) {
        const next = chars[j];
        const pair = j + 1 < chars.length ? next + chars[j + 1] : null;
        if (pair && map[pair]) {
          romajiNext = map[pair];
          break;
        } else if (map[next]) {
          romajiNext = map[next];
          break;
        }
      }
      if (romajiNext) {
        romajiNext = romajiNext.toUpperCase();
        const first = romajiNext[0];
        if (first >= "A" && first <= "Z") out += first;
      }
      continue;
    }

    // long vowel mark
    if (useSpecialRules && ch === "ー") {
      if (out.length > 0) {
        const last = out[out.length - 1];
        if (last >= "A" && last <= "Z") out += last;
      }
      continue;
    }

    let romaji = null;
    if (i + 1 < chars.length) {
      const pair = chars[i] + chars[i + 1];
      if (map[pair]) {
        romaji = map[pair];
        i++;
      }
    }
    if (!romaji) {
      if (map[ch]) {
        romaji = map[ch];
      } else {
        continue;
      }
    }
    out += String(romaji).toUpperCase();
  }
  out = out.toUpperCase().replace(/[^A-Z]/g, "");
  return out;
}

// Three-pass JP->EN for names
function transliterateJpToEnName(jp, maxChars) {
  ensureJpEnMaps();
  const out1 = jpToEnCore(jp, JP_EN_PRIMARY_MAP, true);
  if (out1.length <= maxChars) return out1;
  const out2 = jpToEnCore(jp, JP_EN_PRIMARY_MAP, false);
  if (out2.length <= maxChars) return out2;
  const out3 = jpToEnCore(jp, JP_EN_THIRD_MAP, false);
  if (out3.length <= maxChars) return out3;
  return out3.slice(0, maxChars);
}

// ------------------------------
// EN -> JP transliteration
// ------------------------------

let EN_JP_MAP3 = null;
let EN_JP_MAP2 = null;
let EN_JP_MAP1 = null;

function ensureEnJpMaps() {
  if (EN_JP_MAP1) return;
  EN_JP_MAP3 = {
    KYA: "キャ",
    KYU: "キュ",
    KYO: "キョ",
    SHA: "シャ",
    SHI: "シ",
    SHU: "シュ",
    SHO: "ショ",
    CHA: "チャ",
    CHI: "チ",
    CHU: "チュ",
    CHO: "チョ",
    NYA: "ニャ",
    NYU: "ニュ",
    NYO: "ニョ",
    HYA: "ヒャ",
    HYU: "ヒュ",
    HYO: "ヒョ",
    MYA: "ミャ",
    MYU: "ミュ",
    MYO: "ミョ",
    RYA: "リャ",
    RYU: "リュ",
    RYO: "リョ",
    GYA: "ギャ",
    GYU: "ギュ",
    GYO: "ギョ",
    BYA: "ビャ",
    BYU: "ビュ",
    BYO: "ビョ",
    PYA: "ピャ",
    PYU: "ピュ",
    PYO: "ピョ",
    TSU: "ツ",
  };
  EN_JP_MAP2 = {
    JA: "ジャ",
    JE: "ジェ",
    JI: "ジ",
    JO: "ジョ",
    JU: "ジュ",
    FA: "ファ",
    FI: "フィ",
    FE: "フェ",
    FO: "フォ",
    VA: "ヴァ",
    VI: "ヴィ",
    VE: "ヴェ",
    VO: "ヴォ",
    WI: "ウィ",
    WE: "ウェ",
    WO: "ウォ",
    TI: "ティ",
    DI: "ディ",
    TU: "トゥ",
    DU: "ドゥ",
    SI: "シ",
    ZI: "ジ",
    ME: "メ",
  };
  EN_JP_MAP1 = {
    A: "ア",
    I: "イ",
    U: "ウ",
    E: "エ",
    O: "オ",
    B: "ブ",
    C: "ク",
    D: "ド",
    F: "フ",
    G: "グ",
    H: "ハ",
    J: "ジ",
    K: "ク",
    L: "ル",
    M: "ム",
    N: "ン",
    P: "プ",
    Q: "ク",
    R: "ル",
    S: "ス",
    T: "ト",
    V: "ヴ",
    W: "ウ",
    X: "ク",
    Y: "イ",
    Z: "ズ",
  };
}

function normalizeLatinForJp(str) {
  if (!str) return "";
  let s = String(str);
  const map = {
    ß: "SS",
    ä: "AE",
    ö: "OE",
    ü: "UE",
    Ä: "AE",
    Ö: "OE",
    Ü: "UE",
    é: "E",
    è: "E",
    ê: "E",
    ë: "E",
    É: "E",
    È: "E",
    Ê: "E",
    Ë: "E",
    á: "A",
    à: "A",
    â: "A",
    ã: "A",
    å: "A",
    Á: "A",
    À: "A",
    Â: "A",
    Ã: "A",
    Å: "A",
    í: "I",
    ì: "I",
    î: "I",
    ï: "I",
    Í: "I",
    Ì: "I",
    Î: "I",
    Ï: "I",
    ó: "O",
    ò: "O",
    ô: "O",
    õ: "O",
    ö: "O",
    Ó: "O",
    Ò: "O",
    Ô: "O",
    Õ: "O",
    Ö: "O",
    ú: "U",
    ù: "U",
    û: "U",
    ü: "U",
    Ú: "U",
    Ù: "U",
    Û: "U",
    Ü: "U",
    ç: "C",
    Ç: "C",
    ñ: "N",
    Ñ: "N",
  };
  return Array.from(s).map((ch) => map[ch] || ch).join("");
}

function enToJpKatakana(en, maxChars) {
  ensureEnJpMaps();
  let s = String(en || "").toUpperCase().replace(/[^A-Z]/g, "");
  if (!s || maxChars <= 0) return "";
  const chars = s.split("");
  const out = [];
  let i = 0;
  while (i < chars.length && out.length < maxChars) {
    const remaining = chars.length - i;
    if (remaining >= 3) {
      const tri = chars[i] + chars[i + 1] + chars[i + 2];
      if (EN_JP_MAP3[tri]) {
        out.push(EN_JP_MAP3[tri]);
        i += 3;
        continue;
      }
    }
    if (remaining >= 2) {
      const di = chars[i] + chars[i + 1];
      if (EN_JP_MAP2[di]) {
        out.push(EN_JP_MAP2[di]);
        i += 2;
        continue;
      }
    }
    const ch = chars[i];
    out.push(EN_JP_MAP1[ch] || "ア");
    i++;
  }
  if (out.length > maxChars) out.length = maxChars;
  return out.join("");
}

function transliterateEnToJpName(en, maxChars) {
  const norm = normalizeLatinForJp(en);
  return enToJpKatakana(norm, maxChars);
}

// ------------------------------
// Pokémon default name helpers
// ------------------------------

function loadDefaultPokemonNameBytes(region, species) {
  const cfg = loadEncodingJson();
  const table =
    cfg &&
    cfg["pokemon_default_names"] &&
    cfg["pokemon_default_names"][String(region).toLowerCase()];
  if (!table) return null;
  const entry = table[String(species)];
  if (!entry) return null;
  let parts = [];
  if (Array.isArray(entry)) {
    parts = entry;
  } else if (typeof entry === "string") {
    parts = entry.trim().split(/\s+/);
  } else {
    return null;
  }
  const bytes = [];
  for (const h of parts) {
    const hex = String(h).trim();
    if (!hex) continue;
    const v = parseInt(hex, 16);
    if (!Number.isNaN(v)) bytes.push(v);
  }
  return Buffer.from(bytes);
}

function trimNameBytes(buf) {
  if (!Buffer.isBuffer(buf)) return Buffer.alloc(0);
  let end = buf.length;
  while (end > 0) {
    const b = buf[end - 1];
    if (b === 0x50 || b === 0x00) end--;
    else break;
  }
  return buf.slice(0, end);
}

function isDefaultPokemonName(sourceRegion, species, nicknameBytes) {
  const def = loadDefaultPokemonNameBytes(sourceRegion, species);
  if (!def) return false;
  const nickTrim = trimNameBytes(nicknameBytes);
  const defTrim = trimNameBytes(def);
  return nickTrim.length > 0 && nickTrim.equals(defTrim);
}

function convertDefaultPokemonNameBetweenRegions(
  sourceRegion,
  destRegion,
  species,
  nicknameBytes
) {
  if (!isDefaultPokemonName(sourceRegion, species, nicknameBytes)) return null;
  const destDef = loadDefaultPokemonNameBytes(destRegion, species);
  if (!destDef) return null;
  return destDef;
}

function convertPokemonNicknameForDownload(
  destRegion,
  sourceRegion,
  species,
  nicknameBytes,
  destMaxBytes
) {
  const srcR = String(sourceRegion || "").toLowerCase();
  const dstR = String(destRegion || "").toLowerCase();

  const mappedDefault = convertDefaultPokemonNameBetweenRegions(
    srcR,
    dstR,
    species,
    nicknameBytes
  );
  if (mappedDefault) {
    let buf = Buffer.from(mappedDefault);
    if (buf.length > destMaxBytes) buf = buf.slice(0, destMaxBytes);
    if (buf.length < destMaxBytes) {
      buf = Buffer.concat([buf, Buffer.alloc(destMaxBytes - buf.length, 0x50)]);
    }
    return buf;
  }

  const isSourceJ = srcR === "j";
  const isDestJ = dstR === "j";

  if (isSourceJ && !isDestJ) {
    const jp = simpleDecodeBytesToText(nicknameBytes, "jp");
    if (!jp) return nicknameBytes;
    const ascii = transliterateJpToEnName(jp, destMaxBytes);
    const tableId = nameTableForRegion(destRegion);
    if (!tableId) return nicknameBytes;
    return simpleEncodeTextToBytes(ascii, tableId, destMaxBytes);
  }

  if (!isSourceJ && isDestJ) {
    const srcTable = nameTableForRegion(sourceRegion);
    if (!srcTable) return nicknameBytes;
    const plain = simpleDecodeBytesToText(nicknameBytes, srcTable);
    if (!plain) return nicknameBytes;
    const kat = transliterateEnToJpName(plain, destMaxBytes);
    if (!kat) return nicknameBytes;
    return simpleEncodeTextToBytes(kat, "jp", destMaxBytes);
  }

  // non-J <-> non-J: unchanged except padding
  let buf = Buffer.from(nicknameBytes);
  if (buf.length > destMaxBytes) buf = buf.slice(0, destMaxBytes);
  if (buf.length < destMaxBytes) {
    buf = Buffer.concat([buf, Buffer.alloc(destMaxBytes - buf.length, 0x50)]);
  }
  return buf;
}

function convertPlayerNameForDownload(
  destRegion,
  sourceRegion,
  nameBytes,
  destMaxBytes
) {
  const srcR = String(sourceRegion || "").toLowerCase();
  const dstR = String(destRegion || "").toLowerCase();
  if (srcR === dstR) {
    let buf = Buffer.from(nameBytes);
    if (buf.length > destMaxBytes) buf = buf.slice(0, destMaxBytes);
    if (buf.length < destMaxBytes) {
      buf = Buffer.concat([buf, Buffer.alloc(destMaxBytes - buf.length, 0x50)]);
    }
    return buf;
  }

  const isSourceJ = srcR === "j";
  const isDestJ = dstR === "j";

  if (isSourceJ && !isDestJ) {
    const jp = simpleDecodeBytesToText(nameBytes, "jp");
    if (!jp) return nameBytes;
    const ascii = transliterateJpToEnName(jp, destMaxBytes);
    const tableId = nameTableForRegion(destRegion);
    if (!tableId) return nameBytes;
    return simpleEncodeTextToBytes(ascii, tableId, destMaxBytes);
  }

  if (!isSourceJ && isDestJ) {
    const srcTable = nameTableForRegion(sourceRegion);
    if (!srcTable) return nameBytes;
    const plain = simpleDecodeBytesToText(nameBytes, srcTable);
    if (!plain) return nameBytes;
    const kat = transliterateEnToJpName(plain, destMaxBytes);
    if (!kat) return nameBytes;
    return simpleEncodeTextToBytes(kat, "jp", destMaxBytes);
  }

  let buf = Buffer.from(nameBytes);
  if (buf.length > destMaxBytes) buf = buf.slice(0, destMaxBytes);
  if (buf.length < destMaxBytes) {
    buf = Buffer.concat([buf, Buffer.alloc(destMaxBytes - buf.length, 0x50)]);
  }
  return buf;
}

// ------------------------------
// Trade payload transform
// ------------------------------

function transformExchangePayloadForEmail(destRegion, sourceRegion, row) {
  const dstR = String(destRegion || "").toLowerCase();
  const srcR = String(sourceRegion || "").toLowerCase();

  const isSourceJ = srcR === "j";
  const isDestJ = dstR === "j";

  let trainerName = row["player_name"];
  let pokemon = row["pokemon"];
  let mail = row["mail"];

  if (!Buffer.isBuffer(trainerName))
    trainerName = Buffer.from(trainerName || "", "binary");
  if (!Buffer.isBuffer(pokemon))
    pokemon = Buffer.from(pokemon || "", "binary");
  if (!Buffer.isBuffer(mail)) mail = Buffer.from(mail || "", "binary");

  if (srcR === dstR || (!isSourceJ && !isDestJ)) {
    return { trainerName, pokemon, mail };
  }

  const destPlayerNameBytes = isDestJ ? 5 : 7;
  trainerName = convertPlayerNameForDownload(
    destRegion,
    sourceRegion,
    trainerName,
    destPlayerNameBytes
  );

  if (pokemon.length > 0) {
    const species = pokemon[0];

    const otOffset = 0x30;
    const otLenSource = isSourceJ ? 5 : 7;
    const otLenDest = isDestJ ? 5 : 7;

    if (pokemon.length >= otOffset + otLenSource) {
      const otSlice = pokemon.slice(otOffset, otOffset + otLenSource);
      const newOt = convertPlayerNameForDownload(
        destRegion,
        sourceRegion,
        otSlice,
        otLenDest
      );
      const writeLen = Math.min(otLenSource, newOt.length);
      newOt.copy(pokemon, otOffset, 0, writeLen);
    }

    const nickOffsetSrc = isSourceJ ? 0x35 : 0x37;
    const nickLenSrc = isSourceJ ? 5 : 10;
    const nickLenDest = isDestJ ? 5 : 10;

    if (pokemon.length >= nickOffsetSrc + nickLenSrc) {
      const nickSlice = pokemon.slice(nickOffsetSrc, nickOffsetSrc + nickLenSrc);
      const newNick = convertPokemonNicknameForDownload(
        destRegion,
        sourceRegion,
        species,
        nickSlice,
        nickLenDest
      );
      const writeLen = Math.min(nickLenSrc, newNick.length);
      newNick.copy(pokemon, nickOffsetSrc, 0, writeLen);
    }
  }

  if (mail.length > 0) {
    const textOffsetSrc = 0x00;
    const textLenSrc = isSourceJ ? 0x1c : 0x21;
    const nameOffsetSrc = isSourceJ ? 0x1c : 0x21;
    const nameLenSrc = isSourceJ ? 5 : 7;

    const textLenDest = isDestJ ? 0x1c : 0x21;
    const nameOffsetDest = isDestJ ? 0x1c : 0x21;
    const nameLenDest = isDestJ ? 5 : 7;

    if (mail.length >= textOffsetSrc + textLenSrc) {
      const textSlice = mail.slice(textOffsetSrc, textOffsetSrc + textLenSrc);
      let plain;
      if (isSourceJ) {
        plain = simpleDecodeBytesToText(textSlice, "jp");
        if (plain) {
          const ascii = transliterateJpToEnName(plain, textLenDest);
          const tableId = nameTableForRegion(destRegion);
          if (tableId) {
            const enc = simpleEncodeTextToBytes(ascii, tableId, textLenDest);
            const writeLen = Math.min(textLenSrc, enc.length);
            enc.copy(mail, textOffsetSrc, 0, writeLen);
          }
        }
      } else if (isDestJ) {
        const srcTable = nameTableForRegion(sourceRegion);
        if (srcTable) {
          plain = simpleDecodeBytesToText(textSlice, srcTable);
          if (plain) {
            const kat = transliterateEnToJpName(plain, textLenDest);
            if (kat) {
              const enc = simpleEncodeTextToBytes(kat, "jp", textLenDest);
              const writeLen = Math.min(textLenSrc, enc.length);
              enc.copy(mail, textOffsetSrc, 0, writeLen);
            }
          }
        }
      }
    }

    if (mail.length >= nameOffsetSrc + nameLenSrc) {
      const nameSlice = mail.slice(nameOffsetSrc, nameOffsetSrc + nameLenSrc);
      const newName = convertPlayerNameForDownload(
        destRegion,
        sourceRegion,
        nameSlice,
        nameLenDest
      );
      const writeLen = Math.min(nameLenSrc, newName.length);
      newName.copy(mail, nameOffsetSrc, 0, writeLen);
    }
  }

  return { trainerName, pokemon, mail };
}

// ------------------------------
// Region groups from PHP config
// ------------------------------

const TRADE_CORNER_ENABLED = !!bxtConfig.trade_corner_enabled;

function loadTradeRegionGroupsFromPhpConfig(phpPath) {
  try {
    const php = fs.readFileSync(phpPath, "utf8");

    let tradeKey = "'trade_corner' => [";
    let start = php.indexOf(tradeKey);
    if (start === -1) {
      tradeKey = "'trade' => [";
      start = php.indexOf(tradeKey);
    }
    if (start === -1) {
      return [];
    }

    let end = php.indexOf("'battle_tower'", start);
    if (end === -1) {
      end = start + 512;
      if (end > php.length) end = php.length;
    }

    const block = php.slice(start, end);

    const groups = [];
    const arrayRegex = /\[([^\]]+)\]/g;
    let m;
    while ((m = arrayRegex.exec(block)) !== null) {
      const inner = m[1];
      const codes = [];
      const codeRegex = /'([^']+)'/g;
      let m2;
      while ((m2 = codeRegex.exec(inner)) !== null) {
        codes.push(String(m2[1]));
      }
      if (codes.length) groups.push(codes);
    }
    return groups;
  } catch (e) {
    console.error("Failed to load trade region groups from PHP config:", e);
    return [];
  }
}

const TRADE_REGION_GROUPS = loadTradeRegionGroupsFromPhpConfig(phpConfigPath);

function regionCanTrade(a, b) {
  if (!a || !b) return false;
  a = String(a).toLowerCase();
  b = String(b).toLowerCase();
  if (a === b) return true;
  for (const group of TRADE_REGION_GROUPS) {
    if (!Array.isArray(group)) continue;
    const g = group.map((x) => String(x).toLowerCase());
    if (g.includes(a) && g.includes(b)) return true;
  }
  return false;
}

// ------------------------------
// X-Game-result construction (legacy layout)
// ------------------------------

function toHexString(value, byteLength) {
  let v = Number(value) >>> 0;
  let s = v.toString(16).toUpperCase();
  while (s.length < byteLength * 2) s = "0" + s;
  return s;
}

// ------------------------------
// Email + main exchange logic
// ------------------------------

async function sendExchangeSuccessEmail(
  region,
  emailAddress,
  trainerId,
  secretId,
  offerSpecies,
  requestSpecies,
  offerGender,
  requestGender,
  trainerName,
  pokemon,
  mail
) {
  const r = String(region || "e").toUpperCase();

  const tidHex = toHexString(trainerId, 2);
  const sidHex = toHexString(secretId, 2);
  const offerGenderHex = toHexString(offerGender, 1);
  const offerSpeciesHex = toHexString(offerSpecies, 1);
  const requestGenderHex = toHexString(requestGender, 1);
  const requestSpeciesHex = toHexString(requestSpecies, 1);

  // NOTE: changed to put values on the line *after* the header name,
  // so the game sees the expected "X-Game-*-prefix:\n" then data.
  const header =
    "MIME-Version: 1.0\r\n" +
    "From: MISSINGNO.\r\n" +
    "Subject: Trade\r\n" +
    "X-Game-title:\r\n" +
    "POCKET MONSTERS\r\n" +
    "X-Game-code:\r\n" +
    `CGB-BXT${r}-00\r\n` +
    "X-Game-result:\r\n" +
    `1 ${tidHex}${sidHex} ${offerGenderHex}${offerSpeciesHex} ${requestGenderHex}${requestSpeciesHex} 1\r\n` +
    "X-GBmail-type: exclusive\r\n" +
    "Content-Type: application/octet-stream\r\n" +
    "Content-Transfer-Encoding: base64\r\n" +
    "\r\n";

  const body =
    Buffer.concat([trainerName, pokemon, mail]).toString("base64") +
    "\r\n\r\n";

  const raw = header + body;

  await mailTransport.sendMail({
    envelope: {
      from: "system@" + config["email_domain"],
      to: emailAddress,
    },
    raw: raw,
  });
}

async function doExchange() {
  const connection = await mysql.createConnection(dbConfig);

  try {
    if (!TRADE_CORNER_ENABLED) {
      console.log("Trade Corner is disabled; skipping exchange run.");
      await connection.end();
      return;
    }

    await connection.beginTransaction();

    const table = "bxt_exchange";

    await connection.execute(
      "DELETE FROM " + table + " WHERE timestamp < NOW() - INTERVAL 7 DAY"
    );

    const [trades] = await connection.execute(
      "SELECT * FROM " + table + " ORDER BY timestamp ASC"
    );

    const performedTrades = new Set();
    let numTrades = 0;

    for (let i = 0; i < trades.length; i++) {
      if (performedTrades.has(i)) continue;

      for (let j = i + 1; j < trades.length; j++) {
        if (performedTrades.has(j)) continue;

        const a = trades[i];
        const b = trades[j];

        if (
          regionCanTrade(a["game_region"], b["game_region"]) &&
          a["offer_species"] == b["request_species"] &&
          a["request_species"] == b["offer_species"] &&
          (a["offer_gender"] == b["request_gender"] ||
            b["request_gender"] == 3) &&
          (a["request_gender"] == b["offer_gender"] ||
            a["request_gender"] == 3)
        ) {
          performedTrades.add(i);
          performedTrades.add(j);
          numTrades++;

          // For player B: use B's own metadata in header, A's payload.
          const payloadForB = transformExchangePayloadForEmail(
            b["game_region"],
            a["game_region"],
            a
          );
          await sendExchangeSuccessEmail(
            b["game_region"],
            b["email"],
            b["trainer_id"],
            b["secret_id"],
            b["offer_species"],
            b["request_species"],
            b["offer_gender"],
            b["request_gender"],
            payloadForB.trainerName,
            payloadForB.pokemon,
            payloadForB.mail
          );

          // For player A: use A's own metadata in header, B's payload.
          const payloadForA = transformExchangePayloadForEmail(
            a["game_region"],
            b["game_region"],
            b
          );
          await sendExchangeSuccessEmail(
            a["game_region"],
            a["email"],
            a["trainer_id"],
            a["secret_id"],
            a["offer_species"],
            a["request_species"],
            a["offer_gender"],
            a["request_gender"],
            payloadForA.trainerName,
            payloadForA.pokemon,
            payloadForA.mail
          );

          await connection.execute(
            "DELETE FROM " +
              table +
              " WHERE email = ? AND account_id = ? AND trainer_id = ? AND secret_id = ? LIMIT 1",
            [b["email"], b["account_id"], b["trainer_id"], b["secret_id"]]
          );
          await connection.execute(
            "DELETE FROM " +
              table +
              " WHERE email = ? AND account_id = ? AND trainer_id = ? AND secret_id = ? LIMIT 1",
            [a["email"], a["account_id"], a["trainer_id"], a["secret_id"]]
          );

          break;
        }
      }
    }

    await connection.commit();
    console.log(`Finished exchange; performed ${numTrades} trade(s)`);
  } catch (e) {
    console.error("Exchange failed, rolling back:", e);
    try {
      await connection.rollback();
    } catch (_) {}
  } finally {
    try {
      await connection.end();
    } catch (_) {}
  }
}

doExchange();

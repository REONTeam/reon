const fs = require("fs");
const path = require("path");

/**
 * Small loader for web/cgb/pokemon/bxt_config.php.
 *
 * It does NOT execute PHP; it reads the file as text and extracts a few
 * specific configuration sections needed by the Node tools:
 *
 *   - trade_corner_enabled
 *   - battle_tower_enabled
 *   - feature_allowed_regions.trade
 *   - feature_allowed_regions.battle_tower
 *   - region_groups.trade_corner
 *   - region_groups.battle_tower
 *
 * If parsing fails at any point, sensible defaults are returned.
 */

function parsePhpStringArray(body) {
    const out = [];
    if (!body) return out;
    const re = /'([^']+)'/g;
    let m;
    while ((m = re.exec(body)) !== null) {
        out.push(m[1]);
    }
    return out;
}

function parsePools(body) {
    const pools = [];
    if (!body) return pools;
    const poolRe = /\[([^\]]*?)\]/g;
    let m;
    while ((m = poolRe.exec(body)) !== null) {
        const arr = parsePhpStringArray(m[1]);
        if (arr.length) pools.push(arr);
    }
    return pools;
}

function loadBxtConfig(bxtConfigPath) {
    const fullPath = bxtConfigPath || path.resolve(__dirname, "..", "web", "cgb", "pokemon", "bxt_config.php");
    let text;
    try {
        text = fs.readFileSync(fullPath, "utf8");
    } catch (e) {
        // Hard-coded defaults if the PHP file is missing.
        return {
            trade_corner_enabled: true,
            battle_tower_enabled: true,
            feature_allowed_regions: {
                trade: ["e","f","d","s","i","j","p","u"],
                battle_tower: ["e","f","d","s","i","j","p","u"],
            },
            // Empty pools => each region links only with itself.
            region_groups: {
                trade_corner: [],
                battle_tower: [],
            },
        };
    }

    const cfg = {
        trade_corner_enabled: true,
        battle_tower_enabled: true,
        feature_allowed_regions: {},
        region_groups: {},
    };

    // trade_corner_enabled / battle_tower_enabled
    const mTradeCorner = text.match(/'trade_corner_enabled'\s*=>\s*(true|false)/);
    if (mTradeCorner) cfg.trade_corner_enabled = mTradeCorner[1] === "true";

    const mBattleTower = text.match(/'battle_tower_enabled'\s*=>\s*(true|false)/);
    if (mBattleTower) cfg.battle_tower_enabled = mBattleTower[1] === "true";

    // feature_allowed_regions block
    const mFeat = text.match(/'feature_allowed_regions'\s*=>\s*\[([\s\S]*?)\]\s*,\s*\/\/ Region groups/m);
    if (mFeat) {
        const body = mFeat[1];
        const mTrade = body.match(/'trade'\s*=>\s*\[([\s\S]*?)\]/);
        if (mTrade) {
            cfg.feature_allowed_regions.trade = parsePhpStringArray(mTrade[1]);
        }
        const mBT = body.match(/'battle_tower'\s*=>\s*\[([\s\S]*?)\]/);
        if (mBT) {
            cfg.feature_allowed_regions.battle_tower = parsePhpStringArray(mBT[1]);
        }
    }

    // region_groups block
    const mGroups = text.match(/'region_groups'\s*=>\s*\[([\s\S]*?)\]\s*,\s*];/m);
    if (mGroups) {
        const body = mGroups[1];

        const mTradeGroup = body.match(/'trade_corner'\s*=>\s*\[([\s\S]*?)\],\s*'battle_tower'/m);
        if (mTradeGroup) {
            cfg.region_groups.trade_corner = parsePools(mTradeGroup[1]);
        }

        const mBTGroup = body.match(/'battle_tower'\s*=>\s*\[([\s\S]*?)\],\s*'news'/m);
        if (mBTGroup) {
            cfg.region_groups.battle_tower = parsePools(mBTGroup[1]);
        }
    }

    // Defaults for any missing fields.
    if (!Array.isArray(cfg.feature_allowed_regions.trade) || cfg.feature_allowed_regions.trade.length === 0) {
        cfg.feature_allowed_regions.trade = ["e","f","d","s","i","j","p","u"];
    }
    if (!Array.isArray(cfg.feature_allowed_regions.battle_tower) || cfg.feature_allowed_regions.battle_tower.length === 0) {
        cfg.feature_allowed_regions.battle_tower = ["e","f","d","s","i","j","p","u"];
    }

    // Empty pools mean "no cross-region pooling", i.e. each region only links
    // with itself.  If PHP config defines explicit pools, we honour them.
    if (!Array.isArray(cfg.region_groups.trade_corner)) {
        cfg.region_groups.trade_corner = [];
    }
    if (!Array.isArray(cfg.region_groups.battle_tower)) {
        cfg.region_groups.battle_tower = [];
    }

    return cfg;
}

module.exports = { loadBxtConfig };

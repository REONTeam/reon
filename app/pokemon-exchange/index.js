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
  "..",
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
// Encoding + Pokémon name tables (embedded)
// ------------------------------

const ENCODING_TABLES = {
  "en": {
    "54": "POKé",
    "70": "PO",
    "71": "Ké",
    "72": "“",
    "73": "”",
    "75": "…",
    "7F": " ",
    "80": "A",
    "81": "B",
    "82": "C",
    "83": "D",
    "84": "E",
    "85": "F",
    "86": "G",
    "87": "H",
    "88": "I",
    "89": "J",
    "8A": "K",
    "8B": "L",
    "8C": "M",
    "8D": "N",
    "8E": "O",
    "8F": "P",
    "90": "Q",
    "91": "R",
    "92": "S",
    "93": "T",
    "94": "U",
    "95": "V",
    "96": "W",
    "97": "X",
    "98": "Y",
    "99": "Z",
    "9A": "(",
    "9B": ")",
    "9C": ":",
    "9D": ";",
    "9E": "[",
    "9F": "]",
    "A0": "a",
    "A1": "b",
    "A2": "c",
    "A3": "d",
    "A4": "e",
    "A5": "f",
    "A6": "g",
    "A7": "h",
    "A8": "i",
    "A9": "j",
    "AA": "k",
    "AB": "l",
    "AC": "m",
    "AD": "n",
    "AE": "o",
    "AF": "p",
    "B0": "q",
    "B1": "r",
    "B2": "s",
    "B3": "t",
    "B4": "u",
    "B5": "v",
    "B6": "w",
    "B7": "x",
    "B8": "y",
    "B9": "z",
    "C0": "Ä",
    "C1": "Ö",
    "C2": "Ü",
    "C3": "ä",
    "C4": "ö",
    "C5": "ü",
    "E0": "'",
    "E1": "P",
    "E2": "M",
    "E3": "-",
    "E6": "?",
    "E7": "!",
    "E8": ".",
    "E9": "&",
    "EA": "é",
    "EB": "➞",
    "EC": "▷",
    "ED": "▶",
    "EE": "▼",
    "EF": "♂",
    "F0": "¥",
    "F1": "x",
    "F2": ".",
    "F3": "/",
    "F4": ",",
    "F5": "♀",
    "F6": "0",
    "F7": "1",
    "F8": "2",
    "F9": "3",
    "FA": "4",
    "FB": "5",
    "FC": "6",
    "FD": "7",
    "FE": "8",
    "FF": "9",
    "50": "",
    "00": ""
  },
  "fr_de": {
    "54": "POKé",
    "70": "PO",
    "71": "Ké",
    "72": "“",
    "73": "”",
    "75": "…",
    "7F": " ",
    "80": "A",
    "81": "B",
    "82": "C",
    "83": "D",
    "84": "E",
    "85": "F",
    "86": "G",
    "87": "H",
    "88": "I",
    "89": "J",
    "8A": "K",
    "8B": "L",
    "8C": "M",
    "8D": "N",
    "8E": "O",
    "8F": "P",
    "90": "Q",
    "91": "R",
    "92": "S",
    "93": "T",
    "94": "U",
    "95": "V",
    "96": "W",
    "97": "X",
    "98": "Y",
    "99": "Z",
    "9A": "(",
    "9B": ")",
    "9C": ":",
    "9D": ";",
    "9E": "[",
    "9F": "]",
    "A0": "a",
    "A1": "b",
    "A2": "c",
    "A3": "d",
    "A4": "e",
    "A5": "f",
    "A6": "g",
    "A7": "h",
    "A8": "i",
    "A9": "j",
    "AA": "k",
    "AB": "l",
    "AC": "m",
    "AD": "n",
    "AE": "o",
    "AF": "p",
    "B0": "q",
    "B1": "r",
    "B2": "s",
    "B3": "t",
    "B4": "u",
    "B5": "v",
    "B6": "w",
    "B7": "x",
    "B8": "y",
    "B9": "z",
    "BA": "à",
    "BB": "è",
    "BC": "é",
    "BD": "ù",
    "BE": "ß",
    "BF": "ç",
    "C0": "Ä",
    "C1": "Ö",
    "C2": "Ü",
    "C3": "ä",
    "C4": "ö",
    "C5": "ü",
    "C6": "ë",
    "C7": "ï",
    "C8": "â",
    "C9": "ô",
    "CA": "û",
    "CB": "ê",
    "CC": "î",
    "D0": "c'",
    "D1": "d'",
    "D2": "j'",
    "D3": "l'",
    "D4": "m'",
    "D5": "n'",
    "D6": "p'",
    "D7": "s'",
    "D8": "'s",
    "D9": "t'",
    "DA": "u'",
    "DB": "y'",
    "E0": "'",
    "E1": "PK",
    "E2": "MN",
    "E3": "-",
    "E4": "+",
    "E6": "?",
    "E7": "!",
    "E8": ".",
    "E9": "&",
    "EA": "é",
    "EC": "▷",
    "ED": "▶",
    "EE": "▼",
    "EF": "♂",
    "F0": "¥",
    "F1": "×",
    "F2": ".",
    "F3": "/",
    "F4": ",",
    "F5": "♀",
    "F6": "0",
    "F7": "1",
    "F8": "2",
    "F9": "3",
    "FA": "4",
    "FB": "5",
    "FC": "6",
    "FD": "7",
    "FE": "8",
    "FF": "9",
    "50": "",
    "00": ""
  },
  "es_it": {
    "54": "POKé",
    "70": "PO",
    "71": "Ké",
    "72": "“",
    "73": "”",
    "75": "…",
    "7F": " ",
    "80": "A",
    "81": "B",
    "82": "C",
    "83": "D",
    "84": "E",
    "85": "F",
    "86": "G",
    "87": "H",
    "88": "I",
    "89": "J",
    "8A": "K",
    "8B": "L",
    "8C": "M",
    "8D": "N",
    "8E": "O",
    "8F": "P",
    "90": "Q",
    "91": "R",
    "92": "S",
    "93": "T",
    "94": "U",
    "95": "V",
    "96": "W",
    "97": "X",
    "98": "Y",
    "99": "Z",
    "9A": "(",
    "9B": ")",
    "9C": ":",
    "9D": ";",
    "9E": "[",
    "9F": "]",
    "A0": "a",
    "A1": "b",
    "A2": "c",
    "A3": "d",
    "A4": "e",
    "A5": "f",
    "A6": "g",
    "A7": "h",
    "A8": "i",
    "A9": "j",
    "AA": "k",
    "AB": "l",
    "AC": "m",
    "AD": "n",
    "AE": "o",
    "AF": "p",
    "B0": "q",
    "B1": "r",
    "B2": "s",
    "B3": "t",
    "B4": "u",
    "B5": "v",
    "B6": "w",
    "B7": "x",
    "B8": "y",
    "B9": "z",
    "BA": "à",
    "BB": "è",
    "BC": "é",
    "BD": "ù",
    "BE": "À",
    "BF": "Á",
    "C0": "Ä",
    "C1": "Ö",
    "C2": "Ü",
    "C3": "ä",
    "C4": "ö",
    "C5": "ü",
    "C6": "È",
    "C7": "É",
    "C8": "Ì",
    "C9": "Í",
    "CA": "Ñ",
    "CB": "Ò",
    "CC": "Ó",
    "CD": "Ù",
    "CE": "Ú",
    "CF": "á",
    "D0": "ì",
    "D1": "í",
    "D2": "ñ",
    "D3": "ò",
    "D4": "ó",
    "D5": "ú",
    "D6": "º",
    "D7": "&",
    "D8": "'d",
    "D9": "'l",
    "DA": "'m",
    "DB": "'r",
    "DC": "'s",
    "DD": "'t",
    "DE": "'v",
    "E0": "'",
    "E1": "PK",
    "E2": "MN",
    "E3": "-",
    "E4": "¿",
    "E5": "¡",
    "E6": "?",
    "E7": "!",
    "E8": ".",
    "E9": "&",
    "EA": "é",
    "EB": "▷",
    "EC": "▶",
    "ED": "▼",
    "EE": "♂",
    "F0": "¥",
    "F1": "×",
    "F2": ".",
    "F3": "/",
    "F4": ",",
    "F5": "♀",
    "F6": "0",
    "F7": "1",
    "F8": "2",
    "F9": "3",
    "FA": "4",
    "FB": "5",
    "FC": "6",
    "FD": "7",
    "FE": "8",
    "FF": "9",
    "50": "",
    "00": ""
  },
  "jp": {
    "01": "イ゙",
    "02": "ヴ",
    "03": "エ゙",
    "04": "オ゙",
    "05": "ガ",
    "06": "ギ",
    "07": "グ",
    "08": "ゲ",
    "09": "ゴ",
    "0A": "ザ",
    "0B": "ジ",
    "0C": "ズ",
    "0D": "ゼ",
    "0E": "ゾ",
    "0F": "ダ",
    "10": "ヂ",
    "11": "ヅ",
    "12": "デ",
    "13": "ド",
    "17": "ボ",
    "18": "パ",
    "19": "ピ",
    "1A": "プ",
    "1B": "ポ",
    "1C": "ポ",
    "20": "イ゚",
    "21": "あ゙",
    "26": "が",
    "27": "ぎ",
    "28": "ぐ",
    "29": "げ",
    "2A": "ご",
    "2B": "ざ",
    "2C": "じ",
    "2D": "ず",
    "2E": "ぜ",
    "2F": "ぞ",
    "30": "だ",
    "31": "ぢ",
    "32": "づ",
    "33": "で",
    "34": "ど",
    "39": "ぼ",
    "3A": "ぱ",
    "3B": "ぴ",
    "3C": "ぷ",
    "3D": "ぺ",
    "40": "パ",
    "41": "ピ",
    "42": "プ",
    "43": "ポ",
    "44": "ぱ",
    "45": "ぴ",
    "46": "ぷ",
    "47": "ぺ",
    "48": "ぽ",
    "4D": "も゙",
    "14": "バ",
    "15": "ビ",
    "16": "ブ",
    "35": "ば",
    "36": "び",
    "37": "ぶ",
    "38": "べ",
    "3E": "ぽ",
    "70": "「",
    "71": "」",
    "72": "『",
    "73": "』",
    "74": "・",
    "75": "…",
    "7F": " ",
    "80": "ア",
    "81": "イ",
    "82": "ウ",
    "83": "エ",
    "84": "オ",
    "85": "カ",
    "86": "キ",
    "87": "ク",
    "88": "ケ",
    "89": "コ",
    "8A": "サ",
    "8B": "シ",
    "8C": "ス",
    "8D": "セ",
    "8E": "ソ",
    "8F": "タ",
    "90": "チ",
    "91": "ツ",
    "92": "テ",
    "93": "ト",
    "94": "ナ",
    "95": "ニ",
    "96": "ヌ",
    "97": "ネ",
    "98": "ノ",
    "99": "ハ",
    "9A": "ヒ",
    "9B": "フ",
    "9C": "ホ",
    "9D": "マ",
    "9E": "ミ",
    "9F": "ム",
    "A0": "メ",
    "A1": "モ",
    "A2": "ヤ",
    "A3": "ユ",
    "A4": "ヨ",
    "A5": "ラ",
    "A6": "ル",
    "A7": "レ",
    "A8": "ロ",
    "A9": "ワ",
    "AA": "ヲ",
    "AB": "ン",
    "AC": "ッ",
    "AD": "ャ",
    "AE": "ュ",
    "AF": "ョ",
    "B0": "ィ",
    "B1": "あ",
    "B2": "い",
    "B3": "う",
    "B4": "え",
    "B5": "お",
    "B6": "か",
    "B7": "き",
    "B8": "く",
    "B9": "け",
    "BA": "こ",
    "BB": "さ",
    "BC": "し",
    "BD": "す",
    "BE": "せ",
    "BF": "そ",
    "C0": "た",
    "C1": "ち",
    "C2": "つ",
    "C3": "て",
    "C4": "と",
    "C5": "な",
    "C6": "に",
    "C7": "ぬ",
    "C8": "ね",
    "C9": "の",
    "CA": "は",
    "CB": "ひ",
    "CC": "ふ",
    "CD": "へ",
    "CE": "ほ",
    "CF": "ま",
    "D0": "み",
    "D1": "む",
    "D2": "め",
    "D3": "も",
    "D4": "や",
    "D5": "ゆ",
    "D6": "よ",
    "D7": "ら",
    "D8": "リ",
    "D9": "る",
    "DA": "れ",
    "DB": "ろ",
    "DC": "わ",
    "DD": "を",
    "DE": "ん",
    "DF": "っ",
    "E0": "ゃ",
    "E1": "ゅ",
    "E2": "ょ",
    "E3": "ー",
    "E4": "ﾟ",
    "E5": "ﾞ",
    "E6": "?",
    "E7": "!",
    "E8": "。",
    "E9": "ァ",
    "EA": "ゥ",
    "EB": "ェ",
    "EC": "▷",
    "ED": "▶",
    "EE": "▼",
    "EF": "♂",
    "F0": "円",
    "F1": "x",
    "F2": ".",
    "F3": "/",
    "F4": "ォ",
    "F5": "♀",
    "F6": "0",
    "F7": "1",
    "F8": "2",
    "F9": "3",
    "FA": "4",
    "FB": "5",
    "FC": "6",
    "FD": "7",
    "FE": "8",
    "FF": "9",
    "50": "",
    "00": ""
  }
};

const DEFAULT_POKEMON_NAMES_EN = {
  "1": "BULBASAUR",
  "2": "IVYSAUR",
  "3": "VENUSAUR",
  "4": "CHARMANDER",
  "5": "CHARMELEON",
  "6": "CHARIZARD",
  "7": "SQUIRTLE",
  "8": "WARTORTLE",
  "9": "BLASTOISE",
  "10": "CATERPIE",
  "11": "METAPOD",
  "12": "BUTTERFREE",
  "13": "WEEDLE",
  "14": "KAKUNA",
  "15": "BEEDRILL",
  "16": "PIDGEY",
  "17": "PIDGEOTTO",
  "18": "PIDGEOT",
  "19": "RATTATA",
  "20": "RATICATE",
  "21": "SPEAROW",
  "22": "FEAROW",
  "23": "EKANS",
  "24": "ARBOK",
  "25": "PIKACHU",
  "26": "RAICHU",
  "27": "SANDSHREW",
  "28": "SANDSLASH",
  "29": "NIDORAN♀",
  "30": "NIDORINA",
  "31": "NIDOQUEEN",
  "32": "NIDORAN♂",
  "33": "NIDORINO",
  "34": "NIDOKING",
  "35": "CLEFAIRY",
  "36": "CLEFABLE",
  "37": "VULPIX",
  "38": "NINETALES",
  "39": "JIGGLYPUFF",
  "40": "WIGGLYTUFF",
  "41": "ZUBAT",
  "42": "GOLBAT",
  "43": "ODDISH",
  "44": "GLOOM",
  "45": "VILEPLUME",
  "46": "PARAS",
  "47": "PARASECT",
  "48": "VENONAT",
  "49": "VENOMOTH",
  "50": "DIGLETT",
  "51": "DUGTRIO",
  "52": "MEOWTH",
  "53": "PERSIAN",
  "54": "PSYDUCK",
  "55": "GOLDUCK",
  "56": "MANKEY",
  "57": "PRIMEAPE",
  "58": "GROWLITHE",
  "59": "ARCANINE",
  "60": "POLIWAG",
  "61": "POLIWHIRL",
  "62": "POLIWRATH",
  "63": "ABRA",
  "64": "KADABRA",
  "65": "ALAKAZAM",
  "66": "MACHOP",
  "67": "MACHOKE",
  "68": "MACHAMP",
  "69": "BELLSPROUT",
  "70": "WEEPINBELL",
  "71": "VICTREEBEL",
  "72": "TENTACOOL",
  "73": "TENTACRUEL",
  "74": "GEODUDE",
  "75": "GRAVELER",
  "76": "GOLEM",
  "77": "PONYTA",
  "78": "RAPIDASH",
  "79": "SLOWPOKE",
  "80": "SLOWBRO",
  "81": "MAGNEMITE",
  "82": "MAGNETON",
  "83": "FARFETCH'D",
  "84": "DODUO",
  "85": "DODRIO",
  "86": "SEEL",
  "87": "DEWGONG",
  "88": "GRIMER",
  "89": "MUK",
  "90": "SHELLDER",
  "91": "CLOYSTER",
  "92": "GASTLY",
  "93": "HAUNTER",
  "94": "GENGAR",
  "95": "ONIX",
  "96": "DROWZEE",
  "97": "HYPNO",
  "98": "KRABBY",
  "99": "KINGLER",
  "100": "VOLTORB",
  "101": "ELECTRODE",
  "102": "EXEGGCUTE",
  "103": "EXEGGUTOR",
  "104": "CUBONE",
  "105": "MAROWAK",
  "106": "HITMONLEE",
  "107": "HITMONCHAN",
  "108": "LICKITUNG",
  "109": "KOFFING",
  "110": "WEEZING",
  "111": "RHYHORN",
  "112": "RHYDON",
  "113": "CHANSEY",
  "114": "TANGELA",
  "115": "KANGASKHAN",
  "116": "HORSEA",
  "117": "SEADRA",
  "118": "GOLDEEN",
  "119": "SEAKING",
  "120": "STARYU",
  "121": "STARMIE",
  "122": "MR. MIME",
  "123": "SCYTHER",
  "124": "JYNX",
  "125": "ELECTABUZZ",
  "126": "MAGMAR",
  "127": "PINSIR",
  "128": "TAUROS",
  "129": "MAGIKARP",
  "130": "GYARADOS",
  "131": "LAPRAS",
  "132": "DITTO",
  "133": "EEVEE",
  "134": "VAPOREON",
  "135": "JOLTEON",
  "136": "FLAREON",
  "137": "PORYGON",
  "138": "OMANYTE",
  "139": "OMASTAR",
  "140": "KABUTO",
  "141": "KABUTOPS",
  "142": "AERODACTYL",
  "143": "SNORLAX",
  "144": "ARTICUNO",
  "145": "ZAPDOS",
  "146": "MOLTRES",
  "147": "DRATINI",
  "148": "DRAGONAIR",
  "149": "DRAGONITE",
  "150": "MEWTWO",
  "151": "MEW",
  "152": "CHIKORITA",
  "153": "BAYLEEF",
  "154": "MEGANIUM",
  "155": "CYNDAQUIL",
  "156": "QUILAVA",
  "157": "TYPHLOSION",
  "158": "TOTODILE",
  "159": "CROCONAW",
  "160": "FERALIGATR",
  "161": "SENTRET",
  "162": "FURRET",
  "163": "HOOTHOOT",
  "164": "NOCTOWL",
  "165": "LEDYBA",
  "166": "LEDIAN",
  "167": "SPINARAK",
  "168": "ARIADOS",
  "169": "CROBAT",
  "170": "CHINCHOU",
  "171": "LANTURN",
  "172": "PICHU",
  "173": "CLEFFA",
  "174": "IGGLYBUFF",
  "175": "TOGEPI",
  "176": "TOGETIC",
  "177": "NATU",
  "178": "XATU",
  "179": "MAREEP",
  "180": "FLAAFFY",
  "181": "AMPHAROS",
  "182": "BELLOSSOM",
  "183": "MARILL",
  "184": "AZUMARILL",
  "185": "SUDOWOODO",
  "186": "POLITOED",
  "187": "HOPPIP",
  "188": "SKIPLOOM",
  "189": "JUMPLUFF",
  "190": "AIPOM",
  "191": "SUNKERN",
  "192": "SUNFLORA",
  "193": "YANMA",
  "194": "WOOPER",
  "195": "QUAGSIRE",
  "196": "ESPEON",
  "197": "UMBREON",
  "198": "MURKROW",
  "199": "SLOWKING",
  "200": "MISDREAVUS",
  "201": "UNOWN",
  "202": "WOBBUFFET",
  "203": "GIRAFARIG",
  "204": "PINECO",
  "205": "FORRETRESS",
  "206": "DUNSPARCE",
  "207": "GLIGAR",
  "208": "STEELIX",
  "209": "SNUBBULL",
  "210": "GRANBULL",
  "211": "QWILFISH",
  "212": "SCIZOR",
  "213": "SHUCKLE",
  "214": "HERACROSS",
  "215": "SNEASEL",
  "216": "TEDDIURSA",
  "217": "URSARING",
  "218": "SLUGMA",
  "219": "MAGCARGO",
  "220": "SWINUB",
  "221": "PILOSWINE",
  "222": "CORSOLA",
  "223": "REMORAID",
  "224": "OCTILLERY",
  "225": "DELIBIRD",
  "226": "MANTINE",
  "227": "SKARMORY",
  "228": "HOUNDOUR",
  "229": "HOUNDOOM",
  "230": "KINGDRA",
  "231": "PHANPY",
  "232": "DONPHAN",
  "233": "PORYGON2",
  "234": "STANTLER",
  "235": "SMEARGLE",
  "236": "TYROGUE",
  "237": "HITMONTOP",
  "238": "SMOOCHUM",
  "239": "ELEKID",
  "240": "MAGBY",
  "241": "MILTANK",
  "242": "BLISSEY",
  "243": "RAIKOU",
  "244": "ENTEI",
  "245": "SUICUNE",
  "246": "LARVITAR",
  "247": "PUPITAR",
  "248": "TYRANITAR",
  "249": "LUGIA",
  "250": "HO-OH",
  "251": "CELEBI"
};

const DEFAULT_POKEMON_NAMES_FR = {
  "1": "BULBIZARRE",
  "2": "HERBIZARRE",
  "3": "FLORIZARRE",
  "4": "SALAMÈCHE",
  "5": "REPTINCEL",
  "6": "DRACAUFEU",
  "7": "CARAPUCE",
  "8": "CARABAFFE",
  "9": "TORTANK",
  "10": "CHENIPAN",
  "11": "CHRYSACIER",
  "12": "PAPILUSION",
  "13": "ASPICOT",
  "14": "COCONFORT",
  "15": "DARDARGNAN",
  "16": "ROUCOOL",
  "17": "ROUCOUPS",
  "18": "ROUCARDO",
  "19": "RATTATA",
  "20": "RATTATAC",
  "21": "PIAFABEC",
  "22": "RAPASDEPIC",
  "23": "ABO",
  "24": "ARBOK",
  "25": "PIKACHU",
  "26": "RAICHU",
  "27": "SAPEREAU",
  "28": "SABLAIREAU",
  "29": "NIDORAN♀",
  "30": "NIDORINA",
  "31": "NIDOQUEEN",
  "32": "NIDORAN♂",
  "33": "NIDORINO",
  "34": "NIDOKING",
  "35": "MELOFÉE",
  "36": "MÉLODÈLE",
  "37": "GOUPIX",
  "38": "FEUNARD",
  "39": "RONDODOU",
  "40": "GRODOUDO",
  "41": "NOSFERAPTI",
  "42": "NOSFERALTO",
  "43": "MYSTHERBE",
  "44": "ORTIDE",
  "45": "RAFFLESIA",
  "46": "PARAS",
  "47": "PARASECT",
  "48": "MIMITOSS",
  "49": "AÉROMITE",
  "50": "TAUPIQUEUR",
  "51": "TRIOPIKEUR",
  "52": "MIAOUSS",
  "53": "PERSIAN",
  "54": "PSYKOKWAK",
  "55": "AKWAKWAK",
  "56": "FEROSINGE",
  "57": "COLOSSINGE",
  "58": "CANINOS",
  "59": "ARCANIN",
  "60": "PTITARD",
  "61": "TÊTARTE",
  "62": "TARTARD",
  "63": "ABRA",
  "64": "KADABRA",
  "65": "ALAKAZAM",
  "66": "MACHOC",
  "67": "MACHOPEUR",
  "68": "MACKOGNEUR",
  "69": "CHETIFLOR",
  "70": "BOUSTIFLOR",
  "71": "EMPIFLOR",
  "72": "TENTACOOL",
  "73": "TENTACRUEL",
  "74": "RACAILLOU",
  "75": "GRAVALANCH",
  "76": "GROLEM",
  "77": "PONYTA",
  "78": "GALOPA",
  "79": "RAMOLOSS",
  "80": "FLAGADOSS",
  "81": "MAGNÉTI",
  "82": "MAGNETON",
  "83": "CANARTICHO",
  "84": "DODUO",
  "85": "DODRIO",
  "86": "OTARIA",
  "87": "LAMANTINE",
  "88": "TADMORV",
  "89": "GROTADMORV",
  "90": "KOKIYAS",
  "91": "CRUSTABRI",
  "92": "FANTOMINUS",
  "93": "SPECTRUM",
  "94": "ECTOPLASMA",
  "95": "ONIX",
  "96": "SOPORIFIK",
  "97": "HYPNOMADE",
  "98": "KRABBY",
  "99": "KRABBOSS",
  "100": "VOLTORBE",
  "101": "ÉLECTRODE",
  "102": "NOEUNOEUF",
  "103": "NOADKOKO",
  "104": "OSSELAIT",
  "105": "OSSATUEUR",
  "106": "KICKLEE",
  "107": "TYGNON",
  "108": "EXCELANGUE",
  "109": "SMOGO",
  "110": "SMOGOGO",
  "111": "RHINOCORNE",
  "112": "RHINOFEROS",
  "113": "LEVEINARD",
  "114": "SAQUEDENEU",
  "115": "KANGOUREX",
  "116": "HYPOTREMPE",
  "117": "HYPOCÉON",
  "118": "POISSIRENE",
  "119": "POISSOROY",
  "120": "STARI",
  "121": "STAROSS",
  "122": "MIMIGAL",
  "123": "INSÉCTEUR",
  "124": "LUDELLE",
  "125": "ÉLEKABLE",
  "126": "MAGMAR",
  "127": "SCARABRUTE",
  "128": "TAUROS",
  "129": "MAGICARPE",
  "130": "LÉVIATOR",
  "131": "LOKHLASS",
  "132": "METAMORPH",
  "133": "ÉVOLI",
  "134": "AQUALI",
  "135": "VOLALI",
  "136": "PYROLI",
  "137": "PORYGON",
  "138": "AMONITA",
  "139": "AMONISTAR",
  "140": "KABUTO",
  "141": "KABUTOPS",
  "142": "PTERA",
  "143": "RONFLEX",
  "144": "ARTIKODIN",
  "145": "ÉLECTHOR",
  "146": "SULFURA",
  "147": "MINIDRACO",
  "148": "DRACOLOSS",
  "149": "DRATTAK",
  "150": "MEWTWO",
  "151": "MEW",
  "152": "GERMIGNON",
  "153": "MACRONIUM",
  "154": "MÉGANIUM",
  "155": "HÉRICENDRE",
  "156": "FEURISSON",
  "157": "TYPHLOSION",
  "158": "KAIMINUS",
  "159": "CROCRODIL",
  "160": "ALIGATUEUR",
  "161": "FOUINETTE",
  "162": "FOUINAR",
  "163": "HOOTHOOT",
  "164": "NOARFANG",
  "165": "COXY",
  "166": "COXYCLAQUE",
  "167": "MIMIGAL",
  "168": "MIGALOS",
  "169": "NOSTENFER",
  "170": "LOUPIO",
  "171": "LANTURN",
  "172": "PICHU",
  "173": "MELO",
  "174": "TOUDOUDOU",
  "175": "TOGÉPI",
  "176": "TOGÉTIC",
  "177": "NATU",
  "178": "XATU",
  "179": "WATTOUAT",
  "180": "LAINERGIE",
  "181": "PHARAMP",
  "182": "JOLIFLOR",
  "183": "MARILL",
  "184": "AZUMARILL",
  "185": "SIMULARBRE",
  "186": "TARPAUD",
  "187": "GRANDIRA",
  "188": "FLORA",
  "189": "COTONNEE",
  "190": "CAPUMAIN",
  "191": "TOURNICOTON",
  "192": "COTOGNE",
  "193": "YANMA",
  "194": "AXOLOTO",
  "195": "MARASTE",
  "196": "MENTALI",
  "197": "NOCTALI",
  "198": "CORNEBRE",
  "199": "ROIGADA",
  "200": "FEUFOREVE",
  "201": "ZARBI",
  "202": "QULBUTOKE",
  "203": "GIRAFARIG",
  "204": "POMDEPIK",
  "205": "FORETRESS",
  "206": "INSOLOU",
  "207": "SCORPLANE",
  "208": "STEELIX",
  "209": "SNUBBULL",
  "210": "GRANBULL",
  "211": "QWILFISH",
  "212": "SCIZOR",
  "213": "CARATROC",
  "214": "SCARHINO",
  "215": "FARFURET",
  "216": "TEDDIURSA",
  "217": "URSARING",
  "218": "LIMAGMA",
  "219": "VOLCAROPOD",
  "220": "MARCROC",
  "221": "COCHIGNON",
  "222": "CORAYON",
  "223": "RÉMORAID",
  "224": "OCTILLERY",
  "225": "CADOIZO",
  "226": "DÉMANTA",
  "227": "AIRMURE",
  "228": "MALOSSE",
  "229": "DEMOLOSSE",
  "230": "HYPOROI",
  "231": "PHANPY",
  "232": "DONPHAN",
  "233": "PORYGON2",
  "234": "CERFROUSSE",
  "235": "QUEULORIOR",
  "236": "DEBUGANT",
  "237": "KAPOERA",
  "238": "LIPPOUTI",
  "239": "ÉLEKID",
  "240": "MAGBY",
  "241": "ÉCREMEUH",
  "242": "LEUPHORIE",
  "243": "RAIKOU",
  "244": "ENTEI",
  "245": "SUICUNE",
  "246": "EMBRYLEX",
  "247": "YMPHECT",
  "248": "TYRANOCIF",
  "249": "LUGIA",
  "250": "HOOH",
  "251": "CÉLÉBI"
};

const DEFAULT_POKEMON_NAMES_DE = {
  "1": "BISASAM",
  "2": "BISAKNOSP",
  "3": "BISAFLOR",
  "4": "GLUMANDA",
  "5": "GLUTEXO",
  "6": "GLURAK",
  "7": "SCHIGGY",
  "8": "SCHILLOK",
  "9": "TURTOK",
  "10": "RAUPY",
  "11": "SAFCON",
  "12": "SMETTBO",
  "13": "HORNLIU",
  "14": "KOKUNA",
  "15": "BIBOR",
  "16": "TAUBSI",
  "17": "TAUBOGA",
  "18": "TAUBOSS",
  "19": "RATTFRATZ",
  "20": "RATTIKARL",
  "21": "HABITAK",
  "22": "IBITAK",
  "23": "RETTAN",
  "24": "ARBOK",
  "25": "PIKACHU",
  "26": "RAICHU",
  "27": "SANDAN",
  "28": "SANDAMER",
  "29": "NIDORAN♀",
  "30": "NIDORINA",
  "31": "NIDOQUEEN",
  "32": "NIDORAN♂",
  "33": "NIDORINO",
  "34": "NIDOKING",
  "35": "PIEPI",
  "36": "PIXI",
  "37": "VULPIX",
  "38": "VULNONA",
  "39": "PUMMELUFF",
  "40": "KNUDDELUFF",
  "41": "ZUBAT",
  "42": "GOLBAT",
  "43": "MYRAPLA",
  "44": "DUFLOR",
  "45": "GIFLOR",
  "46": "PARAS",
  "47": "PARASEK",
  "48": "BLUZUK",
  "49": "OMOT",
  "50": "DIGDA",
  "51": "DAGTRIO",
  "52": "MAUZI",
  "53": "SNOBILIKAT",
  "54": "ENTON",
  "55": "ENTO",
  "56": "MENKY",
  "57": "RASAFF",
  "58": "FUKANO",
  "59": "ARKANI",
  "60": "QUAPSEL",
  "61": "QUAPUTZI",
  "62": "QUAPPO",
  "63": "ABRA",
  "64": "KADABRA",
  "65": "SIMSALAR",
  "66": "MACHOLLO",
  "67": "MASCHOCK",
  "68": "MACHOMEI",
  "69": "KNOFENSA",
  "70": "ULTRIGARIA",
  "71": "SARZENIA",
  "72": "TENTACHA",
  "73": "TENTOXA",
  "74": "KLEINSTEIN",
  "75": "GEOROK",
  "76": "GEOWAZ",
  "77": "PONITA",
  "78": "GALLOPA",
  "79": "FLEGMON",
  "80": "LAHMUS",
  "81": "MAGNETILO",
  "82": "MAGNETON",
  "83": "PORITA",
  "84": "DODUO",
  "85": "DODRI",
  "86": "JUROB",
  "87": "JUGONG",
  "88": "SLEIMA",
  "89": "SLEIMOK",
  "90": "MUSCHAS",
  "91": "AUSTOS",
  "92": "NEBULAK",
  "93": "ALPOLLO",
  "94": "GENGAR",
  "95": "ONIX",
  "96": "TRAUMATO",
  "97": "HYPNO",
  "98": "KRABBY",
  "99": "KINGLER",
  "100": "VOLTOBAL",
  "101": "LEKTROBAL",
  "102": "OWEINDE",
  "103": "KOKOWEI",
  "104": "TRAGOSSO",
  "105": "KNOFENSA",
  "106": "KICKLEE",
  "107": "NOCKCHAN",
  "108": "SCHLURP",
  "109": "SMOGON",
  "110": "SMOGMOG",
  "111": "RHYHORN",
  "112": "RIZEROS",
  "113": "CHANEIRA",
  "114": "TANGELA",
  "115": "KANGAMA",
  "116": "SEEPIE",
  "117": "SEEMON",
  "118": "GOLDINI",
  "119": "GOLKING",
  "120": "STERNE",
  "121": "STARMIE",
  "122": "PANTIMOS",
  "123": "SICHLOR",
  "124": "ROSSANA",
  "125": "ELEKTEK",
  "126": "MAGMAR",
  "127": "PINSIR",
  "128": "TAUROS",
  "129": "KARPADOR",
  "130": "GARADOS",
  "131": "LAPRAS",
  "132": "DITTO",
  "133": "EVOLI",
  "134": "AQUANA",
  "135": "BLITZA",
  "136": "FLAMARA",
  "137": "PORYGON",
  "138": "AMONITAS",
  "139": "AMOROSO",
  "140": "KABUTO",
  "141": "KABUTOPS",
  "142": "AERODACTYL",
  "143": "RELAXO",
  "144": "ARKTOS",
  "145": "ZAPDOS",
  "146": "LAVADOS",
  "147": "DRATINI",
  "148": "DRAGONIR",
  "149": "DRAGORAN",
  "150": "MEWTU",
  "151": "MEW",
  "152": "ENDIVIE",
  "153": "LORBLATT",
  "154": "MEGANIUM",
  "155": "FEURIGEL",
  "156": "IGELAVAR",
  "157": "TORNUPTO",
  "158": "KARNIMANI",
  "159": "TYLONI",
  "160": "IMPOBER",
  "161": "WIESOR",
  "162": "WIESENIOR",
  "163": "HOOTHOOT",
  "164": "NOCTHU",
  "165": "WEBARAK",
  "166": "ARIADOS",
  "167": "LEDYBA",
  "168": "LEDIAN",
  "169": "NOSTENFER",
  "170": "LANTURN",
  "171": "LAMPUHL",
  "172": "PICHU",
  "173": "PIXIE",
  "174": "FLUFFELUFF",
  "175": "TOGEPI",
  "176": "TOGETIC",
  "177": "NATU",
  "178": "XATU",
  "179": "VOLTIKAL",
  "180": "FLAAFFY",
  "181": "AMPHAROS",
  "182": "BLUBELLA",
  "183": "MARILL",
  "184": "AZUMARILL",
  "185": "MOGELBAUM",
  "186": "QUAXO",
  "187": "HOPPSPROSS",
  "188": "HUBELUPF",
  "189": "PAPUNGH",
  "190": "GRIFFEL",
  "191": "SONNKERN",
  "192": "SONNFLORA",
  "193": "YANMA",
  "194": "FELINO",
  "195": "MORLORD",
  "196": "PSIANA",
  "197": "NACHTARA",
  "198": "KRAMURX",
  "199": "LASCHOKING",
  "200": "TRAUNFUGIL",
  "201": "ICOGNE",
  "202": "WOINGENAU",
  "203": "GIRAFARIG",
  "204": "TANNZA",
  "205": "FORSTELLKA",
  "206": "DUMMISEL",
  "207": "SKORGLA",
  "208": "STAHLOS",
  "209": "FLUFFELUFF",
  "210": "GRANBULL",
  "211": "BALDORFISH",
  "212": "SCHEROX",
  "213": "POTTROTT",
  "214": "SKARABORN",
  "215": "SNIEBEL",
  "216": "TEDDIURSA",
  "217": "URSARING",
  "218": "SCHNECKMAG",
  "219": "MAGCARGO",
  "220": "QUIEKEL",
  "221": "KEIFEL",
  "222": "CORASONN",
  "223": "REMORAID",
  "224": "OCTILLERY",
  "225": "BOTOGEL",
  "226": "MANTAX",
  "227": "PANZAERON",
  "228": "HUNDUSTER",
  "229": "HUNDEMON",
  "230": "SEEDRAKING",
  "231": "PHANPY",
  "232": "DONPHAN",
  "233": "PORYGON2",
  "234": "DAMHIRPLEX",
  "235": "FARBEAGLE",
  "236": "RABAUZ",
  "237": "KAPOERA",
  "238": "KUSSILLA",
  "239": "ELEKID",
  "240": "MAGBY",
  "241": "MILTANK",
  "242": "HEITEIRA",
  "243": "RAIKOU",
  "244": "ENTEI",
  "245": "SUICUNE",
  "246": "LARVITAR",
  "247": "PUPITAR",
  "248": "DESPOTAR",
  "249": "LUGIA",
  "250": "HO-OH",
  "251": "CELEBI"
};

const DEFAULT_POKEMON_NAMES_JP = {
  "1": "フシギダネ",
  "2": "フシギソウ",
  "3": "フシギバナ",
  "4": "ヒトカゲ",
  "5": "リザード",
  "6": "リザードン",
  "7": "ゼニガメ",
  "8": "カメール",
  "9": "カメックス",
  "10": "キャタピー",
  "11": "トランセル",
  "12": "バタフリー",
  "13": "ビードル",
  "14": "コクーン",
  "15": "スピアー",
  "16": "ポッポ",
  "17": "ピジョン",
  "18": "ピジョット",
  "19": "コラッタ",
  "20": "ラッタ",
  "21": "オニスズメ",
  "22": "オニドリル",
  "23": "アーボ",
  "24": "アーボック",
  "25": "ピカチュウ",
  "26": "ライチュウ",
  "27": "サンド",
  "28": "サンドパン",
  "29": "ニドラン♀",
  "30": "ニドリーナ",
  "31": "ニドクイン",
  "32": "ニドラン♂",
  "33": "ニドリーノ",
  "34": "ニドキング",
  "35": "ピッピ",
  "36": "ピクシー",
  "37": "ロコン",
  "38": "キュウコン",
  "39": "プリン",
  "40": "プクリン",
  "41": "ズバット",
  "42": "ゴルバット",
  "43": "ナゾノクサ",
  "44": "クサイハナ",
  "45": "ラフレシア",
  "46": "パラス",
  "47": "パラセクト",
  "48": "コンパン",
  "49": "モルフォン",
  "50": "ディグダ",
  "51": "ダグトリオ",
  "52": "ニャース",
  "53": "ペルシアン",
  "54": "コダック",
  "55": "ゴルダック",
  "56": "マンキー",
  "57": "オコリザル",
  "58": "ガーディ",
  "59": "ウインディ",
  "60": "ニョロモ",
  "61": "ニョロゾ",
  "62": "ニョロボン",
  "63": "ケーシィ",
  "64": "ユンゲラー",
  "65": "フーディン",
  "66": "ワンリキー",
  "67": "ゴーリキー",
  "68": "カイリキー",
  "69": "マダツボミ",
  "70": "ウツドン",
  "71": "ウツボット",
  "72": "メノクラゲ",
  "73": "ドククラゲ",
  "74": "イシツブテ",
  "75": "ゴローン",
  "76": "ゴローニャ",
  "77": "ポニータ",
  "78": "ギャロップ",
  "79": "ヤドン",
  "80": "ヤドラン",
  "81": "コイル",
  "82": "レアコイル",
  "83": "カモネギ",
  "84": "ドードー",
  "85": "ドードリオ",
  "86": "パウワウ",
  "87": "ジュゴン",
  "88": "ベトベター",
  "89": "ベトベトン",
  "90": "シェルダー",
  "91": "パルシェン",
  "92": "ゴース",
  "93": "ゴースト",
  "94": "ゲンガー",
  "95": "イワーク",
  "96": "スリープ",
  "97": "スリーパー",
  "98": "クラブ",
  "99": "キングラー",
  "100": "ビリリダマ",
  "101": "マルマイン",
  "102": "タマタマ",
  "103": "ナッシー",
  "104": "カラカラ",
  "105": "ガラガラ",
  "106": "サワムラー",
  "107": "エビワラー",
  "108": "ベロリンガ",
  "109": "ドガース",
  "110": "マタドガス",
  "111": "サイホーン",
  "112": "サイドン",
  "113": "ラッキー",
  "114": "モンジャラ",
  "115": "ガルーラ",
  "116": "タッツー",
  "117": "シードラ",
  "118": "トサキント",
  "119": "アズマオウ",
  "120": "ヒトデマン",
  "121": "スターミー",
  "122": "バリヤード",
  "123": "ストライク",
  "124": "ルージュラ",
  "125": "エレブー",
  "126": "ブーバー",
  "127": "カイロス",
  "128": "ケンタロス",
  "129": "コイキング",
  "130": "ギャラドス",
  "131": "ラプラス",
  "132": "メタモン",
  "133": "イーブイ",
  "134": "シャワーズ",
  "135": "サンダース",
  "136": "ブースター",
  "137": "ポリゴン",
  "138": "オムナイト",
  "139": "オムスター",
  "140": "カブト",
  "141": "カブトプス",
  "142": "プテラ",
  "143": "カビゴン",
  "144": "フリーザー",
  "145": "サンダー",
  "146": "ファイヤー",
  "147": "ミニリュウ",
  "148": "ハクリュー",
  "149": "カイリュー",
  "150": "ミュウツー",
  "151": "ミュウ",
  "152": "チコリータ",
  "153": "ベイリーフ",
  "154": "メガニウム",
  "155": "ヒノアラシ",
  "156": "マグマラシ",
  "157": "バクフーン",
  "158": "ワニノコ",
  "159": "アリゲイツ",
  "160": "オーダイル",
  "161": "オタチ",
  "162": "オオタチ",
  "163": "ホーホー",
  "164": "ヨルノズク",
  "165": "レディバ",
  "166": "レディアン",
  "167": "イトマル",
  "168": "アリアドス",
  "169": "クロバット",
  "170": "チョンチー",
  "171": "ランターン",
  "172": "ピチュー",
  "173": "ピィ",
  "174": "ププリン",
  "175": "トゲピー",
  "176": "トゲチック",
  "177": "ネイティ",
  "178": "ネイティオ",
  "179": "メリープ",
  "180": "モココ",
  "181": "デンリュウ",
  "182": "キレイハナ",
  "183": "マリル",
  "184": "マリルリ",
  "185": "ウソッキー",
  "186": "ニョロトノ",
  "187": "ハネッコ",
  "188": "ポポッコ",
  "189": "ワタッコ",
  "190": "エイパム",
  "191": "ヒマナッツ",
  "192": "キマワリ",
  "193": "ヤンヤンマ",
  "194": "ウパー",
  "195": "ヌオー",
  "196": "エーフィ",
  "197": "ブラッキー",
  "198": "ヤミカラス",
  "199": "ヤドキング",
  "200": "ムウマ",
  "201": "アンノーン",
  "202": "ソーナンス",
  "203": "キリンリキ",
  "204": "クヌギダマ",
  "205": "フォレトス",
  "206": "ノコッチ",
  "207": "グライガー",
  "208": "ハガネール",
  "209": "ブルー",
  "210": "グランブル",
  "211": "ハリーセン",
  "212": "ハッサム",
  "213": "ツボツボ",
  "214": "ヘラクロス",
  "215": "ニューラ",
  "216": "ヒメグマ",
  "217": "リングマ",
  "218": "マグマッグ",
  "219": "マグカルゴ",
  "220": "ウリムー",
  "221": "イノムー",
  "222": "サニーゴ",
  "223": "テッポウオ",
  "224": "オクタン",
  "225": "デリバード",
  "226": "マンタイン",
  "227": "エアームド",
  "228": "デルビル",
  "229": "ヘルガー",
  "230": "キングドラ",
  "231": "ゴマゾウ",
  "232": "ドンファン",
  "233": "ポリゴン２",
  "234": "オドシシ",
  "235": "ドーブル",
  "236": "バルキー",
  "237": "カポエラー",
  "238": "ムチュール",
  "239": "エレキッド",
  "240": "ブビィ",
  "241": "ミルタンク",
  "242": "ハピナス",
  "243": "ライコウ",
  "244": "エンテイ",
  "245": "スイクン",
  "246": "ヨーギラス",
  "247": "サナギラス",
  "248": "バンギラス",
  "249": "ルギア",
  "250": "ホウオウ",
  "251": "セレビィ"
};

const DEFAULT_POKEMON_NAMES_BY_REGION = {
  "e": DEFAULT_POKEMON_NAMES_EN,
  "p": DEFAULT_POKEMON_NAMES_EN,
  "u": DEFAULT_POKEMON_NAMES_EN,
  "s": DEFAULT_POKEMON_NAMES_EN,
  "i": DEFAULT_POKEMON_NAMES_EN,
  "f": DEFAULT_POKEMON_NAMES_FR,
  "d": DEFAULT_POKEMON_NAMES_DE,
  "j": DEFAULT_POKEMON_NAMES_JP,
};

function getEncodingTable(id) {
  if (!id) return null;
  return ENCODING_TABLES[id] || null;
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

  const out = [];
  const src = String(text);
  for (let i = 0; i < src.length && out.length < maxBytes; i++) {
    const ch = src[i];
    if (reverse.has(ch)) {
      out.push(reverse.get(ch));
    }
    // else: drop the character entirely, do not substitute "?"
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

    // ASCII digits and punctuation passthrough
    if ((ch >= "0" && ch <= "9") || ch === "!" || ch === "." || ch === "/" || ch === "?") {
      out += ch;
      continue;
    }

    // JP punctuation to ASCII
    if (ch === " ") {
      out += " ";
      continue;
    }
    if (ch === "。") {
      out += ".";
      continue;
    }
    if (ch === "・") {
      out += "-";
      continue;
    }
    if (ch === "「" || ch === "『" || ch === "」" || ch === "』") {
      out += '"';
      continue;
    }

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

  // Uppercase and keep only letters, digits, space, and a small punctuation set
  out = out.toUpperCase();
  out = out
    .split("")
    .filter((c) => c === " " || c === "!" || c === "." || c === "/" || c === "?" || (c >= "0" && c <= "9") || (c >= "A" && c <= "Z"))
    .join("");

  // Collapse patterns like BUBU -> BB
  out = out.replace(/([A-Z])U\1U/g, "$1$1");

  // Per-word final tweaks: REI -> RY, RI -> RY at end of word
  const parts = out.split(" ");
  for (let idx = 0; idx < parts.length; idx++) {
    let p = parts[idx];
    if (!p) continue;
    const lenp = p.length;
    if (lenp >= 3 && p.endsWith("REI")) {
      p = p.slice(0, -2) + "Y";
    } else if (lenp >= 2 && p.endsWith("RI")) {
      p = p.slice(0, -1) + "Y";
    }
    parts[idx] = p;
  }
  out = parts.join(" ");
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
	LLI: "リ",
	LLY: "リ",
	RRY: "リ",
	MMY: "ミ",
	DDY: "ディ",
	PEE: "ピ",
	BEE: "ビ",
	REE: "リ",
	THE: "テ",
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
	WI: "ウィ",
	WE: "ウェ",
	WO: "ウォ",
	TI: "ティ",
	DI: "ディ",
	TU: "トゥ",
	DU: "ドゥ",
	SI: "シ",
	ZI: "ジ",
	MY: "ミ",
	DY: "ディ",
	KA: "カ",
	KI: "キ",
	KU: "ク",
	KE: "ケ",
	KO: "コ",
	SA: "サ",
	SU: "ス",
	SE: "セ",
	SO: "ソ",
	TA: "タ",
	TE: "テ",
	TO: "ト",
	NA: "ナ",
	NI: "ニ",
	NU: "ヌ",
	NE: "ネ",
	NO: "ノ",
	HA: "ハ",
	HI: "ヒ",
	HE: "ヘ",
	HO: "ホ",
	MA: "マ",
	MI: "ミ",
	MU: "ム",
	ME: "メ",
	MO: "モ",
	YA: "ヤ",
	YU: "ユ",
	YO: "ヨ",
	RA: "ラ",
	RI: "リ",
	RU: "ル",
	RE: "レ",
	RO: "ロ",
	WA: "ワ",
	LA: "ラ",
	LI: "リ",
	LY: "リ",
	LU: "ル",
	LE: "レ",
	LO: "ロ",
	RY: "リ",
	BA: "ヴァ",
	BE: "ヴェ",
	BO: "ヴォ",
	CK: "ク",
	CH: "ク",
	TH: "ス",
	GA: "ガ",
	GI: "ギ",
	GU: "グ",
	GE: "ゲ",
	GO: "ゴ",
	ZA: "ザ",
	ZU: "ズ",
	ZE: "ゼ",
	ZO: "ゾ",
	DA: "ダ",
	DE: "デ",
	DO: "ド",
	BA: "バ",
	BI: "ビ",
	BU: "ブ",
	BE: "ベ",
	BO: "ボ",
	VA: "バ",
	VI: "ビ",
	VU: "ブ",
	VE: "ベ",
	VO: "ボ",
	PA: "パ",
	PI: "ピ",
	PU: "プ",
	PE: "ペ",
	PO: "ポ",
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
	T: "タ",
	V: "ビ",
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
    "ß": "SS",
    "ä": "AE",
    "ö": "O",
    "ü": "U",
    "Ä": "AE",
    "Ö": "O",
    "Ü": "U",
    "é": "E",
    "è": "E",
    "ê": "E",
    "ë": "E",
    "É": "E",
    "È": "E",
    "Ê": "E",
    "Ë": "E",
    "á": "A",
    "à": "A",
    "â": "A",
    "ã": "A",
    "å": "A",
    "Á": "A",
    "À": "A",
    "Â": "A",
    "Ã": "A",
    "Å": "A",
    "í": "I",
    "ì": "I",
    "î": "I",
    "ï": "I",
    "Í": "I",
    "Ì": "I",
    "Î": "I",
    "Ï": "I",
    "ó": "O",
    "ò": "O",
    "ô": "O",
    "õ": "O",
    "Ó": "O",
    "Ò": "O",
    "Ô": "O",
    "Õ": "O",
    "ú": "U",
    "ù": "U",
    "û": "U",
    "Ú": "U",
    "Ù": "U",
    "Û": "U",
    "ç": "C",
    "Ç": "C",
    "ñ": "N",
    "Ñ": "N",
    "¡": "!",
    "¿": "?",
  };

  let out = Array.from(s).map((ch) => map[ch] || ch).join("");
  // Collapse French-style letter+apostrophe forms (c', d', l', etc.) to plain letters
  out = out.replace(/([A-Za-z])'/g, "$1");
  return out;
}


function enToJpKatakana(en, maxChars) {
  ensureEnJpMaps();

  let s = String(en || "").toUpperCase();
  // Keep only A-Z, digits, space, and a small punctuation set
  s = s
    .split("")
    .filter((c) => c === " " || c === "!" || c === "." || c === "/" || c === "?" || (c >= "0" && c <= "9") || (c >= "A" && c <= "Z"))
    .join("");
  if (!s || maxChars <= 0) return "";

  const chars = s.split("");
  const out = [];
  let i = 0;

  while (i < chars.length && out.length < maxChars) {
    const ch = chars[i];

    // Preserve digits and basic punctuation as-is
    if ((ch >= "0" && ch <= "9") || ch === " " || ch === "!" || ch === "." || ch === "/" || ch === "?") {
      out.push(ch);
      i++;
      continue;
    }

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

    out.push(EN_JP_MAP1[chars[i]] || "ア");
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
  const r = String(region || "").toLowerCase();
  const speciesTable = DEFAULT_POKEMON_NAMES_BY_REGION[r];
  if (!speciesTable) return null;
  const name = speciesTable[String(species)];
  if (!name) return null;
  const tableId = nameTableForRegion(r);
  if (!tableId) return null;
  const maxBytes = r === "j" ? 5 : 10;
  return simpleEncodeTextToBytes(name, tableId, maxBytes);
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

  const destNameLen = isDestJ ? 5 : 7;
  const destPkmLen = isDestJ ? 58 : 65;
  const destMailLen = isDestJ ? 42 : 47;

  // Always start from a region-correct view of the source row so that
  // offsets and lengths match the original per-region formats.
  const sliced = slicePayloadForRegionFromRow(row, sourceRegion);
  let trainerName = sliced.trainerName;
  let pokemon = sliced.pokemon;
  let mail = sliced.mail;

  if (!Buffer.isBuffer(trainerName))
    trainerName = Buffer.from(trainerName || "", "binary");
  if (!Buffer.isBuffer(pokemon))
    pokemon = Buffer.from(pokemon || "", "binary");
  if (!Buffer.isBuffer(mail)) mail = Buffer.from(mail || "", "binary");

  // If both source and destination are non-J, or if regions are identical,
  // do not attempt any cross-region conversion.
  if (srcR === dstR || (!isSourceJ && !isDestJ)) {
    // Ensure outer trainer name has the correct destination length.
    if (trainerName.length > destNameLen)
      trainerName = trainerName.subarray(0, destNameLen);
    else if (trainerName.length < destNameLen)
      trainerName = Buffer.concat([
        trainerName,
        Buffer.alloc(destNameLen - trainerName.length, 0x50),
      ]);

    if (pokemon.length > destPkmLen)
      pokemon = pokemon.subarray(0, destPkmLen);
    else if (pokemon.length < destPkmLen)
      pokemon = Buffer.concat([
        pokemon,
        Buffer.alloc(destPkmLen - pokemon.length, 0x00),
      ]);

    if (mail.length > destMailLen)
      mail = mail.subarray(0, destMailLen);
    else if (mail.length < destMailLen)
      mail = Buffer.concat([
        mail,
        Buffer.alloc(destMailLen - mail.length, 0x00),
      ]);

    return { trainerName, pokemon, mail };
  }

  // Cross-region J <-> non-J
  const destPlayerNameBytes = destNameLen;
  trainerName = convertPlayerNameForDownload(
    destRegion,
    sourceRegion,
    trainerName,
    destPlayerNameBytes
  );

  // --- Pokémon OT & nickname conversion ---
  if (pokemon.length > 0) {
    const species = pokemon[0];

    const srcOtOffset = 0x30;
    const srcOtLen = isSourceJ ? 5 : 7;
    const destOtOffset = 0x30;
    const destOtLen = isDestJ ? 5 : 7;

    const srcNickOffset = isSourceJ ? 0x35 : 0x37;
    const srcNickLen = isSourceJ ? 5 : 11;
    const destNickOffset = isDestJ ? 0x35 : 0x37;
    const destNickLen = isDestJ ? 5 : 10;

    // Build a fresh destination-shaped Pokémon buffer.
    let destPokemon = Buffer.alloc(destPkmLen, 0x00);

    // Copy the shared core (0x00..0x2F) as-is.
    const coreLen = Math.min(0x30, pokemon.length, destPokemon.length);
    if (coreLen > 0) {
      pokemon.copy(destPokemon, 0, 0, coreLen);
    }

    // OT name: read from source layout, write into destination layout.
    if (pokemon.length >= srcOtOffset + srcOtLen) {
      const otSlice = pokemon.slice(srcOtOffset, srcOtOffset + srcOtLen);
      const newOt = convertPlayerNameForDownload(
        destRegion,
        sourceRegion,
        otSlice,
        destOtLen
      );

      destPokemon.fill(0x50, destOtOffset, destOtOffset + destOtLen);
      const writeLen = Math.min(destOtLen, newOt.length);
      newOt.copy(destPokemon, destOtOffset, 0, writeLen);
    }
// Nickname: read from source layout, write into destination layout.
    if (pokemon.length > srcNickOffset) {
      const availableNickLen = Math.max(
        0,
        Math.min(srcNickLen, pokemon.length - srcNickOffset)
      );
      if (availableNickLen > 0) {
        const nickSlice = pokemon.slice(
          srcNickOffset,
          srcNickOffset + availableNickLen
        );
        const newNick = convertPokemonNicknameForDownload(
          destRegion,
          sourceRegion,
          species,
          nickSlice,
          destNickLen
        );
        destPokemon.fill(0x50, destNickOffset, destNickOffset + destNickLen);
        const writeLen = Math.min(destNickLen, newNick.length);
        newNick.copy(destPokemon, destNickOffset, 0, writeLen);
      }
    }

    pokemon = destPokemon;
  }

  // --- Mail text and name conversion ---
  if (mail.length > 0) {
    const srcTextOffset = 0x00;
    const srcTextLen = 0x21; // 33 bytes: 16 + 0x4E + 16

    const destTextOffset = 0x00;
    const destTextLen = 0x21;

    const srcNameOffset = srcTextOffset + srcTextLen;
    const destNameOffset = destTextOffset + destTextLen;

    // Mail struct layout: [33 bytes text][name][4 bytes metadata].
    const srcMailLen = mail.length;
    const srcMetaLen = 4;
    const srcMetaOffset =
      srcMailLen >= srcMetaLen ? srcMailLen - srcMetaLen : srcMailLen;
    const srcNameLen = Math.max(0, srcMetaOffset - srcNameOffset);

    const destMetaLen = 4;
    const destMetaOffset =
      destMailLen >= destMetaLen ? destMailLen - destMetaLen : destMailLen;
    const destNameLenMail = Math.max(0, destMetaOffset - destNameOffset);

    let destMail = Buffer.alloc(destMailLen, 0x00);

    const srcTableId = nameTableForRegion(sourceRegion);
    const destTableId = nameTableForRegion(destRegion);

    function decodeMailText33(textBuf, tableId) {
      if (!Buffer.isBuffer(textBuf) || textBuf.length === 0) return "";
      if (!tableId) return "";

      // Normalize to a 33-byte window so we can always treat the layout as:
      //   16 bytes line 1, 0x4E, 16 bytes line 2.
      const buf = Buffer.alloc(0x21, 0x50);
      const copyLen = Math.min(textBuf.length, buf.length);
      textBuf.copy(buf, 0, 0, copyLen);

      // If there is no line-break marker at 0x10, fall back to simple decode.
      if (buf[0x10] !== 0x4e) {
        return simpleDecodeBytesToText(textBuf, tableId);
      }

      const line1Raw = buf.slice(0, 16);
      const line2Raw = buf.slice(17, 33);

      const line1 = simpleDecodeBytesToText(line1Raw, tableId) || "";
      const line2 = simpleDecodeBytesToText(line2Raw, tableId) || "";

      // Preserve a strict 32-character grid (16 + 16) with no injected
      // separator. Trailing spaces are trimmed only at the very end.
      const combined = (line1 + line2).slice(0, 32);
      return combined.replace(/\s+$/, "");
    }

    function encodeMailText33(plain, tableId) {
      if (!tableId) return Buffer.alloc(0x21, 0x50);
      const flat = String(plain || "");

      const line1Str = flat.slice(0, 16);
      const line2Str = flat.slice(16, 32);

      const line1 = simpleEncodeTextToBytes(line1Str, tableId, 16);
      const line2 = simpleEncodeTextToBytes(line2Str, tableId, 16);

      return Buffer.concat([line1, Buffer.from([0x4e]), line2]);
    }

    // Mail body text.
    if (srcMailLen >= srcTextOffset + srcTextLen) {
      const textSlice = mail.slice(srcTextOffset, srcTextOffset + srcTextLen);

      if (isSourceJ && !isDestJ) {
        // JP -> non-JP
        if (srcTableId && destTableId) {
          const plain = decodeMailText33(textSlice, srcTableId);
          if (plain) {
            const ascii = transliterateJpToEnName(plain, 32);
            if (ascii) {
              const enc = encodeMailText33(ascii, destTableId);
              const copyLen = Math.min(destTextLen, enc.length);
              destMail.fill(0x50, destTextOffset, destTextOffset + destTextLen);
              enc.copy(destMail, destTextOffset, 0, copyLen);
            }
          }
        }
      } else if (!isSourceJ && isDestJ) {
        // non-JP -> JP
        if (srcTableId && destTableId) {
          const plain = decodeMailText33(textSlice, srcTableId);
          if (plain) {
            const kat = transliterateEnToJpName(plain, 32);
            if (kat) {
              const enc = encodeMailText33(kat, destTableId);
              const copyLen = Math.min(destTextLen, enc.length);
              destMail.fill(0x50, destTextOffset, destTextOffset + destTextLen);
              enc.copy(destMail, destTextOffset, 0, copyLen);
            }
          }
        }
      } else {
        // Fallback: copy existing text into destination window.
        const copyLen = Math.min(destTextLen, textSlice.length);
        textSlice.copy(destMail, destTextOffset, 0, copyLen);
      }
    }

    // Mail player name field inside the mail blob.
    if (srcMailLen > srcNameOffset && destNameLenMail > 0) {
      const nameSlice = mail.slice(srcNameOffset, srcMetaOffset);
      const newName = convertPlayerNameForDownload(
        destRegion,
        sourceRegion,
        nameSlice,
        destNameLenMail
      );
      const writeLen = Math.min(destNameLenMail, newName.length);
      newName.copy(destMail, destNameOffset, 0, writeLen);
    }

    // JP -> non-J: non-J mail includes 2 nationality bytes immediately before the 4-byte metadata tail.
    // JP and EN mail have no nationality bytes, so default them to 0x00,0x00 (instead of inheriting name padding).
    if (isSourceJ && !isDestJ) {
      if (destMetaOffset >= 2) {
        destMail[destMetaOffset - 2] = 0x00;
        destMail[destMetaOffset - 1] = 0x00;
      }
    }

    // Preserve trailing mail metadata (trainer ID, species, mail item, etc.).
    if (
      srcMailLen >= srcMetaOffset + srcMetaLen &&
      destMailLen >= destMetaOffset + destMetaLen
    ) {
      mail.copy(
        destMail,
        destMetaOffset,
        srcMetaOffset,
        srcMetaOffset + srcMetaLen
      );
    }

    mail = destMail;
  }

// Finally, enforce destination lengths for outer trainerName
  if (trainerName.length > destNameLen)
    trainerName = trainerName.subarray(0, destNameLen);
  else if (trainerName.length < destNameLen)
    trainerName = Buffer.concat([
      trainerName,
      Buffer.alloc(destNameLen - trainerName.length, 0x50),
    ]);

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

function regionCanTrade(a, b, aPool, bPool) {
  if (!a || !b) return false;
  var regions = { "a": String(a).toLowerCase(), "b": String(b).toLowerCase() }
  
  //~Set up per-player language pools
  var regionPools = { "a": aPool.split(","), "b": bPool.split(",") }
  
  //~For each player, isolate down to the language pool their game falls into
  for (var player in regionPools) {
    for (var pool of regionPools[player]) {
        if (pool.includes(regions[player])) {
            regionPools[player] = pool; break;
        }
    }
  }
  
  //~Return whether both players' pools have each others' languages
  return (regionPools.a.includes(regions.b)) && (regionPools.b.includes(regions.a));
}

// ------------------------------
// X-Game-result construction (legacy layout)
// ------------------------------

function toHexString(value, byteLength) {
  let v = Number(value) >>> 0;
  let s = v.toString(16).toLowerCase();
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
  // Normalize payload inputs to Buffers to avoid undefined/strings.
  if (!Buffer.isBuffer(trainerName))
    trainerName = Buffer.from(trainerName || "", "binary");
  if (!Buffer.isBuffer(pokemon))
    pokemon = Buffer.from(pokemon || "", "binary");
  if (!Buffer.isBuffer(mail))
    mail = Buffer.from(mail || "", "binary");

  const regionLower = String(region || "e").toLowerCase();
  let r;
  switch (regionLower) {
    case "p":
    case "u":
    case "e":
      r = "E";
      break;
    case "f":
      r = "F";
      break;
    case "d":
      r = "D";
      break;
    case "s":
      r = "S";
      break;
    case "i":
      r = "I";
      break;
    case "j":
      r = "J";
      break;
    default:
      r = "E";
      break;
  }


  const tidHex = toHexString(trainerId, 2);
  const sidHex = toHexString(secretId, 2);
  const offerGenderHex = toHexString(offerGender, 1);
  const offerSpeciesHex = toHexString(offerSpecies, 1);
  const requestGenderHex = toHexString(requestGender, 1);
  const requestSpeciesHex = toHexString(requestSpecies, 1);

  // X-Game-* headers must be on a single line, matching retail mobile mail.
  const header =
    "MIME-Version: 1.0\r\n" +
    "From: MISSINGNO.\r\n" +
    "Subject: Trade\r\n" +
    "X-Game-title: POCKET MONSTERS\r\n" +
    `X-Game-code: CGB-BXT${r}-00\r\n` +
    `X-Game-result: 1 ${tidHex}${sidHex} ${offerGenderHex}${offerSpeciesHex} ${requestGenderHex}${requestSpeciesHex} 1\r\n` +
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


function slicePayloadForRegionFromRow(row, region) {
  const r = String(region || "").toLowerCase();
  // Lengths based on original per-region tables:
  // J:   name 5,  pokemon 58, mail 42
  // non-J (E/F/D/S/I/P/U): name 7, pokemon 65, mail 47
  let nameLen, pkmLen, mailLen;
  if (r === "j") {
    nameLen = 5;
    pkmLen = 58;
    mailLen = 42;
  } else {
    nameLen = 7;
    pkmLen = 65;
    mailLen = 47;
  }

  const nameBuf = row["player_name"] || row["trainer_name"] || Buffer.alloc(0);
  const pkmBuf = row["pokemon"] || Buffer.alloc(0);
  const mailBuf = row["mail"] || Buffer.alloc(0);

  const tn = Buffer.isBuffer(nameBuf)
    ? nameBuf.subarray(0, nameLen)
    : Buffer.from(nameBuf || "", "binary").subarray(0, nameLen);
  const pk = Buffer.isBuffer(pkmBuf)
    ? pkmBuf.subarray(0, pkmLen)
    : Buffer.from(pkmBuf || "", "binary").subarray(0, pkmLen);
  const ml = Buffer.isBuffer(mailBuf)
    ? mailBuf.subarray(0, mailLen)
    : Buffer.from(mailBuf || "", "binary").subarray(0, mailLen);

  return { trainerName: tn, pokemon: pk, mail: ml };
}


async function insertExchangeLogRow(connection, row1, row2) {
  // Each bxt_exchange row represents a single player's OFFER. Mirror that into *_1 / *_2.
  const table = "bxt_exchange_log";

  function buf(v) {
    if (Buffer.isBuffer(v)) return v;
    if (v === null || v === undefined) return Buffer.alloc(0);
    return Buffer.from(String(v), "binary");
  }

  function s(v) {
    if (v === undefined) return null;
    return v === null ? null : String(v);
  }

  const r1 = row1 || {};
  const r2 = row2 || {};

  const params = [
    // player 1
    r1["account_id"],
    s(r1["email"]),
    s(r1["game_region"]),
    r1["trainer_id"],
    r1["secret_id"],
    buf(r1["player_name"] || r1["trainer_name"]),
    s(r1["player_name_decode"] || r1["trainer_name_decode"]),
    r1["offer_gender"],
    s(r1["offer_gender_decode"]),
    r1["offer_species"],
    s(r1["offer_species_decode"]),
    buf(r1["pokemon"]),
    s(r1["pokemon_decode"]),
    buf(r1["mail"]),
    s(r1["mail_decode"]),

    // player 2
    r2["account_id"],
    s(r2["email"]),
    s(r2["game_region"]),
    r2["trainer_id"],
    r2["secret_id"],
    buf(r2["player_name"] || r2["trainer_name"]),
    s(r2["player_name_decode"] || r2["trainer_name_decode"]),
    r2["offer_gender"],
    s(r2["offer_gender_decode"]),
    r2["offer_species"],
    s(r2["offer_species_decode"]),
    buf(r2["pokemon"]),
    s(r2["pokemon_decode"]),
    buf(r2["mail"]),
    s(r2["mail_decode"]),
  ];

  const sql = `
    INSERT INTO \`${table}\` (
      \`account_id_1\`, \`email_1\`, \`game_region_1\`, \`trainer_id_1\`, \`secret_id_1\`,
      \`player_name_1\`, \`player_name_decode_1\`,
      \`gender_1\`, \`gender_decode_1\`,
      \`species_1\`, \`species_decode_1\`,
      \`pokemon_1\`, \`pokemon_decode_1\`,
      \`mail_1\`, \`mail_decode_1\`,
      \`account_id_2\`, \`email_2\`, \`game_region_2\`, \`trainer_id_2\`, \`secret_id_2\`,
      \`player_name_2\`, \`player_name_decode_2\`,
      \`gender_2\`, \`gender_decode_2\`,
      \`species_2\`, \`species_decode_2\`,
      \`pokemon_2\`, \`pokemon_decode_2\`,
      \`mail_2\`, \`mail_decode_2\`
    ) VALUES (
      ?, ?, ?, ?, ?,
      ?, ?,
      ?, ?,
      ?, ?,
      ?, ?,
      ?, ?,
      ?, ?, ?, ?, ?,
      ?, ?,
      ?, ?,
      ?, ?,
      ?, ?,
      ?, ?
    )
  `;
  await connection.execute(sql, params);
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
      "SELECT bxt_exchange.*, sys_users.trade_region_allowlist FROM " + table + " LEFT JOIN sys_users ON bxt_exchange.account_id=sys_users.id ORDER BY timestamp ASC"
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
          regionCanTrade(a["game_region"], b["game_region"], a["trade_region_allowlist"], b["trade_region_allowlist"]) &&
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

          const sameRegion =
            String(a["game_region"] || "").toLowerCase() ===
            String(b["game_region"] || "").toLowerCase();

          let payloadForB;
          let payloadForA;

          if (sameRegion) {
            // Same-region trade: send raw payloads exactly as stored, sliced to the
            // correct per-region lengths based on original table specs.
            payloadForB = slicePayloadForRegionFromRow(a, a["game_region"]);
            payloadForA = slicePayloadForRegionFromRow(b, b["game_region"]);
          } else {
            // Cross-region trade: use transformExchangePayloadForEmail to adapt payloads.
            payloadForB = transformExchangePayloadForEmail(
              b["game_region"],
              a["game_region"],
              a
            );
            payloadForA = transformExchangePayloadForEmail(
              a["game_region"],
              b["game_region"],
              b
            );
          }

          // For player B: use B's own metadata in header, partner's payload.
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

          // For player A: use A's own metadata in header, partner's payload.
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

          await insertExchangeLogRow(connection, a, b);

          // Retention: keep only last 1 month of exchange logs.
          await connection.execute(
            "DELETE FROM bxt_exchange_log WHERE `timestamp` < NOW() - INTERVAL 1 MONTH"
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

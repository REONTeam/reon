/* Trade Corner UI behaviors:
 * - swap static PNG -> animated GIF on card hover
 * - live availability countdown + smooth green/yellow/red color interpolation
 * - fade-and-remove expired cards
 * - live search with multilingual tag support
 */
(function () {
  "use strict";

  var DEFAULT_EXCHANGE_LIFETIME_SECONDS = 7 * 24 * 60 * 60;
  var COLOR_GREEN = [15, 122, 34];
  var COLOR_YELLOW = [138, 103, 0];
  var COLOR_RED = [180, 35, 24];
  var TAG_ALIASES = Object.create(null);
  var ANY_TAG_TERMS = {
    any: true,
    all: true,
    alles: true,
    todos: true,
    cualquiera: true,
    tout: true,
    tous: true,
    nimporte: true,
    tutti: true,
    qualsiasi: true,
    beliebig: true,
    zenbu: true,
    arbitrary: true,
    "\u4EFB\u610F": true,
    "\u3069\u308C\u3067\u3082": true,
  };
  var TAG_DISPLAY_LABELS_BY_LOCALE = {
    en: {
      species: "SPECIES:",
      level: "LEVEL:",
      gender: "GENDER:",
      shiny: "SHINY:",
      name: "NAME:",
      ot: "OT:",
      item: "ITEM:",
      offerer: "OFFERER:",
      language: "LANGUAGE:",
      country: "COUNTRY:",
      pokerus: "POK\u00E9RUS:",
      wanted: "WANTED:",
      offer: "OFFER:",
    },
    de: {
      species: "SPEZIES:",
      level: "LEVEL:",
      gender: "GESCHLECHT:",
      shiny: "SHINY:",
      name: "NAME:",
      ot: "OT:",
      item: "ITEM:",
      offerer: "ANBIETER:",
      language: "SPRACHE:",
      country: "LAND:",
      pokerus: "POK\u00E9RUS:",
      wanted: "GESUCHT:",
      offer: "ANGEBOT:",
    },
    es: {
      species: "ESPECIE:",
      level: "NIVEL:",
      gender: "GENERO:",
      shiny: "SHINY:",
      name: "NOMBRE:",
      ot: "OT:",
      item: "OBJETO:",
      offerer: "OFERENTE:",
      language: "IDIOMA:",
      country: "PAIS:",
      pokerus: "POK\u00E9RUS:",
      wanted: "BUSCADO:",
      offer: "OFERTA:",
    },
    fr: {
      species: "ESPECE:",
      level: "NIVEAU:",
      gender: "SEXE:",
      shiny: "SHINY:",
      name: "NOM:",
      ot: "OT:",
      item: "OBJET:",
      offerer: "DONNEUR:",
      language: "LANGUE:",
      country: "PAYS:",
      pokerus: "POK\u00E9RUS:",
      wanted: "RECHERCHE:",
      offer: "OFFRE:",
    },
    it: {
      species: "SPECIE:",
      level: "LIVELLO:",
      gender: "SESSO:",
      shiny: "SHINY:",
      name: "NOME:",
      ot: "OT:",
      item: "STRUMENTO:",
      offerer: "OFFERENTE:",
      language: "LINGUA:",
      country: "PAESE:",
      pokerus: "POK\u00E9RUS:",
      wanted: "RICERCATO:",
      offer: "OFFERTA:",
    },
    ja: {
      species: "\u7A2E\u65CF:",
      level: "\u30EC\u30D9\u30EB:",
      gender: "\u6027\u5225:",
      shiny: "\u8272\u9055\u3044:",
      name: "\u540D\u524D:",
      ot: "OT:",
      item: "\u3082\u3061\u3082\u306E:",
      offerer: "\u51FA\u54C1\u8005:",
      language: "\u8A00\u8A9E:",
      country: "\u56FD:",
      pokerus: "POK\u00E9RUS:",
      wanted: "\u307B\u3057\u3044:",
      offer: "\u63D0\u4F9B:",
    },
  };
  var TAG_DISPLAY_LABELS = {
    species: "SPECIES:",
    level: "LEVEL:",
    gender: "GENDER:",
    shiny: "SHINY:",
    name: "NAME:",
    ot: "OT:",
    item: "ITEM:",
    offerer: "OFFERER:",
    language: "LANGUAGE:",
    country: "COUNTRY:",
    pokerus: "POK\u00E9RUS:",
    wanted: "WANTED:",
    offer: "OFFER:",
  };
  var REGION_FILTER_VALUES = {
    global: true,
    int: true,
    j: true,
    eng: true,
    e: true,
    p: true,
    u: true,
    f: true,
    d: true,
    i: true,
    s: true,
  };

  var countdownEntries = [];
  var searchEntries = [];
  var searchInput = null;
  var searchTailInput = null;
  var searchChipList = null;
  var searchTokenizedRoot = null;
  var searchTagPills = null;
  var tokenizedSearchChips = [];
  var activeTokenizedChipIndex = -1;
  var deferredGeneralSegments = [];
  var regionFilterSelect = null;
  var noResultsNode = null;
  var uiLocale = "en";
  var searchTagPixelSnapFrame = 0;

  function normalizeSearchText(value) {
    var source = String(value || "")
      .replace(/[\u2640]/g, " female ")
      .replace(/[\u2642]/g, " male ");
    return source
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .replace(/[._-]+/g, " ")
      .replace(/[^a-z0-9\u3040-\u30ff\u3400-\u9fff\s]/g, " ")
      .replace(/\s+/g, " ")
      .trim();
  }

  function normalizeTagKey(key) {
    return normalizeSearchText(key);
  }

  function tokenize(text) {
    return normalizeSearchText(text || "")
      .split(/\s+/)
      .filter(Boolean);
  }

  function buildTokenSet(tokens) {
    var set = Object.create(null);
    for (var i = 0; i < tokens.length; i++) {
      set[tokens[i]] = true;
    }
    return set;
  }

  function isExactTokenTerm(term) {
    return /^(?:\d{1,3}|l\d{1,3}|lv\d{1,3}|level\d{1,3})$/.test(term);
  }

  function isStrictTokenTerm(term) {
    if (isExactTokenTerm(term)) {
      return true;
    }
    return term === "shiny" || term === "male" || term === "female";
  }

  function parseLevelNumberFromTerm(term) {
    var m = /^(?:l|lv|level)?(\d{1,3})$/.exec(term);
    if (!m) {
      return null;
    }
    return parseInt(m[1], 10);
  }

  function parseLevelNumberFromTerms(terms) {
    for (var i = 0; i < terms.length; i++) {
      var parsed = parseLevelNumberFromTerm(terms[i]);
      if (parsed !== null && Number.isFinite(parsed)) {
        return parsed;
      }
    }
    return null;
  }

  function parseLevelRangeFromRaw(rawValue) {
    var raw = String(rawValue || "").trim();
    if (!raw) {
      return null;
    }
    var match = raw.match(
      /(?:^|[^0-9])(?:l|lv|level)?\s*(\d{1,3})\s*[-~\u2010-\u2015\uFF5E]\s*(?:l|lv|level)?\s*(\d{1,3})(?:$|[^0-9])/i
    );
    if (!match) {
      return null;
    }
    var a = parseInt(match[1], 10);
    var b = parseInt(match[2], 10);
    if (!Number.isFinite(a) || !Number.isFinite(b)) {
      return null;
    }
    return {
      min: Math.min(a, b),
      max: Math.max(a, b),
    };
  }

  function parseLevelRangeFromTerms(terms) {
    if (!terms || terms.length < 2) {
      return null;
    }
    var values = [];
    for (var i = 0; i < terms.length; i++) {
      var parsed = parseLevelNumberFromTerm(terms[i]);
      if (parsed !== null && Number.isFinite(parsed)) {
        values.push(parsed);
      }
      if (values.length >= 2) {
        break;
      }
    }
    if (values.length < 2) {
      return null;
    }
    return {
      min: Math.min(values[0], values[1]),
      max: Math.max(values[0], values[1]),
    };
  }

  function parseBooleanFromTerms(terms) {
    var truthy = {
      yes: true,
      y: true,
      true: true,
      on: true,
      shiny: true,
      si: true,
      oui: true,
      ja: true,
      verdadero: true,
      vrai: true,
      vero: true,
      wahr: true,
      hai: true,
      ari: true,
      with: true,
    };
    var falsy = {
      no: true,
      n: true,
      false: true,
      off: true,
      notshiny: true,
      non: true,
      nein: true,
      none: true,
      falso: true,
      faux: true,
      falsch: true,
      iie: true,
      nashi: true,
      without: true,
      aucun: true,
      aucune: true,
      nessuno: true,
      nessuna: true,
      ninguno: true,
      ninguna: true,
    };
    var seenTrue = false;
    var seenFalse = false;

    for (var i = 0; i < terms.length; i++) {
      var t = terms[i];
      if (truthy[t]) {
        seenTrue = true;
      }
      if (falsy[t]) {
        seenFalse = true;
      }
    }

    if (seenTrue && !seenFalse) {
      return true;
    }
    if (!seenTrue && seenFalse) {
      return false;
    }
    return null;
  }

  function parseGenderFromTerms(terms) {
    var maleTokens = {
      m: true,
      male: true,
      man: true,
      boy: true,
      masculine: true,
      maschio: true,
      masculino: true,
      mannlich: true,
    };
    var femaleTokens = {
      f: true,
      female: true,
      woman: true,
      girl: true,
      feminine: true,
      femmina: true,
      femenino: true,
      weiblich: true,
    };

    var seenMale = false;
    var seenFemale = false;
    for (var i = 0; i < terms.length; i++) {
      var t = terms[i];
      if (maleTokens[t]) {
        seenMale = true;
      }
      if (femaleTokens[t]) {
        seenFemale = true;
      }
    }

    if (seenMale && !seenFemale) {
      return "male";
    }
    if (!seenMale && seenFemale) {
      return "female";
    }
    return "";
  }

  function registerTagAlias(alias, field) {
    var normalized = normalizeTagKey(alias);
    if (!normalized) {
      return;
    }
    TAG_ALIASES[normalized] = field;
  }

  function registerTagAliasList(field, aliases) {
    for (var i = 0; i < aliases.length; i++) {
      registerTagAlias(aliases[i], field);
    }
  }

  function initializeTagAliases() {
    registerTagAliasList("wanted", [
      "wanted",
      "gesucht",
      "se busca",
      "recherche",
      "chiesto",
      "richiesto",
      "\u307b\u3057\u3044",
      "\u4ea4\u63db\u5e0c\u671b",
    ]);

    registerTagAliasList("offerer", [
      "offerer",
      "anbieter",
      "ofrece",
      "donneur",
      "offerente",
      "\u51fa\u54c1\u8005",
      "\u4ea4\u63db\u76f8\u624b",
    ]);

    registerTagAliasList("offer", [
      "offer",
      "angebot",
      "oferta",
      "offre",
      "offerta",
      "offerspecies",
      "offer species",
      "\u63d0\u4f9b",
      "\u51fa\u54c1",
    ]);

    registerTagAliasList("species", [
      "species",
      "spezies",
      "especie",
      "especies",
      "espece",
      "specie",
      "specie pokemon",
      "pokemon",
      "\u7a2e\u65cf",
      "\u30dd\u30b1\u30e2\u30f3",
    ]);

    registerTagAliasList("language", [
      "language",
      "lang",
      "sprache",
      "idioma",
      "langue",
      "lingua",
      "linguaggio",
      "region",
      "regione",
      "\u8a00\u8a9e",
    ]);

    registerTagAliasList("item", [
      "item",
      "items",
      "objet",
      "objeto",
      "objetos",
      "gegenstand",
      "strumento",
      "strumenti",
      "\u3069\u3046\u3050",
      "\u3082\u3061\u3082\u306e",
    ]);

    registerTagAliasList("name", [
      "name",
      "nombre",
      "nom",
      "nome",
      "nickname",
      "\u540d\u524d",
      "\u30cb\u30c3\u30af\u30cd\u30fc\u30e0",
    ]);

    registerTagAliasList("shiny", [
      "shiny",
      "brillant",
      "brillante",
      "chromatique",
      "variocolor",
      "luminoso",
      "lucente",
      "\u3044\u308d\u3061\u304c\u3044",
    ]);

    registerTagAliasList("gender", [
      "gender",
      "sex",
      "sexe",
      "genre",
      "geschlecht",
      "genero",
      "sesso",
      "\u6027\u5225",
    ]);

    registerTagAliasList("level", [
      "level",
      "lv",
      "lvl",
      "niveau",
      "stufe",
      "nivel",
      "livello",
      "\u30ec\u30d9\u30eb",
    ]);

    registerTagAliasList("ot", [
      "ot",
      "original trainer",
      "owner",
      "dresseur",
      "entraineur",
      "trainer",
      "\u306a\u307e\u3048",
      "\u30c8\u30ec\u30fc\u30ca\u30fc",
    ]);

    registerTagAliasList("country", [
      "country",
      "nation",
      "pays",
      "pais",
      "paese",
      "land",
      "region",
      "countrycode",
      "\u56fd",
      "\u5730\u57df",
    ]);

    registerTagAliasList("pokerus", [
      "pokerus",
      "pkrs",
      "virus",
      "infected",
      "cured",
      "active",
      "none",
      "infecte",
      "infectado",
      "infetto",
      "\u30dd\u30b1\u30eb\u30b9",
    ]);
  }

  function resolveUiLocale() {
    var root = document.getElementById("trade-search-tag-pills");
    var fromAttr = root ? String(root.getAttribute("data-locale") || "") : "";
    var normalized = normalizeTagKey(fromAttr).slice(0, 2);
    if (normalized === "nl") {
      normalized = "en";
    }
    if (!TAG_DISPLAY_LABELS_BY_LOCALE[normalized]) {
      normalized = "en";
    }
    return normalized;
  }

  function applyLocaleTagDisplayLabels() {
    var localized = TAG_DISPLAY_LABELS_BY_LOCALE[uiLocale] || TAG_DISPLAY_LABELS_BY_LOCALE.en;
    var keys = Object.keys(localized);
    for (var i = 0; i < keys.length; i++) {
      TAG_DISPLAY_LABELS[keys[i]] = localized[keys[i]];
    }
  }

  function applyTagPillLabelsToUi() {
    var root = document.getElementById("trade-search-tag-pills");
    if (!root) {
      return;
    }
    var buttons = root.querySelectorAll(".trade-search-tag-pill[data-tag-field]");
    for (var i = 0; i < buttons.length; i++) {
      var button = buttons[i];
      var field = String(button.getAttribute("data-tag-field") || "").toLowerCase();
      if (!field) {
        continue;
      }
      button.textContent = getTagDisplayLabel(field);
    }
    scheduleSearchTagPixelSnap();
  }

  function addLocalizedAliasesFromUi() {
    var tagPillRoot = document.getElementById("trade-search-tag-pills");
    if (tagPillRoot) {
      var tagPills = tagPillRoot.querySelectorAll(".trade-search-tag-pill[data-tag-field]");
      for (var p = 0; p < tagPills.length; p++) {
        var pill = tagPills[p];
        var field = String(pill.getAttribute("data-tag-field") || "").toLowerCase();
        if (!field) {
          continue;
        }
        registerTagAlias(pill.textContent || "", field);
      }
    }

    var firstSlot = document.querySelector(".crystal-trade-slot");
    if (!firstSlot) {
      return;
    }

    var wantedLabel = firstSlot.querySelector(".wanted-label");
    if (wantedLabel) {
      var localizedWanted = String(wantedLabel.textContent || "").trim();
      registerTagAlias(localizedWanted, "wanted");
      if (localizedWanted) {
        TAG_DISPLAY_LABELS.wanted = localizedWanted.toUpperCase() + ":";
      }
    }

    var offerLabels = firstSlot.querySelectorAll(".offer-details .label");
    if (offerLabels.length >= 1) {
      var localizedName = String(offerLabels[0].textContent || "").trim();
      registerTagAlias(localizedName, "name");
      if (localizedName) {
        TAG_DISPLAY_LABELS.name = localizedName.toUpperCase() + ":";
      }
    }
    if (offerLabels.length >= 2) {
      registerTagAlias(offerLabels[1].textContent || "", "ot");
    }
    if (offerLabels.length >= 3) {
      var localizedItem = String(offerLabels[2].textContent || "").trim();
      registerTagAlias(localizedItem, "item");
      if (localizedItem) {
        TAG_DISPLAY_LABELS.item = localizedItem.toUpperCase() + ":";
      }
    }
    if (offerLabels.length >= 4) {
      var localizedOfferer = String(offerLabels[3].textContent || "").trim();
      registerTagAlias(localizedOfferer, "offerer");
      if (localizedOfferer) {
        TAG_DISPLAY_LABELS.offerer = localizedOfferer.toUpperCase() + ":";
      }
    }

    var levelLabel = firstSlot.querySelector(".level-label");
    if (levelLabel) {
      registerTagAlias(levelLabel.textContent || "", "level");
    }
  }

  function getTagDisplayLabel(field) {
    var key = String(field || "").toLowerCase();
    if (TAG_DISPLAY_LABELS[key]) {
      return TAG_DISPLAY_LABELS[key];
    }
    if (!key) {
      return "";
    }
    return key.charAt(0).toUpperCase() + key.slice(1);
  }

  function syncTagPillAvailability() {
    var root = searchTagPills || document.getElementById("trade-search-tag-pills");
    if (!root) {
      return;
    }

    var activeFields = Object.create(null);
    for (var i = 0; i < tokenizedSearchChips.length; i++) {
      var chip = tokenizedSearchChips[i];
      if (!chip || !chip.field) {
        continue;
      }
      activeFields[String(chip.field).toLowerCase()] = true;
    }

    var pills = root.querySelectorAll(".trade-search-tag-pill[data-tag-field]");
    for (var p = 0; p < pills.length; p++) {
      var pill = pills[p];
      var field = String(pill.getAttribute("data-tag-field") || "").toLowerCase();
      if (!field) {
        continue;
      }
      var isActive = !!activeFields[field];
      pill.style.display = isActive ? "none" : "";
      pill.disabled = isActive;
      pill.setAttribute("aria-hidden", isActive ? "true" : "false");
    }
    scheduleSearchTagPixelSnap();
  }

  function snapSearchTagPixels() {
    searchTagPixelSnapFrame = 0;
    var nodes = [];
    var collectNodes = function (root, selector) {
      if (!root) {
        return;
      }
      var matches = root.querySelectorAll(selector);
      for (var i = 0; i < matches.length; i++) {
        nodes.push(matches[i]);
      }
    };

    collectNodes(searchChipList, ".trade-search-chip");
    collectNodes(searchTagPills, ".trade-search-tag-pill");

    if (!nodes.length) {
      return;
    }

    for (var r = 0; r < nodes.length; r++) {
      nodes[r].style.removeProperty("--trade-search-pixel-nudge-x");
      nodes[r].style.removeProperty("--trade-search-pixel-nudge-y");
    }

    var dpr = window.devicePixelRatio || 1;
    for (var n = 0; n < nodes.length; n++) {
      var node = nodes[n];
      if (!node.offsetParent) {
        continue;
      }
      var rect = node.getBoundingClientRect();
      var nudgeX = (Math.round(rect.left * dpr) - rect.left * dpr) / dpr;
      var nudgeY = (Math.round(rect.top * dpr) - rect.top * dpr) / dpr;
      if (Math.abs(nudgeX) > 0.01) {
        node.style.setProperty("--trade-search-pixel-nudge-x", nudgeX.toFixed(3) + "px");
      }
      if (Math.abs(nudgeY) > 0.01) {
        node.style.setProperty("--trade-search-pixel-nudge-y", nudgeY.toFixed(3) + "px");
      }
    }
  }

  function scheduleSearchTagPixelSnap() {
    if (typeof window === "undefined" || !window.requestAnimationFrame) {
      return;
    }
    if (searchTagPixelSnapFrame) {
      return;
    }
    searchTagPixelSnapFrame = window.requestAnimationFrame(snapSearchTagPixels);
  }

  function resolveTagFieldFromPrefix(prefixText) {
    var normalized = normalizeTagKey(prefixText);
    if (!normalized) {
      return "";
    }
    return TAG_ALIASES[normalized] || "";
  }

  function pushTokenizedSearchChip(field, valueText, allowEmptyValue, isComposing) {
    var normalizedField = String(field || "").toLowerCase();
    var normalizedTerms = tokenize(valueText || "").join(" ");
    if (!normalizedField) {
      return false;
    }
    if (!allowEmptyValue && !normalizedTerms) {
      return false;
    }
    tokenizedSearchChips.push({
      field: normalizedField,
      label: getTagDisplayLabel(normalizedField),
      value: normalizedTerms,
      composing: !!isComposing,
    });
    return true;
  }

  function normalizeDeferredGeneralSegments() {
    var normalized = [];
    var chipCount = tokenizedSearchChips.length;
    for (var i = 0; i < deferredGeneralSegments.length; i++) {
      var segment = deferredGeneralSegments[i];
      if (!segment) {
        continue;
      }
      var text = String(segment.text || "").trim();
      if (!text) {
        continue;
      }
      var index = parseInt(segment.index, 10);
      if (!Number.isFinite(index)) {
        index = chipCount;
      }
      index = Math.max(0, Math.min(chipCount, index));
      normalized.push({
        text: text,
        index: index,
      });
    }
    deferredGeneralSegments = normalized;
  }

  function hasDeferredGeneralSegments() {
    return deferredGeneralSegments.length > 0;
  }

  function pushDeferredGeneralSegment(text, index) {
    var value = String(text || "").trim();
    if (!value) {
      return false;
    }
    deferredGeneralSegments.push({
      text: value,
      index: index,
    });
    normalizeDeferredGeneralSegments();
    return true;
  }

  function renderTokenizedSearchChips() {
    if (!searchChipList) {
      return;
    }
    searchChipList.innerHTML = "";
    var isComposing = activeTokenizedChipIndex >= 0;
    var hostedTailInputInsideChip = false;
    if (searchChipList.parentNode && searchChipList.parentNode.classList) {
      searchChipList.parentNode.classList.toggle("trade-search-tokenized-composing", isComposing);
    }

    normalizeDeferredGeneralSegments();

    var appendDeferredTextNodesAtIndex = function (index) {
      for (var s = 0; s < deferredGeneralSegments.length; s++) {
        var segment = deferredGeneralSegments[s];
        if (!segment || segment.index !== index) {
          continue;
        }
        var segmentText = String(segment.text || "").trim();
        if (!segmentText) {
          continue;
        }
        var leadingTextNode = document.createElement("span");
        leadingTextNode.className = "trade-search-leading-text";
        leadingTextNode.textContent = segmentText;
        searchChipList.appendChild(leadingTextNode);
      }
    };

    for (var i = 0; i < tokenizedSearchChips.length; i++) {
      appendDeferredTextNodesAtIndex(i);

      var chip = tokenizedSearchChips[i];
      var chipNode = document.createElement("span");
      chipNode.className = "trade-search-chip";
      chipNode.setAttribute("data-chip-index", String(i));
      chipNode.setAttribute("title", chip.label + (chip.value ? " " + chip.value : ""));
      if (chip.composing) {
        chipNode.classList.add("trade-search-chip-composing");
      }

      var labelNode = document.createElement("span");
      labelNode.className = "trade-search-chip-label";
      labelNode.textContent = chip.label;

      var valueNode = document.createElement("span");
      valueNode.className = "trade-search-chip-value";
      if (chip.composing && searchTailInput) {
        valueNode.classList.add("trade-search-chip-value-composing");
        valueNode.textContent = "";
        searchTailInput.classList.add("trade-search-tail-input-inside-chip");
        searchTailInput.setAttribute("placeholder", "");
        valueNode.appendChild(searchTailInput);
        hostedTailInputInsideChip = true;
      } else {
        valueNode.textContent = chip.value || "\u00A0";
      }

      var removeButton = document.createElement("button");
      removeButton.type = "button";
      removeButton.className = "trade-search-chip-remove";
      removeButton.setAttribute("aria-label", "Remove " + chip.label + " tag");
      removeButton.setAttribute("data-chip-index", String(i));
      removeButton.textContent = "\u00d7";

      chipNode.appendChild(labelNode);
      chipNode.appendChild(valueNode);
      chipNode.appendChild(removeButton);
      searchChipList.appendChild(chipNode);
    }

    appendDeferredTextNodesAtIndex(tokenizedSearchChips.length);

    if (searchTailInput && !hostedTailInputInsideChip) {
      searchTailInput.classList.remove("trade-search-tail-input-inside-chip");
      searchTailInput.style.removeProperty("width");
      if (tokenizedSearchChips.length > 0 || hasDeferredGeneralSegments()) {
        searchTailInput.setAttribute("placeholder", "");
      } else {
        searchTailInput.setAttribute("placeholder", "Search...");
      }
      if (searchTokenizedRoot && searchTailInput.parentNode !== searchTokenizedRoot) {
        searchTokenizedRoot.appendChild(searchTailInput);
      }
    }

    if (
      !isComposing &&
      tokenizedSearchChips.length === 0 &&
      hasDeferredGeneralSegments() &&
      searchTailInput &&
      !String(searchTailInput.value || "").trim()
    ) {
      var carryText = [];
      for (var d = 0; d < deferredGeneralSegments.length; d++) {
        var carry = String(deferredGeneralSegments[d].text || "").trim();
        if (carry) {
          carryText.push(carry);
        }
      }
      searchTailInput.value = carryText.join(" ").trim();
      deferredGeneralSegments = [];
    }

    updateTailInputInlineWidth();
    syncTagPillAvailability();
  }

  function updateTailInputInlineWidth() {
    if (!searchTailInput) {
      return;
    }
    if (!searchTailInput.classList.contains("trade-search-tail-input-inside-chip")) {
      searchTailInput.style.removeProperty("width");
      return;
    }
    var chars = String(searchTailInput.value || "").length;
    var widthCh = Math.max(1, chars);
    searchTailInput.style.width = widthCh + "ch";
    capActiveTagInputWidth();
  }

  function capActiveTagInputWidth() {
    if (
      !searchTailInput ||
      !searchTailInput.classList.contains("trade-search-tail-input-inside-chip")
    ) {
      return;
    }
    var chipNode = searchTailInput.closest ? searchTailInput.closest(".trade-search-chip") : null;
    if (!chipNode || !searchTokenizedRoot) {
      searchTailInput.style.removeProperty("max-width");
      return;
    }

    var valueNode = searchTailInput.parentNode;
    var labelNode = chipNode.querySelector(".trade-search-chip-label");
    var removeNode = chipNode.querySelector(".trade-search-chip-remove");
    var rootRect = searchTokenizedRoot.getBoundingClientRect();
    var labelWidth = labelNode ? labelNode.getBoundingClientRect().width : 0;
    var removeWidth = removeNode ? removeNode.getBoundingClientRect().width : 0;
    var chipStyle = window.getComputedStyle ? window.getComputedStyle(chipNode) : null;
    var rootStyle = window.getComputedStyle ? window.getComputedStyle(searchTokenizedRoot) : null;
    var chipPadding =
      (chipStyle ? parseFloat(chipStyle.paddingLeft) || 0 : 0) +
      (chipStyle ? parseFloat(chipStyle.paddingRight) || 0 : 0);
    var rootPadding =
      (rootStyle ? parseFloat(rootStyle.paddingLeft) || 0 : 0) +
      (rootStyle ? parseFloat(rootStyle.paddingRight) || 0 : 0);
    var gap = rootStyle ? parseFloat(rootStyle.columnGap || rootStyle.gap) || 0 : 0;
    var available = Math.max(
      24,
      Math.floor(rootRect.width - rootPadding - chipPadding - labelWidth - removeWidth - gap)
    );

    searchTailInput.style.maxWidth = available + "px";
    if (valueNode && valueNode.style) {
      valueNode.style.maxWidth = available + "px";
    }
  }

  function buildTokenizedSearchQuery() {
    var parts = [];
    normalizeDeferredGeneralSegments();

    var appendDeferredTextPartsAtIndex = function (index) {
      for (var s = 0; s < deferredGeneralSegments.length; s++) {
        var segment = deferredGeneralSegments[s];
        if (!segment || segment.index !== index) {
          continue;
        }
        var segmentText = String(segment.text || "").trim();
        if (!segmentText) {
          continue;
        }
        parts.push(segmentText);
      }
    };

    for (var i = 0; i < tokenizedSearchChips.length; i++) {
      appendDeferredTextPartsAtIndex(i);
      var chip = tokenizedSearchChips[i];
      if (!chip.field) {
        continue;
      }
      if (chip.value) {
        parts.push(chip.field + ":" + chip.value);
      }
    }
    appendDeferredTextPartsAtIndex(tokenizedSearchChips.length);

    var tailText = searchTailInput ? String(searchTailInput.value || "").trim() : "";
    if (tailText && activeTokenizedChipIndex < 0) {
      parts.push(tailText);
    }
    return parts.join(" ").trim();
  }

  function syncTokenizedSearchToHiddenInput() {
    if (!searchInput) {
      return "";
    }
    var queryText = buildTokenizedSearchQuery();
    searchInput.value = queryText;
    return queryText;
  }

  function syncActiveTokenizedChipFromTail() {
    if (activeTokenizedChipIndex < 0) {
      return false;
    }
    if (activeTokenizedChipIndex >= tokenizedSearchChips.length) {
      activeTokenizedChipIndex = -1;
      return false;
    }

    var activeChip = tokenizedSearchChips[activeTokenizedChipIndex];
    if (!activeChip) {
      activeTokenizedChipIndex = -1;
      return false;
    }
    activeChip.value = String(searchTailInput ? searchTailInput.value : "");
    return true;
  }

  function finalizeActiveTokenizedChip() {
    if (activeTokenizedChipIndex < 0) {
      return false;
    }
    if (activeTokenizedChipIndex >= tokenizedSearchChips.length) {
      activeTokenizedChipIndex = -1;
      return false;
    }

    var activeChip = tokenizedSearchChips[activeTokenizedChipIndex];
    if (!activeChip) {
      activeTokenizedChipIndex = -1;
      return false;
    }

    activeChip.value = String(activeChip.value || "").trim();
    activeChip.composing = false;
    activeTokenizedChipIndex = -1;
    renderTokenizedSearchChips();
    if (searchTailInput) {
      searchTailInput.value = "";
    }
    return true;
  }

  function beginActiveTokenizedChip(field, presetRawValue, preserveExistingTailText) {
    var resolvedField = String(field || "").toLowerCase();
    if (!resolvedField) {
      return false;
    }

    if (activeTokenizedChipIndex >= 0) {
      finalizeActiveTokenizedChip();
    }

    var priorChipCount = tokenizedSearchChips.length;
    if (searchTailInput && preserveExistingTailText) {
      var currentTail = String(searchTailInput.value || "").trim();
      if (currentTail) {
        pushDeferredGeneralSegment(currentTail, priorChipCount);
      }
      searchTailInput.value = "";
    }

    var added = pushTokenizedSearchChip(resolvedField, "", true, true);
    if (!added) {
      return false;
    }
    activeTokenizedChipIndex = tokenizedSearchChips.length - 1;

    if (searchTailInput) {
      searchTailInput.value = String(presetRawValue || "");
      syncActiveTokenizedChipFromTail();
      renderTokenizedSearchChips();
      searchTailInput.focus();
    }
    return true;
  }

  function startEditingTokenizedChip(index) {
    if (index < 0 || index >= tokenizedSearchChips.length) {
      return false;
    }

    if (activeTokenizedChipIndex === index) {
      if (searchTailInput) {
        searchTailInput.focus();
        searchTailInput.setSelectionRange(
          searchTailInput.value.length,
          searchTailInput.value.length
        );
      }
      return true;
    }

    if (activeTokenizedChipIndex >= 0) {
      finalizeActiveTokenizedChip();
    } else if (searchTailInput) {
      var looseTail = String(searchTailInput.value || "").trim();
      if (looseTail) {
        pushDeferredGeneralSegment(looseTail, tokenizedSearchChips.length);
        searchTailInput.value = "";
      }
    }

    for (var i = 0; i < tokenizedSearchChips.length; i++) {
      tokenizedSearchChips[i].composing = false;
    }

    var chip = tokenizedSearchChips[index];
    chip.composing = true;
    activeTokenizedChipIndex = index;

    if (searchTailInput) {
      searchTailInput.value = String(chip.value || "");
    }

    renderTokenizedSearchChips();
    applyTokenizedSearchStateWithoutRender();

    if (searchTailInput) {
      searchTailInput.focus();
      searchTailInput.setSelectionRange(
        searchTailInput.value.length,
        searchTailInput.value.length
      );
    }
    return true;
  }

  function maybeBeginTokenizedChipFromTail(rawTail) {
    var splitMatch = String(rawTail || "").match(/^(.+?)(?:\:|\uFF1A)(.*)$/);
    if (!splitMatch) {
      return false;
    }

    var rawPrefix = String(splitMatch[1] || "").trim();
    if (!rawPrefix) {
      return false;
    }

    var field = resolveTagFieldFromPrefix(rawPrefix);
    if (!field) {
      return false;
    }

    var valueRemainder = String(splitMatch[2] || "").replace(/^\s+/, "");
    return beginActiveTokenizedChip(field, valueRemainder, false);
  }

  function commitTailInputToTagChips() {
    if (!searchTailInput) {
      return false;
    }

    var rawTail = String(searchTailInput.value || "").trim();
    if (!rawTail) {
      return false;
    }

    var parsed = parseSearchQuery(rawTail);
    if (!parsed.tags || parsed.tags.length === 0) {
      return false;
    }

    var addedAny = false;
    for (var i = 0; i < parsed.tags.length; i++) {
      var tag = parsed.tags[i];
      var valueText = tag.terms.join(" ");
      if (pushTokenizedSearchChip(tag.field, valueText, false, false)) {
        addedAny = true;
      }
    }

    searchTailInput.value = parsed.terms.join(" ");
    renderTokenizedSearchChips();
    return addedAny;
  }

  function extractGifFirstFrameDataUrl(animSrc, callback) {
    var probe = new Image();
    probe.onload = function () {
      try {
        var canvas = document.createElement("canvas");
        var width = probe.naturalWidth || probe.width || 16;
        var height = probe.naturalHeight || probe.height || 16;
        canvas.width = width;
        canvas.height = height;
        var ctx = canvas.getContext("2d");
        if (!ctx) {
          callback(animSrc);
          return;
        }
        ctx.clearRect(0, 0, width, height);
        ctx.drawImage(probe, 0, 0, width, height);
        callback(canvas.toDataURL("image/png"));
      } catch (err) {
        callback(animSrc);
      }
    };
    probe.onerror = function () {
      callback(animSrc);
    };
    probe.src = animSrc;
  }

  function bindHoverAnimation(slot, sprite) {
    if (!sprite) {
      return;
    }

    var staticSrc = String(sprite.getAttribute("data-static-src") || "");
    var animSrc = String(sprite.getAttribute("data-anim-src") || "");
    var fallbackSrc = String(sprite.getAttribute("data-fallback-src") || "");
    if (!animSrc) {
      return;
    }

    var applyFallback = function () {
      if (!fallbackSrc) {
        return;
      }
      sprite.setAttribute("src", fallbackSrc);
      sprite.setAttribute("data-anim-src", fallbackSrc);
      sprite.setAttribute("data-static-src", fallbackSrc);
      sprite.setAttribute("data-state", "static");
      staticSrc = fallbackSrc;
      animSrc = fallbackSrc;
    };

    sprite.addEventListener("error", applyFallback);

    var preload = new Image();
    preload.onerror = applyFallback;
    preload.src = animSrc;

    var showAnim = function () {
      var liveAnimSrc = String(sprite.getAttribute("data-anim-src") || animSrc || "");
      if (!liveAnimSrc) {
        return;
      }
      if (sprite.getAttribute("data-state") !== "anim") {
        sprite.setAttribute("src", liveAnimSrc);
        sprite.setAttribute("data-state", "anim");
      }
    };

    var showStatic = function () {
      var liveStaticSrc = String(sprite.getAttribute("data-static-src") || staticSrc || "");
      if (!liveStaticSrc) {
        return;
      }
      if (sprite.getAttribute("data-state") !== "static") {
        sprite.setAttribute("src", liveStaticSrc);
        sprite.setAttribute("data-state", "static");
      }
    };

    var finalizeBinding = function () {
      showStatic();
      slot.addEventListener("mouseenter", showAnim);
      slot.addEventListener("mouseleave", showStatic);
      slot.addEventListener("focusin", showAnim);
      slot.addEventListener("focusout", showStatic);
    };

    if (!staticSrc) {
      extractGifFirstFrameDataUrl(animSrc, function (dataUrl) {
        staticSrc = dataUrl || animSrc;
        sprite.setAttribute("data-static-src", staticSrc);
        finalizeBinding();
      });
      return;
    }

    finalizeBinding();
  }

  function leftPad(value, width) {
    var out = String(Math.max(0, Math.floor(value)));
    while (out.length < width) {
      out = "0" + out;
    }
    return out;
  }

  function formatCountdown(totalSeconds) {
    var seconds = Math.max(0, Math.floor(totalSeconds));
    var hours = Math.floor(seconds / 3600);
    var minutes = Math.floor((seconds % 3600) / 60);
    var secs = seconds % 60;
    var hourWidth = hours >= 100 ? 3 : 2;
    return (
      leftPad(hours, hourWidth) +
      "h:" +
      leftPad(minutes, 2) +
      "m:" +
      leftPad(secs, 2) +
      "s"
    );
  }

  function clamp01(value) {
    return Math.min(1, Math.max(0, value));
  }

  function lerp(a, b, t) {
    return a + (b - a) * t;
  }

  function blendColor(fromColor, toColor, t) {
    var ratio = clamp01(t);
    return [
      Math.round(lerp(fromColor[0], toColor[0], ratio)),
      Math.round(lerp(fromColor[1], toColor[1], ratio)),
      Math.round(lerp(fromColor[2], toColor[2], ratio)),
    ];
  }

  function rgbCss(rgb) {
    return "rgb(" + rgb[0] + ", " + rgb[1] + ", " + rgb[2] + ")";
  }

  function applyCountdownColor(node, remainingSeconds, lifetimeSeconds) {
    var safeLifetime =
      lifetimeSeconds > 0 ? lifetimeSeconds : DEFAULT_EXCHANGE_LIFETIME_SECONDS;
    var remainingRatio = Math.min(
      1,
      Math.max(0, remainingSeconds / safeLifetime)
    );
    var colorRgb;

    if (remainingRatio <= 0.05) {
      colorRgb = COLOR_RED;
    } else if (remainingRatio <= 0.25) {
      colorRgb = blendColor(
        COLOR_RED,
        COLOR_YELLOW,
        (remainingRatio - 0.05) / 0.20
      );
    } else {
      colorRgb = blendColor(
        COLOR_YELLOW,
        COLOR_GREEN,
        (remainingRatio - 0.25) / 0.75
      );
    }

    node.style.color = rgbCss(colorRgb);
  }

  function expireSlot(slot) {
    if (!slot || !slot.isConnected) {
      return;
    }
    if (slot.getAttribute("data-expiring") === "1") {
      return;
    }
    slot.setAttribute("data-expiring", "1");
    slot.classList.add("trade-slot-expiring");

    window.setTimeout(function () {
      if (slot.parentNode) {
        slot.parentNode.removeChild(slot);
      }
      pruneStaleEntries();
      applySearchFilter();
    }, 520);
  }

  function bindCountdown(slot) {
    var node = slot.querySelector(".trade-remaining");
    if (!node) {
      return;
    }

    var nowEpoch = Math.floor(Date.now() / 1000);
    var createdEpoch = parseInt(
      node.getAttribute("data-exchange-created-epoch"),
      10
    );
    var expireEpoch = parseInt(
      node.getAttribute("data-exchange-expire-epoch"),
      10
    );
    var lifetimeSeconds = parseInt(
      node.getAttribute("data-exchange-lifetime-seconds"),
      10
    );

    if (!Number.isFinite(lifetimeSeconds) || lifetimeSeconds <= 0) {
      lifetimeSeconds = DEFAULT_EXCHANGE_LIFETIME_SECONDS;
    }

    if (!Number.isFinite(createdEpoch) || createdEpoch <= 0) {
      createdEpoch = 0;
    }

    if (!Number.isFinite(expireEpoch) || expireEpoch <= 0) {
      if (createdEpoch > 0) {
        expireEpoch = createdEpoch + lifetimeSeconds;
      } else {
        expireEpoch = nowEpoch + lifetimeSeconds;
      }
    }

    countdownEntries.push({
      slot: slot,
      node: node,
      expireEpoch: expireEpoch,
      lifetimeSeconds: lifetimeSeconds,
    });
  }

  function tickCountdowns() {
    var nowEpoch = Math.floor(Date.now() / 1000);
    for (var i = 0; i < countdownEntries.length; i++) {
      var entry = countdownEntries[i];
      if (!entry.slot || !entry.slot.isConnected) {
        continue;
      }

      var remainingSeconds = Math.max(0, entry.expireEpoch - nowEpoch);
      entry.node.textContent = formatCountdown(remainingSeconds);
      applyCountdownColor(entry.node, remainingSeconds, entry.lifetimeSeconds);

      if (remainingSeconds <= 0) {
        expireSlot(entry.slot);
      }
    }
    pruneStaleEntries();
  }

  function textOf(slot, selector) {
    var node = slot.querySelector(selector);
    if (!node) {
      return "";
    }
    return normalizeSearchText(node.textContent || "");
  }

  function textOfAny(slot, selectors) {
    for (var i = 0; i < selectors.length; i++) {
      var node = slot.querySelector(selectors[i]);
      if (!node) {
        continue;
      }
      var text = normalizeSearchText(node.textContent || "");
      if (text) {
        return text;
      }
    }
    return "";
  }

  function normalizedDataAttribute(node, attrName) {
    if (!node) {
      return "";
    }
    return normalizeSearchText(node.getAttribute(attrName) || "");
  }

  function mergeSearchParts(parts) {
    return normalizeSearchText(parts.join(" "));
  }

  function extractOfferGender(slot) {
    var node = slot.querySelector(".offer-gender");
    if (node) {
      if (node.classList.contains("gender-male")) {
        return "male";
      }
      if (node.classList.contains("gender-female")) {
        return "female";
      }
    }

    var fixedNode = slot.querySelector(".offer-gender-fixed");
    if (!fixedNode) {
      return "";
    }
    var symbolText = String(fixedNode.textContent || "");
    if (symbolText.indexOf("\u2642") >= 0) {
      return "male";
    }
    if (symbolText.indexOf("\u2640") >= 0) {
      return "female";
    }

    return "";
  }

  function extractOfferLevel(slot) {
    var node = slot.querySelector(".offer-level, .offer-level-digits, .offer-level-fixed");
    if (!node) {
      return 0;
    }
    var numeric = String(node.textContent || "").replace(/[^\d]/g, "");
    var parsed = parseInt(numeric, 10);
    if (!Number.isFinite(parsed)) {
      return 0;
    }
    return parsed;
  }

  function languageTokensForRegionCode(gameRegion) {
    switch (gameRegion) {
      case "e":
        return "eng usa united states english";
      case "p":
        return "eng eur europe english";
      case "u":
        return "eng aus australia english";
      case "f":
        return "fra france french";
      case "d":
        return "ger germany german";
      case "i":
        return "ita italy italian";
      case "s":
        return "spa spain spanish";
      case "j":
        return "jpn japan japanese";
      default:
        return "";
    }
  }

  function countryTokensForRegionCode(gameRegion) {
    switch (gameRegion) {
      case "e":
        return "usa united states america";
      case "p":
        return "europe eur eu";
      case "u":
        return "australia aus";
      case "f":
        return "france";
      case "d":
        return "germany";
      case "i":
        return "italy";
      case "s":
        return "spain";
      case "j":
        return "japan";
      case "int":
        return "international";
      case "eng":
        return "english all";
      default:
        return "";
    }
  }

  function getSearchEntry(slot) {
    var regionNode = slot.querySelector(".region, .offer-region-line");
    var languageCode = regionNode
      ? normalizeSearchText(regionNode.textContent || "")
      : "";
    var languageTitle = regionNode
      ? normalizeSearchText(regionNode.getAttribute("title") || "")
      : "";
    var offerSpeciesVisible = textOfAny(slot, [".offer-species", ".offer-species-fixed"]);
    var wantedSpeciesVisible = textOfAny(slot, [".request-species"]);
    var offerSpeciesLocalized = normalizedDataAttribute(slot, "data-offer-species-search");
    var wantedSpeciesLocalized = normalizedDataAttribute(slot, "data-request-species-search");
    var offerSpecies = mergeSearchParts([offerSpeciesVisible, offerSpeciesLocalized]);
    var wantedSpecies = mergeSearchParts([wantedSpeciesVisible, wantedSpeciesLocalized]);
    var nickname = textOf(slot, ".offer-name");
    var otName = textOf(slot, ".offer-ot");
    var offerer = textOf(slot, ".offer-offerer");
    var item = textOf(slot, ".offer-item");
    var hasItem = item.length > 0;
    var gameRegion = normalizeSearchText(slot.getAttribute("data-game-region") || "");
    var offerGender = extractOfferGender(slot);
    var offerLevel = extractOfferLevel(slot);
    var isShiny = !!slot.querySelector(".trade-offer.shiny, .shiny-mark");
    var pokerusLine = slot.querySelector(".offer-pokerus-line");
    var hasPokerus = !!(
      pokerusLine && pokerusLine.classList.contains("pokerus-active")
    );
    var hasPokerusCured = !!slot.querySelector(".offer-pokerus-cured");
    var hasPokerusInfected = !!slot.querySelector(".offer-pokerus-infected");
    var pokerusState = "none";
    if (hasPokerus) {
      if (hasPokerusCured) {
        pokerusState = "cured";
      } else if (hasPokerusInfected) {
        pokerusState = "infected";
      } else {
        pokerusState = "active";
      }
    }

    if (hasItem) {
      var compactItem = item.replace(/\s+/g, "");
      if (compactItem && compactItem !== item) {
        item = item + " " + compactItem;
      }
      item += " yes true with holding held attached";
    } else {
      item = "none no false without noitem itemless";
    }

    var shinyField = isShiny ? "shiny yes true" : "no false notshiny";
    var genderField = offerGender;
    if (offerGender === "male") {
      genderField += " m man boy masculine";
    } else if (offerGender === "female") {
      genderField += " f woman girl feminine";
    }
    var levelField = "";
    if (offerLevel >= 1 && offerLevel <= 100) {
      levelField =
        String(offerLevel) +
        " l" +
        String(offerLevel) +
        " lv" +
        String(offerLevel) +
        " level" +
        String(offerLevel);
    }
    var pokerusField = hasPokerus
      ? "pokerus pkrs yes true active " + pokerusState
      : "none no false clean uninfected";

    var fields = {
      wanted: wantedSpecies,
      offerer: offerer,
      offer: offerSpecies,
      species: offerSpecies,
      language: (
        languageCode +
        " " +
        languageTitle +
        " " +
        languageTokensForRegionCode(gameRegion)
      ).trim(),
      country: (languageTitle + " " + countryTokensForRegionCode(gameRegion)).trim(),
      item: item,
      name: nickname,
      ot: otName,
      shiny: shinyField,
      gender: genderField,
      level: levelField,
      pokerus: pokerusField,
    };

    var combined = (
      fields.wanted +
      " " +
      fields.offerer +
      " " +
      fields.offer +
      " " +
      fields.species +
      " " +
      fields.language +
      " " +
      fields.country +
      " " +
      fields.item +
      " " +
      fields.name +
      " " +
      fields.ot +
      " " +
      fields.shiny +
      " " +
      fields.gender +
      " " +
      fields.level +
      " " +
      fields.pokerus
    ).trim();

    var fieldTokenSets = Object.create(null);
    var fieldNames = Object.keys(fields);
    for (var i = 0; i < fieldNames.length; i++) {
      var fieldName = fieldNames[i];
      fieldTokenSets[fieldName] = buildTokenSet(tokenize(fields[fieldName]));
    }

    return {
      slot: slot,
      fields: fields,
      fieldTokenSets: fieldTokenSets,
      combined: combined,
      combinedTokenSet: buildTokenSet(tokenize(combined)),
      meta: {
        isShiny: isShiny,
        gender: offerGender,
        level: offerLevel,
        hasItem: hasItem,
        hasPokerus: hasPokerus,
        pokerusState: pokerusState,
      },
    };
  }

  function bindSearch(slot) {
    searchEntries.push(getSearchEntry(slot));
  }

  function splitTokenOnColon(token) {
    var value = String(token || "");
    var idxAscii = value.indexOf(":");
    var idxWide = value.indexOf("\uFF1A");
    var idx = -1;

    if (idxAscii >= 0 && idxWide >= 0) {
      idx = Math.min(idxAscii, idxWide);
    } else {
      idx = Math.max(idxAscii, idxWide);
    }

    if (idx <= 0) {
      return null;
    }
    return {
      keyPart: value.slice(0, idx),
      valuePart: value.slice(idx + 1),
    };
  }

  function tokenStartsTag(tokens, index) {
    var split = splitTokenOnColon(tokens[index]);
    if (!split) {
      return false;
    }

    var oneWordKey = normalizeTagKey(split.keyPart);
    if (TAG_ALIASES[oneWordKey]) {
      return true;
    }

    if (index > 0) {
      var twoWordKey = normalizeTagKey(tokens[index - 1] + " " + split.keyPart);
      if (TAG_ALIASES[twoWordKey]) {
        return true;
      }
    }

    return false;
  }

  function parseSearchQuery(raw) {
    var input = String(raw || "");
    var tags = [];
    var generalTokens = [];
    var rawTokens = input.trim().length > 0 ? input.trim().split(/\s+/) : [];

    if (rawTokens.length === 0) {
      return {
        terms: [],
        tags: tags,
      };
    }

    for (var i = 0; i < rawTokens.length; ) {
      var split = splitTokenOnColon(rawTokens[i]);
      var field = null;
      var valueHead = "";
      var consumedPrevGeneral = false;

      if (split) {
        var oneWord = normalizeTagKey(split.keyPart);
        if (TAG_ALIASES[oneWord]) {
          field = TAG_ALIASES[oneWord];
          valueHead = split.valuePart;
        } else if (i > 0) {
          var twoWord = normalizeTagKey(rawTokens[i - 1] + " " + split.keyPart);
          if (TAG_ALIASES[twoWord] && generalTokens.length > 0) {
            field = TAG_ALIASES[twoWord];
            valueHead = split.valuePart;
            consumedPrevGeneral = true;
          }
        }
      }

      if (!field) {
        generalTokens.push(rawTokens[i]);
        i++;
        continue;
      }

      if (consumedPrevGeneral) {
        generalTokens.pop();
      }

      var valueTokens = [];
      if (valueHead.length > 0) {
        valueTokens.push(valueHead);
      }

      i++;
      while (i < rawTokens.length) {
        if (tokenStartsTag(rawTokens, i)) {
          break;
        }

        var probeTerm = normalizeSearchText(rawTokens[i]);
        if (
          field !== "level" &&
          i === rawTokens.length - 1 &&
          isExactTokenTerm(probeTerm)
        ) {
          break;
        }

        valueTokens.push(rawTokens[i]);
        i++;
      }

      var parsedTerms = tokenize(valueTokens.join(" "));
      if (parsedTerms.length > 0) {
        tags.push({
          field: field,
          terms: parsedTerms,
          raw: valueTokens.join(" ").trim(),
        });
      }
    }

    return {
      terms: tokenize(generalTokens.join(" ")),
      tags: tags,
    };
  }

  function containsAllTerms(value, terms, tokenSet) {
    if (!terms || terms.length === 0) {
      return true;
    }

    var haystack = String(value || "");
    for (var i = 0; i < terms.length; i++) {
      var term = terms[i];
      if (isStrictTokenTerm(term)) {
        if (!tokenSet || !tokenSet[term]) {
          return false;
        }
        continue;
      }

      if (haystack.indexOf(term) < 0) {
        return false;
      }
    }
    return true;
  }

  function matchShinyTag(entry, terms) {
    var parsed = parseBooleanFromTerms(terms);
    if (parsed === null) {
      return containsAllTerms(
        entry.fields.shiny,
        terms,
        entry.fieldTokenSets.shiny
      );
    }
    return entry.meta.isShiny === parsed;
  }

  function matchGenderTag(entry, terms) {
    var parsed = parseGenderFromTerms(terms);
    if (!parsed) {
      return containsAllTerms(
        entry.fields.gender,
        terms,
        entry.fieldTokenSets.gender
      );
    }
    return entry.meta.gender === parsed;
  }

  function matchLevelTag(entry, terms, rawValue) {
    var parsedRange = parseLevelRangeFromRaw(rawValue);
    if (!parsedRange) {
      parsedRange = parseLevelRangeFromTerms(terms);
    }
    if (parsedRange) {
      if (
        parsedRange.min < 1 ||
        parsedRange.max > 100 ||
        parsedRange.min > parsedRange.max
      ) {
        return false;
      }
      return (
        entry.meta.level >= parsedRange.min && entry.meta.level <= parsedRange.max
      );
    }

    var parsedLevel = parseLevelNumberFromTerms(terms);
    if (parsedLevel === null) {
      return containsAllTerms(entry.fields.level, terms, entry.fieldTokenSets.level);
    }
    if (parsedLevel < 1 || parsedLevel > 100) {
      return false;
    }
    return entry.meta.level === parsedLevel;
  }

  function matchPokerusTag(entry, terms) {
    var parsed = parseBooleanFromTerms(terms);
    if (parsed !== null) {
      return entry.meta.hasPokerus === parsed;
    }
    return containsAllTerms(
      entry.fields.pokerus,
      terms,
      entry.fieldTokenSets.pokerus
    );
  }

  function matchItemTag(entry, terms) {
    var parsed = parseBooleanFromTerms(terms);
    if (parsed !== null) {
      return entry.meta.hasItem === parsed;
    }
    return containsAllTerms(entry.fields.item, terms, entry.fieldTokenSets.item);
  }

  function entryMatchesQuery(entry, query) {
    if (!containsAllTerms(entry.combined, query.terms, entry.combinedTokenSet)) {
      return false;
    }

    for (var i = 0; i < query.tags.length; i++) {
      var tag = query.tags[i];
      if (
        tag.terms &&
        tag.terms.length > 0 &&
        tag.terms.every(function (term) { return !!ANY_TAG_TERMS[term]; })
      ) {
        continue;
      }

      if (tag.field === "shiny") {
        if (!matchShinyTag(entry, tag.terms)) {
          return false;
        }
        continue;
      }

      if (tag.field === "gender") {
        if (!matchGenderTag(entry, tag.terms)) {
          return false;
        }
        continue;
      }

      if (tag.field === "level") {
        if (!matchLevelTag(entry, tag.terms, tag.raw)) {
          return false;
        }
        continue;
      }

      if (tag.field === "pokerus") {
        if (!matchPokerusTag(entry, tag.terms)) {
          return false;
        }
        continue;
      }

      if (tag.field === "item") {
        if (!matchItemTag(entry, tag.terms)) {
          return false;
        }
        continue;
      }

      if (
        !containsAllTerms(
          entry.fields[tag.field] || "",
          tag.terms,
          entry.fieldTokenSets[tag.field]
        )
      ) {
        return false;
      }
    }

    return true;
  }

  function updateNoResultsState(visibleCount) {
    if (!noResultsNode) {
      noResultsNode = document.getElementById("trade-no-results");
    }
    if (!noResultsNode) {
      return;
    }

    var hasVisible = visibleCount > 0;
    if (!hasVisible) {
      var slotsInDom = document.querySelectorAll(".crystal-trade-slot");
      for (var i = 0; i < slotsInDom.length; i++) {
        if (slotsInDom[i].style.display !== "none") {
          hasVisible = true;
          break;
        }
      }
    }

    noResultsNode.style.display = hasVisible ? "none" : "flex";
  }

  function applySearchFilter() {
    var queryText = searchInput ? String(searchInput.value || "") : "";
    var normalizedQuery = normalizeSearchText(queryText);

    // Empty query should always show every card by default.
    if (normalizedQuery === "") {
      var allSlots = document.querySelectorAll(".crystal-trade-slot");
      for (var s = 0; s < allSlots.length; s++) {
        allSlots[s].style.display = "";
      }
      updateNoResultsState(allSlots.length);
      return;
    }

    var query = parseSearchQuery(queryText);
    var visibleCount = 0;
    for (var i = 0; i < searchEntries.length; i++) {
      var entry = searchEntries[i];
      if (!entry.slot || !entry.slot.isConnected) {
        continue;
      }
      var shouldShow = entryMatchesQuery(entry, query);
      entry.slot.style.display = shouldShow ? "" : "none";
      if (shouldShow) {
        visibleCount += 1;
      }
    }
    updateNoResultsState(visibleCount);
  }

  function pruneStaleEntries() {
    countdownEntries = countdownEntries.filter(function (entry) {
      return entry.slot && entry.slot.isConnected;
    });
    searchEntries = searchEntries.filter(function (entry) {
      return entry.slot && entry.slot.isConnected;
    });
  }

  function sizeRegionFilterToLongestOption() {
    if (!regionFilterSelect || !regionFilterSelect.options) {
      return;
    }

    var options = regionFilterSelect.options;
    if (options.length === 0) {
      return;
    }

    var style = window.getComputedStyle(regionFilterSelect);
    var font =
      style.fontStyle +
      " " +
      style.fontWeight +
      " " +
      style.fontSize +
      " " +
      style.fontFamily;

    var canvas = sizeRegionFilterToLongestOption._canvas;
    if (!canvas) {
      canvas = document.createElement("canvas");
      sizeRegionFilterToLongestOption._canvas = canvas;
    }

    var ctx = canvas.getContext("2d");
    if (!ctx) {
      return;
    }
    ctx.font = font;

    var maxWidth = 0;
    for (var i = 0; i < options.length; i++) {
      var label = String(options[i].text || "");
      maxWidth = Math.max(maxWidth, ctx.measureText(label).width);
    }

    var controlPaddingAndArrow = 52;
    var targetWidth = Math.ceil(maxWidth + controlPaddingAndArrow);
    var maxViewportWidth = Math.max(180, window.innerWidth - 20);
    var finalWidth = Math.min(targetWidth, maxViewportWidth) + "px";
    regionFilterSelect.style.setProperty("width", finalWidth, "important");
    regionFilterSelect.style.setProperty("min-width", finalWidth, "important");
    regionFilterSelect.style.setProperty("max-width", finalWidth, "important");
  }

  function initRegionFilter() {
    regionFilterSelect = document.getElementById("trade-region-filter");
    if (!regionFilterSelect) {
      return;
    }

    sizeRegionFilterToLongestOption();
    window.addEventListener("resize", sizeRegionFilterToLongestOption);
    if (document.fonts && typeof document.fonts.ready === "object") {
      document.fonts.ready.then(sizeRegionFilterToLongestOption);
    }
    window.addEventListener("load", sizeRegionFilterToLongestOption);
    window.setTimeout(sizeRegionFilterToLongestOption, 120);

    regionFilterSelect.addEventListener("change", function () {
      var selectedValue = normalizeSearchText(regionFilterSelect.value || "global");
      if (!REGION_FILTER_VALUES[selectedValue]) {
        selectedValue = "global";
      }

      var url = new URL(window.location.href);
      if (selectedValue === "global") {
        url.searchParams.delete("region");
      } else {
        url.searchParams.set("region", selectedValue);
      }
      window.location.assign(url.toString());
    });
  }

  function removeTokenizedSearchChip(index) {
    if (index < 0 || index >= tokenizedSearchChips.length) {
      return;
    }
    tokenizedSearchChips.splice(index, 1);
    for (var d = 0; d < deferredGeneralSegments.length; d++) {
      var seg = deferredGeneralSegments[d];
      if (!seg) {
        continue;
      }
      if (seg.index > index) {
        seg.index -= 1;
      } else if (seg.index > tokenizedSearchChips.length) {
        seg.index = tokenizedSearchChips.length;
      }
    }
    normalizeDeferredGeneralSegments();
    if (activeTokenizedChipIndex === index) {
      activeTokenizedChipIndex = -1;
      if (searchTailInput) {
        searchTailInput.value = "";
      }
    } else if (activeTokenizedChipIndex > index) {
      activeTokenizedChipIndex -= 1;
    }
    renderTokenizedSearchChips();
  }

  function applyTokenizedSearchState() {
    syncActiveTokenizedChipFromTail();
    renderTokenizedSearchChips();
    syncTagPillAvailability();
    syncTokenizedSearchToHiddenInput();
    applySearchFilter();
  }

  function applyTokenizedSearchStateWithoutRender() {
    syncActiveTokenizedChipFromTail();
    syncTagPillAvailability();
    updateTailInputInlineWidth();
    syncTokenizedSearchToHiddenInput();
    applySearchFilter();
  }

  function bindTagPillEvents() {
    if (!searchTagPills) {
      return;
    }

    var resolveTagPillTarget = function (eventTarget) {
      if (!eventTarget) {
        return null;
      }
      var target = eventTarget.closest
        ? eventTarget.closest(".trade-search-tag-pill")
        : eventTarget;
      if (
        !target ||
        !target.classList ||
        !target.classList.contains("trade-search-tag-pill") ||
        !searchTagPills.contains(target)
      ) {
        return null;
      }
      return target;
    };

    var activateTagPill = function (event) {
      var target = resolveTagPillTarget(event.target);
      if (!target || target.disabled) {
        return;
      }
      var field = String(target.getAttribute("data-tag-field") || "").toLowerCase();
      if (!field) {
        return;
      }
      event.preventDefault();
      event.stopPropagation();
      beginActiveTokenizedChip(field, "", true);
      applyTokenizedSearchStateWithoutRender();
    };

    var pointerEventName =
      typeof window !== "undefined" && "PointerEvent" in window
        ? "pointerdown"
        : "mousedown";
    searchTagPills.addEventListener(pointerEventName, activateTagPill);
    searchTagPills.addEventListener("click", function (event) {
      if (event.detail > 0) {
        event.preventDefault();
        return;
      }
      activateTagPill(event);
    });
  }

  function bindTokenChipEvents() {
    if (!searchChipList) {
      return;
    }
    searchChipList.addEventListener("click", function (event) {
      var target = event.target;
      if (!target) {
        return;
      }

      var removeButton = target.closest
        ? target.closest(".trade-search-chip-remove")
        : null;
      if (removeButton && searchChipList.contains(removeButton)) {
        var removeIdx = parseInt(removeButton.getAttribute("data-chip-index") || "-1", 10);
        if (!Number.isFinite(removeIdx) || removeIdx < 0) {
          return;
        }
        removeTokenizedSearchChip(removeIdx);
        applyTokenizedSearchState();
        if (searchTailInput) {
          searchTailInput.focus();
        }
        return;
      }

      var chipNode = target.closest
        ? target.closest(".trade-search-chip")
        : null;
      if (!chipNode || !searchChipList.contains(chipNode)) {
        return;
      }
      var editIdx = parseInt(chipNode.getAttribute("data-chip-index") || "-1", 10);
      if (!Number.isFinite(editIdx) || editIdx < 0) {
        return;
      }
      startEditingTokenizedChip(editIdx);
    });
  }

  function initTokenizedSearchFromExistingValue() {
    if (!searchInput || !searchTailInput) {
      return;
    }
    var existingValue = String(searchInput.value || "").trim();
    if (!existingValue) {
      tokenizedSearchChips = [];
      deferredGeneralSegments = [];
      activeTokenizedChipIndex = -1;
      renderTokenizedSearchChips();
      return;
    }

    var parsed = parseSearchQuery(existingValue);
    tokenizedSearchChips = [];
    deferredGeneralSegments = [];
    activeTokenizedChipIndex = -1;
    for (var i = 0; i < parsed.tags.length; i++) {
      pushTokenizedSearchChip(parsed.tags[i].field, parsed.tags[i].terms.join(" "), false, false);
    }
    searchTailInput.value = parsed.terms.join(" ");
    renderTokenizedSearchChips();
  }

  function exitActiveTokenizedChipEditing() {
    if (activeTokenizedChipIndex < 0) {
      return false;
    }
    finalizeActiveTokenizedChip();
    applyTokenizedSearchState();
    if (searchTailInput) {
      searchTailInput.focus();
      searchTailInput.setSelectionRange(
        searchTailInput.value.length,
        searchTailInput.value.length
      );
    }
    return true;
  }

  function initSearch() {
    searchInput = document.getElementById("trade-search-input");
    searchTailInput = document.getElementById("trade-search-tail-input");
    searchChipList = document.getElementById("trade-search-chip-list");
    searchTokenizedRoot = document.getElementById("trade-search-tokenized");
    searchTagPills = document.getElementById("trade-search-tag-pills");

    if (searchInput && searchTailInput) {
      initTokenizedSearchFromExistingValue();
      bindTokenChipEvents();
      bindTagPillEvents();
      syncTagPillAvailability();

      if (searchTokenizedRoot) {
        searchTokenizedRoot.addEventListener("mousedown", function (event) {
          if (activeTokenizedChipIndex < 0) {
            return;
          }
          var activeChipNode = searchChipList
            ? searchChipList.querySelector(".trade-search-chip-composing")
            : null;
          if (activeChipNode && activeChipNode.contains(event.target)) {
            return;
          }
          if (searchTailInput && event.target === searchTailInput) {
            return;
          }
          event.preventDefault();
          exitActiveTokenizedChipEditing();
        });
      }

      window.addEventListener("resize", scheduleSearchTagPixelSnap);
      if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(scheduleSearchTagPixelSnap);
      }

      document.addEventListener("mousedown", function (event) {
        if (activeTokenizedChipIndex < 0) {
          return;
        }
        if (searchTagPills && searchTagPills.contains(event.target)) {
          return;
        }
        if (!searchTokenizedRoot || searchTokenizedRoot.contains(event.target)) {
          return;
        }
        exitActiveTokenizedChipEditing();
      });

      searchTailInput.addEventListener("input", function () {
        var rawTail = String(searchTailInput.value || "");

        if (activeTokenizedChipIndex < 0 && maybeBeginTokenizedChipFromTail(rawTail)) {
          applyTokenizedSearchStateWithoutRender();
          return;
        }

        if (activeTokenizedChipIndex >= 0) {
          applyTokenizedSearchStateWithoutRender();
          return;
        }

        var hasTagSyntax = rawTail.indexOf(":") >= 0 || rawTail.indexOf("\uFF1A") >= 0;
        var hasTrailingWhitespace = /\s$/.test(rawTail);
        if (hasTagSyntax && hasTrailingWhitespace) {
          commitTailInputToTagChips();
        }
        applyTokenizedSearchState();
      });

      searchTailInput.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
          event.preventDefault();
          if (activeTokenizedChipIndex >= 0) {
            finalizeActiveTokenizedChip();
            applyTokenizedSearchState();
            searchTailInput.focus();
            searchTailInput.setSelectionRange(
              searchTailInput.value.length,
              searchTailInput.value.length
            );
            return;
          }
          commitTailInputToTagChips();
          applyTokenizedSearchState();
          return;
        }

        if (event.key === "Backspace") {
          var currentValue = String(searchTailInput.value || "");
          if (currentValue.length === 0 && activeTokenizedChipIndex >= 0) {
            event.preventDefault();
            removeTokenizedSearchChip(activeTokenizedChipIndex);
            applyTokenizedSearchState();
            return;
          }
          if (currentValue.length === 0 && tokenizedSearchChips.length > 0) {
            event.preventDefault();
            tokenizedSearchChips.pop();
            if (activeTokenizedChipIndex >= tokenizedSearchChips.length) {
              activeTokenizedChipIndex = -1;
            }
            renderTokenizedSearchChips();
            applyTokenizedSearchState();
          }
        }
      });
    } else if (searchInput) {
      searchInput.addEventListener("input", applySearchFilter);
    }

    applyTokenizedSearchState();
  }

  function init() {
    uiLocale = resolveUiLocale();
    applyLocaleTagDisplayLabels();
    initializeTagAliases();
    applyTagPillLabelsToUi();
    addLocalizedAliasesFromUi();
    applyTagPillLabelsToUi();

    var slots = document.querySelectorAll(".crystal-trade-slot");
    slots.forEach(function (slot) {
      var hoverSprites = slot.querySelectorAll("img[data-anim-src]");
      hoverSprites.forEach(function (sprite) {
        bindHoverAnimation(slot, sprite);
      });
      bindCountdown(slot);
      bindSearch(slot);
    });

    initRegionFilter();
    initSearch();

    if (countdownEntries.length > 0) {
      tickCountdowns();
      setInterval(tickCountdowns, 1000);
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();


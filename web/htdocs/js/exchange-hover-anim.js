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
  var regionFilterSelect = null;
  var noResultsNode = null;

  function normalizeSearchText(value) {
    return String(value || "")
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
    };
    var falsy = {
      no: true,
      n: true,
      false: true,
      off: true,
      notshiny: true,
      non: true,
      nein: true,
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
  }

  function addLocalizedAliasesFromUi() {
    var firstSlot = document.querySelector(".crystal-trade-slot");
    if (!firstSlot) {
      return;
    }

    var wantedLabel = firstSlot.querySelector(".wanted-label");
    if (wantedLabel) {
      registerTagAlias(wantedLabel.textContent || "", "wanted");
    }

    var offerLabels = firstSlot.querySelectorAll(".offer-details .label");
    if (offerLabels.length >= 3) {
      registerTagAlias(offerLabels[0].textContent || "", "name");
      registerTagAlias(offerLabels[1].textContent || "", "offerer");
      registerTagAlias(offerLabels[2].textContent || "", "item");
    }

    var levelLabel = firstSlot.querySelector(".level-label");
    if (levelLabel) {
      registerTagAlias(levelLabel.textContent || "", "level");
    }
  }

  function bindHoverAnimation(slot) {
    var sprite = slot.querySelector(".sprite-pokemon-hoveranim");
    if (!sprite) {
      return;
    }

    var staticSrc = sprite.getAttribute("data-static-src");
    var animSrc = sprite.getAttribute("data-anim-src");
    if (!staticSrc || !animSrc) {
      return;
    }

    var preload = new Image();
    preload.src = animSrc;

    var showAnim = function () {
      if (sprite.getAttribute("data-state") !== "anim") {
        sprite.setAttribute("src", animSrc);
        sprite.setAttribute("data-state", "anim");
      }
    };

    var showStatic = function () {
      if (sprite.getAttribute("data-state") !== "static") {
        sprite.setAttribute("src", staticSrc);
        sprite.setAttribute("data-state", "static");
      }
    };

    showStatic();
    slot.addEventListener("mouseenter", showAnim);
    slot.addEventListener("mouseleave", showStatic);
    slot.addEventListener("focusin", showAnim);
    slot.addEventListener("focusout", showStatic);
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
      "h : " +
      leftPad(minutes, 2) +
      "m : " +
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

  function extractOfferGender(slot) {
    var node = slot.querySelector(".offer-gender");
    if (!node) {
      return "";
    }
    if (node.classList.contains("gender-male")) {
      return "male";
    }
    if (node.classList.contains("gender-female")) {
      return "female";
    }
    return "";
  }

  function extractOfferLevel(slot) {
    var node = slot.querySelector(".offer-level");
    if (!node) {
      return 0;
    }
    var parsed = parseInt((node.textContent || "").trim(), 10);
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

  function getSearchEntry(slot) {
    var regionNode = slot.querySelector(".region");
    var languageCode = regionNode
      ? normalizeSearchText(regionNode.textContent || "")
      : "";
    var languageTitle = regionNode
      ? normalizeSearchText(regionNode.getAttribute("title") || "")
      : "";
    var offerSpecies = textOf(slot, ".offer-species");
    var wantedSpecies = textOf(slot, ".request-species");
    var nickname = textOf(slot, ".offer-name");
    var offerer = textOf(slot, ".offer-offerer");
    var item = textOf(slot, ".offer-item");
    var gameRegion = normalizeSearchText(slot.getAttribute("data-game-region") || "");
    var offerGender = extractOfferGender(slot);
    var offerLevel = extractOfferLevel(slot);
    var isShiny = !!slot.querySelector(".trade-offer.shiny, .shiny-mark");

    var compactItem = item.replace(/\s+/g, "");
    if (compactItem && compactItem !== item) {
      item = item + " " + compactItem;
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

    var fields = {
      wanted: wantedSpecies,
      offerer: offerer,
      offer: offerSpecies,
      species: (offerSpecies + " " + wantedSpecies).trim(),
      language: (
        languageCode +
        " " +
        languageTitle +
        " " +
        languageTokensForRegionCode(gameRegion)
      ).trim(),
      item: item,
      name: nickname,
      shiny: shinyField,
      gender: genderField,
      level: levelField,
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
      fields.item +
      " " +
      fields.name +
      " " +
      fields.shiny +
      " " +
      fields.gender +
      " " +
      fields.level
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
      },
    };
  }

  function bindSearch(slot) {
    searchEntries.push(getSearchEntry(slot));
  }

  function splitTokenOnColon(token) {
    var value = String(token || "");
    var idxAscii = value.indexOf(":");
    var idxWide = value.indexOf("：");
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

  function matchLevelTag(entry, terms) {
    var parsedLevel = parseLevelNumberFromTerms(terms);
    if (parsedLevel === null) {
      return containsAllTerms(entry.fields.level, terms, entry.fieldTokenSets.level);
    }
    if (parsedLevel < 1 || parsedLevel > 100) {
      return false;
    }
    return entry.meta.level === parsedLevel;
  }

  function entryMatchesQuery(entry, query) {
    if (!containsAllTerms(entry.combined, query.terms, entry.combinedTokenSet)) {
      return false;
    }

    for (var i = 0; i < query.tags.length; i++) {
      var tag = query.tags[i];

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
        if (!matchLevelTag(entry, tag.terms)) {
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
    regionFilterSelect.style.width =
      Math.min(targetWidth, maxViewportWidth) + "px";
  }

  function initRegionFilter() {
    regionFilterSelect = document.getElementById("trade-region-filter");
    if (!regionFilterSelect) {
      return;
    }

    sizeRegionFilterToLongestOption();
    window.addEventListener("resize", sizeRegionFilterToLongestOption);

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

  function initSearch() {
    searchInput = document.getElementById("trade-search-input");
    if (searchInput) {
      searchInput.addEventListener("input", applySearchFilter);
    }
    applySearchFilter();
  }

  function init() {
    initializeTagAliases();
    addLocalizedAliasesFromUi();

    var slots = document.querySelectorAll(".crystal-trade-slot");
    slots.forEach(function (slot) {
      bindHoverAnimation(slot);
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

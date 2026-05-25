(function () {
  "use strict";

  var SEARCH_FRAME_COUNT = 5;
  var SCALE_STORAGE_KEY = "reon_crystal_trade_scale";
  var MOBILE_SCALE_MEDIA_QUERY = "(max-width: 768px)";
  var supportsElementZoomCache = null;

  function clampScale(raw) {
    return String(raw) === "2" ? "2" : "1";
  }

  function supportsElementZoom() {
    if (supportsElementZoomCache !== null) {
      return supportsElementZoomCache;
    }

    var supported = false;
    if (typeof CSS !== "undefined" && CSS && typeof CSS.supports === "function") {
      supported = CSS.supports("zoom", "2");
    } else if (document && document.documentElement && document.documentElement.style) {
      supported = "zoom" in document.documentElement.style;
    }

    if (supported && document && document.body && document.createElement) {
      var probe = document.createElement("div");
      probe.style.position = "absolute";
      probe.style.left = "-9999px";
      probe.style.top = "0";
      probe.style.width = "12px";
      probe.style.height = "12px";
      probe.style.zoom = "1";
      document.body.appendChild(probe);

      var baseWidth = probe.getBoundingClientRect().width || 12;
      probe.style.zoom = "2";
      var zoomWidth = probe.getBoundingClientRect().width || baseWidth;

      document.body.removeChild(probe);
      supported = zoomWidth >= (baseWidth * 1.8);
    }

    supportsElementZoomCache = !!supported;
    return supportsElementZoomCache;
  }

  function isMobileViewport() {
    if (typeof window === "undefined" || typeof window.matchMedia !== "function") {
      return false;
    }
    return window.matchMedia(MOBILE_SCALE_MEDIA_QUERY).matches;
  }

  function clearMobileScaleFallback(slot) {
    slot.style.removeProperty("--trade-mobile-fallback-slot-width");
    slot.style.removeProperty("--trade-mobile-fallback-slot-height");
    slot.style.removeProperty("--trade-mobile-fallback-transform");
  }

  function applyMobileScaleFallback(page, scale) {
    if (!page) {
      return;
    }

    var shouldFallback = !supportsElementZoom() && isMobileViewport();
    page.classList.toggle("trade-scale-transform-fallback", shouldFallback);

    var slots = page.querySelectorAll(".crystal-trade-slot");
    if (!slots || slots.length === 0) {
      return;
    }

    var scaleNumber = scale === "2" ? 2 : 1;
    for (var i = 0; i < slots.length; i++) {
      var slot = slots[i];
      if (!shouldFallback) {
        clearMobileScaleFallback(slot);
        continue;
      }

      var tradeCard = slot.querySelector(".crystal-trade");
      var warningNode = slot.querySelector(".trade-warning");
      var remainingNode = slot.querySelector(".trade-remaining");
      var cardWidth = tradeCard ? tradeCard.offsetWidth : 222;
      var cardHeight = tradeCard ? tradeCard.offsetHeight : 134;
      var warningHeight = warningNode ? warningNode.offsetHeight : 0;
      var remainingHeight = remainingNode ? remainingNode.offsetHeight : 0;

      var slotWidth = Math.max(248, Math.ceil((cardWidth + 26) * scaleNumber));
      var slotHeight = Math.max(
        182,
        Math.ceil(warningHeight + remainingHeight + (cardHeight * scaleNumber) + 22)
      );

      slot.style.setProperty("--trade-mobile-fallback-slot-width", slotWidth + "px");
      slot.style.setProperty("--trade-mobile-fallback-slot-height", slotHeight + "px");
      slot.style.setProperty("--trade-mobile-fallback-transform", "scale(" + scaleNumber + ")");
    }
  }

  function setSearchFrameForInput(input) {
    if (!input) {
      return;
    }
    var wrap = input.closest(".trade-search-wrap");
    if (!wrap) {
      return;
    }
    var length = String(input.value || "").length;
    var frame = ((length % SEARCH_FRAME_COUNT) + SEARCH_FRAME_COUNT) % SEARCH_FRAME_COUNT;
    wrap.style.setProperty(
      "--search-icon-image",
      "url('/images/crystal/Slowpoke_" + frame + ".png')"
    );
  }

  function initSearchFrames() {
    var inputs = document.querySelectorAll(".trade-search-input");
    if (!inputs || inputs.length === 0) {
      return;
    }

    for (var i = 0; i < inputs.length; i++) {
      (function (input) {
        setSearchFrameForInput(input);
        input.addEventListener("input", function () {
          setSearchFrameForInput(input);
        });
      })(inputs[i]);
    }
  }

  function applyTradeScale(scale) {
    var resolved = clampScale(scale);
    var page = document.querySelector(".exchange-page");
    if (!page) {
      return resolved;
    }
    page.style.setProperty("--trade-card-scale", resolved);
    page.setAttribute("data-trade-scale", resolved);
    applyMobileScaleFallback(page, resolved);
    return resolved;
  }

  function setActiveScaleButton(buttons, scale) {
    for (var i = 0; i < buttons.length; i++) {
      var isActive = buttons[i].getAttribute("data-trade-size") === scale;
      buttons[i].classList.toggle("is-active", isActive);
      buttons[i].setAttribute("aria-pressed", isActive ? "true" : "false");
    }
  }

  function initTradeScaleButtons() {
    var buttons = document.querySelectorAll(".trade-size-btn[data-trade-size]");
    if (!buttons || buttons.length === 0) {
      return false;
    }

    var initial = clampScale(localStorage.getItem(SCALE_STORAGE_KEY) || "1");
    applyTradeScale(initial);
    setActiveScaleButton(buttons, initial);

    for (var i = 0; i < buttons.length; i++) {
      (function (button) {
        button.addEventListener("click", function () {
          var next = clampScale(button.getAttribute("data-trade-size") || "1");
          applyTradeScale(next);
          setActiveScaleButton(buttons, next);
          localStorage.setItem(SCALE_STORAGE_KEY, next);
        });
      })(buttons[i]);
    }

    return true;
  }

  function initTradeScaleSliderFallback() {
    var slider = document.getElementById("trade-size-slider");
    if (!slider) {
      return;
    }

    var initial = clampScale(localStorage.getItem(SCALE_STORAGE_KEY) || slider.value || "1");
    slider.value = initial;
    applyTradeScale(initial);

    slider.addEventListener("input", function () {
      var next = clampScale(slider.value);
      slider.value = next;
      applyTradeScale(next);
      localStorage.setItem(SCALE_STORAGE_KEY, next);
    });
  }

  function initTradeScaleControls() {
    if (initTradeScaleButtons()) {
      return;
    }
    initTradeScaleSliderFallback();
  }

  function preloadSearchFrames() {
    for (var i = 0; i < SEARCH_FRAME_COUNT; i++) {
      var img = new Image();
      img.src = "/images/crystal/Slowpoke_" + i + ".png";
    }
  }

  function init() {
    preloadSearchFrames();
    initSearchFrames();
    initTradeScaleControls();
    window.addEventListener(
      "resize",
      function () {
        var page = document.querySelector(".exchange-page");
        if (!page) {
          return;
        }
        var activeScale = clampScale(
          page.getAttribute("data-trade-scale") || localStorage.getItem(SCALE_STORAGE_KEY) || "1"
        );
        applyMobileScaleFallback(page, activeScale);
      },
      { passive: true }
    );
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();

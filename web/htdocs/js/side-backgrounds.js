/* Shared "themed side background" driver.
 *
 * Several themed pages (Game Boy Wars, Mario Kart, and the pokecrystal pages)
 * paint fixed decorative panels in the margins beside the centered content.
 * They all measured the content column the same way and exposed its left/right
 * insets as CSS custom properties; this consolidates that duplicated logic.
 *
 *   initReonSideBackgrounds("gbwars");
 *   initReonSideBackgrounds("trade-corner", { bodyClass: "honor-roll-theme" });
 *   initReonSideBackgrounds("trade-corner", { bodyClass: "rankings-theme",
 *       onApply: function (left, right) { ... extra vars ... } });
 *
 * Sets `--<prefix>-nav-side-left` / `--<prefix>-nav-side-right` on <html>.
 * options.bodyClass  — class added to <body> on boot (optional).
 * options.onApply    — callback(sideLeft, sideRight) for page-specific vars.
 */
(function () {
  function initReonSideBackgrounds(prefix, options) {
    options = options || {};

    function anchorEl() {
      return document.querySelector(
        "header.navbar.container-xxl, header.navbar, main#content.container-xxl, #content.container-xxl, .container-xxl"
      );
    }

    function apply() {
      var anchor = anchorEl();
      if (!anchor) {
        return;
      }
      var rect = anchor.getBoundingClientRect();
      var sideLeft = Math.max(0, Math.round(rect.left));
      var sideRight = Math.max(0, Math.round(window.innerWidth - rect.right));
      var style = document.documentElement.style;
      style.setProperty("--" + prefix + "-nav-side-left", sideLeft + "px");
      style.setProperty("--" + prefix + "-nav-side-right", sideRight + "px");
      if (typeof options.onApply === "function") {
        options.onApply(sideLeft, sideRight);
      }
    }

    function boot() {
      if (options.bodyClass) {
        document.body.classList.add(options.bodyClass);
      }
      apply();
      window.addEventListener("resize", apply, { passive: true });
      window.addEventListener("load", apply, { passive: true });
      if (document.fonts && typeof document.fonts.ready === "object") {
        document.fonts.ready.then(apply);
      }
    }

    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", boot);
    } else {
      boot();
    }
  }

  window.initReonSideBackgrounds = initReonSideBackgrounds;
})();

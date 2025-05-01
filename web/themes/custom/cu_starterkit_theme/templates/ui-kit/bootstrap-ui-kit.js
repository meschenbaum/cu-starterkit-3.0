((Drupal, once) => {

  // Show the correct glossary item.
  const hash = location.hash.replace(/^#/, "");
  if (hash) {
    let triggerEl = document.querySelector("#" + hash + "-tab");
    if (triggerEl) {
      triggerEl.click();
    }
  }

  /**
   * UI Kit: Hash navigation support.
   */
  Drupal.behaviors.bootstrapUiKitNav = {
    attach: function attach(context) {
        once("bootstrap-ui-kit-nav", '.bootstrap-ui-kit__glossary [data-bs-toggle="tab"]', context).forEach((element) => {
        element.addEventListener("shown.bs.tab", () => {
          window.history.replaceState(
            window.history.state,
            null,
            element.dataset.bsTarget
          );
        });
      });
    },
  };

  /**
   * UI Kit: Copy URL button.
   */
  Drupal.behaviors.bootstrapUiKitCopyUrl = {
    attach(context) {
      once("ui-kit-copy-url", ".btn-copy__link", context).forEach((element) => {
        let tooltip;

        async function copyTargetText(text) {
          try {
            await navigator.clipboard.writeText(text);
          } catch (err) {
            console.error("Failed to copy (make sure your accessing the site using https): ", err);
          }
        }

        element.addEventListener("click", (e) => {
          copyTargetText(window.location.href);

          if (tooltip) {
            tooltip.dispose();
          }
          tooltip = new bootstrap.Tooltip(element, {
            title: Drupal.t("URL Copied!"),
            trigger: "manual",
            placement: "auto",
          });
          tooltip.show();

          setTimeout(function () {
            tooltip.hide();
          }, 2000)
        });

      });
    },
  };

})(Drupal, once);

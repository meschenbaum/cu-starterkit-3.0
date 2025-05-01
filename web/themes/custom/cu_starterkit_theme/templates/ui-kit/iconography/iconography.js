((Drupal, drupalSettings, once) => {
  /**
   * UI Kit: Copy URL button.
   */
  Drupal.behaviors.bootstrapUiKitCopyIcon = {
    attach(context) {
      once("ui-kit-copy-icon", ".btn-copy__icon", context).forEach((element) => {
        let tooltip;

        async function copyTargetText(text) {
          try {
            await navigator.clipboard.writeText(text);
          } catch (err) {
            console.error("Failed to copy (make sure your accessing the site using https): ", err);
          }
        }

        element.addEventListener("click", (e) => {
          copyTargetText(element.lastElementChild.innerHTML);

          if (tooltip) {
            tooltip.dispose();
          }
          tooltip = new bootstrap.Tooltip(element, {
            title: Drupal.t("Name copied!"),
            trigger: "manual",
            placement: "bottom",
          });
          tooltip.show();

          setTimeout(function () {
            tooltip.hide();
          }, 2000)
        });

      });
    },
  };
})(Drupal, drupalSettings, once);

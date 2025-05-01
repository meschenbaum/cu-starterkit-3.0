<?php

namespace Drupal\cu_starterkit_theme;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Theme\ThemeManagerInterface;

/**
 * CU Starterkit theme settings manager.
 */
class SettingsManager {

  use StringTranslationTrait;

  /**
   * The theme manager.
   *
   * @var \Drupal\Core\Theme\ThemeManagerInterface
   */
  protected $themeManager;

  /**
   * Constructs a WebformThemeManager object.
   *
   * @param \Drupal\Core\Theme\ThemeManagerInterface $theme_manager
   *   The theme manager.
   */
  public function __construct(ThemeManagerInterface $theme_manager) {
    $this->themeManager = $theme_manager;
  }

  /**
   * Alters theme settings form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param string $form_id
   *   The form id.
   *
   * @see hook_form_alter()
   */
  public function themeSettingsAlter(array &$form, FormStateInterface $form_state, $form_id) {
    // Work-around for a core bug affecting admin themes. See issue #943212.
    if (isset($form_id)) {
      return;
    }

    // Search integration
    $searchIntegration = '';
    if (!\Drupal::moduleHandler()
      ->moduleExists('search')) {
      $searchIntegration = $this->t("To use <strong>Local Site Search</strong> you will need to enable Drupal Search module");
    }

    $options_theme = [
      'none' => 'do not apply theme',
      'light' => 'light (dark text/links against a light background)',
      'dark' => 'dark (light/white text/links against a dark background)',
    ];

    $options_color = [
      'none' => 'do not apply color',
      'primary' => 'primary',
      'secondary' => 'secondary',
      'light' => 'light',
      'dark' => 'dark',
    ];

    // Populating options for top container.
    $options_top_container = [
      'container' => 'fixed',
      'container-fluid m-0 p-0' => 'fluid',
    ];

    $options_search = [
      'none' => 'No search',
      'local' => 'Local site search (Drupal)',
      'cu' => 'CU Search (Google PSE)',
    ];

    $options_branding = [
      'cusys' => 'CU System',
      //'anschutz' => 'CU Anschutz',
      //'denver' => 'CU Denver',
      //'uccs' => 'CU Colorado Springs',
    ];

    // Populating extra options for top container.
    foreach (explode("\n", theme_get_setting('cuskt_top_container_config') ?? '') as $line) {
      $values = explode("|", trim($line) ?? '');
      if (is_array($values) && (count($values) == 2)) {
        $options_top_container += [trim($values[0]) => trim($values[1])];
      }
    }

    // Branding
    $form['branding'] = [
      '#type' => 'details',
      '#title' => $this->t('Branding options'),
      '#description' => $this->t('Theme branding to apply.'),
      '#open' => TRUE,
    ];

    $form['branding']['cuskt_branding'] = [
      '#type' => 'select',
      '#title' => $this->t('Site branding'),
      '#default_value' => theme_get_setting('cuskt_branding'),
      '#description' => $this->t("Site branding to use."),
      '#options' => $options_branding,
    ];

    $form['branding']['logo'] = [
      '#type' => 'details',
      '#title' => $this->t('SVG Sprite Logo'),
    ];

    $form['branding']['logo']['cuskt_branding_logo_use_svg_sprite'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use SVG Sprite Logo'),
      '#default_value' => theme_get_setting('cuskt_branding_logo_use_svg_sprite'),
      '#description' => $this->t('Choose to use SVG sprite logo for light/dark theme variations'),
    ];

    $form['branding']['logo']['cuskt_branding_logo_mobile_sprite_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Mobile SVG Sprite Path: <span class="form-item__description">Required for SVG Sprite Logo. <em>(viewbox: 0 0 188 40)</em></span>'),
      '#default_value' => theme_get_setting('cuskt_branding_logo_mobile_sprite_path'),
      '#description' => $this->t("Examples: <code>/cusys_logo_sprite-mobile.svg</code> (for a file in the public filesystem), <code>public://cusys_logo_sprite-mobile.svg</code>, or <code>/themes/custom/cu_starterkit_theme/images/logos/cusys_logo_sprite-mobile.svg</code>"),
    ];

    $form['branding']['logo']['cuskt_branding_logo_desktop_sprite_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Desktop SVG Sprite Path: <span class="form-item__description">Required for SVG Sprite Logo. <em>(viewbox: 0 0 500 50)</em></span>'),
      '#default_value' => theme_get_setting('cuskt_branding_logo_desktop_sprite_path'),
      '#description' => $this->t("Examples: <code>/cusys_logo_sprite.svg</code> (for a file in the public filesystem), <code>public://cusys_logo_sprite.svg</code>, or <code>/themes/custom/cu_starterkit_theme/images/logos/cusys_logo_sprite.svg</code>"),
    ];

    // Body
    $form['body_details'] = [
      '#type' => 'details',
      '#title' => $this->t('Body options'),
      '#description' => $this->t('Combination of theme/background color may affect background color/text color contrast.'),
      '#open' => FALSE,
    ];

    $form['body_details']['cuskt_top_container'] = [
      '#type' => 'select',
      '#title' => $this->t('Website container type'),
      '#default_value' => theme_get_setting('cuskt_top_container'),
      '#description' => $this->t("Type of top level container: fluid (eg edge to edge) or fixed width"),
      '#options' => $options_top_container,
    ];

    $form['body_details']['cuskt_top_container_config'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Website container type configuration'),
      '#default_value' => theme_get_setting('cuskt_top_container_config'),
      '#description' => $this->t("Format: <classes|label> on each line, e.g. <br><pre>container|fixed<br />container-fluid m-0 p-0|fluid</pre>"),
    ];

    $form['body_details']['cuskt_body_schema'] = [
      '#type' => 'select',
      '#title' => $this->t('Body theme:'),
      '#default_value' => theme_get_setting('cuskt_body_schema'),
      '#description' => $this->t("Text color theme of the body."),
      '#options' => $options_theme,
    ];

    $form['body_details']['cuskt_body_bg_schema'] = [
      '#type' => 'select',
      '#title' => $this->t('Body background:'),
      '#default_value' => theme_get_setting('cuskt_body_bg_schema'),
      '#description' => $this->t("Background color of the body."),
      '#options' => $options_color,
    ];

    // Nav
    $form['nav_details'] = [
      '#type' => 'details',
      '#title' => $this->t('Navbar options'),
      '#description' => $this->t("Combination of theme/background color may affect background color/text color contrast."),
      '#open' => FALSE,
    ];

    $form['nav_details']['cuskt_navbar_global_schema'] = [
      '#type' => 'select',
      '#title' => $this->t('Navbar theme(Global):'),
      '#default_value' => theme_get_setting('cuskt_navbar_global_schema'),
      '#description' => $this->t("Text color theme of the Global navbar."),
      '#options' => $options_theme,
    ];

    $form['nav_details']['cuskt_navbar_bg_global_schema'] = [
      '#type' => 'select',
      '#title' => $this->t('Navbar background(Global):'),
      '#default_value' => theme_get_setting('cuskt_navbar_bg_global_schema'),
      '#description' => $this->t("Background color of the Global navbar."),
      '#options' => $options_color,
    ];

    $form['nav_details']['cuskt_navbar_local_schema'] = [
      '#type' => 'select',
      '#title' => $this->t('Navbar theme(Local):'),
      '#default_value' => theme_get_setting('cuskt_navbar_local_schema'),
      '#description' => $this->t("Text color theme of the Local navbar."),
      '#options' => $options_theme,
    ];

    $form['nav_details']['cuskt_navbar_bg_local_schema'] = [
      '#type' => 'select',
      '#title' => $this->t('Navbar background(Local):'),
      '#default_value' => theme_get_setting('cuskt_navbar_bg_local_schema'),
      '#description' => $this->t("Background color of the Local navbar."),
      '#options' => $options_color,
    ];

    $form['nav_details']['cuskt_navbar_main_schema'] = [
      '#type' => 'select',
      '#title' => $this->t('Navbar theme(Main):'),
      '#default_value' => theme_get_setting('cuskt_navbar_main_schema'),
      '#description' => $this->t("Text color theme of the <b>Main</b> navbar."),
      '#options' => $options_theme,
    ];

    $form['nav_details']['cuskt_navbar_bg_main_schema'] = [
      '#type' => 'select',
      '#title' => $this->t('Navbar background(Main):'),
      '#default_value' => theme_get_setting('cuskt_navbar_bg_main_schema'),
      '#description' => $this->t("Background color of the Main navbar."),
      '#options' => $options_color,
    ];

    // Footer
    $form['footer_details'] = [
      '#type' => 'details',
      '#title' => $this->t('Footer options'),
      '#description' => $this->t("Combination of theme/background color may affect background color/text color contrast."),
      '#open' => FALSE,
    ];

    $form['footer_details']['cuskt_footer_global_schema'] = [
      '#type' => 'select',
      '#title' => $this->t('Footer theme(Global):'),
      '#default_value' => theme_get_setting('cuskt_footer_global_schema'),
      '#description' => $this->t("Text color theme of the Global footer."),
      '#options' => $options_theme,
    ];

    $form['footer_details']['cuskt_footer_bg_global_schema'] = [
      '#type' => 'select',
      '#title' => $this->t('Footer background(Global):'),
      '#default_value' => theme_get_setting('cuskt_footer_bg_global_schema'),
      '#description' => $this->t("Background color of the Global footer."),
      '#options' => $options_color,
    ];

    $form['footer_details']['cuskt_footer_local_schema'] = [
      '#type' => 'select',
      '#title' => $this->t('Footer theme(Local):'),
      '#default_value' => theme_get_setting('cuskt_footer_local_schema'),
      '#description' => $this->t("Text color theme of the Local footer."),
      '#options' => $options_theme,
    ];

    $form['footer_details']['cuskt_footer_bg_local_schema'] = [
      '#type' => 'select',
      '#title' => $this->t('Footer background(Local):'),
      '#default_value' => theme_get_setting('cuskt_footer_bg_local_schema'),
      '#description' => $this->t("Background color of the Local footer."),
      '#options' => $options_color,
    ];

    /*
    * Search
    */
    $form['search'] = [
      '#type' => 'details',
      '#title' => $this->t('Search settings'),
      '#description' => $this->t("Options for search on site. ") . $searchIntegration,
      '#open' => FALSE,
    ];

    $form['search']['cuskt_search_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Search Type:'),
      '#default_value' => theme_get_setting('cuskt_search_type'),
      '#description' => $this->t("Type of search to use."),
      '#options' => $options_search,
    ];

    $form['search']['cuskt_search_action'] = [
      '#type' => 'textfield',
      '#title' => $this->t('CU Search Action: <span class="form-item__description">Required for CU Search type.</span>'),
      '#default_value' => theme_get_setting('cuskt_search_action'),
      '#description' => $this->t("Enter the form action for Google PSE search. <em>(https://search.cu.edu/)</em>"),
    ];

    $form['search']['cuskt_search_input_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('CU Search Input Name: <span class="form-item__description">Required for CU Search type.</span>'),
      '#default_value' => theme_get_setting('cuskt_search_input_name'),
      '#description' => $this->t("Enter the form input name to use for Google PSE search. <em>(keys, search, etc.)</em>"),
    ];

    // Styleguide
    // Integrate with styleguide from bootstrap_ui_kit module.
    $description = '';
    if (\Drupal::moduleHandler()
      ->moduleExists('bootstrap_ui_kit')) {
      $styleguidePath = '/ui-kit';
      $description = $this->t("Style guide demonstrates abilities of bootstrap framework. Open <a target='_blank' href='@sglink'>style guide</a> in a new window.", [
        '@sglink' => $styleguidePath,
      ]);
    }
    else {
      $styleguidePath = 'https://www.drupal.org/project/bootstrap_ui_kit';
      $description = $this->t("Style guide demonstrates abilities of bootstrap framework. Style guide is now part of <a target='_blank' href='@sglink'>CU Starter Kit Profile</a>. Enable the <em>Bootstrap UI Kit</em> module to enhance content editor and developer workflows.", [
        '@sglink' => $styleguidePath,
      ]);
    }

    $form['styleguide'] = [
      '#type' => 'details',
      '#title' => $this->t('Style guide'),
      '#description' => $description,
      '#open' => FALSE,
    ];


    // SubTheme
    $form['subtheme'] = [
      '#type' => 'details',
      '#title' => $this->t('Subtheme'),
      '#description' => $this->t("Create subtheme."),
      '#open' => FALSE,
    ];

    $form['subtheme']['subtheme_folder'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subtheme location'),
      '#default_value' => 'themes/custom',
      '#description' => $this->t("Relative path to the webroot <em>%root</em>. No trailing slash.", [
        '%root' => DRUPAL_ROOT,
      ]),
    ];

    $form['subtheme']['subtheme_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subtheme name'),
      '#default_value' => 'CU Starterkit subtheme',
      '#description' => $this->t("If name is empty, machine name will be used."),
    ];

    $form['subtheme']['subtheme_machine_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subtheme machine name'),
      '#default_value' => 'cuskt_subtheme',
      '#description' => $this->t("Use only lower-case letters, numbers, and underscores. Name must start with a letter. For details, see <a href='https://www.drupal.org/docs/creating-custom-modules/naming-and-placing-your-drupal-module'>Naming and placing your Drupal module</a>."),
    ];

    $form['subtheme']['create'] = [
      '#type' => 'submit',
      '#name' => 'subtheme_create',
      '#value' => $this->t('Create'),
      '#button_type' => 'danger',
      '#attributes' => [
        'class' => ['btn btn-danger'],
      ],
      '#submit' => ['cu_starterkit_theme_form_system_theme_settings_subtheme_submit'],
      '#validate' => ['cu_starterkit_theme_form_system_theme_settings_subtheme_validate'],
    ];
  }

}

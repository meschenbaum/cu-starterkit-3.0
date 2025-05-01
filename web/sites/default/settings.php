<?php

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Include the Pantheon-specific settings file.
 *
 * n.b. The settings.pantheon.php file makes some changes
 *      that affect all environments that this site
 *      exists in.  Always include this file, even in
 *      a local development environment, to ensure that
 *      the site settings remain consistent.
 */
include __DIR__ . "/settings.pantheon.php";

// Set Environment specific variables
if (!defined('PANTHEON_ENVIRONMENT') && php_sapi_name() != 'cli') {
  $config['environment_indicator.indicator']['name'] = 'Local';
  $config['environment_indicator.indicator']['bg_color'] = '#4C742C';
  $config['environment_indicator.indicator']['fg_color'] = '#ffffff';
  $config['pantheon_domain_masking.settings']['enabled'] = 'no';
}

/**
  * Pantheon settings should only be included when the site is running on Pantheon 
  */
if (isset($_ENV['PANTHEON_ENVIRONMENT']) && php_sapi_name() != 'cli') {
  
  // Add any additional Pantheon specific customizations bellow
  if ($_ENV['PANTHEON_ENVIRONMENT'] != 'live') {
    $config['pantheon_domain_masking.settings']['enabled'] = 'no';
  }
  // Add color and label to Admin Toolbar
  switch ($_ENV['PANTHEON_ENVIRONMENT']) {
    case 'dev':
      $config['environment_indicator.indicator']['name'] = 'Dev';
      $config['environment_indicator.indicator']['bg_color'] = '#d25e0f';
      $config['environment_indicator.indicator']['fg_color'] = '#ffffff';
      break;
    case 'test':
      $config['environment_indicator.indicator']['name'] = 'Test';
      $config['environment_indicator.indicator']['bg_color'] = '#c50707';
      $config['environment_indicator.indicator']['fg_color'] = '#ffffff';
      break;
    case 'live':
      $config['environment_indicator.indicator']['name'] = 'Live';
      $config['environment_indicator.indicator']['bg_color'] = '#000000';
      $config['environment_indicator.indicator']['fg_color'] = '#ffffff';
      break;
    default:
      // Multidev catchall
      $config['environment_indicator.indicator']['name'] = 'Multidev';
      $config['environment_indicator.indicator']['bg_color'] = '#efd01b';
      $config['environment_indicator.indicator']['fg_color'] = '#000000';
      break;
  }
}

// Handle stacked sites
if (isset($_ENV['DRUSH_OPTIONS_URI'])) {
  $settings['xmlsitemap_base_url'] = 'https://' . $_ENV['DRUSH_OPTIONS_URI'];
  if (str_contains($_ENV['DRUSH_OPTIONS_URI'], 'www.cu.edu')) {
    //split at periods. if the first item in the array isn't www, this is a stacked site
    $url_array = explode('.', $_ENV['DRUSH_OPTIONS_URI']);
    if ($url_array[0] != 'www') {
      $settings['xmlsitemap_base_url'] = 'https://www.cu.edu/' . $url_array[0];
    }
  }
}


/**
 * Skipping permissions hardening will make scaffolding
 * work better, but will also raise a warning when you
 * install Drupal.
 *
 * https://www.drupal.org/project/drupal/issues/3091285
 */
// $settings['skip_permissions_hardening'] = TRUE;

/**
 * If there is a local settings file, then include it
 */
$local_settings = __DIR__ . "/settings.local.php";
if (file_exists($local_settings)) {
  include $local_settings;
}

// Automatically generated include for settings managed by ddev.
$ddev_settings = dirname(__FILE__) . '/settings.ddev.php';
if (getenv('IS_DDEV_PROJECT') == 'true' && is_readable($ddev_settings)) {
  require $ddev_settings;
}

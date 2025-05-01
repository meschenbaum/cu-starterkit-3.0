# CU System Starter Kit theme for Drupal

The Starter Kit theme for Drupal is an implementation and adaptation of standard CU System design principles and patterns.

## Requirements

This theme is part of the CU System Profile and requires the modules and configuration from that to install correclty.

## Configuration

Head to `Appearance` and clicking CU StarterKit Theme `settings`.

### Subtheme

* Enable theme.
* Head to `/admin/appearance/settings/cu_starterkit_theme`.
* Scroll down to `Subtheme` section.
* Name your subtheme and click `Create`.

## Development and patching

For development you will need to make sure you are in the theme directory root(_web/themes/custom/cu_starterkit_theme/_), in order run node and the commands below.

- Install development dependencies by running `npm install`
- To lint SASS files run `npm run lint:sass` (it will fail build if lint fails)
- To lint JS files run `npm run lint:js` (it will fail build if lint fails)
- To compile SASS run `sass scss/style.scss css/style.css` (requires [SASS compiler](https://sass-lang.com/install))
- To compile SASS for CKEditor5 run `sass scss/ck5style.scss css/ck5style.css`
- To compile _All_ SASS `npm run build:sass`
- To compile JS: run `npm run build:js`

## Updating

The CU Starter Kit Theme was created for Drupal 10 using the starterkit_theme generator and [Bootstrap5](https://www.drupal.org/project/bootstrap5). Additional information on generating themes can be found in the [Drupal Starterkit documentation](https://www.drupal.org/docs/core-modules-and-themes/core-themes/starterkit-theme). 

The theme will be maintained up-to-date with Drupal core's `starterkit_theme` theme according to the documentation for
[Tracking upstream changes](https://www.drupal.org/docs/core-modules-and-themes/core-themes/starterkit-theme#s-tracking-upstream-changes).


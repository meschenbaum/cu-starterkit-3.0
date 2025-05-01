<?php
// force the sitemap.xml to be regenerated
echo "Regenerate sitemap.xml using env variables...\n";
passthru('drush xmlsitemap:regenerate');
echo "Sitemap regeneration complete.\n";
echo "Rebuilding cache.\n";
passthru('drush cr');
echo "Rebuilding cache complete.\n";

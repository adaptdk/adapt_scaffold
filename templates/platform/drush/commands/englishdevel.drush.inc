<?php

/**
 * Hack to switch language to English when running drush.
 *
 * This forces all exported features to actually be the
 * same language across.
 */
function englishdevel_drush_init()
{
    global $language;
    $language = new stdClass();
    $language->language = 'en';
    $language->name = 'English';
    $language->native = 'English';
    $language->direction = 0;
    $language->enabled = 1;
    $language->plurals = 0;
    $language->formula = '';
    $language->domain = '';
    $language->prefix = 'en';
#  echo "Language forcefully set to English\r\n";
}

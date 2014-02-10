<?php
/**
 * @file
 * Template profile file.
 */

/**
 * Implements hook_form_install_configure_form_alter().
 */
function {{ profile }}_form_install_configure_form_alter(&$form, $form_state) {
  // Import variable settings
  $profile_path = drupal_get_path('profile', '{{ profile }}');
  require($profile_path . "/includes/settings.inc");

  $form['site_information']['site_name']['#default_value'] = $site_name;
  $form['site_information']['site_mail']['#default_value'] = $site_mail;
  $form['server_settings']['site_default_country']['#default_value'] = $site_default_country;
}

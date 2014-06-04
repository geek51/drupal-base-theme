<?php

include (dirname(__FILE__) . '/includes/theme_conf.php');

function BASETHEME_theme_get_setting($name, $theme = NULL) {
  switch ($name) {
    case 'exclude':
      $setting = BASETHEME_theme_get_info($name, $theme);
      break;
    default:
      $setting = BASETHEME_get_setting($name, $theme);
      break;
  }

  return isset($setting) ? $setting : NULL; 
}

function BASETHEME_get_settings($theme = NULL) {
  if (!isset($theme)) {
    $theme = !empty($GLOBALS['theme_key']) ? $GLOBALS['theme_key'] : '';
  }

  if ($theme) {
    $themes = list_themes();
    $theme_object = $themes[$theme];
  }

  return $theme_object->info['settings'];
}

function BASETHEME_form_system_theme_settings_alter(&$form, $form_state, $form_id = NULL) {
  // Work-around for a core bug affecting admin themes. See issue #943212.
  if (isset($form_id)) {
    return;
  }

  $form['themedev'] = array(
    '#type'          => 'fieldset',
    '#title'         => t('Theme development settings'),
  );

  $form['themedev']['BASETHEME_rebuild_registry'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Rebuild theme registry on every page.'),
    '#default_value' => theme_get_setting('BASETHEME_rebuild_registry'),
    '#description'   => t('During theme development, it can be very useful to continuously <a href="!link">rebuild the theme registry</a>.') . '<div class="alert alert-error">' . t('WARNING: this is a huge performance penalty and must be turned off on production websites. ') . l('Drupal.org documentation on theme-registry.', 'http://drupal.org/node/173880#theme-registry'). '</div>',
  ); 

}


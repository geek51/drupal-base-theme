<?php

include (dirname(__FILE__) . '/includes/theme_conf.php');

function BASETHEME_preprocess_html() {  
  if (
    // Only display for site config admins.
    isset($GLOBALS['user']) && function_exists('user_access') && user_access('administer site configuration')
    && theme_get_setting('BASETHEME_rebuild_registry')
    // Always display in the admin section, otherwise limit to three per hour.
    && (arg(0) == 'admin' || flood_is_allowed($GLOBALS['theme'] . '_rebuild_registry_warning', 3))
  ) {
    flood_register_event($GLOBALS['theme'] . '_rebuild_registry_warning');
    drupal_set_message(t('For easier theme development, the theme registry is being rebuilt on every page request. It is <em>extremely</em> important to <a href="!link">turn off this feature</a> on production websites.', array('!link' => url('admin/appearance/settings/' . $GLOBALS['theme']))), 'warning', FALSE);
  }
}

// Auto-rebuild the theme registry during theme development.
if (theme_get_setting('BASETHEME_rebuild_registry') && !defined('MAINTENANCE_MODE')) {
  // Rebuild .info data.
  system_rebuild_theme_data();
  // Rebuild theme registry.
  drupal_theme_rebuild();
}

/**
*FUnction to add js,css or custom vars
**/
function BASETHEME_preprocess_page(&$variables) {
   
}

/**
 * Implements hook_css_alter().
 */
function BASETHEME_css_alter(&$css) {
  // Load excluded CSS files from theme.
  $excludes = _BASETHEME_alter(BASETHEME_theme_get_info('exclude'), 'css');
  
  $css = array_diff_key($css, $excludes);
}

function _BASETHEME_alter($files, $type) {
  $output = array();
  
  foreach($files as $key => $value) {
    if (isset($files[$key][$type])) {
      foreach ($files[$key][$type] as $file => $name) {
        $output[$name] = FALSE;
      }
    }
  }
  return $output;
}

function BASETHEME_get_field($field){
    if( isset($field[LANGUAGE_NONE]) ) return $field[LANGUAGE_NONE][0];
    
    return $field[0];
}

function BASETHEME_get_image_path($field){
    $field = BASETHEME_get_field($field);
    
    return file_create_url($field['uri']);
}

function BASETHEME_get_field_value($field , $key = 'value'){
    $field = BASETHEME_get_field($field);
    
    return $field[$key];
}

function BASETHEME_get_field_multiple($field){
    if( isset($field[LANGUAGE_NONE]) ) return $field[LANGUAGE_NONE];
    
    return $field;
}

function BASETHEME_get_group_item($group_field){
    return array_pop( 
        entity_load( 'field_collection_item', array($group_field['value']) ) 
    );
}

function BASETHEME_get_multi_group_item($group_field){
    $items = array();
    
    for($i=0, $total = count($group_field); $i < $total; $i++){
        $items[] = get_group_item($group_field[$i]);
    }
    
    return $items;
}

function BASETHEME_get_taxonomy_name($field_taxonomy) {
    return $field_taxonomy[0]['taxonomy_term']->name;
}

//OPTIONA BUT USEFUL HOOKS
/*
function BASETHEME_menu_link(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';
  $name_id = strtolower(strip_tags($element['#title']));
// remove colons and anything past colons
  if (strpos($name_id, ':')) $name_id = substr ($name_id, 0, strpos($name_id, ':'));
//Preserve alphanumerics, everything else goes away
  $pattern = '/[^a-z]+/ ';
  $name_id = preg_replace($pattern, '', $name_id);
  $element['#attributes']['class'][] = 'menu-' . $element['#original_link']['mlid'] . ' '.$name_id;
  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }
  
   //check if the element has an image atached
  if( is_int($element['#localized_options']['content']['image']) ){
      $file = file_load($element['#localized_options']['content']['image']);      
      $img = '<img src="' . file_create_url( $file->uri ) . '" />';
  }else{
      $img = '';
  }
  
  $output = l($img . '<span class="link-with-icon">' .$element['#title'] . '</span>', $element['#href'], array_merge($element['#localized_options'], array('html' => true)) );  
  
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

function BASETHEME_preprocess_views_view(&$variables) {    

}
*/
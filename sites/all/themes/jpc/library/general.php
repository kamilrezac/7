<?php

function theme_path()
{
    return base_path().path_to_theme().'/';
}

function get_node_alias_path($nid)
{
    return drupal_lookup_path('alias',"node/".$nid);
}

function convert_title_to_url($title)
{
    return str_replace(' ', '-', remove_accents(strtolower($title)));
}

function remove_accents($name)
{
    return str_replace(array('á', 'č', 'ď', 'é', 'ě', 'í', 'ň', 'ó', 'ř', 'š', 'ť', 'ú', 'ů', 'ý', 'ž'), array('a', 'c', 'd', 'e', 'e', 'i', 'n', 'o', 'r', 's', 't', 'u', 'u', 'y', 'z'), $name);
}

function get_field_collection_for($node)
{
   return entity_metadata_wrapper('node', $node);
}

function purge_deleted_fields()
{
    field_purge_batch(1000);
}

function create_teaser($content, $length = 300) {
    $content = trim($content);
    $content = strip_tags($content);
    $content = check_plain($content);

    if (strlen($content) > $length && strpos($content, ' ', $length)) {
        return substr($content, 0, strpos($content, ' ', $length));
    } else {
        return $content;
    }
}

function url_arg($index) {
  $path = drupal_get_path_alias($_GET['q']);

  $arguments = explode('/', $path);

  if (isset($arguments[$index])) {
      return $arguments[$index];
  }

  return false;
}

function jpc_theme() {
  $items = array();

  $items['user_login'] = array(
    'render element' => 'form',
    'path' => drupal_get_path('theme', 'jpc') . '/templates',
    'template' => 'user-login',
  );

  return $items;
}

function jpc_css_alter(&$css) {
    unset($css[drupal_get_path('module','system').'/system.theme.css']);
    unset($css[drupal_get_path('module','system').'/system.menus.css']);
    unset($css[drupal_get_path('module','system').'/system.base.css']);
    unset($css[drupal_get_path('module','system').'/system.messages.css']);
}

function jpc_preprocess_page(&$variables) {
  if (isset($variables['node'])) {
      $variables['theme_hook_suggestions'][] = 'page__'. $variables['node']->type;
  }
}

function jpc_preprocess_html(&$variables) {
  // If on an individual node page, add the node type to body classes.
  if ($node = menu_get_object()) {
    $variables['theme_hook_suggestions'][] = 'html__'. $node->type;
  }

  drupal_add_css('http://fonts.googleapis.com/css?family=Droid+Sans:400,700', array('type' => 'external'));
}
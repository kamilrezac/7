<?php

function get_language_switcher() {
  global $language;
  $languages = language_list('enabled');
  $path = drupal_is_front_page() ? '<front>' : $_GET['q'];

  //handle node translations
  if(arg(0) == 'node' && arg(1)>0) {
    $node_path = 'node/'. arg(1);
    $language_paths = translation_path_get_translations($node_path);
  }

  //handle term translations
  if(arg(0) == 'taxonomy' && arg(1) == 'term' && arg(2)>0) {
    $term = taxonomy_get_term(arg(2));
    $language_terms = i18ntaxonomy_term_get_translations(array('tid' => arg(2)));
  }

  if (count($languages[1])>1) {
    $lang_switches = array();
    foreach ($languages[1] as $lang) {
      if($lang->language != $language->language) {
        $modifier = $lang->native;
        //we are translating a node. Check if not neutral or missing translations.
        if($language_paths && !empty($language_paths))
          $path = $language_paths[$lang->language];
        //we are translating a term.
        if($term) {
          $path = 'taxonomy/term/'. $language_terms[$lang->language]->tid;
        }
        $lang_switches[] = l($modifier, $path, array('language' => $lang, 'attributes' => array('class'=>$lang->language)));
      }
    }
    return implode('<br />', $lang_switches);
  }
}

function ll($text, $path, $options = array(), $lang = 'cs')
{
  global $language;

  $options += array(
      'language' => $language
  );

  if (!($internal_link = drupal_lookup_path($path, $lang))) {
      $internal_link = $path;
  }

  $internal_path_args = explode('/', $internal_link);

  if ($internal_path_args[0] == 'node' && $internal_path_args[1] > 0) {
      $language_paths = translation_path_get_translations($internal_link);
      if($language_paths && !empty($language_paths)) {
          $path = $language_paths[$language->language];
      }
  }

  //handle term translations
  if($internal_path_args[0] == 'taxonomy' && $internal_path_args[1] == 'term' && $internal_path_args[2] > 0) {
      $term = taxonomy_get_term($internal_path_args[2]);
      $language_terms = i18ntaxonomy_term_get_translations(array('tid' => $internal_path_args[2]));
      if($term) {
          $path = 'taxonomy/term/'. $language_terms[$language->language]->tid;
      }
  }

  return l($text, $path, $options);
}
<?php

class nodeView
{
    private $node;

    private $wrapper;

    private $content;

    public function __construct($node, $content)
    {
        $this->node = $node;
        $this->wrapper = entity_metadata_wrapper('node', $node);
        $this->content = $content;
    }

    public function __get($name) {
        if ($name == 'title') {
            return $this->node->title;
        }

        return render($this->content[$name]);
    }

    public function getUnformattedValue($field_name)
    {
        return $this->wrapper->{$field_name}->value();
    }

    public function count($field_name)
    {
        return count($this->wrapper->{$field_name}->value());
    }
}

function jpc_image_formatter($variables) {
  $item = $variables['item'];
  $image = array(
    'path' => $item['uri'],
    'alt' => $item['alt'],
  );

  if (isset($item['attributes'])) {
    $image['attributes'] = $item['attributes'];
  }

  if (isset($item['width']) && isset($item['height'])) {
    $image['width'] = $item['width'];
    $image['height'] = $item['height'];
  }

  // Do not output an empty 'title' attribute.
  if (drupal_strlen($item['title']) > 0) {
    $image['title'] = $item['title'];
  }

  if ($variables['image_style']) {
    $image['style_name'] = $variables['image_style'];
    $output = theme('image_style', $image);
  }
  else {
    $output = theme('image', $image);
  }

  if (!empty($variables['path']['path'])) {
    $path = $variables['path']['path'];
    $options = $variables['path']['options'];
    // When displaying an image inside a link, the html option must be TRUE.
    $options['html'] = TRUE;
    $output = l($output, $path, $options);
  }

  return $output;
}

function jpc_field_collection_view($variables) {
  $element = $variables['element'];
  return $element['#children'];
}

function jpc_preprocess_node(&$variables) {
  //purge_deleted_fields();
  if (isset($variables['node'])) {
    $variables['nodeView'] = new nodeView($variables['node'], $variables['content']);
  }
}

function jpc_textarea($variables) {
  $element = $variables['element'];
  element_set_attributes($element, array('id', 'name', 'cols', 'rows'));
  _form_set_class($element, array('form-textarea'));

  $wrapper_attributes = array(
    'class' => array(),
  );

  // Add resizable behavior.
  if (!empty($element['#resizable'])) {
    drupal_add_library('system', 'drupal.textarea');
    $wrapper_attributes['class'][] = 'resizable';
  }

  $output = '<textarea' . drupal_attributes($element['#attributes']) . '>' . check_plain($element['#value']) . '</textarea>';

  return $output;
}
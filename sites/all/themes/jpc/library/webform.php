<?php

class FormBuilder
{
    private $form;

    private $post;

    public function __construct($form, $post)
    {
        $this->form = $form;
        $this->post = $post;
    }

//    public function buildForm()
//    {
//        var_dump(array_keys($this->form['submitted']));exit;
//    }
}

function disable_webform_default_options_textfield(&$field_data)
{
    $field_data['#theme_wrappers'] = array();
    unset($field_data['#size']);
    unset($field_data['#maxlength']);
}

function disable_webform_default_options_textarea(&$field_data)
{
    $field_data['#theme_wrappers'] = array();
    $field_data['#resizable'] = false;
    unset($field_data['#rows']);
    unset($field_data['#cols']);
}

function textfield_render(&$field_data, $field_name, $field_title)
{
    disable_webform_default_options_textfield($field_data);
    $output = "<label for='edit-submitted-".$field_name."'>".t($field_title)."</label>";
    $output .= drupal_render($field_data);

    return $output;
}

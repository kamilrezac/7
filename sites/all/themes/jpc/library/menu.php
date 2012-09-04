<?php

function jpc_menu_tree__menu_block($variables)
{
    return '<ul>' . $variables['tree'] . '</ul>';
}

function jpc_menu_link__menu_block(array $variables)
{
    $element = $variables['element'];
    $sub_menu = '';

    if ($element['#below']) {
      $sub_menu = drupal_render($element['#below']);
    }

    if (in_array('active-trail', $element['#attributes']['class'])) {
        $element['#localized_options']['attributes']['class'][] = 'active';
    }

    $output = l($element['#title'], $element['#href'], $element['#localized_options']);
    return '<li>' . $output . $sub_menu . "</li>\n";
}

function main_menu($block)
{
    $data = _block_render_blocks(array($block));
    $links = $data["menu_block_1"]->content["#content"];
    $output = "<nav id='main-menu'>";
    $output .= get_main_menu_links($links);
    $output .= "</nav>";

    return $output;
}

function get_main_menu_links($links)
{
    unset($links['#sorted']);
    unset($links['#theme_wrappers']);
    $output = "<ul class='reset'>\n";
    foreach ($links as $link) {
        $output .= get_main_menu_link($link);
    }
    $output .= "</ul>\n";

    return $output;
}

function get_main_menu_link($link)
{
    if (in_array('active-trail', $link['#attributes']['class'])) {
        $link['#localized_options']['attributes']['class'][] = 'active';
    }

    if (in_array($link['#title'], array('Kontakt', 'Contact'))) {
        $link['#localized_options']['attributes']['class'][] = 'thickbox';
    }

    return '<li>' . l($link['#title'], $link['#href'], $link['#localized_options']) . "</li>\n";
}

function secondary_menu($block)
{
    $data = _block_render_blocks(array($block));
    $links = $data["menu_block_2"]->content["#content"];
    $output = "<nav class='sub-menu'>";
    $output .= get_secondary_menu_links($links);
    $output .= "</nav>";

    return $output;
}

function get_secondary_menu_links($links)
{
    unset($links['#sorted']);
    unset($links['#theme_wrappers']);
    $output = "<p>\n";
    $i = 1;
    foreach ($links as $key => $link) {
        if (in_array('active', $link['#attributes']['class'])) {
            $output .= "<strong>".$link['#title']."</strong>";
        } else {
            $output .= l($link['#title'], $link['#href'], $link['#localized_options']);
        }
        if ($i != count($links)) {
            $output .= ' /';
        }
        $i++;
    }
    $output .= "</p>\n";

    return $output;
}

function get_all_link_children($mlid)
{
    $select = db_select('menu_links', 'l');
    $select->addField('l', 'link_title');
    $select->addField('l', 'link_path');
    $select->condition('l.plid', $mlid, '=');

    $result = $select->execute();

    return $result->fetchAll();
}
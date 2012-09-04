<?php

function jpc_preprocess_views_view(&$vars) {
    $view = $vars['view'];
    $vars['title'] = filter_xss_admin(t($view->get_title()));
}
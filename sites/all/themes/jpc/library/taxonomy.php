<?php

function get_taxonomy_term_alias($tid)
{
    $term_path = 'taxonomy/term/'.$tid;

    $select = db_select('url_alias', 'u');
    $select->addField('u', 'alias');
    $select->condition('u.source', $term_path , '=');

    $result = $select->execute();

    $term = $result->fetchCol();

    return $term[0];
}
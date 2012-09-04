<?php

function getRaceInformation()
{
    global $language;

    $select = db_select('node', 'n');
    $select->join('field_data_field_race_date', 'd','d.entity_id=n.nid');
    $select->join('field_data_field_race_location', 'l','l.entity_id=n.nid');
    $select->fields('d', array('field_race_date_value'));
    $select->fields('l', array('field_race_location_value'));
    $select->condition('n.type', 'homepage', '=');
    $select->condition('n.language', $language->language, '=');

    return $select->execute()->fetchAssoc();
}

function getBuyTicketUrl()
{
    global $language;

    $select = db_select('node', 'n');
    $select->join('field_data_field_buy_ticket_link', 'l','l.entity_id=n.nid');
    $select->fields('l', array('field_buy_ticket_link_url'));
    $select->condition('n.type', 'homepage', '=');
    $select->condition('n.language', $language->language, '=');

    $url = $select->execute()->fetchAssoc();

    return $url['field_buy_ticket_link_url'];
}

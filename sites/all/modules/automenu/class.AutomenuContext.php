<?php
/**
 * Created by IDEA.
 * User: jakobs
 * Date: 28.06.2011
 * Time: 01:17:31
 * To change this template use File | Settings | File Templates.
 */

class AutomenuContext {

    private $childtypes;
    private static $instance;

    function __construct($type=null) {
        $this->childtypes = array();
        $this->childtypes[] = $type;
    }

    private function canBeAdded($type) {
        return !in_array($type, $this->childtypes);
    }

    private function add($type) {
        $this->childtypes[] = $type;
    }

    public static function createNodeAndLink($node, $childtype) {
        $ctx = self::$instance;
        if($ctx->canBeAdded($childtype['type'])) {
            $ctx->add($childtype['type']);
            $child = self::createChild($node, $childtype);
            node_object_prepare($child);
            node_save($child);
            $autolink = new AutomenuCreateLinkDefaultImpl($child, $node);
            $autolink->createLink();
        }
    }

    private static function createTitle($node, $childtype) {
        //todo user autotitle module
        return $node->title . ' / ' . $childtype['name'];
    }


    private static function createChild($node, $childtype) {

        global $user;
        $settings = array(
            'title' => self::createTitle($node, $childtype),
            'changed' => REQUEST_TIME,
            'moderate' => 0,
            'comment' => '1',
            'promote' => 0,
            'revision' => 0,
            'log' => '',
            'date' => '',
            'status' => 1,
            'sticky' => 0,
            'type' => $childtype['type'],
            'revisions' => NULL,
            'language' => 'de',
            'nid' => null,
            'uid' => $user->uid,
            'name' => $user->name,
        );
        return (object) $settings;
    }

    public static function getInstance() {
        if(is_null(self::$instance)) {
            self::$instance = new AutomenuContext();
        }
        return self::$instance;
    }

    public static function reset($type) {
        self::$instance = new AutomenuContext($type);
    }


}

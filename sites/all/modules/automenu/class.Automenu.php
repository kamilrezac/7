<?php
/**
 * Created by IDEA.
 * User: jakobs
 * Date: 24.06.2011
 * Time: 07:27:03
 * To change this template use File | Settings | File Templates.
 */
//namespace de\dreizwo;
require_once 'abstract.AutomenuCreateLink.php';
require_once 'class.AutomenuContext.php';
//use de\dreizwo\AutomenuCreateLink as AutomenuCreateLink;

class Automenu extends AutomenuCreateLink {

    private $menuname; // '0' if it's the root
    private $plid = null;
    private $linkpath = null;
    private $linktitle;
    private $hidden = false;
    private $options = array('alter' => TRUE);
    private $description;
    private $mlid;
    private $weight; //todo add config element or other behavoiur
    private $expanded = 0; //todo add config element

    private $initialized = false;

    function __construct($child, $parent = null) {
        $this->child = $child;
        $this->parent = $parent;
    }


    private function initialize() {
        global $user;
        $this->initMenuParameters();
        if($this->menuname != '0') {
            $ignore_status = variable_get('automenu_ignore_status_' . $this->child->type, 1);
            if($ignore_status || $this->child->status == 1) {
                $this->linkpath = 'node/' . $this->child->nid;
                $this->linktitle = $this->child->title;
                $this->hidden = variable_get('automenu_hide_' . $this->child->type, 0);
                if($this->child->language)
                    $this->options['langcode'] = $this->child->language;
                if(user_is_logged_in())
                    $this->description = t('created by @user on @date',
                                           array('@user' => $user->name, '@date' => date('l', $this->child->created)));
                else
                    $this->description = t('created by anonymous user on @date',
                                           array('@date' => date('l', $this->child->created)));
                $cmlid = db_select('menu_links', 'ml')
                        ->condition('ml.link_path', 'node/' . $this->child->nid)
                        ->fields('ml', array('mlid'))
                        ->execute()
                        ->fetchField(); // the current mlid
                if($cmlid) {
                    $this->mlid = $cmlid;
                }
            }
        }
        $this->initialized = true;
    }

    private function canBeCreated() {
        if(!$this->initialized) {
            $this->initialize();
        }
        return !is_null($this->linkpath) && !is_null($this->plid);
    }


    /**
     * @return void
     */
    private function initMenuParameters() {
        if(is_null($this->parent)) {
            AutomenuContext::reset($this->child->type);
            $p = explode(":", variable_get(
                'automenu_parentmenu_' . $this->child->language . '_' . $this->child->type, '0'));
            if($p[0] == '0') // 0 == 'none'
                $p = explode(":", variable_get('automenu_parentmenu_' . $this->child->type, '0'));
            $this->menuname = $p[0];
            if(isset($p[1]))
                $this->plid = $p[1];
        }
        else
        {
            // we will read the menu-name (=the Parent of the Parent) from the settings
            $p =
                    explode(":", variable_get(
                        'automenu_parentmenu_' . $this->parent->language . '_' . $this->parent->type, '0'));
            if($p[0] == '0') // 0 == 'none'
                $p = explode(":", variable_get('automenu_parentmenu_' . $this->parent->type, '0'));
            if($p[0] != '0') {

                $plid = db_select('menu_links', 'ml')
                        ->condition('ml.link_path', 'node/' . $this->parent->nid)
                        ->fields('ml', array('mlid'))
                        ->execute()
                        ->fetchField(); // the current mlid will be the new plid
                $this->plid = $plid;
            }
            $this->menuname = $p[0]; // the name of the menu
        }
    }


    public function createLink() {
        if(!$this->initialized) {
            $this->initialize();
        }

        if($this->canBeCreated()) {
            $new_menu = array(
                'menu_name' => $this->menuname,
                'link_path' => $this->linkpath,
                'link_title' => $this->linktitle,
                'plid' => $this->plid,
                'hidden' => $this->hidden,
                'expanded' => $this->expanded,
                'options' => $this->options,
                'description' => $this->description
            );

            if(isset($this->weight)) {
                $new_menu['weight'] = $this->weight;
            }

            if(isset($this->mlid)) {
                $new_menu['mlid'] = $this->mlid;
            }

            if(!menu_link_save($new_menu)) {
                drupal_set_message(t('There was an error saving the auto-menu link.'), 'error');
            }
            else {
                $show_message = variable_get('automenu_show_message_' . $this->child->type, 0);
                if($show_message)
                    drupal_set_message(t('The page was automatically added/updated to: !menu.',
                                         array('!menu' => $this->menuname)));
            }
        }
        else {
            drupal_set_message(t('Unable to create autmenulink, because no parentmenu was found for the child - please check configuraton of @type.',
                                 array('@type' => $this->child->type)), 'warning');
        }
    }


    public static function isEnabled($node) {
        if(!variable_get('automenu_enabled_' . $node->type, 0)) {
            return false;
        }
        //check if a menu has been set, too
        $config = explode(":", variable_get('automenu_parentmenu_' . $node->language . '_' . $node->type, '0'));
        // fallback to language independent if nothing has been set
        // 0 means nothing has been set...
        if($config[0] == '0') {
            $config = explode(":", variable_get('automenu_parentmenu_' . $node->type, '0'));
        }
        // still nothing found?
        if($config[0] == '0') {
            return false;
        }
        return true;
    }

    public static function getMenuName($node) {
        if(!variable_get('automenu_enabled_' . $node->type, 0)) {
            return null;
        }
        //check if a menu has been set, too
        $config = explode(":", variable_get('automenu_parentmenu_' . $node->language . '_' . $node->type, '0'));
        // fallback to language independent if nothing has been set
        // 0 means nothing has been set...
        if($config[0] == '0') {
            $config = explode(":", variable_get('automenu_parentmenu_' . $node->type, '0'));
        }
        // still nothing found?
        if($config[0] == '0') {
            return null;
        }
        return $config[0];
    }


    public static function createMenuLink($parent, $child, $menuname) {
        global $user;
        $plid = self::getMLID($parent, $menuname);
        if(!$plid) {
            drupal_set_message(t('Unable to create autmenulink, because no parentmenu was found for the child - please check configuraton of @type.',
                                 array('@type' => $parent)), 'warning');
            return;
        }

        $description = '';
        if(user_is_logged_in()) {
            $description = t('created by @user on @date',
                             array('@user' => $user->name, '@date' => date('l', $child->created)));
        }
        else
        {
            $description = t('created by anonymous user on @date',
                             array('@date' => date('l', $child->created)));
        }

        $new_menu = array(
            'menu_name' => $menuname,
            'link_path' => 'node/' . $child->nid,
            'link_title' => $child->title,
            'plid' => $plid,
            'hidden' => variable_get('automenu_hide_' . $child->type, 0),
            'expanded' => 0,
            'options' => array('alter' => TRUE),
            'description' => $description
        );


        if(!menu_link_save($new_menu)) {
            drupal_set_message(t('There was an error saving the auto-menu link.'), 'error');
        }
        else {
            $show_message = variable_get('automenu_show_message_' . $child->type, 0);
            if($show_message)
                drupal_set_message(t('The page was automatically added/updated to: !menu.',
                                     array('!menu' => $menuname)));
        }
    }

    private static function getMLID($parent, $menuname) {
        return db_select('menu_links', 'ml')
                ->condition('ml.link_path', 'node/' . $parent->nid)
                ->condition('ml.menu_name', $menuname)
                ->fields('ml', array('mlid'))
                ->execute()
                ->fetchField(); // the current mlid will be the new plid
    }

    public static function getNodeId($plid, $menuname) {
        $linkpath = db_select('menu_links', 'ml')
                ->condition('ml.mlid', $plid)
                ->condition('ml.menu_name', $menuname)
                ->fields('ml', array('link_path'))
                ->execute()
                ->fetchField(); // the current mlid will be the new plid
        return self::getNid($linkpath);
    }

     public static function getChildren($plid, $menuname) {
       $childnids = array();
       $query = db_select('menu_links', 'ml')
                ->condition('ml.plid', $plid)
                ->condition('ml.menu_name', $menuname)
                ->fields('ml', array('link_path'))
                ->execute();
        while($linkpath = $query->fetchField())
            $childnids[]= self::getNid($linkpath);
        return $childnids;
    }

    private static function getNid($path) {

        $args = split('/', $path);
        if($args[0] == 'node' && is_numeric($args[1])) {
            $nid = $args[1];
            return $nid;
        }
        else {
            return 0;
        }
    }


}

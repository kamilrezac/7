<?php
/**
 * Created by IDEA.
 * User: jakobs
 * Date: 24.06.2011
 * Time: 06:15:17
 * To change this template use File | Settings | File Templates.
 */
//namespace de\dreizwo;
require_once 'abstract.AutomenuCreateLink.php';
//use de\dreizwo\AutomenuCreateLink as AutomenuCreateLink;

/**
 * this is just a simple wrapper for creation of Automenu-Links
 * you can use this class to add menu-links programmatically inside of other modules
 * simply create a new AutoNodeCreateLinkDefaultImpl($child, $parent) wioth the appropriate
 * $nodes and call createLink.
 * don't forget to add something like
 *  require_once drupal_get_path('module', 'automenu').'/class.AutoNodeCreateLinkDefaultImpl.php';
 * in your module
 * If you need other behaviours you can create a class of your own...
 */
class AutomenuCreateLinkDefaultImpl extends AutomenuCreateLink {

    /**
     * call this function to add a menu_link programatically
     * @param  $child  - a node which should be linked as a child of the $parent
     * @param  $parent - the parent node
     * @return void
     */
    function __construct($child, $parent) {
        if(module_exists('automenu')) {
            if(!is_object($child))
                drupal_set_message(t("child may not be null an should be a node instance"), 'error');
            $this->child = $child;
            $this->parent = $parent;
        }
        else {
            drupal_set_message(t("module automenu is missiong, please check your configuration"), 'error');
        }
    }


    /**
     * create the link!
     * @return void
     */
    public function createLink() {
       $automenu = new Automenu($this->child, $this->parent);
       $automenu->createLink();
    }



}

<?php
/**
* @package      JoomlaFAP
* @copyright    Copyright (C) 2011 Alessandro Pasotti
* @license      GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/


// no direct access
defined('_JEXEC') or die('Restricted access');

class AccesskeyHelper {

    private static $accesskeys = null;
    private static $titles = null;

    private static function init(){
        $db = JFactory::getDBO();
        $db->setQuery('SELECT id, accesskey, note FROM #__menu WHERE (accesskey IS NOT NULL AND accesskey != \'\') OR (note IS NOT NULL OR note != \'\')');
        $aks = $db->loadAssocList('id');
        AccesskeyHelper::$accesskeys = array();
            if($aks){
            foreach($aks as $id => $data){
                if($data['accesskey']){
                    AccesskeyHelper::$accesskeys[$id] = $data['accesskey'];
                }
                if($data['note']){
                    if($data['accesskey']){
                        AccesskeyHelper::$titles[$id] = "[{$data['accesskey']}] - " . $data['note'];
                    } else {
                        AccesskeyHelper::$titles[$id] = $data['note'];
                    }
                }
            }
        }
    }

    public static function getAccessKeys(){
        if(!is_array(AccesskeyHelper::$accesskeys)){
            AccesskeyHelper::init();
        }
        return AccesskeyHelper::$accesskeys;
    }

    public static function getTitles(){
        if(!is_array(AccesskeyHelper::$titles)){
            AccesskeyHelper::init();
        }
        return AccesskeyHelper::$titles;
    }

}
?>

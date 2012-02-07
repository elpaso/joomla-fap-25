<?php
/**
* This file is part of Joomla! 1.5 FAP
* @version      $Id: helper.php 9877 2008-01-05 12:37:25Z mtk $
* @package      JoomlaFAP
* @copyright    Copyright (C) 2008 Alessandro Pasotti http://www.itopen.it
* @license      GNU/AGPL

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

// Impedisce l'accesso diretto al file
defined('_JEXEC') or die( 'Restricted access' );

class TableAccesskeys extends JTable {

    var $id = null;
    var $name = null;
    var $parent = null;
    var $published = null;
    var $accesskey = null;
    var $title  = null;

    function TableAccesskeys(& $db) {
        parent::__construct('#__menu', 'id', $db);
    }

    function check() {

        if(strlen ($this->accesskey) > 1) {
            // Too long!
            $this->setError(JText::_('\1\U'));
            return false;
        }
        //if( ord('Z') < ord($this->accesskey) || ord($this->accesskey) < ord('A')){
        if( !preg_match('/^[A-Z0-9]$/', $this->accesskey)){
            // Not a letter!
            $this->setError(JText::_('\1\U'));
            return false;
        }
        return true;
    }
}
?>
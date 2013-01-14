<?php
/**
* This file is part of
* Joomla! 2.5 FAP
* @package   JoomlaFAP
* @author    Alessandro Pasotti
* @copyright    Copyright (C) 2012 Alessandro Pasotti http://www.itopen.it
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


defined( '_JEXEC' ) or die( 'Restricted access' );

/**
* Map to beez-2
*/
function get_accessible_pos($pos){

    $_accessible_position_aliases = array(
        'right' => array('position-6', 'position-8', 'position-3'),
        'left' => 'position-4',
        'user4' => 'position-7',
        'inset' => 'position-5',
        'banner' => 'position-0',
        'footer' => 'position-14',
        'user1' => 'position-9',
        'user2' => 'position-10',
        'bottom' => 'position-11',
        'breadcrumb' => 'position-1',
        'center' => 'position-12',
        'user3' => 'position-2'
    );

    $res = array();
    $separator = '';
    if(strpos($pos, ' or ') !== -1){
        $separator = ' or ';
    } elseif(strpos($pos, ' and ') !== -1){
        $separator = ' and ';
    }
    if(!$separator) {
        $separator = ' or ';
        $parms = array($pos);
    } else {
        $parms = explode($separator, $pos);
    }
    foreach($parms as $p){
        $res[] = $p;
        if(array_key_exists($p, $_accessible_position_aliases)){
            if(is_array($_accessible_position_aliases[$p])){
                $res = array_merge($res, $_accessible_position_aliases[$p]);
            } else {
                $res[] = $_accessible_position_aliases[$p];
            }
        }
    }
    $res = implode($separator, $res);
    //echo("<p>$pos => $res</p>");
    return $res;
}

?>

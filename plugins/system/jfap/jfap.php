<?php
/**
* This file is part of Joomla! 2.5 FAP
* @package      JoomlaFAP
* @copyright    Copyright (C) 2008-2013 Alessandro Pasotti http://www.itopen.it
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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * Joomla! jFap plugin
 *
 * @package     jFap
 * @subpackage  System
 */
class  plgSystemJFap extends JPlugin
{
    function onAfterRender(){
        $mainframe = JFactory::getApplication();
        if ($mainframe->isAdmin()){
            return true;
        }
        $body = JResponse::getBody();

        # Too hungry:
        #$style_regexp = '@<span[^>]*>@is';
        #$style_replace = ''

        # Remove style from span
        $style_regexp = '@<span([^>]*?)\sstyle=(["\']).*?\2([^>]*?)>@is';
        $style_replace = '<span\1\3>';
        $img_regexp = '@<img([^>]*?)\sborder=(["\']).*?\2([^>]*?)>@is';
        $img_replace = '<img\1\3>';
        # Dublin Core MD
        $dc_desc_regexp = '#<meta name="description"#';
        $dc_desc_replace = '<meta name="DC.Description"';
        $body = preg_replace(
                    array($dc_desc_regexp,
                        '/target=[\'"][^\'"]+/',
                        $style_regexp,
                        '/(<meta name="generator" content=")([^"]+)"/',
                        $img_regexp
                        ),
                     array($dc_desc_replace,
                        'onclick="window.open(this.href);return false;" onkeypress="return handle_keypress(event, function(){window.open(this.href);});',
                        $style_replace,
                        '\1\2 - Versione FAP"',
                        $img_replace
                        ),
                    $body);
        # onkeypress
        # Already in the accessibility links
        #$body = preg_replace('|onclick="(.*?)"|mus', 'onclick="\1" onkeypress="\1"', $body);
        JResponse::setBody($body);
    }

}

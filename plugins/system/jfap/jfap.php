<?php
/**
* @version		$Id:$
* @package		Plg_jFap
* @copyright	Copyright (C) 2011 ItOpen. All rights reserved.
* @licence      GNU/AGPL
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

/**
 * Joomla! jFap plugin
 *
 * @package		jFap
 * @subpackage	System
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
        # Dublin Core MD
        $dc_desc_regexp = '#<meta name="description"#';
        $dc_desc_replace = '<meta name="DC.Description"';
        $body = preg_replace(array($dc_desc_regexp, '/target=[\'"][^\'"]+/', $style_regexp, '/(<meta name="generator" content=")([^"]+)"/'),
                             array($dc_desc_replace, 'onclick="window.open(this.href);return false;', $style_replace, '\1\2 - Versione FAP"'), $body);
        # onkeypress
        # Already in the accessibility links
        # $body = preg_replace('|onclick="(.*?)"|mus', 'onclick="\1" onkeypress="\1"', $body);
        JResponse::setBody($body);
    }

}

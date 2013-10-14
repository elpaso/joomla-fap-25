<?php
/**
* This file is part of
* Joomla! 2.5 FAP
* @package   JoomlaFAP
* @author    Alessandro Pasotti
* @copyright    Copyright (C) 2013 Alessandro Pasotti http://www.itopen.it
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

if(!defined('__FAP_UTILS__')) {
    /**
     * Add onkeypress to onclick events
     */
    function fap_add_onkeypress($html){
        return preg_replace('|onclick="(.*?)"|mus', 'onclick="\1" onkeypress="return handle_keypress(event, function(){\1;})"', $html);
    }


    /**
     * Fix filter: remove onchange and add submit
     */
    function fap_fix_filter($html){
        return str_replace('onchange="this.form.submit()"', '', $html) . '<button type="submit">' . JText::_('FAP_FORM_SUBMIT'). '</button>';
    }


    /**
     * Load language file from template
     */
    function fap_load_tpl_lang(){
        $app = & JFactory::getApplication();
        $template = $app->getTemplate();
        $lang = JFactory::getLanguage();
        $extension = 'tpl_' . $template;
        $base_dir = JPATH_SITE;
        $language_tag = $lang->getTag();
        $reload = false;
        $lang->load($extension, $base_dir, $language_tag, $reload);
    }
    define('__FAP_UTILS__', 1);
}

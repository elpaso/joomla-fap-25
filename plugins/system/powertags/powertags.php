<?php
/**
*
* @package      PLG_POWERTAGS
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

// Load main PowerTagsHelper or exit
$to_include = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_powertags'.DS.'helpers'.DS.'powertags.php';
if(file_exists($to_include)) {
    require_once($to_include);
}


jimport( 'joomla.plugin.plugin' );


/**
 * Capture new tags on new article save and display tags in the content's body
 */
class plgSystemPowerTags extends JPlugin
{

	function __construct( $subject, $config)
	{
        parent::__construct($subject, $config);
        if(!class_exists('PowerTagsHelper')){
            return false;
        }
		$this->loadLanguage('plg_system_powertags');
        $this->template = PowerTagsHelper::getDeleteTemplate();
	}

	function onAfterInitialise()
	{
            $option = JRequest::getVar('option');
            $layout = JRequest::getVar('layout');
            if ('com_content' !== $option || 'edit' !== $layout ) {
                return true;
            }

            $settings = JComponentHelper::getParams('com_powertags');
            JFactory::getDocument()->addStylesheet(JURI::root() . 'media/com_powertags/css/plugin.css');
            // Must use JHTML because it loads after mootools when last arg is true

            JFactory::getDocument()->addScriptDeclaration("
                    var pwtags_entry_point = \""  . JURI::base() . "?option=com_powertags\";\n
                    var pwtags_component = \"$option\";\n
                    var pwtags_template = \"" . addcslashes($this->template, '"') ."\";\n"
            ) ;
            $jspath = JURI::root() . 'media/com_powertags/js/';
            JFactory::getDocument()->addStylesheet($jspath . 'autocompleter/Autocompleter.css');
            JHTML::script('plugin.js', $jspath,  true);
            // Load autocompleter
            JHTML::script('autocompleter/Autocompleter.js', $jspath,  true);
            JHTML::script('autocompleter/Autocompleter.Local.js', $jspath,  true);
            JHTML::script('autocompleter/Autocompleter.Request.js', $jspath,  true);
            JHTML::script('autocompleter/Observer.js', $jspath,  true);
            JText::script('PLG_POWERTAGS_XHR_FAILED');
            JText::script('PLG_POWERTAGS_XHR_EMPTY_TAGS');
            JText::script('PLG_POWERTAGS_ADD_ITEM_FAILED');
            JText::script('PLG_POWERTAGS_DEL_ITEM_FAILED');
        }

	function onAfterRender()
        {
            $option = JRequest::getVar('option');
            $layout = JRequest::getVar('layout');
            if ('com_content' !== $option || 'edit' !== $layout ) {
                return true;
            }

            $article_id = JRequest::getVar('id');

            if(!$article_id){
                $article_id = JRequest::getVar('cid');
                if(is_array($article_id)){
                    $article_id = $article_id[0];
                }
            }

            // Front-end editor
            if(!$article_id){
                $article_id = JRequest::getVar('a_id');
            }

            if( $article_id){
                $tags = PowerTagsHelper::getItemTags($article_id, $option);
            } else {
                $tags = array();
            }

            // Tags for new articles
            $new_tags = trim(htmlspecialchars(JRequest::getVar('pwtags_tags_new')));
            // Retrieve from the session
            if(!$new_tags){
           		$session = JFactory::getSession();
                $new_tags = $session->get('pwtags_tags_new', null, $option);
                $session->clear('pwtags_tags_new', $option);
            }

            if(!$tags && $new_tags){
                foreach (explode(',', $new_tags) as $t){
                    $_t =  trim($t);
                    if($_t){
                        $tags[$_t] = $_t;
                    }
                }
            }

            // Inject...
            $plg_body = '<div id="pwtags_editor"><label for="pwtags_input_tag">' . JText::_('PLG_POWERTAGS_TAGS_LABEL') . '</label>' ;
            $plg_body .= '<div class="pwtags_tags_new"><input id="pwtags_input_tag" type="text" /><input id="pwtags_tags_new" name="pwtags_tags_new" type="hidden" value="' . $new_tags . '" />'
                    . '<a id="pwtags_new_btn" href="javascript:void(0)" title="'
                    . JText::_('PLG_POWERTAGS_ADD_NEW')
                    . '"><img src="' . JURI::root()
                    . 'media/com_powertags/images/icon-16-newtag.png" alt="'
                    . JText::_('PLG_POWERTAGS_ADD_NEW')
                    . '"/></a></div><div id="pwtags_tag_list">';

            foreach ($tags as $tag_item_id => $tag){
                $plg_body .= str_replace(array( '$id', '$tag'),
                        array($tag_item_id, $tag), $this->template);
            }
            $plg_body .= '<div class="clr"></div></div></div>';

            if(PowerTagsHelper::getConfig('be_editor_position') == 'before'){
                JResponse::setBody(str_replace('<label id="jform_articletext-lbl"',
                        $plg_body . '<label id="jform_articletext-lbl"'
                        , JResponse::getBody()));
            } else {
                JResponse::setBody(str_replace('<div id="editor-xtd-buttons">',
                    $plg_body . '<div id="editor-xtd-buttons">'
                    , JResponse::getBody()));
            }

        }


}

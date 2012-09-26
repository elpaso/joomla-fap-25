<?php
/**
* @version 1.2
* @name htmlPurifier
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* This is a Joomla! 1.6 implementation of the HTML Purifier 4.2 (http://htmlpurifier.org)
* Requires PHP 5
* For more information visit http://nemesisdesign.net/blog/coding/html-purifier-plugin-joomla/
* Last update: 07-11-2010
*/

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

/**
 * HTML Purifier 1.2
 *
 * @package	Joomla
 * @subpackage	Content
 * @since	1.5
 */
class plgContenthtmlpurifier extends JPlugin
{
	/**
	 * HTML Purifier 1.2 prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param	$context	string	The context of the content being passed to the plugin.
	 * @param	$article	object	The content object.  Note $article->text is also available
	 * @param	$params	object	The content params
	 * @param	$limitstart	int The 'page' number
	 * @since	1.5
	 */
	public function onContentPrepare($context, &$article, &$params, $limitstart)
	{
		$app = JFactory::getApplication();


        $doctypes = array(1=>'XHTML 1.0 Transitional', 2=>'XHTML 1.0 Strict', 3=>'HTML 4.01 Transitional', 4=>'HTML 4.01 Strict');
		$tidylevels = array(1=>'light', 2=>'medium', 3=>'heavy');

        if ($this->params->def('enabled', 1) && strripos($article->text, "{disablepurifier}") === false) {

            /*
            echo '<h1>BEFORE Autoloaders</h1>
            <pre>';
            print_r(spl_autoload_functions());
            echo '</pre>';
            */

            // ABP: Disable spl functions
            if ($funcs = spl_autoload_functions()){
                foreach($funcs as $func){
                    spl_autoload_unregister($func);
                }
            }

            $file_name = JPATH_PLUGINS . DS . 'content'.DS.'htmlpurifier'.DS.'htmlpurifier4.2' . DS . 'library' . DS . 'HTMLPurifier.auto.php';
            require_once ($file_name);

            // ABP: Re-enable spl functions
            if ($funcs){
                foreach($funcs as $func){
                    if(is_callable($func)) {
                        spl_autoload_register($func);
                    }
                }
                // Re-register
                // Import the library loader if necessary.
                if (!class_exists('JLoader'))
                {
                    require_once JPATH_PLATFORM . '/loader.php';
                }
                class_exists('JLoader') or die;
                // Setup the autoloaders.
                JLoader::setup();                

            }

			// configure it
			$config = HTMLPurifier_Config :: createDefault();

			$config->set('Core.Encoding', 'utf-8');
			$config->set('Core.RemoveScriptContents', false);

			$config->set('HTML.Doctype', $doctypes[$this->params->get('doctype', 1)]);
			$config->set('HTML.TidyLevel', $tidylevels[$this->params->get('tidylevel', 2)]);
			$config->set('HTML.Allowed', null);

			$config->set('Attr.EnableID', true);
                        //def('tidylevel', 2)
			$config->set('HTML.Trusted', ($this->params->get('trusted')==1) ? true : false );
			$config->set('CSS.Proprietary', ($this->params->get('proprietary')==1) ? true : false );
			$config->set('AutoFormat.Linkify', $this->params->get('linkify'));

			// prevent stripping target="_blank" and similar
                        $config->set('Attr.AllowedFrameTargets', array(
                            0=>'_self',
		    	    1=>'_blank',
		    	    2=>'_parent',
		    	    3=>'_top'
			));

			$cache =  ($this->params->get('htmlpurifier_cache', 'Serializer') != 'Serializer') ? null : 'Serializer';
			$config->set('Cache.DefinitionImpl', $cache);
            // perform the replacement
			$purifier = new HTMLPurifier($config);


			$article->text = $purifier->purify($article->text);

            /*
            echo '<h1>AFTER Autoloaders</h1>
            <pre>';
			print_r(spl_autoload_functions());
            echo '</pre>';
            */
		}
		else{
			$article->text = str_replace("{disablepurifier}", "", $article->text);
		}

	}

}

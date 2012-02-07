<?php
/**
* This file is part of
* Joomla! 1.7 FAP
* @package   JoomlaFAP
* @author    Alessandro Pasotti
* @copyright    Copyright (C) 2011 Alessandro Pasotti http://www.itopen.it
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

JHtml::_('behavior.framework', true);


// xml prolog
echo '<?xml version="1.0" encoding="'. $this->_charset .'"?' .'>';


$cols = 1;
if ($this->countModules('right')
	&& JRequest::getCmd('layout') != 'form'
	&& JRequest::getCmd('task') != 'edit') {
	$cols += 1;
}

if($this->countModules('left or inset or user4')) {
	$cols += 1;
}

/* Accessibility session storage */
$session = JFactory::getSession();

if($fap_skin_request = JRequest::getVar('fap_skin')){
    $fap_skin_current = $session->get('fap_skin_current');
    if(!$fap_skin_current){
        $fap_skin_current = $this->params->get('default_skin');
    }
    switch($fap_skin_request) {
        case 'reset':
            $fap_skin_current = $this->params->get('default_skin');
            $session->set('fap_font_size', 80);
        break;
        case 'liquid':
            if(strpos($fap_skin_current, ' liquid') !== false){
                $fap_skin_current = preg_replace('# liquid#', '', $fap_skin_current);
            } else {
                $fap_skin_current .= ' liquid';
            }
        break;
        case 'contrasthigh':
            if(strpos($fap_skin_current, 'white') !== false){
                $fap_skin_current = str_replace('white', 'black', $fap_skin_current);
            } else {
                $fap_skin_current = str_replace('black', 'white', $fap_skin_current);
            }
        break;
    }
    $session->set('fap_skin_current', $fap_skin_current);
}


if($fap_font_size_request = JRequest::getVar('fap_font_size')){
    $font_size = $session->get('fap_font_size');
    if(!$font_size){
        $font_size = 80;
    }
    if('increase' == $fap_font_size_request){
        $font_size += 5;
    } else {
        $font_size -= 5;
    }
    $font_size = max($font_size, 70);
    $font_size = min($font_size, 100);
    $session->set('fap_font_size', $font_size);
}



?>
<!DOCTYPE html
     PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<meta name="language" content="<?php echo $this->language; ?>" />
<jdoc:include type="head" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/template_css.css" rel="stylesheet" type="text/css"/>
<?php if (file_exists(dirname(__FILE__).'/css/custom_theme.css')) { ?>
    <link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/custom_theme.css" rel="stylesheet" type="text/css"/>
<?php } ?>
<!--[if IE]>
<link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/msie6.css" rel="stylesheet" type="text/css"/>
<![endif]-->
<script type="text/javascript">
/* <![CDATA[ */
    var skin_default = '<?php echo $this->params->get('default_skin').($this->params->get('default_variant') ? ' ' . $this->params->get('default_variant') : ''); ?>';
    <?php if($fap_skin_current = $session->get('fap_skin_current')){ ?>
    var skin_current = <?php echo $fap_skin_current; ?>;
    <?php } ?>
    // To validator...

/* ]]> */
</script>
<?php // set font_size & skin from session
if($fap_font_size = $session->get('fap_font_size')){ ?>
<style type="text/css">
    body#main {
        font-size: <?php echo $fap_font_size ?>%;
    }
</style>
<?php } ?>
<script type="text/javascript" src="<?php echo JURI::base();?>templates/<?php echo $this->template;?>/js/skin_alter.js"></script>
</head>
<body class="<?php echo ($fap_skin_current ? $fap_skin_current : $this->params->get('default_skin').($this->params->get('default_variant') ? ' ' . $this->params->get('default_variant') : ''));?>" id="main">
	<div class="hidden">
		<a name="up" id="up"></a>
		<h1><?php echo $this->description; ?></h1>
		<!-- accesskey goes here! -->
		<ul>
			<li><a accesskey="P" href="#main-content"><?php echo JText::_('FAP_SKIP_TO_CONTENT'); ?></a></li>
			<li><a accesskey="M" href="#main-menu"><?php echo JText::_('FAP_JUMP_TO_MAIN_NAVIGATION_AND_LOGIN'); ?></a></li>
		</ul>
	</div>
    <div id="wrapper">
        <?php if ($this->countModules('breadcrumb')) { ?>
        <div id="pathway">
            <div class="padding">
            <jdoc:include type="modules" name="breadcrumb" />
            </div>
        </div>
        <?php } ?>
        <div id="top">

          <div class="padding">
            <?php if('no' == $this->params->get('accessibility_icons')) { ?>
            <div id="accessibility-links">
                <form id="fap_skin" method="post" action="">
                    <div>
                        <?php echo JText::_('FAP_FONTSIZE'); ?>
                        <button type="button" name="fap_font_size" value="decrease" id="decrease" accesskey="D" onclick="fs_change(-1); return false;" onkeypress="return handle_keypress(function(){fs_change(-1);});" title="<?php echo JText::_('FAP_DECREASE_SIZE'); ?> [D]"><?php echo JText::_('FAP_SMALLER'); ?></button>
                        <button type="button"  name="fap_font_size" value="increase" id="increase" accesskey="A" onclick="fs_change(1); return false;" onkeypress="return handle_keypress(function(){fs_change(1);});" title="<?php echo JText::_('FAP_INCREASE_SIZE'); ?> [A]"><?php echo JText::_('FAP_BIGGER'); ?></button>
                        <button type="button" name="fap_skin" value="contrasthigh" id="contrasthigh" accesskey="X" onclick="skin_change('swap');return false;" onkeypress="return handle_keypress(function(){skin_change('swap');});" title="<?php echo JText::_('FAP_HIGH_CONTRAST'); ?> [X]"><?php echo JText::_('FAP_CONTRAST'); ?></button>
                        <?php if('yes' == $this->params->get('liquid_variant')) { ?>
                        <button type="button" name="fap_skin" value="liquid" id="liquid" accesskey="L" onclick="skin_set_variant('liquid'); return false;" onkeypress="return handle_keypress(function(){skin_set_variant('liquid');});" title="<?php echo JText::_('FAP_SET_VARIABLE_WIDTH'); ?> [L]"><?php echo JText::_('FAP_VARIABLE_WIDTH'); ?></button>
                        <?php } ?>
                        <button type="button" name="reset" id="reset" value="<?php echo JText::_('FAP_RESET'); ?>" accesskey="Z" onclick="skin_change('<?php echo $this->params->get('default_skin'); ?>'); skin_set_variant(''); fs_set(fs_default); return false;" onkeypress="return handle_keypress(function(){skin_change(\'<?php echo $this->params->get('default_skin'); ?>'); skin_set_variant(''); fs_set(fs_default);});" title="<?php echo JText::_('FAP_REVERT_STYLES_TO_DEFAULT'); ?> [Z]"><?php echo JText::_('FAP_RESET'); ?></button>
                    </div>
                </form>

            </div>
            <?php } else { ?>
            <div id="accessibility-links" class="accessibility-icons">
                <form id="accessibility-form" method="post" action="">
                    <div>
                        <span class="accessibility-text"><?php echo JText::_('FAP_FONTSIZE'); ?></span>
                        <span class="accessibility-icon"><button type="submit" name="fap_font_size" value="decrease" id="decrease" accesskey="D" onclick="fs_change(-1); return false;" onkeypress="return handle_keypress(function(){fs_change(-1);});" title="<?php echo JText::_('FAP_DECREASE_SIZE'); ?> [D]"><span><?php echo JText::_('FAP_DECREASE_SIZE'); ?></span></button></span>
                        <span class="accessibility-icon"><button type="submit" name="fap_font_size" value="increase" id="increase" accesskey="A" onclick="fs_change(1); return false;" onkeypress="return handle_keypress(function(){fs_change(1);});" title="<?php echo JText::_('FAP_INCREASE_SIZE'); ?> [A]" ><span><?php echo JText::_('FAP_INCREASE_SIZE'); ?></span></button></span>
                        <span class="accessibility-text"><?php echo JText::_('FAP_CONTRAST'); ?></span>
                        <span class="accessibility-icon"><button type="submit" name="fap_skin" value="contrasthigh" id="contrasthigh" accesskey="X" onclick="skin_change('swap'); return false;" onkeypress="return handle_keypress(function(){skin_change('swap');});" title="<?php echo JText::_('FAP_HIGH_CONTRAST'); ?> [X]"><span><?php echo JText::_('FAP_HIGH_CONTRAST'); ?></span></button></span>
                        <?php if('yes' == $this->params->get('liquid_variant')) { ?>
                        <span class="accessibility-text"><?php echo JText::_('FAP_LAYOUT'); ?></span>
                        <span class="accessibility-icon"><button type="submit" name="fap_skin" value="liquid" id="layouttext" accesskey="L" onclick="skin_set_variant('liquid'); return false;" onkeypress="return handle_keypress(function(){skin_set_variant('liquid');});" title="<?php echo JText::_('FAP_SET_VARIABLE_WIDTH'); ?> [L]" ><span><?php echo JText::_('FAP_SET_VARIABLE_WIDTH'); ?></span></button></span>
                        <?php } ?>
                        <span class="accessibility-text"><?php echo JText::_('FAP_reset'); ?></span>
                        <span class="accessibility-icon"><button type="submit" name="fap_skin" value="reset" id="reset" accesskey="Z" onclick="skin_change('<?php echo $this->params->get('default_skin'); ?>'); skin_set_variant(''); fs_set(fs_default); return false;" onkeypress="return handle_keypress(function(){skin_change(\'<?php echo $this->params->get('default_skin'); ?>'); skin_set_variant(''); fs_set(fs_default);});" title="<?php echo JText::_('FAP_REVERT_STYLES_TO_DEFAULT'); ?> [Z]"><span><?php echo JText::_('FAP_REVERT_STYLES_TO_DEFAULT'); ?></span></button></span>
                    </div>
                </form>
            </div>
            <?php } ?>
          </div>
        </div>
        <?php // Banner from component or CSS
        if ($this->countModules('banner')) { ?>
        <div id="banner">
            <div class="padding">
            <jdoc:include type="modules" name="banner" style="xhtml" />
            </div>
        </div>
        <?php } ?>
            <?php if ($this->countModules('user3')) { ?>
            <div id="menu-top">
                <div class="padding">
                <jdoc:include type="modules" name="user3" style="xhtml" />
                </div>
            </div>
            <?php } ?>
            <div class="clr"></div>
            <?php if ($this->countModules('left or inset or user4')) { ?>
            <div id="sidebar-left">
            <div class="padding">
            	<?php if ($this->countModules('user4')) { ?>
                <div id="searchbox">
                    <jdoc:include type="modules" name="user4" style="xhtml" />
                </div>
                <?php } ?>
                <a name="main-menu" class="hidden"></a>
                <jdoc:include type="modules" name="left" style="xhtml" />
                <?php if ($this->countModules('inset')) { ?>
                <div class="inset">
                    <jdoc:include type="modules" name="inset" style="xhtml" />
                </div>
                <?php } ?>
            </div>
            </div>
        <?php } ?>
        <?php if ($this->countModules('right') 	&& JRequest::getCmd('layout') != 'form'
	&& JRequest::getCmd('task') != 'edit') { ?>
        <div id="sidebar-right">
            <div class="padding">
                <jdoc:include type="modules" name="right" style="xhtml" />
            </div>
        </div>
        <?php } ?>
        <div id="main-<?php print $cols ;?>" class="maincomponent">
          <?php if ($this->countModules('center')){ ?>
          <div id="center-module">
              <div class="padding">
                  <jdoc:include type="modules" name="center" style="xhtml" />
              </div>
          </div>
          <?php } ?>
            <a name="main-content" class="hidden"></a>
            <div class="padding">
            <jdoc:include type="message" />
            <?php if ($this->countModules('top') && JRequest::getCmd('task') != 'edit') { ?>
            <jdoc:include type="modules" name="top" style="xhtml" />
            <div class="clr"></div>
            <?php } ?>
            <jdoc:include type="component" style="xhtml"/>
            <div id="user12">
                <?php if ($this->countModules('user1 or user2') && ! $this->countModules('user1 and user2')) { ?>
                <div class="userfull">
                    <jdoc:include type="modules" name="user1" style="xhtml" />
                    <jdoc:include type="modules" name="user2" style="xhtml" />
                </div>
                <?php } ?>
                <?php if ($this->countModules('user1 and user2')) { ?>
                <div class="column_left">
                    <jdoc:include type="modules" name="user1" style="xhtml" />
                </div>
                <div class="column_right">
                    <jdoc:include type="modules" name="user2" style="xhtml" />
                </div>
                <?php } ?>
            </div>
            <?php if ($this->countModules('bottom') && JRequest::getCmd('task') != 'edit') { ?>
            <jdoc:include type="modules" name="bottom" style="xhtml" />
            <div class="clr"></div>
            <?php } ?>
            </div>
        </div>
        <div id="footer">
            <div class="padding">
                <?php if ($this->countModules('footer')) { ?>
                <jdoc:include type="modules" name="footer" style="xhtml" />
				<?php } ?>
            </div>

        </div>
    </div>
    <jdoc:include type="modules" name="debug" />
</body>
</html>
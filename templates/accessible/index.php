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

JHtml::_('behavior.framework', true);

if($this->params->get('beez2_positions') == 'yes'){
	$beez2_positions = true;
	require_once(JPATH_THEMES.'/'.$this->template.'/aliases.php');
} else {
	function get_accessible_pos($pos){
		return $pos;
	}
	$beez2_positions = false;
}

// xml prolog
echo '<?xml version="1.0" encoding="'. $this->_charset .'"?' .'>';


$cols = 1;
if ($this->countModules(get_accessible_pos('right'))
	&& JRequest::getCmd('layout') != 'form'
	&& JRequest::getCmd('task') != 'edit') {
	$cols += 1;
}

if($this->countModules(get_accessible_pos('left or inset or user4'))) {
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
<?php if (file_exists(JPATH_THEMES.'/'.$this->template.'/css/skin_white.less')) { ?>
<link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/skin_white.less" type="text/css" rel="stylesheet/less" />
<link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/skin_black.less" type="text/css" rel="stylesheet/less" />
<script type="text/javascript" src="<?php echo JURI::base();?>templates/<?php echo $this->template;?>/js/less-1.3.0.min.js"></script>
<?php } else { ?>
<link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/skin_white.css" type="text/css" rel="stylesheet" />
<link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/skin_black.css" type="text/css" rel="stylesheet" />
<?php } ?>
<link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/template_css.css" rel="stylesheet" type="text/css"/>
<?php if ($this->params->get('custom_theme') && file_exists(JPATH_THEMES.'/'.$this->template.'/css/'. $this->params->get('custom_theme').'.css')) { ?>
    <link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/<?php echo $this->params->get('custom_theme').'.css' ?>" rel="stylesheet" type="text/css"/>
<?php } ?>
<!--[if IE]>
<link href="<?php echo JURI::base();?>templates/<?php echo $this->template; ?>/css/msie6.css" rel="stylesheet" type="text/css"/>
<![endif]-->
<script type="text/javascript">
/* <![CDATA[ */
    var skin_default = '<?php echo $this->params->get('default_skin').($this->params->get('default_variant') ? ' ' . $this->params->get('default_variant') : ''); ?>';
    <?php if($fap_skin_current = $session->get('fap_skin_current')){ ?>
    var skin_current = "<?php echo $fap_skin_current; ?>";
    <?php } ?>
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
        <?php if ($this->countModules(get_accessible_pos('breadcrumb'))) { ?>
        <div id="pathway">
            <div class="padding">
            <jdoc:include type="modules" name="breadcrumb" />
            <?php if($beez2_positions){ ?>
            <jdoc:include type="modules" name="position-1" />
			<?php } ?>
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
                        <button type="button" name="reset" id="reset" value="<?php echo JText::_('FAP_RESET'); ?>" accesskey="Z" onclick="skin_change('<?php echo $this->params->get('default_skin'); ?>'); skin_set_variant(''); fs_set(fs_default); return false;" onkeypress="return handle_keypress(function(){skin_change('<?php echo $this->params->get('default_skin'); ?>'); skin_set_variant(''); fs_set(fs_default);});" title="<?php echo JText::_('FAP_REVERT_STYLES_TO_DEFAULT'); ?> [Z]"><?php echo JText::_('FAP_RESET'); ?></button>
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
                        <span class="accessibility-icon"><button type="submit" name="fap_skin" value="reset" id="reset" accesskey="Z" onclick="skin_change('<?php echo $this->params->get('default_skin'); ?>'); skin_set_variant(''); fs_set(fs_default); return false;" onkeypress="return handle_keypress(function(){skin_change('<?php echo $this->params->get('default_skin'); ?>'); skin_set_variant(''); fs_set(fs_default);});" title="<?php echo JText::_('FAP_REVERT_STYLES_TO_DEFAULT'); ?> [Z]"><span><?php echo JText::_('FAP_REVERT_STYLES_TO_DEFAULT'); ?></span></button></span>
                    </div>
                </form>
            </div>
            <?php } ?>
          </div>
        </div>
        <?php // Banner from component or CSS
        if ($this->countModules(get_accessible_pos('banner'))) { ?>
        <div id="banner">
            <div class="padding">
            <jdoc:include type="modules" name="banner" style="accessible" />
            <?php if($beez2_positions){ ?>
            <jdoc:include type="modules" name="position-0" style="accessible" />
            <?php } ?>
            </div>
        </div>
        <?php } ?>
            <?php if ($this->countModules(get_accessible_pos('user3'))) { ?>
            <div id="menu-top">
                <div class="padding">
                <jdoc:include type="modules" name="user3" style="accessible" />
                <?php if($beez2_positions){ ?>
                <jdoc:include type="modules" name="position-2" style="accessible" />
                <?php } ?>
                </div>
            </div>
            <?php } ?>
            <div class="clr"></div>
            <?php if ($this->countModules(get_accessible_pos('left or inset or user4'))) { ?>
            <div id="sidebar-left">
            <div class="padding">
                <a name="main-menu" class="hidden"></a>
            	<?php if ($this->countModules(get_accessible_pos('user4'))) { ?>
                <div id="searchbox">
                    <jdoc:include type="modules" name="user4" style="accessible" />
                    <?php if($beez2_positions){ ?>
                    <jdoc:include type="modules" name="position-7" style="accessible" />
                    <?php } ?>
                </div>
                <?php } ?>
                <jdoc:include type="modules" name="left" style="accessible" />
                <?php if($beez2_positions){ ?>
                <jdoc:include type="modules" name="position-4" style="accessible" />
                <?php } ?>
                <?php if ($this->countModules(get_accessible_pos('inset'))) { ?>
                <div class="inset">
                    <jdoc:include type="modules" name="inset" style="accessible" />
                    <?php if($beez2_positions){ ?>
                    <jdoc:include type="modules" name="position-5" style="accessible" />
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
            </div>
        <?php } ?>
        <?php if ($this->countModules(get_accessible_pos('right')) 	&& JRequest::getCmd('layout') != 'form'
	&& JRequest::getCmd('task') != 'edit') { ?>
        <div id="sidebar-right">
            <div class="padding">
                <jdoc:include type="modules" name="right" style="accessible" />
                <?php if($beez2_positions){ ?>
                <jdoc:include type="modules" name="position-6" style="accessible" />
                <jdoc:include type="modules" name="position-8" style="accessible" />
                <jdoc:include type="modules" name="position-3" style="accessible" />
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        <div id="main-<?php print $cols ;?>" class="maincomponent">
          <?php if ($this->countModules(get_accessible_pos('center'))){ ?>
          <div id="center-module">
              <div class="padding">
                  <jdoc:include type="modules" name="center" style="accessible" />
                  <?php if($beez2_positions){ ?>
                  <jdoc:include type="modules" name="position-12" style="accessible" />
                  <?php } ?>
              </div>
          </div>
          <?php } ?>
            <a name="main-content" class="hidden"></a>
            <div class="padding">
            <jdoc:include type="message" />
            <?php if ($this->countModules(get_accessible_pos('top')) && JRequest::getCmd('task') != 'edit') { ?>
            <jdoc:include type="modules" name="top" style="accessible" />
            <div class="clr"></div>
            <?php } ?>
            <jdoc:include type="component" style="accessible"/>
            <div id="user12">
                <?php if ($this->countModules(get_accessible_pos('user1 or user2')) && ! $this->countModules(get_accessible_pos('user1 and user2'))) { ?>
                <div class="userfull">
                    <jdoc:include type="modules" name="user1" style="accessible" />
                    <jdoc:include type="modules" name="user2" style="accessible" />
                    <?php if($beez2_positions){ ?>
                    <jdoc:include type="modules" name="position-9" style="accessible" />
                    <jdoc:include type="modules" name="position-10" style="accessible" />
                    <jdoc:include type="modules" name="position-11" style="accessible" />
                    <?php } ?>
                </div>
                <?php } ?>
                <?php if ($this->countModules(get_accessible_pos('user1 and user2'))) { ?>
                <div class="column_left">
                    <jdoc:include type="modules" name="user1" style="accessible" />
                </div>
                <div class="column_right">
                    <jdoc:include type="modules" name="user2" style="accessible" />
                </div>
                <?php } ?>
            </div>
            <?php if ($this->countModules(get_accessible_pos('bottom')) && JRequest::getCmd('task') != 'edit') { ?>
            <jdoc:include type="modules" name="bottom" style="accessible" />
            <div class="clr"></div>
            <?php } ?>
            </div>
        </div>
        <div id="footer">
            <div class="padding">
                <?php if ($this->countModules(get_accessible_pos('footer'))) { ?>
                <jdoc:include type="modules" name="footer" style="accessible" />
                <?php if($beez2_positions){ ?>
                <jdoc:include type="modules" name="position-14" style="accessible" />
                <?php } ?>
				<?php } ?>
            </div>

        </div>
    </div>
    <jdoc:include type="modules" name="debug" />
</body>
</html>

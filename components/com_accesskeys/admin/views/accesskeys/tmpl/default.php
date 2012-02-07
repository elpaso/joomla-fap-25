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
require JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'accesskeys.php';

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.framework', true);

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

$menu_options = JHtml::_('menu.menus');
$menu_blank     = new stdClass();
$menu_blank->value = '';
$menu_blank->text= '--';
$menu_options[] = $menu_blank;

?>
<?php //Set up the filter bar. ?>
<form action="<?php echo JRoute::_('index.php?option=com_accesskeys');?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltrt">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('Menu filter'); ?>&nbsp;</label>

			<select name="menutype" class="inputbox" onchange="this.form.submit()">
				<?php echo JHtml::_('select.options', $menu_options, 'value', 'text', $this->state->get('filter.menutype'), true);?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5%">
				<?php echo JText::_('ID'); ?>
			</th>
            <th>
                <?php echo JText::_('Menu'); ?>
            </th>
			<th width="50%">
                <?php echo JText::_('Title'); ?>
            </th>
			<th>
                <?php echo JText::_('Access key'); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
		<td colspan="4">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	$n=count($this->items);

    // Set record Id
    if(is_array($this->ak_record) && count($this->ak_record)){
        $this->ak_record =  $this->ak_record[0];
    } else {
        $this->ak_record = 0; // Fake
    }

	for($i=0 ; $i < $n; $i++) {
		$row =& $this->items[$i];

		// Crea link per editare il record
		$edit_link = JRoute::_( 'index.php?option=com_accesskeys&cid[]='. $row->id );
		// Crea link per eliminare il record
 		$delete_link = JRoute::_( 'index.php?option=com_accesskeys&task=accesskey.remove&cid[]='. $row->id );

	?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $row->id; ?>
			</td>
			<td>
				<?php echo $row->btitle; ?>
			</td>
            <td>
                <?php

                    /*if($row->id == $this->ak_record){
                        ?><input type="text" size="64" maxlength="255" name="title" value="<?php echo $row->title ?>" />
                        <?php
                    }
                    else*/
                    {
                        ?>
                        <?php echo $row->title; ?><?php
                    }
                ?>
            </td>
            <td>
                <?php

                    if($row->id == $this->ak_record){
                        ?><input type="hidden" id="cid_<?php echo $this->ak_record ?>" name="cid[]" value="<?php echo $this->ak_record ?>"/><input type="text" size="1" name="accesskey" value="<?php echo $row->accesskey ?>" />&nbsp;<button type="submit" class="ak_ok" onclick="$('task').value='accesskey.save';"><?php echo JText::_( 'Save' )?></button>&nbsp;<button type="submit" onclick="$('task').value='';"  class="ak_ko"><?php echo JText::_( 'Cancel' )?></button>
                        <?php
                    }
                    else
                    {
                    	echo JHTML::link($edit_link, JText::_( 'Edit' ));
                        ?>&nbsp;
                        <?php
                        	if(isset($row->accesskey) && '' != $row->accesskey){
                    			echo JHTML::link($delete_link, JText::_( 'Delete' ));
                    		}
                        ?>
                        &nbsp;<strong style="color:red"><?php echo $row->accesskey; ?></strong><?php
                    }
                ?>
            </td>
		</tr>
	<?php
		//Per la classe che imposta lo sfondo delle righe a colori alternati
		$k = 1 - $k;
	}
	?>
	</tbody>
	</table>
</div>
<input type="hidden" name="option" value="com_accesskeys" />
<input type="hidden" name="task" id="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="accesskeys" />
<?php echo JHtml::_('form.token'); ?>
</form>
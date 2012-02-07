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

// Include la classe base JView
jimport('joomla.application.component.view');

class AccesskeysViewAccesskeys extends JView {

	protected $items;
	protected $ak_record;
	protected $state;
	protected $pagination;


    function display($tpl = null) {

        $this->assignRef('ak_record', JRequest::getVar('cid'));

        // Assign data to the view
        $this->ak_record    = JRequest::getVar('cid');
        $this->items        = $this->get('Items');
        $this->pagination   = $this->get('Pagination');
        $this->state        = $this->get('State');
        //var_dump($this->state); die();
        JToolBarHelper::title(JText::_('Access keys administration') );
        parent::display($tpl);
    }
}
?>
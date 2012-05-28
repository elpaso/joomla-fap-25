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
defined('_JEXEC') or die();

class AccesskeysControllerAccesskeys extends AccesskeysController {

	function __construct() {

		parent::__construct();

		// task add processato da metodo edit()
		$this->registerTask('add', 'edit');
	}

	function edit() {
		JRequest::setVar('view', 'accesskeys');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}


	function save() {
		$model = $this->getModel('accesskeys');

        // Get Var
		if($model->store()) {
			$msg = JText::_('\1\U');
		} else {
			$msg = JText::_('\1\U') . ': ' . $model->getError();
		}

		$link = 'index.php?option=com_accesskeys';
		$this->setRedirect($link, $msg);
	}

	function remove() {
		$model = $this->getModel('accesskeys');

        // Get Var
		if($model->remove()) {
			$msg = JText::_('\1\U');
		} else {
			$msg = JText::_('\1\U') . ': ' . $model->getError();
		}

		$link = 'index.php?option=com_accesskeys';
		$this->setRedirect($link, $msg);
	}



	function cancel() {
		$msg = JText::_('\1\U');
		$this->setRedirect( 'index.php?option=com_accesskeys', $msg );
	}
}
?>
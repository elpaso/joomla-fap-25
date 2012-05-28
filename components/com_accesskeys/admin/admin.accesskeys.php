


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
// Set some global property
$document = JFactory::getDocument();
$document->addStyleDeclaration('.ak_ok, .ak_ko, .ak_ok:hover, .ak_ko:hover{border:solid 1px gray;background-repeat: no-repeat;padding-left: 15px;}');
$document->addStyleDeclaration('.ak_ok, .ak_ok:hover {background-image: url(../media/com_accesskeys/images/ok.png);}');
$document->addStyleDeclaration('.ak_ko,.ak_ko:hover {background-image: url(../media/com_accesskeys/images/ko.png);}');
// import joomla controller library
jimport('joomla.application.component.controller');


$controller = JController::getInstance('AccessKeys');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
?>
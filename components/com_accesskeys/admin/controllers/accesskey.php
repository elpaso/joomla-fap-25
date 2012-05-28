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

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * Controller
 */
class AccesskeysControllerAccesskey extends JControllerForm
{

    function save(){
        $ids	= JRequest::getVar('cid', array(), '', 'array');
        $model = $this->getModel('accesskey');
        $table = $model->getTable();
        $table->load($ids[0]);
        $table->accesskey = substr(JRequest::getString('accesskey'), 0, 1);
        if($table->check()){
            if($table->store()){
                $this->setMessage(JText::_('Access key saved'));
            } else {
                $this->setMessage(JText::_('Error saving access key'), 'error');
            }
        } else {
            $this->setMessage($table->getError(), 'error');
        }
        $this->setRedirect('?option=com_accesskeys');
    }

    function remove(){
        $ids    = JRequest::getVar('cid', array(), '', 'array');
        $model = $this->getModel('accesskey');
        $table = $model->getTable();
        $table->load($ids[0]);
        if($table->delete()){
            $this->setMessage(JText::_('Access key deleted'));
        } else {
            $this->setMessage(JText::_('Error deleting access key'), 'error');
        }
        $this->setRedirect('?option=com_accesskeys');
    }
}
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

// Include la classe base JModel
jimport('joomla.application.component.modellist');

class AccesskeysModelAccesskeys extends JModelList {

	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'accesskey', 'a.accesskey',
				'menutype', 'a.menutype',
			);
		}

		parent::__construct($config);
	}
 	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.id,a.accesskey,a.title,a.menutype,b.title as btitle');

		// Filter by access level.
		if ($menutype = $this->getState('filter.menutype')) {
			$query->where('a.menutype = ' . $db->quote($db->getEscaped($menutype)));
		}

		// From the menu table
		$query->from('#__menu as a');
		$query->join('', '#__menu_types AS b ON a.menutype = b.menutype');
		$query->where('a.published = 1 AND a.client_id = 0 AND a.level > 0');
		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

        $value = $app->getUserStateFromRequest($this->context.'.menutype', 'menutype', null, 'string');
        $this->setState('filter.menutype', $value);

		// List state information.
		parent::populateState('a.accesskey', 'asc');
	}


}
?>
<?php
/**
 * @package     Team
 * @subpackage  com_team
 *
 * @author      Bruno Batista <bruno@atomtech.com.br>
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Team helper.
 *
 * @package     Team
 * @subpackage  com_team
 * @author      Bruno Batista <bruno@atomtech.com.br>
 * @since       3.2
 */
class TeamHelper extends JHelperContent
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_TEAM_SUBMENU_USERS'),
			'index.php?option=com_team&view=users',
			$vName == 'users'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_TEAM_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_team',
			$vName == 'categories'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_TEAM_SUBMENU_FEATURED'),
			'index.php?option=com_team&view=featured',
			$vName == 'featured'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_TEAM_SUBMENU_POSTS'),
			'index.php?option=com_team&view=posts',
			$vName == 'posts'
		);
	}
}

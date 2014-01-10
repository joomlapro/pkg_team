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

// Load the tabstate behavior script.
JHtml::_('behavior.tabstate');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_team'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Register dependent classes.
JLoader::register('TeamHelper', __DIR__ . '/helpers/team.php');
JLoader::register('UsersHelper', __DIR__ . '/helpers/users.php');
JLoader::register('PostsHelper', __DIR__ . '/helpers/posts.php');

// Execute the task.
$controller = JControllerLegacy::getInstance('Team');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();

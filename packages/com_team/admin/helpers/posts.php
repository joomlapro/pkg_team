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
 * Posts helper.
 *
 * @package     Team
 * @subpackage  com_team
 * @author      Bruno Batista <bruno@atomtech.com.br>
 * @since       3.2
 */
class PostsHelper
{
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   string  $assetName  The asset name.
	 *
	 * @return  JObject  A JObject containing the allowed actions.
	 *
	 * @since   3.2
	 */
	public static function getActions($assetName = '')
	{
		// Initialiase variables.
		$user   = JFactory::getUser();
		$result = new JObject;
		$path   = JPATH_ADMINISTRATOR . '/components/com_team/access.xml';

		$actions = JAccess::getActionsFromFile($path, "/access/section[@name='component']/");

		foreach ($actions as $action)
		{
			$result->set($action->name, $user->authorise($action->name, 'com_team'));
		}

		return $result;
	}

	/**
	 * Get a list of filter options for the posts.
	 *
	 * @return  array  An array of JHtmlOption elements.
	 *
	 * @return  3.2
	 */
	public static function getPostOptions()
	{
		// Initialize variables.
		$options = array();
		$db      = JFactory::getDbo();
		$query   = $db->getQuery(true);

		// Create the base select statement.
		$query->select('a.id AS value, a.title AS text')
			->from($db->quoteName('#__team_posts') . ' AS a')
			->order($db->quoteName('a.ordering'));

		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return $options;
	}
}

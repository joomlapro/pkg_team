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
defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * Post Field class for the Team.
 *
 * @package     Team
 * @subpackage  com_team
 * @author      Bruno Batista <bruno@atomtech.com.br>
 * @since       3.2
 */
class JFormFieldPost extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 * @since   3.2
	 */
	protected $type = 'Post';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   3.2
	 */
	protected function getOptions()
	{
		// Initialiase variables.
		$options = PostsHelper::getPostOptions();

		// Merge any additional options in the XML definition.
		return array_merge(parent::getOptions(), $options);
	}
}

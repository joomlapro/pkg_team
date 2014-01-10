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
 * Team Component Category Tree.
 *
 * @static
 * @package     Team
 * @subpackage  com_team
 * @author      Bruno Batista <bruno@atomtech.com.br>
 * @since       3.2
 */
class TeamCategories extends JCategories
{
	/**
	 * Class constructor.
	 *
	 * @param   array  $options  Array of options.
	 *
	 * @since   3.2
	 */
	public function __construct($options = array())
	{
		$options['table']     = '#__team';
		$options['extension'] = 'com_team';

		parent::__construct($options);
	}
}

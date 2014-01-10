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
 * Featured Table class.
 *
 * @package     Team
 * @subpackage  com_team
 * @author      Bruno Batista <bruno@atomtech.com.br>
 * @since       3.2
 */
class TeamTableFeatured extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabase  &$db  Driver A database connector object.
	 *
	 * @since   3.2
	 */
	public function __construct(& $db)
	{
		parent::__construct('#__team_frontpage', 'user_id', $db);
	}
}

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
 * Team Component Controller.
 *
 * @package     Team
 * @subpackage  com_team
 * @author      Bruno Batista <bruno@atomtech.com.br>
 * @since       3.2
 */
class TeamController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached.
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JControllerLegacy  This object to support chaining.
	 *
	 * @since   3.2
	 */
	public function display($cachable = false, $urlparams = false)
	{
		// Initialise variables.
		$cachable = true;

		// Set the default view name and format from the Request.
		// Note we are using t_id to avoid collisions with the router and the return page.
		$id       = $this->input->getInt('t_id');
		$vName    = $this->input->get('view', 'categories');

		$this->input->set('view', $vName);

		// Get the current user object.
		$user     = JFactory::getUser();

		if ($user->get('id') || ($this->input->getMethod() == 'POST' && (($vName == 'category' && $this->input->get('layout') != 'blog') || $vName == 'archive' )))
		{
			$cachable = false;
		}

		$safeurlparams = array(
			'catid'            => 'INT',
			'id'               => 'INT',
			'cid'              => 'ARRAY',
			'year'             => 'INT',
			'month'            => 'INT',
			'limit'            => 'UINT',
			'limitstart'       => 'UINT',
			'showall'          => 'INT',
			'return'           => 'BASE64',
			'filter'           => 'STRING',
			'filter_order'     => 'CMD',
			'filter_order_Dir' => 'CMD',
			'filter-search'    => 'STRING',
			'print'            => 'BOOLEAN',
			'lang'             => 'CMD',
			'Itemid'           => 'INT'
		);

		// Check for edit form.
		if ($vName == 'userform' && !$this->checkEditId('com_team.edit.user', $id))
		{
			// Somehow the person just went to the form - we do not allow that.
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}

		parent::display($cachable, $safeurlparams);

		return $this;
	}
}

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
 * View class for a list of posts.
 *
 * @package     Team
 * @subpackage  com_team
 * @author      Bruno Batista <bruno@atomtech.com.br>
 * @since       3.2
 */
class TeamViewPosts extends JViewLegacy
{
	/**
	 * List of update items.
	 *
	 * @var     array
	 */
	protected $items;

	/**
	 * List pagination.
	 *
	 * @var     JPagination
	 */
	protected $pagination;

	/**
	 * The model state.
	 *
	 * @var     JObject
	 */
	protected $state;

	/**
	 * List of authors.
	 *
	 * @var     array
	 */
	protected $authors;

	/**
	 * The form filter.
	 *
	 * @var     JForm
	 */
	public $filterForm;

	/**
	 * List of active filters.
	 *
	 * @var     array
	 */
	public $activeFilters;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  Exception on failure, void on success.
	 *
	 * @since   3.2
	 */
	public function display($tpl = null)
	{
		try
		{
			// Initialise variables.
			$this->items         = $this->get('Items');
			$this->pagination    = $this->get('Pagination');
			$this->state         = $this->get('State');
			$this->authors       = $this->get('Authors');
			$this->filterForm    = $this->get('FilterForm');
			$this->activeFilters = $this->get('ActiveFilters');
		}
		catch (Exception $e)
		{
			JErrorPage::render($e);

			return false;
		}

		// We do not need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			// Load the submenu.
			TeamHelper::addSubmenu('posts');

			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	protected function addToolbar()
	{
		// Initialise variables.
		$state = $this->get('State');
		$canDo = PostsHelper::getActions(0, 'com_team');
		$user  = JFactory::getUser();

		// Get the toolbar object instance.
		$bar   = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_TEAM_MANAGER_POSTS_TITLE'), 'stack posts');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('post.add');
		}

		if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own')))
		{
			JToolbarHelper::editList('post.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('posts.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('posts.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::archiveList('posts.archive');
			JToolbarHelper::checkin('posts.checkin');
		}

		if ($state->get('filter.state') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('', 'posts.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('posts.trash');
		}

		if ($user->authorise('core.admin', 'com_team'))
		{
			JToolbarHelper::preferences('com_team');
		}

		JToolBarHelper::help('posts', $com = true);
	}
}

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

// Load the helper class.
JLoader::register('TeamHelper', JPATH_ADMINISTRATOR . '/components/com_team/helpers/team.php');

/**
 * Item Model for an Post.
 *
 * @package     Team
 * @subpackage  com_team
 * @author      Bruno Batista <bruno@atomtech.com.br>
 * @since       3.2
 */
class TeamModelPost extends JModelAdmin
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var     string
	 * @since   3.2
	 */
	protected $text_prefix = 'COM_TEAM_POST';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   3.2
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->state != -2)
			{
				return;
			}

			// Get the current user object.
			$user = JFactory::getUser();

			return $user->authorise('core.delete', 'com_team');
		}
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since   3.2
	 */
	protected function canEditState($record)
	{
		// Get the current user object.
		$user = JFactory::getUser();

		// Check for existing post.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_team');
		}
		// Default to component settings if neither post.
		else
		{
			return parent::canEditState('com_team');
		}
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable  $table  A JTable object.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	protected function prepareTable($table)
	{
		// Reorder the posts so the new post is first.
		if (empty($table->id))
		{
			$table->reorder('state >= 0');
		}
	}

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param   type    $type    The table type to instantiate.
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable    A database object.
	 *
	 * @since   3.2
	 */
	public function getTable($type = 'Post', $prefix = 'TeamTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure.
	 *
	 * @since   3.2
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_team.post', 'post', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		$jinput = JFactory::getApplication()->input;

		// The front end calls this model and uses t_id to avoid id clashes so we need to check for that first.
		if ($jinput->get('t_id'))
		{
			$id = $jinput->get('t_id', 0);
		}
		// The back end uses id so we use that the rest of the time and set it to 0 by default.
		else
		{
			$id = $jinput->get('id', 0);
		}

		// Determine correct permissions to check.
		if ($this->getState('post.id'))
		{
			$id = $this->getState('post.id');
		}

		// Get the current user object.
		$user = JFactory::getUser();

		// Check for existing post.
		// Modify the form based on Edit State access controls.
		if ($id == 0 && !$user->authorise('core.edit.state', 'com_team'))
		{
			// Disable fields for display.
			$form->setFieldAttribute('state', 'disabled', 'true');
			$form->setFieldAttribute('ordering', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an post you can edit.
			$form->setFieldAttribute('state', 'filter', 'unset');
			$form->setFieldAttribute('ordering', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   3.2
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_team.edit.post.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_team.post', $data);

		return $data;
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   object  $table  A record object.
	 *
	 * @return  array  An array of conditions to add to add to ordering queries.
	 *
	 * @since   3.2
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();

		return $condition;
	}

	/**
	 * Saves the manually set order of records.
	 *
	 * @param   array    $pks    An array of primary key ids.
	 * @param   integer  $order  +1 or -1
	 *
	 * @return  mixed
	 *
	 * @since   3.2
	 */
	public function saveorder($pks = null, $order = null)
	{
		// Initialiase variables.
		$table      = $this->getTable();
		$conditions = array();

		if (empty($pks))
		{
			return JError::raiseWarning(500, JText::_($this->text_prefix . '_ERROR_NO_ITEMS_SELECTED'));
		}

		// Update ordering values.
		foreach ($pks as $i => $pk)
		{
			$table->load((int) $pk);

			// Access checks.
			if (!$this->canEditState($table))
			{
				// Prune items that you can't change.
				unset($pks[$i]);

				JLog::add(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), JLog::WARNING, 'jerror');
			}
			elseif ($table->ordering != $order[$i])
			{
				$table->ordering = $order[$i];

				if (!$table->store())
				{
					$this->setError($table->getError());
					return false;
				}

				// Remember to reorder within position and client_id.
				$condition = $this->getReorderConditions($table);
				$found = false;

				foreach ($conditions as $cond)
				{
					if ($cond[1] == $condition)
					{
						$found = true;
						break;
					}
				}

				if (!$found)
				{
					$key = $table->getKeyName();
					$conditions[] = array($table->$key, $condition);
				}
			}
		}

		// Execute reorder for each category.
		foreach ($conditions as $cond)
		{
			$table->load($cond[0]);
			$table->reorder($cond[1]);
		}

		// Clear the component's cache.
		$this->cleanCache();

		return true;
	}
}

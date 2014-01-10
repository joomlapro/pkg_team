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

/**
 * Supports a modal user picker.
 *
 * @package     Team
 * @subpackage  com_team
 * @since       3.2
 */
class JFormFieldModal_User extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var     string
	 * @since   3.2
	 */
	protected $type = 'Modal_User';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.2
	 */
	protected function getInput()
	{
		// Initialiase variables.
		$allowEdit  = ((string) $this->element['edit'] == 'true') ? true : false;
		$allowClear = ((string) $this->element['clear'] != 'false') ? true : false;

		// Load language.
		JFactory::getLanguage()->load('com_team', JPATH_ADMINISTRATOR);

		// Load the modal behavior script.
		JHtml::_('behavior.modal', 'a.modal');

		// Build the script.
		$script = array();

		// Select button script.
		$script[] = 'function jSelectUser_' . $this->id . '(id, name, catid, object) {';
		$script[] = '	document.getElementById("' . $this->id . '_id").value = id;';
		$script[] = '	document.getElementById("' . $this->id . '_name").value = name;';

		if ($allowEdit)
		{
			$script[] = '	jQuery("#' . $this->id . '_edit").removeClass("hidden");';
		}

		if ($allowClear)
		{
			$script[] = '	jQuery("#' . $this->id . '_clear").removeClass("hidden");';
		}

		$script[] = '	SqueezeBox.close();';
		$script[] = '}';

		// Clear button script.
		static $scriptClear;

		if ($allowClear && !$scriptClear)
		{
			$scriptClear = true;

			$script[] = 'function jClearUser(id) {';
			$script[] = '	document.getElementById(id + "_id").value = "";';
			$script[] = '	document.getElementById(id + "_name").value = "' . htmlspecialchars(JText::_('COM_TEAM_SELECT_AN_USER', true), ENT_COMPAT, 'UTF-8') . '";';
			$script[] = '	jQuery("#"+id + "_clear").addClass("hidden");';
			$script[] = '	if (document.getElementById(id + "_edit")) {';
			$script[] = '		jQuery("#"+id + "_edit").addClass("hidden");';
			$script[] = '	}';
			$script[] = '	return false;';
			$script[] = '}';
		}

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html = array();
		$link = 'index.php?option=com_team&amp;view=users&amp;layout=modal&amp;tmpl=component&amp;function=jSelectUser_' . $this->id;

		if (isset($this->element['language']))
		{
			$link .= '&amp;forcedLanguage=' . $this->element['language'];
		}

		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('name')
			->from($db->quoteName('#__team'))
			->where($db->quoteName('id') . ' = ' . $db->quote((int) $this->value));

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$name = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		if (empty($name))
		{
			$name = JText::_('COM_TEAM_SELECT_AN_USER');
		}

		$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

		// The active user id field.
		if (0 == (int) $this->value)
		{
			$value = '';
		}
		else
		{
			$value = (int) $this->value;
		}

		// The current user display field.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-medium" id="' . $this->id . '_name" value="' . $name . '" disabled="disabled" size="35" />';
		$html[] = '<a class="modal btn hasTooltip" title="' . JHtml::tooltipText('COM_TEAM_CHANGE_USER') . '" href="' . $link . '&amp;' . JSession::getFormToken() . '=1" rel="{handler: \'iframe\', size: {x: 800, y: 450}}"><i class="icon-file"></i> ' . JText::_('JSELECT') . '</a>';

		// Edit user button.
		if ($allowEdit)
		{
			$html[] = '<a class="btn hasTooltip' . ($value ? '' : ' hidden') . '" href="index.php?option=com_team&layout=modal&tmpl=component&task=user.edit&id=' . $value . '" target="_blank" title="' . JHtml::tooltipText('COM_TEAM_EDIT_USER') . '" ><span class="icon-edit"></span> ' . JText::_('JACTION_EDIT') . '</a>';
		}

		// Clear user button.
		if ($allowClear)
		{
			$html[] = '<button id="' . $this->id . '_clear" class="btn' . ($value ? '' : ' hidden') . '" onclick="return jClearUser(\'' . $this->id . '\')"><span class="icon-remove"></span> ' . JText::_('JCLEAR') . '</button>';
		}

		$html[] = '</span>';

		// Set class='required' for client side validation.
		$class = '';

		if ($this->required)
		{
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" />';

		return implode("\n", $html);
	}
}

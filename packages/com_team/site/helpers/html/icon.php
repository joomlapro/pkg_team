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
 * Team Component HTML Helper.
 *
 * @package     Team
 * @subpackage  com_team
 * @since       3.2
 */
abstract class JHtmlIcon
{
	/**
	 * Method to generate a link to the create item page for the given category.
	 *
	 * @param   object     $category  The category information.
	 * @param   JRegistry  $params    The item parameters.
	 * @param   array      $attribs   Optional attributes for the link.
	 * @param   boolean    $legacy    True to use legacy images, false to use icomoon based graphic.
	 *
	 * @return  string  The HTML markup for the create item link.
	 *
	 * @since   3.2
	 */
	public static function create($category, $params, $attribs = array(), $legacy = false)
	{
		// Load the tooltip bootstrap script.
		JHtml::_('bootstrap.tooltip');

		// Get the full current URI.
		$uri = JUri::getInstance();
		$url = 'index.php?option=com_team&task=user.add&return=' . base64_encode($uri) . '&t_id=0&catid=' . $category->id;

		if ($params->get('show_icons'))
		{
			if ($legacy)
			{
				$text = JHtml::_('image', 'system/new.png', JText::_('JNEW'), null, true);
			}
			else
			{
				$text = '<span class="icon-plus"></span>&#160;' . JText::_('JNEW') . '&#160;';
			}
		}
		else
		{
			$text = JText::_('JNEW') . '&#160;';
		}

		// Add the button classes to the attribs array.
		if (isset($attribs['class']))
		{
			$attribs['class'] = $attribs['class'] . ' btn btn-primary';
		}
		else
		{
			$attribs['class'] = 'btn btn-primary';
		}

		$button = JHtml::_('link', JRoute::_($url), $text, $attribs);

		$output = '<span class="hasTooltip" title="' . JHtml::tooltipText('COM_TEAM_CREATE_USER') . '">' . $button . '</span>';

		return $output;
	}

	/**
	 * Method to generate a link to the email item page for the given user.
	 *
	 * @param   object     $user  The user information.
	 * @param   JRegistry  $params   The item parameters.
	 * @param   array      $attribs  Optional attributes for the link.
	 * @param   boolean    $legacy   True to use legacy images, false to use icomoon based graphic.
	 *
	 * @return  string  The HTML markup for the email item link.
	 *
	 * @since   3.2
	 */
	public static function email($user, $params, $attribs = array(), $legacy = false)
	{
		// Load the frontend helper.
		require_once JPATH_SITE . '/components/com_mailto/helpers/mailto.php';

		// Initialiase variables.
		$uri      = JUri::getInstance();
		$base     = $uri->toString(array('scheme', 'host', 'port'));
		$template = JFactory::getApplication()->getTemplate();
		$link     = $base . JRoute::_(TeamHelperRoute::getUserRoute($user->slug, $user->catid), false);
		$url      = 'index.php?option=com_mailto&tmpl=component&template=' . $template . '&link=' . MailToHelper::addLink($link);

		$status = 'width=400,height=350,menubar=yes,resizable=yes';

		if ($params->get('show_icons'))
		{
			if ($legacy)
			{
				$text = JHtml::_('image', 'system/emailButton.png', JText::_('JGLOBAL_EMAIL'), null, true);
			}
			else
			{
				$text = '<span class="icon-envelope"></span> ' . JText::_('JGLOBAL_EMAIL');
			}
		}
		else
		{
			$text = JText::_('JGLOBAL_EMAIL');
		}

		$attribs['title']   = JText::_('JGLOBAL_EMAIL');
		$attribs['onclick'] = "window.open(this.href,'win2','" . $status . "'); return false;";

		$output = JHtml::_('link', JRoute::_($url), $text, $attribs);

		return $output;
	}

	/**
	 * Display an edit icon for the user.
	 *
	 * This icon will not display in a popup window, nor if the user is trashed.
	 * Edit access checks must be performed in the calling code.
	 *
	 * @param   object     $user  The user information.
	 * @param   JRegistry  $params   The item parameters.
	 * @param   array      $attribs  Optional attributes for the link.
	 * @param   boolean    $legacy   True to use legacy images, false to use icomoon based graphic.
	 *
	 * @return  string  The HTML for the user edit icon.
	 *
	 * @since   3.2
	 */
	public static function edit($user, $params, $attribs = array(), $legacy = false)
	{
		// Initialiase variables.
		$user = JFactory::getUser();
		$uri  = JUri::getInstance();

		// Ignore if in a popup window.
		if ($params && $params->get('popup'))
		{
			return;
		}

		// Ignore if the state is negative (trashed).
		if ($user->state < 0)
		{
			return;
		}

		JHtml::_('bootstrap.tooltip');

		// Show checked_out icon if the user is checked out by a different user.
		if (property_exists($user, 'checked_out') && property_exists($user, 'checked_out_time') && $user->checked_out > 0 && $user->checked_out != $user->get('id'))
		{
			$checkoutUser = JFactory::getUser($user->checked_out);
			$button       = JHtml::_('image', 'system/checked_out.png', null, null, true);
			$date         = JHtml::_('date', $user->checked_out_time);
			$tooltip      = JText::_('JLIB_HTML_CHECKED_OUT') . ' :: ' . JText::sprintf('COM_TEAM_CHECKED_OUT_BY', $checkoutUser->name) . ' <br /> ' . $date;

			return '<span class="hasTooltip" title="' . JHtml::tooltipText($tooltip . '', 0) . '">' . $button . '</span>';
		}

		$url = 'index.php?option=com_team&task=user.edit&t_id=' . $user->id . '&return=' . base64_encode($uri);

		if ($user->state == 0)
		{
			$overlib = JText::_('JUNPUBLISHED');
		}
		else
		{
			$overlib = JText::_('JPUBLISHED');
		}

		$date    = JHtml::_('date', $user->created);
		$author  = $user->created_by_alias ? $user->created_by_alias : $user->author;

		$overlib .= '&lt;br /&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br /&gt;';
		$overlib .= JText::sprintf('COM_TEAM_WRITTEN_BY', htmlspecialchars($author, ENT_COMPAT, 'UTF-8'));

		if ($legacy)
		{
			$icon = $user->state ? 'edit.png' : 'edit_unpublished.png';
			$text = JHtml::_('image', 'system/' . $icon, JText::_('JGLOBAL_EDIT'), null, true);
		}
		else
		{
			$icon = $user->state ? 'edit' : 'eye-close';
			$text = '<span class="hasTooltip icon-' . $icon . ' tip" title="' . JHtml::tooltipText(JText::_('COM_TEAM_EDIT_ITEM'), $overlib, 0) . '"></span>&#160;' . JText::_('JGLOBAL_EDIT') . '&#160;';
		}

		$output = JHtml::_('link', JRoute::_($url), $text, $attribs);

		return $output;
	}

	/**
	 * Method to generate a popup link to print an user.
	 *
	 * @param   object     $user  The user information.
	 * @param   JRegistry  $params   The item parameters.
	 * @param   array      $attribs  Optional attributes for the link.
	 * @param   boolean    $legacy   True to use legacy images, false to use icomoon based graphic.
	 *
	 * @return  string  The HTML markup for the popup link.
	 *
	 * @since   3.2
	 */
	public static function print_popup($user, $params, $attribs = array(), $legacy = false)
	{
		// Initialiase variables.
		$app     = JFactory::getApplication();
		$input   = $app->input;
		$request = $input->request;

		$url     = TeamHelperRoute::getUserRoute($user->slug, $user->catid);
		$url     .= '&tmpl=component&print=1&layout=default&page=' . @ $request->limitstart;

		$status  = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

		// Checks template image directory for image, if non found default are loaded.
		if ($params->get('show_icons'))
		{
			if ($legacy)
			{
				$text = JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), null, true);
			}
			else
			{
				$text = '<span class="icon-print"></span>&#160;' . JText::_('JGLOBAL_PRINT') . '&#160;';
			}
		}
		else
		{
			$text = JText::_('JGLOBAL_PRINT');
		}

		$attribs['title']   = JText::_('JGLOBAL_PRINT');
		$attribs['onclick'] = "window.open(this.href,'win2','" . $status . "'); return false;";
		$attribs['rel']     = 'nofollow';

		return JHtml::_('link', JRoute::_($url), $text, $attribs);
	}

	/**
	 * Method to generate a link to print an user.
	 *
	 * @param   object     $user  Not used, @deprecated for 4.0.
	 * @param   JRegistry  $params   The item parameters.
	 * @param   array      $attribs  Not used, @deprecated for 4.0.
	 * @param   boolean    $legacy   True to use legacy images, false to use icomoon based graphic.
	 *
	 * @return  string  The HTML markup for the popup link.
	 *
	 * @since   3.2
	 */
	public static function print_screen($user, $params, $attribs = array(), $legacy = false)
	{
		// Checks template image directory for image, if none found default are loaded.
		if ($params->get('show_icons'))
		{
			if ($legacy)
			{
				$text = JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), null, true);
			}
			else
			{
				$text = '<span class="icon-print"></span>&#160;' . JText::_('JGLOBAL_PRINT') . '&#160;';
			}
		}
		else
		{
			$text = JText::_('JGLOBAL_PRINT');
		}

		return '<a href="#" onclick="window.print();return false;">' . $text . '</a>';
	}
}

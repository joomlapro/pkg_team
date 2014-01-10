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

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Load the behavior script.
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

// Initialiase variables.
$this->hiddenFieldsets    = array();
$this->hiddenFieldsets[0] = 'basic-limited';
$this->configFieldsets    = array();
$this->configFieldsets[0] = 'editorConfig';

// Create shortcut to parameters.
$params = $this->state->get('params');

// This checks if the config options have ever been saved. If they haven't they will fall back to the original settings.
$params = json_decode($params);

if (!isset($params->show_publishing_options))
{
	$params->show_publishing_options = '1';
}

// Check if the post uses configuration settings besides global. If so, use them.
if (isset($this->item->params['show_publishing_options']) && $this->item->params['show_publishing_options'] != '')
{
	$params->show_publishing_options = $this->item->params['show_publishing_options'];
}
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'post.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			if (window.opener && (task == 'post.save' || task == 'post.cancel')) {
				window.opener.document.closeEditWindow = self;
				window.opener.setTimeout('window.document.closeEditWindow.close()', 1000);
			}

			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}
</script>
<div class="container-popup">
	<div class="pull-right">
		<button class="btn btn-primary" type="button" onclick="Joomla.submitbutton('post.apply');"><?php echo JText::_('JTOOLBAR_APPLY'); ?></button>
		<button class="btn btn-primary" type="button" onclick="Joomla.submitbutton('post.save');"><?php echo JText::_('JTOOLBAR_SAVE'); ?></button>
		<button class="btn" type="button" onclick="Joomla.submitbutton('post.cancel');"><?php echo JText::_('JCANCEL'); ?></button>
	</div>
	<div class="clearfix"></div>
	<hr class="hr-condensed" />
	<form action="<?php echo JRoute::_('index.php?option=com_team&layout=modal&tmpl=component&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
		<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>
		<div class="form-horizontal">
			<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_TEAM_FIELDSET_POST_CONTENT', true)); ?>
					<div class="row-fluid">
						<div class="span3">
							<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
						</div>
					</div>
				<?php echo JHtml::_('bootstrap.endTab'); ?>

				<?php // Do not show the publishing options if the edit form is configured not to. ?>
				<?php if ($params->show_publishing_options == 1): ?>
					<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_TEAM_FIELDSET_PUBLISHING', true)); ?>
						<div class="row-fluid form-horizontal-desktop">
							<div class="span6">
								<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
							</div>
						</div>
					<?php echo JHtml::_('bootstrap.endTab'); ?>
				<?php endif; ?>
			<?php echo JHtml::_('bootstrap.endTabSet'); ?>
			<div>
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="return" value="<?php echo JFactory::getApplication()->input->getBase64('return'); ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</div>
	</form>
</div>

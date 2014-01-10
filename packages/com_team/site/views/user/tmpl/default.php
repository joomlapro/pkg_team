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

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();

// Load the tooltip behavior script.
JHtml::_('behavior.caption');
?>
<div class="team-profile user-item<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)): ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
		<h2>
			<small><?php echo $this->escape($this->item->post_title); ?></small>
			<?php echo $this->escape($this->item->name); ?>
		</h2>
	<?php else: ?>
		<div class="page-header">
			<h1>
				<small><?php echo $this->escape($this->item->post_title); ?></small>
				<?php echo $this->escape($this->item->name); ?>
			</h1>
		</div>
	<?php endif; ?>

	<div class="row">
		<div class="col-md-8">
			<dl class="dl-horizontal">
				<?php if ($this->item->email): ?>
					<dt><?php echo JText::_('COM_TEAM_HEADING_EMAIL') ?></dt>
					<dd><?php echo JHtml::_('email.cloak', $this->item->email); ?></dd>
				<?php endif ?>
				<?php if ($this->item->phone): ?>
					<dt><?php echo JText::_('COM_TEAM_HEADING_PHONE') ?></dt>
					<dd><?php echo $this->escape($this->item->phone); ?></dd>
				<?php endif ?>
				<?php if ($this->item->address): ?>
					<dt><?php echo JText::_('COM_TEAM_HEADING_ADDRESS') ?></dt>
					<dd><?php echo $this->escape($this->item->address); ?></dd>
				<?php endif ?>
			</dl>
			<?php if ($this->item->biography): ?>
				<h3><?php echo JText::_('COM_TEAM_HEADING_BIOGRAPHY') ?></h3>
				<?php echo $this->item->biography; ?>
			<?php endif; ?>
		</div>
		<div class="col-md-4">
			<figure>
				<?php $image = $this->item->image ? JUri::root() . 'images/users/' . $this->item->image : 'com_team/no_avatar.png'; ?>
				<?php echo JHtml::_('image', $image, $this->item->name, array('class' => 'img-rounded'), true); ?>
			</figure>
		</div>
	</div>
</div>

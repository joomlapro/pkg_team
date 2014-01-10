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

// Include the component helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
?>
<div class="category-list<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading', 1)): ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<?php $groups = array_chunk($this->items, 4); ?>
	<?php foreach ($groups as $group): ?>
		<div class="row">
			<?php foreach ($group as $item): ?>
				<div class="col-md-3">
					<div class="team-profile">
						<figure>
							<a href="<?php echo JRoute::_(TeamHelperRoute::getUserRoute($item->slug, $item->catslug)); ?>">
								<?php $image = $item->image ? JUri::root() . 'images/users/' . $item->image : 'com_team/no_avatar.png'; ?>
								<?php echo JHtml::_('image', $image, $item->name, array('class' => 'img-rounded'), true); ?>
							</a>
						</figure>
						<h6><?php echo $this->escape($item->post_title); ?></h6>
						<h4><a href="<?php echo JRoute::_(TeamHelperRoute::getUserRoute($item->slug, $item->catslug)); ?>"><?php echo $this->escape($item->name); ?></a></h4>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
</div>

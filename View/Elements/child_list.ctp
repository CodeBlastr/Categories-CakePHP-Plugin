<div class="categories child-list" id="parent<?php echo $parentId; ?>">
	<?php if (!empty($parentId)) : ?>
		<?php $this->Category = $this->Helpers->load('Categories.Category'); ?>
		<?php $categories = $this->Category->find('list', array('conditions' => array('Category.parent_id' => $parentId))); ?>
		<ul>
			<?php foreach ($categories as $id => $category) : ?>
				<li><?php echo $this->Html->link($category, array('plugin' => $plugin, 'controller' => $controller, 'action' => $action, $id)); ?></li>
		<?php endforeach; ?>
		</ul>
	<?php else : ?>
		<p>No parent category provided.</p>
	<?php endif; ?>
</div>
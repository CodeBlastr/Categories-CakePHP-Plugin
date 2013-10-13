<div class="categories row-fluid">
	<?php if (!empty($categories)) : ?>
	<div class="accordion span7" id="accordion">
		<?php $i=0; foreach ($categories as $model => $thread) : ?>
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $model; ?>"> <?php echo $model; ?> Categories</a>
			</div>
			<div id="<?php echo $model; ?>" class="accordion-body collapse <?php echo $i==0 ? 'in' : null; ?>" style="max-height: 200px;overflow:scroll;">
				<div class="accordion-inner">
					<div class="row-fluid">
						<div class="span5 pull-left">
							<?php echo $this->Tree->generate($thread, array('element' => 'categories/tree_item', 'class' => 'tree')); ?>
						</div>
						<div class="span5 pull-right">
							<?php echo $this->Form->create('Category', array('type' => 'file', 'url' => array('action' => 'add')));?>
							<?php echo $this->Form->hidden('Category.parent_id', array('value' => null)); ?>
							<?php echo $this->Form->hidden('Category.model', array('value' => $model)); ?>
							<fieldset>
							    <?php echo $this->Form->input('Category.name', array('label' => __('New %s Category Name', $model))); ?>
							</fieldset>
						    <fieldset>
						    	<legend class="toggleClick">extras</legend>
								<?php echo $this->Form->input('GalleryImage.filename', array('type' => 'file', 'label' => 'Category thumbnail')); ?>
								<?php echo $this->Form->input('Category.description', array('type' => 'textarea')); ?>
							</fieldset>
							<?php echo $this->Form->end(__d('categories', 'Add'));?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php $i++; endforeach; ?>
	</div>
	<?php else : ?>
	<div class="span7">
		<p class="alert alert-info">No existing categories, add one using the form on the right.</p>
	</div>
	<?php endif; ?>


	<div class="tagProducts span5 pull-right last">
		<?php if (!empty($models)) : ?>
			<?php echo $this->Form->create('Category', array('type' => 'file', 'url' => array('action' => 'add')));?>
			<?php echo $this->Form->hidden('Category.parent_id', array('value' => null)); ?>
			<fieldset class="row-fluid">
				<div class="span6">
			   		<?php echo $this->Form->input('Category.model', array('label' => 'What are you categorizing?')); ?>
			   	</div>
				<div class="span6">
			    	<?php echo $this->Form->input('Category.name', array('label' => 'Category name')); ?>
			    </div>
			</fieldset>
			
		    <fieldset>
		    	<legend class="toggleClick">extras</legend>
				<?php echo $this->Form->input('GalleryImage.filename', array('type' => 'file', 'label' => 'Category thumbnail')); ?>
				<?php echo $this->Form->input('Category.description', array('type' => 'richtext')); ?>
			</fieldset>
			<?php echo $this->Form->end(__d('categories', 'Add'));?>
		<?php else : ?>
			<p class="alert alert-info">All categorizable plugins have been categorized.</p>
		<?php endif; ?>
		<ul class="nav nav-list">
			<li></li>
		</ul>
	</div>

</div>

<div class="categories dashboard">
	<?php $this->Html->script(array('/categories/js/jquery.treeview', '/categories/js/views/categories/tree'), array('inline' => false)); ?>
	<?php $this->Html->css(array('/categories/css/jquery.treeview'), null, array('inline' => false)); ?>
</div>

<script type="text/javascript">
	$(function() {
		$('.tree').treeview();
	})
</script>

<?php
// set the contextual breadcrumb items
$this->set('context_crumbs', array(
	'crumbs' => array(
		$this->Html->link(__('Admin Dashboard'), '/admin'),
		$page_title_for_layout
		)
	));

// set the contextual menu items
$this->set('context_menu', array(
	'menus' => array(
		array(
			'heading' => 'Categories', 
			'items' => array(
				$this->Html->link('Add', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'add', 'model' => $model, 'type' => 'Category'))
				)
			),
		)
	)); ?>
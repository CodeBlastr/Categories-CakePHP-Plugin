<?php
/**
 * Copyright 2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<?php echo $this->Form->create('Category');?>
	<fieldset>
 		<legend><?php echo __d('categories', 'Edit Category');?></legend>
	<?php
		echo $this->Form->input('Category.id');
		echo $this->Form->input('Category.parent_id', array('empty' => true));
		echo $this->Form->input('Category.name', array('after' => $this->Html->link(' Edit Category Images', array('plugin' => 'galleries', 'controller' => 'galleries', 'action' => 'edit', 'Category', $this->request->data['Category']['id'], 'admin' => false))));
		echo $this->Form->input('Category.model');
		echo $this->Form->input('Category.description', array('type' => 'richtext'));
	?>
	</fieldset>
<?php echo $this->Form->end(__d('categories', 'Submit', true));?>

<?php 
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Categories',
		'items' => array(
			$this->Html->link('List', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'index', 'model' => $this->Form->value('Category.model'))),
			)
		),
	))); ?>
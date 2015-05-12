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
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $this->Paginator->sort('parent_id');?></th>
	<th><?php echo $this->Paginator->sort('name');?></th>
	<th><?php echo $this->Paginator->sort('description');?></th>
	<th class="actions"><?php echo __d('categories', 'Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($categories as $category):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $this->Html->link($category['ParentCategory']['name'], array('controller'=> 'categories', 'action'=>'view', $category['ParentCategory']['id'])); ?>
		</td>
		<!--td>
			<?php echo $this->Html->link($category['User']['id'], array('controller'=> 'users', 'action'=>'view', $category['User']['id'])); ?>
		</td-->
		<td>
			<?php echo $category['Category']['name']; ?>
		</td>
		<td>
			<?php echo $this->Text->truncate(strip_tags($category['Category']['description']), 100, array('ending' => '...')); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__d('categories', 'View'), array('action' => 'view', $category['Category']['id']), array('class' => 'btn btn-default')); ?>
			<?php echo $this->Html->link(__d('categories', 'Edit'), array('action' => 'edit', $category['Category']['id']), array('class' => 'btn btn-warning')); ?>
    		<?php echo $this->Html->link(__d('categories', 'Move Up'), array('action' => 'moveup', $category['Category']['name'], 1), array('class' => 'btn btn-info')); ?>
			<?php echo $this->Html->link(__d('categories', 'Delete'), array('action' => 'delete', $category['Category']['id']), array('class' => 'btn btn-danger'), sprintf(__d('categories', 'Are you sure you want to delete # %s?'), $category['Category']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>


<?php echo $this->element('paging');?>


<?php 
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Categories',
		'items' => array(
			$this->Html->link(__d('categories', 'Add'), array('action' => 'add'), array('class' => 'add')),
			$this->Html->link(__d('categories', 'List'), array('action' => 'tree')),
			)
		),
	))); ?>

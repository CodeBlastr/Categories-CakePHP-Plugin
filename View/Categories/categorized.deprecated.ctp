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
<?php echo $this->Form->create('Category', array('url'=>array('controller'=>'categories',
	'action'=>'categorized')));?>
	<?php echo $this->Form->hidden('Model.type', array('value' => $this->request->params['named']['type']));
			echo $this->Form->hidden('Model.Referer',array('value' => $referer));?>
<div style ="float:left; width: 520px;">
	<div style ="float:left; width: 230px;position:absolute">
	<?php 			echo $this->request->params['named']['type'];?>
		<?php echo $this->Form->select($this->request->params['named']['type'].'.id', $models, null, array('multiple' => true, 'div' => false, 'style' => 'height:235px' ));?>
	</div>
	
	<div style ="float:right; width: 230px;">
		<?php echo 'Category'; 
			echo $this->Form->select('id', $categories, null, array('multiple'=>true, 'div'=>false, 'style'=>'height:235px' ));	?>
	</div>
</div>
<?php echo $this->Form->end('Assign Categories');?>




<?php 
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Categories',
		'items' => array(
			$this->Html->link(__d('categories', 'New Category', true), array('action' => 'add')),
			$this->Html->link(__d('categories', 'Category Tree', true), array('action' => 'tree')),
			)
		),
	))); ?>
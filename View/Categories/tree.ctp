<div class="categories tree">
  <h2><?php echo __d('categories', 'Categories');?></h2>
  <?php 
	$this->Html->script(
		array(
			'/categories/js/jquery.treeview',
			'/categories/js/views/categories/admin_tree'
			),
			array('inline' => false,
			)
		);
	$this->Html->css(
		array(
			'/categories/css/jquery.treeview',
			), 
		null, 
		array('inline' => false));
	#echo $this->Html->scriptBlock('App.pagesAdminIndex.init();');
	#debug($categories);
?>
  <div id="categoryMenu"> <?php echo $this->Tree->generate($categories, array('element' => 'categories/tree_item', 'class' => 'categorytree', 'id' => 'categorytree')); ?>
    <?php #echo $this->Element('thread', array('name' => 'CategoryOption/name', 'data' => $categoryOptions)); ?>
  </div>
</div>

<?php 
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Categories',
		'items' => array(
			$this->Html->link('Add', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'add', 'model' => $model, 'type' => 'Category')),
			)
		),
	))); ?>

<script type="text/javascript"> 
	$(function() {
		$('#categorytree').treeview();
	})
</script>
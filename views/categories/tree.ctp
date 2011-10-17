<div class="categories tree">
  <h2><?php __d('categories', 'Categories');?></h2>
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

<div class="actions">
	<ul>
    	<li><?php echo $this->Html->link('Add Category', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'add')); ?></li>
    </ul>
</div>

<script type="text/javascript"> 
	$(function() {
		$('#categorytree').treeview();
	})
</script>
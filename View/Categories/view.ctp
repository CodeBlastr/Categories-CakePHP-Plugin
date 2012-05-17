<<<<<<< HEAD
<?php # @todo Add the behavior dynamically, and show these links if the behavior is loaded for this view.

# echo $this->Favorites->toggleFavorite('favorite', $category['Category']['id']); 
# echo $this->Favorites->toggleFavorite('watch', $category['Category']['id']); ?>

<div id="catalog<?php echo $category['Category']['id']; ?>" class="category view">
  <div id="viewname<?php echo $category['Category']['id']; ?>" class="viewRow name  altrow">
    <div id="viewNamename" class="viewCell name altrow"></div>
    <h2 id="viewContentname" class="viewCell content  altrow"> <?php echo $category['Category']['name']; ?> </h2>
  </div>
  <div id="viewdescription<?php echo $category['Category']['id']; ?>" class="viewRow description ">
    <div id="viewNamedescription" class="viewCell name "></div>
    <div id="viewContentdescription" class="viewCell content "> <?php echo $category['Category']['description']; ?> </div>
  </div>

<?php 
if (!empty($childCategories)) :  ?>
<div class="subCategories">
<h4><?php echo __('Sub Categories'); ?></h4> <?php
	echo $this->Element('scaffolds/index', array(
		'data' => $childCategories,
		'modelName' => 'ChildCategory',
		'pluginName' => 'categories',
		'controller' => 'categories',
		'displayName' => 'name',
		'displayDescription' => '',
		'showGallery' => true,
		'galleryModel' =>  array(
			'name' => 'Category',
			'alias' => 'ChildCategory',
			),
		'galleryForeignKey' => 'id',
		'actions' => false,
		));?>
</div><?php
endif;
?>

<div class="categoriesItems index">
<?php 
if (!empty($category['Associated']) && !empty($category['Associated'][key($category['Associated'])])) { 
	foreach ($category['Associated'] as $model) {
		#echo $this->Element('categories/category_items', array('id' => $this->request->params['pass'][0], 'limit' => 9, 'model' => $model)); 
		echo '<h4 class="categoryItemsLables">' . $category['Category']['name'] . ' Items ' . '</h4>';
		echo $this->Element('categories/category_items', array('id' => $this->request->params['pass'][0], 'limit' => 9, 'categoryItems' => $model));
	} // end associated loop
} else {
	echo '<div class="categoryNoItemsMessage"><p>No individual items in this category.</p></div>';
} // end associated check
?>
</div>
</div>

<?php 
// set the contextual menu items
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Categories',
		'items' => array(
			$this->Html->link('Add', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'add', 'type' => 'Category')),
			$this->Html->link('List', array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'index')),
			)
		),
	))); ?>
=======
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
<h2><?php  echo __d('categories', 'Category');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('categories', 'Parent Category'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($category['ParentCategory']['name'], array('controller'=> 'categories', 'action'=>'view', $category['ParentCategory']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('categories', 'User'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($category['User']['id'], array('controller'=> 'users', 'action'=>'view', $category['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('categories', 'Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $category[$modelName]['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('categories', 'Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $category[$modelName]['description']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('categories', 'Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $category[$modelName]['created']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__d('categories', 'List Categories'), array('action'=>'index')); ?> </li>
	</ul>
</div>
>>>>>>> b22c4a89c583034e3eded72745d2431c46a1e832

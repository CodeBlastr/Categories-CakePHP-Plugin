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
</div>

<?php 
if (!empty($childCategories)) :  
	echo $this->Element('scaffolds/index', array(
		'data' => $childCategories,
		'modelName' => 'ChildCategory',
		'pluginName' => 'categories',
		'controller' => 'categories',
		'displayName' => 'name',
		'displayDescription' => '',
		'actions' => array(),
		));
endif;
?>

<div class="categoriesItems">
<?php 
if (!empty($category['Associated'])) : 
	foreach ($category['Associated'] as $model) : 
		#echo $this->Element('categories/category_items', array('id' => $this->params['pass'][0], 'limit' => 9, 'model' => $model)); 
		echo '<h4 class="categoryItemsLables">' . $category['Category']['name'] . ' Items ' . '</h4>';
		echo $this->Element('categories/category_items', array('id' => $this->params['pass'][0], 'limit' => 9, 'categoryItems' => $model));
	endforeach; 
else :
	echo '<div class="categoryNoItemsMessage"><p>No individual items in this category.</p></div>';
endif;
?>
</div>
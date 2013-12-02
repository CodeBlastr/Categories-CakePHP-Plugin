<?php
$CategoryHelper = $this->Helpers->load('Categories.Category', $___dataForView);

$categories = $CategoryHelper->loadData();
//debug($categories); exit;
print'<div class="category-list">';
 if ( !empty($categories) ) {
 echo '<ul class="unstyled">';
	 foreach ($categories as $category) {
		 echo '<li>' . $this->Html->link($category['Category']['name'], array('plugin' => strtolower(ZuhaInflector::pluginize($category['Category']['model'])),'controller' => Inflector::tableize($category['Category']['model']),'action'=>'index','?' => array('categories' => $category['Category']['name']))) . '</li>';
	 }
	 echo '</ul>';
 }
print '</div>';
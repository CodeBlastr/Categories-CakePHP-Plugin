<?php /*

This is here temporarily, because I'm not sure why it was here in the first place.  IF there is no reason then we should delete this entire area.  RK 7/27/2011


// this should be at the top of every element created with format __ELEMENT_PLUGIN_ELEMENTNAME_instanceNumber.
// it allows a database driven way of configuring elements, and having multiple instances of that configuration.
	if(!empty($instance) && defined('__ELEMENT_CATEGORIES_CATEGORY_ITEMS_'.$instance)) {
		extract(unserialize(constant('__ELEMENT_CATEGORIES_CATEGORY_ITEMS_'.$instance)));
	} else if (defined('__ELEMENT_CATEGORIES_CATEGORY_ITEMS')) {
		extract(unserialize(__ELEMENT_CATEGORIES_CATEGORY_ITEMS));
	}

# these come from the plugin call
$id = !empty($id) ? $id : $this->params['pass'][0]; 
$limit = !empty($limit) ? $limit : 10;
$modelName = !empty($model) ? $model : null;
#$modelSettings[$modelName] = $$modelName;
#$modelSettings[$modelName] = parse_ini_string($modelSettings[$modelName]); 
#$settings = $modelSettings[$modelName];

if (isset($modelSettings) && $modelSettings[$modelName]['display'] === '0') {
# do nothing, because we don't want to display this model data
} else {
	
# setup view vars for reuse 
$modelClass = $modelName; #ex. ContactPerson
$prefix = null;
$plugin = pluginize($modelName);
$controller = Inflector::tableize($modelName); #contact_people
$indexVar = 'categoryItems'; #contactPeople
$humanModel = Inflector::humanize(Inflector::underscore($modelClass)); #Contact Person
$humanCtrl = Inflector::humanize(Inflector::underscore($controller)); #Contact People
$indexData = $this->requestAction('/categories/categories/requestForItems/'.$id.'/'.$limit.'/'.$modelName); 
?>

<?php #foreach($categoryItems as $categoryItem) { ?>
<?php $controller = Inflector::tableize($model); ?>




<div class="<?php echo $indexVar;?> index">
  <h2><?php echo(!empty($settings['pageHeading']) ? $settings['pageHeading'] : $humanCtrl); ?></h2>
  <!--p><?php #echo $this->Paginator->counter(array('format' => 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%')); ?></p-->
  <div class="indexContainer">
    <?php if (!empty($indexData)) : ?>
    <div class="indexRow" id="headingRow">
      <?php if (!empty($settings['showGalleryThumb'])) : ?>
      <div class="indexCell columnHeading">Image</div>
      <?php endif; ?>
      <?php $i = 0; foreach ($settings['fields'] as $_alias): ?>
      <div class="indexCell columnHeading" id="<?php #echo $_modelClass; ?>"><?php #echo $this->Paginator->sort($_alias); ?></div>
      <?php $i++; endforeach;?>
      <?php if(!empty($settings['action'])) { ?>
      <div class="indexCell columnHeading" id="columnActions">
        <?php __('Actions');?>
      </div>
      <?php } ?>
    </div>
    <?php


$i = -1;
foreach ($indexData as $_modelClass) :
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' altrow';
	}
echo "\n";
  echo "\t<div class=\"indexRow {$class}\">\n";
  
	# show the gallery
	if (!empty($settings['showGalleryThumb'])) :
		echo "\t\t<div class=\"indexCell galleryThumb\">\n";
		echo $this->element('thumb', array('plugin' => 'galleries', 'model' => $modelClass, 'foreignKey' => $indexData[$i]['CategoryItem']['id'], 'showDefault' => 'false', 'thumbSize' => 'medium', 'thumbLink' => "{$prefix}/{$plugin}/{$controller}/view/".$indexData[$i]['CategoryItem']['id']));
		echo "</div>\n"; 
	endif; 
	
	# show the display Fields
	foreach ($settings['fields'] as $key => $value) :
		#temporary fix to get the category item linking to the item itself
		if ($key == 'name') : 
			echo "\t\t<div class=\"indexCell\" id=\"{$key}\">\n\t\t\t<a href=\"{$prefix}/{$plugin}/{$controller}/view/".$indexData[$i]['CategoryItem']['id']."\">".strip_tags($indexData[$i]['CategoryItem'][$key])."</a>\n\t\t</div>\n";
		else :
			echo "\t\t<div class=\"indexCell\" id=\"{$key}\">\n\t\t\t".strip_tags($indexData[$i]['CategoryItem'][$key])."\n\t\t</div>\n";
		endif;
	endforeach;

	# show the actions column
	if(!empty($settings['action'])) : foreach ($settings['action'] as $linkText => $url) :
		echo "\t\t<div class=\"indexCell columnActions\">\n";
		echo "\t\t\t" . $this->Html->link(__($this->Html->tag('span', $linkText), true), $url.'/'.$_modelClass['CategoryItem']['id'], array('escape' => false, 'class' => 'button')) . "\n";
	 	#echo "\t\t\t" . $this->Html->link(__('Edit', true), array('action' => 'edit', $_modelClass[$modelClass]['id'])) . "\n";
	 	#echo "\t\t\t" . $this->Html->link(__('Delete', true), array('action' => 'delete', $_modelClass[$modelClass]['id']), null, __('Are you sure you want to delete', true).' #' . $_modelClass[$modelClass]['id']) . "\n";
		echo "\t\t</div>\n";
	endforeach; endif;
  echo "\t</div>\n";
endforeach; else:
echo __('No records found.');
endif;
echo "\n";
?>
  </div>
</div>
<?php echo $this->element('paging');?>
<?php # } // end foreach categoryItems ?>
<?php } // end if $modelSettings[$modelName]['display'] ?>  
<?php */ ?>
<div class="categoryItems" id="elementCategoryItems">
  <div class="indexContainer">
    <div class="indexRow" id="headingRow">
      <div class="indexCell columnHeading"></div>
    </div>
    <?php
$i = 0;
foreach ($categoryItems as $categoryItem):
	$modelName = key($categoryItem);
	$plugin = pluginize($modelName);
	$controller = Inflector::tableize($modelName);
	$description = !empty($categoryItem[$modelName]['summary']) ? $categoryItem[$modelName]['summary'] : $categoryItem[$modelName]['summary']; // temporary until a better way to handle description field naming is devised
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
    <div class="indexRow">
      <div class="indexCell galleryThumb" id="galleryThumb<?php echo $categoryItem[$modelName]['id']; ?>"> <?php echo $this->element('thumb', array('plugin' => 'galleries', 'model' => $modelName, 'foreignKey' => $categoryItem[$modelName]['id'], 'thumbSize' => 'medium', 'thumbLink' => "/{$plugin}/{$controller}/view/".$categoryItem[$modelName]['id']));  ?> </div>
      <div class="indexCell categoryItemName" id="categoryItemName<?php echo $categoryItem[$modelName]["id"]; ?>"> <?php echo $this->Html->link($categoryItem[$modelName]['name'] , array('plugin' => $plugin, 'controller' => $controller , 'action'=>'view' , $categoryItem[$modelName]["id"])); ?> </div>
      <div class="indexCell categoryItemDescription" id="categoryItemDescription<?php echo $categoryItem[$modelName]['id']; ?>"> <?php echo $text->truncate(strip_tags($description), 50, array('ending' => '...', 'html' => true)); ?> </div>
      
      <?php
	  # this is the only non-abstract item here, we leave it because we haven't thought of a better way to do it yet.
	  # @todo This may mess up the price by user role functionality, so it may need to be fixed.
	  if(!empty($categoryItem[$modelName]['price'])) : ?>  
      <div class="indexCell categoryItemPrice" id="categoryItemPrice<?php echo $categoryItem[$modelName]["id"]; ?>">
        <?php echo '$'.$categoryItem[$modelName]['price']; ?> 
      </div>
      <?php
	  endif;
	  ?>
      
      <div class="indexCell categoryItemAction" id="categoryItemAction<?php echo $categoryItem[$modelName]["id"]; ?>"> <?php echo $this->Html->link(__($this->Html->tag('span', 'view'), true), array('plugin' => $plugin, 'controller' => $controller, 'action' => 'view', $categoryItem[$modelName]['id']), array('escape' => false, 'class' => 'button')); ?> </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php echo $this->element('paging');?>
</div>

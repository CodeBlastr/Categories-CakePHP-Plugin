<?php
if (!empty($data['CategoryOption'][0])) { 
	# these are attribute groups (Shirt > Sizes)
	$i=0;
	foreach ($data['CategoryOption'] as $option) {
		if (!empty($data['CategoryOption'][$i])) {
		  	echo $this->Tree->generate(array(array('CategoryOption' => $data['CategoryOption'][$i])), array('element' => 'category_options/tree_item', 'class' => 'categorytree', 'id' => 'categorytree'));
			$i++;
		}
	}
    # Echo $this->Element('category_options/tree_item', array('data' => $data)); 
} else if (!empty($data['CategoryOption']['name'])) { ?>

	<span class="catLink" id="category<?php echo $data['CategoryOption']['id']; ?>"><?php echo  $data['CategoryOption']['name']; ?></span> 


<?php
} else {  ?>

	<span class="catLink" id="category<?php echo $data['Category']['id']; ?>"><?php echo  $data['Category']['name']; ?></span> 

	<span class="viewCat"><?php echo $this->Html->link('<img src="/categories/img/contextmenu/page_white_go.png" />', array('action' => 'view', $data['Category']['id']), array('escape' => false, 'title' => 'View '.$data['Category']['name'].' category')); ?></span> 

	<span class="addCat separator"><?php echo $this->Html->link('<img src="/categories/img/contextmenu/page_white_add.png" />', array('action' => 'add', 'parent' => $data['Category']['id']), array('escape' => false, 'title' => 'Add child to '.$data['Category']['name'].' category')); ?></span> 

	<span class="editCat"><?php echo $this->Html->link('<img src="/categories/img/contextmenu/page_white_edit.png" />', array('action' => 'edit', $data['Category']['id']), array('escape' => false, 'title' => 'Edit '.$data['Category']['name'].' category')); ?></span> 

	<span class="deleteCat separator"><?php echo $this->Html->link('<img src="/categories/img/contextmenu/page_white_delete.png" />', array('action' => 'delete', $data['Category']['id']), array('escape' => false, 'title' => 'Delete '.$data['Category']['name'].' category')); ?></span>

	<?php if ($model == 'Catalog') { ?>
	<span class="addCat separator"><?php echo $this->Html->link('<img src="/categories/img/contextmenu/page_white_add.png" />', array('action' => 'add', 'model' => $model, 'type' => 'Attribute Group', 'parent' => $data['Category']['id']), array('escape' => false, 'title' => 'Add attribute group to '.$data['Category']['name'].' category')); ?></span> 
    
	<?php
	}
} // end category option check ?>
<?php if ($data['CategoryOption']['type'] == 'Attribute Group') { ?>
	
	<span class="catLink" id="category<?php echo $data['CategoryOption']['id']; ?>"><?php echo !empty($data['Category']['name']) ? $data['Category']['name'] . ' ' . $data['CategoryOption']['name'] : $data['CategoryOption']['name']; ?></span> 
    
	<span class="addCat separator"><?php echo $this->Html->link('<img src="/categories/img/contextmenu/page_white_add.png" />', array('controller' => 'categories', 'action' => 'add', 'model' => 'Catalog', 'type' => 'Attribute Type', 'parent' => $data['CategoryOption']['id']), array('escape' => false, 'title' => 'Add attribute to'.$data['CategoryOption']['name'].' category')); ?></span> 
    
<?php } else { // attribute types ?>

	<span class="catLink" id="category<?php echo $data['CategoryOption']['id']; ?>"><?php echo $data['CategoryOption']['name']; ?></span> 
    
<?php } ?>

<span class="editCat"><?php echo $this->Html->link('<img src="/categories/img/contextmenu/page_white_edit.png" />', array('action' => 'edit', $data['CategoryOption']['id']), array('escape' => false, 'title' => 'Edit '.$data['CategoryOption']['name'].' category')); ?></span> 


<span class="deleteCat separator"><?php echo $this->Html->link('<img src="/categories/img/contextmenu/page_white_delete.png" />', array('action' => 'delete', $data['CategoryOption']['id']), array('escape' => false, 'title' => 'Delete '.$data['CategoryOption']['name'])); ?></span>

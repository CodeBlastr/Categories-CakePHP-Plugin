<span class="catLink" id="category<?php echo $data['Category']['id']; ?>"><?php echo  $data['Category']['name']; ?></span> 

<span class="viewCat"><?php echo $this->Html->link('<img src="/categories/img/contextmenu/page_white_go.png" />', array('action' => 'view', $data['Category']['id']), array('escape' => false, 'title' => 'View '.$data['Category']['name'].' category')); ?></span> 

<span class="addCat separator"><?php echo $this->Html->link('<img src="/categories/img/contextmenu/page_white_add.png" />', array('action' => 'add', 'parent' => $data['Category']['id']), array('escape' => false, 'title' => 'Add child to '.$data['Category']['name'].' category')); ?></span> 

<span class="editCat"><?php echo $this->Html->link('<img src="/categories/img/contextmenu/page_white_edit.png" />', array('action' => 'edit', $data['Category']['id']), array('escape' => false, 'title' => 'Edit '.$data['Category']['name'].' category')); ?></span> 

<span class="deleteCat separator"><?php echo $this->Html->link('<img src="/categories/img/contextmenu/page_white_delete.png" />', array('action' => 'delete', $data['Category']['id']), array('escape' => false, 'title' => 'Delete '.$data['Category']['name'].' category')); ?></span>

<?php if ($model == 'Catalog') { ?>
<span class="addCat separator"><?php echo $this->Html->link('<img src="/categories/img/contextmenu/page_white_add.png" />', array('action' => 'add', 'model' => $model, 'type' => 'Attribute Group', 'parent' => $data['Category']['id']), array('escape' => false, 'title' => 'Add attribute group to '.$data['Category']['name'].' category')); ?></span> 
<?php } ?>
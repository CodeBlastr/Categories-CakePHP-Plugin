<?php echo $this->Form->create('CategoryOption');?>
	<fieldset>
 		<legend><?php __d('categories', 'Edit Attribute');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('category_id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__d('categories', 'Submit', true));?>
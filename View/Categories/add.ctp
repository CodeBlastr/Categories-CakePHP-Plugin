<div class="categories form">
<h2><?php __('Add a New Category'); ?></h2>
<?php echo $this->Form->create('Category', array('type' => 'file'));?>

	<div id ="ajax"></div>
<fieldset>
   <?php 
	echo $this->Form->input('Category.model', array('label' => 'What are you categorizing?'));
	echo $this->Form->input('Category.type', array('label' => 'Advanced catalog options', 'options'=>$types));
	echo $this->Form->input('Category.parent_id', array('label' => 'Is this a sub category?', 'empty' => true, ));
   ?>
</fieldset>
<fieldset>
   <?php 
	echo $this->Form->input('name', array('label' => 'Display Name'));
	echo $this->Form->input('GalleryImage.filename', array('type' => 'file', 'label' => 'Category thumbnail'));
	echo $this->Form->input('GalleryImage.dir', array('type' => 'hidden'));
	echo $this->Form->input('GalleryImage.mimetype', array('type' => 'hidden'));
	echo $this->Form->input('GalleryImage.filesize', array('type' => 'hidden'));
	echo $this->Form->input('description', array('type' => 'richtext'));
   ?>
<?php echo $this->Form->submit(__d('categories', 'Submit', true));?>
</fieldset>

</div>

<script type="text/javascript">
<?php if (empty($this->data['Category']['parent_id'])) : ?>
call_select();
<?php endif; ?>

$('#CategoryType').live("change", function(e){
	call_select();
});
$('#CategoryModel').live("change", function(e){
	call_select();
});


function call_select() {
	$('#ajax').empty().html('<?php echo $this->Html->image('ajax-loader.gif'); ?>');
	type =  $('#CategoryType').val();
	model =  $('#CategoryModel').val();
	
	    $.ajax({
	        type: "POST",
            url: "<?php echo $this->Html->url('/categories/categories/get_all/');?>"+type+"/"+model,
            success:function(data){
                create_select(data, type);
            }
        });
	if (model == 'Catalog') {
		$('#CategoryType').parent().show();
	} else {
		$('#CategoryType').parent().hide();
	}
}

function create_select(data, type) {
	$('#ajax').empty();
	if (data.length > 2 || type == 'Category') {
		var response = JSON.parse(data);
		var res = '';
		if (type == 'Category')
			res += '<option value=""></option>'; // empty for base category
		for (i in response) {
	    	res += '<option value="' + i + '">' + response[i] + '</option>';
		}
    	$('#CategoryParentId').html(res);
	}
	else {
		var res = 'No parent available';
		$('#ajax').html(res);
    	$('#CategoryParentId').html(null);
	}
}
</script>

<div class="categories form">
<h2><?php echo !empty($this->request->data['Category']['type']) ? __('Add a New %s <span id="parentLabel"></span>', $this->request->data['Category']['type']) : __('Add a New Category'); ?></h2>
<?php echo $this->Form->create('Category', array('type' => 'file'));?>

	<div id ="ajax"></div>
<fieldset>
   	<?php
	if (!empty($this->request->data['Category']['model'])) {
		echo $this->Form->hidden('Category.model');
	} else {
		echo $this->Form->input('Category.model', array('label' => 'What are you categorizing?'));
    }
    if (!empty($this->request->data['Category']['type'])) {
		echo $this->Form->hidden('Category.type');
    } else {
	   echo $this->Form->input('Category.type', array('label' => 'Advanced catalog options', 'options' => $types));
    }
    if (!empty($this->request->data['Category']['parent_id']) && !empty($this->request->data['Category']['type'])) {
		echo $this->Form->hidden('Category.parent_id');
    } else {
		echo $this->request->data['Category']['type'] == 'Attribute Type' ? $this->Form->input('Category.parent_id', array('label' => 'What is the parent attribute group?', 'empty' => true)) : $this->Form->input('Category.parent_id', array('label' => 'What is the parent category?', 'empty' => true));
    }
	echo !empty($this->request->data['Category']['type']) ? $this->Form->input('name', array('label' => $this->request->data['Category']['type'] . ' Name')) : $this->Form->input('name', array('label' => 'Name'));
	echo $this->Form->input('GalleryImage.filename', array('type' => 'file', 'label' => 'Category thumbnail'));
	echo $this->Form->input('GalleryImage.dir', array('type' => 'hidden'));
	echo $this->Form->input('GalleryImage.mimetype', array('type' => 'hidden'));
	echo $this->Form->input('GalleryImage.filesize', array('type' => 'hidden'));
	echo $this->Form->input('description', array('type' => 'richtext'));
	echo $this->Form->submit(__d('categories', 'Submit', true));?>
</fieldset>

</div>

<script type="text/javascript">
<?php
if (!empty($this->request->data['Category']['parent_id'])) {
	echo 'var originalParent = '.$this->request->data['Category']['parent_id'].';';
} else {
	echo 'var originalParent = null;';
} ?>

call_select();

$('#CategoryType').change(function(e){
	call_select();
});
$('#CategoryModel').change(function(e){
	call_select();
});


function call_select() {
	var model =  $('#CategoryModel').val();
	if (model == 'Catalog') {
		$('#CategoryType').parent().show();
	} else {
		$('#CategoryType').parent().hide();
		$('#CategoryType').val('Category');
	}
	$('#ajax').empty().html('<?php echo $this->Html->image('ajax-loader.gif'); ?>');
	var type =  $('#CategoryType').val();
	    $.ajax({
	        type: "POST",
            url: "<?php echo $this->Html->url('/categories/categories/get_all/');?>"+type+"/"+model,
            success:function(data){
                create_select(data, type);
            }
        });
}

function create_select(data, type) {
	$('#ajax').empty();
	if (data.length > 2 || type == 'Category') {
		var response = JSON.parse(data);
		var res = '';
		if (type == 'Category')
			res += '<option value=""></option>'; // empty for base category
		for (i in response) {
			if (i == originalParent) {
		    	res += '<option value="' + i + '" selected = "selected">' + response[i] + '</option>';
				$("#parentLabel").html("for " + response[i]);
			} else {
		    	res += '<option value="' + i + '">' + response[i] + '</option>';
			}				
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

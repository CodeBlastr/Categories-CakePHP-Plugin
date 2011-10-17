<?php 
# setup element defaults
$parent = !empty($parent) ? $parent : Inflector::classify($this->params['controller']);
$index = isset($this->data['Category']) ? count($this->data['Category']) : 0;
?>

<div id ="wrap-content">
	<div class="categoryChooseContent">
    	<?php
		$parentId = isset($parentId) ? $parentId : null;
		echo $this->Form->select($parent.'.id', $parents, $parentId, array('multiple' => true));
		?>
	</div>
	<div id="cat4"></div>
	<div id="selected"></div>
</div>


<script type="text/javascript">
	<?php 	
	$str = "/parent:{$parent}";
	if (isset($parentId)) :
		$str .=   "/parentId:{$parentId}";
	endif;
	
	// assuming catalog id will be always tehere isset($catalogIdUrl)) {
	if (true) :
	?>
	window.addEventListener('load', cate, false);
	
	function cate() {
	    $.ajax({
	        type: "POST",
	        url: "<?php echo $this->Html->url('/categories/categories/get_children');?>" + "<?php echo $str?>",
	        success:function(data){
	            create_select(data, 1);
	        }
	    });
	}
	<?php
	endif;
	?>

	$('#<?php echo $parent?>Id').change(function(e){
		$('#selected').empty().html('<?php echo $this->Html->image('ajax-loader.gif'); ?>');
	    $this = $(e.target);
	    $.ajax({
            type: "POST",
            url: "<?php echo $this->Html->url('/categories/categories/get_children');?>" + '/parent:<?php echo $parent?>/parentId:' + $('#<?php echo $parent?>Id').val(),
            success:function(data){
                create_select(data, 1);
            }
        });
	});
	$('.category').live("change", function(e){
		$('#selected').empty().html('<?php echo $this->Html->image('ajax-loader.gif'); ?>');
		id = $(this).attr('id');
	    $.ajax({
            type: "POST",
            url: "<?php echo $this->Html->url('/categories/categories/get_children//parentId:');?>" + $('#'+id).val(),
            success:function(data){
                create_select(data, id);
            }
        });
	});

	function create_select(data, num) {
		num = parseFloat(num) + 1;
		if (data.length > 2) {
			var response = JSON.parse(data);
			var res = '<div id="wrap_' + num + '"><select class="category" id="' + num + '" multiple="multiple" name="data[Category][<?php echo $index;?>]">';
			for (i in response) {
		    	res += '<option value="' + i + '">' + response[i] + '</option>';
			}
			res +='</select></div><div id="selected"></div>';
		}
		else {
			$("#assign").removeAttr("disabled");
		}
		for (i = num	; ;++i) {
			if (($('#'+i).length == 0)) 
				break;
			$('#wrap_'+i).remove();
		}
		$('#selected').remove();
		$('#wrap-content').append(res);
	}
</script>

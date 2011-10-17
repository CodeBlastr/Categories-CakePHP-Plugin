<?php 
if (!empty($children))
foreach ($children as $key => $value) {?>
	<option value = "<?php echo $key;?>"><?php echo $value?></option>
<?php }?>
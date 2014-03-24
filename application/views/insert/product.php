<br />

<?php 

// Echo any errors
echo validation_errors('<p class="error">');

$post_location = isset($post_location) ? $post_location : 'products/insert';
echo form_open($post_location);

// Check if drop down boxes need to be disabled
$disabled = isset($disabled) ? NULL : 'disabled="disabled"';

?>

<fieldset>
	<legend>Product Details</legend>

	<label for="name">Stock: </label>
	<input type="text" id="stock" name="stock" maxlength="200" 
		value="<?php if(isset($form_values['stock'])) echo $form_values['stock']; ?>" />

	<hr>

	<label for="supplier">Supplier: </label>

	<?php

	$selected = isset($form_values['supplier']) ? $form_values['supplier'] : NULL;

	echo form_dropdown('supplier', $supplier, $selected, 
		'id="supplier" onchange="Change_type(this.value)"');

	?>

	<hr>

	<label for="type">Type: </label>
	<?php 

	$js = 'id="type" onchange="Change_subtype(this.value)" ' . $disabled;

	$selected = isset($form_values['type']) ? $form_values['type'] : NULL;

	echo form_dropdown('type', $type, $selected, $js);

	?>

	<hr>

	<label for="subtype">Sub-Type: </label>
	<?php 

	$js = 'id="subtype" ' . $disabled;

	// TODO: Set ID to be the array key
	$selected = isset($form_values['subtype']) ? $form_values['subtype'] : NULL;

	echo form_dropdown('subtype', $subtype, $selected, $js);

	?>

	<hr>

	<input type="submit" name="submit" id="submit" value="Submit" />

</fieldset>

<script type="text/javascript">
	// Load JSON Objects
	
	type = <?php echo $json_type; ?>;
	subtype = <?php echo $json_subtype; ?>

</script>


<script type="text/javascript" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/4dm1n/static/js/product.js"></script>

<?php echo form_close(); ?>
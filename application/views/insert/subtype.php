<br />

<?php 

// Echo any errors
echo validation_errors('<p class="error">');

$post_location = isset($post_location) ? $post_location : 'subtypes/insert';
echo form_open($post_location);

// Check if drop down boxes need to be disabled
$disabled = isset($disabled) ? NULL : 'disabled="disabled"';

?>

<fieldset>
	<legend>Subtype Details</legend>

	<label for="name">Name: </label>
	<input type="text" id="name" name="name" maxlength="200" 
		value="<?php if(isset($form_values['name'])) echo $form_values['name']; ?>" />

	<hr>

	<label for="supplier">Supplier: </label>
	<?php 

	$selected = isset($form_values['supplier']) ? $form_values['supplier'] : NULL;

	echo form_dropdown('supplier', $supplier, $selected, 
		'id="supplier" onchange="Change_type(this.value)"')

	?>	

	<hr>

	<label for="type">Type: </label>
	<?php 

	$js = 'id="type" ' . $disabled;

	$selected = isset($form_values['type']) ? $form_values['type'] : NULL;

	echo form_dropdown('type', $type, $selected, $js);

	?>

	<hr>

	<input type="submit" name="submit" id="submit" value="Submit" />
	<script type="text/javascript">

	// Load JSON objects
	type = <?php echo $json_type; ?>;

	</script>

	<script type="text/javascript" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/4dm1n/static/js/subtype.js"></script>
</fieldset>

<?php echo form_close(); ?>
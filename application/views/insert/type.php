<br />

<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 3/15/13
 * Time: 8:53 PM
 * Licenced under the GPL v3
 */

echo validation_errors('<p class="error">');

$post_location = isset($post_location) ? $post_location : 'types/insert';
echo form_open($post_location);

?>

<fieldset>
	<legend>Type Details</legend>

	<label for="name">Name: </label>
	<input type="text" maxlength="100" id="name" name="name"
	       value="<?php if(isset($form_values['name'])) echo $form_values['name']; ?>" />

	<hr>

	<label for="supplier">Supplier: </label>
	<?php

	$selected = isset($form_values['supplier']) ? $form_values['supplier'] : NULL;

	echo form_dropdown('supplier', $supplier, $selected);

	?>

	<hr>
	<input type="submit" value="Submit" name="submit" id="submit" />

</fieldset>

<?php echo form_close(); ?>
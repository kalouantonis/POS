<br />

<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 3/13/13
 * Time: 7:00 PM
 * Licenced under the GPL v3
 */

// Echo any errors
echo validation_errors('<p class="error">');

$post_location = isset($post_location) ? $post_location : 'suppliers/insert';
echo form_open($post_location);

?>

<fieldset>
	<legend>Supplier Details</legend>

	<label for="name">Name: </label>
	<input type="text" name="name" id="name"
	       value="<?php if(isset($form_values['name'])) echo $form_values['name']; ?>" maxlength="100" />

	<hr>

	<label for="tel">Phone Number: </label>
	<input type="text" name="tel" id="tel"
	       value="<?php if(isset($form_values['tel'])) echo $form_values['tel']; ?>" maxlength="100" />

	<hr>

	<label for="email">Email: </label>
	<input type="text" name="email" id="email"
	       value="<?php if(isset($form_values['email'])) echo $form_values['email']; ?>" maxlength="150" />

	<hr>

	<label for="purchase_area">Purchase Area: </label>
	<input type="text" name="purchase_area" id="purchase_area"
	       value="<?php if(isset($form_values['purchase_area'])) echo $form_values['purchase_area']; ?>" maxlength="200" />

	<hr>
	<input type="submit" name="submit" value="Submit" />
</fieldset>

<?php echo form_close(); ?>


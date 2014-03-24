<?php 

echo validation_errors('<p class="error">');

echo form_open('home/change_password');

?>

<fieldset>
	<legend>Change password</legend>

	<label for="password">Password: </label>
	<input type="password" name="password" maxlength="70">

	<hr>

	<label for="confirm">Re-enter Password: </label>
	<input type="password" name="confirm" maxlength="70">

	<hr>

	<input type="submit" name="submit" value="Submit">

</fieldset>

<?php echo form_close(); ?>
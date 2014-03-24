<br />

<?php 

// Print any errors
echo validation_errors('<p class="error">');

if(isset($message)) 
	echo '<div class="message">' . $message . '</div><br>';


echo form_open('search/index');

?>

<fieldset>
	<legend>Enter Search Terms:</legend>
	<label for="term">Search Term:</label>
	<input type="text" name="term" />

	<hr>

	<label for="search_type">Search By:</label>
	<select name="search_type">
		<!--<option value="ref">References</option>-->
		<option value="supplier">Suppliers</option>
		<option value="product">Products</option>
		<option value="type">Types</option>
		<option value="subtype">Subtypes</option> 
	</select>

	<hr>

	<input type="submit" name="submit" value="Search" />
</fieldset>

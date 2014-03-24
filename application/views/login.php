<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/4dm1n/static/css/style.css">
</head>
<body>

<div id="floater"></div>
<div id="centered">

<div id="login">

	<h1>Login</h1>

	<?php echo validation_errors('<p class="error">'); ?>
	<?php echo isset($error)? '<p class="error">'. $error . '</p>': NULL ?>
	<?php echo isset($message) ? '<p class="message">' . $message. '</p>' : NULL ?>
	
	<fieldset>
		<legend>Login Data</legend>
		<?php 

		echo form_open('home/login');

		?>

		<label for="username">Username: </label>

		<?php
		echo form_input(array('name' => 'username', 
			'id' => 'username', 
			'maxlength' => 100)
		);


		?>

		<br><br />

		<label for="password">Password: </label>
		<?php
		echo form_password(array('name' => 'password',
			'id' => 'password',
			'maxlength' => 100)
		);

		?>

		<hr>

		<?php echo form_submit('submit', 'Login'); ?>

		<?php echo form_close(); ?>

	</fieldset>

</div>
</div>

<div id="bottom">
	Programmed and Designed by Antonis Kalou	
</div>

</body>
</html>
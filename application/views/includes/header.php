<?php

if(!isset($page_title))
	$page_title = 'The POS';

$host_addr = $_SERVER['HTTP_HOST'];

?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo $page_title; ?></title>
	<link rel="stylesheet" type="text/css"  media="all"
		href="http://<?php echo $host_addr; ?>/4dm1n/static/css/style.css">
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js">
	</script>

</head>
<body onload="<?php echo isset($onload) ? $onload : NULL; // Used for scipts ?>">


	<div id="floater"></div>
	<div id="centered">

	<div id="side">
		<div id="logo"><strong><span>Silk</span> Route LTD</strong></div>
		<ul id="nav">
			<li><a href="<?php echo base_url(); ?>">Home</a></li>
		    <li><a href="<?php echo base_url(); ?>suppliers">Suppliers</a></li>
		    <li><a href="<?php echo base_url(); ?>products">Products</a></li>
		    <li><a href="<?php echo base_url(); ?>types">Types</a></li>
		    <li><a href="<?php echo base_url(); ?>subtypes">Subtypes</a></li>
			<li><a href="<?php echo base_url(); ?>search">Search</a></li>
		</ul>

	</div>
	<div id="content">


		<h1><?php echo $page_title; ?></h1>

		<div id="metanav">
			<?php

			// This data should always be set, if not,
			// then this will never be displayed
			echo $this->session->userdata('first_name');

			echo '<br />';

			echo anchor('home/logout', 'Logout');

			?>
		</div>

		<?php if(isset($error_msg)): ?>
		<div class="error">
			<?php echo $error_msg; ?>
		</div>
		<?php endif; ?>




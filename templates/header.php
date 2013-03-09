<?php
session_start();

error_reporting(E_ALL);

if($_SERVER['HTTP_HOST'] == 'localhost') {
    // Set the server path
    $SERVER_PATH = $_SERVER['HTTP_HOST'].'/POS';

    // Set time zone
    date_default_timezone_set('Asia/Nicosia');
}
else {
    // Actual Server
    $SERVER_PATH = $_SERVER['HTTP_HOST'];
}

if(!isset($page_title)) {
    $page_title = 'The POS'; // Change this according to site
}
if(!isset($style_location)) {
	$style_location = "static/css/style.css";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title><?=$page_title ?></title>
    <link rel="stylesheet" type="text/css" href="<?= $style_location?>">
</head>
<body>

<div class="page">
    <h1><?=$page_title ?></h1>
    <?php
    if(isset($_SESSION['first_name'])) {
            ?>
            <div class="metanav">
                 <?php
                    echo $_SESSION['first_name'] . '<br />';
                    echo '<a href="/POS/logout.php">Log out</a><br>';
                ?>
            </div>
    <?php } else { ?>
        <div class="metanav">
            <?php echo "Not logged in"; ?>
        </div>
    <?php
    }
    ?>


<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 3/5/13
 * Time: 4:30 PM
 * Licenced under the GPL v3
 */

$page_title = "Info";
$style_location = "../static/css/style.css";
include_once "../templates/header.php";

$db_location = "../db_model.php";
require "type_model.php";
require "../subtypes/subtype_model.php";

$id = isset($_GET['id']) ? $_GET['id'] : die('No ID given'); // Kill script if no ID is supplied

$type_table = new TypeTable();
$subtype_table = new SubTypeTable();

echo "<h3>Product Type: </h3>" . $type_table->getDataFromID($id)['name'];

$subtype_data = $subtype_table->custQuery("SELECT * FROM subtypes WHERE type_id={$id}");

echo var_dump($subtype_data);

if(!$subtype_data)
	echo '<h3>Product Subtype: </h3> Doesnt exist. <a href="/subtypes/insert.php">Add one!</a>';
else
	echo "<h3>Related Subtype(s): </h3>" . $subtype_data['name'];

include_once "../templates/footer.php";
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
require "type_models.php";

$id = $_GET['id'];

$type_table = new TypeTable();
$subtype_table = new SubTypeTable();

echo "<h3>Product Type: </h3>" . $type_table->getDataFromID($id)['name'];

$subtype_name = $subtype_table->getDataFromID($id)['name'];
if(!$subtype_name)
	echo '<h3>Product Subtype: </h3> Doesnt exist. <a href="/types/insert_sub.php">Add one!</a>';
else
	echo "<h3>Product Subtype: </h3>" . $subtype_table->getDataFromID($id)['name'];

include_once "../templates/footer.php";
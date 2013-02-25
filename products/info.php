<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 2/22/13
 * Time: 10:37 PM
 * Licenced under the GPL v3
 */

$style_location = '../static/css/style.css';
include_once "../templates/header.php";

$db_location = '../db_model.php';
require "pro_model.php";
require "../types/type_models.php";
$id = $_GET['id'];

// Testing
#$id = 6;

$pro_table = new ProductTable();
$pro_data = $pro_table->getDataFromID($id);

$subtype_table = new SubTypeTable();
$subtype_data = $subtype_table->getDataFromID($pro_data['subtype_id']);

$type_table = new TypeTable();
$type_data = $type_table->getDataFromID($subtype_data['type_id']);



echo '<h3>Product Type: </h3>' . $type_data['name'] . '<br>';
echo '<h3>Product Sub-Type: </h3>' .  $subtype_data['name'] . '<br>';
echo '<h3>Stock Level: </h3>' . $pro_data['stock'] . '<br>';

$supplier_data = $pro_table->custQuery("SELECT * FROM suppliers WHERE id={$pro_data['supplier_id']}");
echo '<h3>From Supplier: </h3>' . '<a href="/POS/suppliers/info.php?id=' . $pro_data['supplier_id'] . '">'
	. $supplier_data['name'] . '</a><br>';

echo '<h3>Date/Time Entered: </h3>' . $pro_data['date_entered'];

$user_data = $pro_table->custQuery("SELECT * FROM users WHERE id={$pro_data['user_id']}");
echo '<h3>Entered By: </h3>' . $user_data['first_name'];

include_once "../templates/footer.php";

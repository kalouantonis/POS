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

$id = $_GET['id'];

#$id = 8;

$db_location = '../db_model.php';
require "sup_model.php";

$sup_table = new SupplierTable();
$data = $sup_table->getDataFromID($id);

echo '<h3>Supplier Name: </h3>' . $data['name'] . '<br>';
echo '<h3>Supplier Telephone Number: </h3>' . $data['tel'] . '<br>';
echo '<h3>Supplier Email: </h3>' . $data['email'] . '<br>';
echo '<h3>Purchase Area: </h3>' . $data['purchase_area'] . '<br>';
echo '<h3>Date/Time Last Changed: </h3>' . $data['date_changed'];

$user_data = $sup_table->custQuery("SELECT * FROM users WHERE id={$data['user_id']}");
echo '<h3>Entered By: </h3>' . $user_data['first_name'];

echo '<h3><a href="/POS/suppliers/edit.php?id=' . $data['id'] . '">Edit</a></h3>';

include_once "../templates/footer.php";


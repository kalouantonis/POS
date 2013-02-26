<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 2/23/13
 * Time: 4:31 PM
 * Licenced under the GPL v3
 */

$id = $_GET['id'];

$db_location = '../db_model.php';
require "pro_model.php";

$pro_table = new ProductTable();

$pro_data = $pro_table->getDataFromID($id);

$pro_table->delete($id, $pro_data['deleted']);

header("Location: http://{$_SERVER['HTTP_HOST']}/POS/products.php");
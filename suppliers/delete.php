<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 2/22/13
 * Time: 12:35 PM
 * Licenced under the GPL v3
 */

$id = $_GET['id'];

$db_location = '../db_model.php';
require "sup_model.php";

$sup_table = new SupplierTable();

$sup_table->delete($id);

header("Location: http://{$_SERVER['HTTP_HOST']}/POS/suppliers.php");
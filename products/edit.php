<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 2/22/13
 * Time: 12:04 PM
 * Licenced under the GPL v3
 */

$style_location = '../static/css/style.css';
$page_title = 'Edit Record';
include_once "../templates/header.php";


require_once "../templates/form.php";

$id = $_GET['id'];

$db_location = '../db_model.php';
require "pro_model.php";
$pro_table = new ProductTable();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$pro_table->update($id, $_POST['name'], $_POST['cost'], $_POST['stock'], $_POST['supplier']);
	header("Location: http://{$_SERVER['HTTP_HOST']}/POS/suppliers.php");
}   else {

	$pro_data = $pro_table->getDataFromID($id);

	// Create input form
	$pro_name = new InputField('text', 'name', 'Product Name', $pro_data['name'], null, 100);
	$pro_cost = new InputField('text', 'cost', 'Product Cost', $pro_data['cost'], null, 50);
	$pro_stock = new InputField('text', 'stock', 'Stock Level', $pro_data['stock'], null, 100);

	// Query the suppliers table to make drop down box
	$db_location = '../db_model.php';
	require "../suppliers/sup_model.php";
	$sup_db = new SupplierTable();

	$sup_data = $sup_db->getAllSupplierData();
	for($i=0; $i < count($sup_data); $i++) {
		$options[$sup_data[$i]['id']] = $sup_data[$i]['name'];
	}

	$pro_supplier = new Select('supplier', $options);
	$pro_submit = new InputField('submit', 'submit', null, 'Insert', false);

	// Create the form
	$pro_form = new Form(array($pro_name, $pro_cost, $pro_stock , $pro_supplier, $pro_submit),
		"http://{$_SERVER['HTTP_HOST']}/POS/products/insert.php");

	echo $pro_form->render();
}


include_once "../templates/footer.php";

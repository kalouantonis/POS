<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 2/21/13
 * Time: 5:58 PM
 * Licenced under the GPL v3
 */

$style_location = "../static/css/style.css";
include_once "../templates/header.php";

// Authenticate users

// Debugging
#$_SERVER['REQUEST_METHOD'] = 'POST';
#$_SESSION['uid'] = 1;
#$_POST['name'] = 'test';
#$_POST['cost'] = '32132131';



if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$db_location = '../db_model.php';
	require "pro_model.php";

	$pro_db = new ProductTable();

	$pro_db->insert($_POST['subtype'], $_POST['stock'], $_POST['supplier'], $_SESSION['uid']);

	header("Location: http://{$_SERVER['HTTP_HOST']}/POS/products.php");
}   else {
	require_once "../templates/form.php";


	// Query the suppliers table to make drop down box
	$db_location = '../db_model.php';
	require "../suppliers/sup_model.php";
	require "../types/type_models.php";

	$sup_db = new SupplierTable();
	$type_db = new TypeTable();
	$subtype_db = new SubTypeTable();

	$sup_data = $sup_db->getAllSupplierData();
	$type_data = $type_db->getAllTypeData();

	// TODO: Need to add relationship between type selection and sub type selection
	$subtype_data = $subtype_db->getAllSubTypeData();

	for($i=0; $i < count($sup_data); $i++)
		$sup_option[$sup_data[$i]['id']] = $sup_data[$i]['name'];


	for($i=0; $i < count($type_data); $i++)
		$type_option[$type_data[$i]['id']] = $type_data[$i]['name'];

	for($i=0; $i < count($subtype_data); $i++)
		$subtype_option[$subtype_data[$i]['id']] = $subtype_data[$i]['name'];

	$pro_supplier = new Select('supplier', $sup_option, 'Select Supplier: ');
	$pro_type = new Select('type', $type_option, 'Select Product Type: ');
	$pro_subtype = new Select('subtype', $subtype_option, 'Select Subtype: ');

	// Create input form
	$pro_stock = new InputField('text', 'stock', 'Stock Level', null, null, 100);

	$pro_submit = new InputField('submit', 'submit', null, 'Insert', false);

	// Create the form
	$pro_form = new Form(array($pro_type, $pro_subtype, $pro_stock , $pro_supplier, $pro_submit),
		"http://{$_SERVER['HTTP_HOST']}/POS/products/insert.php");

	echo $pro_form->render();
}


include_once "../templates/footer.php";
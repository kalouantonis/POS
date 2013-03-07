<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 2/21/13
 * Time: 5:58 PM
 * Licenced under the GPL v3
 */

// FIXME: THE WHOLE SCRIPT!!!!!!!

$style_location = "../static/css/style.css";
include_once "../templates/header.php";

// Authenticate users

// Debugging
#$_SERVER['REQUEST_METHOD'] = 'POST';
#$_SESSION['uid'] = 1;
#$_POST['name'] = 'test';
#$_POST['cost'] = '32132131';


$id = $_GET['id'];

// Testing
#$id = 10;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$db_location = '../db_model.php';
	require "pro_model.php";

	$pro_db = new ProductTable();

	$pro_db->update($_GET['id'] ,$_POST['subtype'], $_POST['stock'], $_POST['supplier']);

	header("Location: http://{$_SERVER['HTTP_HOST']}/POS/products.php");
}   else {
	require_once "../templates/form.php";


	// Query the suppliers table to make drop down box
	$db_location = '../db_model.php';
	require "../suppliers/sup_model.php";
	require "../types/type_model.php";
	require "pro_model.php";

	// Create table objects
	$sup_db = new SupplierTable();
	$type_db = new TypeTable();
	$subtype_db = new SubTypeTable();
	$pro_db = new ProductTable();

	$pro_data = $pro_db->getDataFromID($id);
	$sup_data = $sup_db->getAllSupplierData();
	$type_data = $type_db->getAllTypeData();

	$subtype_data = $subtype_db->getAllSubTypeData();

	for($i=0; $i < count($sup_data); $i++)
		$sup_option[$sup_data[$i]['id']] = $sup_data[$i]['name'];


	for($i=0; $i < count($type_data); $i++)
		$type_option[$type_data[$i]['id']] = $type_data[$i]['name'];

	// TODO: Make relation to type
	for($i=0; $i < count($subtype_data); $i++)
		$subtype_option[$subtype_data[$i]['id']] = $subtype_data[$i]['name'];

	$pro_subtype = new Select('subtype', $subtype_option, 'Select Subtype: ', $pro_data['subtype_id']);

	$cur_type = $subtype_db->getDataFromID($pro_data['subtype_id'])['type_id'];
	$pro_type = new Select('type', $type_option, 'Select Product Type: ', $cur_type);

	$pro_supplier = new Select('supplier', $sup_option, 'Select Supplier: ', $pro_data['supplier_id']);
	// Create input form
	$pro_stock = new InputField('text', 'stock', 'Stock Level', $pro_data['stock'], null, 100);

	$pro_submit = new InputField('submit', 'submit', null, 'Insert', false);

	// Create the form
	$pro_form = new Form(array($pro_type, $pro_subtype, $pro_stock, $pro_supplier, $pro_submit),
		"http://{$_SERVER['HTTP_HOST']}/POS/products/insert.php?id={$id}");

	#$pro_form = new Form(array($pro_type, $pro_subtype, $pro_stock , $pro_supplier, $pro_submit),
	#	"http://{$_SERVER['HTTP_HOST']}/POS/products/insert.php");

	echo $pro_form->render();
}


include_once "../templates/footer.php";
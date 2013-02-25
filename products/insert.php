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

	$pro_db->insert($_POST['type'], $_POST['subtype'], $_POST['stock'], $_POST['supplier'], $_SESSION['uid']);

	header("Location: http://{$_SERVER['HTTP_HOST']}/POS/products.php");
}   else {
	require_once "../templates/form.php";

	// Create input form
	$pro_stock = new InputField('text', 'stock', 'Stock Level', null, null, 100);

	// Query the suppliers table to make drop down box
	$db_location = '../db_model.php';
	require "../suppliers/sup_model.php";
	require "../types/type_models.php";

	$sup_db = new SupplierTable();
	$type_db = new TypeTable();
	$subtype_db = new SubTypeTable();

	$sup_data = $sup_db->getAllSupplierData();
	$type_data = $type_db->getAllTypeData();
	$subtype_data = $subtype_db->getAllSubTypeData();

	for($i=0, $j=0, $k=0; $i < count($sup_data), $j < count($type_data), $k < count($subtype_data); $i++, $j++, $k++) {
		$sup_option[$sup_data[$i]['id']] = $sup_data[$i]['name'];
		$type_option[$type_data[$j]['id']] = $type_data[$j]['name'];
		$subtype_option[$subtype_data[$k]['id']] = $subtype_data[$k]['name'];
	}

	$pro_supplier = new Select('supplier', $sup_option);
	$pro_type = new Select('type', $type_option);
	$pro_subtype = new Select('subtype', $subtype_option);
	$pro_submit = new InputField('submit', 'submit', null, 'Insert', false);

	// Create the form
	$pro_form = new Form(array($pro_type , $pro_subtype, $pro_stock , $pro_supplier, $pro_submit),
		"http://{$_SERVER['HTTP_HOST']}/POS/products/insert.php");

	echo $pro_form->render();
}


include_once "../templates/footer.php";
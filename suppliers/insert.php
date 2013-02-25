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
/*
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SESSION['uid'] = 1;
$_POST['sup_name'] = 'test';
$_POST['sup_tel'] = '32132131';
*/

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$db_location = '../db_model.php';
	require "sup_model.php";

	$sup_db = new SupplierTable();
	$sup_db->insert($_POST['sup_name'], $_POST['sup_tel'], $_POST['sup_email'],
		$_POST['purchase_area'], $_SESSION['uid']);
	header("Location: http://{$_SERVER['HTTP_HOST']}/POS/suppliers.php");
}   else {
	require_once "../templates/form.php";

	$sup_name = new InputField('text', 'sup_name', 'Supplier Name', null, null, 50);
	$sup_tel = new InputField('text', 'sup_tel', 'Supplier Phone Number', null, null, 50);
	$sup_email = new InputField('text', 'sup_email', 'Supplier Email', null, null, 100);
	$purchase_area = new InputField('text', 'purchase_area', 'Purchase Area', null, null, 100);
	$sup_submit = new InputField('submit', 'submit', null, 'Insert', false);
	$sup_form = new Form(array($sup_name, $sup_tel, $sup_email, $purchase_area,$sup_submit),
		"http://{$_SERVER['HTTP_HOST']}/POS/suppliers/insert.php");
	echo $sup_form->render();
}


include_once "../templates/footer.php";
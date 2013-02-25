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
require "sup_model.php";
$sup_table = new SupplierTable();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$sup_table->update($id, $_POST['sup_name'], $_POST['sup_tel'], $_POST['sup_email'], $_POST['purchase_area']);
	header("Location: http://{$_SERVER['HTTP_HOST']}/POS/suppliers.php");
}   else {

	// Get data from sent ID
	$sup_data = $sup_table->getDataFromID($id);

	// Create input fields that will be sent to $_POST
	$sup_name = new InputField('text', 'sup_name', 'Supplier Name', $sup_data['name'], null, 50);
	$sup_tel = new InputField('text', 'sup_tel', 'Supplier Phone Number', $sup_data['tel'], null, 50);
	$sup_email = new InputField('text', 'sup_email', 'Supplier Email', $sup_data['email'], null, 100);
	$purchase_area = new InputField('text', 'purchase_area', 'Purchase Area', $sup_data['purchase_area'], null, 100);
	$sup_submit = new InputField('submit', 'submit', null, 'Edit', false);
	$sup_form = new Form(array($sup_name, $sup_tel, $sup_email, $purchase_area,$sup_submit),
		"http://{$_SERVER['HTTP_HOST']}/POS/suppliers/edit.php?id={$id}");

	// Generate the form HTML
	echo $sup_form->render();
}


include_once "../templates/footer.php";
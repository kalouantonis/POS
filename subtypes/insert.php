<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 3/9/13
 * Time: 11:38 AM
 * Licenced under the GPL v3
 */


$style_location = '../static/css/style.css';
include_once '../templates/header.php';

$db_location = '../db_model.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	require_once 'subtype_model.php';
	$subtype_table = new SubTypeTable();

	$subtype_table->insert($_POST['name'], $_POST['type'], $_SESSION['uid']);

	header("Location: http://{$_SERVER['HTTP_HOST']}/POS/subtypes.php");
}   else {
	require_once '../templates/form.php';
	require_once '../types/type_model.php';
	$type_db = new TypeTable();
	$type_data = $type_db->getAllTypeData();

	for($i=0; $i < count($type_data); $i++) // Formulate the select options
		$type_option[$type_data[$i]['id']] = $type_data[$i]['name'];

	$subtype_type = new Select('type', $type_option, 'Select a related type: ');

	$subtype_name = new InputField('text', 'name', 'Enter Subtype Name', null, null, 100);
	$submit = new InputField('submit', 'submit', null, 'Submit', true, null);

	$form = new Form(array($subtype_name, $subtype_type,$submit), "http://{$_SERVER['HTTP_HOST']}/POS/subtypes/insert.php");
	echo $form->render();
}

include_once '../templates/footer.php';

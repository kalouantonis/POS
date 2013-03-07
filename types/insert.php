<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 2/26/13
 * Time: 3:17 PM
 * Licenced under the GPL v3
 */

$style_location = '../static/css/style.css';
$page_title = 'Insert New Type';
include_once "../templates/header.php";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$db_location = '../db_model.php';
	require_once "type_model.php";

	$type_table = new TypeTable();
	$type_table->insert($_POST['type_name'], $_SESSION['uid']);

	header("Location: http://{$_SERVER['HTTP_HOST']}/POS/types.php");

}   else {
	require_once "../templates/form.php";

	$type_name = new InputField('text', 'type_name', 'Enter Type', null, null, 80);
	$type_submit = new InputField('submit', 'submit', null, 'Insert', false);

	$form = new Form(array($type_name, $type_submit), "http://{$_SERVER['HTTP_HOST']}/POS/types/insert.php");

	echo $form->render();
}


include_once "../templates/footer.php";
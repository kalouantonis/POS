<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 3/7/13
 * Time: 5:48 PM
 * Licenced under the GPL v3
 */

$style_location = '../static/css/style.css';
include_once '../templates/header.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('No ID given');

$db_location = '../db_model.php';
require 'type_model.php';
$type_table = new TypeTable();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$type_table->update($_GET['id'], $_POST['type_name']);

	header("Location: http://{$_SERVER['HTTP_HOST']}/POS/types.php");
}   else {
	$type_data = $type_table->getDataFromID($id);

	require_once '../templates/form.php';
	$name = new InputField('text', 'type_name', 'Type Name', $type_data['name']);
	$submit = new InputField('submit', 'submit', null, 'Edit', false);

	$form = new Form(array($name, $submit), "http://{$_SERVER['HTTP_HOST']}/POS/types/edit.php?id={$id}");
	echo $form->render(); // Render the form
}

include_once '../templates/footer.php';
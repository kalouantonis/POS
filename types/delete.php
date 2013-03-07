<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 3/7/13
 * Time: 10:13 AM
 * Licenced under the GPL v3
 */

$db_location = '../db_model.php';
require_once "type_model.php";

$id = isset($_GET['id']) ? $_GET['id'] : die('No ID given'); // Kill script if no ID is supplied

$type_table = new TypeTable();

$type_table->delete($id, $type_table->getDataFromID($id)['deleted']);

header("Location: http://{$_SERVER['HTTP_HOST']}/POS/types.php");
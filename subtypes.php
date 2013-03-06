<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 3/6/13
 * Time: 8:19 PM
 * Licenced under the GPL v3
 */

$page_title = 'Subtypes';
include_once "templates/header.php";

require 'auth/session_auth.php';
if(!sessionAccess())
	die ('You must be logged in to view this page!');
?>

<a href="/POS/types/sub_insert.php">Insert New Subtype</a><br><br>

<?php

require_once 'types/type_models.php';
require_once 'templates/table.php';

$show_deleted = isset($_GET['show']) ? true : false;

$subtype_table = new SubTypeTable();
$subtype_data = $subtype_table->getAllSubTypeData($show_deleted);

for($i=0; $i < count($subtype_data); $i++) {
	$row_object = 'robj' . $i;

	$extract = array(0 => $subtype_data[$i]['name']);
	$$row_object = new Row($extract, $subtype_data[$i]['id'], '/POS/types', $subtype_data[$i]['deleted']);
	$row_arr[] = $$row_object;
}

$table = new Table(array('Subtype'), $row_arr);
echo $table->render();

echo '<br>';
if($show_deleted)
	echo '<a href="/POS/subtypes.php">Hide deleted items</a>';
else
	echo '<a href="/POS/subtypes.php?show=true">Show deleted items</a>';

include_once "templates/footer.php";

?>
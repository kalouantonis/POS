<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 2/26/13
 * Time: 3:03 PM
 * Licenced under the GPL v3
 */

$page_title = 'Types';
include_once "templates/header.php";

require "auth/session_auth.php";

if(!sessionAccess())
	die("You must be logged in to view this page!");

#echo "Under development";

?>

<a href="/POS/types/insert.php">Insert New Type</a><br><br>

<?php

require "types/type_models.php";
require "templates/table.php";

$show_deleted = isset($_GET['show']) ? true : false;

$type_table = new TypeTable();
$subtype_table = new SubTypeTable();
$subtype_data = $subtype_table->getAllSubTypeData($show_deleted);

for($i=0; $i < count($subtype_data); $i++) {
	$row_object = 'robj' . $i;

	$extract = array(0 => $type_table->getDataFromID($subtype_data[$i]['type_id'])['name'],
		1 => $subtype_data[$i]['name']);

	$$row_object = new Row($extract, $subtype_data[$i]['id'], '/POS/types', $subtype_data[$i]['deleted']);
	$obj_arr[] = $$row_object;
}

$table = new Table(array('Type', 'Subtype'), $obj_arr);
echo $table->render();

echo '<br>';
if($show_deleted) {
	echo '<a href="/POS/suppliers.php">Hide deleted items</a>';
} else {
	echo '<a href="/POS/suppliers.php?show=true">Show deleted items</a>';
}

include_once "templates/footer.php";

?>

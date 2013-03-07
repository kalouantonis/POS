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

if(!checkSessionAccess())
	die ("You must be logged in to view this page!");

#echo "Under development";

?>

<a href="/POS/types/insert.php">Insert New Type</a><br><br>

<?php

require "types/type_model.php";
require "templates/table.php";

$show_deleted = isset($_GET['show']) ? true : false;

$type_table = new TypeTable();
$type_data = $type_table->getAllTypeData($show_deleted);

for($i=0; $i < count($type_data); $i++) {
	$row_object = 'robj' . $i;

	$extract = array(0 => $type_data[$i]['name']);

	$$row_object = new Row($extract, $type_data[$i]['id'], '/POS/types', $type_data[$i]['deleted']);
	$obj_arr[] = $$row_object;
}

$table = new Table(array('Type'), $obj_arr);
echo $table->render();

echo '<br>';
if($show_deleted) {
	echo '<a href="/POS/types.php">Hide deleted items</a>';
} else {
	echo '<a href="/POS/types.php?show=true">Show deleted items</a>';
}

include_once "templates/footer.php";

?>

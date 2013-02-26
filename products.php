<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 2/22/13
 * Time: 3:57 PM
 * Licenced under the GPL v3
 */

$page_title = 'Products';
include_once "templates/header.php";


require "auth/session_auth.php";

if(!sessionAccess()) {
	die ("You must be logged in to view this page");
}

?>

<a href="/POS/products/insert.php">Insert New Product</a><br><br>

<?php

require "products/pro_model.php";
require "types/type_models.php";

$pro_table = new ProductTable();
$type_table = new TypeTable();
$subtype_table = new SubTypeTable();

require "templates/table.php";

$show_deleted = isset($_GET['show']) ? true : false;

$product_data = $pro_table->getAllProducerData($show_deleted);

for($i=0; $i < count($product_data); $i++) {
	$row_object = 'robj' . $i;

	// May need supplier id
	$subtype_data = $subtype_table->getDataFromID($product_data[$i]['subtype_id']);
	$type_data = $type_table->getDataFromID($subtype_data['type_id']);

	// Extract values to create row
	$extract = array( 0=> $type_data['name'], 1 => $subtype_data['name'], 2 => $product_data[$i]['stock']);

	// Dynamically create row objects
	$$row_object = new Row($extract, $product_data[$i]['id'], '/POS/products', $product_data[$i]['deleted']);
	$obj_arr[] = $$row_object;
}

$table = new Table(array('Type', 'Sub-Type', 'Stock Level'), $obj_arr);
echo $table->render();

echo '<br>';

$show_deleted = isset($_GET['show']) ? true : false;

if($show_deleted)
	echo '<a href="/POS/products.php">Hide deleted items</a>';
else
	echo '<a href="/POS/products.php?show=true">Show deleted items</a>';

include_once "templates/footer.php";

?>

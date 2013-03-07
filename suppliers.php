<?php
/**
 * Created by JetBrains PhpStorm.
 * User: slacker
 * Date: 2/11/13
 * Time: 5:11 PM
 * To change this template use File | Settings | File Templates.
 */

$page_title = "Suppliers";


include_once('templates/header.php');
require "auth/session_auth.php";

// Debugging
#$_SESSION['username'] = "slacker";
#$_SESSION['uid'] = 1;

if(!checkSessionAccess())
	die ("You must be logged in to view this page!");

?>

<a href="/POS/suppliers/insert.php">Insert New Item</a><br><br>

<?php

require "suppliers/sup_model.php"; // include the database object

$supplier_table = new SupplierTable();

require "templates/table.php";

// TODO: Use regular expressions for input validation (telephone numbers, etc)
$show_deleted = isset($_GET['show']) ? true : false;

$supplier_data = $supplier_table->getAllSupplierData($show_deleted);
// Use function to create new objects for each row
for($i=0; $i < count($supplier_data); $i++) {
	// TODO: Make in to globaly accessible function. Many scripts will use this
	// Variadic variable creation
	$row_object = 'robj' . $i;
	// Only extract name and telephone number
	$extract = array(0 => $supplier_data[$i]['name'], 1=> $supplier_data[$i]['tel'],
		2 => $supplier_data[$i]['email'], 3 => $supplier_data[$i]['purchase_area']);
	$$row_object = new Row($extract, $supplier_data[$i]['id'], '/POS/suppliers', $supplier_data[$i]['deleted']);
	$obj_arr[] = $$row_object;
}

// Generate the table using the objects we created before
$table = new Table(array('Supplier Name', 'Telephone Number', 'Email Address', 'Purchase Area'), $obj_arr);
echo $table->render();

echo '<br>';
if($show_deleted) {
	echo '<a href="/POS/suppliers.php">Hide deleted items</a>';
} else {
	echo '<a href="/POS/suppliers.php?show=true">Show deleted items</a>';
}

include_once('templates/footer.php');


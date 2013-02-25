<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 2/23/13
 * Time: 1:41 PM
 * Licenced under the GPL v3
 */

if(!isset($db_location)) {
	$db_location = 'db_model.php';
}

require $db_location;

class ProductTable extends DBModel
{
	public function __construct($table_name='products', $custom_cols=null) {
		parent::__construct($table_name, $custom_cols);
	}

	public function getAllProducerData ($deleted=false) {
		$ids = $this->getAllIds($deleted);

		foreach($ids as $each_id) {
			$data[] = $this->getDataFromID($each_id['id']);
		}

		if(!isset($data)) {
			die ("No data found");
		}

		return $data;
	}

	public function insert($type, $subtype, $stock, $supplier_id, $user_id) {
		$sql = 'INSERT INTO ' . $this->_TableName . ' (type_id, subtype_id, stock, supplier_id, user_id)' .
			'VALUES (:type, :subtype, :stock, :supplier_id, :user_id)';
		// Using this way to prevent SQL injection

		$statement = $this->_DBObject->prepare($sql);

		$statement->bindParam(':type', $type);
		$statement->bindParam(':subtype', $subtype);
		$statement->bindParam(':stock', $stock);
		$statement->bindParam(':supplier_id', $supplier_id);
		$statement->bindParam(':user_id', $user_id);

		$statement->execute();
	}

	public function update($id, $type, $subtype, $stock, $supplier_id) {
		$sql = 'UPDATE ' . $this->_TableName . ' SET type_id=:type, subtype_id=:subtype, stock:stock, supplier_id=:supplier_id
			 WHERE ' . $this->_PrimaryKey . '=:id';

		$statement = $this->_DBObject->prepare($sql);

		// Bind parameters to prevent SQL Injection
		$statement->bindParam(':type', $type);
		$statement->bindParam(':subtype', $subtype);
		$statement->bindParam(':stock', $stock);
		$statement->bindParam(':supplier_id', $supplier_id);
		$statement->bindParam(':id', $id);

		// Execute statement
		$statement->execute();
	}
}
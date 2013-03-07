<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 3/7/13
 * Time: 10:01 AM
 * Licenced under the GPL v3
 */

if(!isset($db_location)) {
	$db_location = 'db_model.php';
}

require_once $db_location;class SubTypeTable extends DBModel
{
	public function __construct($table_name='subtypes', $custom_cols=null) {
		parent::__construct($table_name, $custom_cols);
	}

	public function getAllSubTypeData ($deleted=false) {
		$ids = $this->getAllIds($deleted);

		foreach($ids as $each_id) {
			$data[] = $this->getDataFromID($each_id['id']);
		}

		if(!isset($data)) {
			die ("No data found");
		}

		return $data;
	}

	public function insert($name, $type_id, $user_id) {
		$sql = 'INSERT INTO ' . $this->_TableName . ' (name, type_id, user_id) VALUES (:name, :type_id, :user_id)';
		// Using this way to prevent SQL injection

		$statement = $this->_DBObject->prepare($sql);

		$statement->bindParam(':name', $name);
		$statement->bindParam(':type_id', $type_id);
		$statement->bindParam(':user_id', $user_id);

		$statement->execute();
	}

	public function update($id, $name, $type_id) {
		$sql = 'UPDATE ' . $this->_TableName . ' SET name=:name, type_id=:type_id
			WHERE ' . $this->_PrimaryKey . '=:id';

		$statement = $this->_DBObject->prepare($sql);

		// Bind parameters to prevent SQL Injection
		$statement->bindParam(':name', $name);
		$statement->bindParam(':type_id', $type_id);
		$statement->bindParam(':id', $id);

		// Execute statement
		$statement->execute();
	}
}
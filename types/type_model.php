<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 2/25/13
 * Time: 7:36 PM
 * Licenced under the GPL v3
 */

if(!isset($db_location)) {
	$db_location = 'db_model.php';
}

require_once $db_location;

class TypeTable extends DBModel
{
	public function __construct($table_name='types', $custom_cols=null) {
		parent::__construct($table_name, $custom_cols);
	}

	public function getAllTypeData ($deleted=false) {
		$ids = $this->getAllIds($deleted);

		foreach($ids as $each_id) {
			$data[] = $this->getDataFromID($each_id['id']);
		}

		if(!isset($data)) {
			die ("No data found");
		}

		return $data;
	}

	public function insert($name, $user_id) {
		$sql = 'INSERT INTO ' . $this->_TableName . ' (name, user_id) VALUES (:name, :user_id)';
		// Using this way to prevent SQL injection

		$statement = $this->_DBObject->prepare($sql);

		$statement->bindParam(':name', $name);
		$statement->bindParam(':user_id', $user_id);

		$statement->execute();
	}

	public function update($id, $name) {
		$sql = 'UPDATE ' . $this->_TableName . ' SET name=:name WHERE ' . $this->_PrimaryKey . '=:id';

		$statement = $this->_DBObject->prepare($sql);

		// Bind parameters to prevent SQL Injection
		$statement->bindParam(':name', $name);
		$statement->bindParam(':id', $id);

		// Execute statement
		$statement->execute();
	}
}



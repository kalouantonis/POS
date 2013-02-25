<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 2/23/13
 * Time: 1:41 PM
 * Licenced under the GPL v3
 */


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

	public function insert($name, $subtype_id) {
		$sql = 'INSERT INTO ' . $this->_TableName . ' (name, subtype_id) VALUES (:name, :subtype_id)';
		// Using this way to prevent SQL injection

		$statement = $this->_DBObject->prepare($sql);

		$statement->bindParam(':name', $name);
		$statement->bindParam(':subtype_id',$subtype_id);

		$statement->execute();
	}

	public function update($id, $name, $subtype_id) {
		$sql = 'UPDATE ' . $this->_TableName . ' SET name=:name, subtype_id=:subtype_id
			WHERE ' . $this->_PrimaryKey . '=:id';

		$statement = $this->_DBObject->prepare($sql);

		// Bind parameters to prevent SQL Injection
		$statement->bindParam(':name', $name);
		$statement->bindParam(':subtype_id', $subtype_id);

		// Execute statement
		$statement->execute();
	}

	public function delete($id) {
		$sql = 'UPDATE ' . $this->_TableName . ' SET deleted=1 WHERE id=:id';

		$statement = $this->_DBObject->prepare($sql);

		$statement->bindParam(':id', $id);

		$statement->execute();
	}
}

class SubTypeTable extends DBModel
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

	public function insert($name) {
		$sql = 'INSERT INTO ' . $this->_TableName . ' (name) VALUES (:name)';
		// Using this way to prevent SQL injection

		$statement = $this->_DBObject->prepare($sql);

		$statement->bindParam(':name', $name);

		$statement->execute();
	}

	public function update($id, $name) {
		$sql = 'UPDATE ' . $this->_TableName . ' SET name=:name
			WHERE ' . $this->_PrimaryKey . '=:id';

		$statement = $this->_DBObject->prepare($sql);

		// Bind parameters to prevent SQL Injection
		$statement->bindParam(':name', $name);
		$statement->bindParam(':subtype_id', $subtype_id);

		// Execute statement
		$statement->execute();
	}
}

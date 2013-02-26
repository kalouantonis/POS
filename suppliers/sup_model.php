<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 2/25/13
 * Time: 7:35 PM
 * Licenced under the GPL v3
 */


if(!isset($db_location)) {
	$db_location = 'db_model.php';
}

require_once $db_location;

class SupplierTable extends DBModel
{

	public function __construct($table_name='suppliers', $custom_cols=null) {
		parent::__construct($table_name, $custom_cols);
	}

	public function __get($value) {
		return $this->getAllSupplierData();
	}

	public function getAllSupplierData ($deleted=false) {
		$ids = $this->getAllIds($deleted);

		foreach($ids as $each_id) {
			// Append data to array
			$id_data[] = $this->getDataFromID($each_id['id']);
		}

		if(!isset($id_data)) {
			die ('No data found');
		}

		return $id_data;
	}

	public function insert ($name, $tel, $email, $purchase_area, $user_id) {
		$sql = 'INSERT INTO ' . $this->_TableName . '(name, tel, email, purchase_area,user_id)
			VALUES (:name, :tel, :email, :purchase_area, :user_id)';
		$statement = $this->_DBObject->prepare($sql);

		$statement->bindParam(':name', $name);
		$statement->bindParam(':tel', $tel);
		$statement->bindParam(':email', $email);
		$statement->bindParam(':purchase_area', $purchase_area);
		$statement->bindParam(':user_id', $user_id);

		$statement->execute();

		//TODO: Write exception handeling
	}

	public function update ($id, $name, $tel, $email, $purchase_area) {
		$sql = 'UPDATE ' . $this->_TableName . ' SET name=:name, tel=:tel, email=:email, purchase_area=:purchase_area
			WHERE ' . $this->_PrimaryKey . '=:id';

		$statement = $this->_DBObject->prepare($sql);

		$statement->bindParam(':name', $name);
		$statement->bindParam(':tel', $tel);
		$statement->bindParam(':id', $id);
		$statement->bindParam(':email', $email);
		$statement->bindParam(':purchase_area', $purchase_area);

		$statement->execute();
	}


}

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: slacker
 * Date: 2/12/13
 * Time: 2:03 PM
 * To change this template use File | Settings | File Templates.
 */

define("DB_NAME", 'pos_db'); // Need to change this manually until further notice
define("DB_USERNAME", 'slacker'); // Test username
define("DB_PASSWORD", 'slacker');
define("DB_HOST", 'localhost');

class DBModel
{
    protected $_TableName;
    protected $_ColumnNames;
	protected $_DBObject; // May not need this
	protected $_PrimaryKey = null;

	// TODO: Add destructor

    public function __construct($table_name, $column_names = null) {   // Object constructor
        $this->_TableName = $table_name; # Set the private attribute

	    // Create new PDO object
	    try {
		    $this->_DBObject = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
	    } catch (PDOException $exception) {
		    echo 'Could not connect to database: ' . $exception;
	    }

	    if (!isset($column_names)) {
		    $this->_ColumnNames = $this->getColumnNames();
		    $this->getPrimaryKey();
	    } else {
		    $this->_ColumnNames = $column_names;
	    }
    }

	public function custQuery($query) {
		$statement = $this->_DBObject;
		$result = $statement->prepare($query);
		$result->execute();
		return $result->fetch();
	}

	public function getDataFromID($id) {

		$sql_query = 'SELECT * FROM ' . $this->_TableName . ' WHERE ' . $this->_PrimaryKey . '=:id';

		// Prepare the query
		$statement = $this->_DBObject->prepare($sql_query);

		// Bind the variables with the query
		$statement->bindParam(":id", $id);

		// Execute the query
		$statement->execute();

		// Fetch the result
		return $statement->fetch(PDO::FETCH_ASSOC);

	}

	public function getAllIds($deleted=false) {
		$sql_query = 'SELECT ' . $this->_PrimaryKey . ' FROM ' . $this->_TableName;

		if(!$deleted) {
			$sql_query .= ' WHERE deleted=0';
		}

		$statement = $this->_DBObject->prepare($sql_query);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function findID($query_field, $query_term) {

		if (!$this->_PrimaryKey) { die("There is not primary key!"); }

		$sql_query = 'SELECT ' . $this->_PrimaryKey .
			' FROM ' .$this->_TableName .
			' WHERE ' . $query_field . '=' . $query_term;


		// Prepare the query
		$statement = $this->_DBObject->prepare($sql_query);

		//Execute the query
		$statement->execute();

		// Fetch the result
		$result = $statement->fetch();
		return $result[$this->_PrimaryKey];
	}

	protected function getColumnNames() {
		$query = "DESCRIBE " . $this->_TableName;
		$statement = $this->_DBObject->prepare($query);
		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_COLUMN);

	}
	protected function getPrimaryKey() {
		foreach ($this->_ColumnNames as $each_column) {
			if ((strstr($each_column, '_id' ) or (strstr($each_column, 'id')))) {
				$this->_PrimaryKey = $each_column;
				break;
			}
		}
	}
}

// Testing
/*
$test = new DBModel('users');

$sent_username = 'slacker';
$sent_password = 'slacker';

$user_data = $test->custQuery("SELECT * FROM users WHERE username='$sent_username'
                                AND password=PASSWORD('$sent_password')");
$allIds = $test->getAllIds();

echo var_dump($allIds);
*/

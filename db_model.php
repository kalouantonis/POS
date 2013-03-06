<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Antonis Kalou
 * Date: 2/12/13
 * Time: 2:03 PM
 * @author: Antonis Kalou
 * @copyright GPL v3
 */

define("DB_NAME", 'pos_db'); // Need to change this manually until further notice
define("DB_USERNAME", 'slacker'); // Test username
define("DB_PASSWORD", 'slacker');
define("DB_HOST", 'localhost');


/**
 * Creates an abstraction layer between the Controller and MySQL.
 *
 * @version 1.1
 *
 * @property string $_TableName The table name in the Database
 * @property array $_ColumnNames
 * @property object $_DBObject Object instance of the PDO
 * @property string $_PrimaryKey The name of the primary in the table. This is used later for automatic querying
 *
 */
class DBModel
{
    protected $_TableName;
    protected $_ColumnNames;
	protected $_DBObject; // May not need this
	protected $_PrimaryKey = null;

	// TODO: Add destructor

	/**
	 * Construct the Class and connects to the database.
	 *
	 * It requires 2 arguments to construct. The table name, which is the table stored in the MySQL database,
	 * and custom column names, if they are required.
	 *
	 * @method void __construct(string $table_name, array $column_names)
	 * @throws Exception if connection to the MySQL database has failed
	 */
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

	/**
	 * Runs a custom SQL command, when the built in functions do not suffice.
	 *
	 * @param string $query The custom SQL query
	 * @return mixed[] Returns a relational array of the results
	 */
	public function custQuery($query) {
		$statement = $this->_DBObject;
		$result = $statement->prepare($query);
		$result->execute();
		return $result->fetch();
	}

	/**
	 * Gets all data from the table, using the primary key.
	 *
	 * Binds parameter of $id in to the :id located in the $sql_query string
	 *
	 * @param int $id The ID to get the data for
	 * @return mixed[] Returns the query results in a relational array
	 */
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

	/**
	 * Gets all ID's in the table
	 *
	 * Queries table for all IDS. The caller can decide weather they want all IDS (included deleted)
	 * or ID's excluding deleted
	 *
	 * @param bool $deleted If true, queries the table for deleted items too
	 * @return mixed[]
	 */
	public function getAllIds($deleted=false) {
		$sql_query = 'SELECT ' . $this->_PrimaryKey . ' FROM ' . $this->_TableName;

		if(!$deleted) {
			$sql_query .= ' WHERE deleted=0';
		}

		$statement = $this->_DBObject->prepare($sql_query);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 *
	 * Finds the ID of the item.
	 *
	 * @todo FIX this
	 *
	 * @depreciated 1.1
	 * @depreciated Do not use this, as it does not work, and will not work until further notice
	 *
	 * @param string $query_field The field name in the table to query
	 * @param string $query_term The term to search for in the provided query field
	 * @return int|mixed[] Unsure of this, as it may return either a single int or an array
	 */
	public function findID($query_field, $query_term) {

		if (!$this->_PrimaryKey) { die("There is no primary key!"); }

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

	/**
	 * Deletes item from the table
	 *
	 * This does not actually delete rows, as this could leave orphaned data.
	 * It sets the deleted field to 1, which is equivalent to true.
	 * If $restore = true, then the oposite is done, setting deleted to false.
	 *
	 * @param int $id The ID that will be deleted.
	 * @param bool $restore If true, sets deleted to 0, making the row accessible
	 */
	public function delete($id, $restore=false) {
		$sql = 'UPDATE ' . $this->_TableName;

		if($restore)
			$sql .= ' SET deleted=0 ';
		else
			$sql .= ' SET deleted=1 ';

		$sql .= 'WHERE id=:id';

		$statement = $this->_DBObject->prepare($sql);

		$statement->bindParam(':id', $id);

		$statement->execute();
	}
}

// Testing


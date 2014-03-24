<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	private $_table_name;
	private $_query_limit = 50;

	function __construct($table_name)
	{
		parent::__construct();

		// Will set the table name required by the constructor
		$this->_table_name = $table_name;
	}

	function getAll($deleted = FALSE, $get_num_pages=FALSE)
	{
		// SELECT * FROM `{'$table_name'} WHERE 'deleted' = {'$deleted'}
		$query = $this->db->get_where($this->_table_name, array('deleted' => $deleted));

		// If get_num_pages is set to TRUE, the method will return only the 
		// pages the view will need to load. 
		if($get_num_pages)
			// ceil Rounds up to the next integer 
			return ceil($query->num_rows() / $this->_query_limit);

		// Data will remain FALSE if nothing is found
		$data = FALSE;

		if($query->num_rows() > 0) {
			foreach($query->result() as $row)
				// Append the row data on to the data array
				$data[] = $row;
		}

		return $data;
	}

	function getPage($page_num=0)
	{
		/* 
		 * Will get a page for loading in the index view.
		 * the limit will be set by $this->_query_limit
		 */

		// Will only get non-deleted items, as only used in index page
		$query = $this->db->get_where($this->_table_name, array('deleted' => 0), 
			$this->_query_limit, $page_num * $this->_query_limit);

		$data = FALSE;

		if($query->num_rows() > 0) {
			foreach($query->result() as $row) 
				$data[] = $row;
		}

		return $data;
	}

	function get_data_from_id($id)
	{
		/* Will only return one row from the selected it.*/
		$query = $this->db->get_where($this->_table_name, array('id' => $id), 1);

		$data = FALSE;

		if($query->num_rows > 0) 
			$data = $query->row();
		

		return $data;

	}

	function insert_row($insert_data)
	{
		return $this->db->insert($this->_table_name, $insert_data);
		// Will return false if action failed
	}

	function update_row($id, $update_data)
	{
		$this->db->where('id', $id);

		return $this->db->update($this->_table_name ,$update_data);
	}

	function delete_row($id, $restore=FALSE)
	{
		// Will dictate weather the item will be restored or deleted
		// If 0 will restore, 1 will delete
		$delete = $restore ? 0 : 1;

		$this->db->where('id', $id);

		return $this->db->update($this->_table_name, array('deleted' => $delete));
	}

}
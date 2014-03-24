<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'my_model.php';

class Type_Model extends MY_Model
{
	function __construct()
	{
		parent::__construct('types');
	}

	function getAllRelevant($id, $deleted=TRUE) 
	{
		$select = array('supplier_id' => $id);
		if(!$deleted) { 
			$select['deleted'] = $deleted;
		}

		$query = $this->db->get_where('types', $select);

		$data = FALSE;

		if($query->num_rows() > 0) {
			foreach($query->result() as $row) 
				$data[] = $row;
		}

		return $data;
	} 

	function checkConflict($type_name, $supplier_id)
	{
		// Results in : 
		//SELECT * FROM types WHERE name LIKE $type_name AND supplier_id = $supplier_id
		$this->db->like('name', $type_name, 'none');
		$this->db->where('supplier_id', $supplier_id);
		$query = $this->db->get('types');

		return $query->row(); // Only want to check if one row exists
		// Will return false if not found
	}
}
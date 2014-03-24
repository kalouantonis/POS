<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'my_model.php';

class Product_Model extends MY_Model
{
	function __construct()
	{
		parent::__construct('products');
	}

	function getAllRelevant($id, $deleted=TRUE)
	{
		// Used in controllers/suppliers.php to get all product items where
		// the $id matches the ID field in the product table 
		$selected = array('subtype_id' => $id);

		// If i want deleted items or not. If TRUE, i do
		if(!$deleted)
			$selected['deleted'] = $deleted;

		$query = $this->db->get_where('products', $selected);

		$data = FALSE;

		if($query->num_rows() > 0) {
			foreach($query->result() as $row)
				$data[] = $row;
		}

		return $data;
	}

	function checkConflict($subtype_id, $stock)
	{
		// Will check if the product already exists
		$this->db->where('subtype_id', $subtype_id);

		$query = $this->db->get('products');

		// Conflict was found if the return != FALSE
		return $query->row();
	}
}
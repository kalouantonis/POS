<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'my_model.php';

class Subtype_Model extends MY_Model
{
	function __construct()
	{
		parent::__construct('subtypes');
	}

	function getAllRelevant($id, $deleted=TRUE)
	{

		$select = array('type_id' => $id);
		if(!$deleted)
			$select['deleted'] = $deleted;

		$query = $this->db->get_where('subtypes', $select);

		$data = FALSE;

		if($query->num_rows() > 0) {
			foreach($query->result() as $row)
				$data[] = $row;
		}

		return $data;
	}

	function checkConflict($subtype_name, $type_id)
	{
		// check if insert confilcts with existing record
		// So if it has same name or type
		$this->db->like('name', $subtype_name, 'none');
		$this->db->where('type_id', $type_id);

		$query = $this->db->get('subtypes');

		return $query->row();
	}
}
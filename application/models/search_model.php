<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search_Model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	/*
	function by_reference($ref_num)
	{
		$tables = array('suppliers', 'types', 'subtypes', 'products');

		$data = FALSE;

		foreach($tables as $table)
		{
			$query = $this->db->get_where($table, array('id' => $ref_num));

			if($query->num_rows() > 0) {
				foreach($query->result() as $row)
					$data[] = $row;
			}
		}

		return $data;
	}
	*/

	function by_table($table_name, $term, $column_names=FALSE)
	{
		if(!$column_names)
			$column_names = $this->db->list_fields($table_name);

		$data = FALSE;

		foreach ($column_names as $column) 
			$this->db->or_like($column, $term);

		$query = $this->db->get($table_name);

		if($query->num_rows() > 0) {
			foreach ($query->result() as $row) 
				$data[] = $row;
		}

		return $data;

	}
}

/* End of file search_model.php */
/* Location: ./application/models/search_model.php */
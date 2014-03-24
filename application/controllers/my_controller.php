<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'utils.php';

class MY_Controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		// Profiling
		#$this->output->enable_profiler(TRUE);
		
		if(!$this->session->userdata('uid') or !$this->session->userdata('username')) {
			// Exit script if not logged in
			return redirect('home/index', 'location');
		}
	}

	protected function _generate_json($model)
	{
		// Will generate JSON objects using Database objects from that model.
		if(!$model_data = $this->$model->getAll()) {
			db_error();
		}

		return json_encode($model_data);
	}

	protected function _generate_select($model_name)
	{
		// Will generate select data to be used in views for
		// <select><option> fields
		if(!$table_data = $this->$model_name->getAll())
			return FALSE;

		foreach($table_data as $row)
			$data[$row->id] = $row->name;

		return $data;
	}

}

/* End of file my_controller.php */
/* Location: ./application/controllers/my_controller.php */
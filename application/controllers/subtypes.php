<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 3/16/13
 * Time: 3:48 PM
 * Licenced under the GPL v3
 */


// Works the same as products.php, so if you want comments
// check that out :). Im too lazy

require_once 'my_controller.php';
require_once 'utils.php';

class Subtypes extends MY_Controller
{
	// Function that will be executed on page load of edit and Insert
	private $_onload = "Change_type($('#supplier').val())";

	function __construct()
	{
		parent::__construct();

		
		$this->load->model('subtype_model');
		$this->load->model('type_model');
		$this->load->model('supplier_model');
	}

	private function _validate_form()
	{
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('type', 'Type', 'trim|required');

		return $this->form_validation->run();
		// Validation failed if return is false
	}

	function index($page=0, $message = NULL)
	{
		$data['num_pages'] = $this->subtype_model->getAll(FALSE, TRUE);
		$data['curr_page'] = $page;
		$data['controller'] = 'subtypes';

		if($subtype_data = $this->subtype_model->getPage($page)) {


			$data['headings'] = array('Ref', 'Sub-Type', 'Related Type', 'Related Supplier');

			foreach($subtype_data as $subtype) {
				if(!$type_data = $this->type_model->get_data_from_id($subtype->type_id) or
					!$supplier_data = $this->supplier_model->get_data_from_id($type_data->supplier_id))
				{
					db_error();
				}

				$data['table_data'][] = array (
					$subtype->id,
					$subtype->name,
					anchor("types/info/{$type_data->id}", $type_data->name),
					anchor("suppliers/info/{$supplier_data->id}", $supplier_data->name),
					anchor("subtypes/info/{$subtype->id}", 'Info'),
					anchor("subtypes/edit/{$subtype->id}", 'Edit'),
					anchor("subtypes/delete/{$subtype->id}", 'Delete')
				);
			}
		}

		if(isset($message))
			$data['message'] = $message;

		$data['insert_location'] = 'subtypes/insert';
		$data['view_name'] = 'table_view';
		$data['page_title'] = 'Subtypes';

		$this->load->view('includes/template', $data);
	}

	function insert() 
	{
		// Page load scipt args
		$data['onload'] = $this->_onload;

		$data['view_name'] = 'insert/subtype';
		$data['page_title'] = 'Insert Subtype';
		$data['type'] = $this->_generate_select('type_model');
		$data['supplier'] = $this->_generate_select('supplier_model');
		if(!$data['supplier'] or !$data['type'])
			return $this->index(0, 'Must enter suppliers and types first!');

		$data['json_type'] = $this->_generate_json('type_model');

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(!$this->_validate_form()) 
				return $this->load->view('includes/template', $data);
			
			$insert_data = array(
				'name' => $this->input->post('name'),
				'type_id' => $this->input->post('type'),
				'user_id' => $this->session->userdata('uid')
			);

			if($this->subtype_model->checkConflict($insert_data['name'], $insert_data['type_id']))
				return $this->index(0, 'Item already exists!');

			if(!$this->subtype_model->insert_row($insert_data))
				db_error();

			return $this->index(0, 'Insert Successful');

		} else {

			$this->load->view('includes/template', $data);
		}
	}

	function edit($id = NULL) 
	{
		if(!isset($id) or !is_numeric($id))
			direct_error();

			// Define data sent to views
		$data['type'] = $this->_generate_select('type_model');
		$data['post_location'] = "subtypes/edit/{$id}";
		$data['view_name'] = 'insert/subtype';
		$data['page_title'] = 'Edit Sub-Type';

		// Generate the supplier Option/Select
		$data['supplier'] = $this->_generate_select('supplier_model');
		// Generate JSON objects for JS
		$data['json_type'] = $this->_generate_json('type_model');

		// Do not disable drop down boxes
		$data['disabled'] = FALSE;

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(!$this->_validate_form()) 
				return $this->load->view('includes/template', $data);

			$update_data = array(
				'name' => $this->input->post('name'),
				'type_id' => $this->input->post('type'),
				'user_id' => $this->session->userdata('uid')
			);

			if(!$this->subtype_model->update_row($id, $update_data)) 
				db_error();

			return $this->index(0, 'Edit Successful!');

		} else {
			if(!$subtype_data = $this->subtype_model->get_data_from_id($id) or  
				!$type_data = $this->type_model->get_data_from_id($subtype_data->type_id) or
				!$supplier_data = $this->supplier_model->get_data_from_id($type_data->supplier_id))
			{
				db_error();
			}

			$selected_type = $subtype_data->type_id;
			$selected_supplier = $type_data->supplier_id;

			$data['form_values'] = array (
				'name' => $subtype_data->name,
				'type' => $selected_type,
				'supplier' => $selected_supplier
			);

			$this->load->view('includes/template', $data);
		}
	}

	function info($id = NULL, $print = NULL) 
	{
		$this->load->model('user_model');

		if(!isset($id) or !is_numeric($id))
			direct_error();
		else if(!$subtype_data = $this->subtype_model->get_data_from_id($id) or
			!$type_data = $this->type_model->get_data_from_id($subtype_data->type_id) or
			!$supplier_data = $this->supplier_model->get_data_from_id($type_data->supplier_id) or
			!$user_data = $this->user_model->get_data_from_id($subtype_data->user_id)) 
		{
			db_error();
		}

		$data['info_data'] = array(
			'Reference Number' => $subtype_data->id,
			'Name' => $subtype_data->name,
			'Related Supplier' => anchor("suppliers/info/{$supplier_data->id}", $supplier_data->name),
			'Related Type' => anchor("types/info/{$type_data->id}", $type_data->name),
			'Date Changed' => $subtype_data->date_changed,
			'Entered By' => $user_data->first_name
		);

		$data['controller'] = 'subtypes';
		$data['page_title'] = 'Subtype Information';

		if(isset($print)) 
			$this->load->view('print', $data);
		else {
			$data['view_name'] = 'info';
			$this->load->view('includes/template', $data);
		}
	}

	function delete($id = NULL, $restore=FALSE) 
	{
		if(!isset($id) or !is_numeric($id))
			direct_error();
		else if(!$this->subtype_model->delete_row($id, $restore))
			db_error();

		$this->index(0, $restore ? 'Restore Successful' :'Delete Successful');
	}
}
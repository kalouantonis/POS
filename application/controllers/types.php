<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 3/14/13
 * Time: 6:57 PM
 * Licenced under the GPL v3
 */


// All docs are in products.php and product_model.php

require_once 'my_controller.php';
require_once 'utils.php';

class Types extends MY_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('type_model'); // Autoload models
		$this->load->model('supplier_model');

	}

	function _validate_form($page_title=NULL)
	{
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('supplier', 'Supplier', 'trim|required');

		// Validation failed
		return $this->form_validation->run();
	}

	function index($page=0, $message=NULL)
	{
		$data['num_pages'] = $this->type_model->getAll(FALSE, TRUE);
		$data['curr_page'] = $page;
		$data['controller'] = 'types';

		if($result = $this->type_model->getPage($page)) {

			$data['headings'] = array('Ref', 'Type', 'Related Supplier'); // Add related subtypes

			foreach($result as $type) {

				if(!$supplier_data = $this->supplier_model->get_data_from_id($type->supplier_id))
					db_error();

				$data['table_data'][] = array(
					$type->id,
					$type->name,
					anchor("suppliers/info/{$supplier_data->id}", $supplier_data->name),
					anchor("types/info/{$type->id}", 'Info'),
					anchor("types/edit/{$type->id}", 'Edit'),
					anchor("types/delete/{$type->id}", 'Delete')
				);
			}
		}

		if(isset($message))
			$data['message'] = $message;

		$data['insert_location'] = 'types/insert';
		$data['view_name'] = 'table_view';
		$data['page_title'] = 'Types';

		$this->load->view('includes/template', $data);
	}

	function info($id=NULL, $print = NULL)
	{
		$this->load->model('user_model');

		if(!isset($id) or !is_numeric($id))
			direct_error();
		else if(!$type_data = $this->type_model->get_data_from_id($id) or
			!$user_data = $this->user_model->get_data_from_id($type_data->user_id) or
			!$supplier_data = $this->supplier_model->get_data_from_id($type_data->supplier_id))
		{
			db_error();
		}

		$data['info_data'] = array (
			'Reference Number' => $type_data->id,
			'Name' => $type_data->name,
			'Related Supplier' => anchor("suppliers/info/{$supplier_data->id}", $supplier_data->name),
			'Date Changed' => $type_data->date_changed,
			'Last Changed By' => $user_data->first_name
			// TODO: Add related subtypes and supplier
		);

		$data['controller'] = 'types';
		$data['page_title'] = 'Type Information';

		if(isset($print)) 
			$this->load->view('print', $data);
		else {
			$data['view_name'] = 'info';
			$this->load->view('includes/template', $data);
		}
	}

	function insert()
	{
		$data['view_name'] = 'insert/type';
		$data['page_title'] = 'Insert Type';
		$data['supplier'] = $this->_generate_select('supplier_model');
		if(!$data['supplier'])
			return $this->index(0, 'Must enter supplier first!');

		// FIXME: Supplier data to be inserted
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(!$this->_validate_form()) {

				// Will not run the rest of the script
				return $this->load->view('includes/template', $data);
			}

			// Do not worry about XSS, as filtering occurs automatically!
			$insert_data = array(
				'name' => $this->input->post('name'),
				'supplier_id' => $this->input->post('supplier'),
				'user_id' => $this->session->userdata('uid')
			);

			if($this->type_model->checkConflict($insert_data['name'], $insert_data['supplier_id']))
				return $this->index(0, 'That item already exists!');

			if(!$this->type_model->insert_row($insert_data))
				db_error();

			$this->index(0, 'Insert Successful');

		} else {

			$this->load->view('includes/template', $data);

		}
	}

	function edit($id=NULL)
	{
		$data['view_name'] = 'insert/type';
		$data['page_title'] = 'Edit Type';
		$data['supplier'] = $this->_generate_select('supplier_model');
		$data['post_location'] = "types/edit/{$id}";

		if(!isset($id) or !is_numeric($id))
			direct_error();

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(!$this->_validate_form()) 
				return $this->load->view('includes/template', $data);

			$update_data = array (
				'name' => $this->input->post('name'),
				'supplier_id' => $this->input->post('supplier'),
				'user_id' => $this->session->userdata('uid') 
			);


			if(!$this->type_model->update_row($id, $update_data))
				db_error();

			$this->index(0, 'Edit Succeeded');

		} else {

			if(!$type_data = $this->type_model->get_data_from_id($id) or
				!$supplier_data = $this->supplier_model->get_data_from_id($type_data->supplier_id))
			{
				db_error();
			}

			$selected_supplier = $type_data->supplier_id;

			$data['form_values'] = array(
				'name' => $type_data->name,
				'supplier' => $selected_supplier 
			);

			$this->load->view('includes/template', $data);
		}
	}

	function delete($id=NULL, $restore=FALSE)
	{
		if(!isset($id) or !is_numeric($id))
			direct_error();
		else if(!$this->type_model->delete_row($id, $restore))
			db_error();

		$this->index(0, $restore ? 'Restore Successful' :'Delete Successful');
	}

}
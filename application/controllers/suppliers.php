<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 3/9/13
 * Time: 3:25 PM
 * Licenced under the GPL v3
 */

require_once 'my_controller.php';
require_once 'utils.php';

class Suppliers extends MY_Controller
{
	// The insert location that will be provided to the view
	function __construct()
	{
		// Will check anytime any page is loaded if there is a user logged in
		parent::__construct();

		// Autoload this model for the class
		$this->load->model('supplier_model');
	}

	private function _validate_form()
	{
			// Run form validation routines
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('tel', 'Phone Number', 'trim|required');
		// valid_email checks for valid emails...
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('purchase_area', 'Purchase Area', 
			'trim|required');

		// Run the validation scripts
		return $this->form_validation->run();
		// Validation has failed if return is false
	}

	function index($page=0, $message=NULL) {

		$data['num_pages'] = $this->supplier_model->getAll(FALSE, TRUE);
		$data['curr_page'] = $page;
		$data['controller'] = 'suppliers';

		if($result = $this->supplier_model->getPage($page)) {


			// HTML table headings
			$data['headings'] = array('Ref', 'Name', 'Telephone number', 'Email', 'Purchase Area');

			// Generate the table data
			foreach($result as $supplier) {

				$data['table_data'][] = array(
					$supplier->id,
					$supplier->name,
					$supplier->tel,
					mailto($supplier->email),
					$supplier->purchase_area,
					anchor("suppliers/info/{$supplier->id}", 'Info'), // Create info URL
					anchor("suppliers/edit/{$supplier->id}", 'Edit'),
					anchor("suppliers/delete/{$supplier->id}", 'Delete')
				);
			}

		}
		// Set the view to be loaded
		if(isset($message))
			$data['message'] = $message;

		$data['insert_location'] = 'suppliers/insert';
		$data['view_name'] = 'table_view';
		$data['page_title'] = 'Suppliers';

		$this->load->view('includes/template.php', $data);
	}

	function insert()
	{
		$data['view_name'] = 'insert/supplier';
		$data['page_title'] = 'Insert Supplier';

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			if(!$this->_validate_form()) 
				return $this->load->view('includes/template', $data);

			$insert_data = array(
				'name' => $this->input->post('name'),
				'tel' => $this->input->post('tel'),
				'email' => $this->input->post('email'),
				'purchase_area' => $this->input->post('purchase_area'),
				'user_id' => $this->session->userdata('uid')
			);

			if(!$this->supplier_model->insert_row($insert_data))
				db_error();

			$this->index(0, 'Insert Successful');

		} else {

			$this->load->view('includes/template', $data);
		}
	}

	function info($id=NULL, $print = NULL)
	{
		// Load required models
		$this->load->model('user_model');
		$this->load->model('product_model');
		$this->load->model('type_model');
		$this->load->model('subtype_model');


		// Generate errors if data is not found. This is an error, as data should exist, unless a person
		// tries to access the url manually. ID is set to null by default to be able to catch errors

		// Grabing from Offset 0, as there should be no other rows
		if(!isset($id) or !is_numeric($id))
			direct_error();
		else if(!$supplier_data = $this->supplier_model->get_data_from_id($id) or
			!$user_data = $this->user_model->get_data_from_id($supplier_data->user_id))
		{
			db_error();
		}
		else if($type_data = $this->type_model->getAllRelevant($supplier_data->id, FALSE)) {
			// If type exists
			foreach($type_data as $type) {

				if($subtype_data = $this->subtype_model->getAllRelevant($type->id, FALSE)) {
					// If the subtype exists
					foreach($subtype_data as $subtype)
						// If the product exists
						if($product_data = $this->product_model->getAllRelevant($subtype->id, FALSE)) {
							foreach($product_data as $product)
								$data['details'][] = array(
									'type' => $type,
									'subtype' => $subtype,
									'product' => $product
								);	
						}
				}
				// Note, if there is a broken link, the data will not be created
			}
		} 

		$data['info_data'] = array(
			'Reference Number' => $supplier_data->id,
			'Name' => $supplier_data->name,
			'Telephone Number' => $supplier_data->tel,
			'Email' => mailto($supplier_data->email),
			'Purchase Area' => $supplier_data->purchase_area,
			'Date Changed' => $supplier_data->date_changed,
			'Last Changed By' => $user_data->first_name
		);

		$data['page_title'] = 'Supplier Information';

		if(isset($print)) 
			$this->load->view('suppliers/print', $data);
		else {
			$data['view_name'] = 'suppliers/info';
			$this->load->view('includes/template', $data);
		}
	}

	function edit($id=NULL)
	{
		if(!isset($id) or !is_numeric($id))
			direct_error();

		if(!$sup_info = $this->supplier_model->get_data_from_id($id))
			db_error();

		$data['view_name'] = 'insert/supplier';
		$data['page_title'] = 'Edit Supplier';
		$data['post_location'] = "suppliers/edit/{$id}";
		$data['form_values'] = array(
			'name' => $sup_info->name,
			'tel' => $sup_info->tel,
			'email' => $sup_info->email,
			'purchase_area' => $sup_info->purchase_area
		);

		if($_SERVER['REQUEST_METHOD'] == 'POST') {

			if(!$this->_validate_form()) 
				return $this->load->view('includes/template', $data);
			

			$update_data = array(
				'name' => $this->input->post('name'),
				'tel' => $this->input->post('tel'),
				'email' => $this->input->post('email'),
				'purchase_area' => $this->input->post('purchase_area'),
				'user_id' => $this->session->userdata('uid')
			);

			if(!$this->supplier_model->update_row($id, $update_data)) 
				db_error();

			$this->index(0, 'Edit Succeeded');

		} else {

			$this->load->view('includes/template', $data);
		}

	}

	function delete($id=NULL, $restore=FALSE)
	{

		// Check if the id arguement is set
		if(!isset($id) or !is_numeric($id))
			direct_error();
		// If delete fails, generate error
		else if(!$this->supplier_model->delete_row($id, $restore))
			db_error();

		$this->index(0, $restore ? 'Restore Successful' :'Delete Successful');

	}
}
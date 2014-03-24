<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'my_controller.php';
require_once 'utils.php';

class Products extends MY_Controller
{
	// Function that will be executed on page load of edit and Insert
	private $_onload = "Change_type($('#supplier').val())";

	// The threshold that will cause stock to be low
	private $_stock_thresh = 20;


	function __construct()
	{
		parent::__construct();

		// Autoload needed models
		$this->load->model('product_model');
		$this->load->model('supplier_model');
		$this->load->model('subtype_model');
		$this->load->model('type_model');
	}

	
	private function _validate_form()
	{
		// Will return FALSE if not numeric
		$this->form_validation->set_rules('stock', 'Stock', 'trim|required|integer');
		$this->form_validation->set_rules('subtype', 'Sub-Type', 'trim|required');
		// Not really required, just for UI 
		$this->form_validation->set_rules('supplier', 'Supplier', 'trim|required');
		$this->form_validation->set_rules('type', 'Type', 'trim|required');

		// Will return false if val failed
		return $this->form_validation->run();
	}

	function index($page=0, $message = NULL)
	{

		// Set number of pages required to load all data
		$data['num_pages'] = $this->product_model->getAll(FALSE, TRUE);
		$data['curr_page'] = $page;
		// The model name
		$data['controller'] = 'products';

		// If there is data on that page
		if($product_data = $this->product_model->getPage($page)) {

			// Table Headings
			$data['headings'] = array('Ref', 'Stock Level','Type', 'Subtype', 'From Supplier');

			// Generate table data
			foreach($product_data as $product) {
				// If any of these fail, a DB error occurs
				if(!$subtype_data = $this->subtype_model->get_data_from_id($product->subtype_id) or
					!$type_data = $this->type_model->get_data_from_id($subtype_data->type_id) or
					!$supplier_data = $this->supplier_model->get_data_from_id($type_data->supplier_id))
				{
					db_error();
				}

				// Check stock with threshold leved
				if($product->stock <= $this->_stock_thresh)  {
					// Data to be added to session
					$flash_stock[] = 'Stock for product reference '
						. anchor("products/info/{$product->id}", $product->id) . ' is running out';

					// Add the array in to flashdata
					$this->session->set_flashdata('flash_stock', $flash_stock);
				}

				// The data that will be loaded in to the table
				$data['table_data'][] = array(
					$product->id,
					$product->stock,
					anchor("types/info/{$type_data->id}", $type_data->name),
					anchor("subtypes/info/{$subtype_data->id}", $subtype_data->name),
					anchor("suppliers/info/{$supplier_data->id}", $supplier_data->name),
					anchor("products/info/{$product->id}", 'Info'),
					anchor("products/edit/{$product->id}", 'Edit'),
					anchor("products/delete/{$product->id}", 'Delete')
				);
			}
		}

		// send an optional message to the view
		if(isset($message))
			$data['message'] = $message;

		$data['insert_location'] = 'products/insert';
		$data['view_name'] = 'table_view';
		$data['page_title'] = 'Products';

		$this->load->view('includes/template', $data);
	}

	

	function insert($selected_supplier=NULL, $selected_type=NULL) 
	{

		$data['view_name'] = 'insert/product';
		$data['page_title'] = 'Insert Product';

		// Page load scipt args
		$data['onload'] = $this->_onload;

		// Models that will have selects generated
		$models = array('type', 'subtype', 'supplier');
		foreach($models as $model) {
			$data[$model] = $this->_generate_select($model . '_model');

			// If no select return for that model, return error 
			if(!$data[$model])
				return $this->index(0, 'Must enter types and subtypes first!');
		}


		// Generate JSON objects for JS
		$data['json_type'] = $this->_generate_json('type_model');
		$data['json_subtype'] = $this->_generate_json('subtype_model');

		// Check request method
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(!$this->_validate_form()) 
				return $this->load->view('includes/template', $data);

			// Data that will be inserted in to the DB
			$insert_data = array(
				'stock' => $this->input->post('stock'),
				'subtype_id' => $this->input->post('subtype'),
				'user_id' => $this->session->userdata('uid')
			);

			// Check if there is a conflict between records
			if($conflict_row = $this->product_model->checkConflict($insert_data['subtype_id'], 
				$insert_data['stock'])) {

				$product_data = $this->product_model->get_data_from_id($conflict_row->id);

				// Append insert on to current stock
				$insert_data['stock'] += $product_data->stock;

				// Update rather than insert, but use the modified insert data
				if(!$this->product_model->update_row($product_data->id ,$insert_data))
					db_error();

				return $this->index(0, "The item was found. The stock amount was added on to it!");

			} else {

				if(!$this->product_model->insert_row($insert_data))
					db_error();
			}

			return $this->index(0, 'Insert Sucess');

		} else {
			$this->load->view('includes/template', $data);	
		}
	}

	function edit($id = NULL)
	{
		// If script was accessed without an ID or if it is Invalid
		if(!isset($id) or !is_numeric($id))
			direct_error();

		// Generate selects
		$data['type'] = $this->_generate_select('type_model');
		$data['subtype'] = $this->_generate_select('subtype_model');
		$data['supplier'] = $this->_generate_select('supplier_model');

		$data['post_location'] = "products/edit/{$id}";
		$data['view_name'] = 'insert/product';
		$data['page_title'] = 'Edit Product';

		// Generate JSON for selects
		$data['json_type'] = $this->_generate_json('type_model');
		$data['json_subtype'] = $this->_generate_json('subtype_model');

		// Do not disable selects at load
		$data['disabled'] = FALSE;

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(!$this->_validate_form())
				return $this->load->view('includes/template', $data);

			$update_data = array(
				'stock' => $this->input->post('stock'),
				'subtype_id' => $this->input->post('subtype'),
				'user_id' => $this->session->userdata('uid') 
			);

			if(!$this->product_model->update_row($id, $update_data)) 
				db_error();

			$this->index(0, 'Edit Successful');

		} else {
			if(!$product_data = $this->product_model->get_data_from_id($id) or
				!$subtype_data = $this->subtype_model->get_data_from_id($product_data->subtype_id) or
				!$type_data = $this->type_model->get_data_from_id($subtype_data->type_id)) 
			{
				db_error();
			}

			$selected_subtype = $product_data->subtype_id;
			$selected_type = $subtype_data->type_id;
			$selected_supplier = $type_data->supplier_id;

			// Will tell the view which items are selected
			$data['form_values'] = array(
				'stock' => $product_data->stock,
				'type' => $selected_type,
				'subtype' => $selected_subtype,
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
		else if(!$product_data = $this->product_model->get_data_from_id($id) or
			!$subtype_data = $this->subtype_model->get_data_from_id($product_data->subtype_id) or
			!$type_data = $this->type_model->get_data_from_id($subtype_data->type_id) or
			!$supplier_data = $this->supplier_model->get_data_from_id($type_data->supplier_id) or
			!$user_data = $this->user_model->get_data_from_id($product_data->user_id))
		{
			db_error();
		}

		// Data that will be displayed to the info view
		$data['info_data'] = array(
			'Reference Number' => $product_data->id,
			'Stock Level' => $product_data->stock,
			'Type' => anchor("type/info/{$type_data->id}", $type_data->name),
			'Subtype' => anchor("subtype/info/{$subtype_data->id}", $subtype_data->name),
			'From Supplier' => anchor("supplier/info/{$supplier_data->id}", $supplier_data->name),
			'Date Changed' => $product_data->date_changed,
			'Entered By' => $user_data->first_name
		);

		// controller name. For use in the view
		$data['controller'] = 'products';

		$data['page_title'] = 'Product Info';

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
		else if(!$this->product_model->delete_row($id, $restore))
			db_error();

		$this->index(0, $restore ? 'Restore Successful' :'Delete Successful');

	}
}

/* End of file products.php */
/* Location: ./application/controllers/products.php */
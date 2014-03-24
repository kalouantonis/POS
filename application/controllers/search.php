<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 3/15/13
 * Time: 7:50 PM
 * Licenced under the GPL v3
 */

require_once 'utils.php';

class Search extends CI_Controller
{

	// What type of search
	private $_search_enum = array(
		'supplier',
		'product',
		'type',
		'subtype'
	);

	function __construct()
	{
		parent::__construct();

		// Profiling
		#$this->output->enable_profiler(TRUE);

		if(!$this->session->userdata('uid') or !$this->session->userdata('username')) {
			// Exit script if not logged in
			return redirect('home/index', 'location');
		}

		$this->load->model('search_model');
	}

	private function _validate_form()
	{
		$this->form_validation->set_rules('term', 'Search Term', 'required|trim');
		$this->form_validation->set_rules('search_type', 'Search Type', 'required|trim');
		// TODO: Write validation for integers when searching for reference

		return $this->form_validation->run();	
	}

	function index($message  = NULL) {
		$data['view_name'] = 'search';
		$data['page_title'] = 'Search';

		$data['message'] = isset($message) ? $message : NULL; // Check if message exists

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(!$this->_validate_form()) 
				return $this->load->view('includes/template', $data);	

			foreach($this->_search_enum as $search_type) {
				if($this->input->post('search_type') == $search_type) {
					$this->$search_type($this->input->post('term'));
					break; // Search type found, exit loop
				}
			}

		} else {
		
			return $this->load->view('includes/template', $data);
		}
	}

	function supplier($term) 
	{

		$column_names = array(
			'id',
			'name',
			'tel',
			'email',
			'purchase_area',
		);

		if(!$search_data = $this->search_model->by_table('suppliers', $term, $column_names)) {
			$data['message'] = 'No data found!';
			$data['view_name'] = 'search';
			$data['page_title'] = 'Search';
			return $this->load->view('includes/template', $data); // Script exits here
		}

		$data['headings'] = array('Ref', 'Name', 'Telephone Number', 
			'Email', 'Purchase Area', 'Deleted');
		$data['view_name'] = 'table_view';
		$data['page_title'] = 'Supplier Search';

		foreach($search_data as $supplier) {
			$deleted = 'No';
			$del_location = "suppliers/delete/{$supplier->id}";

			if($supplier->deleted) {
				$deleted = '<strong>Yes</strong>';
				$del_location .= '/true';
			}

			$data['table_data'][] = array(
				$supplier->id,
				$supplier->name,
				$supplier->tel,
				$supplier->email,
				$supplier->purchase_area,
				$deleted,
				anchor("suppliers/info/{$supplier->id}", 'Info'),
				anchor("suppliers/edit/{$supplier->id}", 'Edit'),
				anchor($del_location, $supplier->deleted ? 'Restore' : 'Delete')
			);
		}

		return $this->load->view('includes/template', $data);
	}

	function product($term) 
	{

		$this->load->model('subtype_model');
		$this->load->model('supplier_model');
		$this->load->model('type_model');


		// Will only search id's in products
		if(!$search_data = $this->search_model->by_table('products', $term, array('id', 'Stock'))) {
			$data['message'] = 'No data found!';
			$data['view_name'] = 'search';
			$data['page_title'] = 'Search';
			return $this->load->view('includes/template', $data); // Script exits here
		}

		$data['headings'] = array('Ref', 'Stock Level', 'Type', 
			'Subtype', 'From Supplier', 'Deleted');
		$data['view_name'] = 'table_view';
		$data['page_title'] = 'Product Search';

		foreach($search_data as $product) {
			if(!$subtype_data = $this->subtype_model->get_data_from_id($product->subtype_id) or
				!$type_data = $this->type_model->get_data_from_id($subtype_data->type_id) or
				!$supplier_data = $this->supplier_model->get_data_from_id($type_data->supplier_id)) 
			{
				db_error();
			}

			$deleted = 'No';
			$del_location = "products/delete/{$product->id}";

			if($product->deleted) {
				$deleted = '<strong>Yes</strong>';
				$del_location .= '/true';
			}

			$data['table_data'][] = array(
				$product->id,
				$product->stock,
				anchor("types/info/{$type_data->id}", $type_data->name),
				anchor("subtypes/info/{$subtype_data->id}", $subtype_data->name),
				anchor("suppliers/info/{$supplier_data->id}", $supplier_data->name),
				$deleted,
				anchor("products/info/{$product->id}", 'Info'),
				anchor("products/edit/{$product->id}", 'Edit'),
				anchor($del_location, $product->deleted ? 'Restore' : 'Delete')
			);
		}

		return $this->load->view('includes/template', $data);
	}


	function type($term) 
	{

		$this->load->model('supplier_model');

		if(!$search_data = $this->search_model->by_table('types', $term, array('id', 'name'))) {
			$data['message'] = 'No data found!';
			$data['view_name'] = 'search';
			$data['page_title'] = 'Type Search';
			return $this->load->view('includes/template', $data); // Script exits here
		} 
		$data['headings'] = array('Ref', 'Name', 'Related Supplier', 'Deleted');
		$data['view_name'] = 'table_view';
		$data['page_title'] = 'Product Search';

		foreach($search_data as $type) {

			if(!$supplier = $this->supplier_model->get_data_from_id($type->supplier_id))
				db_error();

			$deleted = 'No';
			$del_location = "types/delete/{$type->id}";

			if($type->deleted) {
				$deleted = '<strong>Yes</strong>';
				$del_location .= '/true';
			}

			$data['table_data'][] = array(
				$type->id,
				$type->name,
				anchor("suppliers/info/{$supplier->id}", $supplier->name),
				$deleted,
				anchor("types/info/{$type->id}", 'Info'),
				anchor("types/edit/{$type->id}", 'Edit'),
				anchor($del_location, $type->deleted ? 'Restore' : 'Delete')
			);
		}

		return $this->load->view('includes/template', $data);
	}

	function subtype($term)
	{
		$this->load->model('type_model');

		if(!$search_data = $this->search_model->by_table('subtypes', $term, array('id', 'name'))) {
			$data['message'] = 'No data found!';
			$data['view_name'] = 'search';
			$data['page_title'] = 'Search';
			return $this->load->view('includes/template', $data); // Script exits here
		} 
		$data['headings'] = array('Ref', 'Name', 'Related Type', 'Deleted');
		$data['view_name'] = 'table_view';
		$data['page_title'] = 'Subtype Search';

		foreach($search_data as $subtype) {
			if(!$type = $this->type_model->get_data_from_id($subtype->type_id))
				db_error();

			$deleted = 'No';
			$del_location = "subtypes/delete/{$subtype->id}";

			if($subtype->deleted) {
				$deleted = '<strong>Yes</strong>';
				$del_location .= '/true';
			}

			$data['table_data'][] = array(
				$subtype->id,
				$subtype->name,
				anchor("types/info/{$type->id}", $type->name),
				$deleted,
				anchor("subtypes/info/{$subtype->id}", 'Info'),
				anchor("subtypes/edit/{$subtype->id}", 'Edit'),
				anchor($del_location, $subtype->deleted ? 'Restore' : 'Delete')
			);
		}

		return $this->load->view('includes/template', $data);

	}

}
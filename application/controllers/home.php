<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 3/9/13
 * Time: 3:12 PM
 * Licenced under the GPL v3
 */

// Inlcudes db_error()
require_once 'utils.php';

class Home extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		// Profiling
		#$this->output->enable_profiler(TRUE);
	}

	private function _validate_form()
	{
		// Validate input from login form (views/login)
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');

		// Will return false if validation failed
		return $this->form_validation->run();
	}

	function index($message = NULL) {

		// Add message to home view if it exists
		$data['message'] = isset($message) ? $message : NULL;

		// Check if user is logged in,
		if(!$this->session->userdata('uid') or !$this->session->userdata('username')) {
			$this->load->view('login', $data); // Will load the login page manualy

		} else {
			// User is logged in, load home view normally
			$data['page_title'] = 'Home';
			$data['view_name'] = 'home';
			$this->load->view('includes/template', $data);
		}
	}

	function login()
	{
		$this->load->model('user_model');

		if(!$this->_validate_form())
			// Val failed, reload login view
			return $this->load->view('login');

		// Check that the username and password is valid
		if(!$user = $this->user_model->validate($this->input->post('username'),
				$this->input->post('password'))) {
			$data['error'] = 'Incorrect credentials!';
			return $this->load->view('login', $data);
		}

		// Set the session data
		$userdata = array(
			'uid' => $user->id,
			'username' => $user->username,
			'first_name' => $user->first_name
		);

		$this->session->set_userdata($userdata);

		return $this->index('Login Successful');
	}

	function logout()
	{
		// Check if user is logged in
		if(!$this->session->userdata('uid') or !$this->session->userdata('username')) {
			return $this->index('You must be logged in to view this page!');
		}

		// Data to be removed from session
		$userdata = array(
			'username',
			'uid',
			'first_name'
		);
		$this->session->unset_userdata($userdata);
		$this->session->sess_destroy();

		return $this->index('Logout Sucessful, come again!');

	}

	function change_password()
	{
		if(!$this->session->userdata('uid') or !$this->session->userdata('username')) {
			// Exit script if not logged in
			return redirect('home/index', 'location');
		}

		$data['view_name'] = 'change_password';
		$data['page_title'] = 'Change Password';

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Manually check form val
			$this->form_validation->set_rules('password', 'Password', 'required|trim');
			// matches[password] checks that the field data matches the data in password form
			$this->form_validation->set_rules('confirm', 'Confirmation', 'required|trim|matches[password]');
			// Set custom message
			$this->form_validation->set_message('matches', 'The two password fields do not match');

			if(!$this->form_validation->run())
				return $this->load->view('includes/template', $data);

			$this->load->model('user_model');

			if(!$this->user_model->change_password($this->session->userdata('username'),
					$this->input->post('password')))
				return db_error(); 

			return $this->index('Password Changed Successfully');

		} else {
			$this->load->view('includes/template', $data);
		}

	}
}
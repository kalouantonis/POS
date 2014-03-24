<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Includes MY_Model CLASS
require_once "my_model.php";

class User_Model extends MY_Model
{
	function __construct()
	{
		// Call MY_Model constructor
		// Send table name
		parent::__construct('users');
	}

	function validate($username, $password)
	{
		$query = $this->db->query("SELECT * FROM `users` WHERE `username`='{$username}' 
			AND `password`=PASSWORD('{$password}')");

		$data = FALSE;

		if($query->num_rows() > 0)
			$data = $query->row();

		// Will return FALSE if no rows were found.
		return $data;

	}

	function change_password($username, $password) 
	{
		// Will return false if that user or password change fails.

		return $this->db->query("UPDATE `users` 
			SET `password`=PASSWORD('{$password}') 
			WHERE `username`='{$username}'");
	}
}
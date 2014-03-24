<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "my_model.php";

class Supplier_Model extends MY_Model
{
	function __construct()
	{
		parent::__construct('suppliers');
	}
}
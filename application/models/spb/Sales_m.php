<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sales_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	public function getSales() {
		$query = $this->db->get('sales');
		return $query;
	}
}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Areacustomer_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	public function getAreacustomer() {
		$query = $this->db->get('area_customer');
		return $query;
	}
}

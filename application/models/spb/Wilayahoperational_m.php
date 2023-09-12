<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Wilayahoperational_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	public function getWilayahoperational() {
		$query = $this->db->get('wilayah_operational');
		return $query;
	}
}

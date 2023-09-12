<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Alatgps_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}


	public function getAlatgps() {
		$query = $this->db->order_by("id='5'", 'DESC')->get('alat_gps');
		return $query;
	}
}

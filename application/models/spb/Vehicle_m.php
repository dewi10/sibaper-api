<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vehicle_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}
	
	public function getVehicle($perpage, $start, $sort_by, $sort_dir,$query) {
		if($perpage > 100) {
			$perpage = 100;
		}	
		if ($query != '') {
			$this->db->like('name_vehicle', $query);
			$this->db->or_like('metadata', $query);
		}
		$this->db->order_by($sort_by, $sort_dir);
		$query = $this->db->get('vehicle', $perpage, $start);
		return $query;
	}
}

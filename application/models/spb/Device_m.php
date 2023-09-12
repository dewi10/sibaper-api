<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Device_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	public function getDevices() {
		$query = $this->db->get('device');
		return $query;
	}

	public function getFitur() {
		$query = $this->db->order_by('id', 'DESC')->get('fitur');
		return $query;
	}
}

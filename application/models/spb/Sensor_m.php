<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sensor_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	public function getSensors() {
		$query = $this->db->order_by('id', 'DESC')->get('sensor');
		return $query;
	}
}

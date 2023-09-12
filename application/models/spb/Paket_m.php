<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Paket_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	public function getPaket() {
		$query = $this->db->order_by('id', 'DESC')->get('paket');
		return $query;
	}
}

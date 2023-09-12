<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Typelayanan_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	public function getTypeLayanan() {
		$query = $this->db->get('type_layanan');
		return $query;
	}
}

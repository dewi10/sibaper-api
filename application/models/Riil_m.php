<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Riil_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	public function getRiil() {
		$query = $this->db->get('riil');
		return $query;
	}

	public function getRiilIds($no_spt, $id_personel)
	{
			$this->db->where('no_spt', $no_spt);
			$this->db->where('id_personel', $id_personel);
			$query = $this->db->get('riil');
			return $query;
	}

}

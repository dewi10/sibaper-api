<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Representasi_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	public function getRepresentasi() {
		$query = $this->db->get('representasi');
		return $query;
	}

	public function getRepresentasiIds($no_spt, $id_personel)
	{
			$this->db->where('no_spt', $no_spt);
			$this->db->where('id_personel', $id_personel);
			$query = $this->db->get('representasi');
			return $query;
	}

}

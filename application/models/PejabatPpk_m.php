<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class PejabatPpk_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}


	public function getPejabatPpk($perpage, $start, $sort_by, $sort_dir, $query, $other) {
		if($perpage > 100) {
			$perpage = 100;
		}


		if ($other != null && $other != '') {
			$paramWhere = json_decode($other, true);
			foreach($paramWhere as $x => $x_value) {
				if($x_value == "" || $x_value == null) {
					unset($paramWhere[$x]);
				}
			}

			$this->db->where($paramWhere);
		}

		if ($query != '') {
			$query = strtolower($query);
			$this->db->group_start(); 
			$this->db->like('LOWER(concat(nip_pejabat_ppk,\' \',nama_pejabat_ppk,\' \',jabatan_pejabat_ppk,\' \',status))', $query);
			$this->db->group_end();
		}

		$this->db->select('pejabat_ppk.id, nama_pejabat_ppk, nip_pejabat_ppk, jabatan_pejabat_ppk, status, nama_personel');
		$this->db->from('pejabat_ppk');
		$this->db->join('personel', 'personel.id = pejabat_ppk.nama_pejabat_ppk', 'left');
		$query = $this->db->order_by($sort_by, $sort_dir);
		$query = $this->db->get();

		return $query;
	}

}

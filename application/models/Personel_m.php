<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Personel_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}


	public function getPersonel($perpage, $start, $sort_by, $sort_dir, $query, $other) {
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
			$this->db->like('LOWER(concat(nip_personel,\' \',nama_personel,\' \',jabatan_personel,\' \',golongan_personel))', $query);
			$this->db->group_end();
		}

		$this->db->order_by($sort_by, $sort_dir);
		$query = $this->db->get('personel', $perpage, $start);
		return $query;
	}

}

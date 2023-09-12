<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Perincian_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}


	public function getPerincian($perpage, $start, $sort_by, $sort_dir, $query, $other) {
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
			$this->db->like('LOWER(concat(jumlah_hari,\' \',nominal))', $query);
			$this->db->group_end();
		}

		$this->db->order_by($sort_by, $sort_dir);
		$query = $this->db->get('perincian_biaya', $perpage, $start);
		return $query;
	}


	public function getPerincianIds($no_spt, $id_personel)
	{
			$this->db->where('no_spt', $no_spt);
			$this->db->where('id_personel', $id_personel);
			$query = $this->db->get('perincian_biaya');
			return $query;
	}

	public function getPerincianId($no_spt)
	{
			$this->db->where('no_spt', $no_spt);
			$query = $this->db->get('perincian_biaya');
			return $query;
	}

}

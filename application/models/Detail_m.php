<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Detail_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	// public function getVehicle($perpage, $start, $sort_by, $sort_dir,$query) {
	// 	if($perpage > 100) {
	// 		$perpage = 100;
	// 	}	
	// 	if ($query != '') {
	// 		$this->db->like('name_vehicle', $query);
	// 		$this->db->or_like('metadata', $query);
	// 	}
	// 	$this->db->order_by($sort_by, $sort_dir);
	// 	$query = $this->db->get('vehicle', $perpage, $start);
	// 	return $query;
	// }


	public function getDetail($perpage, $start, $sort_by, $sort_dir, $query, $other) {
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
			$this->db->like('LOWER(concat(max,\' \',uraian,\' \',pagu,\' \',realisasi,\' \',tahun))', $query);
			$this->db->group_end();
		}

		$this->db->order_by($sort_by, $sort_dir);
		$query = $this->db->get('detail', $perpage, $start);
		return $query;
	}


	public function checkNoSpt($no_spt) {
    $this->db->where('no_spt', $no_spt);
		$this->db->order_by('id', 'asc'); 
    return $this->db->get('detail');
}


}

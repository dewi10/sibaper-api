<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Spt_m extends CI_Model {
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


	public function getSpt($perpage, $start, $sort_by, $sort_dir, $query, $other) {
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
			$this->db->like('LOWER(concat(no_spt,\' \',username))', $query);
			$this->db->group_end();
		}

		$this->db->select('spt.*, username');
		$this->db->from('spt'); 
		$this->db->join('user', 'user.id = spt.create_by', 'left'); 

		$this->db->order_by($sort_by, $sort_dir);

		$query = $this->db->get(); 
		return $query;
	}

	public function deleteSptWithDetail($spt_id) {
    $this->db->select('no_spt');
    $this->db->where(array('id' => $spt_id));
    $getSpt = $this->db->get('spt')->row_array();
    $noSpt = $getSpt['no_spt'];

    // START QUERY FOR DELETE RELATED RECORDS
    $this->db->where('no_spt', $noSpt);
    $this->db->delete('detail');
    $this->db->where('no_spt', $noSpt);
    $this->db->delete('tiket');
    $this->db->where('no_spt', $noSpt);
    $this->db->delete('harian');
    $this->db->where('no_spt', $noSpt);
    $this->db->delete('riil');
    $this->db->where('no_spt', $noSpt);
    $this->db->delete('hotel');
    $this->db->where('no_spt', $noSpt);
    $this->db->delete('perincian_biaya');
    $this->db->where('no_spt', $noSpt);
    $this->db->delete('dana_taktis');
    // START QUERY FOR DELETE CUSTOMER
    $this->db->where('id', $spt_id);
    $this->db->delete('spt');

    return $this->db->affected_rows();
}


public function checkNoSpt($no_spt) {
	$query = strtolower($no_spt);
	$this->db->like('LOWER(no_spt)', $query);
	return $this->db->get("spt");
}


public function getSptIds($no_spt)
{
		$this->db->where('no_spt', $no_spt);
		$query = $this->db->get('spt');
		return $query;
}


}

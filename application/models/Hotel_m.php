<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hotel_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}


	public function getHotel($perpage, $start, $sort_by, $sort_dir, $query, $other) {
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
			$this->db->like('LOWER(concat(nama_penginapan,\' \',metadata))', $query);
			$this->db->group_end();
		}

		$this->db->order_by($sort_by, $sort_dir);
		$query = $this->db->get('hotel', $perpage, $start);
		return $query;
	}

	public function getHotelIds($no_spt, $id_personel)
	{
			$this->db->where('no_spt', $no_spt);
			$this->db->where('id_personel', $id_personel);
			$query = $this->db->get('hotel');
			return $query;
	}

	public function getHotelId($no_spt)
	{
			$this->db->where('no_spt', $no_spt);
			$query = $this->db->get('hotel');
			return $query;
	}


}

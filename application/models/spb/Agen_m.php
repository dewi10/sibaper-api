<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Agen_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	public function getAgen($perpage, $start, $sort_by, $sort_dir, $query, $other) {
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

			if (isset($paramWhere['sales']))
			{
				$sales = array_map('trim', array_filter(explode(',', $paramWhere['sales'])));
				$this->db->where_in('sales', $sales);
				unset($paramWhere['sales']);
				
			}

			$this->db->where($paramWhere);
		}

		if ($query != '') {
			$query = strtolower($query);
			$this->db->group_start(); 
			$this->db->like('LOWER(concat(name,\' \',telp_agen,\' \',no_rekening,\' \',alamat_agen,\' \',fee))', $query);
			$this->db->group_end();
		}

		$this->db->order_by($sort_by, $sort_dir);
		$query = $this->db->get('agen', $perpage, $start);
		return $query;
	}

}

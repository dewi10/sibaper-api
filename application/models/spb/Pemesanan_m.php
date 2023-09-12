<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pemesanan_m extends CI_Model {
	function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, Accept,charset,boundary,Content-Length');
		parent::__construct();
	}

	public function getPemesanan($perpage, $start, $sort_by, $sort_dir, $query, $other) {
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

			if (isset($paramWhere['pemesan_id']))
			{
				$pemesan_id = array_map('trim', array_filter(explode(',', $paramWhere['pemesan_id'])));
				$this->db->where_in('pemesan_id', $pemesan_id);
				unset($paramWhere['pemesan_id']);
				
			}

			// $id = array(1, 2, 3, 4, 5, 6);
			// $this->db->where_in('pemesan_id', $id);

			$this->db->where($paramWhere);
		}

		
		// if ($query != '') {
		// 	$query = strtolower($query);
		// 	$this->db->group_start();
		// 	$this->db->like('LOWER(concat(agen.name,\' \',customer.name,\' \',po_number,\' \',tanggal_pemesanan,\' \',status_invoice))', $query);
		// 	$this->db->group_end();
			
		// }

		if ($query != '') {
	
			$this->db->where("(
			`agen`.`name` LIKE  '%$query%' OR
			`customer`.`name` LIKE  '%$query%' OR
			`area_customer`.`name` LIKE  '%$query%' OR
			`pemesanan`.`po_number` LIKE  '%$query%' OR
			`pemesanan`.`tanggal_pemesanan` LIKE  '%$query%' OR
			`user`.`name` LIKE  '%$query%' OR
			`alat_gps`.`name` LIKE  '%$query%'
			)");
			// $this->db->like('agen.name', $query);
			// $this->db->or_like('customer.name', $query);
			// $this->db->or_like('area_customer.name', $query);
			// $this->db->or_like('pemesanan.po_number', $query);
			// $this->db->or_like('pemesanan.tanggal_pemesanan', $query);
			// $this->db->or_like('user.name', $query);
			// $this->db->or_like('pic.name_pic', $query);
		}

		$this->db->order_by($sort_by, $sort_dir);
		$this->db->join('customer', 'customer.id = pemesanan.customer_id', 'left');
		$this->db->join('agen', 'agen.id = pemesanan.agen', 'left'); 
		$this->db->join('user', 'user.id = pemesanan.pemesan_id', 'left');
		$this->db->join('alat_gps', 'alat_gps.id = pemesanan.list_alat', 'left');
		$this->db->join('area_customer', 'area_customer.id = pemesanan.area_customer', 'left');
		$this->db->select('pemesanan.*, customer.name, agen.name as aname,user.name as aname,alat_gps.name as aname,area_customer.name');
		$this->db->from('pemesanan');
		$this->db->limit($perpage, $start);
	
		$query = $this->db->get();
		return $query;
		
	}

	public function getById($id, $table) {
		$this->db->where(array("id"=>$id));
		$query = $this->db->get($table);
		return $query;
	}
}

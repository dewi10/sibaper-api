<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pic_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}


	public function getPic($perpage, $start, $sort_by, $sort_dir, $query, $other) {
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
			$this->db->like('LOWER(concat(customer_name,\' \',email_pic,\' \',name_pic,\' \',no_phone,\' \',pic_type))', $query);
			$this->db->group_end();
		}

		$this->db->order_by($sort_by, $sort_dir);
		// $this->db->join('customer', 'customer.name = pic.customer_name', 'left');
		// $this->db->select('pic.*, customer.name');
		// $this->db->from('pic');
		// $this->db->limit($perpage, $start);
		// $query = $this->db->get();


		$query = $this->db->get('pic', $perpage, $start);
		return $query;
	}


	// public function checkUsername($name_pic) {
	// 	$query = strtolower($name_pic);
	// 	$this->db->like('LOWER(name)', $query);
	// 	// $this->db->where('name', $customer_name);
	// 	return $this->db->where('customer_name', $customer_name)->get("pic");
	// }

	public function checkUsername($customer, $name_pic) {
		$lowerCustomer = strtolower($customer);
		$lowerPIC = strtolower($name_pic);
		$this->db->where('LOWER(customer_name)', $lowerCustomer);
		$this->db->like('LOWER(name_pic)', $lowerPIC);
		return $this->db->get("pic");
	}

	
}
	
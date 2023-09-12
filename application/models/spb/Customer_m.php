<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Customer_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	public function getCustomers($perpage, $start, $sort_by, $sort_dir, $query, $other) {
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

		// if ($query != '') {
		// 	$query = strtolower($query);
		// 	$this->db->group_start();
		// 	$this->db->like('LOWER(concat(name,\' \',email,\' \',address,\' \',no_npwp,\' \',no_ktp,\' \',no_telephone,\' \',fax,\' \',pic_ids,\' \',type))', $query);
		// 	$this->db->group_end();
		// }
	 if ($query != '') {
			// $this->db->like('customer.name', $query);
			// $this->db->or_like('customer.email', $query);
			// $this->db->or_like('customer.address', $query);
			// $this->db->or_like('customer.no_npwp', $query);
			// $this->db->or_like('customer.no_ktp', $query);
			// $this->db->or_like('customer.no_telephone', $query);


			$this->db->where("(
				`customer`.`name` LIKE  '%$query%' OR
				`customer`.`email` LIKE  '%$query%' OR
				`customer`.`address` LIKE  '%$query%' OR
				`customer`.`no_npwp` LIKE  '%$query%' OR
				`customer`.`no_ktp` LIKE  '%$query%' OR
				`customer`.`no_telephone` LIKE  '%$query%' 
				)");
			
		}

		$this->db->order_by($sort_by, $sort_dir);
		$query = $this->db->get('customer', $perpage, $start);
		return $query;

		// if ($query != '') {
	
		// 	$this->db->like('agen.name', $query); 
		// 	$this->db->or_like('customer.name', $query);
		// 	$this->db->or_like('customer.email', $query);
		// 	$this->db->or_like('customer.address', $query);
		// 	$this->db->or_like('customer.no_npwp', $query);
		// 	$this->db->or_like('customer.no_ktp', $query);
		// 	$this->db->or_like('customer.no_telephone', $query);
		// }

		// $this->db->order_by($sort_by, $sort_dir); 
		// $this->db->join('agen', 'agen.id = customer.agen', 'left');  
		// $this->db->select('customer.*, agen.name as aname');
		// $this->db->from('customer');
		// $this->db->limit($perpage, $start);
		

		// $query = $this->db->get();
		// return $query;
 
	}

	public function getCustomerName($name) {
    $data = $this->db->where(array("name"=>$name));
    return $this->db->get('customer');
}

public function checkUsername($customer_name) {
	$query = strtolower($customer_name);
	$this->db->like('LOWER(name)', $query);
	// $this->db->where('name', $customer_name);
	return $this->db->get("customer");
}

	public function getPic($customer_name) {
		$this->db->where("customer_name", $customer_name);
		$this->db->select("name_pic");
		return $this->db->get("pic");
	}

	public function deleteCustomerWithPIC($cust_id) {
		$this->db->select('name');
		$this->db->where(array('id'=>$cust_id));
		$getCustomer = $this->db->get('customer')->row_array();
		$customerName = $getCustomer['name'];

		// START QUERY FOR DELETE PIC
		$this->db->where('customer_name', $customerName);
		$this->db->delete('pic');

		// START QUERY FOR DELETE CUSTOMER
		$this->db->where('id', $cust_id);
		$this->db->delete('customer');

		return $this->db->affected_rows();
	}

}

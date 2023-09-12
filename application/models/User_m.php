<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
		
	}

	public function getAllUsers() {
		$query = $this->db->order_by('id', 'DESC')->get('user');
		return $query;
	}

	public function getUsers() {
		// $query = $this->db->get('user');
		$where = "status_user='1' OR status_user='0'";
		// $query = $this->db->order_by('id', 'DESC')->get('user');
		$query = $this->db->order_by('id', 'DESC')->where($where)->get('user');
		return $query;
	}

	public function getUsersPemesan() {
		$where = "type='1' AND status_user='1' OR type='2' AND status_user='1' OR type='5' AND status_user='1'";
		$query = $this->db->where($where)->get('user');
		// $query = $this->db->where(array( "type"=>1, "type"=>2 ))->get('user');
		return $query;
	}

	
	public function getUsersSales() {
		$where = "type='2' AND status_user='1'";
		$query = $this->db->where($where)->get('user');
		// $query = $this->db->where(array( "type"=>1, "type"=>2 ))->get('user');
		return $query;
	}

	public function checkUsernameOrEmail($username, $email) {
		$this->db->where('username', $username);
		$this->db->or_where('email', $email);
		$query = $this->db->get('user');
		return $query;
	}

	public function getWilayah() {
		$query = $this->db->get('wilayah_sales');
		return $query;
	}


	public function getDashboard($perpage, $start, $sort_by, $sort_dir, $query, $other) {
		if($perpage > 100) {$perpage = 100;}


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
			$this->db->like('LOWER(concat(username))', $query);
			$this->db->group_end();
		}
	

		$this->db->select('user.id, username, anggaran, name, nama_personel, total_hotel, grand_total, tiket.total, tiket.create_by, hotel.create_by, perincian_biaya.create_by, total_taktis');
		$this->db->from('user');
		$this->db->join('personel', 'personel.id = user.name', 'left');
		$this->db->join('tiket', 'tiket.create_by = user.id', 'left');
		$this->db->join('perincian_biaya', 'perincian_biaya.create_by = user.id', 'left');
		$this->db->join('hotel', 'hotel.create_by = user.id', 'left');
		$this->db->join('dana_taktis', 'dana_taktis.create_by = user.id', 'left');

		$this->db->order_by($sort_by, $sort_dir);
	
		$query = $this->db->get();
		return $query;
	}

}

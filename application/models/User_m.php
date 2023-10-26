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
    if ($perpage > 100) {
        $perpage = 100;
    }

    if ($other != null && $other != '') {
        $paramWhere = json_decode($other, true);
        foreach ($paramWhere as $x => $x_value) {
            if ($x_value == "" || $x_value == null) {
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

    $this->db->select('user.id, username, anggaran, name, nama_personel');
    $this->db->select('(SELECT SUM(total_hotel) FROM hotel WHERE hotel.create_by = user.id) AS total_hotel', false);
    $this->db->select('(SELECT SUM(grand_total) FROM perincian_biaya WHERE perincian_biaya.create_by = user.id) AS grand_total', false);
    $this->db->select('(SELECT SUM(total) FROM tiket WHERE tiket.create_by = user.id) AS total_tiket', false);
    $this->db->select('(SELECT SUM(total_taktis) FROM dana_taktis WHERE dana_taktis.create_by = user.id) AS total_taktis', false);
    $this->db->from('user');
    $this->db->join('personel', 'personel.id = user.name', 'left');
    $this->db->order_by($sort_by, $sort_dir);

    $query = $this->db->get();
    $results = $query->result_array();

    // Hitung total semua kolom untuk setiap pengguna (per-user)
		foreach ($results as &$result) {
			$result['totalall'] = $result['total_hotel'] + $result['grand_total'] + $result['total_tiket'] + $result['total_taktis'];
	
			// Periksa apakah totalall adalah nol atau kosong
			if ($result['totalall'] == 0) {
					$result['daya_serap'] = 0;
			} else {
					// Hitung daya serap dalam persentase dan bulatkan ke 2 desimal
					$result['daya_serap'] = round(($result['totalall'] / $result['anggaran']) * 100, 2);

			}
	}

// 	foreach ($results as &$result) {
// 		$result['totalall'] = $result['total_hotel'] + $result['grand_total'] + $result['total_tiket'] + $result['total_taktis'];

// 		// Hitung daya serap dalam persentase dan bulatkan ke 2 desimal
// 		$result['daya_serap'] = round(($result['anggaran'] - $result['totalall']) / $result['anggaran'] * 100, 2);
// }
	

    return $results;
}









}

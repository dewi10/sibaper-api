<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Import_model extends CI_Model {
	public function __construct() {
		$this->load->database();
	}
	public function insert_multiple($data){
		$this->db->insert_batch('customer', $data);
	}
	public function insert_multiple2($data){
		$this->db->insert_batch('pemesanan', $data);
	}
	public function insert_multiple3($data){
		$this->db->insert_batch('agen', $data);
	}
	public function insert_multiple4($data){
		$this->db->insert_batch('pic', $data);
	}
	
}
?>

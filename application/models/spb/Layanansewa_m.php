<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layanansewa_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	public function getLayanansewa() {
		$query = $this->db->get('layanansewa');
		return $query;
	}
}

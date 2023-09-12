<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
		
	}

	public function checkAuth($username, $pass) {
		$this->db->where(array("username"=>$username, "password"=>$pass, "status_user"=>1));
		return $this->db->get("user");
	}

	public function checkAuth1($username, $pass) {
		$this->db->where(array("username"=>$username, "password"=>$pass, "status_user"=>0));
		return $this->db->get("user");
	}

	
	public function checkAuth2($username, $pass) {
		$this->db->where(array("username"=>$username, "password"=>$pass, "status_user"=>2));
		return $this->db->get("user");
	}
}

?>

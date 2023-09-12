<?php 

class Api_user extends Api {
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('User_m', 'Auth_m'));
	}


	public function getDashboard()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if($this->pagingParamCheck($jsonReq)) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $this->User_m->getDashboard($jsonReq['limit'], $jsonReq['offset'], $jsonReq['sort_by'], $jsonReq['sort_dir'], $jsonReq['query'], $jsonReq['other'])->result_array();
			$res = array();
			if (!empty($data)) {
				foreach ($data as $row) {
					foreach ($row as $key => $value) {
						$row[$key] = trim($row[$key]);
					}
					$res[] = $row;
				}
			}

			$resp['status'] = "OK";
			$resp['description'] = "Success";
			$resp['total_data'] = count($res);
			$resp['data'] = $res;
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}
		echo json_encode($resp);
		return;
	}

	public function getAllUsers() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $this->User_m->getAllUsers()->result_array();
			$res = array();
			if(!empty($data)) {
				foreach ($data as $row) {
					foreach ($row as $key => $value) {
						// if($key == "password") {
						// 	unset($row[$key]);
						// } 
						if ($key == "metadata") {
							$row[$key] = json_decode($row[$key]);
						} else {
							$row[$key] = trim($row[$key]);
						}
					}
					$res[] = $row;
				}
			}

			$resp['status'] = "OK";
			$resp['description'] = "Success";
			$resp['total_data'] = count($res);
			$resp['data'] = $res;
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}
		echo json_encode($resp);
		return;
	}

	public function getUsers() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $this->User_m->getUsers()->result_array();
			$res = array();
			if(!empty($data)) {
				foreach ($data as $row) {
					foreach ($row as $key => $value) {
						if($key == "password") {
							unset($row[$key]);
						} elseif ($key == "metadata") {
							$row[$key] = json_decode($row[$key]);
						} else {
							$row[$key] = trim($row[$key]);
						}
					}
					$res[] = $row;
				}
			}

			$resp['status'] = "OK";
			$resp['description'] = "Success";
			$resp['total_data'] = count($res);
			$resp['data'] = $res;
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}
		echo json_encode($resp);
		return;
	}

	public function getUsersPemesan() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $this->User_m->getUsersPemesan()->result_array();
			$res = array();
			if(!empty($data)) {
				foreach ($data as $row) {
					foreach ($row as $key => $value) {
						if($key == "password") {
							unset($row[$key]);
						} elseif ($key == "metadata") {
							$row[$key] = json_decode($row[$key]);
						} else {
							$row[$key] = trim($row[$key]);
						}
					}
					$res[] = $row;
				}
			}

			$resp['status'] = "OK";
			$resp['description'] = "Success";
			$resp['total_data'] = count($res);
			$resp['data'] = $res;
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}
		echo json_encode($resp);
		return;
	}

	
	public function getUsersSales() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $this->User_m->getUsersSales()->result_array();
			$res = array();
			if(!empty($data)) {
				foreach ($data as $row) {
					foreach ($row as $key => $value) {
						if($key == "password") {
							unset($row[$key]);
						} elseif ($key == "metadata") {
							$row[$key] = json_decode($row[$key]);
						} else {
							$row[$key] = trim($row[$key]);
						}
					}
					$res[] = $row;
				}
			}

			$resp['status'] = "OK";
			$resp['description'] = "Success";
			$resp['total_data'] = count($res);
			$resp['data'] = $res;
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}
		echo json_encode($resp);
		return;
	}

	public function getUserById() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$res = array();
			$data = $this->Api_m->getById($id, 'user')->row_array();
			// print_r($data);die;
			if(!empty($data)) {
				unset($data['password']);
				$data['metadata'] = json_decode($data['metadata']);
				$res = $data;
			}
			
			$resp['status'] = "OK";
			$resp['description'] = "Success";
			$resp['data'] = $res;
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	public function inputUser() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('username', 'password', 'email', 'name','type','metadata', 'anggaran');

			$formatValid = true;

			if(count($jsonReq['data']) != count($params)) {
				$formatValid = false;
			} else {
				foreach ($jsonReq['data'] as $key => $value) {
					if(!in_array($key, $params)) {
						$formatValid = false;
						break;
					}
				}
			}

			if (!$formatValid) {
				$resp['status'] = "NOK";
				$resp['description'] = "Invalid Format";
	
				echo json_encode($resp);
				return;
			}

			// Input sensor
			$ins = $jsonReq['data'];
			$this->Api_m->insertData($ins, 'user');

			$resp['status'] = "OK";
			$resp['description'] = "User Tersimpan";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";	
		}

		echo json_encode($resp);
		return;
	}

	public function registerUser() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		$params = array('username', 'password', 'email', 'name','type','metadata');

		$formatValid = true;

		if(count($jsonReq) != count($params)) {
			$formatValid = false;
		} else {
			foreach ($jsonReq as $key => $value) {
				if(!in_array($key, $params)) {
					$formatValid = false;
					break;
				}
			}
		}

		if (!$formatValid) {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";

			echo json_encode($resp);
			return;
		}

		$userExist = $this->User_m->checkUsernameOrEmail($jsonReq['username'], $jsonReq['email'])->row_array();
		if(!empty($userExist)) {
			$resp['status'] = "NOK";
			$resp['description'] = "User username or user email is already exist";

			echo json_encode($resp);
			return;
		}

		$ins = $jsonReq;
		$this->Api_m->insertData($ins, 'user');

		$resp['status'] = "OK";
		$resp['description'] = "User Tersimpan";

		echo json_encode($resp);
		return;
	}

	public function updateUser() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 4) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('username', 'password', 'email', 'name','type','metadata','status_user', 'anggaran');
			$formatValid = true;
			foreach ($jsonReq['data'] as $key => $value) {
				if(!in_array($key, $params)) {
					$formatValid = false;
					break;
				}
			}

			if (!$formatValid) {
				$resp['status'] = "NOK";
				$resp['description'] = "Invalid Format";
	
				echo json_encode($resp);
				return;
			}

			$data = $jsonReq['data'];
			$where = array("id"=>$jsonReq['id']);
			$this->Api_m->updateData($data, $where, 'user');

			$resp['status'] = "OK";
			$resp['description'] = "User Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";	
		}

		echo json_encode($resp);
		return;
	}
	

	public function deleteUser() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$this->Api_m->deleteData($id, 'user');

			$resp['status'] = "OK";
			$resp['description'] = "User Deleted";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";	
		}

		echo json_encode($resp);
		return;
	}
	public function getWilayah() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $this->User_m->getWilayah()->result_array();
			$res = array();
			if(!empty($data)) {
				foreach ($data as $row) {
					foreach ($row as $key => $value) {
						if ($key == "metadata") {
							$row[$key] = json_decode($row[$key]);
						} else {
							$row[$key] = trim($row[$key]);
						}
					}
					$res[] = $row;
				}
			}

			$resp['status'] = "OK";
			$resp['description'] = "Success";
			$resp['total_data'] = count($res);
			$resp['data'] = $res;
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}
		echo json_encode($resp);
		return;
	}

	public function inputWilayah() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('name', 'metadata');

			$formatValid = true;

			if(count($jsonReq['data']) != count($params)) {
				$formatValid = false;
			} else {
				foreach ($jsonReq['data'] as $key => $value) {
					if(!in_array($key, $params)) {
						$formatValid = false;
						break;
					}
				}
			}

			if (!$formatValid) {
				$resp['status'] = "NOK";
				$resp['description'] = "Invalid Format";
	
				echo json_encode($resp);
				return;
			}

			// Input wilayah
			$ins = $jsonReq['data'];
			$this->Api_m->insertData($ins, 'wilayah_sales');

			$resp['status'] = "OK";
			$resp['description'] = "Wilayah Sales Tersimpan";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";	
		}

		echo json_encode($resp);
		return;
	}

	public function getWilayahById() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$res = array();
			$data = $this->Api_m->getById($id, 'wilayah_sales')->row_array();
			if(!empty($data)) {
				$data['metadata'] = json_decode($data['metadata']);
				$res = $data;
			}
			
			$resp['status'] = "OK";
			$resp['description'] = "Success";
			$resp['data'] = $res;
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	public function updateWilayah() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 4) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $jsonReq['data'];
			$where = array("id"=>$jsonReq['id']);
			$this->Api_m->updateData($data, $where, 'wilayah_sales');

			$resp['status'] = "OK";
			$resp['description'] = "Wilayah Sales Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";	
		}

		echo json_encode($resp);
		return;
	}

	public function deleteWilayah() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$this->Api_m->deleteData($id, 'wilayah_sales');

			$resp['status'] = "OK";
			$resp['description'] = "Wilayah Sales  Deleted";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";	
		}

		echo json_encode($resp);
		return;
	}
}


?>

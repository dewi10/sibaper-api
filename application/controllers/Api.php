<?php

// class Api extends CI_Controller {
// 	public function __construct()
// 	{
// 		parent::__construct();
// 		$this->load->model(array('Api_m', 'Auth_m'));
// 		// $this->load->helper('new_helper');
// 	}

// 	protected function haveAccess($username, $password)
// 	{
// 		if (!empty($this->Auth_m->checkAuth($username, $password)->result_array())) {
// 			return true;
// 		} else {
// 			$data['status'] = "NOK";
// 			$data['description'] = "Failed Authentication";
// 			echo json_encode($data);
// 			die();
// 		}
// 	}

// 	/**
// 	 * API FOR READ
// 	 */

// 	 // READ ALL ROW
// 	public function getSensors() {
// 		$jsonReq = json_decode($this->input->raw_input_stream, true);
// 		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2) {
// 			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
// 			$data = $this->Api_m->getSensors()->result_array();
// 			$res = array();
// 			if(!empty($data)) {
// 				foreach ($data as $row) {
// 					foreach ($row as $key => $value) {
// 						$row[$key] = trim($row[$key]);
// 					}
// 					$res[] = $row;
// 				}
// 			}

// 			$resp['status'] = "OK";
// 			$resp['description'] = "Success";
// 			$resp['total_data'] = count($res);
// 			$resp['data'] = $res;
// 		} else {
// 			$resp['status'] = "NOK";
// 			$resp['description'] = "Invalid Format";
// 		}
// 		echo json_encode($resp);
// 	}

// 	public function getDevices() {
// 		$jsonReq = json_decode($this->input->raw_input_stream, true);
// 		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2) {
// 			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
// 			$data = $this->Api_m->get->getDevices()->result_array();
// 			$res = array();
// 			if(!empty($data)) {
// 				foreach ($data as $row) {
// 					foreach ($row as $key => $value) {
// 						$row[$key] = trim($row[$key]);
// 					}
// 					$res[] = $row;
// 				}
// 			}

// 			$resp['status'] = "OK";
// 			$resp['description'] = "Success";
// 			$resp['total_data'] = count($res);
// 			$resp['data'] = $res;
// 		} else {
// 			$resp['status'] = "NOK";
// 			$resp['description'] = "Invalid Format";
// 		}
// 		echo json_encode($resp);
// 	}

// 	public function getCustomers() {
// 		$jsonReq = json_decode($this->input->raw_input_stream, true);
// 		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2) {
// 			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
// 			$data = $this->Api_m->getCustomers()->result_array();
// 			$res = array();
// 			if(!empty($data)) {
// 				foreach ($data as $row) {
// 					foreach ($row as $key => $value) {
// 						$row[$key] = trim($row[$key]);
// 					}
// 					$res[] = $row;
// 				}
// 			}

// 			$resp['status'] = "OK";
// 			$resp['description'] = "Success";
// 			$resp['total_data'] = count($res);
// 			$resp['data'] = $res;
// 		} else {
// 			$resp['status'] = "NOK";
// 			$resp['description'] = "Invalid Format";
// 		}
// 		echo json_encode($resp);
// 	}

// 	public function getPemesan() {
// 		$jsonReq = json_decode($this->input->raw_input_stream, true);
// 		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2) {
// 			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
// 			$data = $this->Api_m->getPemesan()->result_array();
// 			$res = array();
// 			if(!empty($data)) {
// 				foreach ($data as $row) {
// 					foreach ($row as $key => $value) {
// 						$row[$key] = trim($row[$key]);
// 					}
// 					$res[] = $row;
// 				}
// 			}

// 			$resp['status'] = "OK";
// 			$resp['description'] = "Success";
// 			$resp['total_data'] = count($res);
// 			$resp['data'] = $res;
// 		} else {
// 			$resp['status'] = "NOK";
// 			$resp['description'] = "Invalid Format";
// 		}
// 		echo json_encode($resp);
// 	}

// 	public function getUsers() {
// 		$jsonReq = json_decode($this->input->raw_input_stream, true);
// 		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2) {
// 			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
// 			$data = $this->Api_m->getUsers()->result_array();
// 			$res = array();
// 			if(!empty($data)) {
// 				foreach ($data as $row) {
// 					foreach ($row as $key => $value) {
// 						$row[$key] = trim($row[$key]);
// 					}
// 					$res[] = $row;
// 				}
// 			}

// 			$resp['status'] = "OK";
// 			$resp['description'] = "Success";
// 			$resp['total_data'] = count($res);
// 			$resp['data'] = $res;
// 		} else {
// 			$resp['status'] = "NOK";
// 			$resp['description'] = "Invalid Format";
// 		}
// 		echo json_encode($resp);
// 	}

// 	// READ BY ID
// 	public function getUserById() {
// 		$jsonReq = json_decode($this->input->raw_input_stream, true);
// 		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
// 			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
// 			$id = $jsonReq['id'];
// 			$res = $this->Api_m->getById($id, 'users')->row_array();
			
// 			$resp['status'] = "OK";
// 			$resp['description'] = "Success";
// 			$resp['data'] = $res;
// 		} else {
// 			$resp['status'] = "NOK";
// 			$resp['description'] = "Invalid Format";
// 		}

// 		echo json_encode($resp);
// 		return;
// 	}

// 	/**
// 	 * API FOR CREATE
// 	*/

// 	public function inputSensor() {
// 		$jsonReq = json_decode($this->input->raw_input_stream, true);
// 		if(isset($jsonReq['username']) && isset($jsonReq['password'])) {
// 			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
// 			$params = array('name', 'price', 'photo');

// 			$formatValid = true;

// 			if(count($jsonReq['data']) != count($params)) {
// 				$formatValid = false;
// 			} else {
// 				foreach ($jsonReq['data'] as $key => $value) {
// 					if(!in_array($key, $params)) {
// 						$formatValid = false;
// 						break;
// 					}
// 				}
// 			}

// 			if (!$formatValid) {
// 				$resp['status'] = "NOK";
// 				$resp['description'] = "Invalid Format";
	
// 				echo json_encode($resp);
// 				return;
// 			}

// 			// Input sensor
// 			$ins = $jsonReq['data'];
// 			$this->Api_m->insertData($ins, 'sensor');

// 			$resp['status'] = "OK";
// 			$resp['description'] = "Sensor Saved";
	
// 			echo json_encode($resp);
// 			return;
// 		}
// 	}

// 	public function inputCustomers() {
// 		$jsonReq = json_decode($this->input->raw_input_stream, true);
// 		if(isset($jsonReq['username']) && isset($jsonReq['password'])) {
// 			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
// 			$params = array('name', 'customer_type', 'no_npwp', 'no_ktp', 'no_telephone', 'photo_npwp', 'photo_ktp');

// 			$formatValid = true;

// 			if(count($jsonReq['data']) != count($params)) {
// 				$formatValid = false;
// 			} else {
// 				foreach ($jsonReq['data'] as $key => $value) {
// 					if(!in_array($key, $params)) {
// 						$formatValid = false;
// 						break;
// 					}
// 				}
// 			}

// 			if (!$formatValid) {
// 				$resp['status'] = "NOK";
// 				$resp['description'] = "Invalid Format";
	
// 				echo json_encode($resp);
// 				return;
// 			}

// 			// Input sensor
// 			$ins = $jsonReq['data'];
// 			$this->Api_m->insertData($ins, 'customer');

// 			$resp['status'] = "OK";
// 			$resp['description'] = "Customer Saved";
	
// 			echo json_encode($resp);
// 			return;
// 		}
// 	}

// 	public function inputUser() {
// 		$jsonReq = json_decode($this->input->raw_input_stream, true);
// 		if(isset($jsonReq['username']) && isset($jsonReq['password'])) {
// 			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
// 			$params = array('username', 'password', 'user_type');

// 			$formatValid = true;

// 			if(count($jsonReq['data']) != count($params)) {
// 				$formatValid = false;
// 			} else {
// 				foreach ($jsonReq['data'] as $key => $value) {
// 					if(!in_array($key, $params)) {
// 						$formatValid = false;
// 						break;
// 					}
// 				}
// 			}

// 			if (!$formatValid) {
// 				$resp['status'] = "NOK";
// 				$resp['description'] = "Invalid Format";
	
// 				echo json_encode($resp);
// 				return;
// 			}

// 			// Input sensor
// 			$ins = $jsonReq['data'];
// 			$this->Api_m->insertData($ins, 'user');

// 			$resp['status'] = "OK";
// 			$resp['description'] = "User Saved";
	
// 			echo json_encode($resp);
// 			return;
// 		}
// 	}

// 	public function inputDevice() {
// 		$jsonReq = json_decode($this->input->raw_input_stream, true);
// 		if(isset($jsonReq['username']) && isset($jsonReq['password'])) {
// 			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
// 			$params = array('name', 'price', 'features', 'sensors', 'photos', 'status');

// 			$formatValid = true;

// 			if(count($jsonReq['data']) != count($params)) {
// 				$formatValid = false;
// 			} else {
// 				foreach ($jsonReq['data'] as $key => $value) {
// 					if(!in_array($key, $params)) {
// 						$formatValid = false;
// 						break;
// 					}
// 				}
// 			}

// 			if (!$formatValid) {
// 				$resp['status'] = "NOK";
// 				$resp['description'] = "Invalid Format";
	
// 				echo json_encode($resp);
// 				return;
// 			}

// 			// Input sensor
// 			$ins = $jsonReq['data'];
// 			$this->Api_m->insertData($ins, 'device');

// 			$resp['status'] = "OK";
// 			$resp['description'] = "Device Saved";
	
// 			echo json_encode($resp);
// 			return;
// 		}
// 	}

// 	public function inputPemesan() {
// 		$jsonReq = json_decode($this->input->raw_input_stream, true);
// 		if(isset($jsonReq['username']) && isset($jsonReq['password'])) {
// 			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
// 			$params = array('id_customer', 'nama_pemesan', 'email_pemesan', 'email_alert', 'uname_pemesan', 'password_pemesan', 'no_phone', 'photo_npwp', 'photo_ktp', 'pemesan_type');

// 			$formatValid = true;

// 			if(count($jsonReq['data']) != count($params)) {
// 				$formatValid = false;
// 			} else {
// 				foreach ($jsonReq['data'] as $key => $value) {
// 					if(!in_array($key, $params)) {
// 						$formatValid = false;
// 						break;
// 					}
// 				}
// 			}

// 			if (!$formatValid) {
// 				$resp['status'] = "NOK";
// 				$resp['description'] = "Invalid Format";
	
// 				echo json_encode($resp);
// 				return;
// 			}

// 			// Input sensor
// 			$ins = $jsonReq['data'];
// 			$this->Api_m->insertData($ins, 'device');

// 			$resp['status'] = "OK";
// 			$resp['description'] = "Device Saved";
	
// 			echo json_encode($resp);
// 			return;
// 		}
// 	}

// 	public function wrong_api() {
// 		$text = "not found";
// 		echo $text;
// 		return;
// 	}
// }

?>

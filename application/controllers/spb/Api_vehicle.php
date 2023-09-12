<?php 

class Api_vehicle extends Api {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Vehicle_m');
	}
	
	// public function getVehicle() {
	// 	$jsonReq = json_decode($this->input->raw_input_stream, true);
	// 	if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2) {
	// 		$this->haveAccess($jsonReq['username'], $jsonReq['password']);
	// 		$data = $this->Vehicle_m->getVehicle()->result_array();
	// 		$res = array();
	// 		if(!empty($data)) {
	// 			foreach ($data as $row) {
	// 				foreach ($row as $key => $value) {
	// 					if ($key == "metadata") {
	// 						$row[$key] = json_decode($row[$key]);
	// 					}
	// 					else {
	// 						$row[$key] = trim($row[$key]);
	// 					}
	// 				}
	// 				$res[] = $row;
	// 			}
	// 		}

	// 		$resp['status'] = "OK";
	// 		$resp['description'] = "Success";
	// 		$resp['total_data'] = count($res);
	// 		$resp['data'] = $res;
	// 	} else {
	// 		$resp['status'] = "NOK";
	// 		$resp['description'] = "Invalid Format";
	// 	}
	// 	echo json_encode($resp);
	// }

	public function getVehicle() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if($this->pagingParamCheck($jsonReq)) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $this->Vehicle_m->getVehicle($jsonReq['limit'], $jsonReq['offset'],  $jsonReq['sort_by'], $jsonReq['sort_dir'], $jsonReq['query'])->result_array();
			$res = array();
			if(!empty($data)) {
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

	public function getVehicleById() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$res = array();
			$data = $this->Api_m->getById($id, 'vehicle')->row_array();
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

	public function inputVehicle() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password'])) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('name_vehicle','metadata');

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
			$this->Api_m->insertData($ins, 'vehicle');

			$resp['status'] = "OK";
			$resp['description'] = "Vehicle Saved";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	public function updateVehicle() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 4) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $jsonReq['data'];
			$where = array("id"=>$jsonReq['id']);
			$this->Api_m->updateData($data, $where, 'vehicle');

			$resp['status'] = "OK";
			$resp['description'] = "Vehicle Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";	
		}

		echo json_encode($resp);
		return;
	}

	public function deleteVehicle() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$this->Api_m->deleteData($id, 'vehicle');

			$resp['status'] = "OK";
			$resp['description'] = "Vehicle Deleted";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";	
		}

		echo json_encode($resp);
		return;
	}
}

<?php 

class Api_sales extends Api {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Sales_m');
	}
	
	public function getSales() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $this->Sales_m->getSales()->result_array();
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
	}

	public function getSalesById() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$res = array();
			$data = $this->Api_m->getById($id, 'sales')->row_array();
			if(!empty($data)) {
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

	public function inputSales() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password'])) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('name');

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
			$this->Api_m->insertData($ins, 'sales');

			$resp['status'] = "OK";
			$resp['description'] = "Sales Saved";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	public function updateSales() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 4) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $jsonReq['data'];
			$where = array("id"=>$jsonReq['id']);
			$this->Api_m->updateData($data, $where, 'sales');

			$resp['status'] = "OK";
			$resp['description'] = "Sales Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";	
		}

		echo json_encode($resp);
		return;
	}

	public function deleteSales() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$this->Api_m->deleteData($id, 'sales');

			$resp['status'] = "OK";
			$resp['description'] = "Sales Deleted";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";	
		}

		echo json_encode($resp);
		return;
	}
}

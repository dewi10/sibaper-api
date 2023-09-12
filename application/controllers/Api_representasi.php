<?php 

class Api_representasi extends Api {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Representasi_m');
	}

	public function getRepresentasis() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $this->Representasi_m->getRepresentasis()->result_array();
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

	//======================================================

	public function getRepresentasiById() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$res = array();
			$data = $this->Api_m->getById($id, 'representasi')->row_array();
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

	//=================================================================

	public function getRepresentasiIds(){
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (
			isset($jsonReq['username']) &&
			isset($jsonReq['password']) &&
			isset($jsonReq['no_spt']) &&
			isset($jsonReq['id_personel']) &&
			count($jsonReq) == 4
		) {
        $this->haveAccess($jsonReq['username'], $jsonReq['password']);

        $no_spt = $jsonReq['no_spt'];
        $id_personel = $jsonReq['id_personel'];

        $data = $this->Representasi_m->getRepresentasiIds($no_spt, $id_personel)->result_array();

        
        if (!empty($data)) {
            unset($data['metadata']); // Menghapus bagian metadata
            $resp['status'] = "OK";
            $resp['description'] = "Success";
            $resp['data'] = $data;
        } else {
					$resp['description'] = "No data found";
					$resp['data'] = []; // Empty data array
        }
    } else {
        $resp['status'] = "NOK";
        $resp['description'] = "Invalid Format";
    }

    echo json_encode($resp);
    return;
}

//========================================================================

	public function inputRepresentasi() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('jumlah_rep',
			'nominal_rep',
			'total_rep',
			'no_spt',
			'id_personel','create_by');

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
			$this->Api_m->insertData($ins, 'representasi');

			$resp['status'] = "OK";
			$resp['description'] = "Representasi Tersimpan";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	//========================================================================

	
	public function inputRepresentasi2()
	{
		if($this->input->raw_input_stream == "") { // Jika photo tidak di input
			$request = $_POST;
			$data = json_decode($request['data'], true);
		} else { // Jika photo di input
			$request = json_decode($this->input->raw_input_stream, true);
			$data = $request['data'];
		}
		if (isset($request['username']) && isset($request['password']) && count($request) == 3) {
			$this->haveAccess($request['username'], $request['password']);
			$params = array('jumlah_rep',
			'nominal_rep',
			'total_rep',
			'no_spt',
			'id_personel','create_by');

			$formatValid = true;
			$fileUploaded = false;

			if (count($data) != count($params)) {
				$formatValid = false;
			} else {
				foreach ($data as $key => $value) {
					if (!in_array($key, $params)) {
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

			if(count($_FILES) != 0) {
				$files = array_keys($_FILES);
				foreach ($files as $file) {
					$fileUploaded = $this->ups($file);
				}
	
				if(!$fileUploaded) {
					$resp['status'] = "NOK";
					$resp['description'] = "Error in upload file";
	
					echo json_encode($resp);
					return;
				}
			}

			// Input sensor
			$ins = $data;
			$this->Api_m->insertData($ins, 'representasi');

			$resp['status'] = "OK";
			$resp['description'] = "Representasi Tersimpan";

		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	//========================================================================

	public function updateRepresentasi() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 4) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('jumlah_rep',
			'nominal_rep',
			'total_rep',
			'no_spt',
			'id_personel');
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
			$this->Api_m->updateData($data, $where, 'representasi');

			$resp['status'] = "OK";
			$resp['description'] = "Representasi Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";	
		}

		echo json_encode($resp);
		return;
	}

	//========================================================================

	public function deleteRepresentasi() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$this->Api_m->deleteData($id, 'representasi');

			$resp['status'] = "OK";
			$resp['description'] = "Representasi Deleted";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";	
		}

		echo json_encode($resp);
		return;
	}

}

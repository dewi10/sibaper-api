<?php

class Api_taktis extends Api
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Taktis_m');
	}

	
	public function getTaktis()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		// if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2)
		if($this->pagingParamCheck($jsonReq))
		{
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $this->Taktis_m->getTaktis($jsonReq['limit'], $jsonReq['offset'], $jsonReq['sort_by'], $jsonReq['sort_dir'], $jsonReq['query'], $jsonReq['other'])->result_array();
			$res = array();
			if (!empty($data)) {
				foreach ($data as $row) {
					foreach ($row as $key => $value) {
						if($key == 'metadata') {
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

	//===============================================================

	public function getTaktisById()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$res = array();
			$data = $this->Api_m->getById($id, 'dana_taktis')->row_array();
			if (!empty($data)) {
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

	//=======================================================================================

		
	public function getTaktisId(){
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (
			isset($jsonReq['username']) &&
			isset($jsonReq['password']) &&
			isset($jsonReq['no_spt']) &&
			count($jsonReq) == 3
		) {
        $this->haveAccess($jsonReq['username'], $jsonReq['password']);
				
        $no_spt = $jsonReq['no_spt'];

        $data = $this->Taktis_m->getTaktisId($no_spt)->result_array();
				

        
        if (!empty($data)) {
            unset($data['metadata']); // Menghapus bagian metadata
            $resp['status'] = "OK";
            $resp['description'] = "Success";
						$resp['total_data'] = count($data);
            $resp['data'] = $data;
        } else {
					$resp['description'] = "No data found";
					$resp['total_data'] = count($res);
					$resp['data'] = []; // Empty data array
        }
    } else {
        $resp['status'] = "NOK";
        $resp['description'] = "Invalid Format";
    }

    echo json_encode($resp);
    return;
}


	//=============================================================================================


		
	public function getTaktisIds(){
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

        $data = $this->Taktis_m->getTaktisIds($no_spt, $id_personel)->result_array();

        
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

	 
//===========================================================================================

	public function inputTaktis()
	{
		if ($this->input->raw_input_stream == "") { // Jika photo tidak di input
			$request = $_POST;
			// echo "JSON";
			// echo "<br>";
			// print_r($request);die;
			$data = json_decode($request['data'], true);
		} else { // Jika photo di input
			$request = json_decode($this->input->raw_input_stream, true);
			// echo "FORM";
			// echo "<br>";
			// print_r($request);die;
			$data = $request['data'];
		}
		if (isset($request['username']) && isset($request['password']) && count($request) == 3) {
			$this->haveAccess($request['username'], $request['password']);
			$params = array('type_pelaksana',
			'nominal',
			'total_taktis',
			'keterangan',
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
	
			if (count($_FILES) != 0) {
				$files = array_keys($_FILES);
				// print_r($files);die;
				foreach ($files as $file) {
					$fileUploaded = $this->ups($file);
				}
	
				if (!$fileUploaded) {
					$resp['status'] = "NOK";
					$resp['description'] = "Error in upload file";
	
					echo json_encode($resp);
					return;
				}
			}


			$ins = $data;
			// Input sensor
			$resp['id'] = $this->Api_m->insertData($ins, 'dana_taktis');
	
			$resp['status'] = "OK";
			$resp['description'] = "Taktis Tersimpan";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}
	
		echo json_encode($resp);
		return;
	}

 
	//-----------------------------------------------------------------


	public function updateTaktis()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 4) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('type_pelaksana',
			'nominal',
			'total_taktis',
			'keterangan',
			'no_spt',
			'id_personel','create_by');
			$formatValid = true;
			foreach ($jsonReq['data'] as $key => $value) {
				if (!in_array($key, $params)) {
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
			$where = array("id" => $jsonReq['id']);
			$this->Api_m->updateData($data, $where, 'dana_taktis');

			$resp['status'] = "OK";
			$resp['description'] = "Taktis Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	//-------------------------------------------------------
	public function updateTaktis2()
	{
		if ($this->input->raw_input_stream == "") { // Jika photo tidak di input
			$request = $_POST;
			$data = json_decode($request['data'], true);
		} else { // Jika photo di input
			$request = json_decode($this->input->raw_input_stream, true);
			$data = $request['data'];
		}

		if (isset($request['username']) && isset($request['password']) && count($request) == 4) {
		$this->haveAccess($request['username'], $request['password']);
		$params = array('type_pelaksana',
			'nominal',
			'total_taktis',
			'keterangan',
			'no_spt',
			'id_personel','create_by');
		
		$formatValid = true;
		$fileUploaded = false;

			foreach ($data as $key => $value) {
				if (!in_array($key, $params)) {
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

			if (count($_FILES) != 0) {
				$files = array_keys($_FILES);
				foreach ($files as $file) {
					$fileUploaded = $this->ups($file);
				}

				if (!$fileUploaded) {
					$resp['status'] = "NOK";
					$resp['description'] = "Error in upload file";

					echo json_encode($resp);
					return;
				}
			}
 
			
			$where = array("id" => $request['id']);
			$this->Api_m->updateData($data, $where, 'dana_taktis');

			$resp['status'] = "OK";
			$resp['description'] = "Taktis Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	//-----------------------------------------------------------

	public function deleteTaktis()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$this->Api_m->deleteData($id, 'dana_taktis');

			$resp['status'] = "OK";
			$resp['description'] = "Taktis Deleted";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}
}

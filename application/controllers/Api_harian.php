<?php

class Api_harian extends Api
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Harian_m');
	}

	
	public function getHarian()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		// if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2)
		if($this->pagingParamCheck($jsonReq))
		{
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $this->Harian_m->getHarian($jsonReq['limit'], $jsonReq['offset'], $jsonReq['sort_by'], $jsonReq['sort_dir'], $jsonReq['query'], $jsonReq['other'])->result_array();
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

	public function getHarianById()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$res = array();
			$data = $this->Api_m->getById($id, 'harian')->row_array();
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

	//============================================================================================

	
	public function getHarianIds(){
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

        $data = $this->Harian_m->getHarianIds($no_spt, $id_personel)->result_array();

        
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

	public function inputHarian()
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
			$params = array('jumlah_hari',
			'nominal',
			'total',
			'id_personel',
			'no_spt','create_by'
			);
		 
			
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
			$resp['id'] = $this->Api_m->insertData($ins, 'harian');
	
			$resp['status'] = "OK";
			$resp['description'] = "Harian Tersimpan";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}
	
		echo json_encode($resp);
		return;
	}

 
	//-----------------------------------------------------------------


	public function updateHarian()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 4) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('jumlah_hari',
			'nominal',
			'total',
			'id_personel',
			'no_spt','create_by'
			);
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
			$this->Api_m->updateData($data, $where, 'Harian');

			$resp['status'] = "OK";
			$resp['description'] = "Harian Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	//-------------------------------------------------------
	public function updateHarian2()
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
		$params = array('jumlah_hari',
		'nominal',
		'total',
		'id_personel',
		'no_spt'
		);
		
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
			$this->Api_m->updateData($data, $where, 'harian');

			$resp['status'] = "OK";
			$resp['description'] = "Harian Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	//-----------------------------------------------------------

	public function deleteHarian()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$this->Api_m->deleteData($id, 'harian');

			$resp['status'] = "OK";
			$resp['description'] = "Harian Deleted";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}
}

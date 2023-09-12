<?php

class Api_detail extends Api
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Detail_m');
	}

	public function getDetail()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		// if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2)
		if($this->pagingParamCheck($jsonReq))
		{
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $this->Detail_m->getDetail($jsonReq['limit'], $jsonReq['offset'], $jsonReq['sort_by'], $jsonReq['sort_dir'], $jsonReq['query'], $jsonReq['other'])->result_array();
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

	//========================================================================


	public function checkNoSpt() {
    $jsonReq = json_decode($this->input->raw_input_stream, true);
		// print_r($jsonReq);die();
    if (isset($jsonReq['username']) && isset($jsonReq['password']) && isset($jsonReq['no_spt'])) {
        $this->haveAccess($jsonReq['username'], $jsonReq['password']);
        $no_spt = $jsonReq['no_spt'];

        // Add debug logs to log the incoming request data
        error_log("Received no_spt: " . $no_spt);

        $res = array();
        $data = $this->Detail_m->checkNoSpt($no_spt, 'detail')->result_array();

        // Add debug logs to log the query executed
        error_log("Query executed: " . $this->db->last_query());

        if (!empty($data)) {
            $res = $data;
        }

        $resp['status'] = "OK";
        $resp['description'] = "Success";
				$resp['total_data'] = count($res);
        $resp['data'] = $res;
    } else {
        $resp['status'] = "NOK";
        $resp['description'] = "Invalid Format";
    }

    // Set the response header to JSON
    header('Content-Type: application/json');

    echo json_encode($resp);
    return;
}




	//===========================================================================

	public function getDetailById() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$res = array();
			$data = $this->Api_m->getById($id, 'detail')->row_array();
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


	 
//===========================================================================================

	public function inputDetail()
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
			$params = array('nama','nip','asal','tujuan','tanggal_berangkat', 'tanggal_kembali','no_spt', 'laporan', 'dokumen','create_by','spt_id');
		 
			
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

			if($data['laporan'] == "" || $data['laporan'] == null) {
				unset($data['laporan']);
			}
	
			if($data['dokumen'] == "" || $data['dokumen'] == null) {
				unset($data['dokumen']);
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
			$resp['id'] = $this->Api_m->insertData($ins, 'detail');
			
	
			$resp['status'] = "OK";
			$resp['description'] = "Detail Tersimpan";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}
	
		echo json_encode($resp);
		return;
	}

 
	//-----------------------------------------------------------------


	public function updateDetail()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 4) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('nama','nip','asal','tujuan','tanggal_berangkat','tanggal_kembali','no_spt', 'laporan', 'dokumen','create_by' );
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
			$this->Api_m->updateData($data, $where, 'detail');

			$resp['status'] = "OK";
			$resp['description'] = "Detail Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	//====================================================================

	
	public function updateDetailbySptId()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 4) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('nama','nip','asal','tujuan','tanggal_berangkat','tanggal_kembali','no_spt', 'laporan', 'dokumen','create_by');
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
			$this->Api_m->updateData($data, $where, 'detail');

			$resp['status'] = "OK";
			$resp['description'] = "Detail Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	//-------------------------------------------------------
	public function updateDetail2()
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
		$params = array('laporan');
		
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
			$this->Api_m->updateData($data, $where, 'detail');

			$resp['status'] = "OK";
			$resp['description'] = "Detail Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	//-----------------------------------------------------------

	public function deleteDetail()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$this->Api_m->deleteData($id, 'detail');

			$resp['status'] = "OK";
			$resp['description'] = "Detail Deleted";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}
}

<?php

class Api_spt extends Api
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Spt_m');
	}

	public function getSpt()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		// if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2)
		if($this->pagingParamCheck($jsonReq))
		{
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $this->Spt_m->getSpt($jsonReq['limit'], $jsonReq['offset'], $jsonReq['sort_by'], $jsonReq['sort_dir'], $jsonReq['query'], $jsonReq['other'])->result_array();
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

	//===========================================================================

	public function getSptById()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$res = array();
			$data = $this->Api_m->getById($id, 'spt')->row_array();
			if (!empty($data)) {
			
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

public function getSptIds(){
	$jsonReq = json_decode($this->input->raw_input_stream, true);
	if (
		isset($jsonReq['username']) &&
		isset($jsonReq['password']) &&
		isset($jsonReq['no_spt']) &&
		count($jsonReq) == 3
	) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);

			$no_spt = $jsonReq['no_spt'];

			$data = $this->Spt_m->getSptIds($no_spt)->result_array();

			
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


//============================================================================================


public function checkNoSpt() {
	$jsonReq = json_decode($this->input->raw_input_stream, true);
	if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
		$no_spt = $jsonReq['no_spt'];
		$getNoSpt = $this->Spt_m->checkNoSpt($no_spt)->row_array();
		if(!empty($getNoSpt)){
			$resp['status'] = "NOK";
			$resp['description'] = "No SPT Sudah ada";
		} else {
			$resp['status'] = "OK";
			$resp['description'] = "GO TO NEXT";
		}

	}

	echo json_encode($resp);
	return;
}

	 
//===========================================================================================

	public function inputSpt()
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
			$params = array('no_spt',
			'tanggal_berangkat',
			'tanggal_kembali',
			'kota_asal',
			'kota_tujuan',
			'alat_angkut',
			'nama_pelaksana',
			'type_pelaksana',
			'pejabat_ttd',
			'sekretaris_ttd',
			'triwulan',
			'beban_mak',
			'dasar_pelaksanaan',
			'mata_anggaran',
			'no_sprin',
			'tanggal_sprin',
			'uraian','create_by');
		 
			
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
			$resp['id'] = $this->Api_m->insertData($ins, 'spt');
			
	
			$resp['status'] = "OK";
			$resp['description'] = "Spt Tersimpan";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}
	
		echo json_encode($resp);
		return;
	}

 
	//-----------------------------------------------------------------


	public function updateSpt()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 4) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('no_spt',
			'tanggal_berangkat',
			'tanggal_kembali',
			'kota_asal',
			'kota_tujuan',
			'alat_angkut',
			'nama_pelaksana',
			'type_pelaksana',
			'pejabat_ttd',
			'sekretaris_ttd',
			'triwulan',
			'beban_mak',
			'dasar_pelaksanaan',
			'mata_anggaran',
			'no_sprin',
			'tanggal_sprin',
			'uraian','create_by');
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
			$this->Api_m->updateData($data, $where, 'spt');

			$resp['status'] = "OK";
			$resp['description'] = "Spt Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	//-------------------------------------------------------
	public function updateSpt2()
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
		$params = array('max','uraian','pagu','realisasi','tahun');
		
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
			$this->Api_m->updateData($data, $where, 'spt');

			$resp['status'] = "OK";
			$resp['description'] = "Spt Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	//-----------------------------------------------------------

	public function deleteSpt()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$this->Spt_m->deleteSptWithDetail($id) ;

			$resp['status'] = "OK";
			$resp['description'] = "Spt Deleted";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}
}

<?php 

class Api_pemesanan extends Api {
	public function __construct()
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, Accept,charset,boundary,Content-Length');
		parent::__construct();
		$this->load->model('Pemesanan_m');
	}

	public function getPemesanan() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if($this->pagingParamCheck($jsonReq)) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $this->Pemesanan_m->getPemesanan($jsonReq['limit'], $jsonReq['offset'], $jsonReq['sort_by'], $jsonReq['sort_dir'], $jsonReq['query'], $jsonReq['other'])->result_array();
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
	
	public function getPemesananById() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
// 		print_r($jsonReq);
// die();
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$res = array();
			$data = $this->Pemesanan_m->getById($id, 'pemesanan')->row_array();
			if(!empty($data)) {
				$data['metadata']= json_decode($data['metadata']);
				// $data['list_alat']= json_decode($data['list_alat']);
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

	public function inputPemesanan() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('pemesan_id', 'form_type','agen','customer_id','vehicle_id', 'tanggal_pemesanan', 'status', 'username', 'password_gps', 'alert_email', 'po_number', 'area_customer', 'photo_po','more_photopo','pic_lapangan','site_address', 'paket','harga_layanan', 'jumlah_unit','lama_sewa','harga_sewa','jumlah_sewa','total_layanan','ppn_layanan','grandtotal_layanan','list_alat', 'harga_alat', 'jumlah_alat', 'list_fitur', 'harga_fitur','jumlah_fitur', 'list_sensor', 'harga_sensor', 'jumlah_sensor', 'total_harga', 'tax','grand_total','grandtotal_all','catatan', 'metadata','status_invoice');

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

			// Input Pemesanan
			$ins = $jsonReq['data'];
			$this->Api_m->insertData($ins, 'pemesanan');
			 

			$resp['status'] = "OK";
			$resp['description'] = "Pemesanan Saved";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}
		echo json_encode($resp);
		return;
	}

	
//------------------------------------------------------------------------------

public function inputPemesanan2()
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
		$params = array(
			'pemesan_id', 
			'form_type',
			'agen',  
			'customer_id',
			'vehicle_id', 
			'tanggal_pemesanan', 
			'status', 
			'username', 
			'password_gps', 
			'alert_email', 
			'po_number', 
			'area_customer', 
			'photo_po', 
			'more_photopo',
			'pic_lapangan',
			'site_address', 
			'paket', 
			'harga_layanan', 
			'jumlah_unit',
			'lama_sewa',
			'harga_sewa',
			'jumlah_sewa',
			'total_layanan',
			'ppn_layanan',
			'grandtotal_layanan',
			'list_alat', 
			'harga_alat', 
			'jumlah_alat', 
			'list_fitur', 
			'harga_fitur',
			'jumlah_fitur', 
			'list_sensor', 
			'harga_sensor', 
			'jumlah_sensor', 
			'total_harga', 
			'tax',
			'grand_total',
			'grandtotal_all',
			'catatan', 
			'metadata',
			'status_invoice', 
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
		// var_dump($data);

		
		if($data['photo_po'] == "" || $data['photo_po'] == null) {
			unset($data['photo_po']);
		}

		if($data['more_photopo'] == "" || $data['more_photopo'] == null) {
			unset($data['more_photopo']);
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


		// Input sensor
		// var_dump($formatValid);
		

		$ins = $data;
		$this->Api_m->insertData($ins, 'pemesanan');

		$resp['status'] = "OK";
		$resp['description'] = "Pemesanan Saved";
		// var_dump($resp);die();
	} else { 
		$resp['status'] = "NOK";
		$resp['description'] = "Invalid Format";
	}

	echo json_encode($resp);
	return;
}
//-----------------------------------------------------------------



	public function updatePemesanan() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 4) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('pemesan_id', 'form_type','agen','customer_id','vehicle_id', 'tanggal_pemesanan', 'status', 'username', 'password_gps', 'alert_email', 'po_number', 'area_customer', 'photo_po', 'more_photopo','pic_lapangan','site_address',  'paket','harga_layanan', 'jumlah_unit','lama_sewa','harga_sewa','jumlah_sewa','total_layanan','ppn_layanan','grandtotal_layanan','list_alat', 'harga_alat', 'jumlah_alat', 'list_fitur', 'harga_fitur','jumlah_fitur', 'list_sensor', 'harga_sensor', 'jumlah_sensor', 'total_harga', 'tax','grand_total','grandtotal_all','catatan', 'metadata','status_invoice');
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
			$this->Api_m->updateData($data, $where, 'pemesanan');

			$resp['status'] = "OK";
			$resp['description'] = "Pemesanan Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";	
		}

		echo json_encode($resp);
		return;
	}

	//-------------------------------------------------------
	public function updatePemesanan2()
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
			$params = array('pemesan_id','form_type', 'agen', 'customer_id','vehicle_id', 'tanggal_pemesanan', 'status', 'username', 'password_gps', 'alert_email', 'po_number', 'area_customer', 'photo_po','more_photopo','pic_lapangan','site_address',  'paket','harga_layanan', 'jumlah_unit','lama_sewa','harga_sewa','jumlah_sewa','total_layanan','ppn_layanan','grandtotal_layanan','list_alat', 'harga_alat', 'jumlah_alat', 'list_fitur', 'harga_fitur','jumlah_fitur', 'list_sensor', 'harga_sensor', 'jumlah_sensor', 'total_harga', 'tax','grand_total','grandtotal_all','catatan', 'metadata','status_invoice');

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

				if($data['photo_po'] == "" || $data['photo_po'] == null) {
					unset($data['photo_po']);
				}

				if($data['more_photopo'] == "" || $data['more_photopo'] == null) {
					unset($data['more_photopo']);
				}

			$where = array("id" => $request['id']);
			$this->Api_m->updateData($data, $where, 'pemesanan');

			$resp['status'] = "OK";
			$resp['description'] = "Pemesanan Updated";

		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	//-----------------------------------------------------------

	public function deletePemesanan() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$this->Api_m->deleteData($id, 'pemesanan');

			$resp['status'] = "OK";
			$resp['description'] = "Pemesanan Deleted";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";	
		}

		echo json_encode($resp);
		return;
	}
}

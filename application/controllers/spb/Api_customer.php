<?php

class Api_customer extends Api
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Customer_m');
	}

	public function getCustomers()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		// if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2)
		if($this->pagingParamCheck($jsonReq))
		{
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $this->Customer_m->getCustomers($jsonReq['limit'], $jsonReq['offset'], $jsonReq['sort_by'], $jsonReq['sort_dir'], $jsonReq['query'], $jsonReq['other'])->result_array();
			$res = array();
			if (!empty($data)) {
				foreach ($data as $row) {
					$row['pic_names'] = ''; // Initilaze for PIC NAME per Customer
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

			for ($i=0; $i < count($res); $i++) {
				$getPic = $this->Customer_m->getPic($res[$i]['name'])->result_array();
				$arrPic = array();
				for ($j=0; $j < count($getPic); $j++) {
					array_push($arrPic, $getPic[$j]['name_pic']);
				}
				$res[$i]['pic_names'] = implode(", ", $arrPic); // Fill in PIC NAMES
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

	public function getCustomerById()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$res = array();
			$data = $this->Api_m->getById($id, 'customer')->row_array();
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


	public function checkUsername() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$customer_name = $jsonReq['name'];
			$getUsername = $this->Customer_m->checkUsername($customer_name)->row_array();
// 					print_r($jsonReq);
// die();
			if(!empty($getUsername)){
				$resp['status'] = "NOK";
				$resp['description'] = "error";
			} else {
				$resp['status'] = "OK";
				$resp['description'] = "GO TO NEXT";
			}

		}

		echo json_encode($resp);
		return;
	}

	public function inputCustomers2()
	{
		if ($this->input->raw_input_stream == "") { // Jika photo tidak di input
			$request = $_POST;
			
			$data = json_decode($request['data'], true);
		} else { // Jika photo di input
			$request = json_decode($this->input->raw_input_stream, true);
			$data = $request['data'];
		}

		if (isset($request['username']) && isset($request['password']) && count($request) == 3) {

			$this->haveAccess($request['username'], $request['password']);
			$params = array(
				'sales',
				'client_id',
				'form_type',
				'agen', 
				'name', 
				'email', 
				'type', 
				'address', 
				'no_npwp', 
				'no_ktp',
				'no_telephone',
				'fax',
				'photo_npwp',
				'photo_ktp',
				'photo_agreement',
				'pic_ids',
				'metadata',
				'nama_penagihan',
				'alamat_penagihan',
				'telp_penagihan',
				'email_penagihan',
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

			
			if($data['photo_npwp'] == "" || $data['photo_npwp'] == null) {
				unset($data['photo_npwp']);
			}

			if($data['photo_ktp'] == "" || $data['photo_ktp'] == null) {
				unset($data['photo_ktp']);
			}

			if($data['photo_agreement'] == "" || $data['photo_agreement'] == null) {
				unset($data['photo_agreement']);
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
			$resp['id'] = $this->Api_m->insertData($ins, 'customer');
			
			$resp['status'] = "OK";
			$resp['description'] = "Customer Saved";
			
		 
		} else {
			echo "gagal";
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}
	
		echo json_encode($resp);
		return;
	}

 
	//-----------------------------------------------------------------


	public function updateCustomer()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 4) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('client_id','sales','form_type','agen','name', 'email', 'type', 'address', 'no_npwp', 'no_ktp', 'no_telephone', 'fax', 'photo_npwp', 'photo_ktp', 'photo_agreement', 'pic_ids', 'metadata','nama_penagihan','alamat_penagihan','telp_penagihan','email_penagihan');
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
			$this->Api_m->updateData($data, $where, 'customer');

			$resp['status'] = "OK";
			$resp['description'] = "Customer Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	//-------------------------------------------------------
	public function updateCustomer2()
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
		$params = array('client_id','sales','form_type','agen','name', 'email', 'type', 'address', 'no_npwp', 'no_ktp', 'no_telephone', 'fax', 'photo_npwp', 'photo_ktp', 'photo_agreement', 'pic_ids', 'metadata','nama_penagihan','alamat_penagihan','telp_penagihan','email_penagihan');
		
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

			if($data['photo_npwp'] == "" || $data['photo_npwp'] == null) {
				unset($data['photo_npwp']);
			}

			if($data['photo_ktp'] == "" || $data['photo_ktp'] == null) {
				unset($data['photo_ktp']);
			}

			if($data['photo_agreement'] == "" || $data['photo_agreement'] == null) {
				unset($data['photo_agreement']);
			}
			
			$where = array("id" => $request['id']);
			$this->Api_m->updateData($data, $where, 'customer');

			$resp['status'] = "OK";
			$resp['description'] = "Customer Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	//-----------------------------------------------------------

	public function deleteCustomer()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$this->Customer_m->deleteCustomerWithPIC($id) ;

			$resp['status'] = "OK";
			$resp['description'] = "Customer Deleted";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

 
}

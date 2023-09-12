<?php

class Runner extends Api
{
	public function __construct()
	{
			parent::__construct();
	}


	public function Runner(){
    $jsonReq = json_decode($this->input->raw_input_stream, true);
		

		$jsonFile = file_get_contents('reminder.json');
		$reminderDataFromJson = json_decode($jsonFile, true);

// Memeriksa apakah jumlah data sama
	$query = $this->db->select('id, subject, status, crontab')->from('reminder')->get();
	$reminderDataFromDatabase = $query->result_array();


	if (count($reminderDataFromJson['reminder']) === count($reminderDataFromDatabase)) {
	// Memeriksa setiap objek reminder
	$isDataSame = true; // Variabel untuk menyimpan status kesamaan data
	foreach ($reminderDataFromJson['reminder'] as $jsonReminder) { // Variabel untuk memeriksa apakah objek reminder ditemukan di database
			foreach ($reminderDataFromDatabase as $dbReminder) {
					if ($dbReminder['id'] === strval($jsonReminder['id']) &&
							$dbReminder['subject'] === $jsonReminder['subject'] &&
							$dbReminder['status'] === $jsonReminder['status'] &&
							$dbReminder['last_updated_at'] === $jsonReminder['updated_at'] &&
							$dbReminder['crontab'] === $jsonReminder['crontab']) {
							$found = true;
							break;
					}
			}
			if (!$found) {
					$isDataSame = false;
					$params = array('id, subject, status, crontab'); 
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

					break;
			}
	}

	if ($isDataSame) {
			echo "Data di file JSON sama dengan data dari database.";
	} else{
			echo "Data di file JSON tidak sama dengan data dari database.";
	}
} else {
	echo "Jumlah data di file JSON tidak sama dengan jumlah data dari database.";
}
		$date = date('Y-m-d');
		$data = array('status' => 1);
		$where = array('date' => $date);
		$updateRunner = $this->Api_m->updateData($data, $where, 'reminder');
		if ($updateRunner) {

		} else {

		}

								
    return;
	}
        

      

	public function inputRunner() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('username', 'password', 'nama_karyawan','level');

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
			$this->Api_m->insertData($ins, 'table_user');

			$resp['status'] = "OK";
			$resp['description'] = "User Saved";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";	
		}

		echo json_encode($resp);
		return;
	}



	
}

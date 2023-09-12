<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
use Cron\CronExpression;

class Api_reminder extends Api
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Reminder_m');
	}


function getNextCronDate($cronExpression)
{
    try {
        $cron = CronExpression::factory($cronExpression);
        return $cron->getNextRunDate()->format('Y-m-d H:i:s');
    } catch (InvalidArgumentException $e) {
        echo 'Error parsing cron expression: ' . $e->getMessage();
        return null;
    }

		//nanti pas looping, tinggal masukin crontab datanya

foreach ($data as $row) {
	$data['next_date'] = $this->getNextCronDate($data['crontab']);
	}
}




	public function getReminder()
	{
		$this->load->library('Reminder');
		$this->reminder->update();
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		// if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2)
		if($this->pagingParamCheck($jsonReq))
		{
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$data = $this->Reminder_m->getReminder($jsonReq['limit'], $jsonReq['offset'], $jsonReq['sort_by'], $jsonReq['sort_dir'], $jsonReq['query'], $jsonReq['other'])->result_array();
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

	public function getReminderById()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$res = array();
			$data = $this->Api_m->getById($id, 'reminder')->row_array();
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

	 
	public function inputReminder()
	{
		$this->load->library('Reminder/ReminderRunner');

		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('customer_id','client_id','subject', 'start_date','occurence','crontab','keterangan','status','virtual_account','agreement_date', 'type_crontab');

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

			// Input Reminder
			
			date_default_timezone_set('Asia/Jakarta');

			$jsonReq['data']['updated_at'] = date('Y-m-d H:i:s');

			$ins = $jsonReq['data'];
			$this->Api_m->insertData($ins, 'reminder');
			 
			$resp['status'] = "OK";
			$resp['description'] = "Reminder Saved";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		$this->reminderrunner->update();

		echo json_encode($resp);
		return;
	}
 
	//-----------------------------------------------------------------


	

	public function updateReminder()
	{
		$this->load->library('Reminder/ReminderRunner');

		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 4) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$params = array('customer_id','client_id','subject','crontab','keterangan','status','virtual_account','agreement_date', 'type_crontab');
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

			date_default_timezone_set('Asia/Jakarta');

			$jsonReq['data']['updated_at'] = date('Y-m-d H:i:s');
			$data = $jsonReq['data'];
			$where = array("id" => $jsonReq['id']);
			$this->Api_m->updateData($data, $where, 'reminder');

			$resp['status'] = "OK";
			$resp['description'] = "Reminder Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		// $this->reminderrunner->update();

		echo json_encode($resp);
		return;
	}

	//-------------------------------------------------------
	public function updateReminder2()
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
		$params = array('customer_id','client_id','subject','date','keterangan','status','virtual_account','periode','agreement_date');
		
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
			$this->Api_m->updateData($data, $where, 'reminder');

			$resp['status'] = "OK";
			$resp['description'] = "Reminder Updated";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}

	//-----------------------------------------------------------

	public function deleteReminder()
	{
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if (isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 3) {
			$this->haveAccess($jsonReq['username'], $jsonReq['password']);
			$id = $jsonReq['id'];
			$this->Api_m->deleteData($id, 'reminder');

			$resp['status'] = "OK";
			$resp['description'] = "Reminder Deleted";
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}

		echo json_encode($resp);
		return;
	}
}

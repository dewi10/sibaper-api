<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller{
	function __construct()
  {
    header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, Accept,charset,boundary,Content-Length');
		parent::__construct();
		$this->load->model(array('Api_m', 'Auth_m'));
  }

	protected function haveAccess($username, $password)
	{
		if (!empty($this->Auth_m->checkAuth($username, $password)->result_array())) {
			return true;
		} else {
			$data['status'] = "NOK";
			$data['description'] = "Failed Authentication";
			echo json_encode($data);
			die();
		}
	}

	protected function pagingParamCheck($request) {
		$requestValid = false;
		if(count($request) == 8) {
			if(isset($request['username']) && isset($request['password']) && isset($request['offset']) && isset($request['limit']) && isset($request['sort_by']) && isset($request['sort_dir']) && isset($request['query']) && isset($request['other'])) {
				$requestValid = true;
			}
		}

		return $requestValid;
	}

	public function checkUser() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 2) {
			if (!empty($this->Auth_m->checkAuth($jsonReq['username'], $jsonReq['password'])->result_array())){
				$data['status'] = "OK";
				$data['description'] = "User Found";
				$data['data'] = $this->Auth_m->checkAuth($jsonReq['username'], $jsonReq['password'])->row_array();
			} 
			else {
				$data['status'] = "NOK";
				$data['description'] = "User Not Found";
			}
		} else {
			$data['status'] = "NOK";
			$data['description'] = "Invalid Format";
		}
		echo json_encode($data);
		return;
	}

	public function ups($input) {
		$temp = $_FILES[$input]['tmp_name'];
		// var_dump($_FILES[$input]);die;
		// var_dump($this->config->item('dirUploads'));die;

		$config['upload_path']		= $this->config->item('dirUploads');
		$config['allowed_types']	= 'gif|jpg|jpeg|png|csv|xls|doc|docx|xlsx|pdf|JPG|webp';
		$config['max_size']			= 10240;
		$config['overwrite'] 		= TRUE;
		
		$this->upload->initialize($config);
		if (!$this->upload->do_upload($input)) {
			return false;
		}

		// print_r($input);
		// die();
		
		$filesize = getimagesize($temp);
		if($filesize != null || $filesize != array()) {
			if ($filesize[0] > 2000 || $filesize[1] > 2000) {
				return false;
			}
		}

		

		return true;
	}

	public function wrong_api() {
		$text = "not found";
		echo $text;
		return;
	}

	public function getCount() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		if(isset($jsonReq['username']) && isset($jsonReq['password']) && count($jsonReq) == 4) {
			$data['status'] = "OK";
			$data['description'] = "Success";
			$data['data']['maskapaiCount'] = $this->Api_m->countData('maskapai', $jsonReq['query'], $jsonReq['other']);
			$data['data']['userCount'] = $this->Api_m->countData('user', $jsonReq['query'], $jsonReq['other']);
			$data['data']['personelCount'] = $this->Api_m->countData('personel', $jsonReq['query'], $jsonReq['other']);
			$data['data']['kotaCount'] = $this->Api_m->countData('kota', $jsonReq['query'], $jsonReq['other']);
			$data['data']['sptCount'] = $this->Api_m->countData('spt', $jsonReq['query'], $jsonReq['other']);
		} else {
			$data['status'] = "NOK";
			$data['description'] = "Invalid Format";
		}
		echo json_encode($data);
		return;
	}

	public function create_token($username, $password) {
		$token = md5($username . ':' . $password);
		if(! file_exists('../spb_form_api/json_access.json')) {
			$data = '[{"username":"'.$username.'", "token":"'.$token.'"}]';
			write_file('../spb_form_api/json_access.json', $data);
		} else {
			$content = file_get_contents('../spb_form_api/json_access.json');
			$newUser = true;
			$dataString = '';
			$dt = json_decode($content, true);
			// print_r($dt);
			for ($i=0; $i < count($dt); $i++) {
				if($dt[$i]['username'] == $username) {
					$dt[$i]['token'] = $token;
					$newUser = false;
				}
				$dataString .= '{"username":"'.$dt[$i]['username'].'", "token":"'.$dt[$i]['token'].'"},';
			}
			if($newUser) {
				$dataNew = '{"username":"'.$username.'", "token": "'.$token.'"}';
				$dataString .= $dataNew;
			}
			// echo $dataString;
			if($dataString[strlen($dataString)-1] == ',') {
				$dataString = substr($dataString, 0, -1);
			}
			$dataFull = '['.$dataString.']';
			// echo $dataFull;die;
			write_file('../spb_form_api/json_access.json', $dataFull);
			// echo(json_decode($content));die;
		}
	}

	public function logoutUser() {
		$jsonReq = json_decode($this->input->raw_input_stream, true);
		// echo "a";
		// print_r($jsonReq);die;
		if(file_exists('../spb_form_api/json_access.json')) {
			$dataString = "";
			$foundUser = false;
			$content = file_get_contents('../spb_form_api/json_access.json');
			$dt = json_decode($content, true);
			foreach ($dt as $data) {
				if($data['username'] != $jsonReq['username']) {
					$dataString .= '{"username":"'.$data['username'].'", "token":"'.$data['token'].'"},';
				} else {
					$foundUser = true;
				}
			}
			if ($foundUser) {
				if($dataString[strlen($dataString)-1] == ',') {
					$dataString = substr($dataString, 0, -1);
				}
				$dataFull = '['.$dataString.']';
				// echo $dataFull;die;
				write_file('../spb_form_api/json_access.json', $dataFull);
				
				$resp['status'] = "OK";
				$resp['description'] = "Success";
	
				echo json_encode($resp);
			} else {
				$resp['status'] = "NOK";
				$resp['description'] = "Failed";
	
				echo json_encode($resp);
			}
			return;
		}
	}
}

?>

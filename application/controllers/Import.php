<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Import extends Api {
// construct
	public function __construct() {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, Accept,charset,boundary,Content-Length');
		parent::__construct();
		// load model
		$this->load->model('Import_model', 'Import');
		$this->load->helper(array('url','html','form'));
	}    
	public function index() {
		$this->load->view('Import');
	}

	//Import File Customer

	public function importFile() {
		$request = $_POST;
		if (isset($request['username']) && isset($request['password'])) {
			// print_r('test');
			if (count($_FILES) != 0) {
				$files = array_keys($_FILES);
				// var_dump($_FILES);die;
				foreach ($files as $file) {
					$fileUploaded = $this->uploadFile($file);
				}
				// print_r('test2');
				// var_dump($fileUploaded);die;
				if (!$fileUploaded) {
					$resp['status'] = "NOK";
					$resp['description'] = "Error in upload file";

					// echo json_encode($resp);
					return;
				} else {
					$resp['status'] = "OK";
					$resp['description'] = "Success Upload File";

					// echo json_encode($resp);
				}
			}
		} else {
			// print_r('test1');
			$resp['status'] = "NOK";
			$resp['description'] = "Invalid Format";
		}
	  // die();

		// var_dump($this->config->item('dirUploads'));die;

		if($resp['status'] = "OK") {
			// read file

			include APPPATH.'third_party/PHPExcel/PHPExcel.php';
			$excelreader = new PHPExcel_Reader_Excel2007();
			$filepath = $this->config->item('dirUploads');
			$filename = $_FILES["file"]["name"];
			// var_dump($filename);die();
			$loadexcel = $excelreader->load($filepath."/".$filename); // Load file yang telah diupload ke folder excel
			$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
			// var_dump($sheet);die();
			
			// insert
			// Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
			$data = array();
			
			$numrow = 1;
			foreach($sheet as $row){
				// Cek $numrow apakah lebih dari 1
				// Artinya karena baris pertama adalah nama-nama kolom
				// Jadi dilewat saja, tidak usah diimport
				if($numrow > 1){
					// Kita push (add) array data ke variabel data
					array_push($data, array(
						'nama_maskapai'=>trim($row['A']), 
						'metadata'=>$row['B'],
						// 'agen'=>$row['C'],
						// 'name'=>$row['D'],
						// 'type'=>$row['E'],
						// 'email'=>$row['F'],
						// 'no_telephone'=>$row['G'],
						// 'fax'=>$row['H'],
						// 'address'=>$row['I'],
						// 'no_npwp'=>$row['J'],
						// 'no_ktp'=>$row['K'], 
						// 'pic_ids'=>$row['L'], 
						// 'nama_penagihan'=>$row['M'],
						// 'telp_penagihan'=>$row['N'],
						// 'email_penagihan'=>$row['O'],
						// 'alamat_penagihan'=>$row['P'],
					));
				}
				$numrow++; // Tambah 1 setiap kali looping
			}
			// var_dump($data);die();
			$this->Api_m->insert_multiple($data, 'maskapai');
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Error WHen Import File ";
		}
		echo json_encode($resp);

		return;
	}
	public function uploadFile($input) {
		// $temp = $_FILES[$input]['tmp_name'];
		// var_dump($_FILES[$input]);die;
		// var_dump($this->config->item('dirUploads'));

		$config['upload_path']		= $this->config->item('dirUploads');
		$config['allowed_types']	= 'csv|xls|xlsx';
		$config['max_size']			= 10240;
		$config['overwrite'] 		= TRUE;

		// var_dump($config);die();

		
		$this->upload->initialize($config);
		if (!$this->upload->do_upload($input)) {
			return false;
		}
		return true;
		// var_dump($$config);die();
	}

	public function insert_multiple($data){
		$this->db->insert_batch('maskapai', $data);
		$this->db->limit($perpage, $start);
		

		$query = $this->db->get();
		return $query;
	
	}

 
	//Import File pemesanan

	public function importFile2() { 
		$request = $_POST;
			if (isset($request['username']) && isset($request['password'])) {
				// var_dump($request);die();
				if (count($_FILES) != 0) {
					$files = array_keys($_FILES);
					// var_dump($_FILES);die();
					foreach ($files as $file) {
						$fileUploaded = $this->uploadFile2($file);
					}
					// print_r('test2');
					// var_dump($fileUploaded);die();
					if (!$fileUploaded) {
						$resp['status'] = "NOK";
						$resp['description'] = "Error in upload file";
	
						// echo json_encode($resp);
						return;
					} else {
						$resp['status'] = "OK";
						$resp['description'] = "Success Upload File";
	
						// echo json_encode($resp);
					}
				}
			} else {
				// print_r('test1');
				$resp['status'] = "NOK";
				$resp['description'] = "Invalid Format";
			}
			// die();
	
			// var_dump($this->config->item('dirUploads'));die();
	
			if($resp['status'] = "OK") {
				// read file
	
				include APPPATH.'third_party/PHPExcel/PHPExcel.php';
				$excelreader = new PHPExcel_Reader_Excel2007();
				$filepath = $this->config->item('dirUploads');
				$filename = $_FILES["file"]["name"];
				// var_dump($filename);die;
				$loadexcel = $excelreader->load($filepath."/".$filename); // Load file yang telah diupload ke folder excel
				$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true, true);
				// var_dump($sheet);die();
				
				// insert
				// Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
				$data = array();
				
				$numrow = 1;
				
				foreach($sheet as $row){
					// Cek $numrow apakah lebih dari 1
					// Artinya karena baris pertama adalah nama-nama kolom
					// Jadi dilewat saja, tidak usah diimport
					if($numrow > 1){
						// Kita push (add) array data ke variabel data
						array_push($data, array(
						'nama_personel' =>trim($row['A']),
						'nip_personel' =>$row['B'],
						'jabatan_personel' =>$row['C'],
						'golongan_personel' =>$row['D'],  
						// 'tanggal_pemesanan' =>$row['E'], 
						// 'status' =>$row['F'], 
						// 'username' =>$row['G'], 
						// 'password_gps' =>$row['H'], 
						// 'alert_email' =>$row['I'], 
						// 'po_number' =>$row['J'], 
						// 'area_customer' =>$row['K'], 
						// 'photo_po' 	=>$row['L'], 
						// 'more_photopo' 	=>$row['M'], 
						// 'pic_lapangan' =>$row['N'], 
						// 'site_address' =>$row['O'], 
						// 'type_layanan' =>$row['P'], 
						// 'paket' =>$row['Q'], 
						// 'harga_layanan' =>$row['R'], 
						// 'jumlah_unit' 	=>$row['S'], 
						// 'lama_sewa' 	=>$row['T'], 
						// 'harga_sewa' 	=>$row['U'], 
						// 'jumlah_sewa' 	=>$row['V'], 
						// 'total_layanan' =>$row['W'], 
						// 'ppn_layanan' =>$row['X'], 
						// 'grandtotal_layanan' =>$row['Y'], 
						// 'list_alat' =>$row['Z'], 
						// 'harga_alat' =>$row['AA'], 
						// 'jumlah_alat' =>$row['AB'], 
						// 'list_fitur' =>$row['AC'], 
						// 'harga_fitur' =>$row['AD'], 
						// 'jumlah_fitur' =>$row['AE'], 
						// 'list_sensor' =>$row['AF'], 
						// 'harga_sensor' =>$row['AG'], 
						// 'jumlah_sensor' =>$row['AH'], 
						// 'total_harga' =>$row['AI'], 
						// 'tax' =>$row['AJ'], 	
						// 'grand_total' =>$row['AK'], 
						// 'grandtotal_all'=>$row['AL'], 
						// 'catatan' =>$row['AM'],  
						// 'status_invoice' =>$row['AN'], 
					));
				}
				$numrow++; // Tambah 1 setiap kali looping
			}

			
			// var_dump($data);die();
			$this->Api_m->insert_multiple2($data, 'personel');
		} else {
			$resp['status'] = "NOK";
			$resp['description'] = "Error WHen Import File ";
		}
		echo json_encode($resp);

		return;
	}
 
 

	public function uploadFile2($input) {
		// $temp = $_FILES[$input]['tmp_name'];
		// var_dump($_FILES[$input]);die();
		// var_dump($this->config->item('dirUploads'));

		$config['upload_path']		= $this->config->item('dirUploads');
		$config['allowed_types']	= 'gif|jpg|png|exe|xls|xlsx';
		$config['max_size']			= 10240;
		$config['overwrite'] 		= TRUE;
 

		// var_dump($config);die();

		
		$this->upload->initialize($config);
		if (!$this->upload->do_upload($input)) {
			return false;
		}
		return true;
		// var_dump($config);die();
	}

	public function insert_multiple2($data){
		$this->db->insert_batch('personel', $data);
	}



		//Import File Agen

		public function importFile3() {
			$request = $_POST;
			if (isset($request['username']) && isset($request['password'])) {
				// print_r('test');
				if (count($_FILES) != 0) {
					$files = array_keys($_FILES);
					// var_dump($_FILES);die;
					foreach ($files as $file) {
						$fileUploaded = $this->uploadFile3($file);
					}
					// print_r('test2');
					// var_dump($fileUploaded);die;
					if (!$fileUploaded) {
						$resp['status'] = "NOK";
						$resp['description'] = "Error in upload file";
	
						// echo json_encode($resp);
						return;
					} else {
						$resp['status'] = "OK";
						$resp['description'] = "Success Upload File";
	
						// echo json_encode($resp);
					}
				}
			} else {
				// print_r('test1');
				$resp['status'] = "NOK";
				$resp['description'] = "Invalid Format";
			}
			// die();
	
			// var_dump($this->config->item('dirUploads'));die;
	
			if($resp['status'] = "OK") {
				// read file
	
				include APPPATH.'third_party/PHPExcel/PHPExcel.php';
				$excelreader = new PHPExcel_Reader_Excel2007();
				$filepath = $this->config->item('dirUploads');
				$filename = $_FILES["file"]["name"];
				// var_dump($filename);die;
				$loadexcel = $excelreader->load($filepath."/".$filename); // Load file yang telah diupload ke folder excel
				$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
				// var_dump($sheet);die;
				
				// insert
				// Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
				$data = array();
				
				$numrow = 1;
				foreach($sheet as $row){
					// Cek $numrow apakah lebih dari 1
					// Artinya karena baris pertama adalah nama-nama kolom
					// Jadi dilewat saja, tidak usah diimport
					if($numrow > 1){
						// Kita push (add) array data ke variabel data
						array_push($data, array(
							'nama_kota'=>trim($row['A']),
							// 'name'=>$row['B'],
							// 'telp_agen'=>$row['C'],
							// 'no_rekening'=>$row['D'],
							// 'fee'=>$row['E'],
							// 'alamat_agen'=>$row['F'],
						));
					}
					$numrow++; // Tambah 1 setiap kali looping
				}
				// var_dump($data);die();
				$this->Api_m->insert_multiple3($data, 'kota');
			} else {
				$resp['status'] = "NOK";
				$resp['description'] = "Error WHen Import File ";
			}
			echo json_encode($resp);
	
			return;
		}
		public function uploadFile3($input) {
			// $temp = $_FILES[$input]['tmp_name'];
			// var_dump($_FILES[$input]);die();
			// var_dump($this->config->item('dirUploads'));
	
			$config['upload_path']		= $this->config->item('dirUploads');
			$config['allowed_types']	= 'csv|xls|xlsx';
			$config['max_size']			= 10240;
			$config['overwrite'] 		= TRUE;
	
			// var_dump($_FILES[$input]);die();
	
			
			$this->upload->initialize($config);
			if (!$this->upload->do_upload($input)) {
				return false;
			}
			return true;
			
		}
	
		public function insert_multiple3($data){
			$this->db->insert_batch('kota', $data);
			$this->db->limit($perpage, $start);
			
	
			$query = $this->db->get();
			return $query;
		}



		//Import File Pic

		public function importFile4() {
			$request = $_POST;
			if (isset($request['username']) && isset($request['password'])) {
				// print_r('test');
				if (count($_FILES) != 0) {
					$files = array_keys($_FILES);
					// var_dump($_FILES);die;
					foreach ($files as $file) {
						$fileUploaded = $this->uploadFile4($file);
					}
					// print_r('test2');
					// var_dump($fileUploaded);die;
					if (!$fileUploaded) {
						$resp['status'] = "NOK";
						$resp['description'] = "Error in upload file";
	
						// echo json_encode($resp);
						return;
					} else {
						$resp['status'] = "OK";
						$resp['description'] = "Success Upload File";
	
						// echo json_encode($resp);
					}
				}
			} else {
				// print_r('test1');
				$resp['status'] = "NOK";
				$resp['description'] = "Invalid Format";
			}
			// die();
	
			// var_dump($this->config->item('dirUploads'));die;
	
			if($resp['status'] = "OK") {
				// read file
	
				include APPPATH.'third_party/PHPExcel/PHPExcel.php';
				$excelreader = new PHPExcel_Reader_Excel2007();
				$filepath = $this->config->item('dirUploads');
				$filename = $_FILES["file"]["name"];
				// var_dump($filename);die;
				$loadexcel = $excelreader->load($filepath."/".$filename); // Load file yang telah diupload ke folder excel
				$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
				// var_dump($sheet);die;
				
				// insert
				// Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
				$data = array();
				
				$numrow = 1;
				foreach($sheet as $row){
					// Cek $numrow apakah lebih dari 1
					// Artinya karena baris pertama adalah nama-nama kolom
					// Jadi dilewat saja, tidak usah diimport
					if($numrow > 1){
						// Kita push (add) array data ke variabel data
						array_push($data, array(
							'sales'=>trim($row['A']),
							'customer_name'=>$row['B'],
							'name_pic'=>$row['C'], 
							'pic_type' =>$row['D'], 
							'email_pic'=>$row['E'],
							'no_phone'=>$row['F'],
						));
					}
					$numrow++; // Tambah 1 setiap kali looping
				}
				// var_dump($data);die();
				$this->Api_m->insert_multiple4($data, 'pic');
			} else {
				$resp['status'] = "NOK";
				$resp['description'] = "Error WHen Import File ";
			}
			echo json_encode($resp);
	
			return;
		}
		public function uploadFile4($input) {
			// $temp = $_FILES[$input]['tmp_name'];
			// var_dump($_FILES[$input]);die;
			// var_dump($this->config->item('dirUploads'));
	
			$config['upload_path']		= $this->config->item('dirUploads');
			$config['allowed_types']	= 'csv|xls|xlsx';
			$config['max_size']			= 10240;
			$config['overwrite'] 		= TRUE;
	
			// var_dump($_FILES[$input]);die;
	
			
			$this->upload->initialize($config);
			if (!$this->upload->do_upload($input)) {
				return false;
			}
			return true;
			
		}
	
		public function insert_multiple4($data){
			$this->db->insert_batch('pic', $data);
			$this->db->limit($perpage, $start);
			
	
			$query = $this->db->get();
			return $query;
		}

}

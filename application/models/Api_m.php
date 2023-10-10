<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
		
	}


	public function getById($id, $table) {
		$this->db->where(array("id"=>$id));
		$query = $this->db->get($table);
		return $query; 
	}

	public function getByName($name, $table) {
		$this->db->where(array("name"=>$name));
		$query = $this->db->get($table);
		
		return $query;
	}

	public function getNextId($customer_id, $table) {
		$this->db->where(array("customer_id"=>$id));
		$query = $this->db->get($table);
		return $query;
	}


	/**
	 * MODEL FOR CREATE / INSERT
	 */
	public function insertData($data, $table) {
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	public function insertDataCustomer($data, $table) {
		$postdata = $this->input->post();  
    extract($postdata);
    $this->db->insert($table, $data, compact($name,$no_telephone));
    return ($this->db->trans_status()) ? $this->db->insert_id() : false;
	}

	/**
	 * MODEL FOR UPDATE
	 */
	public function updateData($data, $where, $table) {
		$this->db->where($where);
		$this->db->update($table, $data);
		return $this->db->affected_rows();
	}

	/**
	 * MODEL FOR DELETE
	 */
	public function deleteData($id, $table) {
		$this->db->where('id', $id);
		$this->db->delete($table);
		return $this->db->affected_rows();
	}


	/**
	 * MODEL FOR Customer
	 */
	public function deleteCustomer($id, $table) {
		$this->db->where('id', $id);
		$this->db->delete('pic', array('customer_name' => $customerName)); // DELETE PIC
		$this->db->delete('customer', array('id' => $customerId)); // DELETE CUSTOMER 
		$this->db->delete($table);
		return $this->db->affected_rows();
	}



	//Dashboard

	public function countData($table, $query, $other = null) {
		if($table == 'customer') {
			if ($query != '' && $query != null) {
				$this->db->like('name', $query);
				$this->db->or_like('email', $query);
			}

			if ($other != null && $other != '') {
				$paramWhere = json_decode($other, true);
				foreach($paramWhere as $x => $x_value) {
					if($x_value == "" || $x_value == null) {
						unset($paramWhere[$x]);
					}
				}
	
				$this->db->where($paramWhere);
			}
		}
		
		if($table == 'pemesanan') {
			if ($query != '' && $query != null) {
				$this->db->like('name', $query);
				$this->db->or_like('area_customer', $query);
			}
			// print_r(json_decode($other, true));die();
			if ($other != null && $other != '' ) {
				$paramWhere = json_decode($other, true);
				foreach($paramWhere as $x => $x_value) {
					if($x_value == "" || $x_value == null || $x == "sales") {
						unset($paramWhere[$x]);
					}
				}
				// print_r($paramWhere);die();
				$this->db->where($paramWhere);
			}
		}

		if($table == 'pic') {
			if ($query != '' && $query != null) {
				$this->db->like('customer_name', $query);
				$this->db->or_like('name_pic', $query);
			}
		}
		if($table == 'vehicle') {
			if ($query != '' && $query != null) {
				$this->db->like('name_vehicle', $query);
				$this->db->or_like('metadata', $query);
			}
		}
		return $this->db->count_all_results($table);
	}

	public function insert_multiple($data)
{
    // Get existing 'nama_maskapai' from the database
    $existingMaskapai = $this->db->select('nama_maskapai')->get('maskapai')->result_array();
    $existingNames = array_column($existingMaskapai, 'nama_maskapai');

    $dataToInsert = array();
    foreach ($data as $dat) {
        $namaMaskapai = $dat['nama_maskapai'];
        
        // Jika 'nama_maskapai' tidak ada di $existingNames, maka masukkan data ke array $dataToInsert
        if (!in_array($namaMaskapai, $existingNames)) {
            $dataToInsert[] = array('nama_maskapai' => $namaMaskapai);
        }
    }

    // Jika ada data yang akan diinsert, lakukan proses insert batch
    if (!empty($dataToInsert)) {
        $this->db->insert_batch('maskapai', $dataToInsert);
    }
}

public function insert_multiple2($data)
{
    // Get existing data from the database
    $existingData = $this->db->select('nip_personel, nama_personel, jabatan_personel, golongan_personel')
                            ->get('personel')
                            ->result_array();

    // Create an array of unique keys based on the combination of all four columns
    $existingKeys = array();
    foreach ($existingData as $row) {
        $key = $row['nip_personel'] . '|' . $row['nama_personel'] . '|' . $row['jabatan_personel'] . '|' . $row['golongan_personel'];
        $existingKeys[$key] = true;
    }

    $dataToInsert = array();
    foreach ($data as $dat) {
        $key = $dat['nip_personel'] . '|' . $dat['nama_personel'] . '|' . $dat['jabatan_personel'] . '|' . $dat['golongan_personel'];

        // If the combination of all four columns is not in $existingKeys, insert the data into $dataToInsert
        if (!isset($existingKeys[$key])) {
            $dataToInsert[] = array(
                'nip_personel' => $dat['nip_personel'],
                'nama_personel' => $dat['nama_personel'],
                'jabatan_personel' => $dat['jabatan_personel'],
                'golongan_personel' => $dat['golongan_personel']
            );
        }
    }

    // If there is data to insert, perform the insert batch operation
    if (!empty($dataToInsert)) {
        $this->db->insert_batch('personel', $dataToInsert);
    }
}


public function insert_multiple3($data)
{
    // Get existing 'nama_maskapai' from the database
    $existingKota = $this->db->select('nama_kota')->get('kota')->result_array();
    $existingNames = array_column($existingKota, 'nama_kota');

    $dataToInsert = array();
    foreach ($data as $dat) {
        $namaKota = $dat['nama_kota'];
        
        // Jika 'nama_kota' tidak ada di $existingNames, maka masukkan data ke array $dataToInsert
        if (!in_array($namaKota, $existingNames)) {
            $dataToInsert[] = array('nama_kota' => $namaKota);
        }
    }

    // Jika ada data yang akan diinsert, lakukan proses insert batch
    if (!empty($dataToInsert)) {
        $this->db->insert_batch('kota', $dataToInsert);
    }
}



	// public function insert_multiple($data){
	// 	$dataFinal = array();
	// 	//Format Customer Name to Id
	// 	$formatSales = array();
	// 	foreach($data as $dat){
	// 		$salesNames = trim($dat['sales']); //'Ririn Oktarina Rahayu'
	// 		$tableUser = "user";
	// 		$this->db->where(array("name"=>$salesNames));
	// 		$query = $this->db->get($tableUser);
	// 		$result = $query->result_array();
	// 		foreach ($result as $val)
	// 		{
	// 			$dat['sales'] = $val['id'];
	// 			array_push($formatSales, $dat);
	// 		}
			
	// 	};
	// 	// var_dump($data);die();
	// 	$dataFinal = $formatSales;


	// 	// Format Form Type  Name to Id 
	// 	$formatFormType = array();
	// 	foreach($formatSales as $dat){
	// 		switch ($dat['form_type']) {
	// 			case 'Sales' :
	// 				$dat['form_type'] = '1';
	// 				array_push($formatFormType, $dat);
	// 				break;
	// 			case 'Agen' :
	// 				$dat['form_type'] = '2';
	// 				array_push($formatFormType, $dat);
	// 				break;
					
	// 			default:
	// 				//what to do if the role is neither 'author' nor 'visitor'?
	// 		}
			
	// 	};
	// 	$dataFinal = $formatFormType;
	// 	// var_dump($dataFinal);die();


	// 	$formatAgen = array();
	// 	foreach($formatFormType as $dat){
	// 		if($dat['form_type'] == '1') {
	// 			// echo "test1";
	// 			$formatAgen = $formatFormType;
				
	// 		} else {
	// 			// echo "test2";
	// 			$agenNames = $dat['agen']; //'pt.anugrah jaya'
	// 			$tableAgen = "agen";
	// 			$this->db->where(array("name"=>$agenNames));
	// 			$query = $this->db->get($tableAgen);
	// 			$result = $query->result_array();
	// 			foreach ($result as $val)
	// 			{
	// 				$dat['agen'] = $val['id'];
	// 				array_push($formatAgen, $dat);
	// 			}
	// 		}
	// 	};
	// 	$dataFinal = $formatAgen;
	// 	// var_dump($formatAgen);die();
 

	// 	$formatType = array();
	// 	foreach($formatAgen as $dat){
	// 		switch ($dat['type']) {
	// 			case 'Company' :
	// 				$dat['type'] = '1';
	// 				array_push($formatType, $dat);
	// 				break;
	// 			case 'Individu' :
	// 				$dat['type'] = '2';
	// 				array_push($formatType, $dat);
					
	// 			default:
	// 				//what to do if the role is neither 'author' nor 'visitor'?
	// 		}
	// 	};
	// 	$dataFinal = $formatType;
	// 	// var_dump($dataFinal);die();
	// 	$this->db->insert_batch('customer', $dataFinal);
		
	// }

	// public function insert_multiple2($data){
	// 	$dataFinal = array();
	// 	// var_dump($data);die();
	// 	//Format Customer Name to Id
	// 	$formatCustomer = array();
	// 	foreach($data as $dat){
	// 		$custNames = $dat['customer_id']; //'pt.anugrah jaya'
	// 		$tableCustomer = "customer";
	// 		$this->db->where(array("name"=>$custNames));
	// 		$query = $this->db->get($tableCustomer);
	// 		$result = $query->result_array();
	// 		foreach ($result as $val)
	// 		{
	// 			$dat['customer_id'] = $val['id'];
	// 			array_push($formatCustomer, $dat);
	// 		}
	// 		// var_dump($formatCustomer);die();
	// 	};
	// 	//Format Pemesan Name to Id
	// 	$formatPemesan = array();
	// 	foreach($formatCustomer as $dat){
	// 		$pemesanNames = trim($dat['pemesan_id']); //'Ririn Oktarina Rahayu'
	// 		$tableUser = "user";
	// 		$this->db->where(array("name"=>$pemesanNames));
	// 		$query = $this->db->get($tableUser);
	// 		$result = $query->result_array();
	// 		foreach ($result as $val)
	// 		{
	// 			$dat['pemesan_id'] = $val['id'];
	// 			array_push($formatPemesan, $dat);
	// 		}
			
	// 	};
	// 	$dataFinal = $formatPemesan;
	// 	// var_dump($dataFinal);die();
		 
  
	// 	//Format Area Customer Name to Id
	// 	$formatAreaCustomer = array();
	// 	foreach($formatPemesan as $dat){
	// 		$areacustomerNames = $dat['area_customer']; //'pt.anugrah jaya'
	// 		$tableAreaCustomer = "area_customer";
	// 		$this->db->where(array("name"=>$areacustomerNames));
	// 		$query = $this->db->get($tableAreaCustomer);
	// 		$result = $query->result_array();
	// 		foreach ($result as $val)
	// 		{
	// 			$dat['area_customer'] = $val['id'];
	// 			array_push($formatAreaCustomer, $dat);
	// 		}	
	
	// 	};
	// 	$dataFinal = $formatAreaCustomer;
	// 		// var_dump($dataFinal);die();


	// 			// Format Form Type  Name to Id 
	// 			$formatFormType = array();
	// 			foreach($formatAreaCustomer as $dat){
	// 				switch ($dat['form_type']) {
	// 					case 'Sales' :
	// 						$dat['form_type'] = '1';
	// 						array_push($formatFormType, $dat);
	// 						break;
	// 					case 'Agen' :
	// 						$dat['form_type'] = '2';
	// 						array_push($formatFormType, $dat);
	// 						break;
							
	// 					default:
	// 						//what to do if the role is neither 'author' nor 'visitor'?
	// 				}
					
	// 			};
	// 			$dataFinal = $formatFormType;
	// 			// var_dump($dataFinal);die();


	// 			$formatAgen = array();
	// 			foreach($formatFormType as $dat){
	// 				if($dat['form_type'] == '1') {
	// 					// echo "test1";
	// 					$formatAgen = $formatFormType;
						
	// 				} else {
	// 					// echo "test2";
	// 					$agenNames = $dat['agen']; //'pt.anugrah jaya'
	// 					$tableAgen = "agen";
	// 					$this->db->where(array("name"=>$agenNames));
	// 					$query = $this->db->get($tableAgen);
	// 					$result = $query->result_array();
	// 					foreach ($result as $val)
	// 					{
	// 						$dat['agen'] = $val['id'];
	// 						array_push($formatAgen, $dat);
	// 					}
	// 				}
	// 			};
	// 			$dataFinal = $formatAgen;
	// 			// var_dump($formatAgen);die();
  

	// 		// Format Status  Name to Id 
	// 	$formatStatus = array();
	// 	foreach($formatAgen as $dat){
	// 		switch ($dat['status']) {
	// 			case 'New Customer' :
	// 				$dat['status'] = '1';
	// 				array_push($formatStatus, $dat);
	// 				break;
	// 			case 'Repeat Order' :
	// 				$dat['status'] = '2';
	// 				array_push($formatStatus, $dat);
	// 				break;
	// 			case 'Trial' :
	// 					$dat['status'] = '3';
	// 					array_push($formatStatus, $dat);
	// 					break;
	// 			default:
	// 				//what to do if the role is neither 'author' nor 'visitor'?
	// 		}
			
	// 	};
	// 	$dataFinal = $formatStatus;
	// 	// var_dump($formatStatus);die();

	// 	// Format Status Invoice  Name to Id 

	// 	$formatStatusInvoice = array();
	// 	foreach($formatStatus as $dat){
	// 		switch ($dat['status_invoice']) {
	// 			case 'in Progress' :
	// 				$dat['status_invoice'] = '1';
	// 				array_push($formatStatusInvoice, $dat);
	// 				break;
	// 			case 'Canceled' :
	// 				$dat['status_invoice'] = '2';
	// 				array_push($formatStatusInvoice, $dat);
	// 				break;
	// 			case 'Done' :
	// 					$dat['status_invoice'] = '3';
	// 					array_push($formatStatusInvoice, $dat);
	// 					break;
	// 			default:
	// 			//what to do if the role is neither 'author' nor 'visitor'?
	// 		}
	// 	};
	// 	$dataFinal = $formatStatusInvoice;
	// 	// var_dump($formatStatusInvoice);die();

	// 	//Format Pic Name to Id
	// 	$formatPicLapangan = array();
	// 	foreach($formatStatusInvoice as $dat){
	// 		$piclapanganNames = $dat['pic_lapangan']; 
	// 		$tablePic = "pic";
	// 		$this->db->where(array("name_pic"=>$piclapanganNames));
	// 		$query = $this->db->get($tablePic);
	// 		$result = $query->result_array();
	// 		foreach ($result as $val)
	// 		{
	// 			$dat['pic_lapangan'] = $val['id'];
	// 			array_push($formatPicLapangan, $dat);
	// 		}
			
	// 	};
	// 	$dataFinal = $formatPicLapangan;
	// 	// var_dump($formatPicLapangan);die();
		

 	// // var_dump($dataFinal);die();
			

	// 	$formatTypeLayanan = array();
	// 	foreach($dataFinal as $dat){
	// 		switch ($dat['type_layanan']) {
	// 			case 'Sewa GPS' :
	// 				$dat['type_layanan'] = '1';
	// 				array_push($formatTypeLayanan, $dat);
	// 				break;
	// 			case 'Beli GPS' :
	// 				$dat['type_layanan'] = '2';
	// 				array_push($formatTypeLayanan, $dat);
	// 				break;
	// 			case 'Trial' :
	// 				$dat['type_layanan'] = '3';
	// 				array_push($formatTypeLayanan, $dat);
	// 				break;
					
	// 			default:
	// 				//what to do if the role is neither 'author' nor 'visitor'?
	// 		}
	// 	};
	// 	$dataFinal = $formatTypeLayanan;
	// 	// var_dump($formatTypeLayanan);die();
		

	// 	//Format List Alat Name to Id
	// 	$formatListAlat = array();
	// 	foreach($dataFinal as $dat){
	// 		$agenNames = $dat['list_alat']; 
	// 		$tableAlat = "alat_gps";
	// 		$this->db->where(array("name"=>$agenNames));
	// 		$query = $this->db->get($tableAlat);
	// 		$result = $query->result_array();
	// 		foreach ($result as $val)
	// 		{
	// 			$dat['list_alat'] = $val['id'];
	// 			array_push($formatListAlat, $dat);
	// 		}
			
	// 	};
	// 	if (count($formatListAlat) > 0) {
	// 		$dataFinal = $formatListAlat;
	// 	}
	// 	// var_dump($formatListAlat);die();


	// 		//Format List Alat Name to Id
	// 		$formatListSensor = array();
	// 		foreach($dataFinal as $dat){
	// 			$agenNames = $dat['list_sensor']; 
	// 			$tableSensor = "sensor";
	// 			$this->db->where(array("name"=>$agenNames));
	// 			$query = $this->db->get($tableSensor);
	// 			$result = $query->result_array();
	// 			foreach ($result as $val)
	// 			{
	// 				$dat['list_sensor'] = $val['id'];
	// 				array_push($formatListSensor, $dat);
	// 			}
				
	// 		};
	// 		if (count($formatListSensor) > 0) {
	// 			$dataFinal = $formatListSensor;
	// 		}
	// 		// var_dump($formatListSensor);die();

	// 		//Format List Fitur Name to Id
	// 		$formatListFitur = array();
	// 		foreach($dataFinal as $dat){
	// 			$fiturNames = $dat['list_fitur']; 
	// 			$tableFitur = "fitur";
	// 			$this->db->where(array("name"=>$fiturNames));
	// 			$query = $this->db->get($tableFitur);
	// 			$result = $query->result_array();
	// 			foreach ($result as $val)
	// 			{
	// 				$dat['list_fitur'] = $val['id'];
	// 				array_push($formatListFitur, $dat);
	// 			}
				
	// 		};
	// 		if (count($formatListFitur) > 0) {
	// 			$dataFinal = $formatListFitur;
	// 		}
	// 		// var_dump($formatListFitur);die();
			
	// 		foreach ($dataFinal as $i => $dat) {
	// 			foreach ($dat as $k => $v) {
	// 				$dataFinal[$i][$k] = trim($v) ;
	// 			}
	// 		}


	// 	$this->db->insert_batch('pemesanan', $dataFinal);
	// }



	// // format table agen

	// public function insert_multiple3($data){
	// 	$dataFinal = array();
	// 	//Format Sales Name to Id
	// 	$formatSales = array();
	// 	foreach($data as $dat){
	// 		$salesNames = trim($dat['sales']); //'Ririn Oktarina Rahayu'
	// 		$tableUser = "user";
	// 		$this->db->where(array("name"=>$salesNames));
	// 		$query = $this->db->get($tableUser);
	// 		$result = $query->result_array();
	// 		foreach ($result as $val)
	// 		{
	// 			$dat['sales'] = $val['id'];
	// 			array_push($formatSales, $dat);
	// 		}
			
	// 	};
	// 	$dataFinal = $formatSales;
	// 	// var_dump($dataFinal);die();

	// 	$this->db->insert_batch('agen', $dataFinal);
	// }


	// format table Pic

	// public function insert_multiple4($data){
	// 	$dataFinal = array();
	// 	//Format Sales Name to Id
	// 	$formatSales = array();
	// 	foreach($data as $dat){
	// 		$salesNames = trim($dat['sales']); //'Ririn Oktarina Rahayu'
	// 		$tableUser = "user";
	// 		$this->db->where(array("name"=>$salesNames));
	// 		$query = $this->db->get($tableUser);
	// 		$result = $query->result_array();
	// 		foreach ($result as $val)
	// 		{
	// 			$dat['sales'] = $val['id'];
	// 			array_push($formatSales, $dat);
	// 		}
			
	// 	};
	// 	$dataFinal = $formatSales;
	// 	// var_dump($dataFinal);die();

	// 	$formatPicType = array();
	// 	foreach($formatSales as $dat){
	// 		switch ($dat['pic_type']) {
	// 			case 'Office' :
	// 				$dat['pic_type'] = '1';
	// 				array_push($formatPicType, $dat);
	// 				break;
	// 			case 'Invoice' :
	// 				$dat['pic_type'] = '2';
	// 				array_push($formatPicType, $dat);
	// 				break;
	// 			case 'Site' :
	// 				$dat['pic_type'] = '3';
	// 				array_push($formatPicType, $dat);
	// 				break;

	// 				case 'General' :
	// 					$dat['pic_type'] = '4';
	// 					array_push($formatPicType, $dat);
	// 					break;
	// 					default:
	// 				//what to do if the role is neither 'author' nor 'visitor'?
	// 		}
	// 	};
	// 	$dataFinal = $formatPicType;
	// 	// var_dump($dataFinal);die();

	// 	$this->db->insert_batch('pic', $dataFinal);
	// }

	public function insert_multiple4($data) {
    if (!empty($data)) {
        $existingData = $this->db->select('kode_diagnosa, nama_diagnosa, name_diagnosa')
            ->get('diagnosa')
            ->result_array();

        $existingKeys = array();
        foreach ($existingData as $row) {
            $key = $row['kode_diagnosa'] . $row['nama_diagnosa'] . $row['name_diagnosa'];
            $existingKeys[$key] = true;
        }

        $dataToInsert = array();
        foreach ($data as $dat) {
            $key = $dat['kode_diagnosa'] . $dat['nama_diagnosa'] . $dat['name_diagnosa'];

            if (!isset($existingKeys[$key])) {
                $dataToInsert[] = [
                    'kode_diagnosa' => $dat['kode_diagnosa'],
                    'nama_diagnosa' => $dat['nama_diagnosa'],
                    'name_diagnosa' => $dat['name_diagnosa'],
                ];
            }
        }

        if (!empty($dataToInsert)) {
            $this->db->insert_batch('diagnosa', $dataToInsert);
        }
    }
}

}

?>

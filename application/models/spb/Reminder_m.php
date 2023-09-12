<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reminder_m extends CI_Model {
	function __construct()
	{
		parent::__construct();
	}

	public function getReminder($perpage, $start, $sort_by, $sort_dir, $query, $other) {
		if($perpage > 100) {
			$perpage = 100;
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

		if ($query != '') {
			$query = strtolower($query);
			$this->db->group_start(); 
			$this->db->like('LOWER(concat(subject,\' \',customer_id,\' \',date,\' \',keterangan,\' \',status))', $query);
			$this->db->group_end();
		}

		// $date = date(Y-m-d);

		// $this->db->order_by("crontab=$date", 'DESC', $sort_by, $sort_dir);
		$this->db->order_by($sort_by, $sort_dir);
		$query = $this->db->get('reminder', $perpage, $start);
		return $query;
	}

}

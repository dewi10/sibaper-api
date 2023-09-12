<?php

class ReminderRunner
{
	private $instance;

	public function __construct()
	{
		$this->instance =& get_instance();
		$this->instance->load->database();
	}

	public function update()
	{
		$json = file_get_contents(__DIR__ . '/Reminder.json');
		$rows = $this->instance->db
			->select('id, crontab, updated_at')
			->from('reminder')
			->get()
			->result_array();
		
		try {
			$json = json_decode($json, TRUE);
		} catch (\Exception $e) {
			$json = array();
		}

		if (!is_array($json)) $json = array();

		foreach ($rows as $row) {
			$index = NULL;
			foreach ($json as $k => $v)
			{
				if ($v['id'] == $row['id'])
				{
					$index = $k;
					break;
				}
			}
			if (is_null($index)) {
				$json[] = array(
					'id'              => $row['id'],
					'crontab'            => $row['crontab'],
					'updated_at'      => $row['updated_at'],
					'last_updated_at' => $row['updated_at'],
				);
			} else {
				if ($json[$index]['last_updated_at'] != $row['updated_at']) {
					$json[$index]['crontab']            = $row['crontab'];
					$json[$index]['updated_at']      = $json[$index]['last_updated_at'];
					$json[$index]['last_updated_at'] = $row['updated_at'];
				}
			}
		}

		foreach ($json as $key => $reminder) {
			$row = $this->instance->db
				->select('id')
				->from('reminder')
				->where('id', $reminder['id'])
				->get()
				->row_array();
			if (empty($row)) {
				unset($json[$key]);
			}
		}

		file_put_contents(__DIR__ . '/Reminder.json', json_encode($json, JSON_PRETTY_PRINT));
	}
}

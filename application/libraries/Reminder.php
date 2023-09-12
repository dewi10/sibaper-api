<?php

class Reminder
{
	private $instance;

	public function __construct()
	{
		$this->instance =& get_instance();
		$this->instance->load->database();
	}

    public function update()
    {
        $output   = shell_exec('crontab -l');
        $lines    = explode(PHP_EOL, $output);
		$others   = array();

        foreach ($lines as $line)
        {
			if (preg_match('/Api_email/', $line) == false) {
				$others[] = trim($line);
			}
        }

        $reminders = $this->instance->db
			->select('id, crontab')
			->from('reminder')
			->get()
			->result_array();

		$crontabs = array();

      foreach ($reminders as $reminder){
				$id         = $reminder['id'];
				$index      = dirname(dirname(__DIR__)) . '\index.php';
				$crontabs[] = "C:\\xampp\php\php.exe $index Api_email send_reminder $id";
      }

		$crontabs = array_merge($crontabs, $others);
		$temp     = tempnam(sys_get_temp_dir(), 'cron_');
		file_put_contents($temp, implode(PHP_EOL, $crontabs));
		exec("crontab $temp");
		unlink($temp);
    }
}

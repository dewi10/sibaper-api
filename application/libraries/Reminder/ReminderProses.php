<?php

class ReminderProcess
{
	public function update()
	{
		$json = file_get_contents(__DIR__ . '/Reminder.json');
		print_r($json);
		die();
	}
}

<?php

class Miidle_Data_MessageLogger
{
	static public function log($message, $origin, $destination = '')
	{
		$log_mapper = new Application_Model_LogMapper();
		$log = new Application_Model_Log();
		$log->setOrigin($origin)
			->setDestination($destination)
			->setMessage(serialize($message))
			->setTstamp(date('Y-m-d H:i:s'));
		$log_mapper->save($log);
	}
}
<?php

class Application_Model_Autoproperties
{
	public function __call($method, $parameters)
	{
		$type = substr($method, 0, 3);
		$variable = strtolower(preg_replace('/([A-Z])/', '_\1', substr($method, 3)));
		
		switch ($type) {
			case 'set':
				if (sizeof($parameters) == 1) {
					$this->$variable = $parameters[0];
					return($this);
				} else {
					throw new Exception('Setters are expected to have exactly one parameter.');
				}
				break;
			case 'get':
				if (sizeof($parameters) == 0) {
					return($this->$variable);
				} else {
					throw new Exception('Getters are expected to have no parameters.');
				}
				break;
			default:
				throw new Exception('Invalid method '.$method);
				break;
		}	
	}
}

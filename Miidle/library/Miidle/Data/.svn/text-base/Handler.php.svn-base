<?php

define('DATAOP_UPDATE', 'U');
define('DATAOP_DELETE', 'D');
define('DATAOP_INSERT', 'I');

class Miidle_Data_Handler
{
	static public function getData($obj)
	{
		$data = (array) $obj;
        unset($data['_operacao']);
        unset($data['_id_op']);
        return $data;
	}
	static public function getOperacao($obj)
	{
		return($obj->_operacao);
	}	
	static public function getIdOperacao($obj)
	{
		return($obj->_id_op);
	}	
}

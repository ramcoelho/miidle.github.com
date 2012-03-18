<?php

class Miidle_Rest_Consumer extends Zend_Rest_Client
{
	protected $_messages;
	protected $_type;

	public function __construct($type)
	{
		$this->_type = $type;

		$app = $GLOBALS['application']->getOption('app');
		$message_config = $app['messages'][$type];
		$server = $message_config['server'];
		$context = $message_config['context'];
		unset($message_config['server']);
		unset($message_config['context']);
		$this->_messages = new StdClass();
		foreach ($message_config as $key => $method) {
			$key = str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
			$this->_messages->$key = $context . $method;
		}
		Zend_Rest_Client::__construct($server);
		$this->getHttpClient()->setHeaders('Accept-Encoding', 'plain');
	}
	
	public function __call($method, $args)
	{
		if(substr($method, 0, 2) == 'do') {
			$message_name = substr($method, 2);
			$message_uri = $this->_messages->$message_name;
			if(!isset($args) || !isset($args[0])) {
				$args = array(array());
			}
			$obj_resposta = $this->_restCall($message_uri, $args[0]);
			return $obj_resposta;
		} else {
			throw new Exception("Metodo inexistente " . $method, 6002);
			
		}
	}

	protected function _restCall($uri, $data)
	{
    	$hash = md5(APPNAME . serialize($data) . APPKEY . date('YmdHi'));
        $pack = serialize(array(
			'data' => $data,
			'sys' => APPNAME,
			'hash' => $hash
		));

		// Logar consulta
        Miidle_Data_MessageLogger::log($data, $this->_type, $uri);

        $resposta = $this->restPost($uri, array('pack' => $pack));
        list($header, $contents) = preg_split('/([\r\n][\r\n])/', $resposta);
        $obj_resposta = json_decode($contents);

        // Logar mensagens de retorno
        Miidle_Data_MessageLogger::log($contents, $uri, $this->_type);

        if($obj_resposta == NULL) {
        	throw new Exception("JSON invalido: " . $contents, 6001);
        }
        return($obj_resposta);
	}
}
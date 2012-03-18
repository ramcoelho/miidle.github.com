<?php
class Zend_View_Helper_RenderMessage extends Zend_View_Helper_Abstract
{
	public function renderMessage($serialized)
	{
		$msg = unserialize($serialized);
		if(is_string($msg)) {
			return $this->_renderJson($msg);
		}
		if(is_object($msg)) {
			return $this->_renderObject($msg);
		}
		if(is_array($msg)) {
			return $this->_renderArray($msg);
		}
		return $serialized;
	}
	protected function _renderMessage($string, $class)
	{
		return '<div class="mensagem mensagem-' . $class . '">' . $string . '</div>';
	}
	protected function _renderString($string) 
	{
		return $this->_renderMessage($string, 'escalar');
	}
	protected function _renderJson($string)
	{
		try {
			$obj = json_decode($string);
		} catch (Exception $e) {
			return $this->_renderString($string);
		}
		if(isset($obj->results)) {
			$results = $obj->results;
			if(is_array($results)) {
				if(empty($results)) {
					return $this->_renderMessage('Nenhum registro', 'operacao');
				} else {
					$quantidade = sizeof($results);
					$s = '';
					if($quantidade != 1) $s = 's';
					return $this->_renderMessage($quantidade . ' registro' . $s, 'operacao');
				}
			}
			if(is_object($results)) {
				if(isset($results->result)) {
					if(!$results->result) {
						return $this->_renderError($results->message[2]);
					}
				}
			}
		} else {
			if(isset($obj->result)) {
				if($obj->result) {
					return $this->_renderMessage('Registro id = ' . $obj->id, 'registros');
				}
				if(!$obj->result) {
					return $this->_renderError($obj->message);
				}
			}
			return serialize($obj);
		}
		return serialize($obj);
	}
	protected function _renderObject($obj)
	{
		return serialize($obj);
	}
	protected function _renderArray($array)
	{
		if(isset($array['results'])) {
			$results = $array['results'];
			if(is_array($results)) {
				if(isset($results['result'])) {
					if(!$results['result']) {
						return $this->_renderError($results['message']);
					}
				}
			}
		}
		if(empty($array)) {
			return $this->_renderMessage('Consulta', 'vazio');
		}
		if(isset($array['id'])) {
			return $this->_renderMessage('Registro id = ' . $array['id'], 'registros');
		}
		if(isset($array['_id_op'])) {
			return $this->_renderMessage('Confirmação operação ' . $array['_id_op'], 'operacao');
		}

		return $this->_renderMessage('Inclusão de novo registro', 'registros');
	}
	protected function _renderError($error)
	{
		return $this->_renderMessage($error, 'erro');
	}
}

<?php

class Helper_Auth
{
		
	/**
	 * Verifica se a mensagem recebida tem as credenciais necessárias para acessar ao iMoo
	 */
	public static function esta_autorizado($pack){

		$lista_de_chaves = array("MIIDLE" => "5gbH#@wd879MJu3j2mrfc");
			
		$unpack = unserialize($pack);
		
		$sys = $unpack['sys'];
		$chave = $lista_de_chaves[$sys];
		$hash1 = md5($sys . serialize($unpack['data']) . $chave . date('YmdHi'));
		$hash2 = md5($sys . serialize($unpack['data']) . $chave . (date('YmdHi') - 1)); 

		if (($hash1 == $unpack['hash']) || ($hash2 == $unpack['hash'])){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Retorna o array data com os dados passados no pack 
	 */
	public static function get_data($pack){
		$unpack = unserialize($pack);
		return $unpack['data'];
	}
}
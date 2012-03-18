<?php

define('FORCA_ALTERACAO_SENHA', false);

class Usuario
{
	public function salvar()
	{	

		$data = Helper_Auth::get_data($_POST['pack']);

		$dao = new Dao_Usuario();

		$nome =  substr($data["nome"], 0, strpos($data["nome"], " "));
		$sobrenome =  substr($data["nome"], strpos($data["nome"], " ") + 1);

		return $dao->insert($nome, $sobrenome, $data["email"], $data["username"], $data["password"], FORCA_ALTERACAO_SENHA);
		
	}
	

	public function atualizar()
	{	

		$data = Helper_Auth::get_data($_POST['pack']);

		$dao = new Dao_Usuario();

		if(!$dao->usuario_existe($data["id"])){

			return $this->salvar();

		} else {
			
			$nome =  substr($data["nome"], 0, strpos($data["nome"], " "));
			$sobrenome =  substr($data["nome"], strpos($data["nome"], " ") + 1);

			return $dao->update( $data["id"], $nome, $sobrenome, $data["email"], $data["username"], $data["password"] );	
		}
		
	}

	public function excluir()
	{	
		$data = Helper_Auth::get_data($_POST['pack']);

		$dao = new Dao_Usuario();

		return $dao->delete($data['id']);		
	}
}

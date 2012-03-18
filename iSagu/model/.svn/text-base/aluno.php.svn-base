<?php

class Aluno
{

	function consultar(){

		$dao = new Dao_Aluno();

		return $dao->consultar();
	}

	function confirmar(){
		
		$dao = new Dao_Aluno();

		$data = Helper_Auth::get_data($_POST['pack']);

		return $dao->confirmar($data['_id_op']);

	}

}

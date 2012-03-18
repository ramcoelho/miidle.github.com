<?php

class Matricula
{

	function consultar(){

		$dao = new Dao_Matricula();

		return $dao->consultar();
	}

	function confirmar(){
		
		$dao = new Dao_Matricula();

		$data = Helper_Auth::get_data($_POST['pack']);
		
		return $dao->confirmar($data['_id_op']);

	}

}

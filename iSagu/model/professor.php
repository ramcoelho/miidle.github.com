<?php

class Professor
{

	function consultar(){

		$dao = new Dao_Professor();

		return $dao->consultar();
	}

	function confirmar(){
		
		$dao = new Dao_Professor();

		$data = Helper_Auth::get_data($_POST['pack']);
		
		return $dao->confirmar($data['_id_op']);

	}

}

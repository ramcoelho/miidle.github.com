<?php

class Turma
{

	public function consultar(){

		$dao = new Dao_Turma();

		return $dao->consultar();
	}

	public function consultar_associacao_professor(){

		$dao = new Dao_Turma();

		return $dao->consultar_associacao_professor();
	}


	function confirmar(){
		
		$dao = new Dao_Turma();

		$data = Helper_Auth::get_data($_POST['pack']);

		return $dao->confirmar($data['_id_op']);

	}

	function confirmar_associacao_professor(){
		
		$dao = new Dao_Turma();

		$data = Helper_Auth::get_data($_POST['pack']);

		return $dao->confirmar_associacao_professor($data['_id_op']);

	}

}

<?php

class Nota
{
	/**
	 * Retornas as notas que foram alteradas desde a última consulta 
	 */
	public function consultar()
	{
		$dao = new Dao_Nota();
		return $dao->find_grades();
	}

	public function confirmar(){
	
		$dao = new Dao_Nota();
		
		$data = Helper_Auth::get_data($_POST['pack']);
		
		return $dao->confirmar($data['_id_op']);
		
	}

}
<?php

class Nota
{

	//só para inserts
	public function salvar(){
		
		$dao = new Dao_Nota();

		$data = Helper_Auth::get_data($_POST['pack']);

		return $dao->salvar($data['id_turma'], $data['id_aluno'], $data['nota'], $data['id_avaliacao'], $data['nome_avaliacao']);
		
	}

}
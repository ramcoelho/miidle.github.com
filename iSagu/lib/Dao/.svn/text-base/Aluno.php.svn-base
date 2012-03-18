<?php

class Dao_Aluno extends Dao_Generic
{
		
	public function consultar(){
		
		$sql_alteracoes = "SELECT operacao as _operacao, esp.id as _id_op, id_fk as id, p.name as nome, p.email, miolo.login as username, miolo.m_password as password FROM basphysicalpersonstudent_esp esp LEFT JOIN basphysicalpersonstudent p ON esp.id_fk =   p.personid left JOIN miolo_user miolo on lower(miolo.login) = lower(miolousername) WHERE esp.confirmado = false";

		$dados = array();

		$result_alteracoes = $this->query($sql_alteracoes, $dados);

		return array('results' => $result_alteracoes);
	}

	public function confirmar($id_op){
		
		$sql = "UPDATE basphysicalpersonstudent_esp esp SET confirmado = TRUE WHERE id = :id";

		$dados = array(':id' => $id_op);

		return $this->executeUpdateOrDelete($sql, $dados);

	}

}

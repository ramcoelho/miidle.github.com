<?php

class Dao_Matricula extends Dao_Generic
{

	public function consultar(){
		
		$sql_alteracoes = "SELECT distinct operacao as _operacao, esp.id as _id_op, id_fk as id, g.groupid as id_turma, c.personid as id_aluno FROM acdenroll_esp esp LEFT JOIN acdenroll e ON esp.id_fk = e.enrollid LEFT JOIN acdgroup g on e.groupid = g.groupid LEFT JOIN acdcontract c on c.contractid = e.contractid WHERE esp.confirmado = false";

		$dados = array();

		$result_alteracoes = $this->query($sql_alteracoes, $dados);

		return array('results' => $result_alteracoes);
	}

	public function confirmar($id_op){
		
		$sql = "UPDATE acdenroll_esp esp SET confirmado = TRUE WHERE id = :id";

		$dados = array(':id' => $id_op);

		return $this->executeUpdateOrDelete($sql, $dados);

	}
}

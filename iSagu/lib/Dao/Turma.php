<?php

class Dao_Turma extends Dao_Generic
{

	public function consultar(){
		$sql_alteracoes = "SELECT DISTINCT operacao as _operacao, esp.id as _id_op, id_fk as id, g.groupid as id_turma,c.name || '-' || cc.name as nome, g.professorresponsible id_professor FROM acdgroup_esp esp LEFT JOIN acdgroup g ON esp.id_fk = g.groupid LEFT JOIN acdclass c ON g.classid = c.classid LEFT JOIN acdcurriculum cu on cu.curriculumid = g.curriculumid left join acdcurricularcomponent cc on cc.curricularcomponentid = cu.curricularcomponentid WHERE esp.confirmado = false";

		$dados = array();

		$result_alteracoes = $this->query($sql_alteracoes, $dados);

		return array('results' => $result_alteracoes);

	}

	public function consultar_associacao_professor(){
		
		$sql_alteracoes = "SELECT DISTINCT 'U' as _operacao, esp.id as _id_op, id_fk as id, g.classid as id_turma, c.name as nome, g.professorresponsible id_professor FROM acdgroup_esp esp LEFT JOIN acdgroup g ON esp.id_fk = g.groupid LEFT JOIN acdclass c ON g.classid = c.classid WHERE esp.confirmado = false";

		$dados = array();

		$result_alteracoes = $this->query($sql_alteracoes, $dados);

		return array('results' => $result_alteracoes);

	}

	public function confirmar($id_op){
		
		$sql = "UPDATE acdgroup_esp esp SET confirmado = TRUE WHERE id = :id";

		$dados = array(':id' => $id_op);

		return $this->executeUpdateOrDelete($sql, $dados);

	}

	public function confirmar_associacao_professor($id_op){
		
		$sql = "UPDATE acdgroup_esp esp SET confirmado = TRUE WHERE id = :id";

		$dados = array(':id' => $id_op);

		return $this->executeUpdateOrDelete($sql, $dados);

	}
}

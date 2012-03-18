<?php

class Dao_Nota extends Dao_Generic
{

	public function find_grades()
	{
		$this->check_updates();

		$sql_esp = "SELECT operacao as _operacao, esp.id as _id_op, g.id as id, i.courseid as id_turma, g.userid AS id_aluno, i.id as id_avaliacao,i.itemname as nome_avaliacao,g.finalgrade AS nota FROM imoo_grade_grades_esp esp LEFT JOIN mdl_grade_grades g on g.id = esp.id_fk INNER JOIN mdl_grade_items i ON g.itemid = i.id where confirmado = false ORDER BY esp.id";

		$dados = array();
		$alteracoes = $this->query($sql_esp, array());

		return (object) array('results' => $alteracoes);;	

	}
	
	/**
	 * Checa pelo lastmodified das notas se ouve alteração ou não, usando blocos de curso.
	 * Caso haja alteração ela é inserida na tabela espelho
	 */
	public function check_updates()
	{

		//seleciona todos os cursos
		$sql_cursos = "SELECT * FROM mdl_course";
		$cursos = $this->query($sql_cursos, array());

		foreach ($cursos as $key => $curso) {
			
			$sql_ult_mod = "SELECT * FROM imoo_alteracao_nota_curso where id_curso = :id_curso";
			$dados = array(':id_curso' => $curso->id);

			$result = $this->query($sql_ult_mod, $dados);

			//Se ainda não está na tabela de modificações, o insere
			if(empty($result)){

				$dados = array(':id_curso' => $curso->id);

				$sql_maior_data = "SELECT max(g.timemodified) as max_atual FROM mdl_grade_grades g INNER JOIN mdl_grade_items i ON g.itemid = i.id WHERE i.courseid = :id_curso and i.itemtype <>'course';";

				$data = $this->query($sql_maior_data, $dados);

				if(empty($data[0]->max_atual)){
					$sql_insert = "INSERT INTO imoo_alteracao_nota_curso(id_curso, ultima_atualizacao) values (:id_curso, 1 )";
				} else {
					$sql_insert = "INSERT INTO imoo_alteracao_nota_curso(id_curso, ultima_atualizacao) values (:id_curso," . $data[0]->max_atual . " )";
				}

				$result_insert = $this->executeInsert($sql_insert, $dados);

				//depois insere as notas na espelho
				$this->insere_espelhos($curso->id, 'I');

			} else {

				foreach ($result as $key => $value) {

					$max_anterior = $value->ultima_atualizacao;

					$sql = "SELECT max(g.timemodified) as max_atual FROM mdl_grade_grades g INNER JOIN mdl_grade_items i ON g.itemid = i.id WHERE i.courseid = :id_curso and i.itemtype <> 'course'";

					$dados = array(":id_curso" => $value->id_curso);

					$result2 = $this->query($sql, $dados);

					$max_atual = $result2[0]->max_atual;

					//houve atualização das notas
					if($max_atual > $max_anterior){
						
						//Atualiza o valos da ultima atualização 
						$sql_update = "UPDATE imoo_alteracao_nota_curso set ultima_atualizacao = :max_atual where id_curso = :id";
						$dados_update = array(':id' => $value->id_curso, 'max_atual' => $max_atual);
						$this->executeUpdateOrDelete($sql_update, $dados_update);

						$this->insere_espelhos($value->id_curso, 'U');
					} 

				}
			}
		}

	}


	private function insere_espelhos($id_curso, $op){

		//Vai recuperar todas as notas alteradas para inserir na tabela espelho
		$sql = "SELECT g.id, g.userid AS id_aluno, g.finalgrade AS nota, i.courseid FROM mdl_grade_grades g INNER JOIN mdl_grade_items i ON g.itemid = i.id WHERE i.itemtype <> 'course' and i.courseid = :id_curso";

		$dados = array(':id_curso' => $id_curso);
		$notas_novas = $this->query($sql, $dados);

		//para todas as nota, insere na tabela espelho
		foreach ($notas_novas as $key => $nota) {
			
			$sql_insert ="INSERT INTO imoo_grade_grades_esp(id_fk,operacao) VALUES (:id_nota,:operacao);";
			$dados = array(':id_nota' => $nota->id, ':operacao' => $op);

			$result_insert_esp = $this->executeInsert($sql_insert, $dados);

		}
	}

	public function confirmar($id_op){
		
		$sql = "UPDATE imoo_grade_grades_esp esp SET confirmado = TRUE WHERE id = :id";

		$dados = array(':id' => $id_op);

		return $this->executeUpdateOrDelete($sql, $dados);

	}

}

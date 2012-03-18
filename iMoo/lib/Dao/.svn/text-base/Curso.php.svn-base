<?php

define('DEFAULT_CATEGORY', 1);
define('DEFAULT_FORMAT', 'weeks');
define('DEFAULT_SUMMARY', ' oferecido via EaD');

define('TEACHER', 'Professor');
define('TEACHERS', 'Professores');
define('STUDENT', 'Aluno');
define('STUDENTS', 'Alunos');

define('COUSE_CONTEXT', 50);
	

class Dao_Curso extends Dao_Generic
{

	public function insert($nome_curso, $id_professor)
	{	
		//inicia a transação
		$this->begin_transaction();

		$sql = "INSERT INTO {$this->prefix}course (category, fullname,	shortname, summary, format, teacher, teachers, student, students) "
				."VALUES (?,?,?,?,?,?,?,?,?);";

		$dados = array(	DEFAULT_CATEGORY, $nome_curso, substr($nome_curso, 0, 100), $nome_curso . DEFAULT_SUMMARY, DEFAULT_FORMAT, TEACHER, TEACHERS, STUDENT, STUDENTS);
		
		$result = $this->executeInsert($sql, $dados);
		
		//verifica se conseguiu inserir a turma e passa para inserir o contexto
		if( $result['result'] ){
			
			// Cria um Contexto para o Curso
			$sql  = "INSERT INTO {$this->prefix}context (contextlevel,instanceid) VALUES (?,?);";
			$dados = array( COUSE_CONTEXT, $result['id']);
			
			$resultCtx = $this->executeInsert($sql, $dados);	

			if( $resultCtx['result'] ){
				//Atualiza o path e o depth do contexto com id do contexto criado:
				$sql =  "UPDATE  {$this->prefix}context SET path='/1/3/{$resultCtx['id']}', depth=3 WHERE id=:id";
				$dados = array(':id' => $resultCtx['id']);
				$resultCtx = $this->executeUpdateOrDelete($sql, $dados);
				
				if(! $resultCtx['result']){
					$this->rollback();
					return $resultCtx;
				}

			} else {
				$this->rollback();
				return $resultCtx;	
			}

		} else {
			$this->rollback();
			return $result;
		}

		//depois de salvar o curso testa se foi informado um professor e o salva
		if($result['result'] && $id_professor != ""){
			$resultProf = $this->setTeacher($result['id'], $id_professor);
			if( ! $resultProf['result'] ){
				$this->rollback();
				return $resultProf;
			}
		}

		$this->commit();
		//se tiver dado tudo certo, retorna a mensagem que veio da inserção da turma
		return $result;
	}


	public function update( $id, $nome_curso, $id_professor )
	{	
		$this->begin_transaction();

		$sql = "UPDATE {$this->prefix}course SET fullname = :fullname,	shortname = :shortname, summary = :summary WHERE ID = :id;";

		$dados = array(	':fullname' => $nome_curso, ':shortname' => substr($nome_curso, 0, 100), ':summary' => 
			$nome_curso . DEFAULT_SUMMARY, ':id' => $id );
						
		$result = $this->executeUpdateOrDelete($sql, $dados);
		
		if($result['result'] && $id_professor != ""){
			$resultProf = $this->setTeacher($id, $id_professor);
			if( ! $resultProf['result'] ){
				$this->rollback();
				return $resultProf;
			}
		}
		$this->commit();

		return $result;
	}

	public function delete( $id )
	{
		$sql = 	"DELETE FROM {$this->prefix}course WHERE ID = :id";
		
		$dados = array(':id' => $id);
						
		$result = $this->executeUpdateOrDelete($sql, $dados);

		return $result;
	}


	/**
	 * Seta um usuário como professor do curso
	 * @param $idCourse id do curso
	 * @param $idUserTeacher id do usuário que será professor do curso
	 * @return - array('result' => boolean, 'id' => int [,'message' => 'Mensagem'])
	 * 			 result indica se a operaçãoo foi bem sucedida ou não 
	 *			 id é o id do registro inserido no banco ou null caso ocorra erro
	 *			 e o message retorna uma mensagem codificada com utf8
	 */
	public function setTeacher($idCourse, $idUserTeacher){
		return $this->setUserRole($idCourse, $idUserTeacher, 3);
	}
	
	/**
	 * Seta um usuário como aluno do curso
	 * @param $idCourse id do curso
	 * @param $idUserStudent id do usuário que será inscrito no curso
	 * @return - array('result' => boolean, 'id' => int [,'message' => 'Mensagem'])
	 * 			 result indica se a operaçãoo foi bem sucedida ou não 
	 *			 id é o id do registro inserido no banco ou null caso ocorra erro
	 *			 e o message retorna uma mensagem codificada com utf8
	 */
	public function setStudent($idCourse, $idUserStudent){
		return $this->setUserRole($idCourse, $idUserStudent, 5);
	}
	
	/**
	 * Seta o papel do usuário no curso
	 * @param $idCourse id do curso
	 * @param $idUser id do usuário
	 * @param $idRole id do papel 
	 * 			1: Administrator, 2:Course creator, 3:Teacher 4:Non-editing teacher, 5:Student, 6:Guest, 7:Authenticated user
	 * @return - array('result' => boolean, 'id' => int [,'message' => 'Mensagem'])
	 * 			 result indica se a operaçãoo foi bem sucedida ou não 
	 *			 id é o id do registro inserido no banco ou null caso ocorra erro
	 *			 e o message retorna uma mensagem codificada com utf8
	 */
	private function setUserRole($idCourse, $idUser, $idRole){
			
		$idContext = $this->findContext($idCourse);
		
		if($idContext != null){
			return $this->insertRoleAssignments($idRole, $idContext, $idUser);
		}else{
			return array('result' => false, 'id' => '', 'message' => 'Contexto não encontrado para o curso informado.');
		}
	}
	
	/**
	 * Insere um registro na tabela mdl_role_assignments
	 * que determina o papel de usu�rios nem um curso cursos
	 *
	 * @param $idRole 1: Administrator, 2:Course creator, 3:Teacher 4:Non-editing teacher, 5:Student, 6:Guest, 7:Authenticated user
	 * @param $idContext Contexto do curso no tabela mdl_context
	 * @param $idUser	 Id do usuário
	 * @return id do registro inserido ou NULL caso ocorra erro
	 */
	private function insertRoleAssignments($idRole, $idContext, $idUser){
		
		$sql = "INSERT INTO {$this->prefix}role_assignments (roleid,contextid,userid) VALUES (?,?,?);";
		$dados = array($idRole, $idContext, $idUser);
		return $this->executeInsert($sql, $dados);
	}
	
	/**
	 * Retorna o id do contexto referente ao curso
	 * @param $idCourse id do curso
	 * @return id do contexto
	 */
	public function findContext($idCourse){
		$sql = "SELECT id FROM mdl_context WHERE instanceid = ? and contextlevel = ?;";
		$dados = array($idCourse, COUSE_CONTEXT);
		$result = $this->query($sql, $dados);
		
		if(empty($result))	
			return null;

		return $result[0]->id;
	}
	
	public function curso_existe($id){
		$sql = "SELECT id FROM mdl_course where id = :id;";
		$dados = array(":id" => $id);
		$result = $this->query($sql, $dados);
		
		if(empty($result))	
			return false;
		
		return true;
	}

	public function desassociar_professor($id_curso, $id_professor){
		
		$id_contexto = $this->findContext($id_curso);
		
		if($id_contexto != null){
			
			$sql = "DELETE FROM {$this->prefix}role_assignments WHERE contextid = :id_contexto AND userid = :id;";
			
			$dados = array(':id_contexto' => $id_contexto, ':id' => $id_professor);
			
			return $this->executeUpdateOrDelete($sql, $dados);

		}
	}

	public function desmatricular($id_curso, $id_aluno){
		
		$id_contexto = $this->findContext($id_curso);
		
		if($id_contexto != null){
			
			$sql = "DELETE FROM {$this->prefix}role_assignments WHERE contextid = :id_contexto AND userid = :id;";
			
			$dados = array(':id_contexto' => $id_contexto, ':id' => $id_aluno);
			
			return $this->executeUpdateOrDelete($sql, $dados);

		}
	}

}

<?php

/**
 * Classe responsável pela manipulação de Usuários do Moodle no banco de dados
 */
class Dao_Usuario extends Dao_Generic
{

	public function insert($nome,$sobrenome,$email,$username,$password, $forcePasswordChange){

		//Verificar se o e-mail e o username são únicos antes de inserir
		if(!$this->checkUsername($username))
			return array('id' => '', 'result' => false, 'message' => utf8_encode('Nome de usuário já cadastrado no moodle'));
			
		if(!$this->checkEmail($email))
			return array('id' => '','result' => false, 'message' => utf8_encode('E-mail já cadastrado no moodle'));
		
		if($password == NULL || str_replace(" ","", $password) == "")
			return array('id' => '','result' => false, 'message' => utf8_encode('Senha é obrigatório'));
		
				
		$sql = 	"INSERT INTO {$this->prefix}user (firstname,lastname,email,username,password,confirmed,".
												  "mnethostid,lang, country) ".
				"VALUES (?,?,?,?,MD5(?),1,(SELECT value FROM {$this->prefix}config WHERE name='mnet_localhost_id'),'pt_br_utf8', 'BR')";

		$dados = array($nome, $sobrenome, $email, $username, $password);

		$result = $this->executeInsert($sql, $dados);
		
		if($forcePasswordChange && $result['result']){
			$sqlForceChange = "INSERT INTO {$this->prefix}user_preferences(userid,name,value) VALUES(?,'auth_forcepasswordchange', '1')";
			$result = $this->execute($sqlForceChange, array($id));
		}
						
		return $result;
		
	}
	
	public function update($id, $nome,$sobrenome,$email,$username,$password){
		
		if($password == NULL || str_replace(" ","", $password) == "")
			return array('id' => '','result' => false, 'message' => utf8_encode('Senha é obrigatório'));
		
				
		$sql = 	"UPDATE {$this->prefix}user SET firstname = :firstname, lastname = :lastname, email = :email, username = :username, password = MD5(:password)WHERE ID = :id";
		
		$dados = array( ':firstname' => $nome, ':lastname' => $sobrenome, ':email' => $email, ':username' => 
			$username, ':password' => $password, ':id' => $id);
						
		$result = $this->executeUpdateOrDelete($sql, $dados);
		
		if($result['result'])
			$result['id'] = $id;

		return $result;
		
	}

	public function delete($id){
						
		$sql = 	"DELETE FROM {$this->prefix}user WHERE ID = :id";
		
		$dados = array(':id' => $id);
						
		$result = $this->executeUpdateOrDelete($sql, $dados);

		return $result;
		
	}

	/**
	 * Verifica se o nome de usuário é único
	 * @param username
	 * @return true caso o username seja único
	 */
	public function checkUsername($username){
		$sql = "SELECT username FROM {$this->prefix}user WHERE username = ?";
		
		$dao = new Dao_Usuario();
		$result = $dao->query($sql, array($username));
		
		return empty($result);
	}

	/**
	 * Verifica se o email do usuário é único
	 * @param email
	 * @return true caso o email seja único
	 */
	public function checkEmail($email){
		$sql = "SELECT email FROM {$this->prefix}user WHERE email = ?";
		
		$dao = new Dao_Usuario();
		$result = $dao->query($sql, array($email));
		
		return empty($result);
	}

	public function usuario_existe($id){
		$sql = "SELECT id FROM mdl_user where id = :id;";
		$dados = array(":id" => $id);
		$result = $this->query($sql, $dados);
		
		if(empty($result))	
			return false;
		
		return true;
	}
	
}

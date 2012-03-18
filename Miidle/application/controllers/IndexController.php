<?php

class IndexController extends Miidle_Controller_Base
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $log_mapper = new Application_Model_LogMapper();
        $since = $log_mapper->lastId();
        
    	// Sincronizar professores

		$param = (object) array(
            'context' => 'PROFESSOR',
            'import_method' => 'doImportarProfessores',
            'insert_method' => 'doInserirProfessor',
            'update_method' => 'doAtualizarProfessor',
            'delete_method' => 'doExcluirProfessor',
            'confirm_method' => 'doConfirmarProfessor'
        );
        $this->SyncLegacyData($param);

		// Sincronizar alunos

        $param = (object) array(
            'context' => 'ALUNO',
            'import_method' => 'doImportarAlunos',
            'insert_method' => 'doInserirAluno',
            'update_method' => 'doAtualizarAluno',
            'delete_method' => 'doExcluirAluno',
            'confirm_method' => 'doConfirmarAluno'
        );
        $this->SyncLegacyData($param);

        // Sincronizar turmas

        $param = (object) array(
            'context' => 'TURMA',
            'import_method' => 'doImportarTurmas',
            'insert_method' => 'doInserirTurma',
            'update_method' => 'doAtualizarTurma',
            'delete_method' => 'doExcluirTurma',
            'confirm_method' => 'doConfirmarTurma'
        );
        $this->SyncLegacyData($param);

        // Sincronizar associações de alunos

        $param = (object) array(
            'context' => 'MATRICULA',
            'import_method' => 'doImportarAssociacaoAluno',
            'insert_method' => 'doAssociarAluno',
            'update_method' => 'doAssociarAluno',
            'delete_method' => 'doDesassociarAluno',
            'confirm_method' => 'doConfirmarAssociacaoAluno'
        );
        $this->SyncLegacyData($param);        

        // Sincronizar notas

        $param = (object) array(
            'context' => 'NOTA',
            'import_method' => 'doImportarNotas',
            'insert_method' => 'doInserirNota',
            'update_method' => 'doInserirNota',
            'delete_method' => 'doInserirNota',
            'confirm_method' => 'doConfirmarNota'
        );
        $this->SyncMoodleData($param);
        
        $this->view->log_entries = $log_mapper->fetchAllSince($since);
        $this->getRequest()->setControllerName('log')->setActionName('show');
    }
}

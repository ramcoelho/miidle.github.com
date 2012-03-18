<?php

class AlunoController extends Miidle_Controller_Base
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->_helper->redirector('importar');
    }

    public function importarAction()
    {
        $log_mapper = new Application_Model_LogMapper();
        $since = $log_mapper->lastId();
        $param = (object) array(
            'context' => 'ALUNO',
            'import_method' => 'doImportarAlunos',
            'insert_method' => 'doInserirAluno',
            'update_method' => 'doAtualizarAluno',
            'delete_method' => 'doExcluirAluno',
            'confirm_method' => 'doConfirmarAluno'
        );
        $this->SyncLegacyData($param);
        $this->view->log_entries = $log_mapper->fetchAllSince($since);
        $this->getRequest()->setControllerName('log')->setActionName('show');
    }

    public function associarAction()
    {
        $log_mapper = new Application_Model_LogMapper();
        $since = $log_mapper->lastId();
        $param = (object) array(
            'context' => 'MATRICULA',
            'import_method' => 'doImportarAssociacaoAluno',
            'insert_method' => 'doAssociarAluno',
            'update_method' => 'doAssociarAluno',
            'delete_method' => 'doDesassociarAluno',
            'confirm_method' => 'doConfirmarAssociacaoAluno'
        );
        $this->SyncLegacyData($param);
        $this->view->log_entries = $log_mapper->fetchAllSince($since);
        $this->getRequest()->setControllerName('log')->setActionName('show');
    }
}

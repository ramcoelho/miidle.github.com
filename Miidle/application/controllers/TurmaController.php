<?php

class TurmaController extends Miidle_Controller_Base
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
            'context' => 'TURMA',
            'import_method' => 'doImportarTurmas',
            'insert_method' => 'doInserirTurma',
            'update_method' => 'doAtualizarTurma',
            'delete_method' => 'doExcluirTurma',
            'confirm_method' => 'doConfirmarTurma'
        );
        $this->SyncLegacyData($param);
        $this->view->log_entries = $log_mapper->fetchAllSince($since);
        $this->getRequest()->setControllerName('log')->setActionName('show');
    }
}

<?php

class ProfessorController extends Miidle_Controller_Base
{
	protected $_context;

    public function init()
    {
        //
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
            'context' => 'PROFESSOR',
            'import_method' => 'doImportarProfessores',
            'insert_method' => 'doInserirProfessor',
            'update_method' => 'doAtualizarProfessor',
            'delete_method' => 'doExcluirProfessor',
            'confirm_method' => 'doConfirmarProfessor'
        );

        $this->SyncLegacyData($param);
        $this->view->log_entries = $log_mapper->fetchAllSince($since);
        $this->getRequest()->setControllerName('log')->setActionName('show');
    }
}

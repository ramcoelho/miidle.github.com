<?php

class NotaController extends Miidle_Controller_Base
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

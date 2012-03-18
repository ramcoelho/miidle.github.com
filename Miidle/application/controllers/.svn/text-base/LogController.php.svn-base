<?php

class LogController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->_helper->redirector('show');
    }

    public function showAction()
    {
        $page_size = 100;

        $this->view->disable_message = true;
        $this->view->show_pages = true;

        $page = $this->_getParam('page');
        if(empty($page)) $page = 1;

        $log_mapper = new Application_Model_LogMapper();
        $this->view->page_count = (int) (($log_mapper->count() / $page_size) + 1);
        $this->view->pagina = $page;
        $this->view->log_entries = $log_mapper->fetchAllPaged($page, $page_size);
    }


}




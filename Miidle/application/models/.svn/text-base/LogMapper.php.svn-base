<?php

class Application_Model_LogMapper
{
	protected $_dbTable;

	public function setDbTable($dbTable)
	{
		if (is_string($dbTable)){
			$dbTable = new $dbTable();
		}
		if (!$dbTable instanceof Zend_Db_Table_Abstract) {
			throw new Exception('Invalid table data gateway provided');
		}
		$this->_dbTable = $dbTable;
		return $this;
	}

	public function getDbTable()
	{
		if (null === $this->_dbTable) {
			$this->setDbTable('Application_Model_DbTable_Log');
		}
		return $this->_dbTable;
	}

	public function save(Application_Model_Log $model)
	{
		$data = array(
			'id' => $model->getId(),
			'origin' => $model->getOrigin(),
			'destination' => $model->getDestination(),
			'tstamp' => $model->getTstamp(),
			'message' => $model->getMessage()
		);

		if (null === ($id = $model->getId())) {
			unset($data['id']);
			$this->getDbTable()->insert($data);
		} else {
			$this->getDbTable()->update($data, array('id = ?' => $id));
		}
	}
	public function find($id, Application_Model_Log $model)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}
		$row = $result->current();
		$model->setId($row->id)
			->setOrigin($row->origin)
			->setDestination($row->destination)
			->setTstamp($row->tstamp)
			->setMessage($row->message);
	}

	public function fetchAllPaged($page = 1, $page_size = 100)
	{
		$table = $this->getDbTable();
		$resultSet = $table->fetchAll($table->select()
			->order('id DESC')
			->limit($page_size, ($page - 1) * $page_size)
		);
                                         		
		$entries = array();
		foreach ($resultSet as $row) {
			$entry = new Application_Model_Log();
			$entry->setId($row->id)
				->setOrigin($row->origin)
				->setDestination($row->destination)
				->setTstamp($row->tstamp)
				->setMessage($row->message);
			$entries[] = $entry;
		}
		return $entries;
	}

	public function fetchAllSince($since_id)
	{
		$table = $this->getDbTable();
		$resultSet = $table->fetchAll($table->select()
			->order('id DESC')
			->where('id > ?', $since_id)
		);
                                         		
		$entries = array();
		foreach ($resultSet as $row) {
			$entry = new Application_Model_Log();
			$entry->setId($row->id)
				->setOrigin($row->origin)
				->setDestination($row->destination)
				->setTstamp($row->tstamp)
				->setMessage($row->message);
			$entries[] = $entry;
		}
		return $entries;
	}

	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		$entries = array();
		foreach ($resultSet as $row) {
			$entry = new Application_Model_Log();
			$entry->setId($row->id)
				->setOrigin($row->origin)
				->setDestination($row->destination)
				->setTstamp($row->tstamp)
				->setMessage($row->message);
			$entries[] = $entry;
		}
		return $entries;
	}

	public function lastId()
	{
		$table = $this->getDbTable();
		$resultSet = $table->fetchAll($table->select()
			->from('log', array('max' => 'max(id)')));

		foreach ($resultSet as $row) {
			$max = $row['max'];
		}
		return $max;
	}

	public function count()
	{
		$table = $this->getDbTable();
		$resultSet = $table->fetchAll($table->select()
			->from('log', array('count' => 'count(1)')));

		foreach ($resultSet as $row) {
			$count = $row['count'];
		}
		return $count;
	}	
}

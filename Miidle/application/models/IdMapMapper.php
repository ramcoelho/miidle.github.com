<?php

class Application_Model_IdMapMapper
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
			$this->setDbTable('Application_Model_DbTable_IdMap');
		}
		return $this->_dbTable;
	}

	public function save(Application_Model_IdMap $model)
	{
		$data = array(
			'id' => $model->getId(),
			'context' => $model->getContext(),
			'id_legacy' => $model->getIdLegacy(),
			'id_moodle' => $model->getIdMoodle()
		);

		if (null === ($id = $model->getId())) {
			unset($data['id']);
			$this->getDbTable()->insert($data);
		} else {
			$this->getDbTable()->update($data, array('id = ?' => $id));
		}
	}
	public function find($id, Application_Model_IdMap $model)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}
		$row = $result->current();
		$model->setId($row->id)
			->setContext($row->context)
			->setIdLegacy($row->id_legacy)
			->setIdMoodle($row->id_moodle);
	}

	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		$entries = array();
		foreach ($resultSet as $row) {
			$entry = new Application_Model_IdMap();
			$entry->setId($row->id)
				->setContext($row->context)
				->setIdLegacy($row->id_legacy)
				->setIdMoodle($row->id_moodle);
			$entries[] = $entry;
		}
		return $entries;
	}

	public function findWhere($where, Application_Model_IdMap $model)
	{
		//fixme - Encontrar o registro com base nas diversas condicoes do array $where

		$select = $this->getDbTable()->select();
		foreach($where as $expr => $val) {
			$select->where($expr, $val);
		}
 
		$row = $this->getDbTable()->fetchRow($select);

		if(null != $row) {
			$model->setId($row->id)
				->setContext($row->context)
				->setIdLegacy($row->id_legacy)
				->setIdMoodle($row->id_moodle);
		}
		return $this;
	}
}

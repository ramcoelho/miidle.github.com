<?php

class Miidle_Data_IdMapper
{
	static public function mapToMoodle($context, $id_legacy)
	{
		$idmap = new Application_Model_IdMap;
		$idmap_mapper = new Application_Model_IdMapMapper();
		$idmap_mapper->findWhere(array('id_legacy = ?' => $id_legacy, 'context = ?' => $context), $idmap);
		return $idmap->getIdMoodle();
	}
	static public function mapToLegacy($context, $id_moodle)
	{
		$idmap = new Application_Model_IdMap;
		$idmap_mapper = new Application_Model_IdMapMapper();
		$idmap_mapper->findWhere(array('id_moodle = ?' => $id_moodle, 'context = ?' => $context), $idmap);
		return $idmap->getIdLegacy();
	}
	static public function createMapForMoodleId($context, $id_legacy, $id_moodle)
	{
		$idmap = new Application_Model_IdMap;
		$idmap_mapper = new Application_Model_IdMapMapper();

		// Remove mapeamentos anteriores, se existirem, para este Moodle Id

		$idmap_mapper->findWhere(array('id_moodle = ?' => $id_moodle, 'context = ?' => $context), $idmap);

		$idmap->setId($idmap->getId())
			->setContext($context)
			->setIdLegacy($id_legacy)
			->setIdMoodle($id_moodle);
		$idmap_mapper->save($idmap);
	}
	static public function createMapForLegacyId($context, $id_legacy, $id_moodle)
	{
		$idmap = new Application_Model_IdMap;
		$idmap_mapper = new Application_Model_IdMapMapper();

		// Remove mapeamentos anteriores, se existirem, para este Moodle Id

		$idmap_mapper->findWhere(array('id_legacy = ?' => $id_legacy, 'context = ?' => $context), $idmap);

		$idmap->setId($idmap->getId())
			->setContext($context)
			->setIdLegacy($id_legacy)
			->setIdMoodle($id_moodle);
		$idmap_mapper->save($idmap);
	}	
}
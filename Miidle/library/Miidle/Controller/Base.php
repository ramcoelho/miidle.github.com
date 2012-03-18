<?php

class Miidle_Controller_Base extends Zend_Controller_Action
{
	public function SyncLegacyData($param, $data = NULL)
	{
        $sync_result = true;
               
		$import_method = $param->import_method;
		$insert_method = $param->insert_method;
		$update_method = $param->update_method;
		$delete_method = $param->delete_method;
		$confirm_method = $param->confirm_method;

        $ilegacy = new Miidle_Rest_Consumer('ilegacy');
        $imoo = new Miidle_Rest_Consumer('imoo');

        // Busca os itens em Lagacy para inclusão no Moodle. Todos os registros vêm com id_op (operação a ser
	    // confirmada), id no sistema legado e dados para inclusão

        if(!empty($data)) {
            $obj_resposta = $ilegacy->$import_method($data);
        } else {
            $obj_resposta = $ilegacy->$import_method();
        }

        foreach($obj_resposta->results as $obj_record) {
        	// Separar dados de operação e inclusão

            $data = Miidle_Data_Handler::getData($obj_record);
            $operacao = Miidle_Data_Handler::getOperacao($obj_record);
            $id_op = Miidle_Data_Handler::getIdOperacao($obj_record);

            // Verifica se o id do sistema legado já foi mapeado para o um id moodle, e retorna-lo neste caso

			$id_legacy = $data['id'];
			$data['id'] = Miidle_Data_IdMapper::mapToMoodle($param->context, $id_legacy);

			// Se nao ha id moodle, deve ser uma inclusao. Remova o campo id

            // Mapear ids de foreign keys
            foreach($data as $key => $value) {
                $parts = explode('_', $key, 2);
                if(sizeof($parts) == 2) {
                    if($parts[0] == 'id') {
                        $my_context = strtoupper($parts[1]);
                        $data[$key] = Miidle_Data_IdMapper::mapToMoodle($my_context, $value);
                    }
                }
            }

			if(empty($data['id']))
				unset($data['id']);

            switch($operacao) {
                case DATAOP_INSERT:
                    $obj_resposta = $imoo->$insert_method($data);
                    break;
                case DATAOP_UPDATE:
                    $obj_resposta = $imoo->$update_method($data);
                    break;
                case DATAOP_DELETE:
                    $obj_resposta = $imoo->$delete_method($data);
                    break;
            }
            if($obj_resposta->result) {
            	// Se a operacao foi bem sucedida, marcar registro de op para exclusao e criar ou
            	// atualizar mapeamento

                $ilegacy->$confirm_method(array('_id_op' => $id_op));
            	Miidle_Data_IdMapper::createMapForLegacyId($param->context, $id_legacy, $obj_resposta->id);
            } else {
                $sync_result = false;
            }
        }
        return $sync_result;
    }
	public function SyncMoodleData($param, $data = NULL)
	{
        $sync_result = true;

		$import_method = $param->import_method;
		$insert_method = $param->insert_method;
		$update_method = $param->update_method;
		$delete_method = $param->delete_method;
		$confirm_method = $param->confirm_method;

        $ilegacy = new Miidle_Rest_Consumer('ilegacy');
        $imoo = new Miidle_Rest_Consumer('imoo');

        // Busca os itens do Moodle para inclusão em Lagacy. Todos os registros vêm com id_op (operação a ser
	    // confirmada), id no sistema legado e dados para inclusão

        if(!empty($data)) {
            $obj_resposta = $imoo->$import_method($data);
        } else {
            $obj_resposta = $imoo->$import_method();
        }
        
        foreach($obj_resposta->results as $obj_record) {
        	// Separar dados de operação e inclusão

            $data = Miidle_Data_Handler::getData($obj_record);
            $operacao = Miidle_Data_Handler::getOperacao($obj_record);
            $id_op = Miidle_Data_Handler::getIdOperacao($obj_record);

            // Verifica se o id do sistema legado já foi mapeado para o um id moodle, e retorna-lo neste caso

			$id_moodle = $data['id'];
			$data['id'] = Miidle_Data_IdMapper::mapToLegacy($param->context, $id_moodle);

			// Se nao ha id moodle, deve ser uma inclusao. Remova o campo id

            // Mapear ids de foreign keys
            foreach($data as $key => $value) {
                $parts = explode('_', $key, 2);
                if(sizeof($parts) == 2) {
                    if($parts[0] == 'id') {
                        $my_context = strtoupper($parts[1]);
                        $data[$key] = Miidle_Data_IdMapper::mapToLegacy($my_context, $value);
                    }
                }
            }

			if(empty($data['id']))
				unset($data['id']);

            switch($operacao) {
                case DATAOP_INSERT:
                    $obj_resposta = $ilegacy->$insert_method($data);
                    break;
                case DATAOP_UPDATE:
                    $obj_resposta = $ilegacy->$update_method($data);
                    break;
                case DATAOP_DELETE:
                    $obj_resposta = $ilegacy->$delete_method($data);
                    break;
            }
            if($obj_resposta->result) {
            	// Se a operacao foi bem sucedida, marcar registro de op para exclusao e criar ou
            	// atualizar mapeamento

            	$imoo->$confirm_method(array('_id_op' => $id_op));
            	Miidle_Data_IdMapper::createMapForMoodleId($param->context, $obj_resposta->id, $id_moodle);
            } else {
                $sync_result = false;
            }
        }
        return $sync_result;
    }
}

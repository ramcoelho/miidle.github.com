<?php

define('BASE_PATH', realpath(dirname(__FILE__)));
define('LIBRARY_PATH', BASE_PATH . '/lib');
define('MODEL_PATH', BASE_PATH . '/model');

require 'Grs/Grs.php';

function __autoload($class_name)
{
	$require_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';
	require(LIBRARY_PATH . '/' . $require_file);
}

if(getenv('DISABLE_MIIDLE_PACK') != 1) {
	if(!isset($_POST['pack'])) {
		header('Content-type: text/plain; charset=utf-8');
		die('Acesso negado! - Pack não informado');
	}
				
	if( ! Helper_Auth::esta_autorizado($_POST['pack'])) {
		header('Content-type: text/plain; charset=utf-8');
		die('Acesso negado! - Não autorizado!');
	}
}

$grs = new Grs();
$grs->setModelsPath(MODEL_PATH);
$grs->dispatch();

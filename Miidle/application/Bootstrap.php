<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	function _initChave()
	{
		$app = $this->getApplication()->getOption('app');
		define('APPKEY', $app['key']);
		define('APPNAME', $app['name']);
		define('BASEURI', str_replace('/index.php', '', $_SERVER['PHP_SELF']));
	}

}


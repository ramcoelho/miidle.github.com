<?php
class Zend_View_Helper_ConverteData extends Zend_View_Helper_Abstract
{
	public function converteData($db_data)
	{
		$screen_data = preg_replace('/([0-9]{4})-([0-9]{2})-([0-9]{2}) (.*)$/', '\3/\2/\1 \4', $db_data);
		return $screen_data;
	}
}
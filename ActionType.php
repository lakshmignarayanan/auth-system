<?php

class ActionType {
	
	private static $db_handle = null;
	
	function __construct()
	{
		$this->initDatabase();
	}

	private function initDatabase($mode = 'r') {
		$db = fopen(__DIR__.'/database/actiontype.csv', $mode);
		if (!$db) {
			throw new Exception("actiontype db not initiated", 1);
		}
		self::$db_handle = $db;
	}

	// boolean method - returns true if user exists
	public static function checkExists($name) {
		error_log("ActionType checkExists = " . $name);
		return self::getActionTypeId($name) > 0;
	}

	// returns boolean
	public static function getActionTypeId($name) {
		if (empty($name)) {
			throw new Exception("Empty action name!", 1);
		}
		self::initDatabase();
		// if (self::$db_handle === FALSE || is_null(self::$db_handle)) {
		// 	throw new Exception("Role db not initiated", 1);
		// }
		$id = 0;
		while (($actions = fgetcsv(self::$db_handle, 1000, ',')) !== FALSE) {
			if ($name === $actions[1]) {
				$id = $actions[0];
				break;
			}
		}
		return $id;
	}

}
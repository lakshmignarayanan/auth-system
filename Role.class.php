<?php

class Role {
	
	private static $db_handle = null;
	
	function __construct($role /*, $action_type = 'r'*/)
	{
		$this->initDatabase();
	}

	private function initDatabase($mode = 'r') {
		$db = fopen(__DIR__.'/database/role.csv', $mode);
		if (!$db) {
			throw new Exception("role db not initiated", 1);
		}
		self::$db_handle = $db;
	}

	// boolean method - returns true if user exists
	public static function checkExists($name) {
		error_log("Role checkExists = " . $name);
		return self::getRoleId($name) > 0;
	}

	// returns boolean
	public static function getRoleId($name) {
		if (empty($name)) {
			throw new Exception("Empty role name!", 1);
		}
		self::initDatabase();
		// if (self::$db_handle === FALSE || is_null(self::$db_handle)) {
		// 	throw new Exception("Role db not initiated", 1);
		// }
		$id = 0;
		while (($roles = fgetcsv(self::$db_handle, 1000, ',')) !== FALSE) {
			if ($name === $roles[1]) {
				$id = $roles[0];
				break;
			}
		}
		return $id;
	}

}
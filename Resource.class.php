<?php

class Resource {
	
	private static $db_handle = null;
	
	function __construct()
	{
		$this->initDatabase();
	}

	private function initDatabase($mode = 'r') {
		$db = fopen(__DIR__.'/database/resource.csv', $mode);
		if (!$db) {
			throw new Exception("resource db not initiated", 1);
		}
		self::$db_handle = $db;
	}

	// boolean method - returns true if user exists
	public static function checkExists($name) {
		return self::getResourceId($name) > 0;
	}

	// returns boolean
	public static function getResourceId($name) {
		if (empty($name)) {
			throw new Exception("Empty resource name!", 1);
		}
		self::initDatabase();
		// if (self::$db_handle === FALSE || is_null(self::$db_handle)) {
		// 	throw new Exception("Role db not initiated", 1);
		// }
		$id = 0;
		while (($resources = fgetcsv(self::$db_handle, 1000, ',')) !== FALSE) {
			error_log("name = " . $name . " resource = " . $resources[1]);
			if ($name === $resources[1]) {
				$id = $resources[0];
				break;
			}
		}
		return $id;
	}

}
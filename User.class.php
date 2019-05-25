<?php

require_once __DIR__ . '/UserRole.php';

class User {

	private static $db_handle = null;
	
	function __construct($user /*, $action_type = 'r'*/)
	{
		$this->user = $user;
	}

	private function initDatabase($mode = 'r') {
		$db = fopen(__DIR__.'/database/user.csv', $mode);
		if (!$db) {
			throw new Exception("user db not initiated", 1);
		}
		self::$db_handle = $db;
	}

	// boolean method - returns true if user exists
	public static function checkExists($email) {
		return self::getUserId($email) > 0;
	}

	// returns boolean
	public static function getUserId($email) {
		self::initDatabase();
		if (empty($email)) {
			throw new Exception("Empty email!", 1);
		}
		if (self::$db_handle === FALSE || is_null(self::$db_handle)) {
			throw new Exception("User db not initiated", 1);
		}
		$id = 0;
		while (($users = fgetcsv(self::$db_handle, 1000, ',')) !== FALSE) {
			if ($email === $users[1]) {
				$id = $users[0];
				break 1;
			}
		}
		return $id;
	}

}
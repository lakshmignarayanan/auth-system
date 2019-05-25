<?php

class ResourceAccess {

	private static $db_handle = null;
	
	private function init_database($mode = 'r') {
		$db = fopen(__DIR__.'/database/role_resource_actiontype.csv', $mode);
		if (!$db) {
			throw new Exception("actiontype db not initiated", 1);
		}
		self::$db_handle = $db;
	}

	// returns boolean if the role has access on a resource for that action type
	public static function validate($role_id, $resource_id, $action_id) {
		self::init_database();
		$has_access = FALSE;
		while (($resource_access = fgetcsv(self::$db_handle, 1000, ',')) !== FALSE) {
			if ($role_id == $resource_access[0] && $resource_id == $resource_access[1] && $action_id == $resource_access[2]) {
				// has access. return true
				$has_access = TRUE;
				break;
			}
		}
		return $has_access;
	}

}
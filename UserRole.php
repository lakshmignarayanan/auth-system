<?php

class UserRole {

	private $user_id;
	private $role_id;
	private static $db, $db2 = null;
	private $operation_types = array( //add,delete roles <-> user
		'add' => 'a',
		'delete' => 'r', // need to read and recreate another file
	);
	private $operation;
	
	public function __construct($user_id, $role_id, $operation = 'add')
	{
		$this->user_id = $user_id;
		$this->role_id = $role_id;
		$this->operation = $operation;
	}

	private function init_database($mode = 'r') {
		if (($db_handle = fopen(__DIR__.'/database/user_role.csv', $mode)) === FALSE) {
			throw new Exception("error initiating user_role db", 1);
		}
		self::$db = $db_handle;
	}

	public function addRoleToUser() {
		//check if user has role first
		if (self::userHasRole($this->user_id, $this->role_id)) {
			return "user already has the role";
		}
		$this->init_database('a+');
		fwrite(self::$db, "\n".$this->user_id . ",". $this->role_id);
		fclose(self::$db);
		return "\nadded successfully\n";
	}

	public function deleteRoleFromUser() {
		if (!self::userHasRole($this->user_id, $this->role_id)) {
			return "user doesn't have role";
		}
		$this->init_database('r');
		$this->init_temp_database();
		while (($user_role = fgetcsv(self::$db, 1000, ',')) !== FALSE) {
			if (($user_role[0] == $this->user_id && $user_role[1] == $this->role_id) || empty($user_role[0]) || empty($user_role[1])) {
				// skip this role in our new db
			} else {
				// fputcsv(self::$db2, $user_role);
				fwrite(self::$db2, "\n".$user_role[0] . ",". $user_role[1]);
			}
		}
		// now rename the new db file into old one
		fclose(self::$db2);
		fclose(self::$db);
		$this->rename_db();
		return "role deleted from user";
	}

	private function rename_db() {
		rename(__DIR__ . '/database/user_role_new.csv', __DIR__ . '/database/user_role.csv');
	}

	public static function userHasRole($userid, $roleid) {
		if (empty($userid) || empty($roleid)) {
			throw new Exception("userid/roleid empty", 1);
		}
		$hasrole = false;
		self::init_database('r');
		while (( $user_role = fgetcsv(self::$db, 1000, ',')) !== FALSE) {
			if ($user_role[0] == $userid && $user_role[1] == $roleid) {
				$hasrole = true;
				break;
			}
		}
		fclose(self::$db);
		return $hasrole;
	}

	// returns an array of roles assigned to an user
	public static function getRolesByUser($user_id) {
		self::init_database('r');
		$roles = [];
		while (($user_roles = fgetcsv(self::$db, 1000, ',')) !== FALSE) {
			if ($user_roles[0] == $user_id) {
				$roles[] = $user_roles[1];
			}
		}
		fclose(self::$db);
		return $roles;
	}

	private function init_temp_database() {
		if (($temp_db = fopen(__DIR__.'/database/user_role_new.csv', 'w')) === FALSE) {
			throw new Exception("error initiating temp user_role db", 1);
		}
		self::$db2 = $temp_db;
	}

}
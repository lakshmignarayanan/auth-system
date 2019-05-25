<?php

require_once __DIR__ . '/autoload.php';

class Authenticate {

	private $user;
	private $role;
	
	public function __construct($role, $user)
	{
		$this->role = $role;
		$this->user = $user;
	}

	public function validateRelation()
	//:D :D :D
}
<?php

require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../Role.class.php';

class ObjectValidation {

	private $object_type = [];

	private $object = null;

	function __construct()
	{
		error_log("ObjectValidation constructor called");
		$this->object_type = array(
			'user' => 'User',
			'role' => 'Role',
		);
	}
	
	public function validate($value, $type)
	{
		error_log("validate params. value = " . $value . " type = " . $type);
		error_log("object_type = " . json_encode($this->object_type));
		if (!array_key_exists($type, $this->object_type)) {
			throw new Exception("Invalid type passed for object validaiton", 1);
		}

		$class = $this->object_type[$type];
		error_log("class = " . $class);
		$object = new $class($value);

		return $object->checkExists($value);
	}

}
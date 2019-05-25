<?php

spl_autoload_register(function($class){
	$lookup = array(
		'User' => __DIR__ . '/User.class.php',
		'Auth' => __DIR__ . '/Auth.class.php',
		'Role' => __DIR__ . '/Role.class.php',
		'UserRole' => __DIR__ . '/UserRole.php',
		'Resource' => __DIR__ . '/Resource.class.php',
		'ResourceAccess' => __DIR__ . '/ResourceAccess.class.php',
		'ActionType' => __DIR__ . '/ActionType.php',
	);

	if (isset($lookup[$class])) {
		require $lookup[$class];
	}
});
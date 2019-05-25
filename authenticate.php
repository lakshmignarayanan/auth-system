<?php

require_once __DIR__ . '/autoload.php';

// actions performed
/*
1. assign role to user
2. remove role from user
3. check access to resource for a user
*/

echo "1. add role to an user\n2.delete role from an user\n3.check resource access for an user";
echo "\n\nWhat do you want to do? press 0 to exit\n";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
if(trim($line) === '0'){
    echo "ABORTING!\n";
    exit;
}
// error_log("line = " . $line);
switch ($line) {
	case 1:
		//get inputs and add role
		$inputs = getInputsAndSanitize();
		//go into the core logic. now add role to user in UserRole
		$userRole = new UserRole($inputs['user_id'], $inputs['role_id']);
		echo $userRole->addRoleToUser();
		break;
	case 2:
		// get inputs and delete role
		$inputs = getInputsAndSanitize();
		// business logic. now delete role from user in UserRole
		$userRole = new UserRole($inputs['user_id'], $inputs['role_id']);
		echo $userRole->deleteRoleFromUser();
		break;
	case 3:
		//get email, resource, actiontype
		$inputs = getInputsAndSanitize(true);
		break;
	default:
		# code...
		echo "hello!";
		break;
}

echo "\n\nThank you for the opportunity\n";

function getInputsAndSanitize($authorize = false) {
	$user_email = sanitize(readline('Enter user email:'), 'email');
	$userid = User::getUserId($user_email);
	if (!$userid) {
		echo "user not in the system\n";
		exit;
	}

	if ($authorize) {
		echo authorizeUserAction($userid);
		exit;
	}

	$role_name = sanitize(readline('Enter role you want to add/delete [devops,teamlead,dev,intern]:'));
	error_log("sanitized inouts = " . $user_email . " / " . $role_name);
	
	$roleid = Role::getRoleId($role_name);
	
	if (!$roleid) {
		echo "role not registered in the system\n";
		exit;
	}
	error_log("sanitized successfully");
	return array('user_id' => $userid, 'role_id' => $roleid);
}

function authorizeUserAction($user_id) {
	//get resource name, action type and print result
	$resource_name = sanitize(readline('Enter the resource to be accessed [proddb,stagingdb,devdb,readreplica]:'));
	$resource_id = Resource::getResourceId($resource_name);
	error_log("resource_id = " . $resource_id . " / " . $resource_name);
	$action = sanitize(readline('Enter the action to be performed [read,write,delete]:'));
	$actiontype_id = ActionType::getActionTypeId($action);
	error_log("actiontype_id = " . $actiontype_id . " / " . $action);
	if (!$resource_id || !$actiontype_id) {
		throw new Exception("invalid action type or resource", 1);
	}
	$user_roles = UserRole::getRolesByUser($user_id);
	error_log("user_roles = " . json_encode($user_roles));
	foreach ($user_roles as $role) {
		if (ResourceAccess::validate($role, $resource_id, $actiontype_id)) {
			return "user has access to perform the action\n";
		}
	}
	return "===sorry no access===\n";
}

function sanitize($value, $type = '') {
	if ($type == 'email') {
		return filter_var($value, FILTER_SANITIZE_EMAIL);
	}
	return preg_replace('/[^a-zA-Z0-9]/', '', $value);
}
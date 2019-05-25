<?php

require_once __DIR__ . '/autoload.php';

/*
actions that can be performed
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

switch ($line) {
	case 1:
		$inputs = getInputsAndSanitize();
		//add role to user
		$userRole = new UserRole($inputs['user_id'], $inputs['role_id']);
		echo $userRole->addRoleToUser();
		break;
	case 2:
		$inputs = getInputsAndSanitize();
		// delete role from user
		$userRole = new UserRole($inputs['user_id'], $inputs['role_id']);
		echo $userRole->deleteRoleFromUser();
		break;
	case 3:
		//get email, resource, actiontype
		$inputs = getInputsAndSanitize(true);
		break;
	default:
		echo "hello! i'm not an option ;)";
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
	$roleid = Role::getRoleId($role_name);
	if (!$roleid) {
		echo "role not registered in the system\n";
		exit;
	}
	return array('user_id' => $userid, 'role_id' => $roleid);
}

function authorizeUserAction($user_id) {
	//get resource name, action type and print result
	$resource_name = sanitize(readline('Enter the resource to be accessed [proddb,stagingdb,devdb,readreplica]:'));
	$resource_id = Resource::getResourceId($resource_name);
	$action = sanitize(readline('Enter the action to be performed [read,write,delete]:'));
	$actiontype_id = ActionType::getActionTypeId($action);
	if (!$resource_id || !$actiontype_id) {
		throw new Exception("\ninvalid action type or resource", 1);
	}
	$user_roles = UserRole::getRolesByUser($user_id);
	foreach ($user_roles as $role) {
		if (ResourceAccess::validate($role, $resource_id, $actiontype_id)) {
			return "\nuser has access to perform the action\n";
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
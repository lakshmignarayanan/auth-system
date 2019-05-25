# auth-system for database

This system authorizes the users for specific actions on particular resources based on the roles that are assigned to the user.
Currently the system has:
1. Resources [proddb, stagingdb, devdb, readreplica]
2. Action Types [read,write,delete]
3. Roles [devops,teamlead,dev,intern]
4. Users [lucky@gmail.com,shobana@outlook.com,vengat@hotmail.com]

Technologies used:
1. PHP
2. OOPS
3. CSV files as database

How to run:
run the php file authorize.php in your command line.

Functionalities:
1. Assign role to an user
2. Remove role from an user
3. Check access for resource to perform specific action

Assumption:
1. All roles have read access to readreplica.
2. Access to resources by other roles can be viewed in /database/role_resource_actiontype.csv

Important files to look at:
1. All the User/Role logic is handled in their respective class files.
2. ResourceAccess class handles the logic of validating the access to a user over a specific resource.
3. CSV databases are stored in `/database` folder.

Model relationships:
1. user-role maintains a 1-many relationship.
2. role-resource-actiontype maintains a many-many relationships between. A role can have any number of actiontypes on any number of resources.
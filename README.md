# auth-system for database

This system authorizes the users for specific actions on particular resources based on the roles that are assigned to the user.
Currently the system has:
1. Resources [proddb, stagingdb, devdb, readreplica]
2. Action Types [read,write,delete]
3. Roles [devops,teamlead,dev,intern]
4. Users [lucky@gmail.com,shobana@outlook.com,vengat@hotmail.com]

Functionalities:
1. Assign role to an user
2. Remove role from an user
3. Check access for resource to perform specific action

Assumption:
1. All roles have read access to readreplica.
2. Access to resources by other roles can be viewed in /database/role_resource_actiontype.csv


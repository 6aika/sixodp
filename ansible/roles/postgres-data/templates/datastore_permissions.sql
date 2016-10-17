/*
This script configures the permissions for the datastore.

It ensures that the datastore read-only user will only be able to select from
the datastore database but has no create/write/edit permission or any
permissions on other databases. You must execute this script as a database
superuser on the PostgreSQL server that hosts your datastore database.

For example, if PostgreSQL is running locally and the "postgres" user has the
appropriate permissions (as in the default Ubuntu PostgreSQL install), you can
run:

    paster datastore set-permissions | sudo -u postgres psql

Or, if your PostgreSQL server is remote, you can pipe the permissions script
over SSH:

    paster datastore set-permissions | ssh dbserver sudo -u postgres psql

*/

-- Most of the following commands apply to an explicit database or to the whole
-- 'public' schema, and could be executed anywhere. But ALTER DEFAULT
-- PERMISSIONS applies to the current database, and so we must be connected to
-- the datastore DB:
\connect datastore_default

-- revoke permissions for the read-only user
REVOKE CREATE ON SCHEMA public FROM PUBLIC;
REVOKE USAGE ON SCHEMA public FROM PUBLIC;

GRANT CREATE ON SCHEMA public TO "ckan_default";
GRANT USAGE ON SCHEMA public TO "ckan_default";

GRANT CREATE ON SCHEMA public TO "ckan_default";
GRANT USAGE ON SCHEMA public TO "ckan_default";

-- take connect permissions from main db
REVOKE CONNECT ON DATABASE "ckan_default" FROM "datastore_default";

-- grant select permissions for read-only user
GRANT CONNECT ON DATABASE "datastore_default" TO "datastore_default";
GRANT USAGE ON SCHEMA public TO "datastore_default";

-- grant access to current tables and views to read-only user
GRANT SELECT ON ALL TABLES IN SCHEMA public TO "datastore_default";

-- grant access to new tables and views by default
ALTER DEFAULT PRIVILEGES FOR USER "ckan_default" IN SCHEMA public
   GRANT SELECT ON TABLES TO "datastore_default";
   
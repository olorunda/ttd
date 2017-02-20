<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['dsn']      The full DSN string describe a connection to the database.
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database driver. e.g.: mysqli.
|			Currently supported:
|				 cubrid, ibase, mssql, mysql, mysqli, oci8,
|				 odbc, pdo, postgre, sqlite, sqlite3, sqlsrv
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Query Builder class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['encrypt']  Whether or not to use an encrypted connection.
|	['compress'] Whether or not to use client compression (MySQL only)
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|	['failover'] array - A array with 0 or more data for connections if the main should fail.
|	['save_queries'] TRUE/FALSE - Whether to "save" all executed queries.
| 				NOTE: Disabling this will also effectively disable both
| 				$this->db->last_query() and profiling of DB queries.
| 				When you run a query, with this setting set to TRUE (default),
| 				CodeIgniter will store the SQL statement for debugging purposes.
| 				However, this may cause high memory usage, especially if you run
| 				a lot of SQL queries ... disable this to avoid that problem.
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $query_builder variables lets you determine whether or not to load
| the query builder class.
*/

$active_group = 'default';
$query_builder = TRUE;

//failover approach however this has its limitation such that changes are committed to only one database at a time
/*$db['default'] = array(
        'dsn'   => '',
        'hostname' => '192.168.1.107',
        'username' => 'slave',
        'password' => '',
        'database' => 'stock',
        'dbdriver' => 'mysqli',
        'dbprefix' => 'sma_',
        'pconnect' => FALSE,
        'db_debug' => TRUE,
        'cache_on' => FALSE,
        'cachedir' => '',
        'char_set' => 'utf8',
        'dbcollat' => 'utf8_general_ci',
        'swap_pre' => '',
        'encrypt' => FALSE,
        'compress' => FALSE,
        'stricton' => FALSE,
        'failover' => array(
            array(
                    'hostname' => '127.0.0.1',
                    'username' => 'root',
                    'password' => '',
                    'database' => 'stock',
                    'dbdriver' => 'mysqli',
                    'dbprefix' => 'sma_',
                    'pconnect' => FALSE,
                    'db_debug' => TRUE,
                    'cache_on' => FALSE,
                    'cachedir' => '',
                    'char_set' => 'utf8',
                    'dbcollat' => 'utf8_general_ci',
                    'swap_pre' => '',
                    'encrypt' => FALSE,
                    'compress' => FALSE,
                    'stricton' => FALSE
            )
        )
);*/


$db['default']['hostname'] = '127.0.0.1';
$db['default']['username'] = 'root';
$db['default']['password'] = '';
$db['default']['database'] = 'stockmanager';
$db['default']['dbdriver'] = 'mysqli';
$db['default']['dbprefix'] = 'sma_';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

$db['cloud']['hostname'] = 'br-cdbr-azure-south-b.cloudapp.net';
$db['cloud']['username'] = 'b77978ec776080';
$db['cloud']['password'] = '69d1c926';
$db['cloud']['database'] = 'acsm_b22958f0be8325a';
$db['cloud']['dbdriver'] = 'mysqli';
$db['cloud']['dbprefix'] = 'sma_';
$db['cloud']['pconnect'] = FALSE;
$db['cloud']['db_debug'] = FALSE;
$db['cloud']['cache_on'] = FALSE;
$db['cloud']['cachedir'] = '';
$db['cloud']['char_set'] = 'utf8';
$db['cloud']['dbcollat'] = 'utf8_general_ci';
$db['cloud']['swap_pre'] = '';
$db['cloud']['autoinit'] = FALSE;
$db['cloud']['stricton'] = FALSE;

$db['cloud']['failover'][0]['hostname'] = '127.0.0.1';
$db['cloud']['failover'][0]['username'] = 'root';
$db['cloud']['failover'][0]['password'] = '';
$db['cloud']['failover'][0]['database'] = 'stockmanager';
$db['cloud']['failover'][0]['dbdriver'] = 'mysqli';
$db['cloud']['failover'][0]['dbprefix'] = 'sma_';
$db['cloud']['failover'][0]['pconnect'] = FALSE;
$db['cloud']['failover'][0]['db_debug'] = TRUE;
$db['cloud']['failover'][0]['cache_on'] = FALSE;
$db['cloud']['failover'][0]['cachedir'] = '';
$db['cloud']['failover'][0]['char_set'] = 'utf8';
$db['cloud']['failover'][0]['dbcollat'] = 'utf8_general_ci';
$db['cloud']['failover'][0]['swap_pre'] = '';
$db['cloud']['failover'][0]['autoinit'] = TRUE;
$db['cloud']['failover'][0]['stricton'] = FALSE;
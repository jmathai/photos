<?php
 /*******************************************************************************************
  * Name:  CDatabase.php
  *
  * General creation wrapper for database interaction
  *
  * Usage:
  *  include_once('CDatabase.php');
  *  $dbh = init_db(create_dsn($DB_CONFIG));
  * 
  *******************************************************************************************/

 /*******************************************************************************************
  * Description
  *   Queries a database
  *
  * Input
  *   $db_host = mixed, either a config array with keys 'dbms','host','user','pass','name' or the hostname/ip
  *   $db_user = string, username
  *   $db_pass = string, password
  *   $db_name = string, name of the database to connect to
  *   $dbms    = string, name of the dbms (default: mysql)
  *
  * Output
  *   string, contains the URL-like database name source string used to connect
  *******************************************************************************************/

function create_dsn($db_host='', $db_user='', $db_pass='', $db_name='', $dbms='mysql')
{
  if(is_array($db_host))
  {
    $dsn = "{$db_host['dbms']}://{$db_host['user']}:{$db_host['pass']}@{$db_host['host']}/{$db_host['name']}";
  }
  else
  {
    $dsn = "{$dbms}://{$db_user}:{$db_pass}@{$db_host}/{$db_name}";
  }
  return $dsn;
}

 /*******************************************************************************************
  * Description
  *   includes the appropriate DBMS database routines and returns a DB handle
  *
  * Input
  *   $dsn          = string, a speficially formatted connection string dbms://user:pass@host/name
  *
  * Output
  *   mixed, returns a database handle or a 0
  *******************************************************************************************/

function init_db($dsn = false)
{
  if($dsn === false)
  {
    return 0;
  }
  
  $dsn_params = parse_url($dsn);

  $dbms    = $dsn_params['scheme'];
  $db_host = $dsn_params['host'];
  $db_user = $dsn_params['user'];
  $db_pass = $dsn_params['pass'];
  $db_name = substr($dsn_params['path'], 1);
  
  switch($dbms)
  {
    case 'mysql':
      include_once(PATH_CLASS . '/CDatabase_MySQL.php');
      break;
  }
  
  $dbh = new CDatabase($db_host, $db_user, $db_pass, $db_name);
  return $dbh;
}
?>

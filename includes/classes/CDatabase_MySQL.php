<?php
/*******************************************************************************************
 * Name:        CDatabase_MySQL.php
 * Class Name:  CDatabase
 *------------------------------------------------------------------------------------------
 * Mod History: Chip Kellam     1/3/03
 *              Jaisen Mathai   
 *              Chip Kellam     October 22, 2002
 *------------------------------------------------------------------------------------------
 * MySQL database abstraction class
 *
 * Usage:
 *   include('/export/home/jacor-common/classes/CDatabase.php');
 *   $db = new CDatabase();
 *   $db->connect($host, $database, $username, $password);
 *   $db->execute( $sql );
 *   $db->close();
 * 
 *******************************************************************************************/
define('DBMS', 'MySQL');
define('CDATABASE_MYSQL_INCLUDED', true);

class CDatabase {
  /*******************************************************************************************
  * Description
  *   Class constructor (initializes variables)
  *
  * Input
  *   $db_host = (optional) database server ip or hostname
  *   $db_name = (optional) database name
  *   $db_user = (optional) user name
  *   $db_pass = (optional) valid password
  *******************************************************************************************/
  function CDatabase( $db_host = '', $db_user = '', $db_pass = '', $db_name = '' ) {
    $this->db_host = $db_host;
    $this->db_user = $db_user;
    $this->db_pass = $db_pass;
    $this->db_name = $db_name;

    $this->dbi      = false;
  }
  
 /*******************************************************************************************
  * Description
  *   Connects to a database server and specific database
  *
  * Input
  *   $db_host = (optional) database server ip or hostname
  *   $db_name = (optional) database name
  *   $db_user = (optional) user name
  *   $db_pass = (optional) valid password
  *
  * Output
  *   true/false = success/failure
  *******************************************************************************************/
  function connect( $db_host = '', $db_user = '', $db_pass = '', $db_name = '' ) {
    $this->db_host = ( $db_host != '' ) ? $db_host : $this->db_host;
    $this->db_user = ( $db_user != '' ) ? $db_user : $this->db_user;
    $this->db_pass = ( $db_pass != '' ) ? $db_pass : $this->db_pass;
    $this->db_name = ( $db_name != '' ) ? $db_name : $this->db_name;

    $dbi = @mysql_connect( $this->db_host, $this->db_user, $this->db_pass );

    if ( $dbi ) {
      $this->dbi = $dbi;

      if ( $this->select_db( $this->db_name )) {
        return true;
      }
    }
    else {
      print 'CDatabase Error: Connection to the database server failed.<br/>';
    }
    return false;
  }

 /*******************************************************************************************
  * Description
  *   Closes the connection to a database
  *
  * Output
  *   true/false = success/failure
  *******************************************************************************************/
  function close() {
    if ( $this->dbi ) {
      mysql_close( $this->dbi );

      $this->dbi    = false;
      return true;
    }
    return false;
  }

 /*******************************************************************************************
  * Description
  *   Selects a specific database on the server
  *
  * Input
  *   $db_name = database name
  *
  * Output
  *   true/false = success/failure
  *******************************************************************************************/
  function select_db( $db_name ) {
    $retval = @mysql_select_db( $db_name, $this->dbi );
    if ( $retval ) {
      return true;
    }
    else {
      echo 'CDatabase Error: Could not select database.<br/>';
      return false;
    }
  }

 /*******************************************************************************************
  * Description
  *   Queries a database
  *
  * Input
  *   $sql = SQL statement to use for query
  *
  * Output
  *   mixed: true/false or a valid MySQL result set (depending on type of query)
  *******************************************************************************************/
  function query( $sql = false ) {
    $retval = false;
    
    if ( !$this->dbi ) {
      if ( !$this->connect() ) {
        return false;
      }
    }

    if ( $sql ) {
      $result = mysql_query( $sql, $this->dbi );
      if( $result ) {
        $retval = $result;
      }
      else {
        echo 'CDatabase Error: ' . mysql_error() . " .\n<!--" . $sql . '--><br/>';
      }
    }

    return $retval;
  }

 /*******************************************************************************************
  * Description
  *   Queries a database - alias for query()
  *
  * Input
  *   $sql = SQL statement to use for query
  *
  * Output
  *   mixed: true/false or a valid MySQL result set (depending on type of query)
  *******************************************************************************************/
  function execute( $sql = false ) {
    return $this->query( $sql );
  }

 /*******************************************************************************************
  * Description
  *   Queries a database and returns an associative array of the first row()
  *
  * Input
  *   $sql = SQL statement to use for query
  *
  * Output
  *   mixed: array
  *******************************************************************************************/
  function query_first( $sql = false ) {
    if ( $result = $this->query( $sql ) ) {
      $row = $this->fetch_assoc( $result );
      $this->free_result( $result );
      return $row;
    }
    else {
      return false;
    }
  }
  
 /*******************************************************************************************
  * Description
  *   Fetches all rows into an associative array and frees the result
  *
  * Input
  *   $result = the result set to use
  *
  * Output
  *   mixed: row[]/false = row array/failure
  *******************************************************************************************/
  function query_all( $sql = false ) {
    $rows = array();
    
    if ( $result = $this->query( $sql )) {
      while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
        $rows[] = $row;
      }
      $this->free_result( $result );
    }
    
    return $rows;
  }

 /*******************************************************************************************
  * Description
  *   Frees the memory allocated to a result set
  *
  * Input
  *   $result = the result set to use
  *
  * Output
  *   true/false = success/failure
  *******************************************************************************************/
  function free_result( $result = false ) {
    if ( $result ) {
      mysql_free_result( $result );
      return true;
    }
    return false;
  }

 /*******************************************************************************************
  * Description
  *   Fetches the next row from a result set
  *
  * Input
  *   $result = the result set to use
  *
  * Output
  *   mixed: row[]/false = row array/failure
  *******************************************************************************************/
  function fetch_row( $result = false ) {
    if ( $result ) {
      $row = mysql_fetch_row( $result );
      return $row;
    }
    return false;    
  }

 /*******************************************************************************************
  * Description
  *   Fetches the next row as an array from a result set
  *
  * Input
  *   $result = the result set to use
  *
  * Output
  *   mixed: row[]/false = row array/failure
  *******************************************************************************************/
  function fetch_array( $result = false ) {
    if ( $result ) {
      $row = mysql_fetch_array( $result );
      return $row;
    }
    return false; 
  }

  /*******************************************************************************************
  * Description
  *   Fetches the next row as an associated array from a result set
  *
  * Input
  *   $result = the result set to use
  *
  * Output
  *   mixed: row[]/false = row array/failure
  *******************************************************************************************/
  function fetch_assoc( $result = false ) {
    if ( $result ) {
      $row = mysql_fetch_array( $result, MYSQL_ASSOC );
      return $row;
    }
    return false; 
  }
  function fetch( $result = false ) { return $this->fetch_assoc( $result ); }

 /*******************************************************************************************
  * Description
  *   Sets internal data pointer to the row specified
  *
  * Input
  *   $result     = the result set to use
  *   $row_number = the row number to seek to (default: 0, will reset the pointer)
  *
  * Output
  *   boolean     = returns true if operation was successful
  *******************************************************************************************/
  function seek( $result = false, $row_number = 0 ) {
    if ( $result ) {
      $success = mysql_data_seek( $result, $row_number );
      if ( $success ) {
        return true;
      }
      else {
        //raise error
      }
    }
    return false;
  }
  
 /*******************************************************************************************
  * Description
  *   Gets number of rows in result set
  *
  * Input
  *   $result = the result set to use
  *
  * Output
  *   mixed: int/false = number of rows in the result set/failure
  *******************************************************************************************/
  function num_rows( $result = false ) {
    if ( $result ) {
      $rows = mysql_num_rows( $result );
      if ( $rows > 0 ) {
        return $rows;
      }
    }
    return false;
  }

 /*******************************************************************************************
  * Description
  *   Gets number of rows affected by last query
  *
  * Input
  *   $result = the result set to use
  *
  * Output
  *    mixed: int/false = number of rows affected by last query/failure
  *******************************************************************************************/
  function affected_rows() {
    return mysql_affected_rows( $this->dbi );
  }
  
  function found_rows() {
    $rs = mysql_query('SELECT FOUND_ROWS() AS _FOUND_ROWS');
    $ar = mysql_fetch_assoc($rs);
    return $ar['_FOUND_ROWS'];
  }

 /*******************************************************************************************
  * Description
  *   Gets id of last INSERT operation
  *
  * Output
  *    mixed: int/false = id generated by last INSERT operation
  *******************************************************************************************/
  function insert_id() {
    $id = mysql_insert_id( $this->dbi );
    if ( $id > 0 ) {
      return $id;
    }
    return false;
  }

 /*******************************************************************************************
  * Description
  *   Readies a string for database insert
  *
  * Input
  *   $string = the string to ready
  *   $allow_nulls = whether to return the keyword NULL or and empty string ('')
  *
  * Output
  *    string: database safe string
  *******************************************************************************************/
  function asql_safe($var_array, $allow_nulls=true) {
    $temp_array = array();
    
    while(list($k, $v) = each($var_array)) {
      $temp_array[$k] = $this->sql_safe($v, $allow_nulls);
    }

    return $temp_array;
  }
  
  /*******************************************************************************************
  * Description
  *   Readies a string for database insert
  *
  * Input
  *   $string = the string to ready
  *   $allow_nulls = whether to return the keyword NULL or and empty string ('')
  *
  * Output
  *    string: database safe string
  *******************************************************************************************/
  function sql_safe($string, $allow_nulls=true, $html_safe=false) {
    if($html_safe) {
      $string = str_replace("<", "&gt;", $string);
      $string = str_replace(">", "&lt;", $string);
    }

    if(strlen($string) > 0) {
      return '\'' . addslashes($string) .  '\'';
    }
    elseif($allow_nulls) {
      return 'NULL';
    }
    else {
      return '';
    }
  }

  /*******************************************************************************************
  * Description
  *   Readies a string for database insert
  *
  * Input
  *   $string = the string to ready
  *   $allow_nulls = whether to return the keyword NULL or and empty string ('')
  *
  * Output
  *    string: database safe string
  *******************************************************************************************/
  function asql_desafe($var_array) {
    $temp_array = array();
    
    while(list($k, $v) = each($var_array)) {
      $temp_array[$k] = $this->sql_desafe($v);
    }

    return $temp_array;
  }

 /*******************************************************************************************
  * Description
  *   Strips any formating cause by readying the string for database insert (db_safe)
  *
  * Input
  *   $string = the string to strip ready formating from
  *
  * Output
  *    string: stripped string
  *******************************************************************************************/
  function sql_desafe($string) {
    return stripslashes($string);
  }
  function sql_unsafe($string) {
    return $this->sql_desafe($string);
  }
}
?>

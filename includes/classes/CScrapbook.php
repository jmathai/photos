<?php

/*******************************************************************************************
 * Name:        CScrapbook.php
 * Class Name:  CScrapbook
 *------------------------------------------------------------------------------------------
 * Mod History: Kevin Hornschemeier   07/02/2006
 *------------------------------------------------------------------------------------------
 * Class to handle the scrapbook
 * 
 *******************************************************************************************/

class CScrapbook
{
  
  /*******************************************************************************************
  * Name
  *   getInstance
  *
  * Description
  *   Static method to invoke this class
  *
  * Input
  *   
  * Output
  *   Class object
  *******************************************************************************************/
  static function & getInstance()
  {
    static $inst = null;
    $class = __CLASS__;
    
    // if this is the first time the class is instantiated
    // then create and return the class
    // otherwise, just return the current instance
    if($inst === null)
    {
      $inst       = new $class;
      $inst->dbh  =&$GLOBALS['dbh'];
    }
    
    return $inst;
  }
  
  /*******************************************************************************************
  * Name
  *   CScrapbook
  *
  * Description
  *   Class constructor (initializes variables)
  *
  * Input
  *   
  * Output
  *
  *******************************************************************************************/
  function CScrapbook()
  {
  }
  
  /*******************************************************************************************
  * Name
  *   scrapbooks
  *
  * Description
  *   Gets all scrapbooks for a user
  *
  * Input
  *   $u_id
  *
  * Output
  *   array
  *
  *******************************************************************************************/
  function scrapbooks($u_id)
  {
    $u_id = $this->dbh->sql_safe($u_id);
    
    $sql = 'SELECT sb.sb_id AS SB_ID, sb.sb_u_id AS SB_U_ID, sb.sb_title AS SB_TITLE, sb.sb_description AS SB_DESC, UNIX_TIMESTAMP(sb.sb_dateCreated) AS SB_CREATED '
         . 'FROM scrapbook_book AS sb '
         . 'WHERE sb.sb_u_id = ' . $u_id . ' ';
         
    return $this->dbh->query_all($sql);
  }
  
  /*******************************************************************************************
  * Name
  *   pages
  *
  * Description
  *   Gets all scrapbooks for a user
  *
  * Input
  *   $u_id
  *   $sb_id
  *
  * Output
  *   array
  *
  *******************************************************************************************/
  function pages($u_id, $sb_id)
  {
    $u_id = $this->dbh->sql_safe($u_id);
    $sb_id = $this->dbh->sql_safe($sb_id);
    
    $sql = 'SELECT sp.sp_id AS SP_ID, sp.sp_u_id AS SP_U_ID, sp.sp_title AS SP_TITLE, sp.sp_description AS SP_DESC, UNIX_TIMESTAMP(sp.sp_dateCreated) AS SP_CREATED '
         . 'FROM scrapbook_page AS sp JOIN scrapbook_book_page_map AS sbpm '
         . 'ON sp.sp_id = sbpm.sbpm_sp_id '
         . 'WHERE sbpm.sbpm_sb_id = ' . $sb_id . ' '
         . 'AND sp_u_id = ' . $u_id . ' ';
         
    return $this->dbh->query_all($sql);
  }
  
  /*******************************************************************************************
  * Name
  *   newBook
  *
  * Description
  *   Creates a new book
  *
  * Input
  *   $u_id
  *   $title
  *   $description
  *
  * Output
  *   insert id
  *
  *******************************************************************************************/
  function newBook($u_id, $title, $description)
  {
    $u_id = $this->dbh->sql_safe($u_id);
    $title = $this->dbh->sql_safe($title);
    $description = $this->dbh->sql_safe($description);
    
    $sql = 'INSERT INTO scrapbook_book (sb_u_id, sb_title, sb_description, sb_dateCreated) '
         . 'VALUES (' . $u_id . ', ' . $title . ', ' . $description . ', NOW())';
         
    $this->dbh->execute($sql);
    return $this->dbh->insert_id();
  }
  
  /*******************************************************************************************
  * Name
  *   newPage
  *
  * Description
  *   Creates a new page
  *
  * Input
  *   $u_id
  *   $title
  *   $description
  *
  * Output
  *
  *******************************************************************************************/
  function newPage($u_id, $parent, $title, $description)
  {
    $u_id = $this->dbh->sql_safe($u_id);
    $parent = $this->dbh->sql_safe($parent);
    $title = $this->dbh->sql_safe($title);
    $description = $this->dbh->sql_safe($description);
    
    $sql = 'INSERT INTO scrapbook_page (sp_u_id, sp_title, sp_description, sp_dateCreated) '
         . 'VALUES (' . $u_id . ', ' . $title . ', ' . $description . ', NOW())';
         
    $this->dbh->execute($sql);
    $sp_id = $this->dbh->insert_id();
    
    $sql = 'INSERT INTO scrapbook_book_page_map (sbpm_sb_id, sbpm_sp_id) '
         . 'VALUES (' . $parent . ', ' . $sp_id . ')';
    
    $this->dbh->execute($sql);
  }
  
  /*******************************************************************************************
  * Name
  *   insertItem
  *
  * Description
  *   Creates a new page
  *
  * Input
  *   $u_id
  *   $parent
  *   $type
  *   $size
  *   $x
  *   $y
  *   $extra
  *
  * Output
  *
  *******************************************************************************************/
  function insertItem($u_id, $parent, $type, $size, $x, $y, $extra)
  {
    $u_id = $this->dbh->sql_safe($u_id);
    $parent = $this->dbh->sql_safe($parent);
    $type = $this->dbh->sql_safe($type);
    $size = $this->dbh->sql_safe($size);
    $x = $this->dbh->sql_safe($x);
    $y = $this->dbh->sql_safe($y);
    $extra = $this->dbh->sql_safe($extra);
    
    $sql = 'INSERT INTO scrapbook_item (si_u_id, si_size, si_x, si_y, si_type, si_extra, si_transformations, si_dateCreated) '
         . "VALUES (" . $u_id . ", " . $size . ", " . $x . ", " . $y . ", " . $type . ", " . $extra . ", '', NOW())";
         
    $this->dbh->execute($sql);
    $si_id = $this->dbh->insert_id();
    
    $sql = 'INSERT INTO scrapbook_page_item_map (spim_sp_id, spim_si_id) '
         . 'VALUES (' . $parent . ', ' . $si_id . ')';
    
    $this->dbh->execute($sql);
    
    return $si_id;
  }
}
<?php
/*
 *******************************************************************************************
 * Class Name:  CApi
 *------------------------------------------------------------------------------------------
 * Mod History: Jaisen Mathai (March 9, 2005)
 *------------------------------------------------------------------------------------------
 * Api class which performs various interaction with remote (or local) systems using web services
 *
 * Usage:
 * 
 ******************************************************************************************
 */
class CToolbox
{
  function get($identifier = false, $type = 'foto')
  {
    $retval = false;
    if($identifier !== false)
    {
      $identifierSafe = $this->dbh->sql_safe($identifier);
      $typeSafe = $this->dbh->sql_safe($type);
      if(strlen($identifier) < 13)
      {
        $sql  = 'SELECT t.t_id AS T_ID, uf.up_id AS P_ID, uf.up_key AS P_KEY, uf.up_thumb_path AS P_THUMB_PATH, uf.up_original_path AS P_ORIG_PATH, uf.up_name AS P_NAME, uf.up_description AS P_DESC, uf.up_tags AS P_TAGS, uf.up_width AS P_WIDTH, uf.up_height AS P_HEIGHT, uf.up_rotation AS P_ROTATION '
              . 'FROM user_toolbox AS t INNER JOIN user_fotos AS uf ON t.t_itemId = uf.up_id '
              . 'WHERE t.t_u_id = ' . $identifierSafe . ' AND t.t_itemType = ' . $typeSafe . ' '
              . 'ORDER BY t.t_id DESC';
        
        $retval = $this->dbh->query_all($sql);
      }
      else
      {
        $sql  = 'SELECT et.et_id AS T_ID, uf.up_id AS P_ID, uf.up_key AS P_KEY, uf.up_thumb_path AS P_THUMB_PATH, uf.up_original_path AS P_ORIG_PATH, uf.up_name AS P_NAME, uf.up_description AS P_DESC, uf.up_tags AS P_TAGS, uf.up_width AS P_WIDTH, uf.up_height AS P_HEIGHT, uf.up_rotation AS P_ROTATION '
              . 'FROM ecom_toolbox AS et INNER JOIN user_fotos AS uf ON et.et_itemId = uf.up_id '
              . 'WHERE et.et_sess_hash = ' . $identifierSafe . ' AND et.et_itemType = ' . $typeSafe . ' '
              . 'ORDER BY et.et_id DESC';
        
        $retval = $this->dbh->query_all($sql);
      }
    }

    return $retval;
  }
  
  function getItem($identifier = false, $toolboxId = false, $type = 'foto')
  {
    $retval = false;
    
    if($identifier !== false)
    {
      $identifierSafe = $this->dbh->sql_safe($identifier);
      $toolboxIdSafe = intval($toolboxId);
      $typeSafe = $this->dbh->sql_safe($type);
      
      if(strlen($identifier) < 13)
      {
        $sql  = 'SELECT t.t_id AS T_ID, uf.up_id AS P_ID, uf.up_thumb_path AS P_THUMB_PATH, uf.up_flix_path AS P_FLIX_PATH, uf.up_web_path AS P_WEB_PATH '
              . 'FROM user_toolbox AS t INNER JOIN user_fotos AS uf ON t.t_itemId = uf.up_id '
              . 'WHERE t.t_id = ' . $toolboxIdSafe . ' AND t.t_u_id = ' . $identifierSafe . ' AND t.t_itemType = ' . $typeSafe . ' '
              . 'ORDER BY t.t_id DESC';
        $retval = $this->dbh->query_first($sql);
      }
      else
      {
        $sql  = 'SELECT et.et_id AS T_ID, uf.up_id AS P_ID, uf.up_thumb_path AS P_THUMB_PATH, uf.up_flix_path AS P_FLIX_PATH, uf.up_web_path AS P_WEB_PATH '
              . 'FROM ecom_toolbox AS et INNER JOIN user_fotos AS uf ON et.et_itemId = uf.up_id '
              . 'WHERE et.et_id = ' . $toolboxIdSafe . ' AND et.et_sess_hash = ' . $identifierSafe . ' AND et.et_itemType = ' . $typeSafe . ' '
              . 'ORDER BY et.et_id DESC';
        $retval = $this->dbh->query_first($sql);
      }
    }
    
    return $retval;
  }
  
  function add($identifier = false, $item = false, $type = 'foto', $order = 0)
  {
    $retval = false;
    
    if($identifier !== false)
    {
      $identifierSafe = $this->dbh->sql_safe($identifier);
      $itemSafe       = intval($item);
      $typeSafe       = $this->dbh->sql_safe($type);
      $orderSafe      = intval($order);
      
      if(strlen($identifier) < 13)
      {
        $sql  = 'INSERT INTO user_toolbox(t_u_id, t_itemId, t_itemType, t_itemOrder) '
              . 'VALUES(' . $identifierSafe . ',' . $itemSafe . ',' . $typeSafe . ',' . $orderSafe . ')';
      }
      else
      {
        $sql  = 'REPLACE INTO ecom_toolbox(et_sess_hash, et_itemId, et_itemType, et_itemOrder) '
              . 'VALUES(' . $identifierSafe . ',' . $itemSafe . ',' . $typeSafe . ',' . $orderSafe . ')';
      }
      
      $this->dbh->execute($sql);
      $retval = $this->dbh->insert_id();
    }
    
    return $retval;
  }
  
  function remove($identifier = false, $toolboxId = false, $type = 'foto')
  {
    $retval = false;
    
    if($identifier !== false)
    {
      $identifierSafe = $this->dbh->sql_safe($identifier);
      $toolboxIdSafe  = $this->dbh->sql_safe($toolboxId);
      $typeSafe       = $this->dbh->sql_safe($type);
      
      if(strlen($identifier) < 13)
      {
        $sql  = 'DELETE FROM user_toolbox WHERE t_id = ' . $toolboxId . ' AND t_u_id = ' . $identifierSafe . ' AND t_itemType = ' . $typeSafe;
      }
      else
      {
        $sql  = 'DELETE FROM ecom_toolbox WHERE et_id = ' . $toolboxId . ' AND et_sess_hash = ' . $identifierSafe . ' AND et_itemType = ' . $typeSafe;
      }
      
      $this->dbh->execute($sql);
      
      $retval = $toolboxId;
    }
    
    return $retval;
  }
  
  function clear($identifier = false, $type = false)
  {
    if($identifier !== false)
    {
      $identifierSafe = $this->dbh->sql_safe($identifier);
      
      if(strlen($identifier) < 13)
      {
        $sql = 'DELETE FROM user_toolbox WHERE t_u_id = ' . $identifierSafe;
        if($type !== false)
        {
          $typeSafe       = $this->dbh->sql_safe($type);
          $sql .= ' AND t_itemType = ' . $typeSafe;
        }
      }
      else
      {
        $sql = 'DELETE FROM ecom_toolbox WHERE et_sess_hash = ' . $identifierSafe;
        if($type !== false)
        {
          $typeSafe       = $this->dbh->sql_safe($type);
          $sql .= ' AND et_itemType = ' . $typeSafe;
        }
      }
      
      $this->dbh->execute($sql);
    }
    
    return true;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   getInstance
  * Description
  *   Static method to invoke this class
  * Output
  *   Class object
  ******************************************************************************************
  */
  static function & getInstance()
  {
    static $inst = null;
    $class = __CLASS__;
    
    if($inst === null)
    {
      $inst      = new $class;
      $inst->dbh =& $GLOBALS['dbh'];
    }
    
    return $inst;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   CUser
  * Description
  *   Constructor
  *
  * Input
  *   None
  * Output
  *   Boolean
  ******************************************************************************************
  */
  function CToolbox()
  {
  }
}
?>
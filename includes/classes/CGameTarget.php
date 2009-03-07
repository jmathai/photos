<?php
 /*******************************************************************************************
  * Name:  CGameTarget.php
  *
  * Class to handle target game
  *
  * Usage:
  * 
  *******************************************************************************************/
class CGameTarget{
 /*******************************************************************************************
  * Description
  *   Method to retrieve games
  *
  * Input (one of the following combinations)
  *   $premium            bool
  * Output
  *   $return             array
  *******************************************************************************************/
  function versions($premium = false)
  {
    $sql  = 'SELECT gto.gto_id AS O_ID, gto.gto_gm_id AS O_G_ID, gto.gto_name AS O_NAME, gto.gto_description AS O_DESCRIPTION, gto.gto_template AS O_TEMPLATE, gto.gto_premium AS O_PREMIUM '
          . 'FROM game_target AS gto ';
    if($premium === false)
    {
      $sql .= "WHERE gm_premium <> 'Y' ";
    }
    
    $return = $this->dbh->query_all($sql);
    
    return $return;
  }
  
 /*******************************************************************************************
  * Description
  *   Constructor
  *******************************************************************************************/
  function CGameTarget()
  {
    include_once PATH_CLASS . '/CGame.php';
    $this->dbh  =& $GLOBALS['dbh'];
    $this->game =& CGame::getInstance();
  }
}
?>
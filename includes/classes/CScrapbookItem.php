<?php

/*******************************************************************************************
 * Name:        CScrapbookItem.php
 * Class Name:  CScrapbookItem
 *------------------------------------------------------------------------------------------
 * Mod History: Kevin Hornschemeier   07/02/2006
 *------------------------------------------------------------------------------------------
 * Class to handle scrapbook items
 * 
 *******************************************************************************************/

class CScrapbookItem
{
  // MEMBER VARIABLES
  var $m_size;
  var $m_x;
  var $m_y;
  var $m_type;
  var $m_extra;
  var $m_transformations;
  
  /*******************************************************************************************
  * Name
  *   CScrapbookItem
  *
  * Description
  *   Class constructor (initializes variables)
  *
  * Input
  *   $size
  *   $x
  *   $y
  *   $type
  *   $extra
  *
  * Output
  *
  *******************************************************************************************/
  function CScrapbookItem($size = 'small', $x = 0, $y = 0, $type = 'photo', $extra = false)
  {
    $m_size = $size;
    $m_x = $x;
    $m_y = $y;
    $m_type = $type;
    $m_extra = $extra;
    $m_transformations = array();
  }
}

?>
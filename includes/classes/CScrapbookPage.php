<?php

/*******************************************************************************************
 * Name:        CScrapbookPage.php
 * Class Name:  CScrapbookPage
 *------------------------------------------------------------------------------------------
 * Mod History: Kevin Hornschemeier   07/02/2006
 *------------------------------------------------------------------------------------------
 * Class to handle scrapbook pages
 * 
 *******************************************************************************************/

include_once PATH_CLASS . '/CScrapbook.php';
include_once PATH_CLASS . '/CScrapbookItem.php';

class CScrapbookPage
{
  // MEMBER VARIABLES
  var $m_parent;
  var $m_title;
  var $m_description;
  var $m_dateCreated;
  var $m_items;
  
  /*******************************************************************************************
  * Name
  *   CScrapbookPage
  *
  * Description
  *   Class constructor (initializes variables)
  *
  * Input
  *   $title
  *   $description
  *
  * Output
  *
  *******************************************************************************************/
  function CScrapbookPage($u_id, $parent, $title = '', $description = '')
  {
    $m_parent = $parent;
    $m_title = $title;
    $m_description = $description;
    $m_dateCreated = time();
    $m_items = array();
    
    $sb = &CScrapbook::getInstance();
    $sb->newPage($u_id, $m_parent, $m_title, $m_description, $m_dateCreated);
  }
  
  /*******************************************************************************************
  * Name
  *   getTitle
  *
  * Description
  *   Title accessor
  *
  * Input
  *
  * Output
  *   string
  *
  *******************************************************************************************/
  function getTitle()
  {
    return $m_title;
  }
  
  /*******************************************************************************************
  * Name
  *   getDescription
  *
  * Description
  *   Description accessor
  *
  * Input
  *
  * Output
  *   string
  *
  *******************************************************************************************/
  function getDescription()
  {
    return $m_description;
  }
  
  /*******************************************************************************************
  * Name
  *   setTitle
  *
  * Description
  *   Title mutator
  *
  * Input
  *   $title
  *
  * Output
  *
  *******************************************************************************************/
  function setTitle($title)
  {
    $m_title = $title;
  }
  
  /*******************************************************************************************
  * Name
  *   setDescription
  *
  * Description
  *   Description mutator
  *
  * Input
  *   $description
  *
  * Output
  *
  *******************************************************************************************/
  function setDescription($description)
  {
    $m_description = $description;
  }
  
  /*******************************************************************************************
  * Name
  *   newItem
  *
  * Description
  *   Creates a new item on this page
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
  function newItem($size, $x, $y, $type, $extra)
  {
    $m_items[] = new CScrapbookItem($size, $x, $y, $type, $extra);
  }
}

?>
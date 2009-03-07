<?php
 /*******************************************************************************************
  * Name:  CSlideshow.php
  *
  * Class to handle Slideshow read stuff
  *
  * Usage:
  *   $sld  =& CSlideshow::getInstance();<br>
  *   $data =  $sld->slideshow($id);
  *******************************************************************************************/
class CSlideshow {
 /*******************************************************************************************
  * Description
  *   Method to retrieve slideshow data
  *
  * Input (one of the following combinations)
  *   $id / $key          int / string(32)
  * Output
  *   $return             array
  *******************************************************************************************/
  function slideshow($identifier = false, $userId = false)
  {
    $retval = false;
    if($identifier !== false)
    {
      $retval = array(
                  'PROPERTIES'=> $this->slideshowProperties($sql),
                  'ELEMENTS'  => $this->slideshowElements($identifier)
                );
    }
    
    return $retval;
  }
  
 /*******************************************************************************************
  * Description
  *   Method to retrieve slideshow properties
  *
  * Input (one of the following combinations)
  *   $id                 int
  * Output
  *   $return             array
  *******************************************************************************************/
  function slideshowProperties($identifier = false)
  {
    $retval = false;
    
    $sql  = 'SELECT us.us_id AS S_ID, us.us_key AS S_KEY, us.us_tags AS S_TAGS, us.us_order AS S_ORDER, us.us_fotoCount AS S_FOTOCOUNT, '
          . 'us.us_length AS S_LENGTH, us.us_views AS S_VIEWS, us.us_viewsComplete AS S_VIEWSCOMPLETE, us.us_privacy AS S_PRIVACY '
          . 'FROM user_slideshows AS us ';
    
    if($identifier !== false)
    {
      $continue = false;
      if(is_numeric($identifier))
      {
        $sql .= 'WHERE us.us_id = ' . intval($identifier) . ' '; // can be intval'ed since it passes is_numeric
        $continue = true;
      }
      else
      if(strlen($identifier) == 32)
      {
        $sql .= 'WHERE us.us_key = ' . $this->dbh->sql_safe($identifier) . ' ';
        $continue = true;
      }
      
      if($continue === true)
      {
        if(is_numeric($userId))
        {
          $sql .= ' AND us.us_u_id = ' . intval($userId);
        }
        
        $retval = $this->dbh->query_first($sql);
      }
    }
    
    return $retval;
  }
  
 /*******************************************************************************************
  * Description
  *   Method to retrieve slideshow elements and create heirarchy
  *
  * Input (one of the following combinations)
  *   $id                 int
  * Output
  *   $return             array
  *******************************************************************************************/
  function slideshowElements($identifier = false)
  {
    $retval = false;
    
    if(is_numeric($identifier))
    {
      // pull all elements and order by parent id and then by order
      $sql  = 'SELECT use_id AS E_ID, use_group_id AS GROUP_ID, use_name AS E_NAME, use_value AS E_VALUE, use_order AS E_ORDER '
            . 'FROM user_slideshow_elements '
            . 'WHERE use_us_id = ' . intval($identifier) . ' '
            . 'ORDER BY use_group_id ASC, use_order ASC ';
      
      $data = $this->dbh->query_all($sql);
      
      $retval = array();
      $this->recursionCounter = 0; // this keeps enless loops from happening
      foreach($data as $v)
      {
        if($v['E_P_ID'] == 0) // check if this element is a top level element
        {
          //$cKey = array_push($retval, array($v['E_NAME'] => $v['E_VALUE'])) - 1; // set the name to the value
          $retval[$v['GROUP_ID']][$v['E_NAME']] = $v['E_VALUE']; // set the name to the value
          /*$hasChildren = $this->_childElements($v['E_ID'], $data); // check to see if this element has any children
          if($hasChildren !== false)
          {
            foreach($hasChildren as $v)
            {
              //list($name, $value) = each($v);
              $retval[$cKey][] = $v;
            }
            //$retval[$cKey][] = $hasChildren; // if there are children then set them in the CHILDREN index
            //$tmpResult['CHILDREN'] = $children; // if there are children then set them in the CHILDREN index
          }*/
        }
        else
        {
          break; // break from the loop if we're past the top level elemenets
        }
      }
    }
    
    return $retval;
  }
  
 /*******************************************************************************************
  * Description
  *   Private method to retrieve slideshow child elements (called recursively)
  *
  * Input (one of the following combinations)
  *   $id                 int
  * Output
  *   $return             array
  *******************************************************************************************/
  function _childElements($id = false, $arr = false)
  {
    $this->recursionCounter++; // increment recursionCounter
    if($this->recursionCounter > 300){ trap('recursionCounter'); die(); } // kill process if recursionCounter exceeds threshold
    
    $retval = false;
    if($id !== false && is_array($arr))
    {
      foreach($arr as $v) // loop over each array element
      {
        if($v['E_P_ID'] == $id) // this is a child element
        {
          if(!is_array($retval)) 
          { 
            $retval = array(); // if retval isn't set to an array then set it here
          }
          
          //$cKey = array_push($retval, array($v['E_NAME'] => $v['E_VALUE'])) - 1; // set the name and value of this child
          $retval[$v['E_NAME']] = $v['E_VALUE']; // set the name and value of this child
          
          /*$hasChildren = $this->_childElements($v['E_ID'], $arr); // check and see if this child has any children
          if($hasChildren !== false)
          {
            foreach($hasChildren as $v)
            {
              list($name, $value) = each($v);
              $retval[$cKey][][$name] = $value;
            }
            $retval[$cKey][] = $hasChildren[0]; // if it has children then set it to the CHILDREN index
            //$retval['CHILDREN'] = $hasChildren; // if it has children then set it to the CHILDREN index
          }*/
        }
      }
    }
    
    return $retval;
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
      $inst       = new $class;
      $inst->dbh  =&$GLOBALS['dbh'];
    }
    
    return $inst;
  }
}
?>
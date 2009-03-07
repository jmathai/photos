<?php
 /*******************************************************************************************
  * Name:  CFlix.php
  *
  * Class to handle Flix read stuff
  *
  * Usage:
  * 
  *******************************************************************************************/
 
include_once PATH_INCLUDE . '/functions.php';
 
class CFlix {
  /*******************************************************************************************
  * Description
  *   Method to search for flix
  *
  * Input
  *   $params
  *       []'s mean the parameter is optional, {}'s mean it's required
  *     ['MODE' - 'GROUP', 'GROUP_SHARE', 'USER']
  *     
  *     1 of the following is required
  *     {
  *     'USER_ID' - used in user mode and group_share mode
  *     'GROUP_ID' - used in group and group_share mode
  *     'KEY' - key of the flix
  *     'FLIX_ID' - flix id of the flix
  *     'FLIX_IDS' - array of flix ids
  *     }
  *
  *     ['TAGS' - array of tags or a comma separated string of tags]
  *     ['PERMISSION' - permission mask (predefined constant)]
  *     ['ORDER_BY' - 'order', 'length', 'title', 'views', 'modified']
  *     ['LIMIT' - integer]
  *     ['OFFSET' - integer]
  *
  * Output
  *   array
  *******************************************************************************************/
  function search($params)
  {
    $return = array(); 
    
    $select = 'SELECT ';
    $from = 'FROM ';
    $where = 'WHERE 1 ';
    $order_by = 'ORDER BY ';
    $limit = '';
    $offset = '';
    $defaultOrder = '';
    
    $select .= 'SQL_CALC_FOUND_ROWS us.us_id AS US_ID, us.us_u_id AS US_U_ID, us.us_key AS US_KEY, us.us_name AS US_NAME, us.us_tags AS US_TAGS, us.us_elements AS US_ELEMENTS, us.us_settings AS US_SETTINGS, us.us_type AS US_TYPE, us.us_order AS US_ORDER, us.us_fotoCount AS US_FOTO_COUNT, '
             . 'us.us_length AS US_LENGTH, us.us_views AS US_VIEWS, us.us_viewsComplete AS US_VIEWS_COMPLETE, us.us_privacy AS US_PRIVACY, UNIX_TIMESTAMP(us.us_dateModified) AS US_DATE_MODIFIED, '
             . 'UNIX_TIMESTAMP(us.us_dateCreated) AS US_DATE_CREATED, us.us_status AS US_STATUS ';
    
    switch($params['MODE'])
    {
      case 'GROUP':
        $from .= 'user_slideshows AS us INNER JOIN group_fotoflix_map AS gfm ON us.us_id = gfm.uf_id ';
        $defaultOrder = 'group';
        $where = "AND gfm.gfm_status = 'Active' ";
        break;
      case 'GROUP_SHARE':
        $select .= ', gfm.uf_id AS US_UF_ORIG_ID ';
        
        if($params['MODERATOR'] == true)
        {
          $from .= 'user_slideshows AS us LEFT JOIN group_fotoflix_map AS gfm ON us.us_id = gfm.uf_id ';
        }
        else 
        {
          $from .= 'user_slideshows AS us LEFT JOIN group_fotoflix_map AS gfm ON us.us_id = gfm.uf_orig_id ';
        }
        
        $from .= 'AND gfm.g_id = ' . intval($params['GROUP_ID']) . ' ';
        $defaultOrder = 'group';
        break;
      case 'USER':
      default:
        $from .= 'user_slideshows AS us ';
        $defaultOrder = 'user';
        break;
    }
    
    if(array_key_exists('USER_ID', $params))
    {
      $where .= "AND us.us_u_id = " . intval($params['USER_ID']) . ' ';
    }
    
    if(array_key_exists('GROUP_ID', $params) && $params['MODE'] == 'GROUP')
    {
      $where .= "AND gfm.g_id = " . intval($params['GROUP_ID']) . ' ';
    }
    
    if(array_key_exists('KEY', $params))
    {
      $params['KEY_SAFE'] = $this->dbh->sql_safe($params['KEY']);
      $where .= "AND us.us_key = " . $params['KEY_SAFE'] . ' ';
    }
    
    if(array_key_exists('FLIX_ID', $params) || array_key_exists('SLIDESHOW_ID', $params)) // backwards compatibility
    {
      $id = array_key_exists('FLIX_ID', $params) ? intval($params['FLIX_ID']) : intval($params['SLIDESHOW_ID']);
      $where .= "AND us.us_id = " . $id . ' ';
    }
    
    if(array_key_exists('FLIX_IDS', $params))
    {
      if(count($params['FLIX_IDS']) == 0)
      {
        $params['FLIX_IDS'] = array(0);
      }
      $params['FLIX_IDS_SAFE'] = $this->dbh->asql_safe($params['FLIX_IDS']);
      $flix_ids = implode(',', $params['FLIX_IDS_SAFE']);
      $where .= 'AND us.us_id IN (' . $flix_ids . ') ';
    }
    
    $where .= "AND us.us_status = 'Active' ";
    
    if(array_key_exists('TAGS', $params) && $params['TAGS'] != '')
    {
      if(!is_array($params['TAGS']))
      {
        $params['TAGS'] = (array)explode(',', $params['TAGS']);
      }
      
      if(count($params['TAGS']) > 0)
      {
        array_walk($params['TAGS'], 'tagwalk');
        $where .= 'AND MATCH(us.us_tags) AGAINST(\'+"' . implode('," +"', $params['TAGS']) . ',"\' IN BOOLEAN MODE) ';
      }
    }
    
    if(array_key_exists('TYPE', $params))
    {
      $where .= 'AND us.us_type = ' . $this->dbh->sql_safe($params['TYPE']) . ' ';
    }
    
    if(array_key_exists('PERMISSION', $params))
    {
      $mask = intval($params['PERMISSION']);
      
      if($mask == PERM_SLIDESHOW_PRIVATE)
      {
        $where .= 'AND us.us_privacy = ' . $mask . ' ';
      }
      else 
      {
        $where .= 'AND us.us_privacy & ' . $mask . ' = ' . $mask . ' ';
      }
    }
    
    switch($params['ORDER_BY'])
    {
      case 'order':
        $order_by .= 'us.us_order ASC ';
        break;
      case 'length':
        $order_by .= 'us.us_length DESC ';
        break;
      case 'title':
        $order_by .= 'us.us_name ASC ';
        break;
      case 'views':
        $order_by .= 'us.us_views DESC ';
        break;
      case 'modified':
      default:
        $order_by .= ($defaultOrder == 'user') ? 'us.us_dateModified DESC ' : 'gfm.dateModified DESC ';
        break;
    }
    
    if(array_key_exists('LIMIT', $params))
    {
      $limit .= 'LIMIT ' . intval($params['LIMIT']) . ' ';
    }
    
    if(array_key_exists('OFFSET', $params))
    {
      $offset .= 'OFFSET ' . intval($params['OFFSET']) . ' ';
    }
    
    $sql = $select . $from . $where . $order_by . $limit . $offset;
    
    if(array_key_exists('FLIX_ID', $params) || array_key_exists('KEY', $params))
    {
      $return = $this->dbh->query_first($sql);
      $return['US_PHOTO'] = $this->firstPhoto($return['US_KEY']);
      
      $settings = jsonDecode($return['US_SETTINGS']);
      $return['US_WIDTH'] = $settings[0]['width_int'];
      $return['US_HEIGHT'] = $settings[0]['height_int'];
    }
    else
    {
      $data = $this->dbh->query_all($sql);
      $rows = intval($this->dbh->found_rows());
      
      foreach($data as $i => $row)
      {
        $return[$i] = $row;
        $return[$i]['US_PHOTO'] = $this->firstPhoto($return[$i]['US_KEY']);
        $settings = jsonDecode($row['US_SETTINGS']);
        $return[$i]['US_WIDTH'] = $settings[0]['width_int'];
        $return[$i]['US_HEIGHT'] = $settings[0]['height_int'];
        $i++;
      }
      
      if($rows > 0) // Only add [0]['ROWS'] if rows exist.  Otherwise foreach() over this array results in a single non existant flix
      {
        $return[0]['ROWS'] = intval($rows);
      }
    }
    
    return $return;
  }
  
 /*******************************************************************************************
  * Description
  *   Method get photos for a slideshow
  *
  * Output
  *   array
  *******************************************************************************************/
  function getFotos($slideshow_id = false, $user_id = false)
  {
    $retval = false;
    if($slideshow_id !== false)
    {
      $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_rotation AS P_ROTATION, up.up_privacy AS P_PRIVACY, up.up_views AS P_VIEWS, '
            . 'up.up_camera_make AS P_CAMERA_MAKE, up.up_camera_model AS P_CAMERA_MODEL, up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
            . "up.up_modified_at AS P_MODIFIED_AT, up.up_taken_at AS P_TAKEN, DATE_FORMAT(FROM_UNIXTIME(up.up_taken_at), '%y%m%d') AS P_TAKEN_KEY, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d') AS P_YMD "
            . 'FROM foto_slideshow_map AS fsm INNER JOIN user_fotos AS up ON fsm.up_id = up.up_id '
            . 'WHERE fsm.us_id = ' . intval($slideshow_id) . ' ';
      if($user_id !== false)
      {
        $sql .= 'AND up.up_u_id = ' . intval($user_id);
      }
      
      $retval = $this->dbh->query_all($sql);
    }
    
    return $retval;
  }
  
 /*******************************************************************************************
  * Description
  *   Method get slideshow data for player/editor
  *
  * Output
  *   array
  *******************************************************************************************/
  function flashOutput($identifier = false, $userId = false)
  {
    $sql  = 'SELECT us_id AS US_ID, us_u_id AS US_U_ID, us_key AS US_KEY, us_name AS US_NAME, us_tags AS US_TAGS, us_privacy AS US_PRIVACY, us_elements AS US_ELEMENTS, us_settings AS US_SETTINGS '
          . 'FROM user_slideshows ';
    if(is_numeric($identifier))
    {
      $sql .= 'WHERE us_id = ' . $this->dbh->sql_safe($identifier) . ' ';
    }
    else
    if(strlen($identifier) == 32)
    {
      $sql .= 'WHERE us_key = ' . $this->dbh->sql_safe($identifier) . ' ';
    }
    
    if($userId !== false)
    {
      $sql .= 'AND us_u_id = ' . $this->dbh->sql_safe($userId) . ' ';
    }

    $sql .= "AND us_status = 'Active' LIMIT 1";
    
    return $this->dbh->query_first($sql);
  }
  
  /*******************************************************************************************
  * Description
  *   Method get the first photo in a slideshow
  *
  * Input
  *   $identifier - 32 character us_key or ELEMENTS array
  *
  * Output
  *   array - array of first photo data or false
  *
  *******************************************************************************************/
  function firstPhoto($identifier = false)
  {
      if(strlen($identifier) == 32)
      {
        $sql = 'SELECT us_elements AS US_ELEMENTS '
             . 'FROM user_slideshows '
             . 'WHERE us_key = ' . $this->dbh->sql_safe($identifier) . ' ';
        
        $data = $this->dbh->query_first($sql);
      }
      else
      {
        $data['US_ELEMENTS'] = $identifier;
      }
      
      $elements = jsonDecode($data['US_ELEMENTS']);
      
      // loop through the elements array
      // if $v['thumbnailPath_str'] is not blank
      // then return that element
      if(is_array($elements))
      {
        foreach($elements as $k => $v)
        {
          if(isset($v['photoId_int']))
          {
            return $v;
          }
        }
      }
      
      return false;
  }
  
 /*******************************************************************************************
  * Description
  *   Count public Flix
  *
  * Output
  *   int
  *******************************************************************************************/
  function countPublic($user_id)
  {
    $user_id = $this->dbh->sql_safe($user_id);
    $sql = 'SELECT COUNT(uf_id) AS _COUNT FROM user_fotoflix WHERE uf_u_id = ' . $user_id . " AND uf_status = 'Active' AND uf_public = 'Y'";
    $res = $this->dbh->query_first($sql);
    
    return $res['_COUNT'];
  }
  
 /*******************************************************************************************
  * Description
  *   Method to retrieve mp3 dependencies
  *
  * Output
  *   array
  *******************************************************************************************/
  function mp3Dependencies($mp3_path = false)
  {
    $return = array();
    
    if($mp3_path !== false)
    {
      $mp3_path = $this->dbh->sql_safe($mp3_path);
      $sql  = 'SELECT uf.uf_id AS A_ID, uf.uf_u_id AS A_U_ID '
            . 'FROM user_fotoflix AS uf '
            . 'WHERE uf.uf_music = ' . $mp3_path;
      
      $rs   = $this->dbh->query($sql);
      
      while($data = $this->dbh->fetch_assoc($rs))
      {
        $return[] = $this->flixData($data['A_ID'], $data['A_U_ID']);
      }
    }
    
    return $return;
  }
  
 /*******************************************************************************************
  * Description
  *   Method to retrieve template by swf or id
  *
  * Output
  *   array
  *******************************************************************************************/
  function template($value = false, $user_id = false)
  {
  }
  
 /*******************************************************************************************
  * Description
  *   Method to retrieve templates
  *
  * Output
  *   array
  *******************************************************************************************/
  function themes($user_id = false)
  {
    $retval = array();
    if($user_id !== false)
    {
      $sql  = 'SELECT ust_id AS T_ID, ust_name AS T_NAME, ust_settings AS T_SETTINGS '
            . 'FROM user_slideshow_themes '
            . 'WHERE ust_u_id = ' . $this->dbh->sql_safe($user_id) . ' AND ust_active = 1';
            
      $retval = $this->dbh->query_all($sql);
    }
    
    return $retval;
  }

 /*******************************************************************************************
  * Description
  *   Method to retrieve newest templates
  *
  * Output
  *   array
  ******************************************************************************************/
  function recentTemplates($max = 3)
  {
    $return = array();
    
    $sql  = 'SELECT ft.ft_id AS T_ID, ft.ft_name AS T_NAME, ft.ft_screenshot AS T_SCREENSHOT '
          . 'FROM flix_templates AS ft '
          . "WHERE ft.ft_type = 'Free' "
          . 'ORDER BY ft.ft_id DESC '
          . 'LIMIT ' . intval($max);
    
    $return = $this->dbh->query_all($sql);
    
    return $return;
  }
  
 /*******************************************************************************************
  * Description
  *   Method to retrieve template categories
  *
  * Output
  *   array
  *******************************************************************************************/
  function categories($user_id = false)
  {
    $return = array();
    
    $sql1 = "SHOW COLUMNS FROM flix_templates LIKE 'ft_categories'";
    $rs1  = $this->dbh->query($sql1);
    $data = $this->dbh->fetch_assoc($rs1);
    
    $list = str_replace(array('set(', ')', "'"), '', $data['Type']);    
    $return = (array)explode(',', $list);

    if($user_id !== false)
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $sql2 = 'SELECT COUNT(u_id) AS _CNT FROM user_template_map WHERE u_id = ' . $user_id;
      
      $user_check = $this->dbh->query_first($sql2);
      
      if($user_check['_CNT'] == 0)
      {
        array_splice($return, 1, 1); // REMOVE Custom
      }
    }
    
    return $return;
  }
  
  /*******************************************************************************************
  * Name
  *   scheduleData
  *
  * Description
  *   gets a flix schedule
  *
  * Input
  *   us_id - flix id
  *
  * Output
  *   array - schedules
  *******************************************************************************************/
  function scheduleData($us_id)
  {
    $sql = 'SELECT uss.uss_id AS USS_ID, uss.uss_u_id AS USS_U_ID, uss.uss_us_id AS USS_US_ID, UNIX_TIMESTAMP(uss.uss_startDate) AS USS_START_DATE, UNIX_TIMESTAMP(uss.uss_endDate) AS USS_END_DATE, uss.uss_dateCreated AS USS_DATE_CREATED '
         . 'FROM user_slideshow_schedule AS uss '
         . 'WHERE uss.uss_us_id = ' . intval($us_id) . ' ';
         
    return $this->dbh->query_all($sql);
  }
  
  /*******************************************************************************************
  * Name
  *   getSchedules
  *
  * Description
  *   gets a list of flix schedules for a date
  *
  * Input
  *   date - day on which to retrieve schedules
  *
  * Output
  *   array - schedules
  *******************************************************************************************/
  function getSchedules($date = null, $field = 'start')
  {
    $date     = $this->dbh->sql_safe($date);
    $dbCol    = $field == 'start' ? 'uss.uss_startDate' : 'uss.uss_endDate';
    $sql      = 'SELECT uss.uss_id AS USS_ID, uss.uss_u_id AS USS_U_ID, uss.uss_us_id AS USS_US_ID, UNIX_TIMESTAMP(uss.uss_startDate) AS USS_START_DATE, UNIX_TIMESTAMP(uss.uss_endDate) AS USS_END_DATE, uss.uss_dateCreated AS USS_DATE_CREATED '
              . 'FROM user_slideshow_schedule AS uss '
              . 'WHERE ' . $dbCol . ' = ' . $date;
    
    $retval = $this->dbh->query_all($sql);
    
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
      $inst      = new $class;
      $inst->dbh =&$GLOBALS['dbh'];
    }
    
    return $inst;
  }
  
 /*******************************************************************************************
  * Description
  *   Constructor
  *******************************************************************************************/
  function CFlix()
  {
  }
}
?>

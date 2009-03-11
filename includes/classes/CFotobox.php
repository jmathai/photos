<?php

// needed for the tagwalk function used by fotosByTags and nextPrev methods
include_once PATH_INCLUDE . '/functions.php';

 /*
  *******************************************************************************************
  * Name:  CFotobox.php
  *
  * Class to handle fotobox functions
  *
  * Usage:
  *
  ******************************************************************************************
  */
class CFotobox
{
 /*
  *******************************************************************************************
  * Name
  *   beforeAfter
  * Description
  *   Method to retrieve the photo before or after this one for personal page
  * Input
  *   beforeAfter($userId, $fotoId, '>')
  * Output
  *   array
  ******************************************************************************************
  */
  function beforeAfter($userId = false, $fotoId = false, $direction = '>')
  {
    $return = false;
    if($userId !== false && $fotoId !== false)
    {
      $userId = intval($userId);
      $fotoId = intval($fotoId);
      $direction = $direction == '>' ? '>' : '<';
      
      $current = $this->dbh->query_first('SELECT up_taken_at FROM user_fotos WHERE up_id = ' . $fotoId);
      $currentDate = $this->dbh->sql_safe($current['up_taken_at']);
      $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_rotation AS P_ROTATION, up.up_privacy AS P_PRIVACY, up.up_views AS P_VIEWS, '
            . 'up.up_camera_make AS P_CAMERA_MAKE, up.up_camera_model AS P_CAMERA_MODEL, up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
            . "up.up_modified_at AS P_MODIFIED_AT, up.up_taken_at AS P_TAKEN, DATE_FORMAT(FROM_UNIXTIME(up.up_taken_at), '%y%m%d') AS P_TAKEN_KEY, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d') AS P_YMD "
            . 'FROM user_fotos AS up '
            . "WHERE up.up_datetaken_at {$direction} {$currentDate} AND up_privacy > " . PERM_PHOTO_PRIVATE . " AND up_status = 'active'";
      
      $return = $this->dbh->query_first($sql);
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   fotoData
  * Description
  *   Method to retrieve an images data
  * Input
  *   fotoData($image_id, $user_id = false) or fotoData($image_hash) // image_hash is md5 of $image_id . '_ff_' . $image_id
  * Output
  *   array
  ******************************************************************************************
  */
  function fotoData()
  {
    $return   = false;
    $cntArgs  = func_num_args();
    $arg0     = func_get_arg(0);
    
    if(is_numeric($arg0))
    {
      $image_id = $arg0;
      $image_id = $this->dbh->sql_safe($image_id);

      $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_rotation AS P_ROTATION, up.up_privacy AS P_PRIVACY, up.up_views AS P_VIEWS, '
            . 'up.up_camera_make AS P_CAMERA_MAKE, up.up_camera_model AS P_CAMERA_MODEL, up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
            . "up.up_modified_at AS P_MODIFIED_AT, up.up_taken_at AS P_TAKEN, DATE_FORMAT(FROM_UNIXTIME(up.up_taken_at), '%y%m%d') AS P_TAKEN_KEY, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d') AS P_YMD "
            . 'FROM user_fotos AS up '
            . 'WHERE up.up_id = ' . $image_id;
      
      if($cntArgs >= 2)
      {
        $user_id = func_get_arg(1);
        $user_id = $this->dbh->sql_safe($user_id);
        $sql .= ' AND up_u_id = ' . $user_id;
      }
    }
    else
    if(strlen($arg0) == 32)
    {
      $id_hash = func_get_arg(0);
      $id_hash = $this->dbh->sql_safe($id_hash);

      $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_rotation AS P_ROTATION, up.up_privacy AS P_PRIVACY, up.up_views AS P_VIEWS, '
            . 'up.up_camera_make AS P_CAMERA_MAKE, up.up_camera_model AS P_CAMERA_MODEL, up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
            . "up.up_modified_at AS P_MODIFIED_AT, up.up_taken_at AS P_TAKEN, DATE_FORMAT(FROM_UNIXTIME(up.up_taken_at), '%y%m%d') AS P_TAKEN_KEY, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d') AS P_YMD "
            . 'FROM user_fotos AS up '
            . 'WHERE up.up_key = ' . $id_hash;
      
      if($cntArgs >= 2)
      {
        $user_id = func_get_arg(1);
        $user_id = $this->dbh->sql_safe($user_id);
        $sql .= ' AND up_u_id = ' . $user_id;
      }
    }
    else
    {
      return $return;
    }
    
    $sql .=  " AND up.up_status = 'active'";
    
    $return = $this->dbh->query_first($sql);

    return $return;
  }

 /*
  *******************************************************************************************
  * Name
  *   fotosSearch
  * Description
  *   Method to perform a search based on various parameters
  * Input
  *   fotosSearch(array $params)
  * Output
  *   array
  ******************************************************************************************
  */
  function fotosSearch($params = false)
  {
    $extraFields = '';
    $from = 'FROM (user_fotos AS up ';

    if(array_key_exists('GROUP_ID', $params))
    {
      $extraFields = ', gfm.up_orig_id AS P_ORIG_ID, gfm.gfm_status AS GFM_STATUS ';

      if(array_key_exists('GROUP_SHARE', $params))
      {
        if($params['GROUP_SHARE'] == 2)
        {
          $from .= 'LEFT JOIN group_foto_map AS gfm ON up.up_id = gfm.up_id '
                . 'AND gfm.g_id = ' . $params['GROUP_ID'] . ' ';
        }
        else
        {
          $from .= 'LEFT JOIN group_foto_map AS gfm ON up.up_id = gfm.up_orig_id '
             . 'AND gfm.g_id = ' . $params['GROUP_ID'] . ' ';
        }
      }
      else
      {
        $from .= 'LEFT JOIN group_foto_map AS gfm ON up.up_id = gfm.up_orig_id '
              . 'AND gfm.g_id = ' . $params['GROUP_ID'] . ' ';
      }
      
      $from .= ') ';
    }
    else
    if(array_key_exists('NETWORK', $params))
    {
      $from .= "INNER JOIN users AS u ON up.up_u_id = u.u_id) LEFT JOIN user_prefs AS upref ON u.u_id = upref.up_u_id AND upref.up_name = 'AVATAR' ";
      $extraFields = ', u.u_id AS U_ID, u.u_username AS U_USERNAME, upref.up_value AS U_AVATAR ';
    }
    else
    {
      $from .= ') ';
    }
    
    $sql  = 'SELECT SQL_CALC_FOUND_ROWS up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_rotation AS P_ROTATION, up.up_privacy AS P_PRIVACY, up.up_views AS P_VIEWS, '
          . 'up.up_camera_make AS P_CAMERA_MAKE, up.up_camera_model AS P_CAMERA_MODEL, up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
          . "up.up_modified_at AS P_MODIFIED_AT, up.up_taken_at AS P_TAKEN, DATE_FORMAT(FROM_UNIXTIME(up.up_taken_at), '%Y%m%d') AS P_TAKEN_KEY, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, DATE_FORMAT(up.up_created_at, '%Y%m%d') AS P_CREATED_KEY, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD, up.up_history AS P_HISTORY  "
          . $extraFields
          . $from
          . "WHERE ";

    // use array_key exists to allow for nulls to be parsed
    if(array_key_exists('USER_ID', $params))
    {
      if(array_key_exists('NETWORK', $params))
      {
        $sql .= ' (up.up_u_id = ' . $this->dbh->sql_safe($params['USER_ID']) . ' OR up.up_u_id IN
                    (
                      SELECT uf_friendId FROM user_friends WHERE uf_u_id = ' . $this->dbh->sql_safe($params['USER_ID']) . " AND uf_status = 'Confirmed'
                    ) 
                  )";
      }
      else
      {
        $sql .= ' up.up_u_id = ' . $this->dbh->sql_safe($params['USER_ID']) . ' ';
      }
    }
    else
    {
      $sql .= ' 1 ';
    }

    if(array_key_exists('GROUP_ID', $params) && !array_key_exists('GROUP_SHARE', $params))
    {
      $sql .= " AND gfm.gfm_status = 'Active' ";
    }

    if(array_key_exists('TAGS', $params) && $params['TAGS'] != '')
    {
      if(!is_array($params['TAGS']))
      {
        $params['TAGS'] = (array)explode(',', $params['TAGS']);
      }

      if(count($params['TAGS']) > 0)
      {
        array_walk($params['TAGS'], 'tagwalk');
        $sql .= ' AND MATCH(up.up_tags) AGAINST(\'+",' . implode('," +"', $params['TAGS']) . ',"\' IN BOOLEAN MODE) ';
      }
    }

    if(array_key_exists('UNTAGGED', $params))
    {
      $sql .= ' AND up.up_tags IS NULL ';
    }

    if(array_key_exists('PERMISSION', $params))
    {
      $mask = intval($params['PERMISSION']);

      if($mask == PERM_PHOTO_PRIVATE)
      {
        $sql .= ' AND up.up_privacy = ' . $mask . ' ';
      }
      else
      {
        $sql .= ' AND up.up_privacy & ' . $mask . ' = ' . $mask . ' ';
      }
    }
    
    if(array_key_exists('AFTER', $params))
    {
      $sql .= ' AND up.up_taken_at > ' . $this->dbh->sql_safe($params['AFTER']) . ' ';
    }
    
    if(array_key_exists('BEFORE', $params))
    {
      $sql .= ' AND up.up_taken_at < ' . $this->dbh->sql_safe($params['BEFORE']) . ' ';
    }

    if(array_key_exists('DATE_TAKEN_EXACT', $params))
    {
      $sql .= ' AND up.up_taken_at = ' . $this->dbh->sql_safe($params['DATE_TAKEN_EXACT']) . ' ';
    }

    if(array_key_exists('DATE_TAKEN_RANGE', $params))
    {
      $sql .= ' AND up.up_taken_at BETWEEN ' . $this->dbh->sql_safe($params['DATE_TAKEN_RANGE'][0]) . ' AND ' . $this->dbh->sql_safe($params['DATE_TAKEN_RANGE'][1]) . ' ';
    }

    if(array_key_exists('DATE_TAKEN_START', $params))
    {
      $sql .= ' AND up.up_taken_at >= ' . $this->dbh->sql_safe($params['DATE_TAKEN_START']) . ' ';
    }

    if(array_key_exists('DATE_TAKEN_END', $params))
    {
      $sql .= ' AND up.up_taken_at <= ' . $this->dbh->sql_safe($params['DATE_TAKEN_END']) . ' ';
    }

    if(array_key_exists('DATE_CREATED_START', $params))
    {
      $sql .= ' AND up.up_created_at >= ' . $this->dbh->sql_safe(date('Y-m-d 00:00:00', $params['DATE_CREATED_START'])) . ' ';
    }

    if(array_key_exists('DATE_CREATED_END', $params))
    {
      $sql .= ' AND up.up_created_at <= ' . $this->dbh->sql_safe(date('Y-m-d 00:00:00', $params['DATE_CREATED_END'])) . ' ';
    }

    $sql .= " AND up.up_status = 'active' ";

    switch($params['ORDER'])
    {
      case 'P_CREATED':
        $sql .= ' ORDER BY P_CREATED DESC ';
        break;
      case 'P_MOD_YMD':
        $sql .= ' ORDER BY P_MOD_YMD DESC ';
        break;
      case 'P_TAKEN':
        $sql .= ' ORDER BY P_TAKEN DESC ';
        break;
      case 'P_TAKEN_ASC':
        $sql .= ' ORDER BY P_TAKEN ASC ';
        break;
      case 'P_TAKEN_BY_DAY':
        $sql .= ' ORDER BY P_TAKEN_KEY DESC, P_TAKEN ASC ';
        break;
      case 'VIEWS':
        $sql .=  ' ORDER BY P_VIEWS DESC ';
        break;
      default:
        $sql .=  ' ORDER BY P_MODIFIED_AT DESC, P_ID DESC ';
        break;
    }

    if(array_key_exists('LIMIT', $params))
    {
      $sql .= ' LIMIT ' . intval($params['LIMIT']) . ' ';
    }

    if(array_key_exists('OFFSET', $params))
    {
      $sql .= ' OFFSET ' . intval($params['OFFSET']) . ' ';
    }
    
    $return = $this->dbh->query_all($sql);
    
    return $return;
  }

 /*
  *******************************************************************************************
  * Name
  *   getDeleted
  * Description
  *   Get photos with a deleted status
  * Input
  *   getDynamic($user_id, $photo_id)
  * Output
  *   boolean / array
  ******************************************************************************************
  */
  function getDeleted($user_id = false, $photo_id = false)
  {
    if($photo_id !== false && $user_id !== false)
    {
      $photo_id = $this->dbh->sql_safe($photo_id);
      $user_id  = $this->dbh->sql_safe($user_id);
      $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_rotation AS P_ROTATION, up.up_privacy AS P_PRIVACY, up.up_views AS P_VIEWS, '
            . 'up.up_camera_make AS P_CAMERA_MAKE, up.up_camera_model AS P_CAMERA_MODEL, up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
            . "up.up_modified_at AS P_MODIFIED_AT, up.up_taken_at AS P_TAKEN, DATE_FORMAT(FROM_UNIXTIME(up.up_taken_at), '%y%m%d') AS P_TAKEN_KEY, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d') AS P_YMD "
            . 'FROM user_fotos AS up '
            . 'WHERE up.up_id = ' . $photo_id . ' AND up.up_u_id = ' . $user_id . " AND up.up_status = 'Deleted'";

      return $this->dbh->query_first($sql);
    }
    
    return false;
  }
 /*
  *******************************************************************************************
  * Name
  *   getDynamic
  * Description
  *   Get dynamic image
  * Input
  *   getDynamic($user_id, $image_id)
  * Output
  *   boolean / array
  ******************************************************************************************
  */
  function getDynamic($user_id = false, $image_id = false)
  {
    $retval = false;

    if($user_id !== false)
    {
      $sql  = 'SELECT ufd_id AS D_ID, ufd_u_id AS D_U_ID, ufd_up_id AS D_P_ID, ufd_source AS D_SOURCE '
            . 'FROM user_fotos_dynamic '
            . 'WHERE ufd_u_id = ' . intval($user_id) . ' AND ufd_up_id = ' . intval($image_id);
      $retval = $this->dbh->query_all($sql);
    }

    return $retval;
  }

 /*
  *******************************************************************************************
  * Name
  *   getDynamics
  * Description
  *   Get list of dynamic images
  * Input
  *   getDynamics($user_id[, $image_id])
  * Output
  *   boolean / array
  ******************************************************************************************
  */
  function getDynamics($user_id = false, $image_id = false)
  {
    $retval = false;

    if($user_id !== false)
    {
      $sql  = 'SELECT ufd_id AS D_ID, ufd_u_id AS D_U_ID, ufd_up_id AS D_P_ID, ufd_source AS D_SOURCE '
            . 'FROM user_fotos_dynamic '
            . 'WHERE ufd_u_id = ' . intval($user_id);
      if($image_id !== false)
      {
        $sql .= ' AND ufd_up_id = ' . intval($image_id);
      }

      $retval = $this->dbh->query_all($sql);
    }

    return $retval;
  }

 /*
  *******************************************************************************************
  * Name
  *   fotosByIds
  * Description
  *   Method to get fotos with id list
  * Input
  *   fotosByIds($arr_ids = false, $user_id = false, $sort = 'P_MOD_YMD')
  * Output
  *   array
  ******************************************************************************************
  */
  function fotosByIds($arr_ids = false, $user_id = false, $sort = 'P_MOD_YMD', $offset = 0, $limit = false)
  {
    $return = array();
    if(is_array($arr_ids))
    {
      $id_map  = array_flip($arr_ids);
      $arr_ids = $this->dbh->asql_safe($arr_ids);
      $id_list = implode(',', $arr_ids);

      if($sort == 'P_UPL_YMD')
      {
        $order_by = 'P_UPL_YMD DESC ';
      }
      else
      if($sort == 'P_NAME')
      {
        $order_by = 'P_NAME ASC ';
      }
      else
      if($sort === false)
      {
        $order_by = false;
      }
      else
      {
        $order_by = 'P_MOD_YMD DESC ';
      }

      $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_rotation AS P_ROTATION, up.up_privacy AS P_PRIVACY, up.up_views AS P_VIEWS, '
            . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
            . "up.up_modified_at AS P_MODIFIED_AT, up.up_taken_at AS P_TAKEN, DATE_FORMAT(FROM_UNIXTIME(up.up_taken_at), '%y%m%d') AS P_TAKEN_KEY, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD  "
            . 'FROM user_fotos AS up '
            . 'WHERE up.up_id IN (' . $id_list . ") AND up.up_status = 'active' ";
      if($user_id !== false)
      {
        $user_id = $this->dbh->sql_safe($user_id);
        $sql .= ' AND up.up_u_id = ' . $user_id . ' ';
      }

      $sql  .=($order_by !== false ? 'ORDER BY ' . $order_by : '');

      if($limit !== false)
      {
        $sql = preg_replace('/^SELECT/', 'SELECT SQL_CALC_FOUND_ROWS', $sql);
        if($offset !== false)
        {
          $sql .= 'LIMIT ' . intval($offset) . ', ' . intval($limit);
        }
        else
        {
          $sql .= 'LIMIT ' . intval($limit);
        }
      }

      $rs = $this->dbh->query($sql);

      while($data = $this->dbh->fetch_assoc($rs))
      {
        $return[$id_map[$data['P_ID']]] = $data;
      }

      ksort($return);
    }

    return $return;
  }

 /*
  *******************************************************************************************
  * Name
  *   fotosByPrivacy
  * Description
  *   Method to get fotos by privacy status (public or private)
  * Input
  *   fotosByPrivacy($user_id)
  * Output
  *   array
  ******************************************************************************************
  */
  function fotosByPrivacy($user_id = false, $tags = false, $offset = false, $limit = 0, $minViewPermission = 3, $sort = 'P_MOD_YMD')
  {
    $return = array();

    if($sort !== false)
    {
      switch($sort)
      {
        case 'P_UPL_YMD':
          $order_by = 'P_UPL_YMD DESC ';
          break;
        case 'P_NAME':
          $order_by = 'P_NAME ASC ';
          break;
        case 'P_CREATED':
          $order_by = 'P_CREATED DESC ';
          break;
        default:
          $order_by = 'P_MOD_YMD DESC, P_ID DESC ';
          break;
      }
    }

    //$privacy = $this->dbh->sql_safe($type == 'Public' ? 'Y' : 'N');
    $minViewPermission = intval($minViewPermission) > 0 ? $minViewPermission : 4;
    $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_rotation AS P_ROTATION, up.up_privacy AS P_PRIVACY, up.up_views AS P_VIEWS, '
          . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
          . "up.up_modified_at AS P_MODIFIED_AT, up.up_taken_at AS P_TAKEN, DATE_FORMAT(FROM_UNIXTIME(up.up_taken_at), '%y%m%d') AS P_TAKEN_KEY, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD  "
          . 'FROM user_fotos AS up '
          . "WHERE up.up_status = 'Active' AND LEFT(up.up_privacy, 1) >= " . $minViewPermission;

    if($user_id !== false)
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $sql .= ' AND up.up_u_id = ' . $user_id . ' ';
    }

    if($filter !== false)
    {
      $sql .= $filter . ' ';
    }

    $sql  .=(isset($order_by) ? ' ORDER BY ' . $order_by : '');

    if($limit !== false)
    {
      $sql = preg_replace('/^SELECT/', 'SELECT SQL_CALC_FOUND_ROWS', $sql);
      if($offset !== false)
      {
        $sql .= ' LIMIT ' . intval($offset) . ', ' . intval($limit);
      }
      else
      {
        $sql .= ' LIMIT ' . intval($limit);
      }
    }

    $return = $this->dbh->query_all($sql);

    return $return;
  }

 /*
  *******************************************************************************************
  * Name
  *   fotosByStatus
  * Description
  *   Method to get fotos by status
  * Input
  *   fotosByStatus('Deleted')
  * Output
  *   array
  ******************************************************************************************
  */
  function fotosByStatus($status = false, $time_limit = false)
  {
    $return = array();
    if($status !== false)
    {
      $status = $this->dbh->sql_safe($status);
      $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_rotation AS P_ROTATION, up.up_privacy AS P_PRIVACY, up.up_views AS P_VIEWS, '
            . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
            . "up.up_modified_at AS P_MODIFIED_AT, up.up_taken_at AS P_TAKEN, DATE_FORMAT(FROM_UNIXTIME(up.up_taken_at), '%y%m%d') AS P_TAKEN_KEY, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD  "
            . 'FROM user_fotos AS up '
            . 'WHERE up.up_status = ' . $status;
      if($time_limit !== false)
      {
        $base_time = $this->dbh->sql_safe(date('Y-m-d', $time_limit));
        $sql .= ' AND up.up_modified_at < ' . $base_time;
      }

      $return = $this->dbh->query_all($sql);
    }

    return $return;
  }

  /*
  *******************************************************************************************
  * Name
  *   quarantinedFotos
  * Description
  *   Method to retrieve quarantined fotos
  * Input
  *   offset - where to start
  *   limit - how many to get
  * Output
  *   array
  ******************************************************************************************
  */
  function quarantinedFotos($offset = 0, $limit = false)
  {
    $order_by = 'P_MODIFIED_AT DESC, P_ID DESC ';

    $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_rotation AS P_ROTATION, up.up_privacy AS P_PRIVACY, up.up_views AS P_VIEWS, '
          . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
          . "up.up_modified_at AS P_MODIFIED_AT, up.up_taken_at AS P_TAKEN, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD  "
          . 'FROM foto_quarantined AS fq INNER JOIN user_fotos AS up ON fq.fq_up_id = up.up_id ';

    $sql .= " WHERE up.up_status = 'active' "
         .  ' ORDER BY ' . $order_by . ' ';

    if($limit !== false)
    {
      $sql = preg_replace('/^SELECT/', 'SELECT SQL_CALC_FOUND_ROWS', $sql);
      if($offset !== false)
      {
        $sql .= 'LIMIT ' . intval($offset) . ', ' . intval($limit);
      }
      else
      {
        $sql .= 'LIMIT ' . intval($limit);
      }
    }

    $return = $this->dbh->query_all($sql);

    return $return;
 }

 /*
  *******************************************************************************************
  * Name
  *   nextPrev
  * Description
  *   Method to get retrieve next and previous fotos
  * Input
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function nextPrev($params)
  {
    $retval = false;

    $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_rotation AS P_ROTATION, up.up_privacy AS P_PRIVACY, up.up_views AS P_VIEWS, '
          . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
          . "up.up_modified_at AS P_MODIFIED_AT, up.up_taken_at AS P_TAKEN, DATE_FORMAT(FROM_UNIXTIME(up.up_taken_at), '%y%m%d') AS P_TAKEN_KEY, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD  "
          . 'FROM user_fotos AS up '
          . 'WHERE 1 ';

    if(array_key_exists('USER_ID', $params))
    {
      $sql .= ' AND up.up_u_id = ' . intval($params['USER_ID']) . ' ';
    }

    if(array_key_exists('TAGS', $params) && $params['TAGS'] != '')
    {
      if(!is_array($params['TAGS']))
      {
        $params['TAGS'] = (array)explode(',', $params['TAGS']);
      }

      if(count($params['TAGS']) > 0)
      {
        array_walk($params['TAGS'], 'tagwalk');
        $sql .= ' AND MATCH(up.up_tags) AGAINST(\'+",' . implode('," +"', $params['TAGS']) . ',"\' IN BOOLEAN MODE) ';
      }
    }

    if(array_key_exists('UNTAGGED', $params))
    {
      $sql .= ' AND up.up_tags IS NULL ';
    }

    if(array_key_exists('PERMISSION', $params))
    {
      $mask = intval($params['PERMISSION']);
      $sql .= ' AND up.up_privacy & ' . $mask . ' = ' . $mask . ' ';
    }

    $sql .= " AND up.up_status = 'active' ";

    switch($params['ORDER'])
    {
      case 'P_MOD_YMD':
        $sql .= ' ORDER BY P_MOD_YMD DESC ';
        break;
      case 'P_TAKEN':
        $sql .= ' ORDER BY P_TAKEN DESC ';
        break;
      case 'VIEWS':
        $sql .=  ' ORDER BY P_VIEWS DESC ';
        break;
      default:
        $sql .=  ' ORDER BY P_MODIFIED_AT DESC, P_ID DESC ';
        break;
    }
    
    $retval = $this->dbh->query_all($sql);

    return $retval;
  }

 /*
  *******************************************************************************************
  * Name
  *   uploadedSince
  * Description
  *   Method to get fotos uploaded since date specified
  * Input
  *   int user_id
  *   string (yyyy-mm-dd hh:ii:ss)
  * Output
  *   string (yyyy-mm-dd hh:ii:ss)
  ******************************************************************************************
  */
  function uploadedSince($user_id = false, $date = false)
  {
    $return = false;
    if($user_id !== false && $date !== false)
    {
      $sql = 'SELECT up_id AS P_ID FROM user_fotos WHERE up_u_id = ' . $this->dbh->sql_safe($user_id) . ' AND up_created_at > ' . $this->dbh->sql_safe($date) . " AND up_status = 'active'";
      $return = $this->dbh->query_all($sql);
    }

    return $return;
  }

 /*
  *******************************************************************************************
  * Name
  *   lastUploaded
  * Description
  *   Method to get date of last uploaded foto
  * Input
  *   int user_id
  * Output
  *   string (yyyy-mm-dd hh:ii:ss)
  ******************************************************************************************
  */
  function lastUploaded($user_id = false)
  {
    $return = 0;
    if($user_id !== false)
    {
      $sql = 'SELECT MAX(up_created_at) AS LASTUPLOADED FROM user_fotos WHERE up_u_id = ' . $this->dbh->sql_safe($user_id);
      $result = $this->dbh->query_first($sql);

      $return = $result['LASTUPLOADED'];
    }

    return $return;
  }

 /*
  *******************************************************************************************
  * Name
  *   customSize
  * Description
  *   generate and return a url for a custom sized foto
  * Input
  *   customSize(path|id|key, width, height)
  * Output
  *   string
  ******************************************************************************************
  */
  function customSize($identifier = false, $width = false, $height = false)
  {
    if(is_numeric($identifier) || strpos($identifier, '.') === false)
    {
      $fotoData = $this->fotoData($identifier);
      $path = str_replace('/web', '', $fotoData['P_WEB_PATH']);
    }
    else
    {
      $path = preg_replace('/^\/\w+/', '', $identifier);
    }

    $retval = '/dynamic' . preg_replace('/(\.\w+)$/', '_' . $width . '_' . $height . '$1', $path) . '?' . md5('ff_' . $_SERVER['REMOTE_ADDR'] . '_ip');

    return $retval;
  }

 /*
  *******************************************************************************************
  * Name
  *   mp3s
  * Description
  *   Method to get a user's mp3s
  * Input
  *   mp3s($user_id = false)
  * Output
  *   array
  ******************************************************************************************
  */
  function mp3s($user_id = false)
  {
    $return = array();

    if($user_id !== false)
    {
      $user_id = $this->dbh->sql_safe($user_id);

      $sql  = 'SELECT um.um_id AS M_ID, um.um_u_id AS M_U_ID, um.um_name AS M_NAME, "My Music" AS M_GENRE, um.um_length AS M_LENGTH, um.um_size AS M_SIZE, um.um_path AS M_PATH, UNIX_TIMESTAMP(um.um_created_at) AS M_CREATED_AT '
            . 'FROM user_mp3s AS um '
            . 'WHERE um.um_u_id = ' . $user_id . " AND um.um_status = 'Active'";

      $return = $this->dbh->query_all($sql);
    }

    return $return;
  }

 /*
  *******************************************************************************************
  * Name
  *   mp3
  * Description
  *   Method to get an mp3
  * Input
  *   mp3($mp3_id = false, $user_id = false)
  * Output
  *   array
  ******************************************************************************************
  */
  function mp3($mp3_id = false, $user_id = false)
  {
    $return = array();

    if($mp3_id !== false)
    {
      $field = is_numeric($mp3_id) ? $field = 'um.um_id' :  'um.um_path';
      $value = $this->dbh->sql_safe($mp3_id);

      $sql  = 'SELECT um.um_id AS M_ID, um.um_u_id AS M_U_ID, um.um_name AS M_NAME, "My Music" AS M_GENRE, um.um_length AS M_LENGTH, um.um_size AS M_SIZE, um.um_path AS M_PATH, UNIX_TIMESTAMP(um.um_created_at) AS M_CREATED_AT '
            . 'FROM user_mp3s AS um '
            . 'WHERE ' . $field . ' = ' . $value;

      if($user_id !== false)
      {
        $user_id = $this->dbh->sql_safe($user_id);
        $sql .= ' AND um.um_u_id = ' . $user_id;
      }

      $sql .= " AND um.um_status = 'Active'";

      $return = $this->dbh->query_first($sql);
    }

    return $return;
  }

 /*
  *******************************************************************************************
  * Name
  *   spaceUsage
  * Description
  *   Method to get space used in bytes
  * Input
  *   spaceUsage($user_id = false)
  * Output
  *   int
  ******************************************************************************************
  */
  function spaceUsage($user_id = false)
  {
    $return = 0;

    if($user_id !== false)
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $ar = $this->dbh->fetch_assoc(
              $this->dbh->query('SELECT SUM(up_size) AS TOTAL FROM user_fotos WHERE up_u_id = ' . $user_id . " AND up_status = 'active'")
            );

      $return = $ar['TOTAL'];
    }

    return $return;
  }
  /* ********************************* DEPRECATED ***************************************/

 /*
  *******************************************************************************************
  * Name
  *   fotos
  * Description
  *   Method to retrieve fotos for a user
  * Input
  *   fotos($user_id, $filter = false, $sort = 'P_MOD_YMD', $offset = 0, $limit = false)
  * Output
  *   array
  ******************************************************************************************
  */
  function fotos($user_id, $filter = false, $sort = 'P_MODIFIED', $offset = 0, $limit = false)
  {
    $return = array();

    $user_id = $this->dbh->sql_safe($user_id);

    if($sort == 'P_UPL_YMD')
    {
      $order_by = 'P_UPL_YMD DESC ';
    }
    else
    if($sort == 'P_NAME')
    {
      $order_by = 'P_NAME ASC ';
    }
    else
    if($sort == 'P_CREATED')
    {
      $order_by = 'P_CREATED DESC ';
    }
    else
    {
      $order_by = 'P_MODIFIED_AT DESC, P_ID DESC ';
    }

    $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_rotation AS P_ROTATION, up.up_privacy AS P_PRIVACY, up.up_views AS P_VIEWS, '
          . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
          . "up.up_modified_at AS P_MODIFIED_AT, up.up_taken_at AS P_TAKEN, DATE_FORMAT(FROM_UNIXTIME(up.up_taken_at), '%y%m%d') AS P_TAKEN_KEY, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD  "
          . 'FROM user_fotos AS up '
          . 'WHERE up.up_u_id = ' . $user_id . " AND up.up_status = 'active' "
          . ($filter !== false ? $filter : '')
          . ' ORDER BY ' . $order_by . ' ';
    if($limit !== false)
    {
      $sql = preg_replace('/^SELECT/', 'SELECT SQL_CALC_FOUND_ROWS', $sql);
      if($offset !== false)
      {
        $sql .= 'LIMIT ' . intval($limit) . ' OFFSET ' . intval($offset);
      }
      else
      {
        $sql .= 'LIMIT ' . intval($limit);
      }
    }

    $return = $this->dbh->query_all($sql);

    return $return;
  }

 /*
  *******************************************************************************************
  * Name
  *   fotosByTags
  * Description
  *   Method to retrieve fotos by tags (optionally by user
  * Input
  *   fotosByTags(mixed $tags, int $user_id, $filter = false, $sort = 'P_MOD_YMD', $offset = 0, $limit = false)
  * Output
  *   array
  ******************************************************************************************
  */
  function fotosByTags($tags = false, $user_id = false, $privacy = false, $sort = false, $offset = 0, $limit = false)
  {
    if($sort == 'P_UPL_YMD')
    {
      $order_by = 'P_UPL_YMD DESC ';
    }
    else
    if($sort == 'P_NAME')
    {
      $order_by = 'P_NAME ASC ';
    }
    else
    if($sort == 'P_CREATED')
    {
      $order_by = 'P_CREATED DESC ';
    }
    else
    {
      $order_by = 'P_MODIFIED_AT DESC, P_ID DESC ';
    }

    $privacy = intval($privacy);

    $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_rotation AS P_ROTATION, up.up_privacy AS P_PRIVACY, up.up_views AS P_VIEWS, '
          . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
          . "up.up_modified_at AS P_MODIFIED_AT, up.up_taken_at AS P_TAKEN, DATE_FORMAT(FROM_UNIXTIME(up.up_taken_at), '%y%m%d') AS P_TAKEN_KEY, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD  "
          . 'FROM user_fotos AS up '
          . "WHERE ";
    if($user_id !== false)
    {
      $sql .= ' up.up_u_id = ' . $this->dbh->sql_safe($user_id) . ' AND ';
    }
    if($tags !== false && count($tags) > 0)
    {
      $tags = (array)$tags;
      array_walk($tags, 'tagwalk');
      $sql .= ' MATCH(up.up_tags) AGAINST(\'+",' . implode('," +"', $tags) . ',"\' IN BOOLEAN MODE) AND ';
    }
    if($privacy > 0)
    {
      $sql .= ' LEFT(up.up_privacy, 1) >= ' . $privacy . ' AND ';
    }
    $sql .= " up.up_status = 'active' "
         .  ' ORDER BY ' . $order_by . ' ';

    if($limit !== false)
    {
      $sql = preg_replace('/^SELECT/', 'SELECT SQL_CALC_FOUND_ROWS', $sql);
      if($offset !== false)
      {
        $sql .= 'LIMIT ' . intval($limit) . ' OFFSET ' . intval($offset);
      }
      else
      {
        $sql .= 'LIMIT ' . intval($limit);
      }
    }

    //echo $sql;
    $return = $this->dbh->query_all($sql);

    return $return;
 }

 /*
  *******************************************************************************************
  * Name
  *   history
  * Description
  *   Method to get history uploads in batch (by date)
  * Input
  *   history($user_id = false)
  * Output
  *   array
  ******************************************************************************************
  */
  function history($user_id = false)
  {
    $return = array();

    if($user_id !== false)
    {
      $user_id  = $this->dbh->sql_safe($user_id);
      $sql_all  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_rotation AS P_ROTATION, up.up_privacy AS P_PRIVACY, up.up_views AS P_VIEWS, '
                . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
                . "up.up_modified_at AS P_MODIFIED_AT, up.up_taken_at AS P_TAKEN, DATE_FORMAT(FROM_UNIXTIME(up.up_taken_at), '%y%m%d') AS P_TAKEN_KEY, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD "
                . ", DATE_FORMAT(up.up_created_at,  '%y%m%d') AS P_DATE_KEY "
                . 'FROM user_fotos AS up '
                . 'WHERE up.up_u_id = ' . $user_id . " AND up.up_status = 'active' "
                . 'ORDER BY P_DATE_KEY DESC';

      $rs_all = $this->dbh->query($sql_all);

      $key = '';
      while($data = $this->dbh->fetch_assoc($rs_all))
      {
        if($key != $data['P_DATE_KEY'])
        {
          if(strlen($key) > 0)
          {
            $return[$key]['SIZE']   = $size;
            $return[$key]['COUNT']  = $count;
            $return[$key]['IDS']    = implode(',', $ids);
          }
          $ids = array();
          $size= $count = 0;
          $key = $data['P_DATE_KEY'];
        }

        $return[$data['P_DATE_KEY']][]  = $data;

        $ids[]  =  $data['P_ID'];
        $size   += $data['P_SIZE'];
        $count  ++;
      }

      if($this->dbh->num_rows($rs_all) > 0)
      {
        $return[$key]['SIZE']   = $size;
        $return[$key]['COUNT']  = $count;
        $return[$key]['IDS']    = implode(',', $ids);
      }
    }

    return $return;
  }

 /*
  *******************************************************************************************
  * Name
  *   dependencies
  * Description
  *   Method to get dependencies for foto(s)
  * Input
  *   dependencies($foto_ids = false)
  * Output
  *   array
  ******************************************************************************************
  */
  function dependencies($foto_ids = false)
  {
    $return = array();
    if($foto_ids != false)
    {
      include_once PATH_CLASS . '/CGroup.php';
      $g =& CGroup::getInstance();
      foreach($foto_ids as $v)
      {
        if(is_numeric($v))
        {
          $id = $this->dbh->sql_safe($v);
          $rs = $this->dbh->query($sql = 'SELECT ufd_uf_id AS F_ID FROM user_fotoflix_data WHERE ufd_up_id = ' . $id);

          $found = false;

          if($this->dbh->num_rows($rs) > 0)
          {
            $return[$v] = array();
            $return[$v]['FLIX_IDS'] = array();
            while($v2 = $this->dbh->fetch_assoc($rs))
            {
              $return[$v]['FLIX_IDS'][] = $v2['F_ID'];
            }
          }

          $groups = $g->fotoShared($v);
          foreach($groups as $v3)
          {
            $found = true;
            if(!isset($return[$v]))
            {
              $return[$v] = array();
            }
            $return[$v]['GROUPS'][] = $v3;
          }

          $return[$v]['P_ID']     = $v;
          /*if($found === true)
          {
            $return[$v]['P_ID']     = $v;
          }*/
        }
      }

      return $return;
    }
    else
    {
      return $return;
    }
  }

 /*
  *******************************************************************************************
  * Name
  *   zip
  * Description
  *   Method to zip fotos to zip file
  * Input
  *   zip($photo_ids = array(), $user_id = false)
  * Output
  *   string
  ******************************************************************************************
  */
  function zip($photo_ids = array(), $user_id = false)
  {
    $return = false;
    if($user_id !== false)
    {
      $array_fotos   = $this->fotosByIds($photo_ids, $user_id);
      $filename = '/zips/' . $user_id . '_' . time();
      $exec     = 'zip -j ' . PATH_FOTOROOT . $filename . ' ';

      while(list(,$v) = each($array_fotos))
      {
        $exec .= ' ' . PATH_FOTOROOT . $v['P_ORIG_PATH'];
      }

      `{$exec}`;

      $fp = fopen(PATH_FOTOROOT . $filename . '.zip', 'r');
      $return = fread($fp, filesize(PATH_FOTOROOT . $filename . '.zip'));
      fclose($fp);

      unlink(PATH_FOTOROOT . $filename . '.zip');
    }

    return $return;
  }

 /*
  *******************************************************************************************
  * Name
  *   safename
  * Description
  *   Method to strip illegal chars out of filename
  * Input
  *   safename($string)
  * Output
  *   boolean
  ******************************************************************************************
  */
  function safename($string)
  {
    $ext  = array_pop(explode('.', $string));
    $base = basename($string, $ext);
    $new  = preg_replace('/\W/', '', $base) . '.' . $ext;

    return $new;
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

 /*
  *******************************************************************************************
  * Name
  *   CFotobox
  * Description
  *   Constructor
  * Input
  *   None
  * Output
  *   None
  ******************************************************************************************
  */
  function CFotobox()
  {
    if(!isset($this->dbh))
    {
      $this->dbh =&$GLOBALS['dbh'];
    }
  }
}
?>

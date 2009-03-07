<?php
 /*
  *******************************************************************************************
  * Name:  CGroup.php
  *
  * General class for group interaction.
  * This class performs read-only functions on the database.
  *
  * Usage:
  *   include_once('CGroup.php');
  *   $group = new CGroup;
  *   $array_groups = $group->groups($user_id);
  * 
  ******************************************************************************************
  */
class CGroup
{
 /*
  *******************************************************************************************
  * Name
  *   groups
  * Description
  *   Method to retrieve groups user is member of
  *
  * Input (one of the following combinations)
  *   $id                 int   (user_id)
  * Output
  *   array
  ******************************************************************************************
  */
  function groups($user_id = false, $ids = false, $mode = 'all', $order = 0)
  {
    $return = array();
    
    if($user_id !== false)
    {
      if($ids !== false)
      {
        $ids = implode(',', $this->dbh->asql_safe((array)explode(',', $ids)));
      }
      
      $order_field = $order == 0 ? 'g.g_name' : 'g.g_dateModified';
      
      $user_id_safe = $this->dbh->sql_safe($user_id);
      $sql  = 'SELECT g.g_id AS G_ID, g.g_u_id AS G_U_ID, g.g_name AS G_NAME, g.g_description AS G_DESC, g.g_listed AS G_LISTED, g.g_public AS G_PUBLIC, g.g_contributors AS G_CONTRIBUTORS, g.g_delete AS G_DELETE, UNIX_TIMESTAMP(g.g_dateModified) AS G_MODIFIED, UNIX_TIMESTAMP(g.g_dateCreated) AS G_CREATED '
            . 'FROM (
                      groups AS g INNER JOIN user_group_map AS ugm ON g.g_id = ugm.g_id
                    ) 
                    INNER JOIN users AS u ON ugm.u_id = u.u_id '
            . 'WHERE u.u_id = ' . $user_id_safe . ' '
            . ($ids !== false ? ' AND g.g_id IN(' . $ids . ') ' : '')
            . "AND g.g_status = 'Active' "
            . 'ORDER BY ' . $order_field . ' ASC';
      
      $rs = $this->dbh->query($sql);
      
      while($data = $this->dbh->fetch_assoc($rs))
      {
        $append_group = true;
        
        if($mode == 'contribute')
        {
          $append_group = $this->canContribute($data['G_ID'], $user_id, $data);
        }
        
        if($append_group === true)
        {
          $return[] = $data;
        }
      }
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   groupData
  * Description
  *   Method to retrieve groups user is member of
  *
  * Input (one of the following combinations)
  *   $id                 int   (user_id)
  * Output
  *   array
  ******************************************************************************************
  */
  function groupData($group_id = false, $user_id = false)
  {
    $return = false;
    
    if($group_id != false)
    {
      $group_id = $this->dbh->sql_safe($group_id);
      
      $sql  = 'SELECT g.g_id AS G_ID, g.g_u_id AS G_U_ID, g.g_name AS G_NAME, g.g_description AS G_DESC, g.g_listed AS G_LISTED, g.g_public AS G_PUBLIC, g.g_contributors AS G_CONTRIBUTORS, g.g_delete AS G_DELETE, UNIX_TIMESTAMP(g.g_dateModified) AS G_MODIFIED, UNIX_TIMESTAMP(g.g_dateCreated) AS G_CREATED '
            . 'FROM groups AS g '
            . 'WHERE g.g_id = ' . $group_id;
      
      if($user_id !== false && false)
      {
        $user_id  = $this->dbh->sql_safe($user_id);
        $sql .= ' AND g.g_u_id = ' . $user_id;
      }
      
      $return = $this->dbh->query_first($sql);
    }
    
    return $return;
  }

  /*
  *******************************************************************************************
  * Name
  *   feed
  * Description
  *   Method to retrieve the group feed
  *
  * Input
  *   GROUP_ID
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function feed($params)
  {
    $return = array();
    
    if(array_key_exists('GROUP_ID', $params))
    {
      $offset = isset($params['OFFSET']) ? intval($params['OFFSET']) : 0;
      $limit = isset($params['LIMIT']) ? intval($params['LIMIT']) : 25;
      
      $sql = 'SELECT gf_id AS GF_ID, gf_g_id AS GF_G_ID, gf_u_id AS GF_U_ID, gf_type AS GF_TYPE, gf_type_id AS GF_TYPE_ID, UNIX_TIMESTAMP(gf_dateCreated) AS GF_DATE_CREATED, '
           . 'COUNT(gf_id) AS GF_CNT, gf_dateId AS GF_DATE_ID '
           . 'FROM group_feed as gf '
           . 'WHERE gf_g_id = ' . intval($params['GROUP_ID']) . ' '
           . 'GROUP BY GF_DATE_ID, GF_U_ID, GF_TYPE '
           . 'ORDER BY GF_DATE_CREATED DESC '
           . 'LIMIT ' . $offset . ', ' . $limit . ' ';
           
      $return = $this->dbh->query_all($sql);
    }
    
    return $return;
  }
  
  /*
  *******************************************************************************************
  * Name
  *   pendingApproval
  * Description
  *   Method to retrieve items waiting for approval
  *
  * Input
  *   GROUP_ID
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function pendingApproval($params)
  {
    $return = array();
    
    if(array_key_exists('GROUP_ID', $params))
    {
      $offset = isset($params['OFFSET']) ? intval($params['OFFSET']) : 0;
      $limit = isset($params['LIMIT']) ? intval($params['LIMIT']) : 25;
      $sql = '';
      
      switch($params['MODE'])
      {
        default:
        case 'PHOTO':
          $sql = 'SELECT SQL_CALC_FOUND_ROWS up_id AS UP_ID, g_id AS G_ID, u_id AS U_ID, u_orig_id AS U_ORIG_ID, up_orig_id AS UP_ORIG_ID, gfm_status AS GFM_STATUS, UNIX_TIMESTAMP(dateCreated) AS DATE_CREATED '
           . 'FROM group_foto_map AS gfm '
           . 'WHERE g_id = ' . intval($params['GROUP_ID']) . ' '
           . "AND gfm_status = 'Pending' "
           . 'LIMIT ' . $offset . ', ' . $limit . ' ';
          break;
        case 'SLIDESHOW':
          $sql = 'SELECT SQL_CALC_FOUND_ROWS g_id AS G_ID, uf_id AS UF_ID, u_id AS U_ID, u_orig_id AS U_ORIG_ID, uf_orig_id AS UF_ORIG_ID, gfm_status AS GFM_STATUS, UNIX_TIMESTAMP(dateModified) AS DATE_MODIFIED '
           . 'FROM group_fotoflix_map AS gfm '
           . 'WHERE g_id = ' . intval($params['GROUP_ID']) . ' '
           . "AND gfm_status = 'Pending' "
           . 'LIMIT ' . $offset . ', ' . $limit . ' ';
          break;
      }
  
      $return = $this->dbh->query_all($sql);
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   fotos
  * Description
  *   Method to retrieve group fotos
  *
  * Input (one of the following combinations)
  *   $group_id   int     (group_id)
  *   $filter     string  (sql filter)
  *   $sort       string  (sql sort field)
  *   $offset     int     (recordset offset)
  *   $limit      int     (recordset limit)
  * Output
  *   array
  ******************************************************************************************
  */
  function fotos($group_id, $filter = false, $sort = 'P_MOD_YMD', $offset = 0, $limit = false)
  {
    $return = array();
    
    $group_id = $this->dbh->sql_safe($group_id);
    
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
    {
      $order_by = 'P_MODIFIED_AT DESC, P_ID DESC ';
    }
    
    $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_id AS P_UP_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_l_ids AS P_LABELS, '
          . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
          . "up.up_modified_at AS P_MODIFIED_AT, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD  "
          . 'FROM group_foto_map AS gp INNER JOIN user_fotos AS up ON gp.up_id = up.up_id '
          . 'WHERE gp.g_id = ' . $group_id . ' '
          . ($filter !== false ? $filter : '')
          . ' ORDER BY ' . $order_by . ' ';
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
    $sql  = 'SELECT SQL_CALC_FOUND_ROWS up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_l_ids AS P_LABELS, up.up_rotation AS P_ROTATION, up.up_privacy AS P_PRIVACY, up.up_views AS P_VIEWS, '
          . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
          . "up.up_modified_at AS P_MODIFIED_AT, up.up_taken_at AS P_TAKEN, DATE_FORMAT(FROM_UNIXTIME(up.up_taken_at), '%y%m%d') AS P_TAKEN_KEY, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD  "
          . 'FROM group_foto_map AS gp INNER JOIN user_fotos AS up ON gp.up_orig_id = up.up_id '
          . "WHERE ";
    
    // use array_key exists to allow for nulls to be parsed
    if(array_key_exists('GROUP_ID', $params))
    {
      $sql .= ' gp.g_id = ' . $this->dbh->sql_safe($params['GROUP_ID']) . ' ';
    }
    else
    {
      $sql .= ' 1 ';
    }
    
    
    if(array_key_exists('TAGS', $params))
    {
      if(count($params['TAGS']) > 0)
      {
        array_walk($params['TAGS'], 'tagwalk');
        $sql .= ' AND MATCH(up.up_tags) AGAINST(\'+",' . implode('," +"', $params['TAGS']) . ',"\' IN BOOLEAN MODE) ';
      }
    }
    
    if(array_key_exists('PRIVACY', $params))
    {
      $sql .= ' AND LEFT(up.up_privacy, 1) >= ' . $this->dbh->sql_safe($params['PRIVACY']) . ' ';
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
    
    switch($params['ORDER'])
    {
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
  *   fotosByTags
  * Description
  *   Method to retrieve fotos by tags (optionally by user
  * Input
  *   fotosByTags(mixed $tags, int $user_id, $filter = false, $sort = 'P_MOD_YMD', $offset = 0, $limit = false)
  * Output
  *   array
  ******************************************************************************************
  */
  function fotosByTags($tags = false, $group_id = false, $privacy = false, $sort = 'P_MOD_YMD', $offset = 0, $limit = false)
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
    
    $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_l_ids AS P_LABELS, up.up_rotation AS P_ROTATION, up.up_privacy AS P_PRIVACY, up.up_views AS P_VIEWS, '
          . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
          . "up.up_modified_at AS P_MODIFIED_AT, up.up_taken_at AS P_TAKEN, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD  "
          . 'FROM group_foto_map AS gp INNER JOIN user_fotos AS up ON gp.up_id = up.up_id '
          . "WHERE ";
    if($group_id !== false)
    {
      $sql .= ' gp.g_id = ' . $this->dbh->sql_safe($group_id) . ' AND ';
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
  *   fotoData
  * Description
  *   Method to retrieve photo data
  *
  * Input fotoData($image_id, $group_id = false) or fotoData($image_hash) // image_hash is md5 of $image_id . '_ff_' . $image_id
  * Output
  *   CDatabase resultset
  ******************************************************************************************
  */
  function fotoData()
  {
    $return   = array();
    $cntArgs  = func_num_args();
    $arg0     = func_get_arg(0);
    
    if(is_numeric($arg0))
    {
      $image_id = $arg0;
      $image_id = $this->dbh->sql_safe($image_id);
    
      $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_id AS P_UP_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_l_ids AS P_LABELS, up.up_l_ids AS P_LABELS, '
            . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
            . "up.up_modified_at AS P_MODIFIED_AT, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d') AS P_YMD "
            . 'FROM group_foto_map AS gp INNER JOIN user_fotos AS up ON gp.up_id = up.up_id '
            . 'WHERE gp.up_id = ' . $image_id . ' ';
      
      if($cntArgs == 2)
      {
        $group_id = func_get_arg(1);
        $group_id = $this->dbh->sql_safe($group_id);
        $sql .= ' AND gp.g_id = ' . $group_id;
      }
    }
    else
    if(strlen($arg0) == 32)
    {
      $id_hash = func_get_arg(0);
      $id_hash = $this->dbh->sql_safe($id_hash);
      
      $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_id AS P_UP_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_l_ids AS P_LABELS, up.up_l_ids AS P_LABELS, '
            . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
            . "up.up_modified_at AS P_MODIFIED_AT, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d') AS P_YMD "
            . 'FROM group_foto_map AS gp INNER JOIN user_fotos AS up ON gp.up_id = up.up_id '
            . 'WHERE gp.gp_key = ' . $id_hash;
    }
    else
    {
      return $return;
    }
        
    $return = $this->dbh->fetch_assoc(
                $this->dbh->query($sql)
              );
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   fotosByIds
  * Description
  *   Method to get fotos with id list
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function fotosByIds($arr_ids = false, $group_id = false, $sort = 'P_MOD_YMD', $offset = 0, $limit = false, $pull = 'group')
  {
    $return = array();
    if(is_array($arr_ids) && $group_id !== false)
    {
      $id_map  = array_flip($arr_ids);
      $arr_ids = $this->dbh->asql_safe($arr_ids);
      $group_id = $this->dbh->sql_safe($group_id);
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
      
      if($pull == 'group')
      {
        $pull_field = 'gp.up_id';
        $map_key = 'P_UP_ID';
      }
      else
      {
        $pull_field = 'gp.up_id';
        $map_key = 'P_UP_ID';
      }
      
      $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_id AS P_UP_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_l_ids AS P_LABELS, '
            . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
            . "up.up_modified_at AS P_MODIFIED_AT, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD  "
            . 'FROM group_foto_map AS gp INNER JOIN user_fotos AS up ON gp.up_id = up.up_id '
            . 'WHERE ' . $pull_field . ' IN (' . $id_list . ') AND gp.g_id = ' . $group_id . ' '
            . ($order_by !== false ? 'ORDER BY ' . $order_by : '');
      
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
        $return[$id_map[$data[$map_key]]] = $data;
      }
      
      ksort($return);
    }

    return $return;
  }
  
/*
  *******************************************************************************************
  * Name
  *   fotoShared
  * Description
  *   Method to get groups that a given foto is shared with
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function fotoShared($user_image_id = false)
  {
    $return = array();
    if($user_image_id !== false)
    {
      $user_image_id = $this->dbh->sql_safe($user_image_id);
      $sql  = 'SELECT g.g_id AS G_ID, g.g_u_id AS G_U_ID, g.g_name AS G_NAME, g.g_description AS G_DESC, g.g_listed AS G_LISTED, g.g_public AS G_PUBLIC, g.g_contributors AS G_CONTRIBUTORS, g.g_delete AS G_DELETE, UNIX_TIMESTAMP(g.g_dateModified) AS G_MODIFIED, UNIX_TIMESTAMP(g.g_dateCreated) AS G_CREATED '
            . 'FROM groups AS g INNER JOIN group_foto_map AS gp ON g.g_id = gp.g_id '
            . 'WHERE gp.up_id = ' . $user_image_id;
      $rs   = $this->dbh->query($sql);
      
      while($data = $this->dbh->fetch_assoc($rs))
      {
        $return[] = $data;
      }
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   fotosByMember
  * Description
  *   Method to get fotos uploaded by member
  *
  * Output
  *   array / int / bool
  ******************************************************************************************
  */
  function fotosByMember($group_id = false, $user_id = false, $type = 'list')
  {
    if($group_id !== false && $user_id !== false)
    {
      if($type == 'list')
      {
        $return = array();
        
        $sql  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_id AS P_UP_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_l_ids AS P_LABELS, '
              . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
              . "up.up_modified_at AS P_MODIFIED_AT, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD  "
              . 'FROM group_foto_map AS gp INNER JOIN user_fotos AS up ON gp.up_id = up.up_id '
              . 'WHERE gp.g_id = ' . $group_id . ' AND gp.u_id = ' . $user_id;
        
        return $this->dbh->query_all($sql);
      }
      else
      {
        $sql  = 'SELECT COUNT(gp.g_id) AS NUM_PHOTOS '
              . 'FROM group_foto_map AS gp '
              . 'WHERE gp.g_id = ' . $group_id . ' AND gp.u_orig_id = ' . $user_id . ' '
              . "AND gp.gfm_status = 'Active' ";
              
        return $this->dbh->query_first($sql);
      }
    }
    else
    {
      return false;
    }
  }
  
/*
  *******************************************************************************************
  * Name
  *   memberSearch
  * Description
  *   Method to search for members based on name, username, or email
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function memberSearch($keywords = '', $group_id = false, $offset = 0, $limit = 12)
  {
    include_once PATH_CLASS . '/CUser.php';
    $u = &CUser::getInstance();
    
    $return = false;
    
    if($group_id !== false)
    { 
      $group_id= $this->dbh->sql_safe($group_id);
      $keywords= $this->dbh->sql_safe('%' . $keywords . '%');
      
      $sql  = 'SELECT SQL_CALC_FOUND_ROWS u.u_id AS U_ID, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, u.u_nameLast AS U_NAMELAST, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_accountType AS U_ACCOUNTTYPE, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS, UNIX_TIMESTAMP(ugm.dateCreated) AS U_JOINED '
            . 'FROM users AS u INNER JOIN user_group_map AS ugm ON u.u_id = ugm.u_id '
            . 'WHERE ugm.g_id = ' . $group_id . ' '
            . 'AND (u.u_username LIKE ' . $keywords . ' OR u.u_nameFirst LIKE ' . $keywords . ' OR u.u_nameLast LIKE ' . $keywords . ' OR u.u_email LIKE ' . $keywords . ") AND u.u_status = 'active'"
            . ' LIMIT ' . intval($limit) . ' OFFSET ' . intval($offset);
      
      $rs = $this->dbh->query_all($sql);
      $rs[0]['TOTAL_ROWS'] = $GLOBALS['dbh']->found_rows();
      
      if($rs[0]['TOTAL_ROWS'] == 0)
      {
        $rs = array();
      }
      
      foreach($rs as $k => $v)
      {
        $fotos = $this->fotosByMember($group_id, $v['U_ID'], 'number');
        $rs[$k]['NUMBER_FOTOS'] = $fotos['NUM_PHOTOS'];
        
        $rs[$k]['AVATAR'] = $u->pref($v['U_ID'], 'AVATAR');
      }
      
      $return = $rs;
    }
    
    return $return;
  }
  
  /*
  *******************************************************************************************
  * Name
  *   poolId
  * Description
  *   Method to retrieve a pool id by group id
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function poolId($group_id)
  {
    $group_id= $this->dbh->sql_safe($group_id);
    
    $sql = 'SELECT g.g_id AS G_ID '
         . 'FROM groups AS g '
         . 'WHERE g.g_id = ' . $group_id;
         
    return $this->dbh->query_first($sql);
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
  function nextPrev($id = false, $date = false, $group_id = false, $tags = false, $sort = false, $public = false)
  {
    $return = array();
    if($id !== false)
    {
      switch($sort)
      {
        case 'P_UPL_YMD':
          $order_by = 'P_UPL_YMD ';
          break;
        case 'P_NAME':
          $order_by = 'P_NAME ';
          break;
        case 'P_CREATED':
          $order_by = 'P_CREATED ';
        case false:
        default:
          $order_by = 'P_MODIFIED_AT ';
          break;
      }
      
      $id   = $this->dbh->sql_safe($id);
      $date = $this->dbh->sql_safe($date);
      $group_id_safe = $this->dbh->sql_safe($group_id);
      
      $sql  = '(SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_l_ids AS P_LABELS, '
            . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
            . "up.up_modified_at AS P_MODIFIED_AT, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD, 'PREV' AS DIRECTION, '0' AS P_ORDER "
            . 'FROM group_foto_map AS gp INNER JOIN user_fotos AS up ON gp.up_id = up.up_id '
            . 'WHERE gp.g_id = ' . $group_id_safe . " AND (up.up_modified_at > {$date} OR (up.up_modified_at = {$date} AND up.up_id > {$id})) ";
      if($tags !== false && count($tags) > 0)
      {
        $tags = (array)$tags;
        array_walk($tags, 'tagwalk');
        $sql .= ' AND MATCH(up.up_tags) AGAINST(\'+",' . implode('," +"', $tags) . ',"\' IN BOOLEAN MODE) ';
      }
      $sql  .="ORDER BY {$order_by} ASC, up.up_id ASC "
            . 'LIMIT 1) '
            . 'UNION '
            . '(SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_l_ids AS P_LABELS, '
            . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
            . "up.up_modified_at AS P_MODIFIED_AT, UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD, 'NEXT' AS DIRECTION, '1' AS P_ORDER "
            . 'FROM group_foto_map AS gp INNER JOIN user_fotos AS up ON gp.up_id = up.up_id '
            . 'WHERE gp.g_id = ' . $group_id_safe . " AND (up.up_modified_at < {$date} OR (up.up_modified_at = {$date} AND up.up_id < {$id})) ";
      if($tags !== false && count($tags) > 0)
      {
        // ALREADY DONE ABOVE $tags = (array)$tags;
        // ALREADY DONE ABOVE array_walk($tags, 'tagwalk');
        $sql .= ' AND MATCH(up.up_tags) AGAINST(\'+",' . implode('," +"', $tags) . ',"\' IN BOOLEAN MODE) ';
      }
      $sql  .="ORDER BY {$order_by} DESC, up.up_id DESC "
            . 'LIMIT 1) '
            . 'ORDER BY P_ORDER ASC';
      
      $sql = str_replace('{FILTER}', ($filter == '' ? '' : $filter), $sql);
      // echo $sql;
      $return = $this->dbh->query_all($sql);
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   history
  * Description
  *   Method to get history uploads in batch (by date)
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function history($group_id = false)
  {
    $return = array();
    
    if($group_id !== false)
    {
      $group_id = $this->dbh->sql_safe($group_id);
      $sql_all  = 'SELECT up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_l_ids AS P_LABELS, '
                . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
                . "UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD,DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD "
                . ", CONCAT_WS('|', DATE_FORMAT(up.up_created_at,  '%y%m%d'), up.up_u_id) AS P_DATE_KEY "
                . 'FROM group_foto_map AS gp INNER JOIN user_fotos AS up ON gp.up_id = up.up_id '
                . 'WHERE gp.g_id = ' . $group_id . ' '
                . 'ORDER BY P_DATE_KEY DESC, P_U_ID';
      
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
      
      $return[$key]['SIZE']   = $size;
      $return[$key]['COUNT']  = $count;
      $return[$key]['IDS']    = implode(',', $ids);
    }
    
    return $return;
  }  
  
 /*
  *******************************************************************************************
  * Name
  *   isMember
  * Description
  *   Method to check if this user is a member of the group
  *
  * Output
  *   bool
  ******************************************************************************************
  */
  function isMember($user_id = false, $group_id = false)
  {
    $isMember = false;
    
    if($group_id !== false && $user_id !== false)
    {
      $sql = 'SELECT * FROM user_group_map WHERE g_id = ' . intval($group_id) . ' AND u_id = ' . intval($user_id) . ' ';
      $rs = $this->dbh->query_first($sql);
      
      if($rs != null)
      {
        $isMember = true;
      }
    }
    
    return $isMember;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   isModerator
  * Description
  *   Method to check if this user is a moderator of the group
  *
  * Output
  *   bool
  ******************************************************************************************
  */
  function isModerator($user_id = false, $group_id = false)
  {
    $isModerator = false;
    
    if($group_id !== false && $user_id !== false)
    {
      $sql = 'SELECT * FROM groups WHERE g_id = ' . intval($group_id) . ' AND g_u_id = ' . intval($user_id) . ' ';
      $rs = $this->dbh->query_first($sql);
      
      if($rs != null)
      {
        $isModerator = true;
      }
    }
    
    return $isModerator;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   members
  * Description
  *   Method to get members for a group (calls CUser::groupMembers)
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function members($params)
  {
    include_once PATH_CLASS . '/CUser.php';
    $u =& CUser::getInstance();
    
    return $u->groupMembers($params);
  }
  
 /*
  *******************************************************************************************
  * Name
  *   memberCount
  * Description
  *   Method to get number of members in group
  *
  * Output
  *   int
  ******************************************************************************************
  */
  function memberCount($group_id = false)
  {
    $group_id = $this->dbh->sql_safe($group_id);
    $sql = 'SELECT COUNT(u_id) AS _number FROM user_group_map WHERE g_id = ' . $group_id;
    
    $ar = $this->dbh->fetch_assoc(
            $this->dbh->query($sql)
          );
    
    return (int)$ar['_number'];
  }
  
 /*
  *******************************************************************************************
  * Name
  *   stats
  * Description
  *   Method to get group stats
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function stats($group_id = false, $user_id = false)
  {
    $return = array();
    if($group_id !== false)
    {
      $group_id = $this->dbh->sql_safe($group_id);
      
      $fl = $this->dbh->query_first('SELECT COUNT(gfm.g_id) AS _number FROM group_fotoflix_map AS gfm WHERE gfm.g_id = ' . $group_id);
      $f  = $this->dbh->query_first('SELECT COUNT(gp.g_id) AS _number FROM group_foto_map AS gp  WHERE gp.g_id = ' . $group_id);
      $return['COUNT_FLIX'] = $fl['_number'];
      $return['COUNT_FOTOS']= $f['_number'];
      
      if($user_id !== false)
      {
        $user_id = $this->dbh->sql_safe($user_id);
        
        $fl_u = $this->dbh->query_first('SELECT COUNT(gfm.g_id) AS _number 
                                         FROM group_fotoflix_map AS gfm 
                                         INNER JOIN user_fotoflix as uf on gfm.uf_id = uf.uf_id
                                         WHERE gfm.g_id = ' . $group_id . ' AND uf.uf_u_id = ' . $user_id);
        
        $f_u  = $this->dbh->query_first('SELECT COUNT(gp.g_id) AS _number FROM group_foto_map AS gp  WHERE gp.g_id = ' . $group_id . ' and gp.u_id = ' . $user_id);
        $return['COUNT_FLIX_USER']  = $fl_u['_number'];
        $return['COUNT_FOTOS_USER'] = $f_u['_number'];
      }
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   labels
  * Description
  *   Method to get labels for photo
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function labels($ids = false)
  {
    $return = array();
    if(is_numeric($ids))
    {
      $ids    = $this->dbh->sql_safe($ids);
      $arIds  = $this->dbh->fetch_assoc(
                  $rsIds = $this->dbh->query("SELECT gp_l_ids AS LABELS FROM group_fotos WHERE gp_id = {$ids}")
                );
      
      if($this->dbh->num_rows($rsIds) > 0 && strlen($arIds['LABELS']) > 0)
      {
        $strIds = str_replace(array('||', '|'), array(',', ''), $arIds['LABELS']);
      }
      else
      {
        $strIds = '0';
      }
    }
    else
    if(strlen($ids) > 0)
    {
      $strIds = str_replace(array('||', '|'), array(',', ''), $ids);
    }
    else
    {
      $strIds = 0;
    }
    
    $rs = $this->dbh->query($sql = 
            'SELECT l_id AS L_ID, l_name AS L_NAME, l_icon AS L_ICON '
          . 'FROM labels '
          . "WHERE l_id IN ({$strIds})"
          );
    
    while($data = $this->dbh->fetch_assoc($rs))
    {
      $return[] = $data;
    }
    
    return $return;
  }
  
  /*
  *******************************************************************************************
  * Name
  *   inviteData
  * Description
  *   Method to retrieve an invitation.
  *
  * Input
  *   str     (reference_id) OR
  *   id      (invitation_id)
  * Output
  *   array
  ******************************************************************************************
  */
  function inviteData()
  {
    if(func_num_args() == 1)
    {
      $value = $this->dbh->sql_safe(func_get_arg(0));
      
      if(!is_numeric(func_get_arg(0)))
      {
        $field = 'gi_reference';
      }
      else
      {
        $field = 'gi_id';
      }
      
      $sql  = 'SELECT gi_id AS I_ID, gi_g_id AS I_G_ID, gi_u_id AS I_U_ID, gi_reference AS I_REFERENCE, gi_name AS I_NAME, gi_email AS I_EMAIL ' . LF
            . 'FROM group_invite '
            . 'WHERE ' . $field . ' = ' . $value;
      
      $result = $this->dbh->fetch_assoc(
                  $this->dbh->query($sql)
                );
      
      return $result;
    }
    else
    {
      return array();
    }
  }
  
  /*
  *******************************************************************************************
  * Name
  *   canInvite
  * Description
  *   Method to check for user invite permissions for group
  *
  * Input
  *   int     group_id
  *   int     user_id
  * Output
  *   boolean
  ******************************************************************************************
  */
  function canInvite($group_id = false, $user_id = false)
  {
    if($group_id !== false && $user_id !== false)
    {
      $group_data = $this->groupData($group_id, $user_id);
      
      if($group_data['G_U_ID'] == $user_id || $group_data['G_CONTRIBUTORS'] == 'All')
      {
        return true;
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
  }
  
  /*
  *******************************************************************************************
  * Name
  *   canContribute
  * Description
  *   Method to check for user contribute permissions for group
  *
  * Input
  *   int     group_id
  *   int     user_id
  * Output
  *   boolean
  ******************************************************************************************
  */
  function canContribute($group_id = false, $user_id = false, $group_data = false)
  {
    if($group_id !== false && $user_id !== false)
    {
      if(!is_array($group_data))
      {
        $group_data = $this->groupData($group_id, $user_id);
      }
      
      if($group_data['G_U_ID'] == $user_id || $group_data['G_CONTRIBUTORS'] == 'All' || $group_data['G_CONTRIBUTORS'] == 'Group')
      {
        return true;
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
  }
  
  /*
  *******************************************************************************************
  * Name
  *   isOwner
  * Description
  *   Method to check if user is owner of group
  *
  * Input
  *   int     group_id
  *   int     user_id
  * Output
  *   boolean
  ******************************************************************************************
  */
  function isOwner($group_id = false, $user_id = false)
  {
    if($group_id !== false && $user_id !== false)
    {
      $group_data = $this->groupData($group_id, $user_id);
      
      if($group_data['G_U_ID'] == $user_id)
      {
        return true;
      }
      else
      {
        return false;
      }
    }
    else
    {
      return false;
    }
  }
  
  /*
  *******************************************************************************************
  * Name
  *   fotoContributions
  * Description
  *   Method to retrieve user fotos that the user contributed to the group
  *
  * Input
  *   &u_id - user id
  *   $g_id - group id
  *   $offset - offset to start from
  *   $limit - limit number of fotos
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function fotoContributions($u_id, $g_id, $offset = 0, $limit = 12)
  {
    $u_id = $this->dbh->sql_safe($u_id);
    $g_id = $this->dbh->sql_safe($g_id);
    
    $sql = 'SELECT SQL_CALC_FOUND_ROWS up.up_id AS P_ID, up.up_u_id AS P_U_ID, up.up_key AS P_KEY, up.up_tags AS P_TAGS, up.up_name AS P_NAME, up.up_size AS P_SIZE, up.up_width AS P_WIDTH, up.up_height AS P_HEIGHT, up.up_description AS P_DESC, up.up_l_ids AS P_LABELS, '
         . 'up.up_original_path AS P_ORIG_PATH, up.up_web_path AS P_WEB_PATH, up.up_flix_path AS P_FLIX_PATH, up.up_thumb_path AS P_THUMB_PATH, '
         . "UNIX_TIMESTAMP(up.up_created_at) AS P_CREATED, UNIX_TIMESTAMP(up.up_modified_at) AS P_MODIFIED, DATE_FORMAT(up.up_modified_at, '%y%m%d%k%i%s') AS P_MOD_YMD, DATE_FORMAT(up.up_created_at, '%y%m%d%k%i%s') AS P_UPL_YMD, "
         . "CONCAT_WS('|', DATE_FORMAT(up.up_created_at,  '%y%m%d'), up.up_u_id) AS P_DATE_KEY "
         . 'FROM group_foto_map AS gp INNER JOIN user_fotos AS up ON gp.up_id = up.up_id '
         . 'WHERE gp.g_id = ' . $g_id . ' '
         . 'AND gp.u_id = ' . $u_id . ' '
         . 'ORDER BY P_DATE_KEY DESC '
         . 'LIMIT ' . $limit . ' '
         . 'OFFSET ' . $offset . ' ';
         
    return $this->dbh->query_all($sql);
  }
  
  /*
  *******************************************************************************************
  * Name
  *   flixContributions
  * Description
  *   Method to retrieve user flix that the user contributed to the group
  *
  * Input
  *   &u_id - user id
  *   $g_id - group id
  *   $offset - offset to start from
  *   $limit - limit number of fotos
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function flixContributions($u_id, $g_id, $offset = 0, $limit = 12)
  {
    include_once PATH_CLASS . '/CFlix.php';
    $fl = &CFlix::getInstance();
    
    $u_id = $this->dbh->sql_safe($u_id);
    $g_id = $this->dbh->sql_safe($g_id);
    
    $sql = 'SELECT SQL_CALC_FOUND_ROWS uf.uf_id AS A_ID, uf.uf_u_id AS A_U_ID, uf.uf_tags AS A_TAGS, uf.uf_autoplay AS A_AUTOPLAY, uf.uf_fastflix AS A_FASTFLIX, uf.uf_music AS A_MUSIC, uf.uf_template AS A_TEMPLATE, uf.uf_name AS A_NAME, uf.uf_createdBy AS A_CREATED_BY, uf.uf_description AS A_DESC, uf.uf_fotoCount AS A_FOTO_COUNT, uf.uf_length AS A_LENGTH, uf.uf_views AS A_VIEWS, uf.uf_public AS A_PUBLIC, uf.uf_privacy AS A_PRIVACY, UNIX_TIMESTAMP(uf.uf_dateCreated) AS A_DATECREATED '
         . 'FROM group_fotoflix_map AS gf INNER JOIN user_fotoflix AS uf ON gf.uf_id = uf.uf_id '
         . 'WHERE gf.g_id = ' . $g_id . ' '
         . 'AND uf.uf_u_id = ' . $u_id . ' '
         . 'ORDER BY A_DATECREATED DESC '
         . 'LIMIT ' . $limit . ' '
         . 'OFFSET ' . $offset . ' ';
         
    $rs = $this->dbh->query($sql);
    
    $i = 0;
    while($data = $this->dbh->fetch_assoc($rs))
    {
      $_internal_data = $fl->_data($data['A_ID'], 1);
      $return[$i]     = $data;
      $return[$i]['A_DATA'] = $_internal_data;
      $i++;
    }
    
    $this->dbh->free_result($rs);
    
    return $return;
  }
  
  /*
  *******************************************************************************************
  * Name
  *   prefs
  * Description
  *   get all preferences for a group
  
  * Use CGroup::prefs($group_id)
  *
  * Output
  *   mixed
  ******************************************************************************************
  */
  function prefs($group_id = false)
  {
    $return = array();
    
    if($group_id !== false)
    {
      $group_id= $this->dbh->sql_safe($group_id);
      $sql = 'SELECT gp_name AS G_NAME, gp_value AS G_VALUE FROM group_prefs WHERE gp_g_id = ' . $group_id;
      
      $prefs = $this->dbh->query_all($sql);
      foreach($prefs as $v)
      {
        $return[$v['G_NAME']] = $v['G_VALUE'];
      }
    }
    
    return $return;
  }
  
 /*
  *******************************************************************************************
  * Name
  *   pref
  * Description
  *   get specific preference for a group
  
  * Use CGroup::pref($user_id, $preference)
  *
  * Output
  *   mixed
  ******************************************************************************************
  */
  function pref($group_id = false, $preference = '')
  {
    $return = false;
    
    if($group_id !== false)
    {
      $group_id= $this->dbh->sql_safe($group_id);
      $preference = $this->dbh->sql_safe($preference);
      $sql = 'SELECT gp_value AS G_VALUE FROM group_prefs WHERE gp_g_id = ' . $group_id . ' AND gp_name = ' . $preference;
      
      $pref = $this->dbh->query_first($sql);
      if(!empty($pref['G_VALUE']))
      {
        $return = $pref['G_VALUE'];
      }
    }
    
    return $return;
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
  *   CGroup
  * Description
  *   Constructor
  *
  * Input
  *   None
  * Output
  *   None
  ******************************************************************************************
  */
  function CGroup()
  {
  }
}
?>
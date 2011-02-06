<?php
 /*******************************************************************************************
  * Name:  CTag.php
  *
  * Class to handle tag functions
  *
  * Usage:
  * 
  *******************************************************************************************/
class CTag
{
  /*******************************************************************************************
  * Description
  *   get specific quick set for a group or user
  *   group tags called via groupTags() method
  *
  * Output
  *   array
  *******************************************************************************************/
  function tags($id = false, $order = 'RANDOM', $limit = false, $offset = false, $privacy = 'public')
  {
    $return = array();
    
    switch($order)
    {
      case 'WEIGHT':
        $order = 'WEIGHT DESC';
        break;
      case 'RANDOM':
        $order = 'RANDOM';
        break;
      case 'TAG':
      default:
        $order = 'TAG';
        break;
    }
    
    $id = intval($id);
    
    if($order == 'RANDOM') // get a random subset
    {
      $sql = 'SELECT COUNT(ut_u_id) AS CNT FROM user_tags WHERE ut_u_id = ' . $id . ' ';
      if($privacy == 'public')
      {
        $sql .= 'AND ut_status = \'public\' ';
      }
      $check = $this->dbh->query_first($sql);
      if($limit !== false)
      {
        $offset = rand(0, max(($check['CNT'] - intval($limit)), 0));
      }
      $sql = 'SELECT ut_tag AS TAG, ut_u_id AS USER_ID, ut_count AS TAG_COUNT, ut_weight AS WEIGHT, ut_random AS RANDOM FROM user_tags WHERE ut_u_id = ' . $id . ' ';
      if($privacy == 'public')
      {
        $sql .= 'AND ut_status = \'public\' ';
      }
    }
    else
    {
      $sql = 'SELECT ut_tag AS TAG, ut_u_id AS USER_ID, ut_count AS TAG_COUNT, ut_weight AS WEIGHT, ut_random AS RANDOM FROM user_tags WHERE ut_u_id = ' . $id . ' ';
      if($privacy == 'public')
      {
        $sql .= 'AND ut_status = \'public\' ';
      }
      $sql .= 'ORDER BY ' . $order . ' ';
    }
    
    
    
    if($limit !== false)
    {
      $limit = intval($limit);
      $sql .= " LIMIT {$limit} ";
      
      if($offset !== false)
      {
        $offset = intval($offset);
        $sql .= " OFFSET {$offset} ";
      }
    }
    
    $rs = $this->dbh->query($sql);
    
    $min = 100;
    $max = 0;
    while($data = $this->dbh->fetch_assoc($rs))
    {
      if($data['WEIGHT'] < $min){ $min = $data['WEIGHT']; }
      if($data['WEIGHT'] > $max){ $max = $data['WEIGHT']; }
      $return[] = $data;
    }
    
    if($order == 'RANDOM')
    {
      $tags = array();
      foreach($return as $k => $v)
      {
        $tags[$k] = $v['RANDOM'] . '-' . $k;
      }
      
      if(NOW % 2 == 0)
      {
        sort($tags);
      }
      else
      {
        rsort($tags);
      }
      
      $tmp = array();
      foreach($tags as $k => $v)
      {
        $parts = explode('-', $v);
        $tmp[$k] = $return[$parts[1]];
      }
      
      $return = $tmp;
    }
    
    $return[0]['COUNT'] = count($return);
    $return[0]['MIN'] = $min;
    $return[0]['MAX'] = $max;
    
    return $return;
  }

  /*******************************************************************************************
  * Description
  *   get coordinates for a group of tags
  *
  * Output
  *   array
  *******************************************************************************************/
  function geoForTags($user_id = false, $tags = false)
  {
    $user_id = intval($user_id);
    if($tags)
      $tags = (array)explode(',', preg_replace('/^,|,$/','',$tags));

    if(!$user_id || !$tags)
    {
      return array();
    }

    $tags_safe = implode(",", $this->dbh->asql_safe($tags));
    return $this->dbh->query_all("SELECT * FROM user_tags_geo WHERE utg_u_id={$user_id} AND utg_tag IN({$tags_safe})");
  }
  
  /*******************************************************************************************
  * Description
  *   get specific quick set for a group
  *   calls the tags() method
  *
  * Output
  *   array
  *******************************************************************************************/
  function groupTags($group_id = false, $rand = false)
  {
    return $this->tags($group_id, 'RANDOM', false, 'group');
  }

  /*******************************************************************************************
  * Description
  *   get specific quick set for a user
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function quickSet($user_id = false, $set_id = 0)
  {
    $return = false;
    
    if($user_id !== false)
    {
      $user_id= intval($user_id);
      $set_id = intval($set_id);
      $sql  = 'SELECT uqs.uqs_id AS S_ID, uqs.uqs_p_id AS S_P_ID, uqs.uqs_u_id AS S_U_ID, uqs.uqs_name AS S_NAME, uqs.uqs_tags AS S_TAGS, uqs.uqs_icon AS S_ICON, uqs.uqs_order AS S_ORDER '
            . 'FROM user_quick_sets AS uqs '
            . "WHERE uqs.uqs_id = {$set_id} AND uqs.uqs_u_id = {$user_id} ";
      $return = $this->dbh->query_first($sql);
    }
    
    return $return;
  }
  
  /*******************************************************************************************
  * Description
  *   gets super tags for a user
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function quickSets($user_id = false, $parent_id = 0, $public = false)
  {
    $return = array();
    
    if($user_id !== false)
    {
      $user_id = intval($user_id);
      $parent_id = intval($parent_id);
      $sql  = 'SELECT uqs.uqs_id AS S_ID, uqs.uqs_p_id AS S_P_ID, uqs.uqs_u_id AS S_U_ID, uqs.uqs_name AS S_NAME, uqs.uqs_tags AS S_TAGS, uqs.uqs_icon AS S_ICON, uqs.uqs_order AS S_ORDER '
            . 'FROM user_quick_sets AS uqs '
            . "WHERE uqs.uqs_p_id = {$parent_id} AND uqs.uqs_u_id = {$user_id} "
            . 'ORDER BY uqs.uqs_name ';
      $return = $this->dbh->query_all($sql);
    }
    
    return $return;
  }

  /*******************************************************************************************
  * Description
  *   
  *
  * Output
  *   
  *******************************************************************************************/
  function addSiblings($userId, $tag, $siblings)
  {
    $continue = false;
    $userIdSafe = $this->dbh->sql_safe($userId);
    $tagSafe = $this->dbh->sql_safe($tag);
    $sql = "REPLACE INTO user_tag_sibling(uts_u_id, uts_ut_tag, uts_sibling) VALUES ";
    foreach((array)$siblings as $sibling)
    {
      if(empty($sibling) || $sibling == $tag)
        continue;

      $siblingSafe = $this->dbh->sql_safe($sibling);
      $continue = true;
      $sql .= "({$userIdSafe}, {$tagSafe}, {$siblingSafe}), ";
    }

    if($continue)
    {
      $sql = substr($sql, 0, -2);
      $this->dbh->execute($sql);
    }
  }
  
  /*******************************************************************************************
  * Description
  *   
  *
  * Output
  *   
  *******************************************************************************************/
  function addQuickSet($name, $tags, $user_id, $parent_id, $position)
  {
    $tagsStr = '';
    foreach($tags as $k => $v)
    {
      $tagsStr .= preg_replace('/\W/', '', trim($v)) . ',';
    }
    $tagsStr = substr($tagsStr, 0, -1);
    
    $name     = $this->dbh->sql_safe($name);
    $tagsStr  = $this->dbh->sql_safe($tagsStr);
    $user_id  = intval($user_id);
    $parent_id= intval($parent_id);
    $position = intval($position);
    
    $this->dbh->execute(  "UPDATE user_quick_sets SET uqs_order=uqs_order+1 WHERE uqs_u_id = {$user_id} AND uqs_p_id = {$parent_id} AND uqs_order>={$position}");
    $this->dbh->execute(  "INSERT INTO user_quick_sets(uqs_p_id, uqs_u_id, uqs_name, uqs_tags, uqs_order) "
                        . "VALUES({$parent_id}, {$user_id}, {$name}, {$tagsStr}, {$position})");
    
    return $this->dbh->insert_id();
  }
  
  /*******************************************************************************************
  * Description
  *   
  *
  * Output
  *   
  *******************************************************************************************/
  function deleteQuickSet($set_id, $user_id)
  {
    $user_id = intval($user_id);
    $set_id  = intval($set_id);
    $this->dbh->execute("DELETE FROM user_quick_sets WHERE uqs_u_id={$user_id} AND (uqs_id={$set_id}  OR uqs_p_id={$set_id})");
    return true;
  }
  
  /*******************************************************************************************
  * Description
  *   
  *
  * Output
  *   
  *******************************************************************************************/
  function updateQuickSet($set_id, $user_id, $name, $tags)
  {
    $name= $this->dbh->sql_safe($name);
    $tags= $this->dbh->sql_safe($tags);
    $user_id = intval($user_id);
    $set_id  = intval($set_id);
    
    $this->dbh->execute($sql = "UPDATE user_quick_sets SET uqs_name={$name}, uqs_tags={$tags} WHERE uqs_id = {$set_id} AND uqs_u_id = {$user_id}");
    return true;
  }
  
  /*******************************************************************************************
  * Description
  *   
  *
  * Output
  *   
  *******************************************************************************************/
  function reorder($newOrder = false, $parent_id, $user_id)
  {
    if(is_array($newOrder))
    {
      $currentSets = $this->quickSets($user_id, $parent_id);
      
      foreach($newOrder as $k => $v)
      {
        $sql = 'UPDATE user_quick_sets SET uqs_order = ' . intval($k) . ' WHERE uqs_id = ' . $currentSets[$v]['S_ID'];
        $this->dbh->execute($sql);
      }
    }
    
    return true;
  }
  
  /*******************************************************************************************
  * Description
  *   
  *
  * Output
  *   
  *******************************************************************************************/
  function generateWeights($user_id)
  {
    $user_id = $this->dbh->sql_safe($user_id);
    $tags = array();
    $fotos = $this->dbh->query_all('SELECT up_tags FROM user_fotos WHERE up_u_id = ' . $user_id . " AND up_status = 'active' AND up_tags <> ''");
    foreach($fotos as $fv)
    {
      $tmpTags = (array)explode(',', $fv['up_tags']);
      foreach($tmpTags as $tv)
      {
        if($tv != '')
        {
          $tags[strtolower($tv)]++;
        }
      }
    }
    
    $flix = $this->dbh->query_all('SELECT us_tags FROM user_slideshows WHERE us_u_id = ' . $user_id . " AND us_status = 'active' AND us_tags <> ''");
    foreach($flix as $fv)
    {
      $tmpTags = (array)explode(',', $fv['uf_tags']);
      foreach($tmpTags as $tv)
      {
        if($tv != '')
        {
          $tags[strtolower($tv)]++;
        }
      }
    }
    
    $tagSum = array_sum($tags);
    $tagCount = count($tags);
    
    $sql = 'INSERT INTO user_tags(ut_u_id, ut_tag, ut_count, ut_weight, ut_random, ut_status) VALUES ';
    $continue = false;
    $i = 0;
    
    foreach($tags as $tag => $count)
    {
      $random = rand(0, 1000);
      $tmpWeight = (intval($count) / intval($tagSum) * 100);
      $weight = number_format($tmpWeight, 2);
      
      $status = 'private';
      $sqlStatus = 'SELECT FIND_IN_SET(' . $this->dbh->sql_safe($tag) . ', up_tags) AS IS_IN_SET, up_privacy FROM user_fotos WHERE up_u_id = ' . $user_id . ' AND up_status = \'active\' ';
      $upPrivacy = $this->dbh->query_all($sqlStatus);
      
      foreach($upPrivacy as $k => $v)
      {
        if($v['IS_IN_SET'] > 0 && $v['up_privacy'] > 0)
        {
          $status = 'public';
          break;
        }
      }
      if($status != 'public')
      {
        $sqlStatus = 'SELECT FIND_IN_SET(' . $this->dbh->sql_safe($tag) . ', us_tags) AS IS_IN_SET, us_privacy FROM user_slideshows WHERE us_u_id = ' . $user_id . ' AND us_status = \'active\' ';
        $usPrivacy = $this->dbh->query_all($sqlStatus);
      
        foreach($usPrivacy as $k => $v)
        {
          if($v['IS_IN_SET'] > 0 && $v['us_privacy'] > 0)
          {
            $status = 'public';
            break;
          }
        }
      }
      if($status != 'public')
      {
        $sqlStatus = 'SELECT FIND_IN_SET(' . $this->dbh->sql_safe($tag) . ', v_tags) AS IS_IN_SET, v_privacy FROM user_videos WHERE v_u_id = ' . $user_id . ' AND v_status = \'active\' ';
        $vPrivacy = $this->dbh->query_all($sqlStatus);
      
        foreach($vPrivacy as $k => $v)
        {
          if($v['IS_IN_SET'] > 0 && $v['v_privacy'] > 0)
          {
            $status = 'public';
            break;
          }
        }
      }
      
      $sql .= "({$user_id}, '{$tag}', '{$count}', '{$weight}', '{$random}', '{$status}'), ";
      $continue = true;
      $i++;
    }
    
    $this->dbh->execute('DELETE FROM user_tags WHERE ut_u_id = ' . $user_id);
    
    if($continue === true)
    {
      $sql = substr($sql, 0, -2);
      $this->dbh->execute($sql);
    }
    
    return true;
  }
  
  /*******************************************************************************************
  * Description
  *   
  *
  * Output
  *   
  *******************************************************************************************/
  function addTags($userId = false, $tags = false)
  {
    if($userId !== false && count($tags) > 0)
    {
      if(!is_array($tags))
      {
        $tags = (array)explode(',', $tags);
      }
      
      $userId = intval($userId);
      $tags   = $this->dbh->asql_safe($tags);
      
      $sql = 'INSERT INTO user_tags(ut_u_id, ut_tag, ut_count) VALUES ';
      foreach($tags as $tag)
      {
        $sql .= '(' . $userId . ', ' . $tag . ', 1), ';
      }
      
      // on duplicate key does nothing.
      // if tag doesn't exist then 1 is entered as the count
      $sql = substr($sql, 0, -2) . '  ON DUPLICATE KEY UPDATE ut_tag=VALUES(ut_tag)';
      
      $this->dbh->execute($sql);
    }
  }
  
  
  /*******************************************************************************************
  * Description
  *   get specific quick set for a group
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function groupQuickSet($group_id = false, $set_id = 0)
  {
    $return = false;
    
    if($group_id !== false)
    {
      $group_id= intval($group_id);
      $set_id = intval($set_id);
      $sql  = 'SELECT gqs.gqs_id AS S_ID, gqs.gqs_p_id AS S_P_ID, gqs.gqs_g_id AS S_G_ID, gqs.gqs_name AS S_NAME, gqs.gqs_tags AS S_TAGS, gqs.gqs_icon AS S_ICON, gqs.gqs_order AS S_ORDER '
            . 'FROM group_quick_sets AS gqs '
            . "WHERE gqs.gqs_id = {$set_id} AND gqs.gqs_g_id = {$group_id} ";
      $return = $this->dbh->query_first($sql);
    }
    
    return $return;
  }
  
  /*******************************************************************************************
  * Description
  *   gets super tags for a group
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function groupQuickSets($group_id = false, $parent_id = 0, $public = false)
  {
    $return = array();
    
    if($group_id !== false)
    {
      $group_id = intval($group_id);
      $parent_id = intval($parent_id);
      $sql  = 'SELECT gqs.gqs_id AS S_ID, gqs.gqs_p_id AS S_P_ID, gqs.gqs_g_id AS S_G_ID, gqs.gqs_name AS S_NAME, gqs.gqs_tags AS S_TAGS, gqs.gqs_icon AS S_ICON, gqs.gqs_order AS S_ORDER '
            . 'FROM group_quick_sets AS gqs '
            . "WHERE gqs.gqs_p_id = {$parent_id} AND gqs.gqs_g_id = {$group_id} "
            . 'ORDER BY gqs.gqs_order ';
      $return = $this->dbh->query_all($sql);
    }
    
    return $return;
  }
  
  /*******************************************************************************************
  * Description
  *   
  *
  * Output
  *   
  *******************************************************************************************/
  function groupAddQuickSet($name, $tags, $group_id, $parent_id, $position)
  {
    $tagsStr = '';
    foreach($tags as $k => $v)
    {
      $tagsStr .= preg_replace('/\W/', '', trim($v)) . ',';
    }
    $tagsStr = substr($tagsStr, 0, -1);
    
    $name     = $this->dbh->sql_safe($name);
    $tagsStr  = $this->dbh->sql_safe($tagsStr);
    $group_id  = intval($group_id);
    $parent_id= intval($parent_id);
    $position = intval($position);
    
    $this->dbh->execute(  "UPDATE group_quick_sets SET gqs_order=gqs_order+1 WHERE gqs_g_id = {$group_id} AND gqs_p_id = {$parent_id} AND gqs_order>={$position}");
    $this->dbh->execute(  "INSERT INTO group_quick_sets(gqs_p_id, gqs_g_id, gqs_name, gqs_tags, gqs_order) "
                        . "VALUES({$parent_id}, {$group_id}, {$name}, {$tagsStr}, {$position})");
    
    return $this->dbh->insert_id();
  }
  
  /*******************************************************************************************
  * Description
  *   
  *
  * Output
  *   
  *******************************************************************************************/
  function groupDeleteQuickSet($set_id, $group_id)
  {
    $group_id = intval($group_id);
    $set_id  = intval($set_id);
    $this->dbh->execute("DELETE FROM group_quick_sets WHERE gqs_g_id={$group_id} AND (gqs_id={$set_id}  OR gqs_p_id={$set_id})");
    return true;
  }
  
  /*******************************************************************************************
  * Description
  *   
  *
  * Output
  *   
  *******************************************************************************************/
  function groupUpdateQuickSet($set_id, $group_id, $name, $tags)
  {
    $name= $this->dbh->sql_safe($name);
    $tags= $this->dbh->sql_safe($tags);
    $group_id = intval($group_id);
    $set_id  = intval($set_id);
    
    $this->dbh->execute($sql = "UPDATE group_quick_sets SET gqs_name={$name}, gqs_tags={$tags} WHERE gqs_id = {$set_id} AND gqs_g_id = {$group_id}");
    return true;
  }
  
  /*******************************************************************************************
  * Description
  *   
  *
  * Output
  *   
  *******************************************************************************************/
  function groupReorder($newOrder = false, $parent_id, $group_id)
  {
    if(is_array($newOrder))
    {
      $currentSets = $this->quickSets($group_id, $parent_id);
      
      foreach($newOrder as $k => $v)
      {
        $sql = 'UPDATE group_quick_sets SET gqs_order = ' . intval($k) . ' WHERE gqs_id = ' . $currentSets[$v]['S_ID'];
        $this->dbh->execute($sql);
      }
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
      $inst->dbh =&$GLOBALS['dbh'];
    }
    
    return $inst;
  }
  
  /*******************************************************************************************
  * Description
  *   Constructor
  *******************************************************************************************/
  function CTag()
  {
  }
}

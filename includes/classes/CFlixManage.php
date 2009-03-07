<?php
 /*
  *******************************************************************************************
  * Name:  CFlixManage.php
  *
  * Class to handle Flix write stuff
  *
  * Usage:
  *
  ******************************************************************************************
  */
class CFlixManage
{
 /*
  *******************************************************************************************
  * Name
  *   createSlideshow
  * Description
  *   Method to create a slideshow
  *
  * Input (one of the following combinations)
  *   $main     array
  *   $settings JSON string
  *   $elements JSON string
  * Output
  *   int
  ******************************************************************************************
  */
  function createSlideshow($main = false, $settings = false, $elements = false)
  {
    $retval = 0;
    if(!empty($main['USER_ID']) && $settings !== false && $elements !== false)
    {
      include_once PATH_INCLUDE . '/functions.php'; // needed for tagwalk
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_CLASS . '/CSubscriptionManage.php';
      
      $f  =& CFlix::getInstance();
      $u  =& CUser::getInstance();
      $um =& CUserManage::getInstance();
      $sm =& CSubscriptionManage::getInstance();

      $us_u_id = $this->dbh->sql_safe($main['USER_ID']);
      $us_key  = substr(randomString(32) . NOW, -32);
      $us_key_safe = $this->dbh->sql_safe($us_key);
      $us_name = $this->dbh->sql_safe($main['NAME']);
      $us_fotoCount = intval($main['FOTOCOUNT']);
      
      $settingsArr = jsonDecode($settings);
      $settingsArr[0]['title_str'] = $main['NAME'];
      $settings    = jsonEncode($settingsArr);
      
      $us_settings  = $this->dbh->sql_safe($settings);

      // reset keys
      $elementsArr = jsonDecode($elements);
      $i = 0;
      $tmp = array();
      foreach($elementsArr as $v)
      {
        if(count($v) > 0)
        {
          array_push($tmp, $v);
        }
      }
      $elements = jsonEncode($tmp);
      $us_elements  = $this->dbh->sql_safe($elements);
      
      $us_type      = ($settingsArr[0]['siteBuilder_bool'] == 1) ? "'site'" : "'slideshow'";
      $us_privacy   = intval($main['PRIVACY']);
      $views        = intval($main['VIEWS']);
      $viewsComplete= intval($main['VIEWSCOMPLETE']);
      $order        = 0; // always on top intval($main['ORDER']);
      
      // increment order for all public slideshows
      $sql = 'UPDATE user_slideshows SET us_order=us_order+1 WHERE us_privacy & ' . PERM_SLIDESHOW_DEFUALT . ' = ' . PERM_SLIDESHOW_DEFUALT . ' AND us_u_id = ' . $us_u_id;
      $this->dbh->execute($sql);
      
      // add slideshow
      $sql  = 'INSERT INTO user_slideshows( us_u_id, us_key, us_name, us_settings, us_elements, us_type, us_fotoCount, us_privacy, us_views, us_viewsComplete, us_order, us_dateModified, us_dateCreated) '
            . "VALUES({$us_u_id}, {$us_key_safe}, {$us_name}, {$us_settings}, {$us_elements}, {$us_type}, {$us_fotoCount}, {$us_privacy}, {$views}, {$viewsComplete}, {$order}, NOW(), NOW())";

      $this->dbh->execute($sql);
      $us_id = $this->dbh->insert_id();

      $firstPhoto = '';
      $insertPhotos = false;
      $tags = array();
      $sql = 'INSERT INTO foto_slideshow_map (up_id, us_id) VALUES';
      foreach($elementsArr as $k => $v)
      {
        if(array_key_exists('photoId_int', $v))
        {
          if($firstPhoto == '')
          {
            $firstPhoto = $v['thumbnailPath_str'];
          }

          $tags = array_merge($tags, (array)explode(',', $v['tags_str']));
          $sql .= '(' . intval($v['photoId_int']) . ', ' . $us_id . '), ';
          $insertPhotos = true;
        }
      }

      if($insertPhotos === true)
      {
        $sql = substr($sql, 0, -2);
        $this->dbh->execute($sql);
      }

      if(count($tags) > 0)
      {
        $tags = array_filter(array_unique($tags), 'tagtrim');
        array_walk($tags, 'tagwalk');
        $tags = ',' . implode(',', $tags) . ',';
        $tags = $this->dbh->sql_safe($tags);
        $this->dbh->execute("UPDATE user_slideshows SET us_tags = {$tags} WHERE us_id = {$us_id}");
      }

      $retval = $us_key; //intval($this->dbh->insert_id());
      
      $userData = $u->find($main['USER_ID']);
      
      // add subscription data
      $sm->addData(array('sd_u_id' => $main['USER_ID'], 'sd_elementType' => 'Slideshow_Public', 'sd_element_id' => $us_key, 'sd_thumbnail' => $firstPhoto));
      // set preference
      $um->setPrefs($main['USER_ID'], array('HAS_SLIDESHOW' => 1));
      // add activity
      $um->addActivity($main['USER_ID'], $us_id, 'newSlideshow', $userData['U_USERNAME'], $firstPhoto, $us_key);
    }

    return $retval;
  }

 /*
  *******************************************************************************************
  * Name
  *   updateSlideshow
  * Description
  *   Method to create a slideshow
  *
  * Input (one of the following combinations)
  *   $main     array
  *   $settings array
  *   $elements array
  * Output
  *   boolean
  ******************************************************************************************
  */
  function updateSlideshow($main = false, $settings = false, $elements = false)
  {
    $retval = false;
    if((!empty($main['US_ID']) || !empty($main['US_KEY'])) && !empty($main['USER_ID']))
    {
      include_once PATH_CLASS . '/CUserManage.php';
      $um =& CUserManage::getInstance();
      
      $sqlInit = $sql = 'UPDATE user_slideshows SET ';

      if(isset($main['NAME']))
      {
        $sql .= 'us_name = ' . $this->dbh->sql_safe($main['NAME']) . ', ';
        
        // update settings array's [0]['title_str']
        if($settings !== false)
        {
          $settingsArr = jsonDecode($settings);
          $settingsArr[0]['title_str'] = $main['NAME'];
          $settings    = jsonEncode($settingsArr);
        }
        else
        {
          include_once PATH_CLASS . '/CFlix.php';
          $fl =& CFlix::getInstance();
          if(!empty($main['US_ID']))
          {
            $searchParams = array('FLIX_ID' => $main['US_ID']);
          }
          else
          if(!empty($main['US_KEY']))
          {
            $searchParams = array('KEY' => $main['US_KEY']);
          }
          
          $slideshowData = $fl->search($searchParams);
          
          $settingsArr = jsonDecode($slideshowData['US_SETTINGS']);
          $settingsArr[0]['title_str'] = $main['NAME'];
          $settings    = jsonEncode($settingsArr);
        }
      }

      if(is_array($main['TAGS']) && count($main['TAGS']) > 0)
      {
        $sql .= 'us_tags = ' . $this->dbh->sql_safe(',' . implode(',', $main['TAGS']) . ',') . ', ';
      }
      else
      if(isset($main['TAGS']))
      {
        $sql .= 'us_tags = NULL, ';
      }

      if(isset($main['PRIVACY']))
      {
        $sql .= 'us_privacy = ' . $this->dbh->sql_safe($main['PRIVACY']) . ', ';
      }

      if(isset($main['ORDER']))
      {
        $sql .= 'us_order = ' . $this->dbh->sql_safe($main['ORDER']) . ', ';
      }

      if(isset($main['FOTOCOUNT'])){ $sql .= 'us_fotoCount = ' . intval($main['FOTOCOUNT']) . ', '; }

      if($settings !== false){ $sql .= 'us_settings = ' . $this->dbh->sql_safe($settings) . ', '; }
      if($elements !== false)
      {
        // reset keys
        $elementsArr = jsonDecode($elements);
        $i = 0;
        $tmp = array();
        foreach($elementsArr as $v)
        {
          if(count($v) > 0)
          {
            array_push($tmp, $v);
          }
        }
        $elements = jsonEncode($tmp);
        $sql .= 'us_elements = ' . $this->dbh->sql_safe($elements) . ', ';
      }
      
      $us_type = ($settingsArr[0]['siteBuilder_bool'] == 1) ? "'site'" : "'slideshow'";
      $sql .= 'us_type = ' . $us_type . ', ';

      if($sqlInit != $sql)
      {
        if(!empty($main['US_KEY'])) // update off of key
        {
          $sql = substr($sql, 0, -2) . ' WHERE us_key = ' . $this->dbh->sql_safe($main['US_KEY']) . ' AND us_u_id = ' . $this->dbh->sql_safe($main['USER_ID']);
        }
        else // update off of id
        {
          $sql = substr($sql, 0, -2) . ' WHERE us_id = ' . $this->dbh->sql_safe($main['US_ID']) . ' AND us_u_id = ' . $this->dbh->sql_safe($main['USER_ID']);
        }
        
        $this->dbh->execute($sql);

        if($elements !== false)
        {
          if(!empty($main['US_KEY'])) // get the id if only the key exists
          {
            $sql = 'SELECT us_id FROM user_slideshows WHERE us_key = ' . $this->dbh->sql_safe($main['US_KEY']) . ' ';
            $data = $this->dbh->query_first($sql);
          }

          $sql = 'DELETE FROM foto_slideshow_map '
               . 'WHERE us_id = ' . intval($data['us_id']) . ' ';
          $this->dbh->execute($sql);

          $elementsArr = jsonDecode($elements);
          foreach($elementsArr as $k => $v)
          {
            if(array_key_exists('photoId_int', $v))
            {
              $sql = 'INSERT INTO foto_slideshow_map (up_id, us_id) '
                   . 'VALUES (' . intval($v['photoId_int']) . ', ' . intval($data['us_id']) . ') ';

              $this->dbh->execute($sql);
            }
          }
        }
        
        // set preference
        $um->setPrefs($main['USER_ID'], array('HAS_SLIDESHOW' => 1));
        $retval = true;
      }
    }

    return $retval;
  }

 /*
  *******************************************************************************************
  * Name
  *   delete
  * Description
  *   Method to delete a flix
  *
  * Input (one of the following combinations)
  *   $flix_id                 int   (flix_id)
  *   $user_id                 int   (user_id)
  * Output
  *   boolean
  ******************************************************************************************
  */
  function delete( $flix_ids = false, $user_id = false )
  {
    if($flix_ids !== false && $user_id !== false)
    {
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CUserManage.php';
      $_fl =& CFlix::getInstance();
      $um =& CUserManage::getInstance();

      $user_id_orig = $user_id;
      $user_id = $this->dbh->sql_safe($user_id);

      if(is_array($flix_ids))
      {
        if(count($flix_ids) == 0)
        {
          return false;
        }

        $arr_flix = $flix_ids;
        $flix_ids = $this->dbh->asql_safe($flix_ids);
        $sql_flix_in = implode(',', $flix_ids);
      }
      else
      {
        $sql_flix_in = "'" . str_replace("'", "','", $flix_ids) . "'";
        $arr_flix = array($flix_ids);
      }

      // get the keys for the user_subscription_data table
      $keys = '';
      foreach($arr_flix as $v)
      {
        $flix_data = $_fl->search(array('FLIX_ID' => $v, 'USER_ID' => $user_id_orig));
        $fastflix  = $flix_data['US_KEY'];

        @unlink($src = PATH_FOTOROOT . '/xml/' . substr($fastflix, 0, 2) . '/' . $fastflix . '.xml');

        $keys .= $this->dbh->sql_safe($flix_data['US_KEY']) . ',';
      }
      $keys = substr($keys, 0, -1);

      //$sql_1 = 'DELETE FROM user_fotoflix WHERE uf_id IN(' . $sql_flix_in . ') AND uf_u_id = ' . $user_id;
      $sql_1 = "UPDATE user_slideshows SET us_status = 'Deleted' WHERE us_id IN({$sql_flix_in}) AND us_u_id = {$user_id}";
      $this->dbh->execute($sql_1);

      //$sql_2 = 'DELETE FROM user_fotoflix_data WHERE ufd_uf_id IN(' . $sql_flix_in . ')';
      $sql_2 = "UPDATE user_slideshow_elements SET use_status = 'Deleted' WHERE use_us_id IN({$sql_flix_in})";
      $this->dbh->execute($sql_2);

      //$sql_3 = "DELETE FROM group_fotoflix_map WHERE us_id IN({$sql_flix_in})";
      $this->dbh->execute($sql_3);

      // delete from the subscriptions table so it doesn't get sent out
      $sql_sub = 'UPDATE user_subscription_data '
               . "SET sd_status = 'deleted' "
               . 'WHERE sd_element_id IN (' . $keys . ') '
               . "AND sd_elementType = 'Slideshow_Public' "
               . 'AND sd_u_id = ' . $user_id . ' ';
      
      $um->deleteActivity($user_id_orig, $arr_flix, 'newSlideshow');
      
      $this->dbh->execute($sql_sub);

      return true;
    }
    else
    {
      return false;
    }
  }

  /*
  *******************************************************************************************
  * Name
  *   prune
  * Description
  *   Method to prune photos out of slideshows
  *
  * Input (one of the following combinations)
  *   $ids - one photo id or an array of photo ids
  *   $user-id - user id
  *
  * Output
  *
  ******************************************************************************************
  */
  function prune($ids = false, $user_id = false)
  {
    if(is_array($ids))
    {
      $sql = 'SELECT us_id AS US_ID, us_elements AS US_ELEMENTS '
           . 'FROM user_slideshows '
           . 'WHERE us_u_id = ' . intval($user_id) . ' ';

      $data = $this->dbh->query_all($sql);

      //loop through each slideshow
      foreach($data as $k => $v)
      {
        $update = false;

        $elements = jsonDecode($v['US_ELEMENTS']);
        // loop through the elements array
        // if $v['photoId_int'] is in the ids array
        // then unset that element
        if(is_array($elements))
        {
          foreach($elements as $k_element => $v_element)
          {
            if(in_array($v_element['photoId_int'], $ids))
            {
              // delete this element from the data array
              unset($elements[$k_element]);
              $update = true;
            }
          }
        }

        if($update === true)
        {
          //rewrite this slideshow
          $elements = jsonEncode($elements);
          $sql = 'UPDATE user_slideshows '
               . 'SET us_elements = ' . $this->dbh->sql_safe($elements) . ' '
               . 'WHERE us_id = ' . intval($v['US_ID']) . ' ';

          $this->dbh->execute($sql);
        }
      }
    }
    else
    {
      $sql = 'SELECT us_elements AS US_ELEMENTS '
           . 'FROM user_slideshows '
           . 'WHERE us_d_id = ' . intval($user_id) . ' ';

      $data = $this->dbh->query_all($sql);

      //loop through each slideshow
      foreach($data as $k => $v)
      {
        $update = false;
        $elements = jsonDecode($v['US_ELEMENTS']);
        // loop through the elements array
        // if $v['photoId_int'] is in the ids array
        // then unset that element
        if(is_array($elements))
        {
          foreach($elements as $k_element => $v_element)
          {
            if($v_element['photoId_int'] == $id)
            {
              unset($elements[$k_element]);
              $update = true;
            }
          }
        }

        if($update === true)
        {
          //rewrite this slideshow
          $elements = jsonEncode($elements);
          $sql = 'UPDATE user_slideshows '
               . 'SET us_elements = ' . $this->dbh->sql_safe($elements) . ' '
               . 'WHERE us_id = ' . intval($v['US_ID']) . ' ';

          $this->dbh->execute($sql);
        }
      }
    }
  }

 /*
  *******************************************************************************************
  * Name
  *   reorder
  * Description
  *   Method to reorder public flix
  *
  * Input (one of the following combinations)
  *   $fastflix                 str
  *   $mode                     str
  * Output
  *   int
  ******************************************************************************************
  */
  function reorder($user_id = false, $key = false, $move = false)
  {
    $retval = false;

    if($user_id != false && $key != false && $move != false)
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $key = $this->dbh->sql_safe($key);

      $sql = 'SELECT us_order FROM user_slideshows WHERE us_key = ' . $key . ' AND us_privacy >= ' . PERM_SLIDESHOW_PUBLIC . ' ';
      $slideshowData = $this->dbh->query_first($sql);

      switch($move)
      {
        // move up
        case 'up':
          if($slideshowData['us_order'] > 1)
          {
            // get the id of the slideshow above it
            $sql = 'SELECT us_id, us_order FROM user_slideshows WHERE us_u_id = ' . $user_id . ' AND us_privacy >= ' . PERM_SLIDESHOW_PUBLIC . ' AND us_order <= ' . ($slideshowData['us_order'] - 1) . ' ORDER BY us_order DESC LIMIT 1';
            $swapData = $this->dbh->query_first($sql);
            
            // get the new orders
            //$order = ($slideshowData['us_order'] - 1);
            $order = intval($swapData['us_order']);
            $swapOrder = intval($slideshowData['us_order']);
            
            // insert the new order for this slideshow
            $sql = 'UPDATE user_slideshows SET us_order = ' . intval($order) . ' WHERE us_key = ' . $key . ' ';
            $this->dbh->execute($sql);
            
            // insert the new order for the slideshow above it
            $sql = 'UPDATE user_slideshows SET us_order = ' . intval($swapOrder) . ' WHERE us_id = ' . intval($swapData['us_id']) . ' ';
            $this->dbh->execute($sql);
          }
          break;

        // move down
        case 'down':
          // get the id of the slideshow below it
          $sql = 'SELECT us_id, us_order FROM user_slideshows WHERE us_u_id = ' . $user_id . ' AND us_privacy >= ' . PERM_SLIDESHOW_PUBLIC . ' AND us_order >= ' . ($slideshowData['us_order'] + 1) . ' ORDER BY us_order ASC LIMIT 1';
          $swapData = $this->dbh->query_first($sql);

          if(!empty($swapData))
          {
            // get the new orders
            //$order = ($slideshowData['us_order'] + 1);
            $order = intval($swapData['us_order']);
            $swapOrder = intval($slideshowData['us_order']);
            
            // insert the new order for this slideshow
            $sql = 'UPDATE user_slideshows SET us_order = ' . intval($order) . ' WHERE us_key = ' . $key . ' ';
            $this->dbh->execute($sql);
            
            // insert the new order for the slideshow below it
            $sql = 'UPDATE user_slideshows SET us_order = ' . intval($swapOrder) . ' WHERE us_id = ' . intval($swapData['us_id']) . ' ';
            $this->dbh->execute($sql);
          }
          break;
          
        // move top
        case 'top':
          if($slideshowData['us_order'] > 1)
          {
            // for each slideshow above this one
            for($i = ($slideshowData['us_order'] - 1); $i >= 1; $i--)
            {
              // get the id of the slideshow
              $sql = 'SELECT us_id, us_order FROM user_slideshows WHERE us_u_id = ' . $user_id . ' AND us_privacy >= ' . PERM_SLIDESHOW_PUBLIC . ' AND us_order = ' . intval($i) . ' ';
              $swapData = $this->dbh->query_first($sql);
              
              // insert the new order for this slideshow
              $sql = 'UPDATE user_slideshows SET us_order = ' . intval($i + 1) . ' WHERE us_id = ' . intval($swapData['us_id']) . ' ';
              $this->dbh->execute($sql);
            }
            
            // put this slideshow at the top
            $sql = 'UPDATE user_slideshows SET us_order = 1 WHERE us_key = ' . $key . ' ';
            $this->dbh->execute($sql);
          }
          break;

        // move bottom
        case 'bottom':
          $sql = 'SELECT * FROM user_slideshows WHERE us_u_id = ' . $user_id . ' AND us_privacy >= ' . PERM_SLIDESHOW_PUBLIC . ' AND us_order > 0 ORDER BY us_order ASC ';
          $total = $this->dbh->query_all($sql);
          $cnt = count($total);
          
          // for each slideshow below this one
          for($i = ($slideshowData['us_order'] + 1); $i <= $cnt; $i++)
          {
            // get the id of the slideshow
            $sql = 'SELECT us_id, us_order FROM user_slideshows WHERE us_u_id = ' . $user_id . ' AND us_privacy >= ' . PERM_SLIDESHOW_PUBLIC . ' AND us_order = ' . intval($i) . ' ';
            $swapData = $this->dbh->query_first($sql);
            
            // insert the new order for this slideshow
            $sql = 'UPDATE user_slideshows SET us_order = ' . intval($i - 1) . ' WHERE us_id = ' . intval($swapData['us_id']) . ' ';
            $this->dbh->execute($sql);
          }
          
          // put this slideshow at the top
          $sql = 'UPDATE user_slideshows SET us_order = ' . intval($cnt) . ' WHERE us_key = ' . $key . ' ';
          $this->dbh->execute($sql);
          break;
      }
    }

    return $retval;
  }

 /*
  *******************************************************************************************
  * Name
  *   incrementOrder
  * Description
  *   Method to reorder public flix
  * Input
  *   $user_id        int
  ******************************************************************************************
  */
  function incrementOrder($user_id)
  {
    $sql = 'UPDATE user_slideshows SET us_order = us_order + 1 WHERE us_u_id = ' . $user_id . " AND us_privacy >= " . PERM_SLIDESHOW_PUBLIC . " ";
    $this->dbh->execute($sql);
  }

 /*
  *******************************************************************************************
  * Name
  *   decrementOrder
  * Description
  *   Method to reorder public flix
  * Input
  *   $user_id        int
  ******************************************************************************************
  */
  function decrementOrder($user_id, $fastflix)
  {
    $user_id  = $this->dbh->sql_safe($user_id);
    $fastflix = $this->dbh->sql_safe($fastflix);

    $sql1 = 'SELECT us_order FROM user_slideshows WHERE us_key = ' . $fastflix . ' AND us_u_id = ' . $user_id;
    $ar1  = $this->dbh->query_first($sql1);

    $sql2 = 'UPDATE user_slideshows SET us_order = us_order - 1 WHERE us_u_id = ' . $user_id . ' AND us_order > ' . $ar1['us_order'] . " AND us_privacy >= " . PERM_SLIDESHOW_PUBLIC . " ";
    $this->dbh->execute($sql2);
  }

/*******************************************************************************************
  * Description
  *   Method to duplicate a flix
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function duplicate($flix_id = false, $user_id = false)
  {
    $return = false;

    if($flix_id !== false)
    {
      $flix_id_safe = $this->dbh->sql_safe($flix_id);
      $user_id_safe = $this->dbh->sql_safe($user_id);
      include_once PATH_CLASS . '/CFlix.php';
      $fl =& CFlix::getInstance();

      $mainData = $this->dbh->query_first('SELECT * FROM user_slideshows WHERE us_id = ' . $flix_id_safe . " AND us_status = 'Active'");
      $mainData['us_name'] = 'Copy of ' . $mainData['us_name'];

      $main = array('NAME' => $mainData['us_name'], 'FOTOCOUNT' => $mainData['us_fotoCount'], 'TAGS' => $mainData['us_tags'], 'USER_ID' => $user_id, 'PRIVACY' => 1);
      $settings = $mainData['us_settings'];
      $elements = $mainData['us_elements'];

      $key = $this->createSlideshow($main, $settings, $elements);
      $slideshowData = $this->dbh->query_first('SELECT * FROM user_slideshows WHERE us_key = ' . $this->dbh->sql_safe($key) . " AND us_status = 'Active'");
      $return = $slideshowData['us_id'];
    }

    return $return;
  }

/*******************************************************************************************
  * Description
  *   Method to add tag(s) to flix
  *
  * Output
  *   array/boolean
  *******************************************************************************************/
  function addTags($flix_id = false, $tags = false, $user_id = false)
  {
    if($flix_id !== false || $tags !== false)
    {
      include_once PATH_CLASS . '/CFlix.php';
      $fl =& CFlix::getInstance();

      $tags = array_filter((array)$tags, 'tagtrim');
      array_walk($tags, 'tagwalk');

      $user_id_safe = $this->dbh->sql_safe($user_id);

      if(is_numeric($flix_id))
      {
        $flix_id_safe = $this->dbh->sql_safe($flix_id);
        $flixData = $fl->search(array('FLIX_ID' => $flix_id, 'USER_ID' => $user_id, 'RETURN_TYPE' => 'SINGLE_FOTO'));

        $tagsUnique = $flixData['US_TAGS'] != '' ? (array)explode(',', $flixData['US_TAGS']) : array();
        $tagsExistingString = ',' . implode(',', $tagsUnique);
        foreach($tags as $v)
        {
          if(stristr($tagsExistingString, ',' . $v . ',') === false)
          {
            $tagsUnique[] = $v;
          }
        }

        sort($tagsUnique);

        if(count($tagsUnique) > 0)
        {
          $tagsUnique = preg_replace(array('/^\,+/','/\,+/','/\,+$/'), array('',',',''), implode(',', $tagsUnique)); // remove commas at beginning and end and replace multiple commas with one

          if($user_id !== false)
          {
            $sql = "UPDATE user_slideshows SET us_tags = ',{$tagsUnique},' WHERE us_id = {$flix_id_safe} AND us_u_id = {$user_id_safe}";
          }
          else
          {
            $sql = "UPDATE user_slideshows SET us_tags = ',{$tagsUnique},' WHERE us_id = {$foto_id_safe}";
          }

          $this->dbh->execute($sql);
        }
      }

      return $tagsUnique;
    }

    return false;
  }

/*******************************************************************************************
  * Description
  *   Method to delete tag(s) from flix
  *
  * Output
  *   array/boolean
  *******************************************************************************************/
  function removeTags($flix_id = false, $tags = false, $user_id = false)
  {
    if($flix_id !== false || $tags !== false)
    {
      include_once PATH_CLASS . '/CFlix.php';
      $fl =& CFlix::getInstance();

      if(!is_array($tags)){ $tags = array($tags); }
      $user_id_safe = $this->dbh->sql_safe($user_id);

      if(is_numeric($flix_id))
      {
        $flix_id_safe = $this->dbh->sql_safe($flix_id);
        $flixData = $fl->search(array('FLIX_ID' => $flix_id, 'USER_ID' => $user_id, 'RETURN_TYPE' => 'SINGLE_FOTO'));

        $tagsExisting = $flixData['US_TAGS'] != '' ? (array)explode(',', $flixData['US_TAGS']) : array();
        $tags = ',' . implode(',', (array)$tags) . ',';
        foreach($tagsExisting as $k => $v)
        {
          //if(in_array($v, $tags) || $v == '')
          if(stristr($tags, ',' . $v . ','))
          {
            unset($tagsExisting[$k]);
          }
        }

        if(count($tagsExisting) > 0)
        {
          $tagsExisting = preg_replace(array('/^\,+/','/\,+/','/\,+$/'), array('',',',''), implode(',', $tagsExisting));
          $sql = "UPDATE user_slideshows SET us_tags = ',{$tagsExisting},' WHERE us_id = {$flix_id_safe} AND us_u_id = {$user_id_safe}";
        }
        else
        {
          $sql = "UPDATE user_slideshows SET us_tags = NULL WHERE us_id = {$flix_id_safe} AND us_u_id = {$user_id_safe}";
        }

        $this->dbh->execute($sql);
      }

      return $tagsExisting;
    }

    return false;
  }

/*******************************************************************************************
  * Description
  *  Method to set privacy of a flix
  *
  *  Input
  *    $params
  *        []'s mean the parameter is optional, {}'s mean it's required
  *      {'USER_ID' - id of the user}
  *      {'FLIX_ID' - flix id of the flix}
  *      {'PRIVACY' - privacy setting to set}
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function setPrivacy($params)
  {
    if(isset($params['FLIX_ID']) && isset($params['USER_ID']) && isset($params['PRIVACY']))
    {
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CUser.php'; 
      include_once PATH_CLASS . '/CUserManage.php';
      $fl =& CFlix::getInstance();
      $u  =& CUser::getInstance();
      $um =& CUserManage::getInstance();
      
      $params['FLIX_ID_SAFE'] = $this->dbh->sql_safe($params['FLIX_ID']);
      $params['USER_ID_SAFE'] = $this->dbh->sql_safe($params['USER_ID']);
      $params['PRIVACY_SAFE'] = intval($params['PRIVACY']);

      $rs = $this->dbh->query_first($sql);

      $sql = 'UPDATE user_slideshows '
           . 'SET us_privacy = ' . $params['PRIVACY_SAFE'] . ' '
           . 'WHERE us_id = ' . $params['FLIX_ID_SAFE'] . ' '
           . 'AND us_u_id = ' . $params['USER_ID_SAFE'] . ' ';
      
      if($params['PRIVACY'] == PERM_SLIDESHOW_PRIVATE)
      {
        $um->deleteActivity($params['USER_ID'], $params['FLIX_ID'], 'newSlideshow');
      }
      else
      {
        $userData = $u->find($params['USER_ID']);
        $flixData = $fl->search(array('FLIX_ID' => $params['FLIX_ID'], 'USER_ID' => $params['USER_ID']));
        $firstPhoto = $fl->firstPhoto($flixData['US_ELEMENTS']);
        $um->addActivity($params['USER_ID'], $params['FLIX_ID'], 'newSlideshow', $userData['U_USERNAME'], $firstPhoto['thumbnailPath_str'], $flixData['US_KEY']);
      }
           
      $this->dbh->execute($sql);

      return true;
    }
    else
    {
      return false;
    }
  }

 /*
  *******************************************************************************************
  * Name
  *   export
  * Description
  *   Exports the flix and web versions of aall fotos in a fotoflix to the specifed basepath
  *     basepath/flix_#/flix and basepath/flix_#/web
  *
  * Input
  *   id        int
  *   user_id   int
  *   basepath  string
  * Output
  *   boolean
  ******************************************************************************************
  */
  function export($id, $user_id, $basepath)
  {
    if (!is_dir($basepath)) { return false; }

    $sql  = 'SELECT ufd.ufd_order, uf.up_id, uf.up_web_path, uf.up_flix_path '
          . 'FROM user_fotoflix_data AS ufd '
          . '  INNER JOIN user_fotos AS uf ON uf.up_id = ufd.ufd_up_id '
          . 'WHERE ufd.ufd_uf_id = ' . $this->dbh->sql_safe($id) . ' '
          . 'ORDER BY ufd.ufd_order';
    $res = $this->dbh->query($sql);

    if ($this->dbh->num_rows($res) === 0) { return false; }

    //make directories
    $flixpath = $basepath . '/flix';
    $webpath  = $basepath . '/web';
    if (!is_dir($flixpath)) {
      mkdir($flixpath, 0755);
    }
    if (!is_dir($webpath)) {
      mkdir($webpath, 0755);
    }

    while ($row = $this->dbh->fetch_assoc($res))
    {
      $flixname = substr($row['up_flix_path'], strrpos($row['up_flix_path'], '/') + 1);
      $webname  = substr('000' . $row['ufd_order'], -3) . '.jpg';

      copy(PATH_FOTOROOT . $row['up_flix_path'], $flixpath . '/' . $flixname);
      copy(PATH_FOTOROOT . $row['up_web_path'], $webpath . '/' . $webname);
    }

    //zip up the web dir
    system("zip -mrq {$basepath}/web.zip {$basepath}/web/");
    chmod("$basepath/web.zip", 0755);

    //save xml
    $xml = $this->getXml($id, $user_id, str_replace($_SERVER['DOCUMENT_ROOT'], '', $flixpath));

    @unlink($file_path = $basepath . '/flix.xml');



    $fp = fopen($file_path, 'w');
    fwrite($fp, $xml, strlen($xml));
    fclose($fp);
    chmod($file_path, 0755);

    return true;
  }

 /*
  *******************************************************************************************
  * Name
  *   getXml
  * Description
  *   Method to retrieve xml string for fotoflix
  *
  * Input
  *   flix_ident    int/string
  *   user_id       int
  * Output
  *   boolean
  ******************************************************************************************
  */
  function getXml($flix_ident, $user_id)
  {
    include_once PATH_CLASS . '/CFlix.php';
    $fl         =& CFlix::getInstance();
    if(is_numeric($flix_ident))
    {
      $flix_data= $fl->flixData($flix_ident, $user_id);
      $fastflix = $flix_data['A_FASTFLIX'];
    }
    else
    {
      $fastflix = $flix_ident;
    }

    if(isset($fastflix))
    {
      $xml_string = '';
      $mode       = 'file';
      $filename   = include_once PATH_HOMEROOT . '/dynamicFlixXML.php';
      if(is_file($path = PATH_TMPROOT . '/' . $filename))
      {
        $xml_string = file_get_contents($path);
      }

      return $xml_string;
    }
    else
    {
      return false;
    }
  }

 /*
  *******************************************************************************************
  * Name
  *   writeXml
  * Description
  *   Method to write xml file to disk
  *
  * Input
  *   flix_id   int
  *   user_id   int
  * Output
  *   boolean
  ******************************************************************************************
  */
  function writeXml($fastflix, $user_id)
  {
    $xml_string = $this->getXml($fastflix, $user_id);

    if($xml_string !== false)
    {
      @unlink($file_path = PATH_FOTOROOT . '/xml/' . substr($fastflix, 0, 2) . '/' . $fastflix . '.xml');

      $fp = fopen($file_path, 'a');
      $xml_string = str_replace("\r\n", '', $xml_string);
      $status = fwrite($fp, $xml_string, strlen($xml_string));
      fclose($fp);
      return true;
    }
    else
    {
      return false;
    }
  }

 /*
  *******************************************************************************************
  * Name
  *   clearMp3
  * Description
  *   Method to clear mp3 from flix table
  *
  * Input
  *   string
  * Output
  *   boolean
  ******************************************************************************************
  */
  function clearMp3($mp3_path = false)
  {
    if($mp3_path !== false)
    {
      $mp3_path = $this->dbh->sql_safe($mp3_path);
      $this->dbh->execute(
                    "UPDATE user_fotoflix SET uf_music = '/noMusic.swf' WHERE uf_music = {$mp3_path}"
                  );
      return true;
    }
    return false;
  }

 /*
  *******************************************************************************************
  * Name
  *   viewed
  * Description
  *   Method to increment view counter for a flix
  *
  * Input
  *   string
  * Output
  *   boolean
  ******************************************************************************************
  */
  function viewed($key = false, $complete = false)
  {
    if(strlen($key) == 32)
    {
      $field = $complete === false ? 'us_views' : 'us_viewsComplete';
      $key_safe = $this->dbh->sql_safe($key);
      $sql = "UPDATE user_slideshows SET {$field} = {$field} + 1 WHERE us_key = {$key_safe}";

      $this->dbh->execute($sql);

      $type = ($complete === true) ? 'Slideshow Viewed Complete' : 'Slideshow Viewed';
      $ip = $this->dbh->sql_safe($_SERVER['REMOTE_ADDR']);
      $type = $this->dbh->sql_safe($type);
      $sql = 'INSERT INTO report_data (rd_element_key, rd_type, rd_ipAddress, rd_dateCreated) '
           . "VALUES (" . $key_safe . ", " . $type . ", " . $ip . ", NOW())";

      $this->dbh->execute($sql);
    }
    else
    {
      return false;
    }
  }

 /*
  *******************************************************************************************
  * Name
  *   error
  * Description
  *   Method to retrieve error(s).  Last error or array of all errors.
  *
  * Input
  *   string
  * Output
  *   mixed
  ******************************************************************************************
  */
  function error($type = 'last')
  {
    if($type == 'last')
    {
      return $this->error[(count($this->error) - 1)];
    }
    elseif($type == 'all')
    {
      return $this->error;
    }
  }


  /*
  *******************************************************************************************
  * Name
  *   runScheduledFlix
  * Description
  *   Method to make a scheduled flix public or private
  *
  * Input
  *   $date - current date
  * Output
  *
  ******************************************************************************************
  */
  function runScheduledFlix($date = false)
  {
    $sql = 'SELECT fs_id AS FS_ID, fs_uf_id AS FS_UF_ID, fs_u_id AS FS_U_ID, UNIX_TIMESTAMP(fs_beginDate) AS FS_BEGINDATE, UNIX_TIMESTAMP(fs_endDate) as FS_ENDDATE, fs_initialPrivacy AS FS_INITIALPRIVACY, fs_privacy AS FS_PRIVACY '
         . 'FROM flix_scheduled ';

    if( $date === false )
    {
      $date = date('Y-m-d H', NOW);
      $date = $this->dbh->sql_safe($date);
    }
    else
    {
      $date = date('Y-m-d H', $date);
      $date = $this->dbh->sql_safe($date);
    }
    $sql .= 'WHERE fs_beginDate = ' . $date . ' OR fs_endDate = ' . $date;

    $data = $this->dbh->query_all($sql);

    foreach( $data as $k => $v )
    {
      if( $v['FS_BEGINDATE'] == $date )
      {
        $this->setPrivacy( $v['FS_UF_ID'], $v['FS_U_ID'], $v['FS_PRIVACY'] );
      }

      if( $v['FS_ENDDATE'] == $date )
      {
        $this->setPrivacy( $v['FS_UF_ID'], $v['FS_U_ID'], $v['FS_INITIALPRIVACY'] );
      }
    }
  }

  /*
  *******************************************************************************************
  * Name
  *   insertScheduledFlix
  * Description
  *   Method to make insert a scheduled flix
  *
  * Input
  *   $uf_id - flix id
  *   $u_id - user id
  *   $date - begin date/end date
  *   $initialPriv - privacy to change back to
  *   $priv - privacy to set flix to
  * Output
  *
  ******************************************************************************************
  */
  function insertScheduledFlix($uf_id, $u_id, $date, $initialPriv = '111', $priv = '311')
  {
    $uf_id = $this->dbh->sql_safe($uf_id);
    $u_id = $this->dbh->sql_safe($u_id);
    $date[0] = date('Y-m-d H:i:s', $date[0]);
    $date[0] = $this->dbh->sql_safe($date[0]);
    $date[1] = date('Y-m-d H:i:s', $date[1]);
    $date[1] = $this->dbh->sql_safe($date[1]);
    $initialPriv = $this->dbh->sql_safe($initialPriv);
    $priv = $this->dbh->sql_safe($priv);

    $sql = 'INSERT INTO flix_scheduled (fs_uf_id, fs_u_id, fs_beginDate, fs_endDate, fs_initialPrivacy, fs_privacy)'
         . 'VALUES (' . $uf_id . ', ' . $u_id . ', ' . $date[0] . ', ' . $date[1] . ', ' . $initialPriv . ', ' . $priv . ')';

    $this->dbh->execute($sql);
  }

  /*
  *******************************************************************************************
  * Name
  *   scheduledFlixData
  * Description
  *   Method to retrieve scheduled flix data
  *
  * Input
  *   $u_id - user id
  *   $uf_id - flix id
  *   $beginDate - begin date
  *   $endDate - end date
  * Output
  *   array
  ******************************************************************************************
  */
  function scheduledFlixData($u_id, $uf_id = false, $beginDate = false, $endDate = false)
  {
    $sql = 'SELECT fs_id AS FS_ID, fs_uf_id AS FS_UF_ID, fs_u_id AS FS_U_ID, UNIX_TIMESTAMP(fs_beginDate) AS FS_BEGINDATE, UNIX_TIMESTAMP(fs_endDate) as FS_ENDDATE, fs_initialPrivacy AS FS_INITIALPRIVACY, fs_privacy AS FS_PRIVACY '
         . 'FROM flix_scheduled';

    $u_id = $this->dbh->sql_safe($u_id);
    $sql .= ' WHERE fs_u_id = ' . $u_id;

    if( $uf_id !== false )
    {
      $uf_id = $this->dbh->sql_safe($uf_id);
      $sql .= ' AND fs_uf_id = ' . $uf_id;
    }

    if( $beginDate !== false )
    {
      $beginDate = date('Y-m-d H:i:s', $beginDate);
      $beginDate = $this->dbh->sql_safe($beginDate);
      $sql .= ' AND fs_beginDate = ' . $beginDate;
    }

    if( $endDate !== false )
    {
      $endDate = date('Y-m-d H:i:s', $endDate);
      $endDate = $this->dbh->sql_safe($endDate);
      $sql .= ' AND fs_endDate = ' . $endDate;
    }

    return $this->dbh->query_all($sql);
  }

 /*******************************************************************************************
  * Name
  *   schedule
  *
  * Description
  *   sets a flix schedule
  *
  * Input
  *   $params
  *   {'US_ID' - slideshow id}
  *   {'START_DATE' - date to start showing a flix}
  *   {'END_DATE' - date to stop showing a flix}
  *
  * Output
  *   insert id - new schedule id
  *******************************************************************************************/
  function schedule($params)
  {
    $params['US_ID'] = intval($params['US_ID']);
    $params['US_U_ID'] = intval($params['US_U_ID']);

    $date = split('/', $params['START_DATE']);
    $startDate = $date[2] . '-' . $date[0] . '-' . $date[1];

    $date = split('/', $params['END_DATE']);
    $endDate = $date[2] . '-' . $date[0] . '-' . $date[1];

    $startDate = $this->dbh->sql_safe($startDate);
    $endDate = $this->dbh->sql_safe($endDate);

    $sql = 'INSERT INTO user_slideshow_schedule (uss_us_id, uss_u_id, uss_startDate, uss_endDate, uss_dateCreated) '
         . 'VALUES (' . $params['US_ID'] . ', ' . $params['US_U_ID'] . ', ' . $startDate . ', ' . $endDate . ', NOW()) ';

    $this->dbh->execute($sql);
    return $this->dbh->insert_id();
  }

 /*******************************************************************************************
  * Name
  *   schedule
  *
  * Description
  *   unsets a flix schedule
  *
  * Input
  *   $params
  *   {'US_ID' - slideshow id}
  *
  * Output
  *   int id (same as args[0]
  *******************************************************************************************/
  function unschedule($id, $userId)
  {
    $sql = 'DELETE FROM user_slideshow_schedule WHERE uss_id = ' . intval($id) . ' AND uss_u_id = ' . intval($userId);

    $this->dbh->execute($sql);

    return $id;
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
  *   CFlixManage
  * Description
  *   Constructor
  ******************************************************************************************
  */
  function CFlixManage()
  {
  }
}
?>
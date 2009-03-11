<?php
 /*******************************************************************************************
  * Name:  CFotoboxManage.php
  *
  * Class to handle fotobox write functions
  *
  * Usage:
  *
  *******************************************************************************************/
class CFotoboxManage
{
  /*******************************************************************************************
  * Description
  *   Add image to database
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function add($data = false)
  {
    if(is_array($data))
    {
      include_once PATH_INCLUDE . '/functions.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_CLASS . '/CFotobox.php';
      $u    =& CUser::getInstance();
      $um   =& CUserManage::getInstance();
      $u_size = $data['up_size'];
      $user_id = $data['up_u_id'];
      $fb = &CFotobox::getInstance();

      $data['up_name'] = sanitize($data['up_name']);
      $data['up_description'] = sanitize($data['up_description']);

      $data = $this->dbh->asql_safe($data);

      $ins = 'INSERT INTO user_fotos(';
      $val = 'VALUES(';

      foreach($data as $k=> $v)
      {
        $ins .= "{$k}, ";
        $val .= "{$v}, ";
      }

      $ins = substr($ins, 0, (strlen($ins) - 2)) . ', up_modified_at, up_created_at) ';
      $val = substr($val, 0, (strlen($val) - 2)) . ', Now(), Now()) ';

      $sql = $ins . $val;
      
      $this->dbh->execute($sql);

      $insert_id = $this->dbh->insert_id();

      $user_data = $u->find($user_id);
      $total_size = $u_size + $user_data['U_SPACEUSED'];
      $um->update(array('u_spaceUsed' => $total_size, 'u_id' => $user_id));
      
      return $insert_id;
    }
    else
    {
      return false;
    }
  }

  /*******************************************************************************************
  * Description
  *   Update image data in database (do not use to change image source)
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function update($data = false, $timestamp = true)
  {
    if(is_array($data) && isset($data['up_id']))
    {
      include_once PATH_INCLUDE . '/functions.php';

      if(isset($data['up_name']))
      {
        $data['up_name'] = sanitize($data['up_name']);
      }

      if(isset($data['up_description']))
      {
        $data['up_description'] = sanitize($data['up_description']);
      }

      $data = $this->dbh->asql_safe($data);

      $sql  = 'UPDATE user_fotos SET ';

      foreach($data as $k => $v)
      {
        $sql .= $k . ' = ' . $v . ', ';
      }

      if($timestamp === false)
      {
        $sql = substr($sql, 0, strrpos($sql, ',')) . ' ';
      }
      else
      {
        $sql .= 'up_modified_at = Now() ';
      }

      $sql .= " WHERE up_id = {$data['up_id']} ";

      if(isset($data['up_u_id']))
      {
        $sql .= 'AND up_u_id = ' . $data['up_u_id']; // this has already been safe'd
      }

      $this->dbh->execute($sql);
      return true;
    }
    else
    {
      return false;
    }
  }

  /*******************************************************************************************
  * Description
  *   Delete images (flags images as inactive)
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function delete($ids = false, $user_id)
  {
    include_once PATH_CLASS . '/CUserManage.php';
    $um =& CUserManage::getInstance();
    
    $user_id_safe = $this->dbh->sql_safe($user_id);
    $continue = false;
    if(is_array($ids))
    {
      $ids_safe= implode(',', $this->dbh->asql_safe($ids));
      $where_u = 'WHERE up_id IN(' . $ids_safe . ') ';
      $where_g = 'WHERE up_id IN(' . $ids_safe . ') ';
			$where_sub = 'WHERE sd_element_id IN(' . $ids_safe . ') ';
      $continue = true;
    }
    else
    if(is_numeric($ids))
    {
      $ids_safe= $this->dbh->sql_safe($ids);
      $where_u = 'WHERE up_id = ' . $ids_safe . ' ';
      $where_g = 'WHERE up_id = ' . $ids_safe . ' ';
			$where_sub = 'WHERE sd_element_id = ' . $ids_safe . ' ';
      $continue = true;
    }

    if($continue === true)
    {
      $sql_c  = "SELECT SUM(up_size) AS _size FROM user_fotos " . $where_u . " AND up_status = 'active' AND up_u_id = " . $user_id_safe;
      $ar_c   = $this->dbh->query_first($sql_c);

      $sql_a  = 'UPDATE users SET u_spaceUsed = (u_spaceUsed - ' . floor($ar_c['_size']) . ') WHERE u_id = ' . $user_id_safe;

      $sql_u  = 'UPDATE user_fotos '
              . "SET up_status = 'deleted', up_modified_at = NOW() "
              . $where_u . ' AND up_u_id = ' . $user_id_safe;

			// delete from the subscriptions table so it doesn't get sent out
			$sql_sub = 'UPDATE user_subscription_data '
			         . "SET sd_status = 'deleted' "
							 . $where_sub
							 . "AND sd_elementType = 'Photo_Public' "
							 . 'AND sd_u_id = ' . $user_id_safe . ' ';
		  
      $um->deleteActivity($user_id, $ids, 'newPhoto');
      /*$sql_g  = 'UPDATE group_fotos '
              . "SET gp_status = 'deleted' "
              . $where_g . ' AND gp_u_id = ' . $user_id_safe;*/
      /*$sql_g  = 'DELETE FROM group_foto_map ' . $where_g . ' AND u_id = ' . $user_id_safe;*/

      $prune_ids = (array)$ids;
      include_once PATH_CLASS . '/CFlixManage.php';
      $f =& CFlixManage::getInstance();
      $f->prune($prune_ids, $user_id);

      $this->dbh->execute($sql_a);
      $this->dbh->execute($sql_u);
      $this->dbh->execute($sql_g);
			$this->dbh->execute($sql_sub);

      return true;
    }
    else
    {
      return false;
    }
  }

  /*******************************************************************************************
  * Description
  *   Copies an image (does not restore from original)
  *   This is used to share with the group
  *
  * Input
  *   $foto_id    Id of the photo to be copied
  *   $user_id    Id of the user this photo is being copied to (defaults to owner of photo)
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function copy($foto_id = false, $user_id = false, $group = false)
  {
    $retval = false;

    if($foto_id !== false)
    {
      include_once PATH_INCLUDE . '/functions.php'; // needed for randomString()
      include_once PATH_CLASS . '/CIdat.php';
      include_once PATH_CLASS . '/CFotobox.php';
      $id =& CIdat::getInstance();
      $fb =& CFotobox::getInstance();

      $foto_data = $fb->fotoData($foto_id);

      if($user_id === false)
      {
        $user_id = $foto_data['P_U_ID'];
      }

      $newName = preg_replace('/\/thumbnail\/\d{6}\/\d+/', ('/' . $this->stamp . '/' . NOW), $foto_data['P_THUMB_PATH']);

      copy(PATH_FOTOROOT . $foto_data['P_THUMB_PATH'], PATH_FOTOROOT . '/original' . $newName); // duplicate original
      copy(PATH_FOTOROOT . $foto_data['P_THUMB_PATH'], PATH_FOTOROOT . '/thumbnail' . $newName); // duplicate thumb

      $add_data = $this->dbh->query_first('SELECT * FROM user_fotos WHERE up_id = ' . intval($foto_id));

      // up_web_path, up_flix_path can be removed once the columns are dropped
      unset($add_data['up_id'], $add_data['up_u_id'], $add_data['up_key'], $add_data['up_created_at'], $add_data['up_modified_at'], $add_data['up_web_path'], $add_data['up_flix_path']);
      $add_data['up_u_id'] = intval($user_id); // new user id
      $add_data['up_key'] = $id->nextID(FF_IMAGE_KEY) . randomString();

      //if($group !== false)
      //{
        //$add_data['up_status'] = 'pending';
      //}
      $retval = $this->add($add_data);
    }

    return $retval;
  }

  /*******************************************************************************************
  * Description
  *   Add dynamic image to database
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function addDynamic($user_id = false, $photo_id = false, $path = false, $width = 0, $height = 0, $ip = '')
  {
    if($user_id !== false && $photo_id !== false && $path !== false)
    {
      $user_id  = $this->dbh->sql_safe($user_id);
      $photo_id = $this->dbh->sql_safe($photo_id);
      $path   = $this->dbh->sql_safe($path);
      $width  = intval($width);
      $height = intval($height);
      $ip     = $this->dbh->sql_safe($ip);
      $sql  = 'INSERT INTO user_fotos_dynamic(ufd_u_id, ufd_up_id, ufd_source, ufd_width, ufd_height, ufd_ipAddress, ufd_dateCreated, ufd_dateAccessed) '
            . 'VALUES(' . $user_id . ', ' . $photo_id . ', ' . $path . ", {$width}, {$height}, {$ip}, NOW(), NOW())";
      $this->dbh->execute($sql);

      return $this->dbh->insert_id() > 0 ? true : false;
    //ufd_id	ufd_up_id	ufd_source	ufd_dateCreated	ufd_dateAccessed
    }
    else
    {
      return false;
    }
  }

  /*******************************************************************************************
  * Description
  *   Remove photos and dynamics from disk
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function remove($user_id = false, $photo_id = false)
  {
    if($user_id !== false && $photo_id !== false)
    {
        $fb =& CFotobox::getInstance();
        $foto = $fb->getDeleted($photo_id, $user_id);
        @unlink($oPath = PATH_FOTOROOT . $foto['P_ORIG_PATH']);
        @unlink($tPath = PATH_FOTOROOT . $foto['P_THUMB_PATH']);
        $this->delete($photo_id);
        $this->removeDynamics($photo_id, $user_id, true);
        return true;
    } 

    return false;
  }

  /*******************************************************************************************
  * Description
  *   Remove dynamic images from database
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function removeDynamics($photo_id = false, $user_id = false, $force = false)
  {
    if($photo_id !== false)
    {
      if($user_id !== false && $force === true)
      {
        include_once PATH_CLASS . '/CFotobox.php';
        $fb =& CFotobox::getInstance();
        $dynamics = $fb->getDynamics($user_id, $photo_id);

        foreach($dynamics as $v)
        {
          @unlink(PATH_FOTOROOT . $v['D_SOURCE']);
        }
      }

      $photo_id = $this->dbh->sql_safe($photo_id);

      $sql  = 'DELETE FROM  user_fotos_dynamic '
            . 'WHERE ufd_up_id = ' . $photo_id;
      if($user_id !== false)
      {
        $user_id  = $this->dbh->sql_safe($user_id);
        $sql .= ' AND ufd_u_id = ' . $user_id;
      }

      $this->dbh->execute($sql);

      return true;
    }
    else
    {
      return false;
    }
  }

  /*******************************************************************************************
  * Description
  *   Delete images (flags images as violation)
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function violation($ids = false, $user_id)
  {
    $user_id_safe = $this->dbh->sql_safe($user_id);
    $continue = false;
    if(is_array($ids))
    {
      $ids_safe= implode(',', $this->dbh->asql_safe($ids));
      $where_u = 'WHERE up_id IN(' . $ids_safe . ') ';
      $where_g = 'WHERE up_id IN(' . $ids_safe . ') ';
      $continue = true;
    }
    else
    if(is_numeric($ids))
    {
      $ids_safe= $this->dbh->sql_safe($ids);
      $where_u = 'WHERE up_id = ' . $ids_safe . ' ';
      $where_g = 'WHERE up_id = ' . $ids_safe . ' ';
      $continue = true;
    }

    if($continue === true)
    {
      $sql_c  = "SELECT SUM(up_size) AS _size FROM user_fotos " . $where_u . " AND up_status = 'active' AND up_u_id = " . $user_id_safe;
      $ar_c   = $this->dbh->query_first($sql_c);

      $sql_a  = 'UPDATE users SET u_spaceUsed = (u_spaceUsed - ' . floor($ar_c['_size']) . ') WHERE u_id = ' . $user_id_safe;

      $sql_u  = 'UPDATE user_fotos '
              . "SET up_status = 'violation', up_modified_at = NOW() "
              . $where_u . ' AND up_u_id = ' . $user_id_safe;

      $this->dbh->execute($sql_a);
      $this->dbh->execute($sql_u);

      if( is_array($ids) )
      {
        foreach( $ids as $p_id )
        {
          $sql_insert = "INSERT INTO user_violations (uv_u_id, uv_up_id, uv_dateCreated) VALUES (" . $user_id_safe . ", " . $p_id . ", NOW() )";
          $this->dbh->execute($sql_insert);
        }
      }
      else
      {
        $sql_insert = "INSERT INTO user_violations (uv_u_id, uv_up_id, uv_dateCreated) VALUES (" . $user_id_safe . ", " . $ids . ", NOW() )";
        $this->dbh->execute($sql_insert);
      }

      return true;
    }
    else
    {
      return false;
    }
  }

  /*******************************************************************************************
  * Description
  *   Restores a quarantined image (marks as active, deletes from quarantine)
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function restore($id = false, $user_id)
  {
    $user_id_safe = $this->dbh->sql_safe($user_id);
    $id_safe= $this->dbh->sql_safe($id);
    $where_u = 'WHERE up_id = ' . $id_safe . ' ';
    $where_g = 'WHERE up_id = ' . $id_safe . ' ';

    $sql_u  = 'UPDATE user_fotos '
            . "SET up_status = 'active', up_modified_at = NOW() "
            . $where_u . ' AND up_u_id = ' . $user_id_safe;
    $this->dbh->execute($sql_u);

    $sql_c  = "SELECT SUM(up_size) AS _size FROM user_fotos " . $where_u . " AND up_status = 'active' AND up_u_id = " . $user_id_safe;
    $ar_c   = $this->dbh->query_first($sql_c);

    $sql_a  = 'UPDATE users SET u_spaceUsed = (u_spaceUsed - ' . floor($ar_c['_size']) . ') WHERE u_id = ' . $user_id_safe;
    $this->dbh->execute($sql_a);

    $sql_delete = "DELETE FROM foto_quarantined WHERE fq_up_id = " . $id_safe;
    $this->dbh->execute($sql_delete);

    $sql_delete = "DELETE FROM foto_objectionable WHERE fo_up_id = " . $id_safe;
    $this->dbh->execute($sql_delete);

    return true;
  }


  /*******************************************************************************************
  * Description
  *   Flag image as objectionable
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function flag($id, $u_id, $session_id)
  {
    $id_safe = $this->dbh->sql_safe($id);
    $u_id_safe = $this->dbh->sql_safe($u_id);
    $session_id_safe = $this->dbh->sql_safe($session_id);

    $sql = "SELECT COUNT(*) as _count FROM foto_objectionable WHERE fo_up_id = " . $id_safe . " AND fo_us_hash = " . $session_id_safe;
    $ar = $this->dbh->query_first($sql);

    if($ar['_count'] == 0)
    {
      $sql_insert = "INSERT INTO foto_objectionable (fo_up_id, fo_us_hash, fo_dateCreated) VALUES (" . $id_safe . ", " . $session_id_safe . ", NOW())";
      $this->dbh->execute($sql_insert);
    }

    $sql = "SELECT COUNT(*) as _count FROM foto_objectionable WHERE fo_up_id = " . $id_safe;
    $ar = $this->dbh->query_first($sql);

    if($ar['_count'] > 2)
    {
      $sql_insert = "INSERT INTO foto_quarantined (fq_up_id, fq_dateCreated) VALUES (" . $id_safe . ", NOW())";
      $this->dbh->execute($sql_insert);
      $this->violation($id, $u_id);

      $sql_delete = "DELETE FROM foto_objectionable WHERE fo_up_id = " . $id_safe;
      $this->dbh->execute($sql_delete);

      include_once PATH_CLASS . '/CMail.php';
      $cm =& CMail::getInstance();

      $to      = 'jaisen@fotoflix.com';
      $subject = 'Foto Quarantined';
      $message = 'This foto has been quarantined.  Foto ID: ' . $id . ', User ID: ' . $u_id;
      $headers = 'From: kevin@fotoflix.com' . "\r\n" .
                 'X-Mailer: PHP/' . phpversion();

      $cm->send( $to, $subject, $message, $headers );
    }
  }

/*******************************************************************************************
  * Description
  *   Method to add tag(s) to foto
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function addTags($foto_ids = false, $tags = false, $user_id = false)
  {
    if($foto_ids !== false || $tags !== false)
    {
      include_once PATH_CLASS . '/CFotobox.php';
      $fb =& CFotobox::getInstance();

      $tags = array_filter((array)$tags, 'tagtrim');
      array_walk($tags, 'tagwalk');

      $user_id_safe = $this->dbh->sql_safe($user_id);

      foreach($foto_ids as $v)
      {
        if(is_numeric($v))
        {
          $foto_id_safe = $this->dbh->sql_safe($v);

          if($user_id !== false)
          {
            $fotoData = $fb->fotoData($v, $user_id);
          }
          else
          {
            $fotoData = $fb->fotoData($v);
          }

          $tagsUnique = $fotoData['P_TAGS'] != '' ? (array)explode(',', $fotoData['P_TAGS']) : array();
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
              $sql = "UPDATE user_fotos SET up_tags = ',{$tagsUnique},' WHERE up_id = {$foto_id_safe} AND up_u_id = {$user_id_safe}";
            }
            else
            {
              $sql = "UPDATE user_fotos SET up_tags = ',{$tagsUnique},' WHERE up_id = {$foto_id_safe}";
            }

            $this->dbh->execute($sql);
          }
        }
      }

      return $tagsUnique;
    }
  }

/*******************************************************************************************
  * Description
  *   Method to delete tag(s) from foto(s)
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function removeTags($foto_ids = false, $tags = false, $user_id = false)
  {
    if($foto_ids !== false || $tags !== false)
    {
      include_once PATH_CLASS . '/CFotobox.php';
      $fb =& CFotobox::getInstance();

      $user_id_safe = $this->dbh->sql_safe($user_id);
      foreach($foto_ids as $v)
      {
        if(is_numeric($v))
        {
          $foto_id_safe = $this->dbh->sql_safe($v);
          $fotoData = $fb->fotoData($v, $user_id);
          $tagsExisting = $fotoData['P_TAGS'] != '' ? (array)explode(',', $fotoData['P_TAGS']) : array();

          $tags = array_filter((array)$tags, 'tagtrim');
          array_walk($tags, 'tagwalk');

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
            $sql = "UPDATE user_fotos SET up_tags = ',{$tagsExisting},' WHERE up_id = {$foto_id_safe} AND up_u_id = {$user_id_safe}";
          }
          else
          {
            $sql = "UPDATE user_fotos SET up_tags = NULL WHERE up_id = {$foto_id_safe} AND up_u_id = {$user_id_safe}";
          }

          $this->dbh->execute($sql);
        }
      }

      return $tagsExisting;
    }
  }

/*******************************************************************************************
  * Description
  *   Method to set privacy by bulk
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function setPrivacyByIds($user_id = false, $foto_ids = false, $privacy = false)
  {
    $return = false;

    if($user_id !== false && is_array($foto_ids))
    {
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CUser.php';      
      include_once PATH_CLASS . '/CUserManage.php';
      $fb =& CFotobox::getInstance();
      $u  =& CUser::getInstance();
      $um =& CUserManage::getInstance();
      
      $user_id_safe = $this->dbh->sql_safe($user_id);
      $foto_ids_safe= $this->dbh->asql_safe($foto_ids);
      $privacy_safe = $this->dbh->sql_safe($privacy);

      $foto_ids_safe= implode(', ', $foto_ids_safe);

      $sql = 'UPDATE user_fotos SET up_privacy = ' . $privacy_safe . ', up_modified_at = NOW() WHERE up_id IN(' . $foto_ids_safe . ') AND up_u_id = ' . $user_id_safe;

      $this->dbh->execute($sql);
      
      if($privacy == PERM_PHOTO_PRIVATE)
      {
        $um->deleteActivity($user_id, $foto_ids, 'newPhoto');
      }
      else
      {
        $userData = $u->find($user_id);
        $photosData = $fb->fotosByIds($foto_ids, $user_id);
        foreach($photosData as $v)
        {
          $um->addActivity($user_id, $v['P_ID'], 'newPhoto', $userData['U_USERNAME'], $v['P_THUMB_PATH'], $v['P_KEY']);
        }
      }
      
      $return = true;
    }

    return $return;
  }

/*******************************************************************************************
  * Description
  *   Method to set copyright by bulk
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function setCopyrightByIds($user_id = false, $foto_ids = false, $copyright = false)
  {
    $return = false;

    if($user_id !== false && is_array($foto_ids))
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $foto_ids= $this->dbh->asql_safe($foto_ids);
      $copyright = $this->dbh->sql_safe($copyright);

      $foto_ids= implode(', ', $foto_ids);

      $sql = 'UPDATE user_fotos SET up_creative_commons = ' . $copyright . ', up_modified_at = NOW() WHERE up_id IN(' . $foto_ids . ') AND up_u_id = ' . $user_id;

      $this->dbh->execute($sql);

      $return = true;
    }

    return $return;
  }

/*******************************************************************************************
  * Description
  *   Method to increment view
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function viewed($foto_key = false, $user_id = false)
  {
    if($foto_key !== false)
    {
      $foto_key = $this->dbh->sql_safe($foto_key);
      $user_id  = $this->dbh->sql_safe($user_id);
      $sql = 'UPDATE user_fotos SET up_views = up_views + 1 WHERE up_key = ' . $foto_key . ' AND up_u_id = ' . $user_id;

      $this->dbh->execute($sql);

      $ip = $this->dbh->sql_safe($_SERVER['REMOTE_ADDR']);
      $sql = 'INSERT INTO report_data (rd_element_key, rd_type, rd_ipAddress, rd_dateCreated) '
           . "VALUES (" . $foto_key . ", 'Photo Viewed', " . $ip . ", NOW())";

      $this->dbh->execute($sql);
    }
  }

  /*******************************************************************************************
  * Description
  *   Method to generate xml files for fotos (currently used for my fotopage)
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function generateFotoXml($user_id = false)
  {
    $return = false;

    if($user_id !== false)
    {
      include_once PATH_CLASS . '/CUser.php';
      $us =& CUser::getInstance();

      $userData = $us->find($user_id);

      $fotosPerFlix = 7;
      $permission = 3;
      $fotos = $this->fb->fotosByPrivacy($user_id, false, false, $fotosPerFlix, $permission, 'P_MOD_YMD');

      $_REQUEST['ids'] = '';
      $_REQUEST['user_id'] = $user_id;
      $_REQUEST['template'] = $_REQUEST['override_template'] = '/home_flix.swf';
      $_REQUEST['autoStart'] = 'N';
      $_REQUEST['title'] = 'Recent Fotos';
      $_REQUEST['flix_length'] = $fotosPerFlix * 2;

      foreach($fotos as $vFoto)
      {
        $_REQUEST['ids'] .= ',' . $vFoto['P_ID'];
      }

      $xml_file = PATH_HOMEROOT . '/fotos/xml/users/' . substr($userData['U_KEY'], -2) . '/' . $userData['U_KEY'] . '.xml';

      $mode = 'file';
      $tmp_file = include(PATH_HOMEROOT . '/dynamicFlixXML.php');

      @rename(PATH_TMPROOT . '/' . $tmp_file, $xml_file); // @ sign in front because this doesn't work on local for some reason

      $return = true;
    }

    return $return;
  }

/*******************************************************************************************
  * Description
  *   Method to update an mp3 entry (only textual data)
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function updateMp3($data = false)
  {
    if(isset($data['um_id']) && isset($data['um_u_id']))
    {
      include_once PATH_INCLUDE . '/functions.php';
      if(isset($data['um_name']))
      {
        $data['um_name'] = sanitize($data['um_name']);
      }

      $data = $this->dbh->asql_safe($data);

      $sql = 'UPDATE user_mp3s SET ';

      foreach($data as $k => $v)
      {
        $sql .= $k .' = ' . $v . ', ';
      }

      $sql  = substr($sql, 0, -2);

      $sql  .=  ' WHERE um_id = ' . $data['um_id'] . ' AND um_u_id = ' . $data['um_u_id'];

      $this->dbh->execute($sql);

      return true;
    }
    else
    {
      return false;
    }
  }

/*******************************************************************************************
  * Description
  *   Method to delete an mp3
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function deleteMp3($mp3_id = false, $user_id)
  {
    if($mp3_id !== false && $user_id !== false)
    {
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_CLASS . '/CFlixManage.php';

      $us =& CUser::getInstance();
      $usm=& CUserManage::getInstance();
      $flm=& CFlixManage::getInstance();

      $mp3_data = $this->fb->mp3($mp3_id, $user_id);
      $user_data= $us->find($user_id);

      $mp3_src  = PATH_FOTOROOT . $mp3_data['M_PATH'];

      $usm->update(
              array(
                'u_id' => $user_id,
                'u_spaceUsed' => intval($user_data['U_SPACEUSED'] - $mp3_data['M_SIZE'])
              )
            );

      $flm->clearMp3($mp3_data['M_PATH']);

      $mp3_id = $this->dbh->sql_safe($mp3_id);

      //$this->dbh->query('DELETE FROM user_mp3s WHERE um_id = ' . $mp3_id);
      $this->dbh->query("UPDATE user_mp3s SET um_status = 'Deleted' WHERE um_id = {$mp3_id}");

      if(file_exists($mp3_src))
      {
        unlink($mp3_src);
      }

      return true;
    }

    return false;
  }

/*******************************************************************************************
  * Description
  *   Method to upload mp3 (only handles the copy from temp to dir)
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function uploadMp3($src = false, $dest = false, $data = false)
  {
    if($src !== false && $dest !== false && $data !== false)
    {
      if(is_file($src)) // can't use is_uploaded_file when using megauploader
      {
        $copy_status = copy($src, $dest);

        if($copy_status === true)
        {
          include_once PATH_CLASS . '/CMp3.php';
          include_once PATH_CLASS . '/CUser.php';
          include_once PATH_CLASS . '/CUserManage.php';

          include_once PATH_INCLUDE . '/functions.php';
          if(isset($data['um_name']))
          {
            $data['um_name'] = sanitize($data['um_name']);
          }

          $m  =& new CMp3;
          $u  =& CUser::getInstance();
          $um  =& CUserManage::getInstance();

          $m->load($dest);
          $length = $m->length();

          $user_data = $u->find($data['um_u_id']);
          $um->update(
                    array(
                      'u_id' => $data['um_u_id'],
                      'u_spaceUsed' => intval($data['um_size'] + $user_data['U_SPACEUSED'])
                    )
                  );

          $data['um_length'] = $length;
          $data = $this->dbh->asql_safe($data);
          $keys = array_keys($data);

          $sql  = 'INSERT INTO user_mp3s( ' . implode(',', $keys) . ', um_created_at ) '
                . 'VALUES( ' . implode(',', $data) . ', NOW() )';

          $this->dbh->execute($sql);
          unlink($src);

          return true;
        }
      }
    }

    return false;
  }

  /*******************************************************************************************
  * Description
  *   Method to upload base image
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function uploadBase($src = false, $name)
  {
    return $this->_upload($src, PATH_FOTOROOT . "/base/{$this->stamp}/{$name}", FF_BASE_WIDTH, FF_BASE_HEIGHT, 'base');
  }
  
/*******************************************************************************************
  * Description
  *   Method to upload original image
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function uploadOriginal($src = false, $name)
  {
    return $this->_upload($src, PATH_FOTOROOT . "/original/{$this->stamp}/{$name}", false, false, 'orig');
  }

  /*******************************************************************************************
  * Description
  *   Method to upload thumbnail image
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function uploadThumbnail($src = false, $name)
  {
    return $this->_upload($src, PATH_FOTOROOT . "/thumbnail/{$this->stamp}/{$name}", FF_THUMB_WIDTH, FF_THUMB_HEIGHT, 'thumb');
  }

  /*******************************************************************************************
  * Description
  *   Private Method to upload images
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function _upload($src = false, $dest = false, $width = false, $height = false, $type = false)
  {
    include_once PATH_CLASS . '/CImageMagick.php';
    $this->im   =& CImageMagick::getInstance();

    $dir  = dirname($dest);
    $base = $this->fb->safename(basename($dest));
    $ext = substr($base, strrpos($base, '.'));

    if(stristr($ext, '.jpg') === false && stristr($ext, '.jpeg') === false)
    {
      $base_convert = preg_replace('/\.\w*?$/', '.jpg', $base);
    }

    copy($src, $dest);

    if(isset($base_convert))
    {
      $this->im->convert($dest, $dir . '/' . $base_convert);
      unlink($dest);
      $dest = $dir . '/' . $base_convert;
    }

    if($width !== false && $height !== false)
    {
      if($type == 'thumb')
      {
        $this->im->square($dest);
      }
      
      $this->im->image($dest);
      $this->im->scale($width, $height);
    }

    return str_replace(PATH_FOTOROOT, '', $dest);
  }
  
 /*
  *******************************************************************************************
  * Name
  *   setStamp
  * Description
  *   Method to set the class timestamp variable
  ******************************************************************************************
  */
  function setStamp($stamp = false)
  {
    if($stamp !== false)
    {
      $this->stamp = (int)$stamp;
    }
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
  static function & getInstance($stamp = 0)
  {
    static $inst = null;
    $class = __CLASS__;

    if($inst === null || $stamp > 0)
    {
      include_once PATH_CLASS . '/CFotobox.php';
      $inst       = new $class;
      $inst->dbh  =&$GLOBALS['dbh'];
      $inst->stamp= $stamp > 0 ? $stamp : FF_YM_STAMP;
      $inst->fb   =& CFotobox::getInstance();
    }

    return $inst;
  }

  /*******************************************************************************************
  * Description
  *   Constructor
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function CFotoboxManage($stamp = 0)
  {
  }
}
?>

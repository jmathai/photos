<?php
 /*
  *******************************************************************************************
  * Name:  CGroupManage.php
  *
  * General class for group interaction.
  * This class performs write functions on the database.
  *
  * Usage:
  *   include_once('CGroupManage.php');
  *   $group = new CGroupManage;
  *   $group->create($data);
  *
  ******************************************************************************************
  */
class CGroupManage
{
 /*
  *******************************************************************************************
  * Name
  *   add
  * Description
  *   Method to add a group
  *
  * Input
  *   array
  * Output
  *   boolean
  ******************************************************************************************
  */
  function add($data = false)
  {
    if(is_array($data))
    {
      include_once PATH_INCLUDE . '/functions.php';
      $data['g_name'] = sanitize($data['g_name']);
      $data['g_description'] = sanitize($data['g_description']);      
      
      $data = $this->dbh->asql_safe($data);
      $keys = array_keys($data);
      
      $sql  = 'INSERT INTO groups(' . implode(", ", $keys) . ', g_dateModified, g_dateCreated) ' . LF
            . 'VALUES(' . implode(", ", $data) . ', NOW(), NOW())';

      $this->dbh->execute($sql);

      return $this->dbh->insert_id();
    }
    else
    {
      return false;
    }
  }

 /*
  *******************************************************************************************
  * Name
  *   update
  * Description
  *   Method to update a group
  *
  * Input
  *   array
  * Output
  *   boolean
  ******************************************************************************************
  */
  function update($data = false)
  {
    if(is_array($data))
    {
      include_once PATH_INCLUDE . '/functions.php';
      if(isset($data['g_name']))
      {
        $data['g_name'] = sanitize($data['g_name']);
      }
      
      if(isset($data['g_description']))
      {
        $data['g_description'] = sanitize($data['g_description']);      
      }
      
      $group_id = $data['g_id'];
      $data = $this->dbh->asql_safe($data);

      $sql = 'UPDATE groups SET ' . LF;

      foreach($data as $k => $v)
      {
        $sql .= $k . ' = ' . $v . ', ' . LF;
      }

      $sql  .=  'g_dateModified = Now() ' . LF
            .   'WHERE g_id = ' . $data['g_id'];

      $this->dbh->execute($sql);

      return $group_id;
    }
    else
    {
      return false;
    }
  }

 /*
  *******************************************************************************************
  * Name
  *   delete
  * Description
  *   Method to mark a group for deletion
  *
  * Input
  *   int
  * Output
  *   boolean
  ******************************************************************************************
  */
  function delete($group_id = false, $user_id = false)
  {
    if($group_id !== false && $user_id !== false)
    {
      include_once PATH_CLASS . '/CGroup.php';
      $g =& CGroup::getInstance();
      $group_data = $g->groupData($group_id);
      $perms = $g->isOwner($group_id, $user_id, $group_data);

      if($perms === true)
      {
        include_once PATH_CLASS . '/CMail.php';
        $m =& CMail::getInstance();

        $expiry   = NOW + 86400;

        $safe_group_id = $this->dbh->sql_safe($group_id);
        $safe_user_id  = $this->dbh->sql_safe($user_id);
        $safe_expiry  = $this->dbh->sql_safe(date('Y-m-d', $expiry));
        $sql = 'REPLACE INTO group_delete(g_id, u_id, gd_dateToDelete) VALUES(' . $safe_group_id . ', ' . $safe_user_id . ', ' . $safe_expiry . ')';


        $this->dbh->execute($sql);

        $this->update(array('g_id' => $group_id, 'g_delete' => 1));

        $group_members = $g->members($group_id);
        $body = file_get_contents(PATH_DOCROOT . '/group_delete.tpl.php');
        $body = str_replace(
                  array('{DATE}', '{GROUP}'),
                  array(date(FF_FORMAT_DATE_LONG, $expiry), $group_data['G_NAME']),
                  $body
                );

        $mail_headers   = "MIME-Version: 1.0\n"
                        . "Content-type: text/plain; charset=iso-8859-1\n"
                        . "Return-Path: " . FF_EMAIL_FROM . "\n"
                        . "From: " . FF_EMAIL_FROM_FORMATTED . "\n";

        foreach($group_members as $v)
        {
          $this_body = str_replace('{NAME}', $v['U_NAMEFIRST'], $body);
          $email_formatted = $v['U_NAMEFIRST'] . ' ' . $v['U_NAMELAST'] . '<' . $v['U_EMAIL'] . '>';
          $m->send(
                    $email_formatted,
                    'FotoGroup ' . $group_data['G_NAME'] . ' to be deleted!',
                    $this_body,
                    $mail_headers,
                    '-f' . FF_EMAIL_FROM
                   );
        }

        return true;
      }
    }

    return false;
  }

 /*
  *******************************************************************************************
  * Name
  *   deleteCommit
  * Description
  *   Method to execute group deletion
  *
  * Input
  *   unix timestamp
  *
  * Output
  *   boolean
  ******************************************************************************************
  */
  function deleteCommit($timestamp = false, $verbose = false)
  {
    if($timestamp !== false)
    {
      $expiry_safe = $this->dbh->sql_safe(date('Y-m-d', $timestamp));

      $sql = 'SELECT g_id AS G_ID, u_id AS U_ID FROM group_delete WHERE gd_dateToDelete = ' . $expiry_safe;

      if($verbose === true)
      {
        echo 'Executing query... ' . $sql . "\n";
      }

      $groups = $this->dbh->query_all($sql);

      $ids = array(0);

      foreach($groups as $v)
      {
        $ids[] = $this->dbh->sql_safe($v['G_ID']);
      }

      $ids = '(' . implode(',', $ids) . ')';

      $sql_array[] = 'DELETE FROM user_group_map WHERE g_id IN ' .$ids;
      $sql_array[] = "UPDATE groups SET g_status = 'Disabled' WHERE g_id IN {$ids}";
      $sql_array[] = 'DELETE FROM group_invite WHERE gi_g_id IN ' . $ids;
      $sql_array[] = "UPDATE group_fotos SET gp_status = 'deleted' WHERE gp_g_id IN {$ids}";
      $sql_array[] = 'DELETE FROM group_fotoflix_map WHERE g_id IN ' . $ids;
      $sql_array[] = 'DELETE FROM group_delete WHERE g_id IN ' . $ids;
      // $sql_array[] = 'DELETE FROM forums WHERE f_g_id IN ' . $ids;
      // $sql_array[] = 'DELETE FROM forum_threads WHERE ft_g_id IN ' . $ids;
      // $sql_array[] = 'DELETE FROM forum_posts WHERE fp_g_id IN ' . $ids;

      foreach($sql_array as $sql_commit)
      {
        $this->dbh->execute($sql_commit);

        if($verbose === true)
        {
          echo 'Executing query... ' . $sql_commit . "\n";
          echo 'Affected rows... ' . $this->dbh->affected_rows() . "\n";
        }
      }
    }
  }

 /*
  *******************************************************************************************
  * Name
  *   shareFotos
  * Description
  *   Method to share fotos with a group
  *
  * Input
  *   $user_id      Id of user making request (security)
  *   $group_id     Id of group to share with
  *   $array_fotos  Array of foto ids to share
  * Output
  *   boolean / array
  ******************************************************************************************
  */
  function shareFotos($user_id = false, $group_id = false, $array_fotos = false)
  {
    $retval = false;
    if($user_id !== false && $group_id !== false && $array_fotos !== false)
    {
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CFotoboxManage.php';
      
      $g  =& CGroup::getInstance();
      $fbm=& CFotoboxManage::getInstance();
      
      $user_id  = intval($user_id);
      $group_id = intval($group_id);
      
      $group_data = $g->groupData($group_id);
      
      $doCopy = $user_id != $group_data['G_U_ID']; // if the user is group owner then don't duplicate the photo in their account
      
      if($group_data !== false)
      {
        $retval = array();
        $sql = 'REPLACE INTO group_foto_map(up_id, g_id, u_id, u_orig_id, up_orig_id, gfm_status, dateCreated) VALUES';
        $continue = false;
        
        if(!is_array($array_fotos))
        {
          $array_fotos = array(intval($array_fotos));
        }
        
        foreach($array_fotos as $v)
        {
          $v = intval($v);
          
          if($doCopy) // if user != group owner then copy photo into group owner's account
          {
            $isAdded = $fbm->copy($v, $group_data['G_U_ID'], true); // copy foto $v to group owner's account and then share that foto
          }
          else // if user = group owner then don't copy the photo
          {
            $isAdded = $v;
          }
          
          if($isAdded !== false)
          {
            $sql .= "({$isAdded}, {$group_id}, {$group_data['G_U_ID']}, {$user_id}, {$v}, 'Pending', NOW()),";
            $retval[] = $isAdded;
            $continue = true;
          }
        }
        
        if($continue === true)
        {
          $sql = substr($sql, 0, -1);
          $this->dbh->execute($sql);
        }
      }
    }
    
    return $retval;
  }
  
  /*
  *******************************************************************************************
  * Name
  *   approvePhoto
  * Description
  *   Method to share fotos with a group
  *
  * Input
  *   $group_id     Id of group to share with
  *   $photo_id     Id of the photo
  * Output
  *   boolean / array
  ******************************************************************************************
  */
  function approvePhoto($group_id = false, $photo_id = false, $photo_orig_id = false, $u_orig_id = false)
  {
    if($group_id !== false && $photo_id !== false && $photo_orig_id !== false && $u_orig_id !== false)
    {
      $sql = 'UPDATE group_foto_map '
           . "SET gfm_status = 'Active' "
           . 'WHERE up_id = ' . intval($photo_id) . ' '
           . 'AND g_id = ' . intval($group_id) . ' ';
      
      $this->dbh->execute($sql);
      
      //$sql = 'UPDATE user_fotos '
           //. "SET up_status = 'active' "
           //. 'WHERE up_id = ' . intval($photo_id) . ' ';
           
      $this->dbh->execute($sql);
      
      $sql = 'INSERT INTO group_feed(gf_g_id, gf_u_id, gf_type, gf_type_id, gf_dateCreated, gf_date_id) '
           . "VALUES (" . $group_id . ", " . $u_orig_id . ", 'Photo_add', " . $photo_id . ", NOW(), '" . date('ymd') . "') ";
           
      $this->dbh->execute($sql);
    }
  }
  
  /*
  *******************************************************************************************
  * Name
  *   rejectPhoto
  * Description
  *   Method to share fotos with a group
  *
  * Input
  *   $group_id     Id of group to share with
  *   $photo_id     Id of the photo
  * Output
  *   boolean / array
  ******************************************************************************************
  */
  function rejectPhoto($group_id = false, $photo_id = false, $photo_orig_id = false, $u_orig_id = false)
  {
    if($group_id !== false && $photo_id !== false && $photo_orig_id !== false && $u_orig_id !== false)
    {
      $sql = 'UPDATE group_foto_map '
           . "SET gfm_status = 'Rejected' "
           . 'WHERE up_id = ' . intval($photo_id) . ' '
           . 'AND g_id = ' . intval($group_id) . ' ';
      
      $this->dbh->execute($sql);
      
      $sql = 'UPDATE user_fotos '
           . "SET up_status = 'deleted' "
           . 'WHERE up_id = ' . intval($photo_id) . ' ';
           
      $this->dbh->execute($sql);
    }
  }
  
  /*
  *******************************************************************************************
  * Name
  *   approveslideshow
  * Description
  *   Method to share slideshows with a group
  *
  * Input
  *   $group_id     Id of group to share with
  *   $slideshow_id     Id of the slideshow
  * Output
  *   boolean / array
  ******************************************************************************************
  */
  function approveslideshow($group_id = false, $slideshow_id = false, $slideshow_orig_id = false, $u_orig_id = false)
  {
    if($group_id !== false && $slideshow_id !== false && $slideshow_orig_id !== false && $u_orig_id !== false)
    {
      $sql = 'UPDATE group_fotoflix_map '
           . "SET gfm_status = 'Active' "
           . 'WHERE uf_id = ' . intval($slideshow_id) . ' '
           . 'AND g_id = ' . intval($group_id) . ' ';
      
      $this->dbh->execute($sql);
      
      $sql = 'UPDATE user_slideshows '
           . "SET us_status = 'Active', us_privacy = " . PERM_SLIDESHOW_PUBLIC . " "
           . 'WHERE us_id = ' . intval($slideshow_orig_id) . ' ';
           
      $this->dbh->execute($sql);
      
      $sql = 'INSERT INTO group_feed(gf_g_id, gf_u_id, gf_type, gf_type_id, gf_dateCreated, gf_date_id) '
           . "VALUES (" . $group_id . ", " . $u_orig_id . ", 'Slideshow_add', " . $slideshow_id . ", NOW(), '" . date('ymd') . "') ";
           
      $this->dbh->execute($sql);
    }
  }
  
  /*
  *******************************************************************************************
  * Name
  *   rejectslideshow
  * Description
  *   Method to share fotos with a group
  *
  * Input
  *   $group_id     Id of group to share with
  *   $slideshow_id     Id of the slideshow
  * Output
  *   boolean / array
  ******************************************************************************************
  */
  function rejectslideshow($group_id = false, $slideshow_id = false, $slideshow_orig_id = false)
  {
    if($group_id !== false && $slideshow_id !== false && $slideshow_orig_id !== false)
    {
      $sql = 'UPDATE group_fotoflix_map '
           . "SET gfm_status = 'Rejected' "
           . 'WHERE uf_id = ' . intval($slideshow_id) . ' '
           . 'AND g_id = ' . intval($group_id) . ' ';
      
      $this->dbh->execute($sql);
      
      $sql = 'UPDATE user_slideshows '
           . "SET us_status = 'Deleted' "
           . 'WHERE us_id = ' . intval($slideshow_orig_id) . ' ';
           
      $this->dbh->execute($sql);
    }
  }
  
 /*
  *******************************************************************************************
  * Name
  *   unshareFotos
  * Description
  *   Method to unshare flix with a group
  *
  * Input
  *   int
  *   array
  *   int
  * Output
  *   boolean
  ******************************************************************************************
  */
  function unshareFotos($group_id = false, $user_id = false, $array_fotos = false)
  {
    if($group_id !== false && $array_fotos !== false)
    {
      $safe_fotos_ids = implode(',', $this->dbh->asql_safe($array_fotos));
      $safe_group_id = $this->dbh->sql_safe($group_id);
      $safe_user_id  = $this->dbh->sql_safe($user_id);

      $sql = 'DELETE FROM group_foto_map WHERE g_id = ' . $safe_group_id . ' AND u_id = ' . $safe_user_id . ' AND up_id IN(' . $safe_fotos_ids . ')';

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
  *   shareFlix
  * Description
  *   Method to share flix with a group
  *
  * Input
  *   int
  *   array
  *   int
  * Output
  *   boolean
  ******************************************************************************************
  */
  function shareFlix($group_id = false, $array_flix = false, $user_id = false, $share_fotos = false)
  {
    if($group_id !== false && $array_flix !== false && $user_id !== false)
    {
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CFlixManage.php';
      include_once PATH_CLASS . '/CGroup.php';
      $fl =& CFlix::getInstance();
      $flm = &CFlixManage::getInstance();
      $g = &CGroup::getInstance();

      $safe_group_id = $this->dbh->sql_safe($group_id);
      $safe_user_id  = $this->dbh->sql_safe($user_id);
      
      foreach($array_flix as $v)
      {
        $flix_data = $fl->search(array('FLIX_ID' => $v, 'USER_ID' => $user_id));
        
        if($share_fotos === true)
        {
          $photo_array = (array)explode(',', $flix_data['US_PHOTO']['photoId_int']);
          $this->shareFotos($user_id, $group_id, $photo_array);
        }
        
        $group_data = $g->groupData($group_id, $user_id);
        $flix_id = $this->dbh->sql_safe($v);
        
        if($user_id != $group_data['G_U_ID'])
        {
          $new_flix_id = $flm->duplicate($v, $group_data['G_U_ID']);
        }
        else 
        {
          $new_flix_id = $flix_id;
        }
        
        $sql = 'REPLACE INTO group_fotoflix_map(g_id, u_id, uf_id, u_orig_id, uf_orig_id, gfm_status, dateModified) VALUES(' . $safe_group_id . ', ' . $group_data['G_U_ID'] . ', ' . $new_flix_id . ', ' . $safe_user_id . ', ' . $flix_id . ', \'Pending\', NOW())';
        $this->dbh->execute($sql);
      }

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
  *   unshareFlix
  * Description
  *   Method to unshare flix with a group
  *
  * Input
  *   int
  *   array
  *   int
  * Output
  *   boolean
  ******************************************************************************************
  */
  function unshareFlix($group_id = false, $array_flix = false, $user_id = false)
  {
    if($group_id !== false && $array_flix !== false && $user_id !== false)
    {
      $safe_flix_ids = implode(',', $this->dbh->asql_safe($array_flix));
      $safe_group_id = $this->dbh->sql_safe($group_id);
      $safe_user_id  = $this->dbh->sql_safe($user_id);
      
      $sql = 'DELETE FROM group_fotoflix_map WHERE g_id = ' . $safe_group_id . ' AND u_id = ' . $safe_user_id . ' AND uf_id IN(' . $safe_flix_ids . ')';
      
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
  *   join
  * Description
  *   Method for a user to join a group.
  *
  * Input
  *   int
  *   int
  *   str
  * Output
  *   boolean
  ******************************************************************************************
  */
  function join($user_id = false, $group_id = false, $reference_id = false)
  {
    if($user_id !== false && $group_id !== false)
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $group_id = $this->dbh->sql_safe($group_id);
      $rsCheck = $this->dbh->query('SELECT u_id FROM users WHERE u_id = ' . $user_id); /* do not require status check for immediate viewing */

      if($this->dbh->num_rows($rsCheck) == 1)
      {
        $this->dbh->execute($sql = 'REPLACE INTO user_group_map SET u_id = ' . $user_id . ', g_id = ' . $group_id . ', dateCreated = NOW()');
        if($reference_id !== false)
        {
          $reference_id = $this->dbh->sql_safe($reference_id);
          $this->dbh->execute('DELETE FROM group_invite WHERE gi_reference = ' . $reference_id);
        }

        $sql = 'INSERT INTO group_feed(gf_g_id, gf_u_id, gf_type, gf_type_id, gf_dateCreated, gf_date_id) '
           . "VALUES (" . $group_id . ", " . $user_id . ", 'Group_join', " . $user_id . ", NOW(), '" . date('ymd') . "') ";
           
        $this->dbh->execute($sql);
        
        return true;
      }
      else
      {
        array_push($this->error, 'User is not active and could not be added to group.');
        return false;
      }
    }
  }

 /*
  *******************************************************************************************
  * Name
  *   leave
  * Description
  *   Method for a user to leave a group.
  *
  * Input
  *   string
  * Output
  *   mixed
  ******************************************************************************************
  */
  function leave($user_id = false, $group_id = false)
  {
    if($user_id !== false && $group_id !== false)
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $group_id = $this->dbh->sql_safe($group_id);
      $this->dbh->execute('DELETE FROM user_group_map WHERE u_id = ' . $user_id . ' AND g_id = ' . $group_id);
      return true;
    }
    else
    {
      array_push($this->error, 'Group Id and User Id were not both specified.');
      return false;
    }
  }

 /*
  *******************************************************************************************
  * Name
  *   invite
  * Description
  *   Method to log an invitation to a group.
  *
  * Input
  *   array
  * Output
  *
  ******************************************************************************************
  */
  function invite($data = false)
  {
    if($data !== false)
    {
      $data = $this->dbh->asql_safe($data);
      $keys = array_keys($data);

      $sql  = 'INSERT INTO group_invite(' . implode(',', $keys) . ', gi_dateResponded) '
            . 'VALUES(' . implode(',', $data) . ', NOW())';

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
  *   inviteResponse
  * Description
  *   Method to log an invitation to a group.
  *
  * Input
  *   array
  * Output
  *
  ******************************************************************************************
  */
  function inviteResponse($group_id, $user_id, $key, $status)
  {
    $group_id = $this->dbh->sql_safe($group_id);
    $user_id = $this->dbh->sql_safe($user_id);
    $key = $this->dbh->sql_safe($key);
    $status = $this->dbh->sql_safe($status);

    $sql  = 'UPDATE group_invite '
          . 'SET gi_dateResponded = NOW(), '
          . 'gi_status = ' . $status . ' '
          . 'WHERE gi_g_id = ' . $group_id . ' '
          . 'AND gi_u_id = ' . $user_id . ' '
          . 'AND gi_reference = ' . $key . ' ';

    $this->dbh->execute($sql);
  }
  
  /*
  *******************************************************************************************
  * Name
  *   removeMember
  * Description
  *   Method to remove a member from a group
  *
  * Output
  *   array
  ******************************************************************************************
  */
  function removeMember($group_id, $who)
  {
    $group_id = $this->dbh->sql_safe($group_id);
    $who = $this->dbh->sql_safe($who);
    
    $sql = 'DELETE FROM user_group_map '
         . 'WHERE u_id = ' . $who . ' '
         . 'AND g_id = ' . $group_id;
         
    $this->dbh->execute($sql);
    
    $sql = 'DELETE FROM group_invite '
         . 'WHERE gi_u_id = ' . $who . ' '
         . 'AND gi_g_id = ' . $group_id;
         
    $this->dbh->execute($sql);
  }
  
  /*
  *******************************************************************************************
  * Description
  *   Set a group preference
  *
  * Input
  *   $group_id    int
  *   $key        string
  * Output
  *   bool
  ******************************************************************************************
  */
  function setPrefs($group_id = false, $prefs = false)
  {
    if($group_id !== false && is_array($prefs))
    {
      $group_id = $this->dbh->sql_safe($group_id);
      $prefs = $this->dbh->asql_safe($prefs);      
      $sql = 'REPLACE INTO group_prefs(gp_g_id, gp_name, gp_value) VALUES ';
      
      $continue = false;
      foreach($prefs as $k => $v)
      {
        $k = $this->dbh->sql_safe($k); // do not use sql_safe because we don't want single quotes around this
        $sql .= "({$group_id}, {$k}, {$v}), "; // $v is already safe'd using asql_safe above
        $continue = true;
      }
      
      if($continue === true)
      {
        $sql = substr($sql, 0, -2);
        $this->dbh->execute($sql);
      }
    }
  }
  
 /*
  *******************************************************************************************
  * Description
  *   Remove a group preference
  *
  * Input
  *   $group_id    int
  *   $key        string
  * Output
  *   bool
  ******************************************************************************************
  */
  function removePrefs($group_id = false, $prefs = false)
  {
    if($group_id !== false && is_array($prefs))
    {
      $group_id = $this->dbh->sql_safe($group_id);
      $prefs = $this->dbh->asql_safe($prefs);      
      foreach($prefs as $k => $v)
      {
        $sql = 'DELETE FROM group_prefs WHERE gp_g_id = ' . $group_id . ' AND gp_name = ' . $this->dbh->sql_safe($k);
        $this->dbh->execute($sql);
      }
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
  static function & getInstance()
  {
    static $inst = null;
    $class = __CLASS__;

    if($inst === null)
    {
      $inst      = new $class;
      $inst->dbh =& $GLOBALS['dbh'];
    }

    return $inst;
  }

 /*
  *******************************************************************************************
  * Name
  *   CGroupManage
  * Description
  *   Constructor
  *
  * Input
  *   None
  * Output
  *   Boolean
  ******************************************************************************************
  */
  function CGroupManage()
  {
  }
}
?>
<?php
 /*
  *******************************************************************************************
  * Name:  CVideo.php
  *
  * Class to handle videos.
  * This class performs read and write functions on the database.
  *
  * Usage:
  *   include_once('CVideo.php');
  *   $vid = CVideo::getInstance();
  *   $vidData = $vid->find($videoId);
  * 
  ******************************************************************************************
  */
class CVideo
{
  function find($identifier)
  {
    $continue = $retval = false;
    $params = array();
    if(strlen($identifier) == 32)
    {
      $params['KEY'] = $identifier;
      $continue = true;
    }
    else
    {
      $params['ID']  = $identifier;
      $continue = true;
    }
    
    if($continue === true)
    {
      if(func_num_args() > 1)
      {
        $params['USER_ID'] = func_get_arg(1);
      }
      
      $result = $this->search($params);
      
      if(count($result) > 0)
      {
        $retval = $result[0];
      }     
    }

    return $retval;
  }
  
  function search($params)
  {
    /*$apcKey = 'ptg.3rdparty.video';
    $thirdParty = apc_fetch($apcKey);
    if(!$thirdParty)
    {
      $ch = curl_init('http://vimeo.com/api/v2/jmathai/videos.json');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $resp = curl_exec($ch);
      try
      {
        $thirdParty = json_decode($resp, true);
        apc_store($apcKey, $thirdParty);
      }
      catch(Exception $e) {}
    }*/
    
    
    $sql  = 'SELECT v_id AS V_ID, v_u_id AS V_U_ID, v_key AS V_KEY, v_name AS V_NAME, v_description AS V_DESCRIPTION, '
          . 'v_tags AS V_TAGS, v_path AS V_PATH, v_screen75x75 AS V_SCREEN_75x75, v_screen115x50 AS V_SCREEN_115x50, v_screen150x100 AS V_SCREEN_150x100, '
          . 'v_screen400x300 AS V_SCREEN_400x300, v_length AS V_LENGTH, v_views AS V_VIEWS, v_privacy AS V_PRIVACY, v_dateCreated AS V_DATECREATED '
          . 'FROM user_videos '
          . 'WHERE 1 ';
    
    if(array_key_exists('ID', $params))
    {
      $sql .= ' AND v_id = ' . $this->dbh->sql_safe($params['ID']) . ' ';
      $doSingle = true;
    }
    
    if(array_key_exists('KEY', $params))
    {
      $sql .= ' AND v_key = ' . $this->dbh->sql_safe($params['KEY']) . ' ';
      $doSingle = true;
    }
          
    if(array_key_exists('USER_ID', $params))
    {
      $sql .= ' AND v_u_id = ' . $this->dbh->sql_safe($params['USER_ID']) . ' ';
    }
    
    $sql .= " AND v_status = 'active' ";
    
    if(array_key_exists('TAGS', $params) && $params['TAGS'] != '')
    {
      if(!is_array($params['TAGS']))
      {
        $params['TAGS'] = (array)explode(',', $params['TAGS']);
      }
      
      if(count($params['TAGS']) > 0)
      {
        array_walk($params['TAGS'], 'tagwalk');
        $where .= 'AND MATCH(v_tags) AGAINST(\'+"' . implode('," +"', $params['TAGS']) . ',"\' IN BOOLEAN MODE) ';
      }
    }
    
    if(array_key_exists('PERMISSION', $params))
    {
      $mask = intval($params['PERMISSION']);
      
      if($mask == PERM_VIDEO_PRIVATE)
      {
        $where .= 'AND v_privacy = ' . $mask . ' ';
      }
      else
      {
        $where .= 'AND v_privacy & ' . $mask . ' = ' . $mask . ' ';
      }
    }
    
    switch($params['ORDER'])
    {
      case 'CREATED':
        $sql .= ' ORDER BY v_dateCreated DESC ';
        break;
      default:
        $sql .= ' ORDER BY v_id DESC ';
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
    
    $retval = $this->dbh->query_all($sql);
    
    return $retval;
  }
  
  function add($data)
  {
    $retval = 0;
    if(is_array($data))
    {
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CUserManage.php';
      
      $u  =& CUser::getInstance();
      $um =& CUserManage::getInstance();
      
      $key    = $data['v_key'];
      $userId = intval($data['uv_u_id']);
      $thumb1 = $data['v_screen115x50'];
      $thumb2 = $data['v_screen150x100'];
      
      if(!empty($data['uv_tags']))
      {
        $data['uv_tags'] = array_filter((array)$data['uv_tags'], 'tagtrim');
        array_walk($data['uv_tags'], 'tagwalk');
      }
      
      $dataSafe = $this->dbh->asql_safe($data);
      $sql = 'INSERT INTO user_videos(' . implode(',', array_keys($dataSafe)) . ', v_dateCreated) '
           . 'VALUES(' . implode(',', $dataSafe) . ', NOW())';
      
      $this->dbh->execute($sql);
      
      $retval = $this->dbh->insert_id();
      
      $userData = $u->find($userId);
      // add activity
      $um->addActivity($data['uv_u_id'], $retval, 'newVideo', $userData['U_USENAME'], $thumb1, $key);
    }
    
    return $retval;
  }
  
  function update($params = false)
  {
    if(isset($params['v_id']))
    {
      $paramsSafe = $this->dbh->asql_safe($params);
      $sql = 'UPDATE user_videos SET ';
      
      foreach($paramsSafe as $k => $v)
      {
        if($k != 'v_id')
        {
          $sql .= $k . ' = ' . $v . ',';
        }
      }
      
      $sql = substr($sql, 0, -1);
      $sql .= ' WHERE v_id = ' . $paramsSafe['v_id'];
      
      $this->dbh->execute($sql);
      
      return true;
    }
    
    return false;
  }
  
  function save($flv, $name)
  {
    $retval = '';
    if(file_exists($flv))
    {
      $name = substr($name, 0, strrpos($name, '.'));
      $safeName = NOW . '_' . preg_replace('/\W/', '_', $name) . '.flv';
      $safePath = '/' . FF_YM_STAMP . '/' . $safeName;
      rename($flv, PATH_VIDEOROOT . $safePath);
      
      $retval = $safePath;
    }
    
    return $retval;
  }
  
  function prepare($video)
  {
    $retval = '';
    if(is_file($video))
    {
      $output = dirname($video) . '/' . basename($video) . '.flv';
      //exec($ffmpeg = 'ffmpeg -i ' . $video . ' -s ' . FF_VIDEO_SIZE . ' -ar ' . FF_AUDIO_FREQUENCY . ' -ab ' . FF_AUDIO_BITRATE . ' -aspect ' . FF_VIDEO_ASPECT . ' ' . $output);
      exec($ffmpeg = 'ffmpeg -i ' . $video . ' -s ' . FF_VIDEO_SIZE . ' -b ' . FF_VIDEO_BITRATE . ' -ar ' . FF_AUDIO_FREQUENCY . ' -ab ' . FF_AUDIO_BITRATE . ' ' . $output);
      exec($flvtool2 = 'flvtool2 -U ' . $output);
      //echo '<br/>ffmpeg<br/>' . $ffmpeg . '<br/><br/>';
      //echo '<br/>flvtool2<br/>' . $flvtool2 . '<br/><br/>';
      //exec('rm -f ' . $video);
      
      $retval = $output;
    }
    
    return $retval;
  }
  
  function screenshots($video, $name)
  {
    $retval = false;
    
    if(is_file($video))
    {
      include_once PATH_CLASS . '/CImageMagick.php';
      $im =& CImageMagick::getInstance();
      
      $name = substr($name, 0, strrpos($name, '.'));
      
      $safeNameM = NOW . '_' . preg_replace('/\W/', '_', $name) . '_400x300.jpg';
      $safePathM = PATH_FOTOROOT . '/thumbnail/' . FF_YM_STAMP . '/' . $safeNameM;
      exec($ffmpeg = 'ffmpeg -i ' . $video . ' -s 400x300 -vframes 10 -f image2 ' . $safePathM);
      
      $im->image($safePathM);
      
      $safeNameT1 = NOW . '_' . preg_replace('/\W/', '_', $name) . '_75x75.jpg';
      $safeNameT2 = NOW . '_' . preg_replace('/\W/', '_', $name) . '_115x50.jpg';
      $safeNameT3 = NOW . '_' . preg_replace('/\W/', '_', $name) . '_150x100.jpg';
      $safePathT = PATH_FOTOROOT . '/thumbnail/' . FF_YM_STAMP . '/';
      
      $im->crop(75, 75, $safePathT . $safeNameT1);
      $im->crop(115, 50, $safePathT . $safeNameT2);
      $im->crop(150, 100, $safePathT . $safeNameT3);
      
      $basePath = '/thumbnail/' . FF_YM_STAMP . '/';
      $retval = array('400x300' => $basePath . $safeNameM, '150x100' => $basePath . $safeNameT3, '115x50' => $basePath . $safeNameT2,'75x75' => $basePath . $safeNameT1);
    }
    
    return $retval;
  }
  
  function delete($key = false, $userId = false)
  {
    if($key !== false && $userId !== false)
    {
      include_once PATH_CLASS . '/CVideo.php';
      $v =& CVideo::getInstance();
      $videoData = $v->find($key, $userId);
      
      $sql = "UPDATE user_videos SET v_status = 'deleted' WHERE v_key = " . $this->dbh->sql_safe($key) . ' AND v_u_id = ' . intval($userId);
      $this->dbh->execute($sql);
      
      if(file_exists($videoSrc = PATH_VIDEOROOT . $videoData['V_PATH']))
      {
        unlink($videoSrc);
      }
      
      return true;
    }
    
    return false;
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
      global $siteConfig;
      $inst      = new $class;
      $inst->dbh =& $GLOBALS['dbh'];
      $inst->siteConfig =& $siteConfig;
    }
    
    return $inst;
  }
}
?>

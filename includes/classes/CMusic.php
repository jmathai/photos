<?php
 /*******************************************************************************************
  * Name:  CMusic.php
  *
  * Class to handle music stuff
  *
  * Usage:
  * 
  *******************************************************************************************/
class CMusic
{
  /*******************************************************************************************
  * Description
  *   Method to retrieve track data
  *
  * Input (one of the following combinations)
  *   $id                 int   (music id)
  *   $swf_src            str   (swf src)
  * Output
  *   array
  *******************************************************************************************/
  function track()
  {
    if(func_num_args() == 1)
    {
      $value = $this->dbh->sql_safe(func_get_arg(0));
      
      if(is_numeric(func_get_arg(0)))
      {
        $field = 'm_id';
      }
      else
      {
        $field = 'm_swf_src';
      }
      
      $sql  = 'SELECT m_id AS M_ID, m_swf_src AS M_SWF_SRC, m_genre AS M_GENRE, m_tempo AS M_TEMPO, m_name AS M_NAME, m_description AS M_DESC '
            . 'FROM music '
            . 'WHERE ' . $field . ' = ' . $value . " AND m_active = 'Y'";
      
     $return = $this->dbh->fetch_assoc(
                  $this->dbh->query($sql)
                );
      
      return $return;
    }
    else
    {
      return false;
    }
  }
  
  function music($genre = false)
  {
    $return = array();
    if($genre !== false)
    {
      $genre = $this->dbh->sql_safe($genre);
      
      $sql  = 'SELECT m_id AS M_ID, m_swf_src AS M_SWF_SRC, 0 AS M_LENGTH, 0 AS M_SIZE, m_genre AS M_GENRE, m_tempo AS M_TEMPO, m_name AS M_NAME, m_description AS M_DESC '
            . 'FROM music '
            . 'WHERE m_genre = ' . $genre . " AND m_active = 'Y' "
            . 'ORDER BY m_name ASC';
      
      $rs = $this->dbh->query($sql);
      
      while($data = $this->dbh->fetch_assoc($rs))
      {
        $return[] = $data;
      }
    }
    
    return $return;
  }
  
  function myMusic($user_id = false, $track_id_src = false)
  {
    $return = array();
    
    if($user_id !== false)
    {
      include_once PATH_CLASS . '/CFotobox.php';
      $fb =& CFotobox::getInstance();
      
      if($track_id_src === false)
      {
        $mp3s = $fb->mp3s($user_id);
        
        foreach($mp3s as $v)
        {
          $return[] = array(
                        'M_ID'      => 'N/A',
                        'M_SWF_SRC' =>  $v['M_PATH'],
                        'M_LENGTH'  =>  $v['M_LENGTH'],
                        'M_SIZE'    =>  $v['M_SIZE'],
                        'M_GENRE'   => 'My Music',
                        'M_TEMPO'   => '',
                        'M_NAME'    => $v['M_NAME'],
                        'M_DESC'    => ''
                      );
        }
      }
      else
      {
        $return = $fb->mp3($track_id_src, $user_id);
      }
    }
      
    return $return;
  }
  
  function templatePlaylist($template_src = false, $user_id = false)
  {
    $return = array();
    
    if($template_src !== false && $user_id != false)
    {
      include_once PATH_CLASS . '/CFlix.php';
      $fl =& CFlix::getInstance();
      $ar = $fl->templates($template_src, $user_id);
      
      if(count($ar) > 0)
      {
        $ids  = (array)$this->dbh->asql_safe( explode(',', $ar['T_MUSIC']));
      }
      else
      {
        $ids = array('NULL');
      }
      
      $ids  = implode(',', $ids);
      
      $sql  = 'SELECT m_id AS M_ID, m_swf_src AS M_SWF_SRC, m_genre AS M_GENRE, m_tempo AS M_TEMPO, m_name AS M_NAME, m_description AS M_DESC '
            . 'FROM music '
            . 'WHERE m_id IN (' . $ids . ') AND m_active = \'Y\' '
            . 'ORDER BY m_name ASC';
            
      $return = $this->dbh->query_all($sql);
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
  
 /*******************************************************************************************
  * Description
  *   Constructor
  *******************************************************************************************/
  function CMusic()
  {
  }
}
?>

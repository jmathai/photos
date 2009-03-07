<?php
/*
  Input: username - user to import
  
  Select all the fotoflix for the user from v2.user_fotoflix
  Loop through every flix
    Convert name/description's (listed as titles) into title frames
    Insert the required data for the flix into phtogious.user_slideshows
*/
?>

<?php
include_once '../init_constants.php';
include_once PATH_INCLUDE . '/functions.php';
include_once PATH_DOCROOT . '/init_database.php';
include_once PATH_CLASS . '/CUser.php';
include_once PATH_CLASS . '/CUserManage.php';
include_once PATH_CLASS . '/CFotobox.php';
include_once PATH_CLASS . '/CFotoboxManage.php';
include_once PATH_CLASS . '/CFlix.php';
include_once PATH_CLASS . '/CFlixManage.php';
include_once 'tmp/themes.php';

$u = &CUser::getInstance();
$um = &CUserManage::getInstance();
$fb = &CFotobox::getInstance();
$fbm = &CFotoboxManage::getInstance();
$fl = &CFlix::getInstance();
$flm = &CFlixManage::getInstance();

$oldDB = 'fotoflix_live';
$newDB = 'photagious_live';

/*$oldDB = 'fotoflix_v2';
$newDB = 'photagious_temp';*/

// Input username
$username = $_GET['username'];
$userData = $u->userByUsername($username);

if($username == null)
{
  echo 'Error - Username is null';
  die();
}
else 
{
  echo '<br/>Importing Comments for ' . $username . '<br /><br />';
  
  $username = $GLOBALS['dbh']->sql_safe($username);
  
  // Select user data from old
  $sql = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_password AS U_PASSWORD, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAME_FIRST, u.u_nameLast AS U_NAME_LAST, u.u_birthDay AS U_BIRTH_DAY, '
       . 'u.u_birthMonth AS U_BIRTH_MONTH, u.u_birthYear AS U_BIRTH_YEAR, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, '
       . 'u.u_accountType AS U_ACCOUNT_TYPE, u.u_dateExpires AS U_DATE_EXPIRES, u.u_dateModified AS U_DATE_MODIFIED, u.u_dateCreated AS U_DATE_CREATED, u.u_status AS U_STATUS '
       . 'FROM ' . $oldDB . '.users AS u '
       . 'WHERE u.u_username = ' . $username . ' ';
       
  $user_rs = $GLOBALS['dbh']->query_first($sql);
  $old_user_id = $user_rs['U_ID'];
  
  echo $oldDB . ' user id: ' . $old_user_id . '<br />';
  
  // Select user data from new
  $sql = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_password AS U_PASSWORD, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAME_FIRST, u.u_nameLast AS U_NAME_LAST, u.u_birthDay AS U_BIRTH_DAY, '
       . 'u.u_birthMonth AS U_BIRTH_MONTH, u.u_birthYear AS U_BIRTH_YEAR, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, '
       . 'u.u_accountType AS U_ACCOUNT_TYPE, u.u_dateExpires AS U_DATE_EXPIRES, u.u_dateModified AS U_DATE_MODIFIED, u.u_dateCreated AS U_DATE_CREATED, u.u_status AS U_STATUS '
       . 'FROM ' . $newDB . '.users AS u '
       . 'WHERE u.u_username = ' . $username . ' ';
       
  $new_user_rs = $GLOBALS['dbh']->query_first($sql);
  $new_user_id = $new_user_rs['U_ID'];
  
  echo $newDB . ' user id: ' . $new_user_id . '<br /><br />';
  
  // Select comments from old
  $sql = 'SELECT c_id AS C_ID, c_by_u_id AS C_BY_U_ID, c_for_u_id AS C_FOR_U_ID, c_element_id AS C_ELEMENT_ID, c_comment AS C_COMMENT, c_type AS C_TYPE, c_time AS C_TIME '
       . 'FROM ' . $oldDB . '.comments '
       . 'WHERE c_for_u_id = ' . $old_user_id . ' ';
       
  $comments_rs = $GLOBALS['dbh']->query_all($sql);
  
  // insert each comment
  foreach($comments_rs as $k => $v)
  {
    // get the username for the c_by_u_id
    $sql = 'SELECT u_username AS U_USERNAME '
         . 'FROM ' . $oldDB . '.users '
         . 'WHERE u_id = ' . $v['C_BY_U_ID'] . ' ';
         
    $by_user = $GLOBALS['dbh']->query_first($sql);
    
    // make sure this user exists in the new db
    $sql = 'SELECT u_id AS U_ID, u_username AS U_USERNAME '
         . 'FROM ' . $newDB . '.users '
         . 'WHERE u_username = ' . $by_user['U_USERNAME'] . ' ';
         
    $new_by_user = $GLOBALS['dbh']->query_first($sql);
    
    if(empty($new_by_user))
    {
      echo 'c_by_u_id doesn\'t exist in the new db <br />';
      continue;
    }
    
    // make sure the element id exists
    if($v['C_TYPE'] == 'foto')
    {
      $sql = 'SELECT up_original_path AS UP_ORIGINAL_PATH '
           . 'FROM ' . $oldDB . '.user_fotos '
           . 'WHERE up_id = ' . $v['C_ELEMENT_ID'] . ' ';
           
      $old_element_rs = $GLOBALS['dbh']->query_first($sql);
      
      $sql = 'SELECT up_id AS ELEMENT_ID '
           . 'FROM ' . $newDB . '.user_fotos '
           . 'WHERE up_original_path = ' . $old_element_rs['UP_ORIGINAL_PATH'] . ' ';
           
      $new_element_rs = $GLOBALS['dbh']->query_first($sql);
    }
    else 
    {
      $sql = 'SELECT uf_fastflix AS UF_FASTFLIX '
           . 'FROM ' . $oldDB . '.user_fotoflix '
           . 'WHERE uf_id = ' . $v['C_ELEMENT_ID'] . ' ';
           
      $old_element_rs = $GLOBALS['dbh']->query_first($sql);
      
      $sql = 'SELECT us_id AS ELEMENT_ID '
           . 'FROM ' . $newDB . '.user_slideshows '
           . 'WHERE us_key = ' . $old_element_rs['UF_FASTFLIX'] . ' ';
           
      $new_element_rs = $GLOBALS['dbh']->query_first($sql);
    }
    
    if(empty($new_element_rs))
    {
      echo 'c_element_id doesn\'t exist in the new db <br />';
      continue;
    }
    
    // other user and element exist, so insert the comment
    $sql = 'INSERT INTO ' . $newDB . '.comments (c_by_u_id, c_for_u_id, c_element_id, c_comment, c_type, c_time) '
         . 'VALUES (' . $new_by_user['U_ID'] . ', ' . $new_user_id . ', ' . $new_element_rs['ELEMENT_ID'] . ', ' . $v['C_COMMENT'] . ', ' . $v['C_TYPE'] . ', ' . $v['C_TIME'] . ') ';
         
    $GLOBALS['dbh']->execute($sql);
    
    echo 'comment inserted <br />';
  }
}
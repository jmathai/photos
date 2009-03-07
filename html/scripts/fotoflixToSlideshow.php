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

function xmlUnSafe($str)
{
  return str_replace(array('&amp;','&lt;','&gt;'), array('&','<','>'), $str);
}

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

$oldPhotoPath = '/www/www.fotoflix.com/html/fotos';
$newPhotoPath = '/www/photagious.com/www/html/photos';

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
  echo '<br/>Importing Flix to Slideshows for ' . $username . '<br /><br />';
  
  $username = $GLOBALS['dbh']->sql_safe($username);
  
  // Select user data from old
  $sql = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_password AS U_PASSWORD, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAME_FIRST, u.u_nameLast AS U_NAME_LAST, u.u_birthDay AS U_BIRTH_DAY, '
       . 'u.u_birthMonth AS U_BIRTH_MONTH, u.u_birthYear AS U_BIRTH_YEAR, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, '
       . 'u.u_accountType AS U_ACCOUNT_TYPE, u.u_dateExpires AS U_DATE_EXPIRES, u.u_dateModified AS U_DATE_MODIFIED, u.u_dateCreated AS U_DATE_CREATED, u.u_status AS U_STATUS '
       . 'FROM ' . $oldDB . '.users AS u '
       . 'WHERE u.u_username = ' . $username . ' ';
       
  $user_rs = $GLOBALS['dbh']->query_first($sql);
  $user_id = $user_rs['U_ID'];
  
  echo $oldDB . ' user id: ' . $user_id . '<br />';
  
  // Select user data from new
  $sql = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_password AS U_PASSWORD, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAME_FIRST, u.u_nameLast AS U_NAME_LAST, u.u_birthDay AS U_BIRTH_DAY, '
       . 'u.u_birthMonth AS U_BIRTH_MONTH, u.u_birthYear AS U_BIRTH_YEAR, u.u_address AS U_ADDRESS, u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, '
       . 'u.u_accountType AS U_ACCOUNT_TYPE, u.u_dateExpires AS U_DATE_EXPIRES, u.u_dateModified AS U_DATE_MODIFIED, u.u_dateCreated AS U_DATE_CREATED, u.u_status AS U_STATUS '
       . 'FROM ' . $newDB . '.users AS u '
       . 'WHERE u.u_username = ' . $username . ' ';
       
  $new_user_rs = $GLOBALS['dbh']->query_first($sql);
  $new_user_id = $new_user_rs['U_ID'];
  
  echo $newDB . ' user id: ' . $new_user_id . '<br /><br />';
   
  // Select all the slideshows for this user from old
  $sql = 'SELECT uf.uf_id AS UF_ID, uf.uf_tags AS UF_TAGS, uf.uf_autoplay AS UF_AUTOPLAY, uf.uf_fastflix AS UF_FASTFLIX, uf.uf_template AS UF_TEMPLATE, uf.uf_delay AS UF_DELAY, uf.uf_music AS UF_MUSIC, uf.uf_name AS UF_NAME, '
       . 'uf.uf_createdBy AS UF_CREATED_BY, uf.uf_description AS UF_DESC, uf.uf_fotoCount AS UF_FOTO_COUNT, uf.uf_length AS UF_LENGTH, uf.uf_views AS UF_VIEWS, uf.uf_viewsComplete AS UF_VIEWS_COMPLETE, '
       . 'uf.uf_public AS UF_PUBLIC, uf.uf_privacy AS UF_PRIVACY, uf.uf_publicOrder AS UF_PUBLIC_ORDER, uf.uf_dateModified AS UF_DATE_MODIFIED, uf.uf_dateCreated AS UF_DATE_CREATED, uf.uf_status AS UF_STATUS '
       . 'FROM ' . $oldDB . '.user_fotoflix AS uf '
       . 'WHERE uf.uf_u_id = ' . intval($user_id) . " AND uf.uf_status = 'active' ";
       
  $slideshows_rs = $GLOBALS['dbh']->query_all($sql);
  
  echo 'Looping through user\'s flix<br /><br />';
  
  // Loop through all the slideshows
  foreach($slideshows_rs as $k => $v)
  {
    if(isset($themes[$v['UF_TEMPLATE']]))
    {
      // create the settings
      $settings = array();
  
      // Based on the theme get the premade mc_array
      $settings = jsonDecode($themes[$v['UF_TEMPLATE']]);
      $settings[0]['autoPlay_bool'] = ($v['UF_AUTOPLAY'] == 'y') ? 1 : 0;
      $settings[0]['musicPath_str'] = $v['UF_MUSIC'];
      $settings[0]['title_str'] = xmlUnSafe($v['UF_NAME']);
      
      echo 'Flix id: ' . $v['UF_ID'] . '<br />';
      echo 'Settings: ';
      print_r($settings);
      echo '<br />';
      
      // Set Permission
      // Figure out the new privacy based on the old privacy settings
      // 333 (public, comment, tags), 311 (public), 111 (private)
      $privacy = PERM_SLIDESHOW_PUBLIC;
      switch($v['UF_PRIVACY'])
      {
        case '333':
          $privacy = PERM_SLIDESHOW_PUBLIC | PERM_SLIDESHOW_COMMENT | PERM_SLIDESHOW_TAG;
          break;
        case '331':
          $privacy = PERM_SLIDESHOW_PUBLIC | PERM_SLIDESHOW_COMMENT;
          break;
        case '311':
          $privacy = PERM_SLIDESHOW_PUBLIC;
          break;
        case '111':
          $privacy = PERM_SLIDESHOW_PRIVATE;
          break;
      }
      
      echo 'Permission: ' . $privacy . '<br />';
      
      // Get all the slideshow elements from old
      $sql = 'SELECT ufd.ufd_up_id AS UFD_UP_ID, ufd.ufd_delay AS UFD_DELAY, ufd.ufd_isTitle AS UFD_IS_TITLE, ufd.ufd_name AS UFD_NAME, ufd.ufd_description AS UFD_DESC '
           . 'FROM ' . $oldDB . '.user_fotoflix_data AS ufd '
           . 'WHERE ufd.ufd_uf_id = ' . $v['UF_ID'] . ' '
           . 'ORDER BY ufd.ufd_order ';
           
      $elements_rs = $GLOBALS['dbh']->query_all($sql);
      
      // Create the new elements
      $elements = array();
      
      $photoPath_str = '';
      $photoKey_str = '';
      $photoId_int = 0;
      $thumbnailPath_str = '';
      $description_str = '';
      $delay_int = 0;
      $tags_str = '';
      $width_int = 0;
      $height_int = 0;
      $rotation_int = 0;
      
      $themeAr = jsonDecode($themes[$v['UF_TEMPLATE']]);
      
      if($v['UF_CREATED_BY'] !== null)
      {
        echo 'Created By Title Frame<br />';
        
        // set title frame
        $delay_int = 3000;
        foreach($themeAr as $v3)
        { 
          if($v3['instanceName_str'] == 'title_mc')
          { 
            $swfPath_str = $v3['swfPath_str']; 
            break;
          }
        }
           
        $title_str  = "Created by\n" . xmlUnSafe($v['UF_CREATED_BY']);
        $mainColor_str = $settings[0]['highlightColor_str']; 
        $backgroundColor_str = $settings[0]['backgroundColor_str'];
        
        $elements[] = array('delay_int' => $delay_int, 'swfPath_str' => $swfPath_str, 'title_str' => $title_str, 'mainColor_str' => $mainColor_str, 'backgroundColor_str' => $backgroundColor_str);
      }
      
      // Loop over each photo in the fotoflix and add it as a new element
      foreach($elements_rs as $k2 => $v2)
      { 
        // Each photo's data from v3
        $sql = 'SELECT up.up_id AS UP_ID, up.up_thumb_path AS UP_THUMB_PATH, up.up_original_path AS UP_ORIGINAL_PATH, up.up_name AS UP_NAME, up.up_description AS UP_DESC, up.up_tags AS UP_TAGS '
             . 'FROM ' . $oldDB . '.user_fotos AS up '
             . 'WHERE up.up_id = ' . $v2['UFD_UP_ID'] . ' ';
             
        $oldPhoto_rs = $GLOBALS['dbh']->query_first($sql);
        
        // Map to this photo on new
        $sql = 'SELECT up.up_id AS UP_ID, up.up_thumb_path AS UP_THUMB_PATH, up.up_original_path AS UP_ORIGINAL_PATH, up.up_name AS UP_NAME, up.up_description AS UP_DESC, up.up_tags AS UP_TAGS, '
             . 'up_key AS UP_KEY, up_width AS UP_WIDTH, up_height AS UP_HEIGHT, up_rotation AS UP_ROTATION '
             . 'FROM ' . $newDB . '.user_fotos AS up '
             . 'WHERE up.up_original_path = ' . $GLOBALS['dbh']->sql_safe($oldPhoto_rs['UP_ORIGINAL_PATH']) . ' ';
             
        $photo_rs = $GLOBALS['dbh']->query_first($sql);
        
        // If it's a title, then add a frame before the photo
        if($v2['UFD_IS_TITLE'] == 'Y')
        {
          echo 'Title frame<br />';
          
          $delay_int = 3000;
          
          foreach($themeAr as $v3)
          { 
            if($v3['instanceName_str'] == 'title_mc')
            { 
              $swfPath_str = $v3['swfPath_str']; 
              break;
            }
          }
  
          $title_str  = xmlUnSafe($v2['UFD_NAME']);
          $mainColor_str = $settings[0]['highlightColor_str'];
          $backgroundColor_str = $settings[0]['backgroundColor_str'];
          
          $elements[] = array('delay_int' => $delay_int, 'swfPath_str' => $swfPath_str, 'title_str' => $title_str, 'mainColor_str' => $mainColor_str, 'backgroundColor_str' => $backgroundColor_str);
          $description_str = xmlUnSafe($v2['UP_DESC']);
        }
        else
        {
          $description_str = xmlUnSafe($v2['UFD_NAME']) . "\n" . xmlUnSafe($v2['UFD_DESC']);
        }
        
        $photoId_int = $photo_rs['UP_ID'];
        $photoPath_str = $photo_rs['UP_ORIGINAL_PATH'];
        $thumbnailPath_str = $photo_rs['UP_THUMB_PATH'];
        $delay_int = $v2['UFD_DELAY'];
        $tags_str = $photo_rs['UP_TAGS'];
        $photoKey_str = $photo_rs['UP_KEY'];
        $width_int = $photo_rs['UP_WIDTH'];
        $height_int = $photo_rs['UP_HEIGHT'];
        $rotation_int = $photo_rs['UP_ROTATION'];
      
        
        // insert the new element
        $elements[] = array('photoId_int' => $photoId_int, 'photoPath_str' => $photoPath_str, 'thumbnailPath_str' => $thumbnailPath_str, 'description_str' => $description_str, 'delay_int' => $delay_int, 'tags_str' => $tags_str, 'photoKey_str' => $photoKey_str, 'width_int' => $width_int, 'height_int' => $height_int, 'rotation_int' => $rotation_int);
      }
      
      echo 'Elements: ';
      print_r($elements);
      echo '<br />';
      
      // Create the main elements of the slideshow
      $main = array('USER_ID' => $new_user_id, 'NAME' => $v['UF_NAME'], 'TAGS' => (array)explode(',', $v['UF_TAGS']), 'FOTOCOUNT' => $v['UF_FOTO_COUNT'], 'PRIVACY' => $privacy, 'VIEWS' => $v['UF_VIEWS'], 'VIEWSCOMPLETE' => $v['UF_VIEWS_COMPLETE'], 'ORDER' => $v['UF_PUBLIC_ORDER']);
      
      echo 'Main: ';
      print_r($main);
      echo '<br />';
      
      // Encode both settings and elements
      $settings = jsonEncode($settings);
      $elements = jsonEncode($elements);
      
      // Call the new slideshow method to insert it
      $slideshow_key = $flm->createSlideshow($main, $settings, $elements);
      $sql = 'UPDATE user_slideshows SET us_key = ' . $GLOBALS['dbh']->sql_safe($v['UF_FASTFLIX']) . ', us_dateModified = ' . $GLOBALS['dbh']->sql_safe($v['UF_DATE_MODIFIED']) . ', us_dateCreated = ' . $GLOBALS['dbh']->sql_safe($v['UF_DATE_CREATED']) . ' WHERE us_key = ' . $GLOBALS['dbh']->sql_safe($slideshow_key);
      $GLOBALS['dbh']->execute($sql);
      echo $sql . '<br/><br/>';
      //$flm->updateSlideshow(array('FOTOCOUNT' => $v['UF_FOTO_COUNT'], 'US_ID' => $slideshow_id, 'USER_ID' => $new_user_id));
      
      echo 'New Slideshow Id: ' . $slideshow_key . '<br /><br />';
      
      $sql = 'REPLACE INTO ' . $newDB . '.fotoflix_slideshow_map (fotoflix_id, slideshow_key) '
           . 'VALUES(' . $v['UF_ID'] . ', ' . $GLOBALS['dbh']->sql_safe($slideshow_key) . ') ' ;
           
      $GLOBALS['dbh']->execute($sql);
      
      echo 'NEXT FLIX<br /><br /><br />';
    }
    else
    {
      echo '<br/><br/>****************************************<br/><b>THEME DOES NOT EXIST: ' . $v['UF_TEMPLATE'] . '</b><br/>****************************************<br/><br/>';
    }
  }
  
  echo 'Import Finished';
}
?>
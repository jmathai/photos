<?php
/*
  Input: username - user to import
  
  If this username already exists on photagious
    abort the script
  
  Get all of the user's information from fotoflix_live
  Insert the new user into photagious
  
  Get all of the user's photos information from fotoflix_live
  Insert each photo into photagious
*/

include_once dirname(__FILE__) . '/../init_constants.php';
include_once PATH_DOCROOT . '/init_database.php';
include_once PATH_CLASS . '/CUser.php';
include_once PATH_CLASS . '/CUserManage.php';
include_once PATH_CLASS . '/CIdat.php';
include_once PATH_CLASS . '/CFotobox.php';
include_once PATH_CLASS . '/CFotoboxManage.php';
include_once PATH_CLASS . '/CFlix.php';
include_once PATH_CLASS . '/CFlixManage.php';
include_once PATH_HOMEROOT . '/scripts/tmp/themes.php';

$mode = FF_SERVER_NAME == 'www.photagious.com' ? 'live' : 'test';

switch($mode)
{
  case 'test':
    $oldDB = 'fotoflix_live';
    $newDB = 'photagious_temp';
    $oldPhotoPath = '/www/www.fotoflix.com/html/fotos';
    $newPhotoPath = '/www/photagious.com/temp/html/photos';
    break;
  case 'live':
    $oldDB = 'fotoflix_live';
    $newDB = 'photagious_live';
    $oldPhotoPath = '/www/www.fotoflix.com/html/fotos';
    $newPhotoPath = '/www/photagious.com/www/html/photos';
    break;
  default:
    echo 'no mode';
    die();
    break;
}

$u = &CUser::getInstance();
$um = &CUserManage::getInstance();
$fb = &CFotobox::getInstance();
$fbm = &CFotoboxManage::getInstance();
$idat = &CIdat::getInstance();
$fl = &CFlix::getInstance();
$flm = &CFlixManage::getInstance();

$accounts = "'premium_trial'";

if(isset($_GET['username']))
{
  $fotoflixUsers = $GLOBALS['dbh']->query_all($sql = 'SELECT u_username, u_accountType FROM ' . $oldDB . '.users WHERE u_username = ' . $GLOBALS['dbh']->sql_safe($_GET['username']) . ' AND u_dateExpires > NOW() AND u_status = \'active\'');
}
else
if($mode == 'live')
{
  $fotoflixUsers = $GLOBALS['dbh']->query_all($sql = 'SELECT u_username, u_accountType FROM ' . $oldDB . '.users WHERE u_accountType IN(' . $accounts . ') AND u_dateExpires > NOW() AND u_status = \'active\'');
}
else
{
  $fotoflixUsers = $GLOBALS['dbh']->query_all($sql = 'SELECT u_username, u_accountType FROM ' . $oldDB . '.users WHERE u_accountType IN(' . $accounts . ') AND u_dateExpires > NOW()  AND u_status = \'active\'');
}
print_r($fotoflixUsers);

foreach($fotoflixUsers as $ffUser)
{
  $username = $ffUser['u_username'];
  $userData = $u->userByUsername($username);
  
  if($username == null)
  {
    echo 'Error - Username is null...skipping<br/>';
    continue;
  }
  else if($userData !== false)
  {
    echo 'Error - Username already exists...skipping<br/>';
    continue;
  }
  else 
  {
    echo 'importing ' . $username . ' @ ' . PATH_CLASS . '<br/>' . $mode;
    set_time_limit(0);
    
    importUserInformation($username, $mode);
    
    if($ffUser['u_accountType'] != 'premium_trial')
    {
      importUserEcom($username, $mode); // ecom recur information
      importUserPhotos($username, $mode); // photos and quicksets
      importUserSlideshows($username, $mode); // slideshows and mp3s
    }
  }
}

?>
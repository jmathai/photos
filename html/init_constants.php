<?php
  $_const_mode = !isset($argv[1]) ? $_SERVER['SERVER_NAME'] : $argv[1];

  switch($_const_mode)
  {
    case 'mac.photagious.com':
    case 'mac.photagious.com:80':
      define('PATH_INCLUDE', '/Users/jmathai/Y/photos/includes');
      define('PATH_HOMEROOT',  '/Users/jmathai/Y/photos/html');
      define('PATH_SECRET',  '/Users/jmathai/Y/photos/secret');
      define('PATH_BACKUP', '/www/backups/demo'); // DNE
      define('PATH_EXEC', '/opt/local/bin');
      define('FF_SERVER_NAME', 'mac.photagious.com');
      define('FF_STATIC_URL', 'http://mac.photagious.com');
      define('FF_MODE', 'development');
      define('FF_ENVIORNMENT', 'local');
      define('FF_ECOM_COMMIT', false);
      define('FF_ECOM_RESULT_DEFAULT', 1); // value defined as a constant CEcomGateway_authorize.php
      define('FF_ERROR_REPORTING', E_ERROR | E_PARSE | E_WARNING);
      define('FB_API_KEY', 'ae22ca97353391576a2eb44bc595b441');
      define('FB_SECRET', trim(file_get_contents(PATH_SECRET . '/facebook_secret')));
      define('DB_HOST', 'localhost');
      define('DB_USER', 'root');
      define('DB_PASS', trim(file_get_contents(PATH_SECRET . '/mysql_password')));
      define('DB_NAME', 'photagious');
      define('DB_DBMS', 'mysql');
      define('GMAP_KEY', 'ABQIAAAApenhXDxFYh2ZrUerPrWSdxT7WM4p2E5b68hDZr2ZO_4QtM3KPBQ4BKfPzLWO_98Ol0MT5TJIJ2wnLg');
      break;
    case 'photos.branefamily.com':
    case 'photos.jaisenmathai.com':
      error_reporting(E_ALL ^ E_NOTICE);
      define('PATH_INCLUDE', '/www/photagious.com/www/includes');
      define('PATH_HOMEROOT',  '/www/photagious.com/www/html');
      define('PATH_SECRET',  '/www/photagious.com/www/secret');
      define('PATH_EXEC', '/usr/bin');
      define('FF_SERVER_NAME', $_const_mode);
      define('FF_STATIC_URL', "http://{$_const_mode}");
      define('FF_MODE', 'live');
      define('FF_ENVIORNMENT', 'production');
      define('FF_ECOM_COMMIT', true); // change to true and change below to ''
      define('FF_ECOM_RESULT_DEFAULT', '');
      define('FF_ERROR_REPORTING', E_ERROR | E_PARSE);
      define('FB_API_KEY', 'ddf49b968bc8122843f42ae6c55ced98');
      define('FB_SECRET', trim(file_get_contents(PATH_SECRET . '/facebook_secret')));
      define('DB_HOST', 'localhost');
      define('DB_USER', 'fotoflix_strict');
      define('DB_PASS', trim(file_get_contents(PATH_SECRET . '/mysql_password')));
      define('DB_NAME', 'jaisenmathai_ptg');
      define('DB_DBMS', 'mysql');
      define('GMAP_KEY', 'ABQIAAAApenhXDxFYh2ZrUerPrWSdxT9yy5AVeixNYn6myX0mzov-hUyFhSEaFOZ4RETZGWcVs1kwOYz4cKz0Q');
      break;
    default:
      echo 'Unknown configuration.  Please wait while you are redirected.  (' . $_const_mode . ')';
      die();
  }

  define('LF', chr(10) . chr(13));
  define('KB', 1024);
  define('NOW', time());

  define('PATH_FOTOROOT', PATH_HOMEROOT . '/photos');
  define('PATH_VIDEOROOT', PATH_HOMEROOT . '/videos');
  define('PATH_DOCROOT', PATH_INCLUDE . '/documents');
  define('PATH_CLASS', PATH_INCLUDE . '/classes');
  define('PATH_TMPROOT', PATH_FOTOROOT . '/tmp');
  define('PATH_FOTO', '/photos');
  define('PATH_VIDEO', '/videos');
  define('PATH_TMP', PATH_FOTO . '/tmp');
  define('PATH_IMAGE', '/images');
  define('PATH_SWF', '/swf');
  define('PATH_SWF_MUSIC', '/swf/audio');
  define('PATH_SENDMAIL', '/usr/sbin/sendmail -t -i');

  define('KEY_SESSION', 'fotoflix.user_session');

  define('FF_SESSION_KEY', 'ff_session_id');
  define('FF_SESSION_LENGTH', 604800); // 7 days
  define('FF_SESSION_EXPIRY', NOW + FF_SESSION_LENGTH);
  define('FF_SESSION_PATH', '/');
  define('FF_SESSION_DOMAIN', FF_SERVER_NAME);
  define('FF_SESSION_UID_PREFIX', '');
  define('FF_VERSION_EDITOR', '3.1');
  define('FF_VERSION_TEMPLATE', '2.0');
  define('FF_IMAGE_KEY', 'fotobox.image_key');
  define('FF_API_READ', 1); // read only permission for api
  define('FF_API_WRITE', 3); // read/write permission for api
  
  define('FF_YM_STAMP', date('Ym', NOW));

  define('FF_THUMB_WIDTH', 75);
  define('FF_THUMB_HEIGHT', 75);
  define('FF_WEB_WIDTH', 500); // for cropping
  define('FF_WEB_HEIGHT', 375); // for cropping
  define('FF_BASE_WIDTH', 1280);
  define('FF_BASE_HEIGHT', 1024);

  
  define('FF_EMAIL_FROM', 'jaisen@jmathai.com');
  define('FF_EMAIL_FROM_FORMATTED', '"Jaisen Mathai" <jaisen@jmathai.com>');
  
  define('FF_FORMAT_DATE_LONG', 'l, F j, Y');
  define('FF_MYSQL_DATE_TIME', 'Y-m-d H:i:s');
  
  define('FF_FB_COLS', 4);
  define('FF_FB_COL_WIDTH', 130);
  define('FF_FB_ROW_HEIGHT', 120);

  define('USER_IS_TRIAL', 1);
  define('USER_IS_NOT_TRIAL', 0);
  
  define('PERM_USER_0', 0); // personal
  define('PERM_USER_1', 1); // professional
  define('PERM_USER_2', 3);
  
  define('PERM_TEIR_1', 1);
  define('PERM_TEIR_2', 3);
  
  define('PERM_PHOTO_DEFUALT', 3);
  
  define('PERM_PHOTO_PRIVATE', 0);
  define('PERM_PHOTO_PUBLIC', 1);
  define('PERM_PHOTO_COMMENT', 2);
  define('PERM_PHOTO_TAG', 4);
  define('PERM_PHOTO_DOWNLOAD', 8);
  define('PERM_PHOTO_COPY', 16);
  define('PERM_PHOTO_PRINT', 32);
  define('PERM_PHOTO_LIMIT', 65535);
  
  define('PERM_SLIDESHOW_DEFUALT', 5);
  
  // do not use 0 because it can be private and allow comments
  define('PERM_SLIDESHOW_PRIVATE', 0);
  define('PERM_SLIDESHOW_PUBLIC', 1);
  define('PERM_SLIDESHOW_UNKNOWN', 2);
  define('PERM_SLIDESHOW_COMMENT', 4);
  define('PERM_SLIDESHOW_RELATED', 8);
  
  define('PERM_VIDEO_DEFAULT', 11);
  
  define('PERM_VIDEO_PRIVATE', 0);
  define('PERM_VIDEO_PUBLIC', 1);
  define('PERM_VIDEO_COMMENT', 2);
  
  define('PERM_GROUP_LIMIT', 4294901760);
  
  define('PAGE_AVATAR_WIDTH', 220);
  define('PAGE_AVATAR_HEIGHT', 95);
  
  define('CATALOG_ACCOUNT_START', 1);
  define('CATALOG_ACCOUNT_END', 4);
  
  define('FF_VIDEO_SIZE', '640x480');
  define('FF_VIDEO_ASPECT', '4:3');
  define('FF_VIDEO_BITRATE', '409600'); // 400 kbits/s
  define('FF_AUDIO_FREQUENCY', '22050');
  define('FF_AUDIO_BITRATE', '64');
?>

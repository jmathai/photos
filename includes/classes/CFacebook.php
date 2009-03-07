<?php
require_once PATH_CLASS . '/epi/EpiCurl.php';
class CFacebook
{
  private function __construct(){}

  public function createAlbum($albumName)
  {
    $args = array(
      'method' => 'photos.createAlbum',
      'v' => $this->version,
      'api_key' => $this->apikey,
      'uid' => $this->fbId,
      'call_id' => $cid,
      'name' => $albumName,
      'format' => 'XML'
    );
    $this->signRequest($args);
    $url = $this->createUrl('photos.createAlbum');

    $req = $this->makeRequest($url, $args);
    $sXml = simplexml_load_string($req->data);
    return array(
              'aid' => (string)$sXml->aid,
              'link'=> (string)$sXml->link,
              'size'=> (string)$sXml->size
            );
  }
  
  public function publishPhotos($photos, $albumName, $username)
  {
    $album = $this->createAlbum($albumName);
    $albumId = $album['aid'];

    $posts = array();
    foreach($photos as $photo)
    {
      $cid = microtime(true);

      if(file_exists($basePath = str_replace('/original/', '/base/', $photo['P_ORIG_PATH'])))
      {
        $path = $basePath;
        $file= PATH_FOTOROOT . $path;
      }
      else
      {
        $url = array_shift(dynamicImageLock($photo['P_ORIG_PATH'], $photo['P_KEY'], $photo['P_ROTATION'], $photo['P_WIDTH'], $photo['P_HEIGHT'], 640, 640));
        $ch = curl_init($url);
        $_tmp = curl_exec($ch);
        $path = parse_url($url, PHP_URL_PATH);
        $file= PATH_HOMEROOT . $path;
      }

      $basename = basename($file);

      $args = array(
        'method' => 'photos.upload',
        'v' => $this->version,
        'api_key' => $this->apikey,
        'uid' => $this->fbId,
        'call_id' => $cid,
        'aid' => $albumId,
        'caption' => 'Published from http://' . FF_SERVER_NAME . '/users/' . $username . '/',
        'format' => 'XML'
      );
      $this->signRequest($args);
      $args[$basename] = '@' . realpath($file);
      ob_start();
      $url = $this->createUrl('photos.upload');
      $posts[] = $this->makeRequest($url, $args);
      ob_clean();
    }

    foreach($posts as $post)
    {
      $post->data;
    }

    return array('name' => $album['name'], 'link' => $album['link']);
  }

  private function signRequest(&$args)
  {
    ksort($args);
    $sig = '';
    foreach($args as $k => $v){
      $sig .= $k . '=' . $v;
    }
    $sig .= $this->secret;
    $args['sig'] = md5($sig);
  }

  private function createUrl($method, $params = array())
  {
    $url = 'http://api.facebook.com/restserver.php?method=' . $method;
    foreach($params as $key => $val)
    {
      $url .= "&{$key}={$val}";
    }

    return $url;
  }

  private function makeRequest($url, $args)
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    return $this->curl->addCurl($ch);
  }

  static function & getInstance($fbId=null)
  {
    static $inst = null;
    $class = __CLASS__;
    
    if($inst === null)
    {
      $inst      = new $class;
      $inst->apikey = '03f99def50e358c05b3855039c85d097';
      $inst->secret = '9cd9d117a7d2112e80448eb15c41fd11';
      $inst->fbId   = '500273081'; //$fbId;
      $inst->version= '1.0';
      $inst->curl   = EpiCurl::getInstance();
    }
    
    return $inst;
  }
}
?>

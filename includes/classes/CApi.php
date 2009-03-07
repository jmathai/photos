<?php
/*
 *******************************************************************************************
 * Class Name:  CApi
 *------------------------------------------------------------------------------------------
 * Mod History: Jaisen Mathai (March 9, 2005)
 *------------------------------------------------------------------------------------------
 * Api class which performs various interaction with remote (or local) systems using web services
 *
 * Usage:
 * 
 ******************************************************************************************
 */

class CApi
{
  function postBlogger()
  {
    $uri = 'http://plant.blogger.com/api/RPC2';
    $params = array('appkey' => '', 'blogid' => '11341374', 'username' => 'senzafine77', 'password' => 'senzafine77', 'content' => 'FotoFlix autopost', 'publish' => true);
    $this->_call($uri, 'blogger.newPost', $params);
  }
  
  function _call($uri, $method, $params)
  {
    include_once PATH_CLASS . '/nusoap/nusoap.php';
    $this->client =& new soapclient($uri);
    
    $cnt_param = count($param);
    for($i=0; $i<$cnt_param; $i++)
    {
      $param[$i] = utf8_encode($param[$i]);
    }
    
    echo '<br /><br />----------------------------------------------------------------------------------------<br /><br />';
    echo $this->client->getHTTPContentTypeCharset();
    echo '<br /><br />----------------------------------------------------------------------------------------<br /><br />';
    print_r($this->client);
    $result = $this->client->call($method, $params);
    echo '<br /><br />----------------------------------------------------------------------------------------<br /><br />';
    echo $this->client->getHTTPContentTypeCharset();
    echo '<br /><br />----------------------------------------------------------------------------------------<br /><br />';
    print_r($result);
  }
  
  function CApi()
  {
  }
}
?>
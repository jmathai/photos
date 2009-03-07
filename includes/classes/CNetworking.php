<?php
/*******************************************************************************************
 * Class Name:  CNetworking
 *------------------------------------------------------------------------------------------
 * Mod History: Jaisen Mathai   07/28/2003
 *------------------------------------------------------------------------------------------
 * Miscellaneous networking tools
 * Primarily for socket connections but expanded to cover curl
 *
 * Usage:
 *    include('/export/home/jacor-common/classes/CNetworking.php');
 *    $cn = new CNetworking;
 *    $return = $cn->post('clearchannel.com', 80, '/socket/test.php', array('name_1' => 'value_1', 'name_2' => 'value_2'));
 * 
 *******************************************************************************************/

class CNetworking
{
  /*******************************************************************************************
  * Description
  *   Create a socket connection
  *
  * Input
  *   $host   = (required) string hostname
  *   $port   = (optional) numeric port number
  * 
  * Output
  *   $return = handle
  *******************************************************************************************/
  function open($host = false, $port = false)
  {
    if($host != false && $port != false)
    {
      $this->fp = fsockopen($host, $port);
      
      return $this->fp;
    }
    else
    {
      return false;
    }
  }
  
  /*******************************************************************************************
  * Description
  *   Close a socket connection
  *
  * Output
  *   boolean
  *******************************************************************************************/
  function close()
  {
    if($this->fp)
    {
      fclose($this->fp);
    }
    else
    {
      return false;
    }
  }  
  
  /*******************************************************************************************
  * Description
  *   Submits a post request using a socket connection
  *
  * Input
  *   $uri    = (optional) string uri
  *   $data   = (optional) array post data
  *******************************************************************************************/
  function post($uri = false, $data = false)
  {
    if($uri != false)
    {
      return $this->_request('POST', $uri, $data);
    }
    else
    {
      return false;
    }
  }
  
  /*******************************************************************************************
  * Description
  *   Submits a get request using a socket connection
  *
  * Input
  *   $uri    = (optional) string uri
  *   $data   = (optional) array post data
  *******************************************************************************************/
  function get($uri = false, $data = false)
  {
    if($uri != false)
    {
      return $this->_request('GET', $uri, $data);
    }
    else
    {
      return false;
    }
  }
  
  /*******************************************************************************************
  * Description
  *   Submit a request (POST/GET) using curl
  *
  * Input
  *   $host     = (required) string hostname
  *   $protocol = (optional) string hostname
  *   $uri      = (optional) string uri
  *   $curlopts = (optional) array key/value of values used in curl_setopt function
  *******************************************************************************************/
  function curl_request($host = false, $protocol = 'http', $uri = '', $curlopts = array())
  {
    if($host !== false)
    {
      $curl_h = curl_init();
      curl_setopt ($curl_h, CURLOPT_URL,$protocol . '://' . $host . $uri);
      foreach($curlopts as $k => $v)
      {
        if($k == 'CURLOPT_POSTFIELDS' && is_array($v))
        {
          $v = $this->_genQs($v);
        }
        
        curl_setopt($curl_h, constant($k), $v);
      }
      
      $return = curl_exec($curl_h);
      curl_close($curl_h);

      return $return;
    }
    else
    {
      return false;
    }
  }
  
  /*******************************************************************************************
  * Description
  *   Class constructor (empty)
  *******************************************************************************************/ 
  function CNetworking()
  {
    // empty constructor
  }
  
  /*******************************************************************************************
  * INTERNAL METHODS
  *******************************************************************************************/
  
  /*******************************************************************************************
  * Description
  *   Generate name/value pair string from array
  *******************************************************************************************/ 
  function _genQs($data = array())
  {
    $postdata = '';
    if(is_array($data))
    {
      while(list($k,$v) = each($data))
      {
        $postdata .= $k . '=' . $v . '&';
      }
    }
    
    return $postdata;
  }
  
  /*******************************************************************************************
  * Description
  *   Submit a request using a socket connection
  *
  * Input
  *   $method   = (required) string method
  *   $uri      = (required) string uri
  *   $data     = (optional) data to be submitted along with request
  *******************************************************************************************/
  function _request($medhod = '', $uri = '', $data = false)
  {
    if($method != false && $uri != false && $this->fp)
    {
      $requestdata  = '?'.$this->_genQs($data);
      
      $requestlength   = strlen($requestdata);
      
      
      $content  = "$method $uri HTTP/1.1\n"
                . "Referer: http://".$host.$uri."\n"
                . "Accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, */*\n"
                . "Accept-Language: en-us\n"
                . "Accept-Encoding: gzip, deflate\n"
                . "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4b) Gecko/20030516 Mozilla Firebird/0.6\n"
                . "Host: $host\n"
                . "Connection: Keep-Alive\n\n";
      
      if($method == 'POST')
      {
        $content .= "Content-Type: application/x-www-form-urlencoded\n"
                 .  "Content: $requestlength\n";
      }
      
      $content  .= "$requestdata\n\n\r\n";

      
      fputs($this->fp, $content);
      
      $return = '';
      
      while(!feof($this->fp) && $this->fp)
      {
        $return .= fgets($this->fp, 128);
      }
      
      $this->close();
      
      return $return;
    }
    else
    {
      return false;
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
      $inst       = new $class;
    }
    
    return $inst;
  }
}
?>

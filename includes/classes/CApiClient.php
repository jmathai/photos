<?php
/*
 *******************************************************************************************
 * Class Name:  CApiClient
 *------------------------------------------------------------------------------------------
 * Mod History: Jaisen Mathai (April 12, 2005)
 *------------------------------------------------------------------------------------------
 ******************************************************************************************
 */

class CApiClient
{
 /*
  *******************************************************************************************
  * Name
  *   atomPostBasic
  * Description
  *   method to sent a post request to an atom enabled service using basic authentication
  *   Use CApiClient::atomPost('username', 'password', '1111111', 'www.blogger.com', '/atom/1111111', 'title', 'body', timestamp) to get blogs
  *
  * Output
  *   http status code returned from server
  ******************************************************************************************
  */
  function atom($data = false)
  {
    $return = false;
    //$host = false, $path = false, $method = 'POST', $authentication = 'SECURE', $protocol = 'SSL', $content = '', $issued = NOW)
    if(isset($data['HOST']) && isset($data['PATH']))
    {
      $data['METHOD'] = isset($data['METHOD']) ? $data['METHOD'] : 'POST';
      $data['AUTHENTICATION'] = isset($data['AUTHENTICATION']) ? $data['AUTHENTICATION'] : 'SECURE';
      $data['PROTOCOL'] = isset($data['PROTOCOL']) ? $data['PROTOCOL'] : 'SSL';
      $data['CONTENT'] = isset($data['CONTENT']) ? $data['CONTENT'] : '';
      $data['ISSUED'] = isset($data['ISSUED']) ? $data['ISSUED'] : NOW;
      $data['USERNAME'] = isset($data['USERNAME']) ? $data['USERNAME'] : '';
      $data['PASSWORD'] = isset($data['PASSWORD']) ? $data['PASSWORD'] : '';
      //$data[''] = isset($data['']) ? $data[''] : '';
      
      if(strncmp($data['AUTHENTICATION'], 'SECURE', 6) == 0)
      {
        include_once PATH_INCLUDE . '/functions_sha1.php';
        $headers = $data['METHOD'] == 'POST' ? $this->_secureHeaders : $this->_getHeadersSecure;        
        if(strstr($data['AUTHENTICATION'], '-RPC') != false)
        {
          $headers = str_replace('application/atom+xml', 'text/xml', $headers);
        }
        
        $created = gmdate("Y-m-d\TH:i:s\Z", $data['ISSUED']);
        $nonce = udf_sha1($created . ':' . 'FF_*_TEXT', true);
        $passwordDigest = base64_encode(udf_sha1($nonce .  $created . $data['PASSWORD'], true));
        
        $requestData = str_replace(array('{PATH}', '{USERNAME}', '{PASSWORDDIGEST}', '{NONCE}', '{CREATED}', '{CONTENTLENGTH}', '{USERAGENT}', '{HOST}', '{CONTENT}'),
                                    array($data['PATH'], $data['USERNAME'], $passwordDigest, $nonce, $created, strlen($data['CONTENT']), "FotoFlix CApiClient v{$this->_version}", $data['HOST'], $data['CONTENT']), 
                                    $headers);
      }
      else 
      if(strncmp($data['AUTHENTICATION'], 'BASIC', 5) == 0)
      {
        $headers = $data['METHOD'] == 'POST' ? $this->_basicHeaders : $this->_getHeadersBasic;
        if(strstr($data['AUTHENTICATION'], '-RPC') != false)
        {
          $headers = str_replace('application/atom+xml', 'text/xml', $headers);
        }
        
        $requestData = str_replace(array('{PATH}', '{CREDENTIALS}', '{CONTENTLENGTH}', '{USERAGENT}', '{HOST}', '{CONTENT}'),
                                    array($data['PATH'], base64_encode($data['USERNAME'] .':'.$data['PASSWORD']), strlen($data['CONTENT']), "FotoFlix CApiClient v{$this->_version}", $data['HOST'], $data['CONTENT']), 
                                    $headers);
      }
      
      //echo '<!-- jm -->' . $requestData . '<!-- // jm -->';
      
      //die();
      $fp = $data['PROTOCOL'] == 'SSL' ? fsockopen('ssl://' . $data['HOST'], 443) : fsockopen($data['HOST'], 80);
      //print_r($fp);

      if($fp)
      {
        fputs($fp, $requestData);
        
        //preg_match('/\s(\d+)/', fgets($fp, 1024), $responseCode);
        //$return = $responseCode[1];
        
        $i = 0;
        $return = '';
        while(!feof($fp))
        {
          $return .= fgets($fp, 1024);
          if($i > 100){ break; }
          $i++;
        }
      }
    }
    
    return $return;
  }
  
  function atomRequestContent($data)
  {
    if(isset($data['METHOD']))
    {
      $params = '';
      if(isset($data['BLOG_ID'])){ $params .= str_replace('{VALUE}', $data['BLOG_ID'], $this->_metaWeblogParam); }
      if(isset($data['USERNAME'])){ $params .= str_replace('{VALUE}', $data['USERNAME'], $this->_metaWeblogParam); }
      if(isset($data['PASSWORD'])){ $params .= str_replace('{VALUE}', $data['PASSWORD'], $this->_metaWeblogParam); }
      if(isset($data['TITLE']) || isset($data['CONTENT'])){ $params .= str_replace(array('{TITLE}','{CONTENT}'), array(htmlspecialchars($data['TITLE']), htmlspecialchars($data['CONTENT'])), $this->_metaWeblogParamContent); }
      if(isset($data['PUBLISH'])){ $params .= str_replace('{VALUE}', $data['PUBLISH'], $this->_metaWeblogParamBoolean); }
      
      return str_replace(array('{METHOD}', '{PARAMS}', '{ISSUED}'),
                  array($data['METHOD'], $params, gmdate("Y-m-d\TH:i:s\Z", NOW)), 
                  $this->_metaWeblog);
    }
    else
    {
      return str_replace(array('{TITLE}', '{ISSUED}', '{CONTENT}'),
                  array(str_replace('&', '&amp;', $data['TITLE']), gmdate("Y-m-d\TH:i:s\Z", NOW), str_replace('&', '&amp;', $data['CONTENT'])), 
                  $this->_blogger);
    }
  }
  
  function getHttpStatus($result = '')
  {
    preg_match('/\d{3}/', $result, $matches);
    return $matches[0];
  }
  
 /*
  *******************************************************************************************
  * Name
  *   _setAtomParams
  * Description
  *   private method to set enviornment for atom methods
  ******************************************************************************************
  */
  function _setAtomParams()
  {
    $this->_basicHeaders  = "POST {PATH} HTTP/1.1\r\n"
                          . "Authorization: Basic {CREDENTIALS}\r\n"
                          . "Content-type: application/atom+xml\r\n"
                          . "Content-length: {CONTENTLENGTH}\r\n"
                          . "UserAgent: {USERAGENT}\r\n"
                          . "Host: {HOST}\r\n"
                          . "Connection: Close\r\n\r\n"
                          . "{CONTENT}";
                          
    $this->_secureHeaders = "POST {PATH} HTTP/1.1\r\n"
                          . "Authorization: WSSE profile=\"UsernameToken\"\r\n"
                          . "X-WSSE: UsernameToken Username=\"{USERNAME}\", PasswordDigest=\"{PASSWORDDIGEST}\", Created=\"{CREATED}\", Nonce=\"{NONCE}\"\r\n"
                          . "Content-type: application/atom+xml\r\n"
                          . "Content-length: {CONTENTLENGTH}\r\n"
                          . "UserAgent: {USERAGENT}\r\n"
                          . "Host: {HOST}\r\n"
                          . "Connection: Close\r\n\r\n"
                          . "{CONTENT}";
    
    $this->_getHeadersSecure    = "GET {PATH} HTTP/1.1\r\n"
                                . "Authorization: WSSE profile=\"UsernameToken\"\r\n"
                                . "X-WSSE: UsernameToken Username=\"{USERNAME}\", PasswordDigest=\"{PASSWORDDIGEST}\", Created=\"{CREATED}\", Nonce=\"{NONCE}\"\r\n"
                                . "Content-type: application/atom+xml\r\n"
                                . "Content-length: {CONTENTLENGTH}\r\n"
                                . "UserAgent: {USERAGENT}\r\n"
                                . "Host: {HOST}\r\n"
                                . "Connection: Close\r\n\r\n";
                                
    $this->_getHeadersBasic = "GET {PATH} HTTP/1.1\r\n"
                            . "Authorization: BASIC {CREDENTIALS}\r\n"
                            . "Content-type: application/atom+xml\r\n"
                            . "Content-length: {CONTENTLENGTH}\r\n"
                            . "UserAgent: {USERAGENT}\r\n"
                            . "Host: {HOST}\r\n"
                            . "Connection: Close\r\n\r\n";
    
    $this->_blogger  = '<?xml version="1.0" encoding="UTF-8"?>'
                      . '<entry xmlns="http://purl.org/atom/ns#">'
                      . '<title mode="escaped" type="text/plain">{TITLE}</title>'
                      . '<issued>{ISSUED}</issued>'
                      . '<generator url="http://www.fotoflix.com/" version="' . $this->_version . '"></generator>'
                      . '<content type="text/html">'
                      . '<div xmlns="http://www.w3.org/1999/xhtml">{CONTENT}</div>'
                      . '</content>'
                      . '</entry>';
    
    $this->_metaWeblogParam = "   <param>\n"
                            . "     <value>\n"
                            . "       <string>{VALUE}</string>\n"
                            . "     </value>\n"
                            . "   </param>\n";
    $this->_metaWeblogParamContent  = "   <param>\n"
                                    . "     <value>\n"
                                    . "       <struct>\n"
                                    . "         <member>\n"
                                    . "           <name>title</name>\n"
                                    . "           <value>\n"
                                    . "             <string>{TITLE}</string>\n"
                                    . "           </value>\n"
                                    . "         </member>\n"
                                    . "         <member>\n"
                                    . "           <name>description</name>\n"
                                    . "           <value>\n"
                                    . "             <string>{CONTENT}</string>\n"
                                    . "           </value>\n"
                                    . "         </member>\n"
                                    . "       </struct>\n"
                                    . "     </value>\n"
                                    . "   </param>\n";
    $this->_metaWeblogParamBoolean  = "   <param>\n"
                                    . "     <value>\n"
                                    . "       <boolean>{VALUE}</boolean>\n"
                                    . "     </value>\n"
                                    . "   </param>\n";
    $this->_metaWeblog  = '<?xml version="1.0"?>'
                        . "<methodCall>\n"
                        . " <methodName>{METHOD}</methodName>\n"
                        . " <params>\n"
                        . "{PARAMS}"
                        . " </params>\n"
                        . "</methodCall>";
  }
  
 /*
  *******************************************************************************************
  * Constructor
  ******************************************************************************************
  */
  function CApiClient()
  {
    //$this->_version = 'FotoFlix CApiClient v1.0';
    $this->_version = '1.0';
    $this->_setAtomParams();
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
}
?>
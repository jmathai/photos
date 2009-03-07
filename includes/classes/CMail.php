<?php
 /*
  *******************************************************************************************
  * Name:  CMail.php
  *
  * General class for user interaction.
  * This class performs read-only functions on the database.
  *
  * Usage:
  *   include_once('CMail.php');
  *   $mail = new CMail;
  *   $mail->sendBatch($user_id);
  * 
  ******************************************************************************************
  */
class CMail
{
  function send($to = false, $subject = false, $message = false, $headers = false, $extra = false)
  {
    if(FF_MODE == 'live')
    {
      mail($to, $subject, $message, $headers);
    }
  }
  
  function validate($email)
  {
  	if(	preg_match("/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/",$email) || !preg_match("/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/",$email))
    {
      return false;
    }
    else
    {
      return true;
    }
  }
  
  function stripHtml($string)
  {
    $search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
                     "'<[\/\!]*?[^<>]*?>'si",          // Strip out HTML tags
                     "'([\r\n])[\s]+'",                // Strip out white space
                     "'&(quot|#34);'i",                // Replace HTML entities
                     "'&(amp|#38);'i",
                     "'&(lt|#60);'i",
                     "'&(gt|#62);'i",
                     "'&(nbsp|#160);'i",
                     "'&(iexcl|#161);'i",
                     "'&(cent|#162);'i",
                     "'&(pound|#163);'i",
                     "'&(copy|#169);'i",
                     "'&#(\d+);'e");                    // evaluate as php
    
    $replace = array ("",
                     "",
                     "\\1",
                     "\"",
                     "&",
                     "<",
                     ">",
                     " ",
                     chr(161),
                     chr(162),
                     chr(163),
                     chr(169),
                     "chr(\\1)");
    
    return preg_replace($search, $replace, $string);
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
      $inst         = new $class;
    }
    
    return $inst;
  }
  
  /*******************************************************************************************
  * Description
  *   Constructor
  *
  * Input
  *   None
  * Output
  *   Boolean
  *******************************************************************************************/
  function CMail()
  {
  }
}
?>
<?php
/*******************************************************************************************
 * Class Name:  CServerValidator
 *------------------------------------------------------------------------------------------
 * Mod History: Jaisen Mathai   6/13/2003
 *------------------------------------------------------------------------------------------
 * Performs Server Side Form Validation (generally used in conjunction with CFormValidator
 *
 * Usage:
 *   include('/export/home/jacor-common/classes/CServerValidator.php');
 *   $v = new CServerValidator($server_keys, $server_types, $server_messages, $HTTP_POST_VARS);
 *   $v -> validate();
 * 
 *******************************************************************************************/

class CServerValidator {
  /*******************************************************************************************
  * Description
  *   Class constructor
  *
  * Input
  *   $data_keys = (required) array of name of keys to be checked
  *   $data_types = (required) array of types
  *   $data_messages = (required) array of messages
  *   $data_values = (required)  array of values (HTTP_POST_VARS or HTTP_GET_VARS - generally)
  *******************************************************************************************/
  function CServerValidator( $data_keys = false, $data_types = false, $data_messages = false, $data_values = false ) {
    global $_validator_names, $_validator_types, $_validator_messages;
    if($data_keys) $this->data_keys = explode(',', $data_keys); else $this->data_keys = (array) explode(',', $_validator_names);
    if($data_types) $this->data_types = explode(',', $data_types); else $this->data_types = (array) explode(',', $_validator_types);
    if($data_messages) $this->data_messages = explode(',', $data_messages); else $this->data_messages = (array) explode(',', $_validator_messages);
    
    if($data_values)
    {
      $this->data_values = $data_values;
    }
    else
    {
      global $HTTP_POST_VARS;
      $this->data_values = $HTTP_POST_VARS;
    }
    
    $this->errorArray = array();
    
    if(count($this->datakeys) != count($this->data_names))
    {
      $this->errorArray[] = 'Datakeys and Datanames do not match in length.';
    }
  }
  
 /*******************************************************************************************
  * Description
  *   performs validation
  *
  * Input
  *
  * Output
  *   true/false = success/failure
  *******************************************************************************************/
  function validate(){
    foreach($this->data_keys as $v)
    {
      $type = current($this->data_types);
      next($this->data_types);
      $message = current($this->data_messages);
      next($this->data_messages);
      switch($type)
      {
        case 'length':
          if(!$this->_length($this->data_values[$v]))
          {
            $this->errorArray[] = stripslashes($message);
          }
          break;
        case 'numeric':
          if(!$this->_numeric($this->data_values[$v]))
          {
            $this->errorArray[] = stripslashes($message);
          }
          break;
          
        // custom
        default:
          if(strncmp($type,'exactlength',11) == 0)
          {
            if(!$this->_exactlength($this->data_values[$v], $type))
            {
              $this->errorArray[] = stripslashes($message);
            }
          }
      }
    }
  }

 /*******************************************************************************************
  * Description
  *   returns formatted string of all errors
  *
  * Input
  *   $header = (optional) header message
  *   false/string
  *******************************************************************************************/
  function display($header = 'Sorry but here\'s a list of errors we found in your form:')
  {
    if(count($this->errorArray) > 0)
    {
      $return =   $header;
      $return .=  "\n<ol>\n";
      foreach($this->errorArray as $v)
      {
        $return .= "<li>{$v}</li>\n";
      }
      $return .=  "</ol>\n";
    }
    else
    {
      $return = false;
    }
    
    return $return;
  }

 /*******************************************************************************************
  * Description
  *   *private* checks length of a string
  *
  * Input
  *   $str = (required) checks length of string
  *
  * Output
  *   true/false = success/failure
  *******************************************************************************************/
  function _length($str)
  {
    return strlen($str) == 0 ? false : true;
  }
  
  function _exactlength($str, $type)
  {
    preg_match('/([0-9]+)$/', $type, $matches);
    return strlen($str) != $matches[1] ? false : true;
  }
  
  function _numeric($val)
  {
    return !is_numeric($val) ? false : true;
  }
}
?>
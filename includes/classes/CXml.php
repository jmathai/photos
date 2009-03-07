<?php
class CXml
{
  function CXml() {
    //$this->_xml = '';
  }
  
  function loadXml($xml_data = '') {
    $this->_xml = trim($xml_data);
    return true;
  }
  
  function loadFile($xml_file = '') {
    if (!$fp = fopen($xml_file, 'r')) {
      return false;
    }
    $xml = '';
    while (!feof($fp)) {
      $xml .= fread($fp, 4096);
    }
    fclose($fp);
    
    $this->_xml = trim($xml);
    return true;
  }
  
  function toArray($white_space=1)
  {
    $vals = $index = $array = array();
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, $white_space);
    if (!xml_parse_into_struct($parser, $this->_xml, $vals, $index)) {
      die(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($parser)), xml_get_current_line_number($parser)));
    }
    
    xml_parser_free($parser);

    $i = 0; 

    $tagname = $vals[$i]['tag'];
    if (isset($vals[$i]['attributes'] )) {
      $array[$tagname]['@'] = $vals[$i]['attributes'];
    }
    else {
      $array[$tagname]['@'] = array();
    }

    $array[$tagname]["#"] = $this->_xml_depth($vals, $i);

    return $array;
  }
  
  function _xml_depth($vals, &$i)
  { 
    $children = array(); 

    if (isset($vals[$i]['value'])) {
      array_push($children, $vals[$i]['value']);
    }

    while (++$i < count($vals)) { 
      switch ($vals[$i]['type']) { 
        case 'open': 
          if (isset($vals[$i]['tag'])) {
            $tagname = $vals[$i]['tag'];
          }
          else {
            $tagname = '';
          }

          if (isset($children[$tagname])) {
            $size = sizeof($children[$tagname]);
          }
          else {
            $size = 0;
          }

          if (isset($vals[$i]['attributes'])) {
            $children[$tagname][$size]['@'] = $vals[$i]["attributes"];
          }

          $children[$tagname][$size]['#'] = $this->_xml_depth($vals, $i);
          break; 

        case 'cdata':
          array_push($children, $vals[$i]['value']); 
          break; 

        case 'complete': 
          $tagname = $vals[$i]['tag'];

          if (isset($children[$tagname])) {
            $size = sizeof($children[$tagname]);
          } else {
            $size = 0;
          }

          if(isset($vals[$i]['value'])) {
            $children[$tagname][$size]["#"] = $vals[$i]['value'];
          } else {
            $children[$tagname][$size]["#"] = '';
          }

          if (isset($vals[$i]['attributes'])) {
            $children[$tagname][$size]['@'] = $vals[$i]['attributes'];
          }
          break; 

        case 'close':
          return $children; 
          break;
      }
    } 

    return $children;
  }
  
  function traverse_xmlize($array, $arrName="array", $level=0)
  {
    foreach($array as $key => $val) {
      if (is_array($val)) {
        traverse_xmlize($val, $arrName . "[" . $key . "]", $level + 1);
      }
      else {
        $GLOBALS['traverse_array'][] = '$' . $arrName . '[' . $key . '] = "' . $val . "\"\n";
      }
    }
  
    return 1;
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
      $inst->_xml = '';
    }
    
    return $inst;
  }
}
?>

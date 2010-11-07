<?php
  /*
  * FotoFlix function file.
  * Includes random functions used throughout the site.
  * Alphabetized.
  */
  
  function decrypt($string)
  {
    $return = '';
    if($string !== false)
    {
      $td = mcrypt_module_open('tripledes', '', 'ecb', '');
      $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
      mcrypt_generic_init($td, ECOM_CC_ENCRYPT_KEY, $iv);
      
      $return = preg_replace('/\D/', '', mdecrypt_generic($td, base64_decode($string)));
      
      mcrypt_generic_deinit($td);
      mcrypt_module_close($td);
    }
    
    return $return;
  }
  
  function directory($dir)
  {
    $return = array();
    $dh = dir($dir);
    
    while(($entry = $dh->read()) !== false)
    {
      if(!in_array($entry, array('.','..')) && is_file($dir . '/' . $entry))
      {
        $return[] = $entry;
      }
    }
    
    $dh->close();
    
    return $return;
  }
  
  function dynamicImage($src, $key, $width, $height)
  {
    $src = PATH_FOTO . $src;
    $dtmp = explode('/', $src);
    $dtmp[2] = 'custom';
    $dirname  = dirname(implode('/', $dtmp));
    $filename = basename($src);
    $filenamePrefix = substr($filename, 0, strrpos($filename, '.'));
    $filenameSuffix = substr($filename, strrpos($filename, '.')+1);
    
    $filenameReturn = $filenamePrefix . '_' . intval($width) . '_' . intval($height) . '.' . $filenameSuffix;
    
    $retval = FF_STATIC_URL . $dirname . '/' . $filenameReturn . '?' . $key;
    
    return $retval;
  }
  
  function dynamicImageLock($src, $key, $rotation, $srcWidth, $srcHeight, $destWidth, $destHeight)
  {
    $src = PATH_FOTO . $src;
    
    // adjust the source dimensions so that the ratios are accurate
    // without this the "lock" won't work if the photo has been rotated
    if($rotation == 90 || $rotation == 270)
    {
      $tmp = $srcWidth;
      $srcWidth = $srcHeight;
      $srcHeight = $tmp;
    }
    
    $srcHeight = max($srcHeight, 1); // avoid division by zero error
    
    $srcRatio = $srcWidth / $srcHeight;
    $destRatio= $destWidth / $destHeight;
    
    if($destRatio > $srcRatio) // height is maxed
    {
      $factor = floatval($destHeight / $srcHeight);
      $finalHeight= $destHeight;
      $finalWidth = ceil($srcWidth * $factor);
    }
    else
    if($destRatio < $srcRatio) // width is maxed
    {
      $factor = floatval($destWidth / $srcWidth);
      $finalWidth = $destWidth;
      $finalHeight= ceil($srcHeight * $factor);
    }
    else // maintain aspect ratio
    {
      $finalWidth = $destWidth;
      $finalHeight= $destHeight;
    }
    
    $dtmp = explode('/', $src);
    $dtmp[2] = 'custom';
    $dirname  = dirname(implode('/', $dtmp));
    $filename = basename($src);
    
    $filenamePrefix = substr($filename, 0, strrpos($filename, '.'));
    $filenameSuffix = substr($filename, strrpos($filename, '.')+1);
    
    $filenameReturn = $filenamePrefix . '_' . intval($finalWidth) . '_' . intval($finalHeight) . '.' . $filenameSuffix;
    
    $retval = FF_STATIC_URL . $dirname . '/' . $filenameReturn . '?' . $key;
    
    // this image should not truly be resized...the 2nd, 3rd and 4th parameters should reflect the true image size
    if($srcWidth < $destWidth && $srcHeight < $destHeight)
    {
      $finalWidth = $srcWidth;
      $finalHeight= $srcHeight;
    }
    
    return array($retval, $finalWidth, $finalHeight, ' width="' . $finalWidth . '" height="' . $finalHeight . '" ');
  }
  
  // Stopwatch function to time events
  function echo_stopwatch()
  {
    global $stop_watch;
    
    echo "\n\n<!--\n";
    echo "\nTiming ***************************************************\n";
    
    $total_elapsed = 0;
    list($usec, $sec) = explode(" ",$stop_watch['Start']);
    $t_end = ((float)$usec + (float)$sec);
    
    foreach( $stop_watch as $key => $value )
    {
       list($usec, $sec) = explode(" ",$value);
       $t_start = ((float)$usec + (float)$sec);
    
       $elpased = abs($t_end - $t_start);
       $total_elapsed += $elpased;
    
       echo str_pad($key, 20, ' ', STR_PAD_LEFT) . ": " . number_format($elpased,3) . '  ' . number_format($total_elapsed,3) . "\n";
       $t_end = $t_start;
    }
    
    echo "\n";
    echo str_pad("Elapsed time", 20, ' ', STR_PAD_LEFT) . ": " . number_format($total_elapsed,3) . "\n";
    echo "\n-->";
  }
  
  // used to generate html or plain text footers for emails which are sent out
  function emailFooter($key = '', $code = '', $mode = 'text')
  {
    if($mode == 'text')
    {
      return    "If you no longer wish to recieve emails about updates on Photagious then go to the link below.\n"
              . "Our physical mailing address is 520 Madison Ave. Covington, KY 41011\n"
              . 'http://' . FF_SERVER_NAME . "/?action=home.unsubscribe.act&key={$key}&ec_id={$code}";
    }
    else 
    {
      return    'Click <a href="http://' . FF_SERVER_NAME . "/?action=home.unsubscribe.act&key={$key}&ec_id={$code}\">here</a> if you no longer wish to recieve emails about updates on Photagious.\n"
              . "Our physical mailing address is 520 Madison Ave. Covington, KY 41011";
    }
  }
  
  function encrypt($string = false)
  {
    $return = '';
    if($string !== false)
    {
      $td = mcrypt_module_open('tripledes', '', 'ecb', '');
      $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
      mcrypt_generic_init($td, ECOM_CC_ENCRYPT_KEY, $iv);
      
      $return = base64_encode(mcrypt_generic($td, $string));
      
      mcrypt_generic_deinit($td);
      mcrypt_module_close($td);
    }
    
    return $return;
  }
  
  // error handling
  function error($errno, $errstr, $errfile, $errline)
  {
    switch ($errno)
    {
      case E_ERROR:
      case E_PARSE:
        header('Location: /?action=error');
        die();
        break;
    }
    
    $php_errormsg = "ERROR: {$errno}<br/>\n{$errstr}<br/>\n({$errfile} -> {$errline})<br/>\n{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}<br/>\n";
    //mail('errors@fotoflix.com', $errstr . ' - ' . basename($errfile), "ERROR: {$errno}\n{$errstr}\n({$errfile} -> {$errline})\n{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}\n");
    
    switch(FF_MODE)
    {
      case 'live':
        if($errno < E_NOTICE)
        {
          if(!isset($GLOBALS['php_error_message']))
          {
            echo '<span class="italic">There was a problem on this page.<br/><a href="/contactus/">Contact us</a> if this problem persists.</span>';
          }
          
          echo "<!--\n";
          echo $php_errormsg;
          echo "-->\n";
        }
        break;
      default:
        if($errno < E_NOTICE)
        {
          echo "<br/>\n";
          echo $php_errormsg;
          echo "\n";
          break;
        }
    }
    
    $GLOBALS['php_error_message'] = true;
  }
  
  function footer($logged_in = false, $username = '')
  {
    global $logged_in;
    echo '<div id="footer">
            <div style="margin-top:5px;">&copy; ' . date('Y', NOW) . ' Photagious / Jaisen Mathai.  All Rights Reserved.&nbsp;&nbsp;</div>
          </div>
          <!-- close footer -->';
  }
  
  // apply htmlspecialchars to each element of an array (single dimension)
  function htmlSafeArray(&$array)
  {
    foreach($array as $k => $v)
    {
      $array[$k] = htmlspecialchars($v);
    }
    
    return $array;
  }
  
  // decode a json string into a native datatype
  function jsonDecode($data)
  {
    return json_decode($data, true);
  }
  
  // encode a native datatype to a json string
  function jsonEncode($data)
  {
    return json_encode($data);
  }
  
  // 
  function linkUserPage($username = false, $text = false, $appendUrl = false)
  {
    if($username !== false)
    {
      if($appendUrl === false)
      {
        return '<a href="/users/' . $username . '/" title="view ' . $username . '\'s personal page">' . ($text === false ? $username : $text) . '</a>';
      }
      else
      {
        return '<a href="/users/' . $username . '/' . $appendUrl . '/" title="view ' . $username . '\'s personal page">' . ($text === false ? $username : $text) . '</a>';
      }
    }
    else
    {
      return '';
    }
  }
  
  // return new line character - ascii 10
  function nl()
  {
    return chr(10);
  }
  
  function numberWord($number)
  {
    $table = array('zero','one','two','three','four','five','six','seven','eight','nine','ten');
    if(isset($table[$number]))
    {
      return $table[$number];
    }
    else
    {
      return $number;
    }
  }
  
  // return options for countries
  function optionCountries($selected = '', $omitted = array())
  {
    $array_countries  = array(
                          'USA', 'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belgium', 'Belize', 'Belarus', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chile', "People's Rep. of China", 'Christmas Island', 'Colombia', 'Comoros', 'Congo', 'Democratic Republic of the Congo', 'Cook Islands', 'Costa Rica', "Cote D'Ivoire", 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands', 'Fiji', 'Finland', 'France', 'French Guiana', 'French Polynesia', 'Gabon', 'Gambia', 'Germany', 'Georgia', 'S. Georgia and the S. Sandwich Is.', 'Ghana', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Kitts and Nevis', 'North Korea', 'South Korea', 'Kyrgyzstan', 'Kuwait', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg',
                          'Macau', 'Macedonia', 'Madagascar', 'Malaysia', 'Maldives', 'Mali', 'Marshall Islands', 'Malta', 'Northern Mariana Islands', 'Malawi', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia', 'Moldova', 'Mongolia', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'Netherlands Antilles', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norway',
                          'Oman', 'Pakistan', 'Palau', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Pitcairn Island', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Reunion', 'Romania', 'Russia', 'Rwanda', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa-American', 'Samoa-Western', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'USA', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Virgin Islands', 'Vietnam', 'Western Sahara', 'Yemen', 'Yugoslavia', 'Zambia', 'Zimbabwe', 'APO', 'FPO', 'Other', 'Bouvet Island', 'British Indian Ocean Territory', 'Chad', 'Cocos(Keeling) Islands', 'East Timor', 'Faroe Islands', 'French Southern Territories', 'Gibraltar', 'Heard and McDonald Islands', 'Monaco', 'Norfolk Island', 'Saint Helena', 'Saint Pierre and Miquelon', 'Svalbard and Jan Mayen Islands', 'Tokelau', 'Turks and Caicos Islands', 'United States Minor Outlying Islands', 'Wallis and Futuna', 'British Virgin Islands'
                        );
    
    $retval = '';
    foreach( $array_countries as $k => $v )
    {
      if(!in_array($v, $omitted))
      {
        $selected_text = ($v == $selected) ? ' SELECTED' : '';
        $retval .= "<option value=\"{$v}\" {$selected_text}>" . str_mid($v, 25) . "</option>\n";
      }
    }
    
    return $retval;
  }
  
  // return options for states
  function optionStates($selected = '', $omitted = array())
  {
    $array_states     = array(
                          'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana',
                          'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 
                          'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'DC' => 'Washington D.C.', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming', 'XX' => 'Outside of USA'
                        );
    
    $retval = '';
    
    foreach( $array_states as $k => $v )
    {
      if(!in_array($k, $omitted) && !in_array($v, $omitted))
      {
        $selected_text = ($k == $selected || $v == $selected) ? ' SELECTED' : '';
        $retval .= "<option value=\"{$k}\" {$selected_text}>{$v}</option>\n";
      }
    }
    
    return $retval;
  }
  
  // check the permission of two binary values
  function permission($permission = 0, $mask = 0)
  {
    $permission = intval($permission);
    $mask = intval($mask);
    return ($permission & $mask) == $mask;
  }
  
  // generate a random 32 character string
  function randomString($strlen = 32)
  {
    $length = intval($strlen);
    $rand_string = md5(uniqid(mt_rand(0,9), true));
    $rand_start  = mt_rand(0, 32 - $length);
    
    $return = substr($rand_string, $rand_start, $length);
    
    return $return;
  }
  
  // determine additional rotation based of existing rotation
  function rotation($original = 0, $additional = 0)
  {
    $final = $original + $additional;
    
    if($final == 360)
    {
      $final = 0;
    }
    else
    if($final > 360)
    {
      $final = $final - 360;
    }
    
    return (int)$final;
  }
  
  function samplesNavigation($links = array())
  {
    $linkMap = array(
              'samples' => array('http://' . FF_SERVER_NAME . '/?action=home.samples', 'Samples'),
              'demo'    => array('http://' . FF_SERVER_NAME . '/?action=home.samples&subaction=demoSlideshow', 'Demo Slideshow Editor'),
              'tour'    => array('http://' . FF_SERVER_NAME . '/home/tour/', 'Take the Tour'),
              'trial'   => array('https://' . FF_SERVER_NAME . '/?action=home.registration_form_b', 'Free Trial'),
              'features'=> array('http://' . FF_SERVER_NAME . '/?action=home.samples&subaction=featureList', 'Feature List'),
              'printing'=> array('http://' . FF_SERVER_NAME . '/?action=home.samples&subaction=printing', 'Printing'),
              'aboutus' => array('http://' . FF_SERVER_NAME . '/aboutus/', 'About Us')
              );
  
    $retval = '<table width="100%" height="32" border="0" cellpadding="0" cellspacing="0" align="center">
                <tr>
                  <td align="center" background="images/samples/nav_bg.gif" bgcolor="#EBE9ED">';
    
    foreach($links as $v)
    {
      $retval .= '<a href="' . $linkMap[$v][0] . '" class="f_black bold f_9">' . $linkMap[$v][1] . '</a> | ';
    }
    
    $retval = substr($retval, 0, -3);
    
    $retval .='   </td>
                </tr>
              </table>';
    
    return $retval;
  }
  
  // strip a string of any illegal characters and replace them with safe values
  // optionally parse string (i.e. hyperllink urls)
  function sanitize($str = '', $args = array())
  {
    if(isset($args['PRESERVE_ANCHORS']))
    {
      $str = preg_replace('/<a href="(.*?)">.*?<\/a>/s', '\1', $str);
    }
    
    $search = array ('@<script[^>]*?>.*?</script>@si', // Strip out javascript
                     '@<[\/\!]*?[^<>]*?>@si',          // Strip out HTML tags
                     //'@([\r\n])[\s]+@',                // Strip out white space
                     '@&(quot|#34);@i',                // Replace HTML entities
                     '@&(amp|#38);@i',                 // ampersand
                     '@&(lt|#60);@i',                  // less than
                     '@&(gt|#62);@i',                  // greater than
                     '@&(nbsp|#160);@i',               // non breaking space
                     '@&(iexcl|#161);@i',
                     '@&(cent|#162);@i',
                     '@&(pound|#163);@i',
                     '@&(copy|#169);@i',
                     '@&#(\d+);@e');                    // evaluate as php
    
    $replace = array ('',
                     '',
                     //'\1',
                     '"',
                     '&',
                     '<',
                     '>',
                     ' ',
                     chr(161),
                     chr(162),
                     chr(163),
                     chr(169),
                     'chr(\1)');
    
    $str = preg_replace($search, $replace, $str);
    
    if(isset($args['PRESERVE_ANCHORS']))
    {
      // hacked this by adding a space at the end of the string need to revise and trim the right side
      if(isset($args['ANCHOR_TARGET']))
      {
        $str = trim(preg_replace('/(http:\/\/.*)\s/sU', '<a href="\1" target="' . $args['ANCHOR_TARGET'] . '">\1</a> ', $str . ' '));
      }
      else
      {
        $str = trim(preg_replace('/(http:\/\/.*)\s/sU', '<a href="\1">\1</a> ', $str . ' '));
      }
    }
    
    return $str;
  }
  
  // return a fixed length string and preserve the beginning and the end and put dots in the middle
  function str_mid($str = '', $limit = 10)
  {
    if(strlen($str) > $limit)
    {
      $limit_half = $limit / 2;
      $mid_l = $mid_r = ceil($limit_half * .75);
      $sep = ceil($limit_half / 3);
      $return = substr($str, 0, $mid_l) . str_repeat('.', $sep) . substr($str, (-1 * $mid_r));
    }
    else
    {
      $return = $str;
    }
    return $return;
  }
  
  // eliminates blank entries in a tag array
  function tagsize($weight, $step, $sizes = array(12, 16, 20,  24, 28))
  {
    if($weight < $step)
    {
      $fontSize = $sizes[0];
    }
    else
    if($weight < ($step * 2))
    {
      $fontSize = $sizes[1];
    }
    else
    if($weight < ($step * 3))
    {
      $fontSize = $sizes[2];
    }
    else
    if($weight < ($step * 4))
    {
      $fontSize = $sizes[3];
    }
    else
    {
      $fontSize = $sizes[4];
    }
    
    return $fontSize;
  }
  
  // eliminates blank entries in a tag array
  function tagtrim($value)
  {
    return $value == '' ? false : true;
  }
  
  // lowercase values in an array
  function tagwalk(&$value, $key)
  {
    $value = preg_replace('/\W/', '', trim($value));
  }
  
  function trackSignup($userId)
  {
    $retval = '';
    include_once PATH_CLASS . '/CUser.php';
    include_once PATH_CLASS . '/CUserManage.php';
    
    $u =& CUser::getInstance();
    $um=& CUserManage::getInstance();
    
    $prefs = $u->prefs($userId);
    
    // enter shareasale and google tracking if user has uploaded photos
    if(intval($prefs['HAS_UPLOADED']) > 0 && empty($prefs['SIGNUP_TRACKED']))
    {
      $retval = '
                  <!-- Google Code for signup Conversion Page -->
                  <script language="JavaScript" type="text/javascript">
                  <!--
                  var google_conversion_id = 1061541346;
                  var google_conversion_language = "en_US";
                  var google_conversion_format = "1";
                  var google_conversion_color = "FFFFFF";
                  if (1) {
                    var google_conversion_value = 1;
                  }
                  var google_conversion_label = "signup";
                  //-->
                  </script>
                  <script language="JavaScript" src="http://www.googleadservices.com/pagead/conversion.js">
                  </script>
                  <noscript>
                  <img height=1 width=1 border=0 src="http://www.googleadservices.com/pagead/conversion/1061541346/imp.gif?value=1&label=signup&script=0">
                  </noscript>
                  
                  <!-- shareasale conversion tracking code -->
                  <img src="https://shareasale.com/sale.cfm?amount=0.00&tracking=' . $_USER_ID . '&transtype=LEAD&merchantID=12918" width="1" height="1" />
      ';
      $um->setPrefs($userId, array('SIGNUP_TRACKED' => 1));
    }
    
    return $retval;
  }
  
  // send serialized data via email - used for debugging
  function trap($data = '')
  {
    if(FF_MODE != 'local')
    {
      $message = serialize($data);
      mail('jaisen@jmathai.com', 'Trap', $message);
    }
  }
  
  function xmlUnSafe($str)
  {
    return str_replace(array('&amp;','&lt;','&gt;'), array('&','<','>'), $str);
  }
?>

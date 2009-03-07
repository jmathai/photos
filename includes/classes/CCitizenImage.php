<?php
/*
*******************************************************************************************
* Name:  CCitizenImage.php
*
* Class to interface with Citizen Image API
*
* Usage: $ci =& CCitizenImage::getInstance();
*
******************************************************************************************
*/

class CCitizenImage
{

  private $username;
  private $password;
  private $firstname;
  private $lastname;
  private $emailaddress;
  private $paymenttype;
  private $streetaddress1;
  private $streetaddress2;
  private $city;
  private $state;
  private $zip;
  private $country;
  private $acceptagreement;
  private $experiencelevel;
  private $publicname;
  private $website;
  private $biography;
  private $equipment;
  private $schools;
  private $courses;
  private $specialties;
  private $accepthostingagreement;

  public function setUsername($username)
  {
    $this->username = $username;
  }

  public function getUsername()
  {
    return $this->username;
  }

  public function setPassword($password)
  {
    $this->password = $password;
  }

  public function getPassword()
  {
    return $this->password;
  }

  public function setFirstname($firstname)
  {
    $this->firstname = $firstname;
  }

  public function getFirstname()
  {
    return $this->firstname;
  }

  public function setLastname($lastname)
  {
    $this->lastname = $lastname;
  }

  public function getLastname()
  {
    return $this->lastname;
  }

  public function setEmailaddress($emailaddress)
  {
    $this->emailaddress = $emailaddress;
  }

  public function getEmailaddress()
  {
    return $this->emailaddress;
  }

  public function setPaymenttype($paymenttype)
  {
    $this->paymenttype = $paymenttype;
  }

  public function getPaymenttype()
  {
    return $this->paymenttype;
  }

  public function setStreetaddress1($streetaddress1)
  {
    $this->streetaddress1 = $streetaddress1;
  }

  public function getStreetaddress1()
  {
    return $this->streetaddress1;
  }

  public function setStreetaddress2($streetaddress2)
  {
    $this->streetaddress2 = $streetaddress2;
  }

  public function getStreetaddress2()
  {
    return $this->streetaddress2;
  }

  public function setCity($city)
  {
    $this->city = $city;
  }

  public function getCity()
  {
    return $this->city;
  }

  public function setState($state)
  {
    $this->state = $state;
  }

  public function getState()
  {
    return $this->state;
  }

  public function setZip($zip)
  {
    $this->zip = $zip;
  }

  public function getZip()
  {
    return $this->zip;
  }

  public function setCountry($country)
  {
    $this->country = $country;
  }

  public function getCountry()
  {
    return $this->country;
  }

  public function setAcceptagreement($acceptagreement)
  {
    $this->acceptagreement = $acceptagreement;
  }

  public function getAcceptagreement()
  {
    return $this->acceptagreement;
  }

  public function setExperiencelevel($experiencelevel)
  {
    $this->experiencelevel = $experiencelevel;
  }

  public function getExperiencelevel()
  {
    return $this->experiencelevel;
  }

  public function setPublicname($publicname)
  {
    $this->publicname = $publicname;
  }

  public function getPublicname()
  {
    return $this->publicname;
  }

  public function setWebsite($website)
  {
    $this->website = $website;
  }

  public function getWebsite()
  {
    return $this->website;
  }

  public function setBiography($biography)
  {
    $this->biography = $biography;
  }

  public function getBiography()
  {
    return $this->biography;
  }

  public function setEquipment($equipment)
  {
    $this->equipment = $equipment;
  }

  public function getEquipment()
  {
    return $this->equipment;
  }

  public function setSchools($schools)
  {
    $this->schools = $schools;
  }

  public function getSchools()
  {
    return $this->schools;
  }

  public function setCourses($courses)
  {
    $this->courses = $courses;
  }

  public function getCourses()
  {
    return $this->courses;
  }

  public function setSpecialties($specialties)
  {
    $this->specialties = $specialties;
  }

  public function getSpecialties()
  {
    return $this->specialties;
  }

  public function setAccepthostingagreement($accepthostingagreement)
  {
    $this->accepthostingagreement = $accepthostingagreement;
  }

  public function getAccepthostingagreement()
  {
    return $this->accepthostingagreement;
  }


  /*
  *******************************************************************************************
  * Name
  *   template
  * Description
  *   This method can be deleted...it's here as a template method
  * Output
  *   None
  *******************************************************************************************
  */
  function template()
  {
    // Execute an insert/update/delete query
    //    $this->dbh->execute(/* sql */);
    // Retrieve an auto increment for inserts (must appear immediately after insert query)
    //    $insertId = $this->dbh->insert_id();

    // Execute a select query to retrieve multiple rows
    //    $resultArray = $this->dbh->query_all(/* sql */);
    //    foreach($resultArray as $v)
    //    {
    //      echo $v['databaseField'];
    //    }

    // Execute a select query to retrieve a single row
    //    $resultArray = $this->dbh->query_first(/* sql */);
    //    echo $resultArray['databaseField'];
  }

  /*
  * Name
  *   save
  * Description
  *   Method to log in and register a user
  * Output
  *   Array
  */
  public function save()
  {
    $retval = array('response' => '', 'code' => '', 'text' => '');
    $rand = rand(0, 1000);
    //Build xml
    $XML = $this->BuildXml();
    
    //login to CI Server
    //build login xml
    $XMLLogin = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $XMLLogin .= "<credentials>\n";
    $XMLLogin .= "  <username>" . CI_USER . "</username>\n";
    $XMLLogin .= "  <password>" . CI_PASS . "</password>\n";
    $XMLLogin .= "</credentials>\n";

    $request = CI_SERVER . '/service_login.do';
    $session = curl_init();

    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($session, CURLOPT_POST, true);
    curl_setopt($session, CURLOPT_POSTFIELDS, $XMLLogin);
    curl_setopt($session, CURLOPT_COOKIEJAR, PATH_HOMEROOT . PATH_TMP . '/ci_' . $rand);
    curl_setopt($session, CURLOPT_URL, $request);

    $response = curl_exec($session);
    
    $response = simplexml_load_string($response);
    $response = $response->attributes();

    /*
    * simplexml_load_string makes no sense.
    * It's nodes are all simplexml objects that can be treated as simple values
    */
    if(isset($response->message[0]))
    {
      $retval['response'] = $response['result-code'];
      $retval['code']     = $response->message[0]->code;
      $retval['text']     = $response->message[0]->text;
    }
    else
    if($response['result-code'] == 'success')
    {
      curl_setopt($session, CURLOPT_HEADER, false);
      curl_setopt($session, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
      curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($session, CURLOPT_POST, true);
      curl_setopt($session, CURLOPT_POSTFIELDS, $XML);
      curl_setopt($session, CURLOPT_COOKIEFILE, PATH_HOMEROOT . PATH_TMP . '/ci_' . $rand);
      curl_setopt($session, CURLOPT_URL, CI_SERVER . '/service_register_user.do');

      $response = curl_exec($session);
      
      $response = simplexml_load_string($response);
      $response = $response->attributes();
      
      if(isset($response->message[0]))
      {
        $retval['response'] = $response['result-code'];
        $retval['code']     = $response->message[0]->code;
        $retval['text']     = $response->message[0]->text;
      }
      else
      {
        $retval['response'] = $response['result-code'];
      }
    }
    else
    {
      $retval['response'] = 'failure';
      $retval['code']     = 'unnkown_error';
      $retval['text']     = 'Call Feargall';
    }

    curl_close($session);

    return $retval;
  }

  public function createMemberMethods() {
    foreach($this as $key => $value) {
      print "public function set".ucwords($key)."(\$$key)\n";
      print "{\n";
      print "  \$this->$key = \$$key;\n";
      print "}\n\n";

      print "public function get".ucwords($key)."()\n";
      print "{\n";
      print "  return \$this->$key;\n";
      print "}\n\n";
    }
  }

  public function createMemberXml() {
    foreach($this as $key => $value) {
      print "\$strXml .= \"<$key>\".\$this->$key.\"</$key>\\n\";\n";
    }
  }

  public function createMemberSet() {
    foreach($this as $key => $value) {
      print "\$obj->set".ucwords($key)."('npond');\n";
    }
  }
  
  /*
  *******************************************************************************************
  * Name
  *   accountExists
  * Description
  *   Whether a user has already set up a Citizen Image Account
  * Input
  *  uId - user id
  * Output
  *   bool
  ******************************************************************************************
  */
  function accountExists($uId = false)
  {
    if($uId !== false)
    {
      $uId = $this->dbh->sql_safe($uId);

      $sql = 'SELECT COUNT(*) AS CNT FROM user_ci_credentials WHERE ucc_u_id = ' . $uId . ' ';
      $rs = $this->dbh->query_first($sql);

      if($rs['CNT'] > 0)
      {
        return true;
      }
      else
      {
        return false;
      }
    }
  }
  
 /*******************************************************************************************
  * Description
  *   Method to search the citizen image table
  *
  * Input
  *   $params
  *     U_ID - user id
  *     UP_ID - photo id
  *     STATUS - status
  *
  * Output
  *   array
  *******************************************************************************************/
  function search($params = false)
  {
    $return = false;
    if($params !== false)
    {
      //$params = $this->dbh->asql_safe($params); removed this and put it inline because date() function requires raw value
      $sql = 'SELECT ucp_id AS UCI_ID, ucp_u_id AS UCI_U_ID, ucp_up_id AS UCI_U_ID, '
           . 'ucp_title AS UCI_TITLE, ucp_description AS UCI_DESCRIPTION, ucp_category AS UCI_CATEGORY, ucp_subCategory AS UCI_SUBCATEGORY, '
           . 'ucp_release AS UCI_RELEASE, ucp_timestamp AS UCI_TIMESTAMP, ucp_country AS UCI_COUNTRY, ucp_state AS UCI_STATE, '
           . 'ucp_city AS UCI_CITY, ucp_keywords AS UCI_KEYWORDS, '
           . 'ucp_dateCreated AS UCI_DATECREATED, ucp_status AS UCI_STATUS, '
           . 'up_original_path AS UCI_IMAGE, ucc_username AS UCI_USERNAME, ucc_password AS UCI_PASSWORD '
           . 'FROM (user_ci_photos INNER JOIN user_fotos ON ucp_up_id = up_id) '
           . 'INNER JOIN user_ci_credentials ON ucp_u_id = ucc_u_id '
           . 'WHERE 1 ';
 
      if(isset($params['U_ID']))
      {
        $sql .= 'AND ucp_u_id = ' . $this->dbh->sql_safe($params['U_ID']) . ' ';
      }
 
      if(isset($params['UP_ID']))
      {
        $sql .= 'AND ucp_up_id = ' . $this->dbh->sql_safe($params['UP_ID']) . ' ';
      }
      
      if(isset($params['TIMESTART']))
      {
        $sql .= 'AND ucp_dateCreated >= ' . $this->dbh->sql_safe(date('Y-m-d H:i:s', $params['TIMESTART'])) . ' ';
      }
      
      if(isset($params['TIMEEND']))
      {
        $sql .= 'AND ucp_dateCreated <= ' . $this->dbh->sql_safe(date('Y-m-d H:i:s', $params['TIMEEND'])) . ' ';
      }
      
      if(isset($params['STATUS']))
      {
        $sql .= 'AND ucp_status = ' . $this->dbh->sql_safe($params['STATUS']) . ' ';
      }
      
      $return = $this->dbh->query_all($sql);
    }
 
    return $return;
  }
  
  /*
  *******************************************************************************************
  * Name
  *   processed
  * Description
  *   Marks a photo as processed
  * Input
  *  array - photoId
  * Output
  *   none
  ******************************************************************************************
  */
  function processed($id = 0)
  {
    $id = intval($id);
    $this->dbh->execute("UPDATE user_ci_photos SET ucp_status = 'processed' WHERE ucp_id = {$id}");
  }

  /*
  *******************************************************************************************
  * Name
  *   saveCredentials
  * Description
  *   Saves the user's citizen image credentials
  * Input
  *  array - USER_ID, USERNAME, PASSWORD
  * Output
  *   none
  ******************************************************************************************
  */
  function saveCredentials($params = false)
  {
    if($params !== false)
    {
      $params = $this->dbh->asql_safe($params);

      $sql = 'INSERT INTO user_ci_credentials (ucc_u_id, ucc_username, ucc_password) '
           . 'VALUES (' . $params['USER_ID'] . ',' . $params['USERNAME'] . ',' . $params['PASSWORD'] . ') ';

      $this->dbh->execute($sql);
    }
  }
  
  private function BuildXml()
  {
    $strXml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $strXml .= "<citizen-image-registration-data>\n";
    $strXml .= "  <photographer>\n";

    $strXml .= "    <username>".$this->username."</username>\n";
    $strXml .= "    <password>".$this->password."</password>\n";
    $strXml .= "    <first-name>".$this->firstname."</first-name>\n";
    $strXml .= "    <last-name>".$this->lastname."</last-name>\n";
    $strXml .= "    <email-address>".$this->emailaddress."</email-address>\n";
    $strXml .= "    <payment-type>".$this->paymenttype."</payment-type>\n";
    $strXml .= "    <street-address1>".$this->streetaddress1."</street-address1>\n";
    if ($this->streetaddress2 != "")
      $strXml .= "    <street-address2>".$this->streetaddress2."</street-address2>\n";
    $strXml .= "    <city>".$this->city."</city>\n";
    $strXml .= "    <state>".$this->state."</state>\n";
    if ($this->zip != "")
      $strXml .= "    <zip-code>".$this->zip."</zip-code>\n";
    $strXml .= "    <country>".$this->zip."</country>\n";
    $strXml .= "    <accept-membership-agreement>".$this->acceptagreement."</accept-membership-agreement>\n";
    $strXml .= "    <experience-level>".$this->experiencelevel."</experience-level>\n";
    $strXml .= "    <public-name>".$this->publicname."</public-name>\n";
    if ($this->website != "")
      $strXml .= "    <web-site>".$this->website."</web-site>\n";
    $strXml .= "    <biography>".$this->biography."</biography>\n";
    $strXml .= "    <equipment>\n";
    foreach ($this->equipment as $camera)
    {
      $strXml .= "      <camera>\n";
      $strXml .= "        <make>".$camera->getMake()."</make>\n";
      $strXml .= "        <model>".$camera->getModel()."</model>\n";
      $strXml .= "        <resolution>".$camera->getResolution()."</resolution>\n";
      if ($camera->getSpecial() != "")
        $strXml .= "        <special>".$camera->getSpecial()."</special>\n";
      $strXml .= "      </camera>\n";
    }
    $strXml .= "    </equipment>\n";
    if ($this->schools != "")
      $strXml .= "    <schools>".$this->schools."</schools>\n";
    if ($this->courses != "")
      $strXml .= "    <courses>".$this->courses."</courses>\n";
    if ($this->specialties != "")
      $strXml .= "    <specialties>".$this->specialties."</specialties>\n";
    $strXml .= "    <accept-hosting-agreement>".$this->accepthostingagreement."</accept-hosting-agreement>\n";
    $strXml .= "  </photographer>\n";
    $strXml .= "</citizen-image-registration-data>\n";

    return $strXml;
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

  function CCitizenImage()
  {
  }
}

/*
*******************************************************************************************
* Name:  CCitizenImageCamera
*
* Class to provide camera information to the Citizen Image API
*
* Usage: $ci =& CCitizenImageCamera::getInstance();
*
******************************************************************************************
*/

class CCitizenImageCamera
{
  private $make;
  private $model;
  private $resolution;
  private $special;

  public function setMake($make)
  {
    $this->make = $make;
  }

  public function getMake()
  {
    return $this->make;
  }

  public function setModel($model)
  {
    $this->model = $model;
  }

  public function getModel()
  {
    return $model->model;
  }

  public function setResolution($resolution)
  {
    $this->resolution = $resolution;
  }

  public function getResolution()
  {
    return $this->resolution;
  }

  public function setSpecial($special)
  {
    $this->special = $special;
  }

  public function getSpecial()
  {
    return $this->special;
  }

  public function __construct($make, $model, $resolution, $special)
  {
    $this->make = $make;
    $this->model = $model;
    $this->resolution = $resolution;
    $this->special = $special;
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



/*
*******************************************************************************************
* Name:  CCIImage
*
* Class to provide image information to the Citizen Image API
*
* Usage: $ci =& CCIImage::getInstance();
*
******************************************************************************************
*/

class CCIImage
{
  private $imagedata;
  private $imagehash;
  private $title;
  private $description;
  private $category;
  private $release;
  private $subcategory;
  private $location;
  private $datetime;
  private $timezone;
  private $licensetype;
  private $keywords;

  /*
  *******************************************************************************************
  * Name
  *   batchPhoto
  * Description
  *   Adds photos to the database for batch uploading at night
  * Input
  *  uId - user id
  *  id - photo id
  * Output
  *   none
  ******************************************************************************************
  */
  function batchPhoto($uId = false, $id = false)
  {
    if($uId !== false && $id !== false)
    {
      $uId = $this->dbh->sql_safe($uId);
      $id = $this->dbh->sql_safe($id);
      $sql = 'INSERT INTO user_ci_photos (ucp_u_id, ucp_up_id, ucp_title, ucp_description, ucp_category, ucp_subCategory,
                                              ucp_release, ucp_timestamp, ucp_timezone, ucp_country, ucp_state, ucp_city,
                                              ucp_keywords, ucp_dateCreated, ucp_status) '
           . 'VALUES (' . $uId . ',' . $id . ',' . $this->dbh->sql_safe($this->title) . ','
           . $this->dbh->sql_safe($this->description) . ',' . $this->dbh->sql_safe($this->category) . ',';
      
      if($this->subcategory !== '')
      {
        $sql .= $this->dbh->sql_safe($this->subcategory) . ',';
      }
      else
      {
        $sql .= 'NULL,';
      }

      if($this->release !== '')
      {
        $sql .= $this->dbh->sql_safe($this->release) . ',';
      }
      else
      {
        $sql .= 'NULL,';
      }

      if($this->datetime !== '')
      {
        $sql .= $this->dbh->sql_safe($this->datetime) . ',';
      }
      else
      {
        $sql .= 'NULL,';
      }

      if($this->timezone !== '')
      {
        $sql .= $this->dbh->sql_safe($this->timezone) . ',';
      }
      else
      {
        $sql .= 'NULL,';
      }

      if($this->location->getCountry() !== '')
      {
        $sql .= $this->dbh->sql_safe($this->location->getCountry()) . ',';
      }
      else
      {
        $sql .= 'NULL,';
      }

      if($this->location->getState() !== '')
      {
        $sql .= $this->dbh->sql_safe($this->location->getState()) . ',';
      }
      else
      {
        $sql .= 'NULL,';
      }

      if($this->location->getCity() !== '')
      {
        $sql .= $this->dbh->sql_safe($this->location->getCity()) . ',';
      }
      else
      {
        $sql .= 'NULL,';
      }

      $sql .= $this->dbh->sql_safe($this->keywords) . ", NOW(), 'pending') "
            . 'ON DUPLICATE KEY UPDATE ucp_up_id = ' . $id . ' ';
      
      $this->dbh->execute($sql);
    }
  }

  public function setImagedata($imagefile)
  {
    $tag = fopen($imagefile, 'rb');
    $contents = fread($tag, filesize($imagefile));

    $this->imagedata = bin2hex($contents);

    //set image hash
    $this->imagehash = bin2hex(md5($contents));
  }

  public function getImagedata()
  {
    return $this->imagedata;
  }

  public function setTitle($title)
  {
    $this->title = $title;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function setDescription($description)
  {
    $this->description = $description;
  }

  public function getDescription()
  {
    return $this->description;
  }

  public function setCategory($category)
  {
    $this->category = $category;
  }

  public function getCategory()
  {
    return $this->category;
  }

  public function setRelease($release)
  {
    $this->release = $release;
  }

  public function getRelease()
  {
    return $this->release;
  }

  public function setSubcategory($subcategory)
  {
    $this->subcategory = $subcategory;
  }

  public function getSubcategory()
  {
    return $this->subcategory;
  }

  public function setLocation($location)
  {
    $this->location = $location;
  }

  public function getLocation()
  {
    return $this->location;
  }

  public function setDatetime($datetime)
  {
    $this->datetime = $datetime;
  }

  public function getDatetime()
  {
    return $this->datetime;
  }

  public function setTimezone($timezone)
  {
    $this->timezone = $timezone;
  }

  public function getTimezone()
  {
    return $this->timezone;
  }

  public function setLicensetype($licensetype)
  {
    $this->licensetype = $licensetype;
  }

  public function getLicensetype()
  {
    return $this->licensetype;
  }

  public function setKeywords($keywords)
  {
    $this->keywords = $keywords;
  }

  public function getKeywords()
  {
    return $this->keywords;
  }

  /*
  * Name
  *   save
  * Description
  *   Method to log in and register a user
  * Output
  *   Array
  */
  public function save($username, $password)
  {
    $retval = array('response' => '', 'code' => '', 'text' => '');
    //login to CI Server
    //build login xml
    $rand = rand(0, 1000);
    $XMLLogin = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $XMLLogin .= "<credentials>\n";
    $XMLLogin .= "  <username>{$username}</username>\n";
    $XMLLogin .= "  <password>{$password}</password>\n";
    $XMLLogin .= "</credentials>\n";

    $request = CI_SERVER . '/service_login.do';
    $session = curl_init();

    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($session, CURLOPT_POST, true);
    curl_setopt($session, CURLOPT_POSTFIELDS, $XMLLogin);
    curl_setopt($session, CURLOPT_COOKIEJAR, PATH_HOMEROOT . PATH_TMP . '/ci_' . $rand);
    curl_setopt($session, CURLOPT_URL, $request);

    $response = curl_exec($session);
    $response = simplexml_load_string($response);
    $response = $response->attributes();

    /*
    * simplexml_load_string makes no sense.
    * It's nodes are all simplexml objects that can be treated as simple values
    */
    if(isset($response->message[0]))
    {
      $retval['response'] = $response['result-code'];
      $retval['code']     = $response->message[0]->code;
      $retval['text']     = $response->message[0]->text;
    }
    else
    if($response['result-code'] == 'success')
    {
      $XML = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
           . '<citizen-image-upload-data version="1002">' . "\n"
           . $this->getImageXML()
           . "</citizen-image-upload-data>";
      
      curl_setopt($session, CURLOPT_HEADER, false);
      curl_setopt($session, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
      curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($session, CURLOPT_POST, true);
      curl_setopt($session, CURLOPT_POSTFIELDS, $XML);
      curl_setopt($session, CURLOPT_COOKIEFILE, PATH_HOMEROOT . PATH_TMP . '/ci_' . $rand);
      curl_setopt($session, CURLOPT_URL, CI_SERVER . '/service_upload_image.do');

      $response = curl_exec($session);
      $response = simplexml_load_string($response);
      $response = $response->attributes();
      if(isset($response->message[0]))
      {
        $retval['response'] = $response['result-code'];
        $retval['code']     = $response->message[0]->code;
        $retval['text']     = $response->message[0]->text;
      }
      else
      {
        $retval['response'] = $response['result-code'];
      }
    }
    else
    {
      $retval['response'] = 'failure';
      $retval['code']     = 'unnkown_error';
      $retval['text']     = 'Call Feargall';
    }

    curl_close($session);

    return $retval;
  }

  public function createMemberMethods() {
     foreach($this as $key => $value) {
      print "public function set".ucwords($key)."(\$$key)\n";
      print "{\n";
      print "  \$this->$key = \$$key;\n";
      print "}\n\n";

      print "public function get".ucwords($key)."()\n";
      print "{\n";
      print "  return \$this->$key;\n";
      print "}\n\n";
     }
  }

  public function createMemberXml() {
     foreach($this as $key => $value) {
      print "\$strXml .= \"<$key>\".\$this->$key.\"</$key>\\n\";\n";
     }
  }

  public function createMemberSet() {
     foreach($this as $key => $value) {
      print "\$obj->set".ucwords($key)."('npond');\n";
     }
  }

  public function getImageXML(){
    $strXml =  "  <image>\n";
    $strXml .= "    <image-data>".$this->imagedata."</image-data>\n";
    $strXml .= "    <image-hash>".$this->imagehash."</image-hash>\n";
    if ($this->title != "")
      $strXml .= "    <title>".$this->title."</title>\n";
    if ($this->description != "")
    $strXml .= "    <description>".$this->description."</description>\n";
    $strXml .= "    <category>".$this->category."</category>\n";
    if (strtolower($this->category) == "editorial")
      $strXml .= "    <subcategory>".$this->subcategory."</subcategory>\n";
    $location = $this->location;
    if (isset($location))
    {
      $strXml .= "      <location>\n";
      $strXml .= "        <city>".$location->getCity()."</city>\n";
      $strXml .= "        <state>".$location->getState()."</state>\n";
      $strXml .= "        <country>".$location->getCountry()."</country>\n";
      $strXml .= "      </location>\n";
    }
    $strXml .= "    <date-time>".$this->datetime."</date-time>\n";
    $strXml .= "    <license-type>".$this->licensetype."</license-type>\n";
    $strXml .= "    <keywords>\n";
    if ($this->ArrayDepth($this->keywords) != 0)
    {
      foreach ($this->keywords as $keyword)
      {
        $strXml .= "      <keyword>\n";
        $strXml .= "        <word>".$keyword."</word>\n";
        $strXml .= "      </keyword>\n";
      }
    }
    $strXml .= "    </keywords>\n";
    $strXml .= "  </image>\n";

    return $strXml;
  }

  private function ArrayDepth($Array,$DepthCount=-1,$DepthArray=array()) {
    $DepthCount++;
    if (is_array($Array))
     foreach ($Array as $Key => $Value)
     $DepthArray[]=$this->ArrayDepth($Value,$DepthCount);
    else
     return $DepthCount;
    foreach($DepthArray as $Value)
     $Depth=$Value>$Depth?$Value:$Depth;
    return $Depth;
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


/*
*******************************************************************************************
* Name:  CCitizenImageLocation
*
* Class to provide location information to the Citizen Image API
*
* Usage: $ci =& CCitizenImageLocation::getInstance();
*
******************************************************************************************
*/

class CCitizenImageLocation
{
  private $country;
  private $state;
  private $city;

  public function setCountry($country)
  {
    $this->country = $country;
  }

  public function getCountry()
  {
    return $this->country;
  }

  public function setState($state)
  {
    $this->state = $state;
  }

  public function getState()
  {
    return $this->state;
  }

  public function setCity($city)
  {
    $this->city = $city;
  }

  public function getCity()
  {
    return $this->city;
  }

  public function __construct($country, $state, $city)
  {
    $this->country = $country;
    $this->state = $state;
    $this->city = $city;
  }
}

function read_header($ch, $string)
{
  echo '-----' . $string;
}
?>
<?php
  ini_set('max_execution_time', 0);
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  chdir(dirname(__FILE__));
  ob_start();
  include_once $path = str_replace('scripts', '', dirname(__FILE__)) . 'init_constants.php';
  
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_CLASS . '/CCitizenImage.php';
  
  $time = strtotime('-1 day');
  $startTime = mktime(0, 0, 0, date('m', $time), date('d', $time), date('Y', $time));
  $endTime = mktime(23, 59, 59, date('m', $time), date('d', $time), date('Y', $time));
  
  $ci =& CCitizenImage::getInstance();
  $cii=& CCIImage::getInstance();
  
  $photos = $ci->search(array('TIMESTART' => $startTime, 'TIMEEND' => $endTime, 'STATUS' => 'pending'));
  
  echo "Processing photos for " . date(FF_FORMAT_DATE_LONG, $time) . "\n";
  
  foreach($photos as $v)
  {
    //UCI_USERNAME
    echo "Processing photo ({$v['UCI_ID']})...";
    $license = $v['UCI_CATEGORY'] == 'editorial' ? 'Rights Managed' : 'Royalty Free';
    $cii->setImagedata(PATH_FOTOROOT . $v['UCI_IMAGE']);
    $cii->setTitle($v['UCI_TITLE']);
    $cii->setDescription($v['UCI_DESCRIPTION']);
    $cii->setCategory($v['UCI_CATEGORY']);
    $cii->setSubcategory($v['UCI_SUBCATEGORY']);
    $cii->setDatetime($v['UCI_TIMESTAMP']);
    $cii->setLicensetype($license);
    $cii->setLocation(new CCitizenImageLocation($v['UCI_COUNTRY'], $v['UCI_STATE'], $v['UCI_CITY']));
    $cii->setKeywords((array)explode(',', $v['UCI_KEYWORDS']));
    $cii->setTimezone($v['UCI_TIMEZONE']);
    $result = $cii->save($v['UCI_USERNAME'], $v['UCI_PASSWORD']);
    $ci->processed($v['UCI_ID']);
    echo "{$result['response']}\n";
  }
  echo "\n--------------------------\n";
?>
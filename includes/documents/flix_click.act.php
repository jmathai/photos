<?php
  $l =& CLogging::getInstance();
  
  $data = array('lfc_us_hash' => $_FF_SESSION->value('sess_hash'),
                'lfc_ipAddress' => $_SERVER['REMOTE_ADDR'],
                'lfc_fastflix' => $_GET['fastflix'],
                'lfc_urlReferrer' => $_GET['referrer'],
                'lfc_urlDestination' => $_GET['destination']
          );
  
  $l->addHitFlix($data);
  
  $url = isset($_GET['destination']) ? $_GET['destination'] : 'http://' . FF_SERVER_NAME;
?>
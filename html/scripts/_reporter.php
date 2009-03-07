<?php
  error_reporting(E_ERROR | E_PARSE);
  set_error_handler('_monitor_error');
  
  function _monitor_error($errno, $errstr, $errfile, $errline)
  {
    $errFile= dirname($_SERVER['SCRIPT_FILENAME']) . '/' . basename($_SERVER['SCRIPT_FILENAME'], '.php') . '.err';
    $fp = fopen($errFile, 'a');
    $errStr = "----------" . date('Y-m-d', time()) . "----------\n"
            . "ERROR: {$errno}\n"
            . "{$errstr}\n"
            . "({$errfile} -> {$errline})\n"
            . "{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}\n"
            . "----------------------------------------------\n";
    fputs($fp, $errStr, strlen($errStr));
    fclose($fp);
  }
?>
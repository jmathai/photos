<?php
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  include_once $path = str_replace('scripts', '', dirname(__FILE__)) . 'init_constants.php';
  
  include_once PATH_DOCROOT . '/init_database.php';
  
  if(isset($_GET['promotion']))
  {
    $promotion = $GLOBALS['dbh']->sql_safe($_GET['promotion']);
    $registrants = $GLOBALS['dbh']->query_all("SELECT * FROM users INNER JOIN promotions ON u_id = p_u_id WHERE p_name = {$promotion}");
    //print_r($registrants);
    $cnt = count($registrants);
    echo "Total registrants: {$cnt}<br/>\n";
    reduceRegistrants($registrants);
  }
  else
  {
    echo 'No promotion specified.';
  }
  
  function reduceRegistrants($registrants = null)
  {
    if($registrants != null)
    {
      // step 1
      // eliminate 1/2
      $prune = floor(count($registrants) / 2);
      for($i=0; $i<=$prune; $i++)
      {
        $rand = rand(0, count($registrants)-1);
        //unset($registrants[$rand]);
        array_splice($registrants, $rand, 1);
      }
      
      // step 2
      // eliminate another 1/2
      $prune = floor(count($registrants) / 2);
      for($i=0; $i<=$prune; $i++)
      {
        $rand = rand(0, count($registrants)-1);
        //unset($registrants[$rand]);
        array_splice($registrants, $rand, 1);
      }
      
      // pick a random winner
      $rand = rand(0, count($registrants)-1);
      $winner = $registrants[$rand];
      echo "The winner is:<br/><br>\n\n";
      foreach($winner as $k => $v)
      {
        echo "{$k} - {$v}<br/>\n";
      }  
    }
    else
    {
      echo 'registrants were null';
    }
  }
?>
<?php
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  chdir(dirname(__FILE__));
  ob_start();
  include_once str_replace('/scripts', '', dirname(__FILE__)) . '/init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  
  $groups = $GLOBALS['dbh']->query_all("SELECT g_id FROM groups WHERE g_status = 'Active'");
  $GLOBALS['dbh']->execute('TRUNCATE TABLE group_tags');
  foreach($groups as $v)
  {
    $tags = array();
    $fotos = $GLOBALS['dbh']->query_all('SELECT up.up_tags FROM group_fotos_map AS gp INNER JOIN user_fotos AS up ON gp.up_id = up.up_id WHERE gp.g_id = ' . $v['g_id'] . " AND up.up_status = 'active' AND up.up_tags <> ''");
    foreach($fotos as $fv)
    {
      $tmpTags = (array)explode(',', $fv['up_tags']);
      foreach($tmpTags as $tv)
      {
        if($tv != '')
        {
          $tags[strtolower($tv)]++;
        }
      }
    }
    
    $flix = $GLOBALS['dbh']->query_all('SELECT uf.uf_tags FROM group_fotoflix_map AS gp INNER JOIN user_fotoflix AS uf ON gp.uf_id = uf.uf_id WHERE gp.g_id = ' . $v['g_id'] . " AND uf.uf_status = 'active' AND uf.uf_tags <> ''");
    foreach($flix as $fv)
    {
      $tmpTags = (array)explode(',', $fv['uf_tags']);
      foreach($tmpTags as $tv)
      {
        if($tv != '')
        {
          $tags[strtolower($tv)]++;
        }
      }
    }
    
    $tagSum = array_sum($tags);
    $tagCount = count($tags);
    
    $group_id = $v['g_id'];
    
    $totalWeight = 0;
    $sql = 'INSERT INTO group_tags(gt_g_id, gt_tag, gt_weight) VALUES ';
    $continue = false;
    foreach($tags as $tag => $count)
    {
      $tmpWeight = (intval($count) / intval($tagSum) * 100);
      $weight = number_format($tmpWeight, 2);
      $totalWeight += $weight;
      $sql .= "({$group_id}, '{$tag}', '{$weight}'), ";
      $continue = true;
    }
    
    //$GLOBALS['dbh']->execute('DELETE FROM group_tags WHERE gt_g_id = ' . $group_id);
    
    if($continue == true)
    {
      $sql = substr($sql, 0, -2);
      $GLOBALS['dbh']->execute($sql);
    }
  }
?>
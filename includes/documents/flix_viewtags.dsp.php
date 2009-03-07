<?php
  /*$t =& CTag::getInstance();
  $allTags = $t->tags($_USER_ID, 'TAG');
  $tagCount = count($allTags);
  
  if($tagCount <17)
  {
    $cols = 1;
  }
  else
  if($tagCount < 51)
  {
    $cols = 2;
  }
  else
  {
    $cols = 3;
  }
  
  $colMax = ceil($tagCount / $cols);
  $colWidth = floor(530 / $cols);*/
  $t =& CTag::getInstance();
  $tags = $t->tags($_USER_ID, 'TAG', false, false, 'all');
  $min = $tags[0]['MIN'];
  $max = $tags[0]['MAX'];
  $variance = $max - $min;
?>

<div class="bold" style="padding-left:5px; padding-bottom:5px;" align="left">All of your tags (click any link to view slideshows with that tag)</div>

<div style="white-space:normal; padding-left:5px; padding-left:5px;">
  <?php
    if($tags[0]['COUNT'] > 0)
    {
      $min = $tags[0]['MIN'];
      $max = $tags[0]['MAX'];
      $step= ($max - $min) / 5;
      foreach($tags as $k => $v)
      {
        $fontSize = tagsize(intval($v['WEIGHT']), $step);
        echo '&nbsp;<a href="/?action=flix.flix_list&tags=' . urlencode($v['TAG']) .'" style="line-height:30px; font-size:' . $fontSize . 'px;" title="' . $v['TAG_COUNT'] . ' photos tagged with ' . $v['TAG'] . '" class="plain">' . $v['TAG'] . '</a>&nbsp; ';
      }
    }
    else
    {
      echo '<div>There are no tags to display.</div>';
    }
  ?>
  <br clear="all" />
</div>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>
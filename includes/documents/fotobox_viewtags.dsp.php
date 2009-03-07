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

  if(isset($_GET['update']))
  {
    $t->generateWeights($_USER_ID);
  }

  $tags = $t->tags($_USER_ID, 'TAG', false, false, 'all');
  //print_r($tags);
  $min = $tags[0]['MIN'];
  $max = $tags[0]['MAX'];
  $variance = $max - $min;
?>

<div class="bold" style="padding-left:5px; padding-bottom:5px;" align="left">All of your tags (click any link to view photos with that tag, <a href="/?action=fotobox.view_all_tags&view_list=1">view tags in a list</a> or <a
href="/?action=fotobox.view_all_tags&update=1">update this list</a>)</div>

<div style="padding:5px;">
  <?php
    if($tags[0]['COUNT'] > 0)
    {
      if(!isset($_GET['view_list']))
      {
        $min = $tags[0]['MIN'];
        $max = $tags[0]['MAX'];
        $step= ($max - $min) / 5;
        foreach($tags as $k => $v)
        {
          $fontSize = tagsize(intval($v['WEIGHT']), $step);
          echo '&nbsp;<a href="/?action=fotobox.fotobox_myfotos&TAGS=' . urlencode($v['TAG']) .'" style="line-height:30px; font-size:' . $fontSize . 'px;" title="' . $v['TAG_COUNT'] . ' photos tagged with ' . $v['TAG'] . '" class="plain tagCouldElement">' . $v['TAG'] . '</a>&nbsp; ';
        }
      }
      else
      {
        foreach($tags as $k => $v)
        {
          echo '<div class="bullet"><a href="/?action=fotobox.fotobox_myfotos&TAGS=' . urlencode($v['TAG']) .'" title="' . $v['TAG_COUNT'] . ' photos tagged with ' . $v['TAG'] . '">' . $v['TAG'] . '</a></div>';
        }
      }
    }
    else
    {
      echo '<div>There are no tags to display.</div>';
    }
  ?>
</div>

<?php
  $t =& CTag::getInstance();
  $tags = $t->tags($_GET['group_id'], 'RANDOM', false, 'group');
  $min = $tags[0]['MIN'];
  $max = $tags[0]['MAX'];
  $variance = $max - $min;
?>

<div class="bold" style="width:545px; padding-left:5px; padding-bottom:5px;" align="left">All of your tags (click any link to view fotos with that tag)</div>

<div style="width:535px; white-space:normal; padding-left:5px; padding-left:5px; border:solid 1px #efefef;">
  <?php
    foreach($tags as $k => $v)
    {
      $weight = intval($v['WEIGHT'] / $variance * 10);
      $fontSize = $weight + 10;
      echo '<div style="font-size:' . $fontSize . 'pt; padding:5px; height:30px; float:left;"><a href="/?action=fotogroup.group_fotos&group_id=1&tags=' . urlencode($v['TAG']) .'">' . $v['TAG'] . '</a></div>';
    }
  ?>
  <br clear="all" />
</div>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>
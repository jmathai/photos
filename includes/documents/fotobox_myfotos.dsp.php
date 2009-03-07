<?php
  $us =& CUser::getInstance();

  // new changes
	$i = 0;

  // this is outdated
  switch($action)
  {
    case 'fotobox.fotobox_myfotos_create_flix':
      echo '<div style="width:545px; padding-left:10px; text-align:left; padding-bottom:10px;">
              <div class="f_10 bold f_off_accent">Create a slideshow</div>
              <div class="bullet">Select the photos you want by adding them to the Tool Box</div>
              <div class="bullet">Click "Create Slideshow" underneath the Tool Box</div>
            </div>';
      break;
  }
  
  echo trackSignup($_USER_ID);
?>

<!-- fotobox iframe -->
<!--<iframe src="/fotobox<?php echo $qs; ?>" name="_fotobox" id="_fotobox" width="545" height="0" marginheight="0" marginwidth="0" scrolling="no" frameborder="0" style="z-index:1;"></iframe>-->


<div id="myFotos">
  <div id="myFotosLoading">
    <div style="float:left;"><img src="images/ajax_loader_snake.gif" width="16" height="16" hspace="4" border="0" /></div>
    <div style="float:left;">Loading...</div>
    <br/>
  </div>
  <div id="myFotosContent"></div>
  <br clear="all" />
</div>

<?php include_once PATH_DOCROOT . '/fotobox_toolbox.dsp.php'; ?>

<div class="bullet"><a href="/?action=home.samples&subaction=editPhoto">How do I edit my photos?</a> (crop, rotate, black &amp; white, sepia)</div>
<div class="bullet"><a href="/?action=home.samples&subaction=createSlideshow">How do I create a slideshow?</a></div>
<div class="bullet"><a href="/?action=home.samples&subaction=printing">Can I order more than just prints?</a></div>
<div class="bullet"><a href="/?action=home.samples&subaction=network">How can I keep others updated with my photos?</a></div>

<script type="text/javascript">
  function addToTbFromLb(id)
  {
    if(tb.exists(id))
    {
      tId = tb.mapPtoT(id);
      tb.remove(tId);
      $('addToTbFromLb').innerHTML = 'Add to Tool Box';
    }
    else
    {
      tb.add(id);
      $('addToTbFromLb').innerHTML = 'Remove from Tool Box';
    }
  }

  function showToTbFromLb(id)
  {
    if(tb.exists(id))
    {
      $('addToTbFromLb').innerHTML = 'Remove from Tool Box';
    }
    else
    {
      $('addToTbFromLb').innerHTML = 'Add to Tool Box';
    }
  }

  //tb.add(' . $foto['P_ID'] . ')
  var effect = new fx.Opacity('myFotosContent');
  effect.hide();
  <?php
    $urlOmit = array('action' => true);
    parse_str($_SERVER['QUERY_STRING'], $urlParams);

    $limit = $us->pref($_USER_ID, 'MYPHOTOS_LIMIT');
    if($limit === false)
    {
      $limit = 12;
    }

    $params = array('LIMIT' => $limit, 'OFFSET' => 0, 'ORDER' => 'P_CREATED');
    foreach($urlParams as $k => $v)
    {
      if(!isset($urlOmit[$k]))
      {
        $params[$k] = $v;
      }
    }

    //$params['DATE_TAKEN_START']  = strtotime('24 May 2006'); //intval($_GET['startdate']);
    //$params['DATE_TAKEN_END']    = strtotime('25 May 2006'); //intval($_GET['enddate']);

    $params = jsonEncode($params);
    echo '
          var fbOpts = ' . $params . ';
          loadFotos(fbOpts);
          ';
  ?>
</script>
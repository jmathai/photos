<?php
  if(isset($_GET['key']))
  {
    $key = $_GET['key'];
    $swf_url = '/swf/container/dynamic/container_980_730.swf?version=' . FF_VERSION_EDITOR . '&startEditMode_int=1&timestamp=' . NOW . '&slideshowKey_str=' . $key;
  }
  else
  if(isset($_GET['toolbox']))
  {
    $swf_url = '/swf/container/dynamic/container_980_730.swf?toolbox=1&version=' . FF_VERSION_EDITOR . '&startEditMode_int=1&timestamp=' . NOW;
  }
  else
  if(isset($_GET['tags']))
  {
    $swf_url = '/swf/container/dynamic/container_980_730.swf?tags=' . urlencode($_GET['tags']) . '&version=' . FF_VERSION_EDITOR . '&startEditMode_int=1&timestamp=' . NOW;
  }
  
  echo trackSignup($_USER_ID);
?>

<div style="width:980px; margin:auto;">
  <script type="text/javascript">
    // embed <object>
    embedSwf({WIDTH: 980, HEIGHT: 730, SRC: '<?php echo $swf_url; ?>', BGCOLOR: '#ffffff'});
  </script>
  <br/>
  <a name="timeline"></a>
  <br/>
  <div class="bold">
    <div class="bullet"><a href="/?action=home.samples&subaction=toolbar" target="_blank">How do I customize a slideshow?</a></div>
  </div>
</div>

<script>
  function goToTimeline()
  {
    location.href = '/?<?php echo $_SERVER['QUERY_STRING']; ?>#timeline';
  }
</script>
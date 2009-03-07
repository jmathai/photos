<?php
  $qs = '?action=' . $action;
  
  /*if(isset($_GET['tags']) || isset($tags))
  {
    $tags = isset($_GET['tags']) ? $_GET['tags'] : $tags;
    $qs .= '&tags=' . $tags;
  }*/
  if(isset($_GET['tags']))
  {
    $qs .= '&tags=' . $_GET['tags'];
  }
  
  if(isset($_GET['group_id']) && !isset($no_group))
  {
    $qs .= '&type=group&group_id=' . $_GET['group_id'];
  }
  
  if(isset($no_paging))
  {
    $qs .= '&nopaging=1';
  }
  
  if(isset($foto_ids) || isset($_GET['foto_ids']))
  {
    $qs .= '&foto_ids=' . (isset($foto_ids) ? $foto_ids : $_GET['foto_ids']);
  }
  
  if(isset($no_title))
  {
    $qs .= '&notitle=1';
  }
  
  if(isset($no_checkboxes))
  {
    $qs .= '&nocheckboxes=1';
  }
  
  $fb_show_actions = true;
  if(isset($display_only))
  {
    $qs .= '&display_only=1';
    $display_only     = true;
    $fb_show_actions  = false;
  }
  else
  {
    $display_only = false;
  }
  
  if(isset($max_fotos))
  {
    $qs .= '&max_fotos=' . $max_fotos;
  }
  
  if(isset($page))
  {
    $qs .= '&page=' . $page;
  }
  
  if(isset($bgcolor))
  {
    $qs .= '&bgcolor=' . $bgcolor;
  }
  
  if(isset($_GET['message']))
  {
    switch($_GET['message'])
    {
      case 'fotos_deleted':
        $message_text = 'Your foto(s) were successfully deleted.';
        break;
      case 'shared':
        $message_text = 'Your foto(s) were successfully shared';
        break;
      case 'account_created':
        $message_text = 'Your account was successfully created.';
        break;
      case 'fotos_uploaded':
        $message_text = 'Your foto(s) were successfully uploaded.';
        break;
      case 'made_private':
        $message_text = 'Your foto(s) were made private.';
        break;
      case 'made_public':
        $message_text = 'Your foto(s) were made public.';
        break;
    }
    
    echo    '<table class="confirm" align="center">'
          . '<tr><td align="center">'
          . $message_text
          . '</td></tr>'
          . '</table>';
  }
  
  switch($action)
  {
    case 'fotobox.fotobox_myfotos_create_flix':
      echo '<div style="width:545px; padding-left:10px; text-align:left;">
              <div class="f_10 bold f_off_accent">Create a Flix</div>
              <div class="bullet">Select the fotos you want by clicking the checkbox under each foto</div>
              <div class="bullet">Click "Create a new Flix" at the bottom of the page under "SHARING OPTIONS"</div>
            </div>';
      break;
  }
  
?>

<!-- fotobox iframe -->
<iframe src="/fotobox<?php echo $qs; ?>" name="_fotobox" id="_fotobox" width="545" height="0" marginheight="0" marginwidth="0" scrolling="no" frameborder="0" style="z-index:1;"></iframe>

<?php
  if($fb_show_actions === true)
  {
    echo '<script language="javascript">
            document.getElementById("_fotobox").height = parseInt(398);
          </script>';
    
    switch($action)
    {
      case 'fotobox.fotobox_myfotos':
      case 'fotogroup.group_fotos':
        include PATH_DOCROOT . '/fotobox_actions.dsp.php';
        break;
      case 'fotobox.fotobox_myfotos_create_flix':
        include PATH_DOCROOT . '/flix_create_actions.dsp.php';
        break;
    }
  }
  
  include_once PATH_DOCROOT . '/ads_horizontal.dsp.php';
  
  if(!isset($no_set_template))
  {
    $tpl->main($tpl->get());
    $tpl->clean();
  }
  
  unset($no_set_template);
?>
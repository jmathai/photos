<?php
  $us =& CUser::getInstance();
  $fb =& CFotobox::getInstance();
  
  $result   = $_GET['result'];
  $foto_ids = explode(',', $_GET['foto_ids']);
  $fotos    = $fb->fotosByIds($foto_ids, $_USER_ID);
  
  echo '<div class="bold">';
  
  if($result == 0)
  {
    echo 'No actions were performed.';
  }
  else
  if($result == 1)
  {
    echo 'Your foto was successfully blogged.';
  }
  else
  if($result == -1)
  {
    echo 'An error occurred while trying to blog your foto.';
  }
  
  echo '</div>
        <div style="width:450px; padding-top:10px;">
          <div align="left">';

  if(isset($_GET['codes']))
  {
    $codes  = (array)explode(',', $_GET['codes']);
    $errors = array();
    foreach($codes as $v)
    {
      switch($v)
      {
        case '401':
          $errors[] = '<div class="bold">We could not authorize you with the information provided.</div><div style="padding-left:10px;">- Please verify your username, password, and blog id.</div>';
          break;
        case '500':
          $errors[] = '<div class="bold">An unexplained error occurred.</div><div style="padding-left:10px;">- Please try your request again later or contact us.</div>';
          break;
      }
    }
    
    echo implode('<br/>', $errors);
  }
  else
  if(isset($_GET['b_ids']))
  {
    $blogs = $us->blogs($_USER_ID, (array)explode(',', $_GET['b_ids']));
    foreach($blogs as $v)
    {
      if($v['B_URL'] != '')
      {
        $display = '<a href="' . $v['B_URL'] . '" target="_blank">' . $v['B_URL'] . '</a>';
      }
      else
      {
        $display = $v['B_USERNAME'] . '@' . $v['B_TYPE'];
      }
      
      echo '<div style="padding-bottom:2px;">You have a new post at ' . $display . '!*</div>';
    }
    
    if(count($blogs) > 0) 
    {
      echo '<div class="f_7">*Depending on your settings you may have to manually publish your blog</div>';
    }
  }
  
  if($result == 1)
  {
    echo '<div style="margin:auto;">';
    
    foreach($fotos as $v)
    {
      echo '<div style="padding-top:10px;"><img src="' . PATH_FOTO . $v['P_FLIX_PATH'] . '" border="0" /></div>';
    }
    
    echo '</div>';
  }
  
  echo '  </div>
        </div>';
  $tpl->main($tpl->get());
  $tpl->clean();
?>
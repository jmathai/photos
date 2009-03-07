<?php
  if(isset($_GET['foto_ids']))
  {
    $foto_ids = $_GET['foto_ids'];
    $foto_ids_array = explode(',', $foto_ids);
    $foto_count = count($foto_ids_array);
  }
  else 
  {
    $fb =& CFotobox::getInstance();
    if($_GET['foto_date'] != '')
    {
      $fotos = $fb->uploadedSince($_USER_ID, $_GET['foto_date']);
    }
    
    $foto_ids_array = array();
    foreach($fotos as $v)
    {
      $foto_ids_array[] = $v['P_ID'];
    }
    $foto_ids = implode(',', $foto_ids_array);
    $foto_count = count($foto_ids_array);
  }
  
  if($foto_count <= 100)
  {
    
?>
  
    <div style="padding-left:15px;">
      <div class="f_11 f_off_accent bold" style="padding-bottom:10px;">
        Your <?php echo $foto_count; ?> photos have uploaded successfully!
        <?php
          if($_GET['failed'] > 0)
          {
            echo '<div class="f_red bold">' . intval($_GET['failed']) . ' photos failed to upload.</div>';
          }
          
        ?>
      </div>
      
      <div style="padding-bottom:20px;">
        <div style="float:left; height:30px;"><img src="images/flix_medium.gif" width="20" height="23" style="padding-right:10px;" /></div>
        <div>
          <div style="padding-bottom:3px;"><a href="/?action=fotobox.toolbox_add.act&ids=<?php echo $foto_ids ?>&purge=1&redirect=<?php echo urlencode('/?action=flix.flix_form&toolbox=1'); ?>" class="f_11 f_dark bold">Would you like to create a slideshow now?</a></div>
          <div>(Arrange the order of your photos, add music, titles, names &amp; descriptions and choose from over 30 themes)</div>
        </div>
      </div>
      
      <div>
        <div style="float:left; height:30px;"><img src="images/my_fotos_medium.gif" width="23" height="19" style="padding-right:10px;" /></div>
        <div>
          <div style="padding-bottom:3px;"><a href="/?action=fotobox.fotobox_myfotos" class="f_11 f_dark bold">Or continue on to Photos.</a></div>
          <div>(Manage your photos.  Add tags, crop, rotate and more.)</div>
        </div>
      </div>
    </div>
    
    <div style="padding-bottom:275px;"></div>

<?php
  }
  else 
  {
    echo '<script language="javascript"> location.href = "/?action=fotobox.fotobox_myfotos"; </script>';
  }
  
  $tpl->main($tpl->get());
  $tpl->clean();
?>
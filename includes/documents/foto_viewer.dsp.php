<?php
  $prefix   = substr($action, 0, strpos($action, '.'));
  $viewmode = isset($args) ? $args[0] : false;
  $id_hash  = !isset($id_hash) ? $args[1] : $id_hash;

  if($prefix == 'fotobox' || $viewmode == 'fotoviewer')
  {
    $obj =& CFotobox::getInstance();
  }
  else
  {
    $obj =& CGroup::getInstance();
  }

  if(strlen($id_hash) == 32)
  {
    $foto_data = $obj->fotoData($id_hash);
  }
  else
  {
    $image_id = substr($id_hash, 6);
    $foto_data= $obj->fotoData($image_id);
  }

  if(!is_file(PATH_FOTOROOT . $foto_data['P_WEB_PATH']))
  {
    echo '<div class="bold" align="center" style="width:100%;">Sorry, but we can not load the foto you requested.</div.';
  }
  else
  {
    $foto_size = @getimagesize(PATH_FOTOROOT . $foto_data['P_WEB_PATH']);
?>

    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td align="center">
          <?php
            if($viewmode !== false)
            {
              echo 'FotoLink: http://' . FF_SERVER_NAME . '/' . $viewmode . '?' . $foto_data['P_KEY'] . '<br />';
            }
          ?>

          <img src="<?php echo PATH_FOTO . $foto_data['P_WEB_PATH']; ?>?dyn=<?php echo NOW; ?>" <?php echo $foto_web_info[3]; ?> style="border:1px solid #ffffff;" />
        </td>
      </tr>
      <tr>
        <td align="center">
          <div class="bold">
            <?php echo $foto_data['P_NAME']; ?>
          </div>

          <?php
            if($viewmode !== false)
            {
              echo '<div style="width:' . FF_WEB_WIDTH . 'px; height:40px; overflow:auto;">' . $foto_data['P_DESC'] . '</div>';
            }
            else
            {
              echo $foto_data['P_DESC'];
            }
          ?>
        </td>
      </tr>
    </table>

    <script language="javascript">
      function focuser()
      {
        if(opener)
        {
          opener.window.focus();
        }
      }

      window.onunload = focuser;
    </script>

<?php
  }

  if(isset($tpl))
  {
    $tpl->main($tpl->get());
    $tpl->clean();
  }
?>
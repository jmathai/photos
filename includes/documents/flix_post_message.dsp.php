<?php
  $us =& CUser::getInstance();
  $fl =& CFlix::getInstance();
  $fb =& CFotobox::getInstance();
  
  $fastflix = $_GET['fastflix'];
  $result   = $_GET['result'];
  $flix_data  = $fl->fastflix($fastflix);
  $foto_id    = $flix_data['A_DATA'][0]['D_UP_ID'];
  $foto_data  = $fb->fotoData($foto_id);
  $sizeArr    = explode('x', $flix_data['A_SIZE']);
  $containerWidth      = $sizeArr[0];
  $containerHeight     = $sizeArr[1];
  
  $swf_src = '/swf/flix_theme/layout_small/small_' . substr($flix_data['A_TEMPLATE'], 1) . '?imageSource=' . PATH_FOTO . $foto_data['P_THUMB_PATH'] . '&fastflix=' . $flix_data['A_FASTFLIX'] . '&containerWidth=' . $containerWidth . '&containerHeight=' . $containerHeight;
?>

<div class="bold">
  <?php
    if($result == 0)
    {
      echo 'No actions were performed.';
    }
    else
    if($result == 1)
    {
      echo 'Your Flix was successfully blogged.';
    }
    else
    if($result == -1)
    {
      echo 'An error occurred while trying to blog your Flix.';
    }
  ?>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="545">
  <tr>
    <td width="150">
      <div class="flix_border"><a href="/fastflix_popup?fastflix=<?php echo $fastflix; ?>" onclick="_open(this.href,<?php echo $containerWidth . ', ' . $containerHeight; ?>); return false;"><img src="<?php echo PATH_FOTO . $foto_data['P_THUMB_PATH']; ?>" border="0" /></a></div>
    </td>
    <td>
      <?php
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
          
          /*if($usernamesCnt == 1)
          {
            echo 'Your blog should be updated...view it here.  <a href="http://' . $usernames[0] . '.blogspot.com" target="_blank">http://' . $usernames[0] . '.blogspot.com</a>.';
          }
          else 
          if($usernamesCnt > 1)
          {
            echo 'Your blogs should be updated...view them here.<br/><br/>';
            foreach($usernames as $v)
            {
              echo '<a href="http://' . $v . '.blogspot.com" target="_blank">http://' . $v . '.blogspot.com</a><br/>';
            }
          }*/
        }
      ?>
    </td>
  </tr>
</table>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>
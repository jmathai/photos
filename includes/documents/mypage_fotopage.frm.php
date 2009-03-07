<?php
  $us=& CUser::getInstance();
  $fotoPref = $us->pref($_USER_ID, 'FOTO_PRIVACY');
  
  if(isset($_GET['message']))
  {
    switch($_GET['message'])
    {
      case 'updated':
        echo '<div class="confirm">Your settings were saved</div>';
        break;
    }
  }
?>

<div style="width:545px; padding-top:5px; padding-bottom:15px;" align="left">
  <div style="padding-bottom:10px;">
    <div class="bold">Set the default settings for the fotos you upload.</div>
  </div>
  
  <form method="post" action="/?action=mypage.fotopage.act" style="display:inline;">
    <div>
      <div class="bold"><span class="f_off_accent">All photos I upload should have this setting</span></div>
      <div style="padding-left:12px;">
        <div><input type="radio" name="privacySetting" value="1111" <?php echo $fotoPref == '1111' ? ' checked="true" ' : ''; ?> />Do not add to FotoPage.</div>
        <div><input type="radio" name="privacySetting" value="3311" <?php echo empty($fotoPref) || $fotoPref == '3311' ? ' checked="true" ' : ''; ?> />Add to My FotoPage.  Allow comments. (Recommended)</div>
        <div><input type="radio" name="privacySetting" value="3331" <?php echo $fotoPref == '3331' ? ' checked="true" ' : ''; ?> />Add to My FotoPage.  Allow notes or comments.</div>
        <div><input type="radio" name="privacySetting" value="3333" <?php echo $fotoPref == '3333' ? ' checked="true" ' : ''; ?> />Add to My FotoPage.  Allow notes, comments or tags.</div>
      </div>
      <br />
    </div>
  
    <div>
      <input type="image" src="images/save_fp.gif" width="86" height="23" border="0">
    </div>
  </form>
</div>

<div style="height:275px"></div>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>
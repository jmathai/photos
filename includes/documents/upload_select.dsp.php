<?php
  if(isset($_COOKIE['ff_uploader_preference']))
  {
    if($_COOKIE['ff_uploader_preference'] == 1)
    {
      //$tpl->kill('<script language="javascript"> location.href="/?action=fotobox.upload_form"; </script>');
      //header('Location: /?action=fotobox.upload_form');
      echo '<script language="javascript"> location.href="/?action=fotobox.upload_form"; </script>';
      die();
    }
    else
    if($_COOKIE['ff_uploader_preference'] == 2)
    {
      //$tpl->kill('<script language="javascript"> location.href="/?action=fotobox.upload_form_compat"; </script>');
      //header('Location: /?action=fotobox.upload_form_compat');
      echo '<script language="javascript"> location.href="/?action=fotobox.upload_form_compat"; </script>';
      die();
    }
  }
?>

<script language="javascript">
  _str_basic = ''
    + '<div style="padding-bottom:5px;" class="bold">How would you like to upload your fotos?</div>'
    + '<div style="padding-bottom:5px; padding-top:10px; border-bottom:1px solid #dddddd;">'
    + '  <div>{RECOMMENDED}'
    + '    <div style="padding-top:3px; padding-bottom:3px;">'
    + '      <a href="/?action=fotobox.upload_form_html"" title="Click to use">'
    + '        <div><img src="images/uploader_specs_all.gif" width="190" height="75" border="0" /></div>'
    + '        <span class="f_12 f_off_accent bold">Use the basic uploader.</span>'
    + '      </a>'
    + '    </div>'
    + '    Supported by virtually every browser on the planet, our basic uploader let\'s everyone share their fotos.'
    + '  </div>'
    + '</div>';
    
  _str_ie = ''
    + '<div style="padding-bottom:5px; padding-top:20px; border-bottom:1px solid #dddddd;">'
    + '<div>{RECOMMENDED}'
    + '  <a href="/?action=fotobox.upload_form"" title="Click to use">'
    + '    <div><img src="images/uploader_specs_1.gif" width="44" height="69" border="0" align="absmiddle" /><img src="images/fotouploader_screen.gif" width="125" height="91" border="0" hspace="30" align="absmiddle" /></div>'
    + '    <div style="padding-top:3px; padding-bottom:3px;"><span class="f_12 f_off_accent bold">Bulk uploader for Windows using Internet Explorer!</span></div>'
    + '  </a>'
    + '  We have an uploader that is compatible with Internet Explorer 5 and higher on Windows.  '
    + '  This is the fastest and easiest way to get your fotos uploaded.  '
    + '  Simply click and drag the fotos you want to upload and press a button!'
    + '  <div class="f_7">*Requires ActiveX controls to be enabled</div>'
    + '</div>'
    + '</div>';
    
  _str_non_ie = ''
    + '<div style="padding-bottom:5px; padding-top:20px; border-bottom:1px solid #dddddd;">'
    + '<div>{RECOMMENDED}'
    + '  <a href="/?action=fotobox.upload_form_compat"" title="Click to use">'
    + '    <div><img src="images/uploader_specs_2_beta.gif" width="160" height="95" border="0" align="absmiddle" /><img src="images/fotouploader_screen.gif" width="125" height="91" border="0" hspace="30" align="absmiddle" /></div>'
    + '    <div style="padding-top:3px; padding-bottom:3px;"><span class="f_12 f_off_accent bold">Bulk uploader for FireFox and Safari!</span></div>'
    + '  </a>'
    + '  This version of our uploader is compatible with the popular Safari and FireFox browsers.  '
    + '  You simply click and drag the fotos you want to upload and press a button!'
    + '  <div class="f_7">*Requires Java to be enabled</div>'
    + '  <div class="f_7">**FireFox on OSX requires <a href="http://javaplugin.sourceforge.net/" target="_blank">Java Embedding Plugin</a>.</div>'
    + '</div>'
    + '</div>';
</script>

<div align="left" style="padding-left:15px; padding-right:15px;">
  <script language="javascript">
    var _info = navigator.userAgent; var _ns = false;
    var _ie = (_info.indexOf("MSIE") > 0 && _info.indexOf("Win") > 0 && _info.indexOf("Windows 3.1") < 0);
    var _recommended = '<span class="f_9 f_off_accent_bright bold"><img src="images/favorite.gif" width="11" height="11" border="0" style="padding-right:4px;" />FotoFlix recommends this uploader</span>';
    
    document.writeln(_str_basic.replace('{RECOMMENDED}', _recommended));
    
    if(_ie)
    {
      document.writeln(_str_ie.replace('{RECOMMENDED}', ''));
      document.writeln(_str_non_ie.replace('{RECOMMENDED}', ''));
    }
    else
    {
      document.writeln(_str_non_ie.replace('{RECOMMENDED}', ''));
    }
  </script>

</div>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>
<?php
  $us =& CUser::getInstance();
  $userData = $us->find($_USER_ID);
  
  echo '
  <script language="javascript">
    _embedSrc = "http://' . FF_SERVER_NAME . '/js/foto_reel/' . $userData['U_KEY'] . '/?count={COUNT}&display={DISPLAY}";
  </script>
  ';
?>

<script language="javascript">
  function updateCodeFotoReel()
  {
    _count     = document.forms['_reel'].elements['fCount'].options[document.forms['_reel'].elements['fCount'].selectedIndex].value;
    if(document.forms['_reel'].elements['fDisplay'][0].checked)
    {
      _display = 'horizontal';
    }
    else
    {
      _display = 'vertical';
    }
    
    _newString = _embedSrc;
    _newString = _newString.replace('{COUNT}', _count);
    _newString = _newString.replace('{DISPLAY}', _display);
    
    document.forms['_reel'].elements['fotoReel'].value = '<' + 'script language="javascript" src="' + _newString + '"><' + '/script>';
    document.forms['_reel'].elements['fotoReel'].select();
    document.forms['_reel'].elements['fotoReel'].focus();
  }
</script>

<div style="width:545px;" align="left">
  <div style="padding-bottom:10px;">
    <fieldset>
      <legend style="font-weight:bold;">Example</legend>
      <script language="javascript" src="http://www.fotoflix.com/js/foto_reel/1254f28707ef47d10dc841eabe6de78c/?count=6&display=horizontal"></script>
    </fieldset>
  </div>
  
  <form style="display:inline;" name="_reel">
  <div class="bold">Display your most recent public fotos on your blog or website.</div>
  <br/><br/>
  <div class="bold">How many fotos do you want to display?</div>
  <div>
    <select name="fCount" class="formfield" onChange="updateCodeFotoReel();">
      <option value="1">Select One</option>
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
      <option value="6">6</option>
      <option value="7">7</option>
      <option value="8">8</option>
      <option value="9">9</option>
      <option value="10">10</option>
    </select>
  </div>
  <br/>
  <div class="bold">How do you want them to be displayed?</div>
  <div><input type="radio" name="fDisplay" value="horizontal" checked="true" onClick="updateCodeFotoReel();" />&nbsp;Horizontally</div>
  <div><input type="radio" name="fDisplay" value="vertical" onClick="updateCodeFotoReel();" />&nbsp;Vertical</div>
  <br/><br/>
  <div class="bold">Copy and paste this code into your blog or website</div>
  <textarea id="fotoReel" class="formfield" style="width:500px; height:40px;"></textarea>
  </form>
</div>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>
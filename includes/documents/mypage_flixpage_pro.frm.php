<?php
  $p =& CMyPage::getInstance();
  $us=& CUser::getInstance();
  $pageData = $p->page($_USER_ID);
  $userData = $us->find($_USER_ID);
?>

<div class="f_dark_accent" style="padding-bottom:10px; border-top:1px solid #9799a6; border-bottom:1px solid #9799a6; background-color:#e6e5ea; width:545px;" align="left">
  <div style="padding-left:10px; padding-top:10px; padding-bottom:10px;">
    <img src="images/flix_configuration.gif" width="195" height="23" border="0" />
    <table border="0" cellpadding="0" align="center" class="f_dark_accent">
      <td width="250">
        <div><img src="images/tab_private_page.gif" width="112" height="15" border="0" /></div>
        <div style="border:1px solid #39485b; width:250px; height:170px;">
          <iframe name="flix_configuration_private" id="flix_configuration_private" src="/popup/mypage_flix_config_private/" frameborder="0" style="width:250px; height:170px;"></iframe>
        </div>
        <div class="f_8 f_lite" style="padding-top:4px; border:1px solid #39485b; background-color:#39485b; width:250px; height:20px;" align="center">
          <!--<div style="padding-left:3px;"><img src="images/make_flix_public.jpg" width="161" height="24" border="0" /></div>-->
          <div style="padding-left:3px;">&nbsp;</div>
        </div>
      </td>
      <td width="250">
        <div><img src="images/tab_public_page.gif" width="112" height="15" border="0" /></div>
        <div style="border:1px solid #39485b; width:250px; height:170px;">
          <iframe name="flix_configuration_public" id="flix_configuration_public" src="/popup/mypage_flix_config_public/" frameborder="0" style="width:250px; height:170px;"></iframe>
        </div>
        <div class="f_8 f_lite" style="padding-top:4px; border:1px solid #39485b; background-color:#39485b; width:250px; height:20px;" align="center">
          <!--<div style="padding-right:3px;"><img src="images/make_flix_private.jpg" width="181" height="24" border="0" /></div>-->
          <div style="padding-right:3px;">(Public Flix will be on your FotoPage)</div>
        </div>
      </td>
    </table>
  </div>
  
  <script language="javascript">
  function genCode()
  {
    _url = <?php echo '"http://' . FF_SERVER_NAME . '/js/flixlist_remote?key=' . $userData['U_KEY'] . '";'; ?>
    _url += "&width="+document.getElementById("width").value;
    _url += "&height="+document.getElementById("height").value;
    _url += "&cols="+document.getElementById("cols").value;
    _url += "&rows="+document.getElementById("rows").value;
    _url += "&bgcolor="+document.getElementById("bgcolor").value;
    _url += "&fontcolor="+document.getElementById("fontcolor").value;
    //_url += document.forms["flixconfigurator"].elements["hidetitle"][0].checked ? "&hidetitle=1" : "";
    _url += document.forms["flixconfigurator"].elements["thumbdisplay"][0].checked ? "&thumbdisplay=flix" : "&thumbdisplay=foto";
    _url += document.forms["flixconfigurator"].elements["detailfirstfoto"][0].checked ? "&detailfirstfoto=no" : "&detailfirstfoto=yes";
    
    document.forms["flixconfigurator"].elements["ffSrcCode"].value = "<" + "script language=\"javascript\" src=\"" + _url + "\"><" + "/script>";
    document.forms["flixconfigurator"].elements["ffSrcCode"].select();
    document.forms["flixconfigurator"].elements["ffSrcCode"].focus();
  }
  
  </script>
  <div style="width:545px; border-top:1px solid #9799a6; padding-bottom:10px;"></div>
  <div style="padding-bottom:10px; padding-left:4px;">
    <form name="flixconfigurator" style="display:inline;">
      <div style="padding-bottom:5px;">
        How many pixels wide should the Flix area be?<br/>
        <input type="text" value="500" id="width" size="4" class="formfield" onBlur="genCode();" />
      </div>
      <div style="padding-bottom:5px;">
        How many pixels tall should the Flix area be?<br/>
        <input type="text" value="400" id="height" size="4" class="formfield" />
      </div>
      <div style="padding-bottom:5px;">
        How many columns of Flix would you like?<br/>
        <select id="cols" class="formfield">
          <option value="1">1</option>
          <option value="2" selected="true">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
        </select>
      </div>
      <div style="padding-bottom:5px;">
        How many rows of Flix would you like?<br/>
        <select id="rows" class="formfield">
          <option value="1">1</option>
          <option value="2" selected="true">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
        </select>
      </div>
      <div style="padding-bottom:5px;">
        What color should the background be?<br/>
        <input type="text" value="ffffff" id="bgcolor" class="formfield" />
      </div>
      <div style="padding-bottom:5px;">
        What color should the text be?<br/>
        <input type="text" value="000000" id="fontcolor" class="formfield" />
      </div>
      <!--<div style="padding-bottom:5px;">
        Do you want the Flix name to be displayed?<br/>
        <input type="radio" name="hidetitle" value="1" CHECKED /> Yes&nbsp;&nbsp;
        <input type="radio" name="hidetitle" value="0" /> No&nbsp;&nbsp;
      </div>-->
      <div style="padding-bottom:5px;">
        Which thumbnails do you want to display?<br/>
        <input type="radio" name="thumbdisplay" value="0" CHECKED /> Thumbnail of Flix theme&nbsp;&nbsp;
        <input type="radio" name="thumbdisplay" value="1" /> Thumbnail of foto&nbsp;&nbsp;
      </div>
      <div style="padding-bottom:5px;">
        Do you want to attach the name &amp; description of the first foto?<br/>
        <input type="radio" name="detailfirstfoto" value="0" CHECKED /> No;&nbsp;
        <input type="radio" name="detailfirstfoto" value="1" /> Yes&nbsp;&nbsp;
      </div>
    </div>
    <div style="padding-bottom:10px; padding-left:4px;">
      <a href="javascript:genCode();"><img src="images/buttons/generate_code.gif" width="150" height="23" border="0" /></a>
    </div>
    <div align="center">
      <textarea name="ffSrcCode" class="formfield" style="width:530px; height:50px;"></textarea>
    </div>
  </form>
</div>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>
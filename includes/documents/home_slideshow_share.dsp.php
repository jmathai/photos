<?php
  $fl = &CFlix::getInstance();
  
  $key = $_GET['KEY'];
  $slideshowData = $fl->search(array('KEY' => $key));
  
  $fotoURL = dynamicImage($slideshowData['US_PHOTO']['thumbnailPath_str'], $slideshowData['US_PHOTO']['photoKey_str'], 150, 100);
  
  $fv =  new CFormValidator;
  $fv -> setForm('_emailSlideshow');
  $fv -> addElement('to', 'Recipient Email(s)', '  - Please enter at least one recipient\'s email address.', 'length');
  $fv -> addElement('from', 'Your Email', '  - Please enter your email address.', 'email');
  $fv -> addElement('subject', 'Subject', '  - Please enter a subject.', 'length');
  $fv -> addElement('message', 'Message', '  - Please enter a message.', 'length');
  $fv -> setMaxElementsToDisplay(5);
  $fv -> setDebugOutput(false);
  $fv -> setFunctionName('_validate');
  $fv -> validate();
  
  if(isset($_GET['confirmation']))
  {
    echo '<div class="center f_12 bold" style="margin-bottom:10px;"><img src="images/icons/checkmark_16x16.png" border="0" width="16" height="16" class="png" align="absmiddle" /> Your email has been sent</div>';
  }
  
  $swfPath = 'http://' . FF_SERVER_NAME . '/swf/container/dynamic/container_' . $slideshowData['US_WIDTH'] . '_' . $slideshowData['US_HEIGHT'] . '.swf?slideshowKey_str=' . $slideshowData['US_KEY'] . '&version=' . FF_VERSION_TEMPLATE . '&timestamp=' . NOW;
?>


<div style="width:400px; text-align:left;" class="margin-auto">
  <div style="padding-bottom:10px; padding-top:10px;" class="f_12 bold">Share Your Slideshow</div>
  <div style="margin-bottom:10px;" class="flix_border_medium"><img src="<?php echo $fotoURL; ?>" width="150" height="100" border="0" /></div>
  
  <div class="f_8 bold"><a href="javascript:void(0);" onclick="effLink.toggle(); $('_linkCodeResult').select();" class="plain"><img src="images/icons/document_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" /> Copy/paste slideshow link</a></div>
  <div id="_linkCode" style="margin-top:10px; margin-bottom:10px;">
    <div class="bold">Copy and paste this link to share slideshow</div>
    <div><textarea id="_linkCodeResult" class="formfield" style="width:350px; height:50px;">http://<?php echo FF_SERVER_NAME; ?>/slideshow?<?php echo $key; ?></textarea></div>
  </div>
  
  <div class="f_8 bold"><a href="javascript:void(0);" onclick="effEmail.toggle();" class="plain"><img src="images/icons/mail_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" /> Email a link to this slideshow</a></div>
  <div id="_emailForm" style="margin-top:10px;">
    <form action="/?action=home.slideshow_share.act" method="post" name="_emailSlideshow" id="_emailSlideshow" onsubmit="return _emailSlideshow();">
    
      <div class="bold">Recipient Email(s)</div>
      <div><textarea name="to" id="to" class="formfield" style="width:250px; height:40px;" wrap="virtual"></textarea></div>
      <div>(Separate multiple email addresses with a comma)</div>
      
      <br/>
      
      <div class="bold">Your Email</div>
      <div><input type="text" name="from" id="from" class="formfield" size="33" /></div>
      
      <br/>
      
      <div class="bold">Subject</div>
      <div><input type="text" name="subject" id="subject" class="formfield" size="33" /></div>
      
      <br/>
      
      <div class="bold">Message</div>
      <div><textarea name="message" id="message" class="formfield" style="width:300px; height:100px;"></textarea></div>
      
      <br/>
      
      <div><input type="image" src="images/buttons/send_email.gif" width="150" height="23" border="0" /></div>
      <input type="hidden" name="redirect" value="1" />
      <input type="hidden" name="key" value="<?php echo $key; ?>" />
    </form>
  </div>
  
  <br/>
  
  <div id="_website">
    <div class="f_8 bold"><a href="javascript:void(0);" onclick="effEmbed.toggle(); $('_embedCodeResult').select();" class="plain"><img src="images/icons/document_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" /> Embed this slideshow in a blog or web site</a></div>
    <div id="_embedCode" style="margin-top:10px;">
      <div class="bold">Copy and paste this code into web page</div>
      <div><textarea id="_embedCodeResult" class="formfield" style="width:350px; height:50px;"></textarea></div>
      <div><a href="javascript:updateEmbed('blog');">Blog</a> | <a href="javascript:updateEmbed('myspace');">My Space</a></div>
    </div>
    
    <br />
    
    <div class="f_8 bold"><a href="javascript:void(0);" onclick="effPopup.toggle(); $('_popupCodeResult').select();" class="plain"><img src="images/icons/document_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" /> Link to a popup</a></div>
    <div id="_popupCode" style="margin-top:10px;">
      <div class="bold">Copy and paste this code into web page</div>
      <div><textarea id="_popupCodeResult" class="formfield" style="width:350px; height:50px;"></textarea></div>
    </div>
    
    <br/>
  </div>
</div>
<script type="text/javascript">
  function updateEmbed(type)
  {
    var blog = '<script src="http://<?php echo FF_SERVER_NAME; ?>/js/slideshow_remote/<?php echo $slideshowData['US_KEY']; ?>/"> </scr'+'ipt>';
    //var myspace = '<?php echo '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="' . $slideshowData['US_WIDTH'] . '" height="' . $slideshowData['US_HEIGHT'] . '"><param name="movie" value="' . $swfPath . '" />\n<param name="menu" value="false" />\n<param name="quality" value="high" />\n<param name="bgcolor" value="#ffffff" />\n<embed src="' . $swfPath . '" menu="false" quality="high" bgcolor="#ffffff" width="' . $slideshowData['US_WIDTH'] . '" height="' . $slideshowData['US_HEIGHT'] . '" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>\n</object>'; ?>';
    var myspace = '<?php echo '<embed src="' . $swfPath . '" menu="false" quality="high" bgcolor="#ffffff" width="' . $slideshowData['US_WIDTH'] . '" height="' . $slideshowData['US_HEIGHT'] . '" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>'; ?>';
    switch(type)
    {
      case 'blog':
        $('_embedCodeResult').value = blog;
        $('_embedCodeResult').select();
        break;
      case 'myspace':
        $('_embedCodeResult').value = myspace;
        $('_embedCodeResult').select();
        break;
    }
  }
  
  function updatePopup()
  {
    var popup = '<a href="javascript:void(0);" onclick="window.open(\'http://<?php echo FF_SERVER_NAME; ?>/popup/slideshow/<?php echo $slideshowData['US_KEY']; ?>/\', \'<?php echo 'ptg_' . NOW; ?>\',\'width=<?php echo $slideshowData['US_WIDTH'];?>,height=<?php echo $slideshowData['US_HEIGHT']; ?>,location=no,menubar=no,resizable=no,scrollbars=no,status=no,toolbar=no,fullscreen=no\');">View Slideshow</a>';
    $('_popupCodeResult').value = popup;
  }
  
  var effLink = new fx.Height('_linkCode');
  var effEmail = new fx.Height('_emailForm');
  var effEmbed = new fx.Height('_embedCode');
  var effPopup = new fx.Height('_popupCode');
  
  effLink.hide();
  effEmail.hide();
  effEmbed.hide();
  effPopup.hide();
  
  updateEmbed('blog');
  updatePopup();
</script>
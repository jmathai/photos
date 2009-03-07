<?php
  if(strlen($_COOKIE['registrationKey']) != 32)
  {
    echo '<script type="text/javascript"> location.href="/"; </script>';
    die();
  }
?>

<div style="border:1px solid gray; width:680px; margin:auto;">
  <div style="border-bottom:1px solid gray; padding-left:5px; padding-top:5px; padding-bottom:5px;" class="f_16">Cheaper than your morning latte</div>
  <div style="float:left; margin-left:10px;">
    <div style="padding-top:15px;" class="f_12 bold">Try it free for 7 days</div>
    <div style="padding-top:10px;" class="f_12 bold">No commitment</div>
    <div style="padding-top:10px;" class="f_12 bold">No ads</div>
    <div style="padding-top:10px;" class="f_12 bold">Create personalized slideshows</div>
    <div class="f_12 bold"></div>
    <div style="padding-top:50px; padding-left:75px;"><a href="/?action=home.registration_form_b"><img src="images/buttons/register.gif" border="0" width="87" height="27" /></a></div>
    <div style="padding-top:3px; padding-left:55px;" class="f_8 bold"><a href="/?action=home.more_info">Still not convinced?</a></div>
    <!-- <div style="padding-top:35px;" class="f_8 bold"><a href="">Why pay</a> when some sites are free?</div> -->
  </div>
  <div style="float:left; margin-top:15px; margin-left:10px; margin-bottom:10px; border:1px solid gray;"><img src="images/homepage/child1.jpg" border="0" width="350" height="263" /></div>
  <br clear="all" />
</div>
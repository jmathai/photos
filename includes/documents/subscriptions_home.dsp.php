<?php
  $s = &CSubscription::getInstance();
  
  $subscriptions = $s->getSubscriptions($_USER_ID);
  
  $fv = new CFormValidator();
  $fv -> setForm('_subscriptionsForm');
  $fv -> addElement('email_1', 'Email', '  - Please enter at least one email address.', 'email');
  $fv -> setMaxElementsToDisplay(5);
  $fv -> setDebugOutput(false);
  $fv -> setFunctionName('_val_registration');
  $fv -> validate();
  
  if(isset($_GET['message']))
  {
    switch($_GET['message'])
    {
      case 'added':
        $message = '<img src="images/icons/checkmark_16x16.png" class="png" width="16" height="16" hspace="5" align="absmiddle" />Email addresses have been added';
        break;
      case 'deleted':
        $message = '<img src="images/icons/delete_16x16.png" class="png" width="16" height="16" hspace="5" align="absmiddle" />Email address has been deleted.';
        break;
    }
    
    if($message != '')
    {
      echo '<div class="confirm">' . $message . '</div>';
    }
  }
?>

<div class="f_12 bold"><img src="images/icons/network-wireless_22x22.png" class="png" width="22" height="22" align="absmiddle" />&nbsp;Let others know when you add photos or create a slideshow</div>

<div style="margin-left:25px; margin-top:25px;">
  <div style="margin-bottom:5px;">When you add a photo or slideshow to your personal page the following people will receive an email.</div>
  
  <form name="_subscriptionsForm" id="_subscriptionsForm" method="POST" action="/?action=subscriptions.home.act">
    <div style="margin-bottom:5px;"><input type="text" name="email_1" size="30" class="formfield" /></div>
    <div style="margin-bottom:5px;"><input type="text" name="email_2" size="30" class="formfield" /></div>
    <div style="margin-bottom:5px;"><input type="text" name="email_3" size="30" class="formfield" /></div>
    <div style="margin-bottom:5px;"><input type="text" name="email_4" size="30" class="formfield" /></div>
    <div style="margin-bottom:5px;"><input type="text" name="email_5" size="30" class="formfield" /></div>
    
    <div><a href="javascript:void(0);" onclick="if(_val_registration()){ $('_subscriptionsForm').submit(); }" class="plain"><img src="images/icons/network-wireless_16x16.png" width="16" height="16" border="0" align="absmiddle" />&nbsp;Add these emails</a></div>
    <input type="hidden" name="method" value="push" />
  </form>
  
<?php
  if(count($subscriptions) > 0)
  {
    echo '<div style="margin-top:35px; margin-bottom:3px;" class="bold">Email addresses currently on your list</div>';
    foreach($subscriptions as $k => $v)
    {
      echo '<div><a href="/?action=subscriptions.home.act&email=' . $v['S_EMAIL'] . '" class="plain"><img src="images/icons/delete_16x16.png" class="png" width="16" height="16" vspace="2" border="0" align="absmiddle" /></a>&nbsp;' . $v['S_EMAIL'] . '</div>';
    }
  }
?>

</div>
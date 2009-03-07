<?php
  $pm = &CPrivateMessage::getInstance();
  $hasOptedOut = $pm->hasOptedOut($_USER_ID);
  $banned = $pm->getBanned($_USER_ID);
?>

<div class="f_10 bold">Private Messages</div>

<?php
  if($hasOptedOut === false)
  {
?>

    <div style="text-align:left; padding-top:25px; padding-left:25px;">
      <div style="float:left;" class="f_8 bold">Message Box</div><div style="float:left; padding-left:15px;" class="f_8"><a href="javascript:void(pm_optOut());">disasble private messages</a></div>
      <br />
      <div style="float:left; padding-top:5px; padding-left:10px;" class="f_8 bold"><a href="/?action=pm.inbox">Inbox</a></div>
      <?php
        if( $pm->newMessagesExist($_USER_ID) )
        {
      ?>
          <div style="float:left; padding-left:10px; padding-top:6px;" class="f_7">New Messages!</div>
      <?php
        }
      ?>
      <br />
      <div style="padding-top:5px; padding-left:10px;" class="f_8 bold"><a href="/?action=pm.outbox">Outbox</a></div>
    </div>
    
    <div style="text-align:left; padding-top:25px; padding-left:25px;">
      <div class="f_8 bold">Banned List</div>
      <div style="padding-top:15px; padding-left:10px;" class="f_8">
        <?php
          if(count($banned) == 0)
          {
            echo '<div>No banned users</div>';
          }
          else 
          {
            foreach($banned as $k => $v)
            {
              echo '<div>' . $v['U_USERNAME'] . '</div>';
            }
          }
        ?>
      </div>
    </div>

<?php
  }
  else 
  {
?>

    <div style="text-align:left; padding-top:25px; padding-left:25px;">
      <div style="float:left;" class="f_8 bold">Private messages are disabled</div><div style="float:left; padding-left:15px;" class="f_8"><a href="javascript:void(pm_optIn());">enable private messages</a></div>
      <br />
    </div>
    
<?php
  }
?>

<div style="text-align:left; padding-top:25px; padding-left:25px;">
  <div class="f_8 bold">Person A</div>
  <div><a href="javascript:void(pm_newMessage('_newMessage', 2));">Send PM</a></div>
  <div id="_newMessage" style="display:block; z-index:75;"></div>
  
  <?php
    if( $pm->isBanned(2, $_USER_ID) )
    {
  ?>
      <div id="_unban"><a href="javascript:void(pm_unBan(2));">Unban User</a></div>
  <?php
    }
    else
    {
  ?>
      <div id="_ban"><a href="javascript:void(pm_ban(2));">Ban User</a></div>
  <?php
    }
  ?>
 
</div>    

<?php  
  $tpl->main($tpl->get());
  $tpl->clean();
?>
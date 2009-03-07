<?php
  $u =& CUser::getInstance();
  $s = &CSubscription::getInstance();
  
  $userData   = $u->find($_USER_ID);
  $pageData   = $u->page($_USER_ID);
  $subscriptions = $s->getSubscriptions($_USER_ID);
  
  $pageStyle = !empty($pageData['P_COLORS']) ? $pageData['P_COLORS'] : 'light_slate';
  
  if($_USER_ID == $user_id)
  {
    
    if($_SERVER['QUERY_STRING'] == 'updated')
    {
      echo '<div style="padding-bottom:10px;" class="f_12 bold">
              <img src="/images/icons/checkmark_24x24.png" class="png" width="24" height="24" border="0" hspace="4" align="absmiddle" />Your settings have been saved
            </div>';
    }
?>
    <form method="post" action="/?action=my.page_settings.act" name="myFotoPage" style="display:inline;">
      <div class="bold" style="padding-bottom:3px;">
        Password protect your page
      </div>
      <div style="padding-left:15px;">
        <?php
          if($pageData['P_PASSWORD'] == '')
          {
            echo '<div><input type="radio" name="pword" onclick="$(\'passwordProtect\').style.display=\'none\'; $(\'p_password\').value=\'\';" checked="true" /> No thanks, I don\'t need my personal page password protected</div>
                  <div><input type="radio" name="pword" onclick="$(\'passwordProtect\').style.display=\'block\';" /> Yes, I would like to password protect my personal page</div>
                  <div id="passwordProtect" style="padding-left:25px; padding-top:5px; display:none;"><input type="password" id="p_password" name="p_password" class="formfield" maxlength="16" value="' . htmlentities($pageData['P_PASSWORD']) . '" style="width:100px;" />&nbsp;&nbsp;(Type a password for your photo page)</div>';
          }
          else
          {
            echo '<div><input type="radio" name="pword" onclick="$(\'passwordProtect\').style.display=\'none\'; $(\'p_password\').value=\'\';" /> No thanks, I don\'t need my photo page password protected</div>
                  <div><input type="radio" name="pword" onclick="$(\'passwordProtect\').style.display=\'block\';" checked="true" /> Yes, I would like to password protect my photo page</div>
                  <div id="passwordProtect" style="padding-left:25px; padding-top:5px; display:block;"><input type="password" id="p_password" name="p_password" class="formfield" maxlength="16" value="' . htmlentities($pageData['P_PASSWORD']) . '" style="width:100px;" />&nbsp;&nbsp;(Type a password for your photo page)</div>';
          }
        ?>
      </div>
      
      <div style="padding-top:25px;"></div>
    
      <div class="bold" style="padding-bottom:3px;">
        Specify your page's title
      </div>
      <div style="padding-left:15px;">
        <textarea name="p_description" class="formfield" wrap="virtual" style="width:250px; height:75px;"><?php echo $pageData['P_DESCRIPTION']; ?></textarea>
      </div>
      
      <div style="padding-top:25px;"></div>
    
      <div class="bold" style="padding-bottom:3px;">
        Automatically notify others when your personal page changes (<a href="/xml_result?action=fotopage_what_is_subscription" class="lbOn">what's this</a>)
      </div>
      <div style="padding-left:15px;" id="emailDiv">
        <textarea name="addSubscription" class="formfield" wrap="virtual" style="width:275px; height:75px;">(enter each email address on a new line)<?php
            foreach($subscriptions as $k => $v)
            {
              echo "\n" . $v['S_EMAIL'];
            }
          ?></textarea>
      </div>
      <!--
      <div style="padding:3px 0px 0px 15px;">(<a href="javascript:void(0);" onclick="effEmailExisting.toggle();">Would you like to view or remove an email address?</a>)</div>
      <div id="emailExistingDiv">
        <div style="padding:5px 0px 0px 15px;">
          <?php
            if(count($subscriptions) > 0)
            {
              foreach($subscriptions as $k => $v)
              {
                echo '<div><input type="checkbox" value="' . $v['S_EMAIL'] . '" name="removeSubscription[]" />&nbsp;' . $v['S_EMAIL'] . '</div>';
              }
            }
            else
            {
              echo 'No email addresses to show';
            }
          ?>
        </div>
      </div>-->
      
      <div style="padding-top:15px;">
        <a href="javascript:document.forms['myFotoPage'].submit();" class="bold f_white f_11 plain"><img src="images/icons/save_24x24.png" class="png" width="24" height="24" hspace="3" border="0" align="absmiddle" />Save Settings</a>
      </div>
      <input type="hidden" name="redirect" value="/users/<?php echo $username; ?>/settings/?updated" />
    </form>
<?php
  }
  else
  {
    echo '<div class="bold italic center">You need to be logged in as ' . $username . ' to view this page.</div>';
  }
?>
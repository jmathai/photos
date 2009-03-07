<?php
  $us =& CUser::getInstance();
  $subAccounts = $us->subAccounts($_USER_ID);
?>

<?php
  echo '<div style="padding-left:5px;">';
  foreach($subAccounts as $v)
  {
    echo '
          <div style="width:540px; margin-top:10px; text-align:left;">
            <div style="float:left; width:35px;"><a href="javascript:saShow' . $v['SA_ID'] . '.toggle(); editSubAccountPerms(' . $v['SA_ID'] . ', saPermShow' . $v['SA_ID'] . ');">edit</a></div>
            <div style="float:left; width:150px;">' . $v['SA_USERNAME'] . '</div>
            <div style="float:left; width:175px;">' . ($v['SA_EMAIL'] != '' ? $v['SA_EMAIL'] : 'N/A') . '</div>
            <div style="float:left; width:75px;">' . date('m-d-Y', $v['SA_LASTLOGIN']) . '</div>
            <br/>
            <div id="subAccount' . $v['SA_ID'] . '" style="margin-top:10px;">
              <div style="height:17px; margin-top:3px;">
                <div style="height:13px; width:140px; padding:2px; border-left:solid 1px #c0c0c0; border-top:solid 1px #c0c0c0; border-right:solid 1px #c0c0c0; text-align:center; float:left;" class="bold">
                  Edit this sub account
                </div>
                <div style="height:100%; width:394px; border-bottom:solid 1px #c0c0c0; float:left;"></div>
                <br/>
              </div>
              <div id="subAccountDetails' . $v['SA_ID'] . '" style="height:50px; padding-left:5px; padding-top:10px; border-left:solid 1px #c0c0c0; border-right:solid 1px #c0c0c0;">
                <div style="float:left; padding-right:20px;">
                  Username:<br/>
                  <input type="text" id="sa_username_' . $v['SA_ID'] . '" value="' . $v['SA_USERNAME'] . '" class="formfield" style="width:100px;" />
                </div>
                <div style="float:left; padding-right:20px;">
                  Email<br/>
                  <input type="text" id="sa_email_' . $v['SA_ID'] . '" value="' . $v['SA_EMAIL'] . '" class="formfield" style="width:125px;" />
                </div>
                <div style="float:left; padding-right:20px;">
                  Password<br/>
                  <input type="text" id="sa_password_' . $v['SA_ID'] . '" value="' . $v['SA_PASSWORD'] . '" class="formfield" style="width:100px;" />
                </div>
                <div style="float:left; padding-top:15px;"><a href="javascript:setSubAccountDetails(' . $v['SA_ID'] . ', document.getElementById(\'sa_username_' . $v['SA_ID'] . '\').value, document.getElementById(\'sa_email_' . $v['SA_ID'] . '\').value, document.getElementById(\'sa_password_' . $v['SA_ID'] . '\').value);">save</a></div>
                <br clear="left" />
                <div style="width:90%; padding-top:5px; margin:auto;"><div class="line_lite"></div></div>
                <br/>
              </div>
            </div>
            <script type="text/javascript">
              var saShow' . $v['SA_ID'] . ' = new fx.Height("subAccount' . $v['SA_ID'] . '", {duration:400});
              saShow' . $v['SA_ID'] . '.hide();
            </script>
            
            <div id="subAccountPerms' . $v['SA_ID'] . '" style="border-left:solid 1px #c0c0c0; border-right:solid 1px #c0c0c0;">
              <div style="padding-left:5px; padding-bottom:15px;"> 
                <div style="text-align:center;">
                  <div style="padding-left:90px; width:60px; float:left;">View</div>
                  <div style="width:60px; float:left;">Create</div>
                  <div style="width:60px; float:left;">Update</div>
                  <div style="width:60px; float:left;">Delete</div>
                </div> 
                <div id="subAccountPermsText' . $v['SA_ID'] . '">loading...</div>
              </div>
              
              <div style="border-bottom:solid 1px #c0c0c0;"></div>
            </div>
            <script type="text/javascript">
              var saPermShow' . $v['SA_ID'] . ' = new fx.Height("subAccountPerms' . $v['SA_ID'] . '", {duration:400});
              saPermShow' . $v['SA_ID'] . '.hide();
            </script>

          </div>
          <div class="line_lite" style="padding-top:5px;"></div>
          ';
  }
  echo '</div>';
?>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>
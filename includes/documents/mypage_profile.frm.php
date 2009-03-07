<?php
  $u =& CUser::getInstance();
  $profileData = $u->profile($_USER_ID);
  
  $avatarSrc = $profileData['P_AVATAR'] != '' ? PATH_FOTO . $profileData['P_AVATAR'] : 'images/avatar.jpg';
?>

<div style="width:545px;">
  
  <div style="padding-bottom:10px;" align="left">
    Your profile just tells others a little bit about yourself.  
    All of the fields are optional so fill in whatever you want others to know about you!
  </div>
  
  <div style="width:545px; border-top:1px solid #9799a6; padding-bottom:10px;"></div>
  
  <table border="0" cellpadding="0" cellspacing="0" width="545">
    <tr>
      <td width="150" align="center" valign="top">
        <div><img src="images/fb_frame_top.gif" width="87" height="5" border="0" /></div>
        <div><img src="images/fb_frame_left.gif" width="5" height="75" border="0" /><img src="<?php echo $avatarSrc; ?>" id="avatarFoto" width="75" height="75" border="0" /><img src="images/fb_frame_right.gif" width="7" height="75" border="0" /></div>
        <div><img src="images/fb_frame_bottom.gif" width="87" height="7" border="0" /></div>
        <?php
          if($profileData['P_AVATAR'] != '')
          {
            echo '<a href="/?action=fotobox.avatar.act&id=0">Remove avatar<br /></a>';
          }
        ?>
        <a href="javascript:void(changeAvatar(''));">Change avatar<br /></a>
        <div style="display:none; text-align:left; margin-top:-115px; margin-left:55px;" id="avatarBlank"></div>
      </td>
      <td width="395" valign="top" align="left">
        <form name="_profile" action="/?action=mypage.profile.act" method="post">
          <div style="padding-top:10px;">
            <div style="float:left; width:110px;" class="bold">Your gender</div>
            <div style="float:left; margin-top:-2px;"><input type="radio" name="p_gender" value="Male" <?php if($profileData['P_GENDER'] == 'Male'){ echo 'CHECKED'; } ?> /></div>
            <div style="float:left; padding-right:4px;">Male</div>
            <div style="float:left; margin-top:-2px;"><input type="radio" name="p_gender" value="Female" <?php if($profileData['P_GENDER'] == 'Female'){ echo 'CHECKED'; } ?> /></div>
            <div style="float:left; padding-right:4px;">Female</div>
            <div style="float:left; margin-top:-2px;"><input type="radio" name="p_gender" value="Neither" <?php if($profileData['P_GENDER'] == 'Neither' || !isset($profileData['P_GENDER'])){ echo 'CHECKED'; } ?> /></div>
            <div style="float:left;">Neither</div>
            <br clear="all" />
          </div>
          <div style="padding-top:10px;">
            <div style="float:left; width:110px;" class="bold">Your birthdate</div>
            <div style="float:left; margin-top:-2px;"><input type="text" name="p_dateBirth" class="formfield" style="width:70px;" value="<?php if(intval($profileData['P_DATEBIRTH']) > 0){ echo date('m/d/Y', $profileData['P_DATEBIRTH']); } ?>" /></div>
            <div style="float:left; padding-left:4px;" class="italic">m/d/y</div>
            <br clear="all" />
          </div>
          <div style="padding-top:10px;">
            <div style="float:left; width:110px;" class="bold">Your city</div>
            <div style="float:left; margin-top:-2px;"><input type="text" name="p_city" class="formfield" style="width:100px;" value="<?php echo $profileData['P_CITY']; ?>" /></div>
            <br clear="all" />
          </div>
          <div style="padding-top:10px;">
            <div style="float:left; width:110px;" class="bold">Your state</div>
            <div style="float:left; margin-top:-2px;">
              <select name="p_state" class="formfield">
                <?php
                  echo optionStates($profileData['P_STATE']);
                ?>
              </select>
            </div>
            <br clear="all" />
          </div>
          <div style="padding-top:10px;">
            <div style="float:left; width:110px;" class="bold">Your zipcode</div>
            <div style="float:left; margin-top:-2px;"><input type="text" name="p_zip" class="formfield" style="width:50px;" value="<?php echo $profileData['P_ZIP']; ?>" /></div>
            <br clear="all" />
          </div>
          <div style="padding-top:10px;">
            <div style="float:left; width:110px;" class="bold">Your email</div>
            <div style="float:left; margin-top:-2px;"><input type="text" name="p_email" class="formfield" style="width:150px;" value="<?php echo $profileData['P_EMAIL']; ?>" /></div>
            <br clear="all" />
          </div>
          <div style="padding-top:10px;">
            <div style="float:left; width:110px; " class="bold">Your website</div>
            <div style="float:left; margin-top:-2px;"><input type="text" name="p_website" class="formfield" style="width:200px;" value="<?php echo $profileData['P_WEBSITE']; ?>" /></div>
            <br clear="all" />
          </div>
          <div style="padding-top:10px;">
            <div style="float:left; width:110px;" class="bold">Your AOL IM</div>
            <div style="float:left; margin-top:-2px;"><input type="text" name="p_imAol" class="formfield" style="width:100px;" value="<?php echo $profileData['P_IMAOL']; ?>" /></div>
            <br clear="all" />
          </div>
          <div style="padding-top:10px;">
            <div style="float:left; width:110px;" class="bold">Your Yahoo! IM</div>
            <div style="float:left; margin-top:-2px;"><input type="text" name="p_imYahoo" class="formfield" style="width:100px;" value="<?php echo $profileData['P_IMYAHOO']; ?>" /></div>
            <br clear="all" />
          </div>
          <div style="padding-top:10px;">
            <div style="float:left; width:110px;" class="bold">Your MSN IM</div>
            <div style="float:left; margin-top:-2px;"><input type="text" name="p_imMsn" class="formfield" style="width:100px;" value="<?php echo $profileData['P_IMMSN']; ?>" /></div>
            <br clear="all" />
          </div>
          <div style="padding-top:10px;">
            <div style="float:left; width:110px;" class="bold">Your ICQ #</div>
            <div style="float:left; margin-top:-2px;"><input type="text" name="p_imIcq" class="formfield" style="width:100px;" value="<?php echo $profileData['P_IMICQ']; ?>" /></div>
            <br clear="all" />
          </div>
          <div style="padding-top:10px;">
            <div style="float:left; width:110px;" class="bold">Your camera</div>
            <div style="float:left; margin-top:-2px;"><input type="text" name="p_camera" class="formfield" style="width:100px;" value="<?php echo $profileData['P_CAMERA']; ?>" /></div>
            <br clear="all" />
          </div>
          <div style="padding-top:10px;">
            <div style="float:left; width:110px; margin-top:28px" class="bold">About yourself</div>
            <div style="float:left; margin-top:-2px;"><textarea name="p_description" class="formfield" style="width:250px; height:75px;"><?php echo $profileData['P_DESCRIPTION']; ?></textarea></div>
            <br clear="all" />
          </div>
          <div style="padding-top:10px;">
            <div style="float:left; width:110px; margin-top:28px" class="bold">Your interests</div>
            <div style="float:left; margin-top:-2px;"><textarea name="p_interests" class="formfield" style="width:250px; height:75px;"><?php echo $profileData['P_INTERESTS']; ?></textarea></div>
            <br clear="all" />
          </div>
          <div style="padding-top:10px;">
            <div style="float:left; width:110px; margin-top:28px" class="bold">Favorite music</div>
            <div style="float:left; margin-top:-2px;"><textarea name="p_music" class="formfield" style="width:250px; height:75px;"><?php echo $profileData['P_MUSIC']; ?></textarea></div>
            <br clear="all" />
          </div>
          <div style="padding-top:10px;">
            <div style="float:left; width:110px; margin-top:28px" class="bold">Favorite books</div>
            <div style="float:left; margin-top:-2px;"><textarea name="p_books" class="formfield" style="width:250px; height:75px;"><?php echo $profileData['P_BOOKS']; ?></textarea></div>
            <br clear="all" />
          </div>
          <div style="padding-top:10px;">
            <div style="float:left; width:110px; margin-top:28px" class="bold"></div>
            <div style="float:left;"><a href="javascript:document.forms['_profile'].submit();"><img src="images/buttons/update.gif" width="87" height="27" border="0" /></a></div>
            <br clear="all" />
          </div>
          <input type="hidden" name="p_u_id" value="<?php echo $_USER_ID; ?>" />
        </form>
      </td>
    </tr>
  </table>
</div>

<?php
  $tpl->main($tpl->get());
  $tpl->clean();
?>
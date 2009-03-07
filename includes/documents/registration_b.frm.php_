<?php
  $_SESSION_HASH = $_FF_SESSION->value('sess_hash');

  if(!isset($_get_udf))
  {
    $_get_udf = $_REQUEST;
  }

  foreach($_GET as $k => $v)
  {
    if(!isset($_get_udf[$k]))
    {
      $_get_udf[$k] = $v;
    }
  }

  $fv =  new CFormValidator;

  $required = ''; //'<span class="f_dark_accent">&nbsp;<sup>*</sup></span>';

  $fv -> setForm('_registration');
  $fv -> addElement('u_username', 'Username', '  - Username must be between 4 and 16 alpha-numeric characters.', 'regexp/^[a-zA-Z0-9_]{4`,16}$/');
  $fv -> addElement('u_password', 'Password', '  - Please enter a password.', 'length');
  //$fv -> addElement('u_password_confirm', 'Confirm Password', '  - Please confirm your password.', 'length');
  //$fv -> addElement('u_password', 'Confirm Password', '  - Your password confirmation does not match your password.', 'regexp/^\'+document.forms[\'_registration\'].u_password_confirm.value+\'$/');
  $fv -> addElement('u_email', 'Email', '  - Please enter your email address.', 'email');
  $fv -> addElement('u_birthMonth', 'Birth Month', '  - Please select the month you were born.', 'selectboxnull');
  $fv -> addElement('u_birthDay', 'Birth Day', '  - Please select the day you were born.', 'selectboxnull');
  $fv -> addElement('u_birthYear', 'Birth Year', '  - Please select the year you were born.', 'selectboxnull');
  $fv -> addElement('u_secret', 'Mother\'s Maiden Name', '  - Please enter your mother\'s maiden name.', 'length');
  $fv -> setMaxElementsToDisplay(5);
  $fv -> setDebugOutput(false);
  $fv -> setFunctionName('_val_registration');
  $fv -> validate();
  // validate method called at bottom

  echo samplesNavigation(array('samples','demo','tour','features','aboutus'));

  if(isset($_get_udf['message']))
  {
    switch($_get_udf['message'])
    {
      case 'password_email_mismatch':
        $message = 'Verify that your password and email confirmation match.';
        break;
      case 'email_exists':
        $message = 'The email address you entered is already in our system or contains invalid syntax.';
        break;
      case 'username_exists':
        $message = 'The username you selected is taken.  Please select another username.';
        break;
      case 'account_info':
        $message = 'Account information does not exist, please enter your account information.';
        break;
      default:
        $message = '';
        break;

    }
    echo '<div class="confirm">' . $message . '</div>';
  }
?>

<br/>

<div class="dataSingleContent">
  <form name="_registration" id="_registration" method="post" action="/?action=home.registration_form_b.act&redirect=<?php echo urlencode($redirect); ?>" onsubmit="return _val_registration();">
    <div style="margin-bottom:15px;">
      <?php
        /*switch($_GET['promotion'])
        {
          case 'ipod':
            echo '<div class="f_11 f_red bold center">Enter using the form below for a chance to win an iPod Nano!</div><br/>';
            break;
        }*/
      ?>
      <div style="float:left;">
        <?php
          switch($_GET['promotion'])
          {
            /*case 'ipod':
              echo '<div><img src="images/promotions/ipod_registration_graphic.gif" width="150" height="100" hspace="10" /></div>
                    <div class="center"><a href="/?action=home.ipod_rules" class="plain">contest rules</a></div>
                    <input type="hidden" name="promotion" value="ipod" />';
              break;*/
            default:
              echo '<img src="images/homepage/free_trial_graphic_2.jpg" width="150" height="100" hspace="10" class="border_dark" />';
              break;
          }
        ?>
      </div>
      <div style="float:left; width:525px;">
        <div class="f_11 bold">Start your free trial now!</div>
        <div class="f_10">
          Sign up below to begin your free 7 day trial.
          We’re confident that you'll love your Photagious service.
          <br/><br/>
          <div class="bold">Sign up for as low as $3.95 a month or get 2 months free when signing up for a year!</div>
        </div>
      </div>
      <br clear="all" />
    </div>

    <div>
      <div class="bold"><img src="images/icons/user_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" hspace="5" />Account Information</div>
    </div>

    <div class="formBlock">
      <div class="formRow">
        <div class="formLabel">Username</div>
        <div class="formField">
          <input type="text" name="u_username" id="u_username" value="<?php echo $_get_udf['u_username']; ?>" class="formfield" style="width:110px" onblur="checkUsername(this);" /><?php echo $required; ?>&nbsp;(4 to 16 characters)
        </div>
        <div class="formIndent italic" id="_usernameSuggest"></div>
      </div>

      <div class="formRow">
        <div class="formLabel">Password</div>
        <div class="formField"><input type="password" name="u_password" value="" class="formfield" style="width:110px" /><?php echo $required; ?></div>
      </div>

<!--
      <div class="formRow">
        <div class="formLabel">Confirm Password</div>
        <div class="formField"><input type="password" name="u_password_confirm" value="" class="formfield" style="width:110px" onblur="$('_passwordMismatch').style.display = this.value != this.form.u_password.value ? 'inline' : 'none';" /><?php echo $required; ?></div>
        <div class="formIndent bold f_red" id="_passwordMismatch" style="display:none;" class="italic">Sorry but your passwords don't match.</div>
      </div>
-->

      <?php
        if(!isset($force_email))
        {
      ?>
          <div class="formRow">
            <div class="formLabel">Email</div>
            <div class="formField"><input type="text" name="u_email" id="u_email" value="<?php if(isset($_get_udf['u_email'])){ echo $_get_udf['u_email']; } ?>" <?php if($_get_udf['fotoflixTrialUser'] != '101'){ echo 'onblur="checkEmail(this);"'; } ?> class="formfield" style="width:135px" /><?php echo $required; ?></div>
            <div class="formIndent italic bold f_red" id="_emailSuggest"></div>
          </div>
      <?php
        }
        else
        {
          echo '<input type="hidden" name="u_email" value="' . $force_email . '" />';
        }
      ?>

      <div class="formRow">
        <div class="formLabel">Birth Date</div>
        <div class="formField">
          <select name="u_birthMonth" class="formfield" style="width:40px">
            <option value="null">--</option>
            <?php
              for($i=1; $i<=12; $i++)
              {
                $selected = '';
                if(isset($_get_udf['u_birthMonth']))
                {
                  $selected = $_get_udf['u_birthMonth'] == $i ? ' SELECTED' : '';
                }
                echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
              }
            ?>
          </select>
          /
          <select name="u_birthDay" class="formfield" style="width:40px">
            <option value="null">--</option>
            <?php
              for($i=1; $i<=31; $i++)
              {
                $selected = '';
                if(isset($_get_udf['u_birthDay']))
                {
                  $selected = $_get_udf['u_birthDay'] == $i ? ' SELECTED' : '';
                }
                echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
              }
            ?>
          </select>
          /
          <select name="u_birthYear" class="formfield" style="width:55px">
            <option value="null">----</option>
            <?php
              $end = date('Y', NOW) - 13;
              for($i=$end; $i>=1900; $i--)
              {
                $selected = '';
                if(isset($_get_udf['u_birthYear']))
                {
                  $selected = $_get_udf['u_birthYear'] == $i ? ' SELECTED' : '';
                }

                echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
              }
            ?>
          </select>
          <?php echo $required; ?>&nbsp;(m/d/y)
        </div>
      </div>

      <div class="formRow">
        <div class="formLabel">Account Type</div>
        <div class="formField"><input type="radio" name="u_accountType" id="u_accountType" value="<?php echo PERM_USER_0; ?>" checked="true" class="formfield" /> Personal &nbsp;&nbsp;&nbsp;<input type="radio" name="u_accountType" id="u_accountType" value="<?php echo PERM_USER_1; ?>" class="formfield" /> Professional<?php echo $required; ?>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="effDif.toggle();">What's the difference?</a></div>
      </div>
      
      <div id="difference">
        <div style="border:1px solid gray; background-color:#FFFFFF; margin-top:10px; width:600px; height:200px; overflow:auto;">
          <div style="margin-left:10px;">
            <div style="padding-top:10px;" class="f_12 bold">What's the difference?</div>
            <div style="padding-top:10px;" class="f_10 bold">Personal Account</div>
            <div style="padding-top:5px;">Allows you to upload and share as many photos and slideshows as you want.  You'll be able to personalize your slideshows and create your personal home page.  All for just $3.95/month.</div>
            <div style="padding-top:20px;" class="f_10 bold">Professional Account</div>
            <div style="padding-top:5px; padding-bottom:10px;">Perfect for those who need more control over their photos, slideshows and <span class="bold">videos</span>.  You'll be able to integrate your photos and slideshows directly into your web site, add your company logo, add widgets, and generate traffic reports.  All for just $9.95/month.</div>
            <div class="center"><a href="javascript:void(0);" onclick="effDif.toggle();" class="f_8 f_red bold">close</a></div>
          </div>
        </div>
      </div>

      <div class="formRow">
        <div class="formLabel">What are you interested in most?</div>
        <div class="formField" style="padding-top:5px;">
          <select name="u_interest" id="u_interest" class="formfield">
            <option value="N/A">Select one</option>
            <option value="slideshows">Creating slideshows</option>
            <option value="prints">Ordering prints</option>
            <option value="selling">Selling your photos</option>
            <option value="sharing">Sharing your photos</option>
            <option value="organizing">Organizing your photos</option>
            <option value="archiving">Archiving your photos</option>
            <option value="undecided">I don't know yet</option>
          </select><?php echo $required; ?>
        </div>
      </div>
      <br />
      <div class="formRow">
        <div class="formLabel">Mother's Maiden Name</div>
        <div class="formField"><input type="text" name="u_secret" value="<?php if(isset($_get_udf['u_secret'])){ echo $_get_udf['u_secret']; } ?>" class="formfield" style="width:135px" /><?php echo $required; ?>&nbsp;(For password retrieval)</div>
      </div>
    </div>

    <div class="formRow">
      <div class="formIndent">
        <div id="registrationMessage"></div>
        <div id="registrationButton" style="padding-left:30px;">
          <input type="image" src="images/buttons/create_account.gif" id="registrationSubmit" width="168" height="34" border="0" vspace="2" border="0" />
        </div>
      </div>
    </div>

    <?php
      if(isset($_POST['invite_key']) || isset($_GET['invite_key']))
      {
        $invite_key = isset($_POST['invite_key']) ? $_POST['invite_key'] : $_GET['invite_key'];
        echo '<input type="hidden" name="invite_key" value="' . $invite_key . '" />';
      }

      if(isset($earn_space_key))
      {
        echo '<input type="hidden" name="earn_space_key" value="' . $earn_space_key . '" />';
      }

      if(isset($email_validation_bypass))
      {
        echo '<input type="hidden" name="email_validation_bypass" value="1" />';
      }
    ?>
  </form>
</div>

<script>
  var effDif = new fx.Height('difference');
  effDif.hide();
</script>
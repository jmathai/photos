<?php
  $u = &CUser::getInstance();
  $ci = &CCitizenImage::getInstance();

  // send them to the upload images form if they already have an account
  if($ci->accountExists($_USER_ID))
  {
    echo '
        <div class="f_9 bold">
          <img src="images/icons/warning_16x16.png" class="png" width="16" height="16" hspace="4" border="0" align="absmiddle" />
          It looks like you already have an account set up with Citizen Image.  <a href="/?action=ci.upload_images">Click here</a> to continue to upload your photos.
        </div>
      ';
  }
  else
  {
    $userData = $u->find($_USER_ID);
    if(isset($_POST['login_error']))
    {
      // variables from the first page
      $username = $_POST['ci_username'];
      $password = $_POST['ci_password'];
      $nameFirst = $_POST['ci_name_first'];
      $nameLast = $_POST['ci_name_last'];
      $email = $_POST['ci_email'];
      $paymentType = $_POST['ci_payment_type'];
      $street1 = $_POST['ci_street_1'];
      $street2 = $_POST['ci_street_2'];
      $country = $_POST['ci_country'];
      $city = $_POST['ci_city'];
      $state = $_POST['ci_state'];
      $zip = $_POST['ci_zip'];
      $categoryType = $_POST['ci_category_type'];
      $acceptAgreement = 'true';

      $numberPhotos = $_POST['ci_number_photos'];
    }
    else
    {
      $username = $userData['U_USERNAME'];
      $nameFirst = $userData['U_NAMEFIRST'];
      $nameLast = $userData['U_NAMELAST'];
      $email = $userData['U_EMAIL'];
      $street1 = $userData['U_ADDRESS'];
      $country = $userData['U_COUNTRY'];
      $city = $userData['U_CITY'];
      $state = $userData['U_STATE'];
      $zip = $userData['U_ZIP'];
    }

    $paymentArr = array('cip_paypal' => 'Paypal', 'cip_check' => 'Check');
    $paymentTypes = '<select name="ci_payment_type" id="ci_payment_type" class="formfield">';
    foreach($paymentArr as $k => $v)
    {
      if($k == $paymentType)
      {
        $paymentTypes .= '<option value="' . $v . '" selected="true">' . $v . '</option>';
      }
      else
      {
        $paymentTypes .= '<option value="' . $v . '">' . $v . '</option>';
      }
    }
    $paymentTypes .= '</select>';

    $categoryArr = array('Amateur', 'Hobbyist', 'Prosumer', 'Professional');
    $categoryTypes = '<select name="ci_category_type" id="ci_category_type" class="formfield">';
    foreach($categoryArr as $v)
    {
      $categoryTypes .= '<option value="' . $v . '" ' . ($v == $categoryType ? 'selected="SELECTED"' : '') . '>' . $v . '</option>';
    }
    $categoryTypes .= '</select>';

    $fv =  new CFormValidator;

    $fv -> setForm('_ciRegistration');
    $fv -> addElement('ci_username', 'Username', '  - Username must be between 4 and 50 characters.', 'regexp/^[a-zA-Z0-9_]{4`,50}$/');
    $fv -> addElement('ci_password', 'Password', '  - Password must be between 8 and 12 characters.', 'regexp/^[a-zA-Z0-9_]{8`,12}$/');
    $fv -> addElement('ci_password_confirm', 'Password (again)', '  - Your password confirmation does not match your password.', 'length');
    $fv -> addElement('ci_password', 'Password (again)', '  - Your password confirmation does not match your password.', 'regexp/^\'+document.forms[\'_ciRegistration\'].ci_password_confirm.value+\'$/');
    $fv -> addElement('ci_name_first', 'First Name', '  - Please enter your first name.', 'length');
    $fv -> addElement('ci_name_last', 'Last Name', '  - Please enter your last name.', 'length');
    $fv -> addElement('ci_email', 'Email', '  - Please enter your email address.', 'email');
    $fv -> addElement('ci_payment_type', 'How You\'re Paid', '  - Please select how you would like to be paid.', 'selectboxnull');
    $fv -> addElement('ci_street_1', 'Street Address (line 1)', '  - Please enter your street address.', 'length');
    $fv -> addElement('ci_country', 'Country', '  - Please select your country.', 'selectboxnull');
    $fv -> addElement('ci_city', 'City', '  - Please enter your city.', 'length');
    $fv -> addElement('ci_state', 'State', '  - Please select your state.', 'selectboxnull');
    $fv -> addElement('ci_zip', 'Zip', '  - Please enter your zip code.', 'length');
    $fv -> addElement('ci_agreement', 'Agree To The Terms', '  - Please agree to the Photographer Member Agreement', 'checkboxmin1');
    $fv -> setMaxElementsToDisplay(10);
    $fv -> setDebugOutput(false);
    $fv -> setFunctionName('_val_registration');
    $fv -> validate();

    $required = '*';
?>

    <div style="margin-left:25px;">
      <div class="f_12 bold">Citizen Image Basic Photographer Registration</div>
      <div style="float:left; margin-left:5px; margin-top:10px;">
        <div class="bold" style="margin-top:5px; width:230px; text-align:right;">Username:</div>
        <div class="bold" style="margin-top:10px; width:230px; text-align:right;">Password:</div>
        <div class="bold" style="margin-top:10px; width:230px; text-align:right;">Password (again):</div>
        <div class="bold" style="margin-top:10px; width:230px; text-align:right;">First Name:</div>
        <div class="bold" style="margin-top:10px; width:230px; text-align:right;">Last Name:</div>
        <div class="bold" style="margin-top:10px; width:230px; text-align:right;">How You're Paid:</div>
        <div class="bold" style="margin-top:10px; width:230px; text-align:right;">Email Address:</div>
        <div class="bold" style="margin-top:10px; width:230px; text-align:right;" id="ci_street_1_title">Street Address (line 1):</div>
        <div class="bold" style="margin-top:10px; width:230px; text-align:right;" id="ci_street_2_title">Street Address (line 2):</div>
        <div class="bold" style="margin-top:10px; width:230px; text-align:right;">Country:</div>
        <div class="bold" style="margin-top:10px; width:230px; text-align:right;">City:</div>
        <div class="bold" style="margin-top:10px; width:230px; text-align:right;">State:</div>
        <div class="bold" style="margin-top:10px; width:230px; text-align:right;">Zip or Postal Code:</div>
        <div class="bold" style="margin-top:10px; width:230px; text-align:right;">Describe yourself as a photographer:</div>
      </div>

      <?php
        if(isset($_POST['login_error']))
        {
          echo '<form name="_ciRegistration" id="_ciRegistration" method="POST" action="/?action=ci.save.act" onsubmit="return _val_registration();">';
        }
        else
        {
          echo '<form name="_ciRegistration" id="_ciRegistration" method="POST" action="/?action=ci.agreements" onsubmit="return _val_registration();">';
        }
      ?>

      <div style="float:left; margin-left:15px; margin-top:10px;">

        <?php
          if(isset($_POST['login_error']))
          {
            echo '<div style="float:left; margin-top:5px;"><input type="text" name="ci_username" size="20" maxlength="50" class="formfield" value="' . $username . '" /></div>';
            echo '<div style="float:left; padding-left:10px; padding-top:5px;" class="f_red">Username is already taken</div>';
            echo '<br clear="all" />';
          }
          else
          {
            echo '<div style="margin-top:5px;"><input type="text" name="ci_username" size="20" maxlength="50" class="formfield" value="' . $username . '" />' . $required . '</div>';
          }
        ?>
        <div style="margin-top:6px;"><input type="password" name="ci_password" size="20" maxlength="50" class="formfield" /><?php echo $required; ?></div>
        <div style="margin-top:6px;"><input type="password" name="ci_password_confirm" size="20" maxlength="50" class="formfield" /><?php echo $required; ?></div>
        <div style="margin-top:6px;"><input type="text" name="ci_name_first" size="20" maxlength="50" class="formfield" value="<?php echo $nameFirst; ?>" /><?php echo $required; ?></div>
        <div style="margin-top:6px;"><input type="text" name="ci_name_last" size="20" maxlength="50" class="formfield" value="<?php echo $nameLast; ?>" /><?php echo $required; ?></div>
        <div style="margin-top:6px;"><?php echo $paymentTypes; ?><?php echo $required; ?> (for any amount over $200 a check will be sent to the address below)</div>
        <div style="margin-top:6px;"><input type="text" name="ci_email" size="20" maxlength="50" class="formfield" value="<?php echo $email; ?>" /><?php echo $required; ?></div>
        <div style="margin-top:6px;" id="ci_street_1_input"><input type="text" name="ci_street_1" id="ci_street_1" size="20" maxlength="50" class="formfield" value="<?php echo $street1; ?>" /><?php echo $required; ?></div>
        <div style="margin-top:6px;" id="ci_street_2_input"><input type="text" name="ci_street_2" id="ci_street_2" size="20" maxlength="50" class="formfield" value="<?php echo $street2; ?>" /></div>
        <div style="margin-top:6px;"><select name="ci_country" class="formfield" style="width:150px"><?php echo optionCountries($country); ?></select><?php echo $required; ?></div>
        <div style="margin-top:6px;"><input type="text" name="ci_city" size="20" maxlength="50" class="formfield" value="<?php echo $city; ?>" /><?php echo $required; ?></div>
        <div style="margin-top:6px;"><select name="ci_state" class="formfield" style="width:150px"><?php echo optionStates($state); ?></select><?php echo $required; ?></div>
        <div style="margin-top:6px;"><input type="text" name="ci_zip" size="20" maxlength="50" class="formfield" value="<?php echo $zip; ?>" /><?php echo $required; ?></div>
        <div style="margin-top:6px;"><?php echo $categoryTypes . $required; ?></div>
      </div>
      <br clear="all" />

      <div class="center">
        <div style="margin:25px 0px 5px 0px;">Download or print a copy of this agreement: <a href="http://www.citizenimage.com/legal/CitizenImagePhotographerMemberAgreement.pdf" target="_blank">Citizen Image Photographer Member Agreement PDF</a></div>
        <div>
          <iframe src="http://www.citizenimage.com/legal/photograhper_member_agreement.html" width="550" height="200"></iframe>
        </div>
        <div>
          <div style="padding-top:2px; line-height:20px;">
            <input type="checkbox" id="ci_agreement" value="agree" class="formfield" />&nbsp;I agree to the above terms
        </div>
        <br clear="all" />


        <?php
          if(isset($_POST['login_error']))
          {
            echo '<input type="hidden" name="ci_number_photos" value="' .  $numberPhotos . '" />';

            for($i = 0; $i < $numberPhotos; $i++)
            {
              $id = $_POST['ci_image_' . $i];
              $image = $_POST['ci_image_' . $id];
              $title = $_POST['ci_image_' . $id . '_title'];
              $description = $_POST['ci_image_' . $id . '_description'];
              $category = $_POST['ci_image_' . $id . '_license'];
              $subCategory = $_POST['ci_image_' . $id . '_subcategory'];
              $month = $_POST['ci_image_' . $id . '_month'];
              $year = $_POST['ci_image_' . $id . '_year'];
              $day = $_POST['ci_image_' . $id . '_day'];
              $hour = $_POST['ci_image_' . $id . '_hour'];
              $minute = $_POST['ci_image_' . $id . '_minute'];
              $ampm = $_POST['ci_image_' . $id . '_ampm'];
              $timezone = $_POST['ci_image_' . $id . '_timezone'];
              $country = $_POST['ci_image_' . $id . '_country'];
              $state = $_POST['ci_image_' . $id . '_state'];
              $city = $_POST['ci_image_' . $id . '_city'];
              $keywords = $_POST['ci_image_' . $id . '_keywords'];

              echo '<input type="hidden" name="ci_image_' . $i . '" value="' . $id . '" />';
              echo '<input type="hidden" name="ci_image_' . $id . '" value="' . $image . '" />';
              echo '<input type="hidden" name="ci_image_' . $id . '_title" value="' . $title . '" />';
              echo '<input type="hidden" name="ci_image_' . $id . '_description" value="' . $description . '" />';
              echo '<input type="hidden" name="ci_image_' . $id . '_license" value="' . $category . '" />';
              echo '<input type="hidden" name="ci_image_' . $id . '_subcategory" value="' . $subcategory . '" />';
              echo '<input type="hidden" name="ci_image_' . $id . '_month" value="' . $month . '" />';
              echo '<input type="hidden" name="ci_image_' . $id . '_year" value="' . $year . '" />';
              echo '<input type="hidden" name="ci_image_' . $id . '_day" value="' . $day . '" />';
              echo '<input type="hidden" name="ci_image_' . $id . '_hour" value="' . $hour . '" />';
              echo '<input type="hidden" name="ci_image_' . $id . '_minute" value="' . $minute . '" />';
              echo '<input type="hidden" name="ci_image_' . $id . '_ampm" value="' . $ampm . '" />';
              echo '<input type="hidden" name="ci_image_' . $id . '_timezone" value="' . $timezone . '" />';
              echo '<input type="hidden" name="ci_image_' . $id . '_country" value="' . $country . '" />';
              echo '<input type="hidden" name="ci_image_' . $id . '_state" value="' . $state . '" />';
              echo '<input type="hidden" name="ci_image_' . $id . '_city" value="' . $city . '" />';
              echo '<input type="hidden" name="ci_image_' . $id . '_keywords" value="' . $keywords . '" />';
            }
          }
        ?>

        <div>
          <div id="registrationMessage"></div>
          <div id="registrationButton">
            <a href="javascript:void(0);" onclick="if(_val_registration()){ registrationSubmitFcn(); }" class="plain bold f_11">
              Continue<img src="images/icons/right_24x24.png" class="png" border="0" hspace="4" width="24" height="24" align="absmiddle" />
            </a>
          </div>
        </div>
      </div>

      </form>
    </div>
    <br/><br/><br/>

    <script>
      function registrationSubmitFcn()
      {
        $('registrationButton').style.display = 'none';
        $('registrationMessage').innerHTML = '<img src="images/ajax_loader_snake.gif" width="16" height="16" hspace="3" align="absmiddle" />Please wait...';
        $('_ciRegistration').submit();
      }
    </script>
<?php
  }
?>
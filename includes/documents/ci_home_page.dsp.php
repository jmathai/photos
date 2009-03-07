<?php
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
  $acceptAgreement = 'true';

  $fv =  new CFormValidator;

  $fv -> setForm('_ciRegistration');
  $fv -> addElement('ci_category_type', 'How would you describe yourself as a photographer', '  - Please choose a category.', 'selectboxnull');
  $fv -> addElement('ci_public_name', 'Public Name', '  - Please must enter a public name.', 'length');
  $fv -> addElement('ci_camera_make', 'Digital Camera Make', '  - Please select a camera make.', 'selectboxnull');
  $fv -> addElement('ci_camera_model', 'Digital Camera Model', '  - Please enter a camera model.', 'length');
  $fv -> addElement('ci_camera_resolution', 'Digital Camera Resolution', '  - Please enter your camera resolution.', 'selectboxnull');
  $fv -> setMaxElementsToDisplay(10);
  $fv -> setDebugOutput(false);
  $fv -> setFunctionName('_val_registration');
  $fv -> validate();

  $categoryArr = array('Amateur', 'Hobbyist', 'Prosumer', 'Professional');
  $categoryTypes = '<select name="ci_category_type" id="ci_category_type" class="formfield">';
  foreach($categoryArr as $v)
  {
    $categoryTypes .= '<option value="' . $v . '">' . $v . '</option>';
  }
  $categoryTypes .= '</select>';

  $cameraMakeTypes = '<select name="ci_camera_make" id="ci_camera_make" class="formfield">
                        <option value="null"></option>
                        <option value="Agfa" >Agfa</option>
                        <option value="Argus" >Argus</option>
                        <option value="BenQ" >BenQ</option>
                        <option value="Bushnell" >Bushnell</option>
                        <option value="Canon" >Canon</option>
                        <option value="Casio" >Casio</option>
                        <option value="Concord" >Concord</option>
                        <option value="Contax" >Contax</option>
                        <option value="Cool-iCam" >Cool-iCam</option>
                        <option value="D-Link" >D-Link</option>
                        <option value="DXG" >DXG</option>
                        <option value="Epson" >Epson</option>
                        <option value="Fujifilm" >Fujifilm</option>
                        <option value="Gateway" >Gateway</option>
                        <option value="Hasselblad" >Hasselblad</option>
                        <option value="Hewlett Packard" >Hewlett Packard</option>
                        <option value="Hitachi" >Hitachi</option>
                        <option value="Jenoptik" >Jenoptik</option>
                        <option value="JVC" >JVC</option>
                        <option value="Kodak" >Kodak</option>
                        <option value="Konica" >Konica</option>
                        <option value="Konica Minolta" >Konica Minolta</option>
                        <option value="Kyocera" >Kyocera</option>
                        <option value="Largan" >Largan</option>
                        <option value="Leica" >Leica</option>
                        <option value="Maxell" >Maxell</option>
                        <option value="Microtek" >Microtek</option>
                        <option value="Minolta" >Minolta</option>
                        <option value="Minox" >Minox</option>
                        <option value="Mustek" >Mustek</option>
                        <option value="Nikon" >Nikon</option>
                        <option value="Olympus" >Olympus</option>
                        <option value="Oregon" >Oregon</option>
                        <option value="Panasonic" >Panasonic</option>
                        <option value="Pentax" >Pentax</option>
                        <option value="Polaroid" >Polaroid</option>
                        <option value="Premier" >Premier</option>
                        <option value="Ricoh" >Ricoh</option>
                        <option value="Ritz" >Ritz</option>
                        <option value="Rollei" >Rollei</option>
                        <option value="Samsung" >Samsung</option>
                        <option value="Sanyo" >Sanyo</option>
                        <option value="Sealife" >Sealife</option>
                        <option value="Sigma" >Sigma</option>
                        <option value="SiPix" >SiPix</option>
                        <option value="SMaL Camera Technologies" >SMaL Camera Technologies</option>
                        <option value="Sony" >Sony</option>
                        <option value="Toshiba" >Toshiba</option>
                        <option value="UMAX" >UMAX</option>
                        <option value="Vivitar" >Vivitar</option>
                        <option value="Yashica" >Yashica</option>
                        <option value="Other" >Other</option>
                      </select>';

  $cameraResolutionTypes = '<select name="ci_camera_resolution" id="ci_camera_resolution" class="formfield">
                              <option value="null"></option>
            									<option value="1" >1-3</option>
            									<option value="4" >4-7</option>
            									<option value="8" >8-10</option>
            									<option value="11" >11-20</option>
            									<option value="21" >21-30</option>
            									<option value="31" >31-40</option>
            									<option value="41" >41-50</option>
            									<option value="51" >51+</option>
            								</select>';

  $specialitiesTypes = '<select name="ci_speciality" id="ci_speciality" onchange="showSpecialityOther();" class="formfield">
                          <option value="Editorial - Entertainment / Celebrity">Editorial - Entertainment / Celebrity</option>
      										<option value="Editorial - News">Editorial - News</option>
      										<option value="Editorial - Sports">Editorial - Sports</option>
      										<option value="Architecture">Architecture</option>
      										<option value="Corporate">Corporate</option>
      										<option value="Events">Events</option>
      										<option value="Food">Food</option>
      										<option value="Landscapes">Landscapes</option>
      										<option value="Nature">Nature</option>
      										<option value="Portraits">Portraits</option>
      										<option value="Specialty subjects (cars, dogs etc.)">Specialty subjects (cars, dogs etc.)</option>
      										<option value="Travel">Travel</option>
      										<option value="Underwater">Underwater</option>
      										<option value="Other">Other</option>
      									</select>';

	$required = '*';
?>

<div style="margin-left:25px; padding-top:5px;">

  <form name="_ciRegistration" id="_ciRegistration" method="POST" action="/?action=ci.agreements" onsubmit="return _val_registration();">

  <div class="f_12 bold" style="margin-top:5px;">Photographer Details</div>
  <div style="float:left; margin-left:5px; margin-top:10px;">
		<div class="bold" style="margin-top:5px; width:300px; text-align:right;">Describe yourself as a photographer:</div>
    <div class="bold" style="margin-top:10px; width:300px; text-align:right;">Public Name:</div>
    <div class="bold" style="margin-top:10px; width:300px; text-align:right;">Web Site:</div>
    <div class="bold" style="margin-top:10px; width:300px; text-align:right;">Biography:</div>
    <div class="bold" style="margin-top:120px; width:300px; text-align:right;">Digital Camera Make:</div>
    <div class="bold" style="margin-top:10px; width:300px; text-align:right;">Digital Camera Model:</div>
    <div class="bold" style="margin-top:10px; width:300px; text-align:right;">Digital Camera Resolution (in megapixels):</div>
    <div class="bold" style="margin-top:10px; width:300px; text-align:right;">Digital Camera Specialty lenses / equipment:</div>
    <div class="bold" style="margin-top:65px; width:300px; text-align:right;" id="ci_street_1_title">Photography Training - Schools:</div>
    <div class="bold" style="margin-top:65px; width:300px; text-align:right;" id="ci_street_2_title">Photography Training - Courses:</div>
    <div class="bold" style="margin-top:46px; width:300px; text-align:right;">Specialities:</div>
  </div>

  <div style="float:left; margin-left:15px; margin-top:10px;">
		<div style="margin-top:5px;"><?php echo $categoryTypes . $required; ?></div>
    <div style="margin-top:5px;"><input type="text" name="ci_public_name" size="20" maxlength="50" class="formfield" value="<?php echo $nameFirst . ' ' . $nameLast; ?>" /><?php echo $required; ?></div>
    <div style="margin-top:6px;"><input type="text" name="ci_web_site" size="25" maxlength="300" class="formfield" style="background:url(/images/http.gif) no-repeat; padding-left:40px;" /></div>
    <div style="margin-top:6px;"><textarea name="ci_biography" rows="8" cols="50" class="formfield"></textarea></div>
    <div style="margin-top:6px;"><?php echo $cameraMakeTypes . $required; ?></div>
    <div style="margin-top:6px;"><input type="text" name="ci_camera_model" size="20" maxlength="100" class="formfield" /><?php echo $required; ?></div>
    <div style="margin-top:6px;"><?php echo $cameraResolutionTypes . $required; ?></div>
    <div style="margin-top:6px;"><textarea name="ci_camera_equipment" rows="4" cols="30" class="formfield"></textarea></div>
    <div style="margin-top:6px;"><textarea name="ci_training_schools" rows="4" cols="30" class="formfield"></textarea></div>
    <div style="margin-top:6px;"><textarea name="ci_training_courses" rows="4" cols="30" class="formfield"></textarea></div>
    <div style="margin-top:6px; margin-right:5px;"><?php echo $specialitiesTypes; ?></div>
    <div style="margin-top:3px;">
      <a href="javascript:void(0);" onclick="addSpeciality($('ci_speciality').options[$('ci_speciality').selectedIndex].text, $('ci_speciality').options[$('ci_speciality').selectedIndex].value, $('ci_speciality_other').value);" class="plain">
        <img src="images/icons/add_alt_2_16x16.png" class="png" border="0" width="16" height="16" align="absmiddle" />&nbsp;Add to list
      </a>
    </div>
    <div style="margin-top:3px;"><input type="text" name="ci_speciality_other" id="ci_speciality_other" class="formfield" style="display:none;" disabled /></div>
    <div style="margin-top:5px; margin-right:5px;">
      <select name="ci_speciality_types" id="ci_speciality_types" class="formfield" style="width:300px; height:100px;" multiple="true">
        <option value="">Specialties you have selected</option>
      </select>
    </div>
    <div style="margin-top:3px;">
      <a href="javascript:void(0);" onclick="removeSpeciality();" class="plain">
        <img src="images/icons/remove_alt_2_16x16.png" class="png" border="0" width="16" height="16" align="absmiddle" />&nbsp;Remove from list
      </a>
    </div>
  </div>
  <br clear="all" />

  <input type="hidden" name="ci_username" value="<?php echo $username; ?>" />
  <input type="hidden" name="ci_password" value="<?php echo $password; ?>" />
  <input type="hidden" name="ci_name_first" value="<?php echo $nameFirst; ?>" />
  <input type="hidden" name="ci_name_last" value="<?php echo $nameLast; ?>" />
  <input type="hidden" name="ci_email" value="<?php echo $email; ?>" />
  <input type="hidden" name="ci_payment_type" value="<?php echo $paymentType; ?>" />
  <input type="hidden" name="ci_street_1" value="<?php echo $street1; ?>" />
  <input type="hidden" name="ci_street_2" value="<?php echo $street2; ?>" />
  <input type="hidden" name="ci_country" value="<?php echo $country; ?>" />
  <input type="hidden" name="ci_city" value="<?php echo $city; ?>" />
  <input type="hidden" name="ci_state" value="<?php echo $state; ?>" />
  <input type="hidden" name="ci_zip" value="<?php echo $zip; ?>" />
  <input type="hidden" name="ci_agreement" value="<?php echo $acceptAgreement; ?>" />
  <input type="hidden" name="ci_speciality_list" id="ci_speciality_list" value="" />

  <div style="margin-top:10px; margin-left:320px;">
    <div id="registrationMessage"></div>
    <div id="registrationButton">
      <a href="javascript:void(0);" onclick="if(_val_registration()){ registrationSubmitFcn(); }" class="plain bold f_11">
        Continue<img src="images/icons/right_24x24.png" class="png" border="0" hspace="4" width="24" height="24" align="absmiddle" />
      </a>
    </div>
  </div>
  
  </form>
</div>


<script>
  ci_speciality_list = new Array();

  function addSpeciality(txt, val, otherVal)
  {
    testVal = val;
    if(val == 'Other')
    {
      testVal = otherVal;
    }

    cnt = $('ci_speciality_types').options.length;
    found = false;
    for(i = 0; i < cnt; i++)
    {
      if($('ci_speciality_types').options[i].value == txt)
      {
        found = true;
        break;
      }
    }

    if(!found)
    {
      $('ci_speciality_types').options[cnt] = new Option(testVal, txt);
      ci_speciality_list.push(testVal);
    }
  }

  function removeSpeciality()
  {
    cnt = $('ci_speciality_types').options.length;
    for(i = 1; i < cnt; i++) // start at 1 to skip title option
    {
      if($('ci_speciality_types').options[i].selected == true)
      {
        $('ci_speciality_types').remove(i);
        ci_speciality_list.splice(i,1);
      }
    }
  }

  function showSpecialityOther()
  {
    if($('ci_speciality').options[$('ci_speciality').selectedIndex].value == 'Other')
    {
      $('ci_speciality_other').disabled = false;
			$('ci_speciality_other').style.display = 'block';
    }
    else
    {
      $('ci_speciality_other').disabled = true;
			$('ci_speciality_other').style.display = 'none';
    }
  }

  function registrationSubmitFcn()
  {
    cnt = ci_speciality_list.length;
    for(i = 0; i < cnt; i++)
    {
      if($('ci_speciality_list').value != '')
      {
        $('ci_speciality_list').value = $('ci_speciality_list').value + ',' + ci_speciality_list[i];
      }
      else
      {
        $('ci_speciality_list').value = ci_speciality_list[i];
      }
    }

    $('registrationButton').style.display = 'none';
    $('registrationMessage').innerHTML = '<img src="images/ajax_loader_snake.gif" width="16" height="16" hspace="3" align="absmiddle" />Please wait...';
    $('_ciRegistration').submit();
  }
</script>
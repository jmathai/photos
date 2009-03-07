<?php
  $t = &CToolbox::getInstance();
  $fb = &CFotobox::getInstance();

	// if the toolbox is empty then send them back to their photo page
	$rs = $t->get($_USER_ID);
	if(count($rs) == 0)
	{
		echo '<script>
						document.location = "/?action=fotobox.fotobox_myfotos";
					</script>';
		die();
	}

  if(!empty($_POST) && !isset($_POST['image_error']))
  {
    // variables from the first and second pages
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

    $categoryType = $_POST['ci_category_type'];
    $publicName = $_POST['ci_public_name'];
    $webSite = isset($_POST['ci_web_site']) ? $_POST['ci_web_site'] : '';
    $biography = isset($_POST['ci_biography']) ? $_POST['ci_biography'] : '';
    $cameraMake = $_POST['ci_camera_make'];
    $cameraModel = $_POST['ci_camera_model'];
    $cameraResolution = $_POST['ci_camera_resolution'];
    $cameraEquipment = isset($_POST['ci_camera_equipment']) ? $_POST['ci_camera_equipment'] : '';
    $trainingSchools = isset($_POST['ci_training_schools']) ? $_POST['ci_training_schools'] : '';
    $trainingCourses = isset($_POST['ci_training_courses']) ? $_POST['ci_training_courses'] : '';
    $specialities = isset($_POST['ci_speciality_list']) ? $_POST['ci_speciality_list'] : '';
  }

  $fv =  new CFormValidator;

  $fv -> setForm('_ciRegistration');

  $photosRs = $t->get($_USER_ID);
	$required = '*';
?>

<form name="_ciRegistration" id="_ciRegistration" method="POST" action="/?action=ci.save.act" onsubmit="return _val_registration();">
  <?php
    echo '<div style="padding-left:15px; padding-top:15px; padding-bottom:25px;" class="f_12 bold">Sell these images</div>';

    foreach($photosRs as $k => $v)
    {
      echo '<input type="hidden" name="ci_image_' . $k . '" value="' . $v['P_ID'] . '" />';
      echo '<input type="hidden" name="ci_image_' . $v['P_ID'] . '" value="' . PATH_FOTOROOT . $v['P_THUMB_PATH'] . '" />';

      $photoRs = $fb->fotoData($v['P_ID']);

      $fv -> addElement('ci_image_' . $v['P_ID'] . '_title', 'Title', '  - Please enter a title.', 'length');
      $fv -> addElement('ci_image_' . $v['P_ID'] . '_description', 'Description', '  - Please enter a description.', 'length');
      $fv -> addElement('ci_image_' . $v['P_ID'] . '_keywords', 'Keywords', '  - Please enter at least 3 keywords.', 'length');


      $licenseTypes = '<select name="ci_image_' . $v['P_ID'] . '_license" id="ci_image_' . $v['P_ID'] . '_license" onchange="toggleLicense_' . $v['P_ID'] . '();" class="formfield">
      										<option value="creative" selected="true">Creative (Royalty Free)</option>
      									  <option value="editorial">Editorial (Rights Managed)</option>
      									</select>';

      $subcategoryTypes = '<select name="ci_image_' . $v['P_ID'] . '_subcategory" id="ci_image_' . $v['P_ID'] . '_subcategory" class="formfield">
                              <option value="Daily Life" selected="true">Daily Life</option>
          										<option value="Entertainment" >Entertainment</option>
          										<option value="News" >News</option>
          										<option value="Sports" >Sports</option>
    										      <option value="Other" >Travel/Other</option>
    										   </select>';

      $months = '<select name="ci_image_' . $v['P_ID'] . '_month" id="ci_image_' . $v['P_ID'] . '_month" class="formfield">
                    <option value="0" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 2, 2))-1) == 0 ? 'selected="true"' : '') . '>Jan</option>
    								<option value="1" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 2, 2))-1) == 1 ? 'selected="true"' : '') . '>Feb</option>
    								<option value="2" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 2, 2))-1) == 2 ? 'selected="true"' : '') . '>Mar</option>
    								<option value="3" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 2, 2))-1) == 3 ? 'selected="true"' : '') . '>Apr</option>
    								<option value="4" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 2, 2))-1) == 4 ? 'selected="true"' : '') . '>May</option>
    								<option value="5" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 2, 2))-1) == 5 ? 'selected="true"' : '') . '>Jun</option>
    								<option value="6" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 2, 2))-1) == 6 ? 'selected="true"' : '') . '>Jul</option>
    								<option value="7" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 2, 2))-1) == 7 ? 'selected="true"' : '') . '>Aug</option>
    								<option value="8" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 2, 2))-1) == 8 ? 'selected="true"' : '') . '>Sep</option>
    								<option value="9" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 2, 2))-1) == 9 ? 'selected="true"' : '') . '>Oct</option>
    								<option value="10" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 2, 2))-1) == 10 ? 'selected="true"' : '') . '>Nov</option>
    								<option value="11" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 2, 2))-1) == 11 ? 'selected="true"' : '') . '>Dec</option>
    					   </select>';

      $years = '<select name="ci_image_' . $v['P_ID'] . '_year" id="ci_image_' . $v['P_ID'] . '_year" class="formfield">
                    <option value="2006" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 6 ? 'selected="true"' : '') . '>2006</option>
    								<option value="2005" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 5 ? 'selected="true"' : '') . '>2005</option>
    								<option value="2004" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 4 ? 'selected="true"' : '') . '>2004</option>
    								<option value="2003" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 3 ? 'selected="true"' : '') . '>2003</option>
    								<option value="2002" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 2 ? 'selected="true"' : '') . '>2002</option>
    								<option value="2001" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 1 ? 'selected="true"' : '') . '>2001</option>
    								<option value="2000" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 0 ? 'selected="true"' : '') . '>2000</option>
    								<option value="1999" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 99 ? 'selected="true"' : '') . '>1999</option>
    								<option value="1998" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 98 ? 'selected="true"' : '') . '>1998</option>
    								<option value="1997" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 97 ? 'selected="true"' : '') . '>1997</option>
    								<option value="1996" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 96 ? 'selected="true"' : '') . '>1996</option>
    								<option value="1995" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 95 ? 'selected="true"' : '') . '>1995</option>
    								<option value="1994" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 94 ? 'selected="true"' : '') . '>1994</option>
    								<option value="1993" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 93 ? 'selected="true"' : '') . '>1993</option>
    								<option value="1992" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 92 ? 'selected="true"' : '') . '>1992</option>
    								<option value="1991" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 91 ? 'selected="true"' : '') . '>1991</option>
    								<option value="1990" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 90 ? 'selected="true"' : '') . '>1990</option>
    								<option value="1989" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 89 ? 'selected="true"' : '') . '>1989</option>
    								<option value="1988" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 88 ? 'selected="true"' : '') . '>1988</option>
    								<option value="1987" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 87 ? 'selected="true"' : '') . '>1987</option>
    								<option value="1986" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 86 ? 'selected="true"' : '') . '>1986</option>
    								<option value="1985" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 85 ? 'selected="true"' : '') . '>1985</option>
    								<option value="1984" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 84 ? 'selected="true"' : '') . '>1984</option>
    								<option value="1983" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 83 ? 'selected="true"' : '') . '>1983</option>
    								<option value="1982" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 82 ? 'selected="true"' : '') . '>1982</option>
    								<option value="1981" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 81 ? 'selected="true"' : '') . '>1981</option>
    								<option value="1980" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 80 ? 'selected="true"' : '') . '>1980</option>
    								<option value="1979" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 79 ? 'selected="true"' : '') . '>1979</option>
    								<option value="1978" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 78 ? 'selected="true"' : '') . '>1978</option>
    								<option value="1977" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 77 ? 'selected="true"' : '') . '>1977</option>
    								<option value="1976" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 76 ? 'selected="true"' : '') . '>1976</option>
    								<option value="1975" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 75 ? 'selected="true"' : '') . '>1975</option>
    								<option value="1974" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 74 ? 'selected="true"' : '') . '>1974</option>
    								<option value="1973" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 73 ? 'selected="true"' : '') . '>1973</option>
    								<option value="1972" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 72 ? 'selected="true"' : '') . '>1972</option>
    								<option value="1971" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 71 ? 'selected="true"' : '') . '>1971</option>
    								<option value="1970" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 70 ? 'selected="true"' : '') . '>1970</option>
    								<option value="1969" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 69 ? 'selected="true"' : '') . '>1969</option>
    								<option value="1968" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 68 ? 'selected="true"' : '') . '>1968</option>
    								<option value="1967" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 0, 2))) == 67 ? 'selected="true"' : '') . '>1967</option>
    					   </select>';

      $days = '<select name="ci_image_' . $v['P_ID'] . '_day" id="ci_image_' . $v['P_ID'] . '_day" class="formfield">
    								<option value="1" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 1 ? 'selected="true"' : '') . '>1</option>
    								<option value="2" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 2 ? 'selected="true"' : '') . '>2</option>
    								<option value="3" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 3 ? 'selected="true"' : '') . '>3</option>
    								<option value="4" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 4 ? 'selected="true"' : '') . '>4</option>
    								<option value="5" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 5 ? 'selected="true"' : '') . '>5</option>
    								<option value="6" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 6 ? 'selected="true"' : '') . '>6</option>
    								<option value="7" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 7 ? 'selected="true"' : '') . '>7</option>
    								<option value="8" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 8 ? 'selected="true"' : '') . '>8</option>
    								<option value="9" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 9 ? 'selected="true"' : '') . '>9</option>
    								<option value="10" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 10 ? 'selected="true"' : '') . '>10</option>
    								<option value="11" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 11 ? 'selected="true"' : '') . '>11</option>
    								<option value="12" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 12 ? 'selected="true"' : '') . '>12</option>
    								<option value="13" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 13 ? 'selected="true"' : '') . '>13</option>
    								<option value="14" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 14 ? 'selected="true"' : '') . '>14</option>
    								<option value="15" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 15 ? 'selected="true"' : '') . '>15</option>
    								<option value="16" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 16 ? 'selected="true"' : '') . '>16</option>
    								<option value="17" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 17 ? 'selected="true"' : '') . '>17</option>
    								<option value="18" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 18 ? 'selected="true"' : '') . '>18</option>
    								<option value="19" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 19 ? 'selected="true"' : '') . '>19</option>
    								<option value="20" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 20 ? 'selected="true"' : '') . '>20</option>
    								<option value="21" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 21 ? 'selected="true"' : '') . '>21</option>
    								<option value="22" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 22 ? 'selected="true"' : '') . '>22</option>
    								<option value="23" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 23 ? 'selected="true"' : '') . '>23</option>
    								<option value="24" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 24 ? 'selected="true"' : '') . '>24</option>
    								<option value="25" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 25 ? 'selected="true"' : '') . '>25</option>
    								<option value="26" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 26 ? 'selected="true"' : '') . '>26</option>
    								<option value="27" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 27 ? 'selected="true"' : '') . '>27</option>
    								<option value="28" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 28 ? 'selected="true"' : '') . '>28</option>
    								<option value="29" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 29 ? 'selected="true"' : '') . '>29</option>
    								<option value="30" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 30 ? 'selected="true"' : '') . '>30</option>
    								<option value="31" ' . ((intval(substr($photoRs['P_TAKEN_KEY'], 4, 2))-1) == 31 ? 'selected="true"' : '') . '>31</option>
    					   </select>';

      $datetime = getDate($photoRs['P_TAKEN']);

      $hours = '<select name="ci_image_' . $v['P_ID'] . '_hour" id="ci_image_' . $v['P_ID'] . '_hour" class="formfield">
    								<option value="1" ' . (($datetime['hours'] == 1 || $datetime['hours'] == 13) ? 'selected="true"' : '') . '>1</option>
  									<option value="2" ' . (($datetime['hours'] == 2 || $datetime['hours'] == 14) ? 'selected="true"' : '') . '>2</option>
  									<option value="3" ' . (($datetime['hours'] == 3 || $datetime['hours'] == 15) ? 'selected="true"' : '') . '>3</option>
  									<option value="4" ' . (($datetime['hours'] == 4 || $datetime['hours'] == 16) ? 'selected="true"' : '') . '>4</option>
  									<option value="5" ' . (($datetime['hours'] == 5 || $datetime['hours'] == 17) ? 'selected="true"' : '') . '>5</option>
  									<option value="6" ' . (($datetime['hours'] == 6 || $datetime['hours'] == 18) ? 'selected="true"' : '') . '>6</option>
  									<option value="7" ' . (($datetime['hours'] == 7 || $datetime['hours'] == 19) ? 'selected="true"' : '') . '>7</option>
  									<option value="8" ' . (($datetime['hours'] == 8 || $datetime['hours'] == 20) ? 'selected="true"' : '') . '>8</option>
  									<option value="9" ' . (($datetime['hours'] == 9 || $datetime['hours'] == 21) ? 'selected="true"' : '') . '>9</option>
  									<option value="10" ' . (($datetime['hours'] == 10 || $datetime['hours'] == 22) ? 'selected="true"' : '') . '>10</option>
  									<option value="11" ' . (($datetime['hours'] == 11 || $datetime['hours'] == 23) ? 'selected="true"' : '') . '>11</option>
  									<option value="12" ' . (($datetime['hours'] == 12 || $datetime['hours'] == 0) ? 'selected="true"' : '') . '>12</option>
    					   </select>';

      $minutes = '<select name="ci_image_' . $v['P_ID'] . '_minute" id="ci_image_' . $v['P_ID'] . '_minute" class="formfield">
    								<option value="0" ' . (($datetime['minutes'] >= 0 && $datetime['minutes'] < 5) ? 'selected="true"' : '') . '>00</option>
  									<option value="5" ' . (($datetime['minutes'] >= 5 && $datetime['minutes'] < 10) ? 'selected="true"' : '') . '>05</option>
  									<option value="10" ' . (($datetime['minutes'] >= 10 && $datetime['minutes'] < 15) ? 'selected="true"' : '') . '>10</option>
  									<option value="15" ' . (($datetime['minutes'] >= 15 && $datetime['minutes'] < 20) ? 'selected="true"' : '') . '>15</option>
  									<option value="20" ' . (($datetime['minutes'] >= 20 && $datetime['minutes'] < 25) ? 'selected="true"' : '') . '>20</option>
  									<option value="25" ' . (($datetime['minutes'] >= 25 && $datetime['minutes'] < 30) ? 'selected="true"' : '') . '>25</option>
  									<option value="30" ' . (($datetime['minutes'] >= 30 && $datetime['minutes'] < 35) ? 'selected="true"' : '') . '>30</option>
  									<option value="35" ' . (($datetime['minutes'] >= 35 && $datetime['minutes'] < 40) ? 'selected="true"' : '') . '>35</option>
  									<option value="40" ' . (($datetime['minutes'] >= 40 && $datetime['minutes'] < 45) ? 'selected="true"' : '') . '>40</option>
  									<option value="45" ' . (($datetime['minutes'] >= 45 && $datetime['minutes'] < 50) ? 'selected="true"' : '') . '>45</option>
  									<option value="50" ' . (($datetime['minutes'] >= 50 && $datetime['minutes'] < 55) ? 'selected="true"' : '') . '>50</option>
  									<option value="55" ' . (($datetime['minutes'] >= 55 && $datetime['minutes'] < 60) ? 'selected="true"' : '') . '>55</option>
    					   </select>';

      $ampm = '<select name="ci_image_' . $v['P_ID'] . '_ampm" id="ci_image_' . $v['P_ID'] . '_ampm" class="formfield">
    								<option value="0" ' . (($datetime['hours'] >=0 && $datetime['hours'] < 13) ? 'selected="true"' : '') . '>AM</option>
  									<option value="1" ' . (($datetime['hours'] >= 13 && $datetime['hours'] < 24) ? 'selected="true"' : '') . '>PM</option>
    					   </select>';

      $timezones = '<select name="ci_image_' . $v['P_ID'] . '_timezone" id="ci_image_' . $v['P_ID'] . '_timezone" class="formfield">
  										<option value="Brazil/Acre">Acre Time</option>
  										<option value="Asia/Kabul">Afghanistan Time</option>
  										<option value="US/Alaska">Alaska Standard Time</option>
  										<option value="Asia/Almaty">Alma-Ata Time</option>
  										<option value="Brazil/West">Amazon Time</option>
  										<option value="Asia/Anadyr">Anadyr Time</option>
  										<option value="Asia/Aqtau">Aqtau Time</option>
  										<option value="Asia/Aqtobe">Aqtobe Time</option>
  										<option value="Asia/Riyadh">Arabia Standard Time</option>
  										<option value="America/Rosario">Argentine Time</option>
  										<option value="NET">Armenia Time</option>
  										<option value="SystemV/AST4ADT">Atlantic Standard Time</option>
  										<option value="Asia/Baku">Azerbaijan Time</option>
  										<option value="Atlantic/Azores">Azores Time</option>
  										<option value="BST">Bangladesh Time</option>
  										<option value="Asia/Thimphu">Bhutan Time</option>
  										<option value="America/La_Paz">Bolivia Time</option>
  										<option value="Brazil/East">Brasilia Time</option>
  										<option value="Asia/Brunei">Brunei Time</option>
  										<option value="Atlantic/Cape_Verde">Cape Verde Time</option>
  										<option value="CAT">Central African Time</option>
  										<option value="Poland">Central European Time</option>
  										<option value="Asia/Ujung_Pandang">Central Indonesia Time</option>
  										<option value="Cuba">Central Standard Time</option>
  										<option value="Australia/North">Central Standard Time (Northern Territory)</option>
  										<option value="Australia/South">Central Standard Time (South Australia)</option>
  										<option value="Australia/Yancowinna">Central Standard Time (South Australia/New South Wales)</option>
  										<option value="Pacific/Saipan">Chamorro Standard Time</option>
  										<option value="Pacific/Chatham">Chatham Standard Time</option>
  										<option value="Chile/Continental">Chile Time</option>
  										<option value="PRC">China Standard Time</option>
  										<option value="Asia/Choibalsan">Choibalsan Time</option>
  										<option value="Indian/Christmas">Christmas Island Time</option>
  										<option value="Indian/Cocos">Cocos Islands Time</option>
  										<option value="America/Bogota">Colombia Time</option>
  										<option value="Pacific/Rarotonga">Cook Is. Time</option>
  										<option value="Zulu">Coordinated Universal Time</option>
  										<option value="Antarctica/Davis">Davis Time</option>
  										<option value="Antarctica/DumontDUrville">Dumont-d\'Urville Time</option>
  										<option value="Asia/Jayapura">East Indonesia Time</option>
  										<option value="Asia/Dili">East Timor Time</option>
  										<option value="Pacific/Easter">Easter Is. Time</option>
  										<option value="Indian/Mayotte">Eastern African Time</option>
  										<option value="Turkey">Eastern European Time</option>
  										<option value="America/Scoresbysund">Eastern Greenland Time</option>
  										<option value="America/New_York" selected="true">Eastern Standard Time</option>
  										<option value="Australia/Sydney">Eastern Standard Time (New South Wales)</option>
  										<option value="Australia/Queensland">Eastern Standard Time (Queensland)</option>
  										<option value="Australia/Tasmania">Eastern Standard Time (Tasmania)</option>
  										<option value="Australia/Victoria">Eastern Standard Time (Victoria)</option>
  										<option value="America/Guayaquil">Ecuador Time</option>
  										<option value="Atlantic/Stanley">Falkland Is. Time</option>
  										<option value="Brazil/DeNoronha">Fernando de Noronha Time</option>
  										<option value="Pacific/Fiji">Fiji Time</option>
  										<option value="America/Cayenne">French Guiana Time</option>
  										<option value="Indian/Kerguelen">French Southern & Antarctic Lands Time</option>
  										<option value="GMT0">GMT+00:00</option>
  										<option value="Etc/GMT-1">GMT+01:00</option>
  										<option value="Etc/GMT-2">GMT+02:00</option>
  										<option value="Etc/GMT-3">GMT+03:00</option>
  										<option value="Mideast/Riyadh89">GMT+03:07</option>
  										<option value="Etc/GMT-4">GMT+04:00</option>
  										<option value="Etc/GMT-5">GMT+05:00</option>
  										<option value="Etc/GMT-6">GMT+06:00</option>
  										<option value="Etc/GMT-7">GMT+07:00</option>
  										<option value="Etc/GMT-8">GMT+08:00</option>
  										<option value="Etc/GMT-9">GMT+09:00</option>
  										<option value="Etc/GMT-10">GMT+10:00</option>
  										<option value="Etc/GMT-11">GMT+11:00</option>
  										<option value="Etc/GMT-12">GMT+12:00</option>
  										<option value="Etc/GMT-13">GMT+13:00</option>
  										<option value="Etc/GMT-14">GMT+14:00</option>
  										<option value="Etc/GMT+1">GMT-01:00</option>
  										<option value="Etc/GMT+2">GMT-02:00</option>
  										<option value="Etc/GMT+3">GMT-03:00</option>
  										<option value="Etc/GMT+4">GMT-04:00</option>
  										<option value="Etc/GMT+5">GMT-05:00</option>
  										<option value="Etc/GMT+6">GMT-06:00</option>
  										<option value="Etc/GMT+7">GMT-07:00</option>
  										<option value="Etc/GMT+8">GMT-08:00</option>
  										<option value="Etc/GMT+9">GMT-09:00</option>
  										<option value="Etc/GMT+10">GMT-10:00</option>
  										<option value="Etc/GMT+11">GMT-11:00</option>
  										<option value="Etc/GMT+12">GMT-12:00</option>
  										<option value="Pacific/Galapagos">Galapagos Time</option>
  										<option value="Pacific/Gambier">Gambier Time</option>
  										<option value="Asia/Tbilisi">Georgia Time</option>
  										<option value="Pacific/Tarawa">Gilbert Is. Time</option>
  										<option value="Iceland">Greenwich Mean Time</option>
  										<option value="Asia/Muscat">Gulf Standard Time</option>
  										<option value="America/Guyana">Guyana Time</option>
  										<option value="US/Hawaii">Hawaii Standard Time</option>
  										<option value="US/Aleutian">Hawaii-Aleutian Standard Time</option>
  										<option value="Hongkong">Hong Kong Time</option>
  										<option value="Asia/Hovd">Hovd Time</option>
  										<option value="IST">India Standard Time</option>
  										<option value="Indian/Chagos">Indian Ocean Territory Time</option>
  										<option value="VST">Indochina Time</option>
  										<option value="Iran">Iran Standard Time</option>
  										<option value="Asia/Irkutsk">Irkutsk Time</option>
  										<option value="Israel">Israel Standard Time</option>
  										<option value="Japan">Japan Standard Time</option>
  										<option value="Asia/Bishkek">Kirgizstan Time</option>
  										<option value="ROK">Korea Standard Time</option>
  										<option value="Pacific/Kosrae">Kosrae Time</option>
  										<option value="Asia/Krasnoyarsk">Krasnoyarsk Time</option>
  										<option value="Pacific/Kiritimati">Line Is. Time</option>
  										<option value="Australia/Lord_Howe">Load Howe Standard Time</option>
  										<option value="Asia/Magadan">Magadan Time</option>
  										<option value="Asia/Kuching">Malaysia Time</option>
  										<option value="Indian/Maldives">Maldives Time</option>
  										<option value="Pacific/Marquesas">Marquesas Time</option>
  										<option value="Pacific/Majuro">Marshall Islands Time</option>
  										<option value="Indian/Mauritius">Mauritius Time</option>
  										<option value="Antarctica/Mawson">Mawson Time</option>
  										<option value="MET">Middle Europe Time</option>
  										<option value="W-SU">Moscow Standard Time</option>
  										<option value="US/Mountain">Mountain Standard Time</option>
  										<option value="Asia/Rangoon">Myanmar Time</option>
  										<option value="Pacific/Nauru">Nauru Time</option>
  										<option value="Asia/Katmandu">Nepal Time</option>
  										<option value="Pacific/Noumea">New Caledonia Time</option>
  										<option value="Pacific/Auckland">New Zealand Standard Time</option>
  										<option value="Canada/Newfoundland">Newfoundland Standard Time</option>
  										<option value="Pacific/Niue">Niue Time</option>
  										<option value="Pacific/Norfolk">Norfolk Time</option>
  										<option value="Asia/Novosibirsk">Novosibirsk Time</option>
  										<option value="Asia/Omsk">Omsk Time</option>
  										<option value="Asia/Oral">Oral Time</option>
  										<option value="US/Pacific-New">Pacific Standard Time</option>
  										<option value="PLT">Pakistan Time</option>
  										<option value="Pacific/Palau">Palau Time</option>
  										<option value="Pacific/Port_Moresby">Papua New Guinea Time</option>
  										<option value="America/Asuncion">Paraguay Time</option>
  										<option value="America/Lima">Peru Time</option>
  										<option value="Asia/Kamchatka">Petropavlovsk-Kamchatski Time</option>
  										<option value="Asia/Manila">Philippines Time</option>
  										<option value="Pacific/Enderbury">Phoenix Is. Time</option>
  										<option value="America/Miquelon">Pierre & Miquelon Standard Time</option>
  										<option value="Pacific/Pitcairn">Pitcairn Standard Time</option>
  										<option value="Pacific/Ponape">Ponape Time</option>
  										<option value="Asia/Qyzylorda">Qyzylorda Time</option>
  										<option value="Indian/Reunion">Reunion Time</option>
  										<option value="Antarctica/Rothera">Rothera Time</option>
  										<option value="Asia/Sakhalin">Sakhalin Time</option>
  										<option value="Europe/Samara">Samara Time</option>
  										<option value="US/Samoa">Samoa Standard Time</option>
  										<option value="Indian/Mahe">Seychelles Time</option>
  										<option value="Singapore">Singapore Time</option>
  										<option value="SST">Solomon Is. Time</option>
  										<option value="Africa/Mbabane">South Africa Standard Time</option>
  										<option value="Atlantic/South_Georgia">South Georgia Standard Time</option>
  										<option value="Asia/Colombo">Sri Lanka Time</option>
  										<option value="America/Paramaribo">Suriname Time</option>
  										<option value="Antarctica/Syowa">Syowa Time</option>
  										<option value="Pacific/Tahiti">Tahiti Time</option>
  										<option value="Asia/Dushanbe">Tajikistan Time</option>
  										<option value="Pacific/Fakaofo">Tokelau Time</option>
  										<option value="Pacific/Tongatapu">Tonga Time</option>
  										<option value="Pacific/Truk">Truk Time</option>
  										<option value="Asia/Ashkhabad">Turkmenistan Time</option>
  										<option value="Pacific/Funafuti">Tuvalu Time</option>
  										<option value="Asia/Ulan_Bator">Ulaanbaatar Time</option>
  										<option value="America/Montevideo">Uruguay Time</option>
  										<option value="Asia/Tashkent">Uzbekistan Time</option>
  										<option value="Pacific/Efate">Vanuatu Time</option>
  										<option value="America/Caracas">Venezuela Time</option>
  										<option value="Asia/Vladivostok">Vladivostok Time</option>
  										<option value="Antarctica/Vostok">Vostok Time</option>
  										<option value="Pacific/Wake">Wake Time</option>
  										<option value="Pacific/Wallis">Wallis & Futuna Time</option>
  										<option value="Asia/Pontianak">West Indonesia Time</option>
  										<option value="Pacific/Apia">West Samoa Time</option>
  										<option value="Africa/Windhoek">Western African Time</option>
  										<option value="WET">Western European Time</option>
  										<option value="America/Godthab">Western Greenland Time</option>
  										<option value="Australia/West">Western Standard Time (Australia)</option>
  										<option value="Asia/Yakutsk">Yakutsk Time</option>
  										<option value="Pacific/Yap">Yap Time</option>
  										<option value="Asia/Yekaterinburg">Yekaterinburg Time</option>
    					   </select>';

      echo '<div style="padding-left:25px;">';
      echo '<div style="float:left; padding-left:15px;"><img src="' . PATH_FOTO . $v['P_THUMB_PATH'] . '" border="1" width="75" height="75" /></div>';
      echo '<div style="float:left; padding-top:15px; padding-left:15px;">';
      echo '<div style="width:120px; text-align:right; padding-right:5px; padding-top:5px;">Title:</div>';
      echo '<div style="width:120px; text-align:right; padding-right:5px; padding-top:10px;">Description:</div>';
      echo '<div style="width:120px; text-align:right; padding-right:5px; padding-top:75px;">Category/License:</div>';
      echo '<div style="width:120px; text-align:right; padding-right:5px; padding-top:10px; display:none;" id="ci_image_' . $v['P_ID'] . '_subcategory_title">Subcatgegory:</div>';
      echo '<div style="width:120px; text-align:right; padding-right:5px; padding-top:10px; display:block;" id="ci_image_' . $v['P_ID'] . '_release_title">Releases:</div>';
      echo '<div style="width:120px; text-align:right; padding-right:5px; padding-top:10px; display:none;" id="ci_image_' . $v['P_ID'] . '_country_title">Country:</div>';
      echo '<div style="width:120px; text-align:right; padding-right:5px; padding-top:10px; display:none;" id="ci_image_' . $v['P_ID'] . '_state_title">State:</div>';
      echo '<div style="width:120px; text-align:right; padding-right:5px; padding-top:10px; display:none;" id="ci_image_' . $v['P_ID'] . '_city_title">City:</div>';
      echo '<div style="width:120px; text-align:right; padding-right:5px; padding-top:10px; display:none;" id="ci_image_' . $v['P_ID'] . '_date_title">Date:</div>';
      echo '<div style="width:120px; text-align:right; padding-right:5px; padding-top:10px; display:none;" id="ci_image_' . $v['P_ID'] . '_time_title">Time:</div>';
      echo '<div style="width:120px; text-align:right; padding-right:5px; padding-top:10px;">Keywords:</div>';
      echo '</div>';

      echo '<div style="padding-top:15px;">';
      echo '<div style="padding-top:5px;"><input type="text" name="ci_image_' . $v['P_ID'] . '_title" id="ci_image_' . $v['P_ID'] . '_title" size="20" maxlength="50" class="formfield" value="' . $photoRs['P_NAME'] . '" />' . $required . '</div>';
      echo '<div style="padding-top:8px;"><textarea name="ci_image_' . $v['P_ID'] . '_description" id="ci_image_' . $v['P_ID'] . '_description" rows="4" cols="50" class="formfield">' . $photoRs['P_DESC'] . '</textarea>' . $required . '</div>';
      echo '<div style="padding-top:13px;">' . $licenseTypes . $required . '</div>';
      echo '<div style="padding-top:5px; display:none;" id="ci_image_' . $v['P_ID'] . '_subcategory_block">' . $subcategoryTypes . $required . '</div>';
      echo '<div style="padding-top:5px; display:block;" id="ci_image_' . $v['P_ID'] . '_release_block"><input type="checkbox" name="ci_image_' . $v['P_ID'] . '_release" id="ci_image_' . $v['P_ID'] . '_release" value="agree" class="formfield" />Release(s) Required</div>';
      echo '<div style="padding-top:5px; display:none;" id="ci_image_' . $v['P_ID'] . '_country_block"><select name="ci_image_' . $v['P_ID'] . '_country" id="ci_image_' . $v['P_ID'] . '_country" class="formfield">' . optionCountries($country) . '</select>' . $required . '</div>';
      echo '<div style="padding-top:6px; display:none;" id="ci_image_' . $v['P_ID'] . '_state_block"><select name="ci_image_' . $v['P_ID'] . '_state" id="ci_image_' . $v['P_ID'] . '_state" class="formfield">' . optionStates($state) . '</select>' . $required . '</div>';
      echo '<div style="padding-top:6px; display:none;" id="ci_image_' . $v['P_ID'] . '_city_block"><input type="text" name="ci_image_' . $v['P_ID'] . '_city" id="ci_image_' . $v['P_ID'] . '_city" size="20" maxlength="50" class="formfield" value="' . $city . '" />' . $required . '</div>';
      echo '<div style="padding-top:6px; display:none;" id="ci_image_' . $v['P_ID'] . '_date_block">';
      echo '<div style="float:left; padding-right:5px;">' . $months . '</div>';
      echo '<div style="float:left; padding-right:5px;">' . $days . '</div>';
      echo '<div>' . $years . $required . '</div>';
      echo '</div>';
      echo '<div style="padding-top:5px; display:none;" id="ci_image_' . $v['P_ID'] . '_time_block">';
      echo '<div style="float:left; padding-right:5px;">' . $hours . ' :</div>';
      echo '<div style="float:left; padding-right:5px;">' . $minutes . '</div>';
      echo '<div style="float:left; padding-right:5px;">' . $ampm . '</div>';
      echo '<div>' . $timezones . $required . '</div>';
      echo '</div>';
      echo '<div style="padding-top:5px;"><input type="text" name="ci_image_' . $v['P_ID'] . '_keywords" id="ci_image_' . $v['P_ID'] . '_keywords" size="53" maxlength="200" class="formfield" value="' . preg_replace('/^\,|\,$/', '', $photoRs['P_TAGS']) . '" />' . $required . '</div>';
      echo '</div>';
      echo '<div style="border-top:1px dotted gray; width:600px; margin-left:15px; margin-top:15px; margin-bottom:25px;"></div>';
      echo' </div>';

      echo '<script>
              function toggleLicense_' . $v['P_ID'] . '()
              {
                if($(\'ci_image_' . $v['P_ID'] . '_license\').options[$(\'ci_image_' . $v['P_ID'] . '_license\').selectedIndex].value == \'creative\')
                {
                  $(\'ci_image_' . $v['P_ID'] . '_release_title\').style.display = \'block\';
                  $(\'ci_image_' . $v['P_ID'] . '_release_block\').style.display = \'block\';

                  $(\'ci_image_' . $v['P_ID'] . '_subcategory_title\').style.display = \'none\';
                  $(\'ci_image_' . $v['P_ID'] . '_subcategory_block\').style.display = \'none\';
                  $(\'ci_image_' . $v['P_ID'] . '_country_title\').style.display = \'none\';
                  $(\'ci_image_' . $v['P_ID'] . '_country_block\').style.display = \'none\';
                  $(\'ci_image_' . $v['P_ID'] . '_state_title\').style.display = \'none\';
                  $(\'ci_image_' . $v['P_ID'] . '_state_block\').style.display = \'none\';
                  $(\'ci_image_' . $v['P_ID'] . '_city_title\').style.display = \'none\';
                  $(\'ci_image_' . $v['P_ID'] . '_city_block\').style.display = \'none\';
                  $(\'ci_image_' . $v['P_ID'] . '_date_title\').style.display = \'none\';
                  $(\'ci_image_' . $v['P_ID'] . '_date_block\').style.display = \'none\';
                  $(\'ci_image_' . $v['P_ID'] . '_time_title\').style.display = \'none\';
                  $(\'ci_image_' . $v['P_ID'] . '_time_block\').style.display = \'none\';
                }
                else if($(\'ci_image_' . $v['P_ID'] . '_license\').options[$(\'ci_image_' . $v['P_ID'] . '_license\').selectedIndex].value == \'editorial\')
                {
                  $(\'ci_image_' . $v['P_ID'] . '_release_title\').style.display = \'none\';
                  $(\'ci_image_' . $v['P_ID'] . '_release_block\').style.display = \'none\';

                  $(\'ci_image_' . $v['P_ID'] . '_subcategory_title\').style.display = \'block\';
                  $(\'ci_image_' . $v['P_ID'] . '_subcategory_block\').style.display = \'block\';
                  $(\'ci_image_' . $v['P_ID'] . '_country_title\').style.display = \'block\';
                  $(\'ci_image_' . $v['P_ID'] . '_country_block\').style.display = \'block\';
                  $(\'ci_image_' . $v['P_ID'] . '_state_title\').style.display = \'block\';
                  $(\'ci_image_' . $v['P_ID'] . '_state_block\').style.display = \'block\';
                  $(\'ci_image_' . $v['P_ID'] . '_city_title\').style.display = \'block\';
                  $(\'ci_image_' . $v['P_ID'] . '_city_block\').style.display = \'block\';
                  $(\'ci_image_' . $v['P_ID'] . '_date_title\').style.display = \'block\';
                  $(\'ci_image_' . $v['P_ID'] . '_date_block\').style.display = \'block\';
                  $(\'ci_image_' . $v['P_ID'] . '_time_title\').style.display = \'block\';
                  $(\'ci_image_' . $v['P_ID'] . '_time_block\').style.display = \'block\';
                }
              }
            </script>';
    }

  if(!empty($_POST) && !isset($_POST['image_error']))
  {
  ?>
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

    <input type="hidden" name="ci_category_type" value="<?php echo $categoryType;?>" />
    <input type="hidden" name="ci_public_name" value="<?php echo $publicName;?>" />
    <input type="hidden" name="ci_web_site" value="<?php echo $webSite;?>" />
    <input type="hidden" name="ci_biography" value="<?php echo $biography;?>" />
    <input type="hidden" name="ci_camera_make" value="<?php echo $cameraMake;?>" />
    <input type="hidden" name="ci_camera_model" value="<?php echo $cameraModel;?>" />
    <input type="hidden" name="ci_camera_resolution" value="<?php echo $cameraResolution;?>" />
    <input type="hidden" name="ci_camera_equipment" value="<?php echo $cameraEquipment;?>" />
    <input type="hidden" name="ci_training_schools" value="<?php echo $trainingSchools;?>" />
    <input type="hidden" name="ci_training_courses" value="<?php echo $trainingCourses;?>" />
    <input type="hidden" name="ci_speciality_list" value="<?php echo $specialities;?>" />

  <?php
  }
  ?>

  <input type="hidden" name="ci_number_photos" value="<?php echo count($photosRs);?>" />
  
  <div align="center">
    <div id="registrationMessage"></div>
    <div id="registrationButton">
      <a href="javascript:void(0);" onclick="if(_val_registration()){ registrationSubmitFcn(); }" class="plain bold f_11">
        Continue<img src="images/icons/right_24x24.png" class="png" border="0" hspace="4" width="24" height="24" align="absmiddle" />     
      </a>
    </div>
  </div>
  
</form>

<?php
  $fv -> setMaxElementsToDisplay(10);
  $fv -> setDebugOutput(false);
  $fv -> setFunctionName('_val_registration');
  $fv -> validate();
?>

<script>
  function registrationSubmitFcn()
  {
    $('registrationButton').style.display = 'none';
    $('registrationMessage').innerHTML = '<img src="images/ajax_loader_snake.gif" width="16" height="16" hspace="3" align="absmiddle" />Please wait...';
    $('_ciRegistration').submit();
  }
</script>
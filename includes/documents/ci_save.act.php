<?php

  // if they are registering for the first time
  // else, they are just trying to upload photos
  if(!empty($_POST['ci_username']))
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


    // try to save the information
    $obj =& CCitizenImage::getInstance();

    $obj->setUsername($username);
    $obj->setPassword($password);
    $obj->setFirstname($nameFirst);
    $obj->setLastname($nameLast);
    $obj->setEmailaddress($email);
    $obj->setPaymenttype($paymentType);
    $obj->setStreetaddress1($street1);
    $obj->setStreetaddress2($street2);
    $obj->setCity($city);
    $obj->setState($state);
    $obj->setZip($zip);
    $obj->setCountry($country);
    $obj->setAcceptagreement('true');
    $obj->setExperiencelevel($categoryType);
    $obj->setPublicname($nameFirst . ' ' . $nameLast);
    $obj->setWebsite($webSite);
    $obj->setBiography($biography);
    $equipment = array();
    $cam1 = new CCitizenImageCamera($cameraMake,$cameraModel,$cameraResolution,$cameraEquipment);
    array_push($equipment,$cam1);
    $obj->setEquipment($equipment);
    $obj->setSchools($trainingSchools);
    $obj->setCourses($trainingCourses);
    $obj->setSpecialties($specialities);
    $obj->setAccepthostingagreement('true');

    $response = $obj->save();
    
    // if it's a success then queue the photos
    // else, send them back to step 1
    if($response['response'] == 'success')
    {
			// save the citizen image credentials
			$obj->saveCredentials(array('USER_ID' => $_USER_ID, 'USERNAME' => $_POST['ci_username'], 'PASSWORD' => $_POST['ci_password']));
			
      // variables for each photo being uploaded
      // queue each photo
      $obj =& CCIImage::getInstance();

      $numberPhotos = $_POST['ci_number_photos'];

      for($i = 0; $i < $numberPhotos; $i++)
      {
        $id = $_POST['ci_image_' . $i];
        //$image = $_POST['ci_image_' . $id];
				$title = $_POST['ci_image_' . $id . '_title'];
				$description = $_POST['ci_image_' . $id . '_description'];
        $category = $_POST['ci_image_' . $id . '_license'];
				$release = $_POST['ci_image_' . $id . '_release'];
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
        $keywordsArr = split(',', $keywords);

        if($ampm == 1)
        {
          $hour += 12;
        }

        $datetime = mktime($hour, $minute, 0, ($month+1), $day, $year);
        
				if($release == 'agree')
				{
					$release = 1;
				}
				else
				{
					$release = 0;
				}

        //$obj->setImagedata($image);
				$obj->setTitle($title);
				$obj->setDescription($description);
        $obj->setCategory($category);
				$obj->setRelease($release);
        $obj->setSubcategory($subCategory);
        $obj->setDatetime($datetime);
				$obj->setTimezone($timezone);
        //$obj->setLicensetype('license');
        $obj->setLocation(new CCitizenImageLocation($country, $state, $city));
        $obj->setKeywords($keywords);

        //$obj->getImageXML();
				$obj->batchPhoto($_USER_ID, $id);
      }

      // go somewhere
      $url = '/?action=ci.confirmation';
    }
    else
    {
      echo '<form name="_ciRegistration" id="_ciRegistration" method="POST" action="/?action=ci.register">';
      echo '<input type="hidden" name="login_error" value="true" />';
      foreach($_POST as $k => $v)
      {
        echo '<input type="hidden" name="' . $k . '" value="' . $v . '" />';
      }
      echo '<input type="image" src="images/spacer.gif" width="1" height="1" />
            </form>';

      echo '<script>';
      echo '  document.getElementById(\'_ciRegistration\').submit();';
      echo '</script>';
      die();
    }
  }
  else
  {
		// variables for each photo being uploaded
    // queue each photo
    $obj =& CCIImage::getInstance();

      $numberPhotos = $_POST['ci_number_photos'];

      for($i = 0; $i < $numberPhotos; $i++)
      {
        $id = $_POST['ci_image_' . $i];
        //$image = $_POST['ci_image_' . $id];
				$title = $_POST['ci_image_' . $id . '_title'];
				$description = $_POST['ci_image_' . $id . '_description'];
        $category = $_POST['ci_image_' . $id . '_license'];
				$release = $_POST['ci_image_' . $id . '_release'];
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
        $keywordsArr = split(',', $keywords);

        if($ampm == 1)
        {
          $hour += 12;
        }

        $datetime = mktime($hour, $minute, 0, ($month+1), $day, $year);

				if($release == 'agree')
				{
					$release = 1;
				}
				else
				{
					$release = 0;
				}

        //$obj->setImagedata($image);
				$obj->setTitle($title);
				$obj->setDescription($description);
        $obj->setCategory($category);
				$obj->setRelease($release);
        $obj->setSubcategory($subCategory);
        $obj->setDatetime($datetime);
				$obj->setTimezone($timezone);
        //$obj->setLicensetype('license');
        $obj->setLocation(new CCitizenImageLocation($country, $state, $city));
        $obj->setKeywords($keywords);

        //$obj->getImageXML();
				$obj->batchPhoto($_USER_ID, $id);
      }

      // go somewhere
      $url = '/?action=ci.confirmation';
  }
?>
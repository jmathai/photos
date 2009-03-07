<?php
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
?>

<div style="margin-left:25px; padding-top:25px;">
   <div>IMAGE HOSTING AND LICENSING AGREEMENT THAT FOLLOWS - TAKE NOTE OF THESE FACTS</div>
  <div style="padding-left:10px; padding-top:10px;">Before you click OK on the agreement that follows please take note below of they key things that you are signing up for. Knowing these facts protects you and us from nasty lawsuits from third parties. This is not the agreement itself - merely an explanation of important stuff to remember.
  By uploading content here.</div>
	<div style="padding-left:10px; padding-top:10px;">
		<ol>
     <li>You are giving us a 2 year marketing rights for each image that you upload to us that is accepted.</li>
        For Creative and standard (non-newsworthy) Editorial images this is a non-exclusive marketing right.
        For Newsworthy Editorial images we require 3 month exclusive marketing rights at the start of this Two year period. This means you cannot sell the same OR SIMILAR* newsworthy editorial images elsewhere during the covered period. This is because we will be issuing rights on your behalf and need to avoid another issuer of rights issuing conflicting rights to ones issued by us.
        This 2 year deal AUTOMATICALLY renews for 1 year periods at the end of the term unless either party terminates with written notice.
        Licenses that survive the term are covered beyond the term.
     <li>You are acknowledging that the images uploaded are yours to sell and license and that you have not broken any laws in producing the image or in offering it to us.</li>
     <li>You are indemnifying us against wrongful actions by you.</li>
     <li>You are adhering to our submission guidelines. This includes making sure that, where appropriate, you have the appropriate model and property releases for subjects shot.</li>
	</ol>

  <div style="padding-left:10px;">
    * Definition of "Similars" means an Image in analogue or digital form that is substantially similar to any Accepted Image and which may reasonably cause an industry professional viewing the image to believe it is the same or substantially the same image, whether in color or black and white. By way of illustration only, landscapes, cityscapes and scenic images are similar if nearly the exact scene or subject matter within the same photographic shoot. Arranged or posed Photographs are considered similar if using the same models, with the same clothing doing the same activity within a photographic shoot. The use of different angles lenses or other creative techniques may minimize the similarity in photographs taken on the same shoot.
	</div>
	<br clear="all" />
  
	<div class="center">
    <div style="margin:25px 0px 5px 0px;">Download or print a copy of this agreement: <a href="http://www.citizenimage.com/legal/CitizenImageImageHostingAgreement.pdf" target="_blank">Citizen Image Image Hosting and Licensing Agreement PDF</a></div>
    <div>
      <iframe src="http://www.citizenimage.com/legal/upload_agreement.html" width="550" height="200"></iframe>
    </div>
    <div>
      <div style="padding-top:2px; line-height:20px;">
        <input type="checkbox" id="ci_agreement_check" value="agree" class="formfield" />&nbsp;I agree to the above terms
    </div>
  
    <form name="_ciRegistration" id="_ciRegistration" method="POST" action="/?action=ci.upload_images">
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
  
      <div>
        <div id="registrationMessage"></div>
        <div id="registrationButton">
          <a href="javascript:void(0);" onclick="registrationSubmitFcn();" class="plain bold f_11">
            Continue<img src="images/icons/right_24x24.png" class="png" border="0" hspace="4" width="24" height="24" align="absmiddle" />     
          </a>
        </div>
        <!--
        <br/>
        <div id="disagreeButton">
          <a href="/?action=confirm.main&type=ci_disagree" class="plain bold f_11">
            Do not continue<img src="images/icons/close_24x24.png" class="png" border="0" hspace="4" width="24" height="24" align="absmiddle" />
          </a>
        </div>
        -->
      </div>
    </form>
  </div>
</div>

<script>
  function registrationSubmitFcn()
  {
    if($('ci_agreement_check').checked == true)
    {
      $('registrationButton').style.display = 'none';
      $('registrationMessage').innerHTML = '<img src="images/ajax_loader_snake.gif" width="16" height="16" hspace="3" align="absmiddle" />Please wait...';
      $('_ciRegistration').submit();
    }
    else
    {
      alert('You must agree to the hosting and licensing agreement.');
    }
  }
</script>
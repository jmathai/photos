<?php
	$ci = &CCitizenImage::getInstance();

	// send them to the upload images form if they already have an account
	if($ci->accountExists($_USER_ID))
	{
    $url = '/?action=ci.upload_images';
	}
	else
	{
	 $url = '/?action=ci.start';
	}
?>
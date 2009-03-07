<?php
  $u_id = $_GET['u_id'];
  
  $sql = 'SELECT * FROM users WHERE u_id = ' . intval($u_id);
  $user = $GLOBALS['dbh']->query_first($sql);
  
  if(!empty($user))
  {
    $sql = 'SELECT * FROM user_fotos WHERE up_u_id = ' . intval($u_id);
		$photos = $GLOBALS['dbh']->query_all($sql);
    
    $sql = 'SELECT * FROM user_slideshows WHERE us_u_id = ' . intval($u_id);
		$slideshows = $GLOBALS['dbh']->query_all($sql);
    
    $sql = 'SELECT * FROM user_videos WHERE v_u_id = ' . intval($u_id);
		$videos = $GLOBALS['dbh']->query_all($sql);
    
    $sql = 'SELECT * FROM ecom_recur WHERE er_u_id = ' . intval($u_id);
    $ecom = $GLOBALS['dbh']->query_first($sql);
    
    $sql = 'SELECT * FROM ecom_recur_results WHERE err_er_id = ' . intval($ecom['er_id']);
    $ecomResults = $GLOBALS['dbh']->query_all($sql);
    
    switch($user['u_accountType'])
    {
      case '0':
				$aType = 'Personal - ' . $ecom['er_period'];
				break;
			case '1':
				$aType = 'Professional - ' . $ecom['er_period'];
				break;
      default:
        $aType = 'Error';
        break;
    }
    
    $businessName = 'No Business Name';
    if(!empty($user['u_business']))
    {
      $businessName = $user['u_business'];
    }
    
    $ecomBusinessName = 'No Business Name';
    if(!empty($ecom['er_ccBusiness']))
    {
      $ecomBusinessName = $ecom['er_ccBusiness'];
    }
    
		$ecomStatus = 'No Payment Information';
    if(!empty($ecom['er_status']))
    {
      $ecomStatus = $ecom['er_status'];
    }
		
    $qs = '&u_username=' . $_GET['u_username'] . '&u_email=' . $_GET['u_email'] . '&u_nameFirst=' . $_GET['u_nameFirst'] . '&u_nameLast=' . $_GET['u_nameLast'];
    echo '<div class="f_8" style="padding-top:25px;"><a href="/cp/?action=users.search_results' . $qs . '">Back to results</a></div>';
    
    echo '<div class="f_12 bold" style="padding-top:25px; padding-bottom:15px; color:#0000FF;">Personal Information</div>';
    
    echo '<div style="float:left;">';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Username</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Password</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Key</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Email</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">First Name</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Last Name</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Birthday</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Address</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">City, State, Zip</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Secret</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Account Type</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Business Name</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Date Created</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Date Expires</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Status</div>';
    echo '</div>';
    echo '<div style="float:left;">';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . $user['u_username'] . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . $user['u_password'] . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . $user['u_key'] . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . $user['u_email'] . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . $user['u_nameFirst'] . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . $user['u_nameLast'] . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . $user['u_birthMonth'] . ' - ' . $user['u_birthDay'] . ' - ' . $user['u_birthYear'] . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . $user['u_address'] . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . $user['u_city'] . ', ' . $user['u_state'] . ' ' . $user['u_zip'] . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . $user['u_secret'] . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . $aType . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . $businessName . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . date('Y-m-d', strtotime($user['u_dateCreated'])) . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . date('Y-m-d', strtotime($user['u_dateExpires'])) . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;" id="u_status">' . $user['u_status'] . '</div>';
    echo '</div>';

    echo '<div style="float:left; padding-left:45px;">';
    echo '<div>Find MD5:</div>';
    echo '<div><input type="text" id="md5_request" name="md5_request" maxlength="500" size="30" value="" /> &nbsp; <a href="javascript:void(0);" onclick="getMD5($(\'md5_request\').value)">Submit</a></div>';
    echo '<div style="padding-top:10px;" id="md5_result">Result:</div>';
		echo '<div style="padding-top:220px;">';
		echo '<div><a href="javascript:void(0);" onclick="disableAccount(' . $user['u_id'] . ');">Disable Account</a></div>';
		echo '</div>';
		echo '<div style="padding-top:5px; padding-bottom:5px;">';
		echo '<div><a href="javascript:void(0);" onclick="enableAccount(' . $user['u_id'] . ');">Enable Account</a></div>';
		echo '</div>';
    echo '</div>';
    echo '<br clear="all" />';
    
    echo '<div class="f_12 bold" style="padding-top:25px; padding-bottom:15px; color:#0000FF;">Media</div>';
    
    echo '<div style="float:left;">';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;"># Photos</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;"># Slideshows</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;"># Videos</div>';
    echo '</div>';
    echo '<div style="float:left;">';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . count($photos) . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . count($slideshows) . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . count($videos) . '</div>';
    echo '</div>';
    echo '<br clear="all" />';
    

    echo '<div style="float:left;">';
    echo '<div class="f_12 bold" style="padding-top:25px; padding-bottom:15px; color:#0000FF;">Payment Information</div>';
    
    echo '<div style="float:left;">';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Credit Card Number</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">CC Expiration</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">CC Ccv</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">CC First Name</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">CC Last Name</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">CC Business</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">CC Address</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">CC City, State, Zip</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Payment Initial Date</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Payment Period</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Payment Amount</div>';
    echo '<div class="f_10 bold" style="padding-bottom:5px; width:150px; text-align:right;">Payment Status</div>';
    echo '</div>';
    echo '<div style="float:left;">';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px; width:200px;" id="ccNum">' . (isset($ecom['er_ccNum']) ? $ecom['er_ccNum'] : '&nbsp;') . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . (isset($ecom['er_ccExpMonth']) ? $ecom['er_ccExpMonth'] . ' - ' : '&nbsp;') . (isset($ecom['er_ccExpYear']) ? $ecom['er_ccExpYear'] : '&nbsp;') . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . (isset($ecom['er_ccCcv']) ? $ecom['er_ccCcv'] : '&nbsp;') . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . (isset($ecom['er_ccNameFirst']) ? $ecom['er_ccNameFirst'] : '&nbsp;') . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . (isset($ecom['er_ccNameLast']) ? $ecom['er_ccNameLast'] : '&nbsp;') . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . $ecomBusinessName . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . (isset($ecom['er_ccStreet']) ? $ecom['er_ccStreet'] : '&nbsp;') . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . (isset($ecom['er_ccCity']) ? $ecom['er_ccCity'] . ', ' : '&nbsp;') . (isset($ecom['er_ccState']) ? $ecom['er_ccState'] : '&nbsp;') . ' ' . (isset($ecom['er_ccZip']) ? $ecom['er_ccZip'] : '&nbsp;') . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . (isset($ecom['er_initialDate']) ? date('Y-m-d', strtotime($ecom['er_initialDate'])) : '&nbsp;') . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . (isset($ecom['er_period']) ? $ecom['er_period'] : '&nbsp;') . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;">' . (isset($ecom['er_amount']) ? $ecom['er_amount'] : '&nbsp;') . '</div>';
    echo '<div class="f_10" style="padding-bottom:5px; padding-left:15px;" id="er_status">' . $ecomStatus . '</div>';
    echo '</div>';

		if(isset($ecom['er_ccNum']) && isset($ecom['er_status']))
		{
			echo '<div style="float:left; padding-left:45px;">';
			echo '<div><a href="javascript:void(0);" onclick="decryptCC(\'' . $ecom['er_ccNum'] . '\')">Decrypt</a></div>';
			echo '</div>';
			echo '<div style="padding-top:230px; padding-left:45px;">';
			echo '<div><a href="javascript:void(0);" onclick="disablePayment(' . $ecom['er_id'] . ', ' . $ecom['er_u_id'] . ');">Disable Payment</a></div>';
			echo '</div>';
			echo '<div style="padding-top:5px; padding-bottom:5px; padding-left:45px;">';
			echo '<div><a href="javascript:void(0);" onclick="enablePayment(' . $ecom['er_id'] . ', ' . $ecom['er_u_id'] . ');">Enable Payment</a></div>';
			echo '</div>';
			echo '</div>';
		}
		else
		{
			echo '</div>';
		}
    
    
    echo '<div style="float:left; padding-left:50px;">';
    echo '<div class="f_12 bold" style="padding-top:25px; padding-bottom:15px; color:#0000FF;">CC Submissions</div>';
    
    echo '<div>';
    echo '<div class="f_10 bold center" style="float:left; padding-bottom:5px; width:150px;">Date</div>';
    echo '<div class="f_10 bold center" style="float:left; padding-bottom:5px; width:150px;">Result</div>';
    echo '<br clear="all" />';
    echo '</div>';
    
    foreach($ecomResults as $k => $v)
    { 
      echo '<div>';
      echo '<div class="f_10 center" style="float:left; padding-bottom:5px; width:150px;">' . date('Y-m-d', strtotime($v['err_dateTime'])) . '</div>';
      echo '<div class="f_10 center" style="float:left; padding-bottom:5px; width:150px;">' . $v['err_result'] . '</div>';
      echo '</div>';
      echo '<br clear="all" />';
    }
    echo '</div>';
    echo '<br clear="all" />';
  }
  else
  {
    echo '<div style="padding-top:25px; padding-bottom:25px; color:#ff0000;" class="f_12 bold">User doesn\'t exist</div>';
  }
?>
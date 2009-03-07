<?php
	// current incomplete users (since Jan 23, 2007)
  $sql = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, '
       . 'u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, '
       . 'u.u_secret AS U_SECRET, UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, '
       . 'uir.uir_response AS U_RESPONSE, uir.uir_customResponse AS U_CUSTOMRESPONSE '
       . 'FROM user_incompletes AS u LEFT JOIN user_incomplete_responses AS uir ON u.u_id = uir.uir_u_id ';

	if(!empty($_GET['dateFrom']))
	{
		$u_dateFrom = $_GET['dateFrom'];
    $dateInfo = split("-", $u_dateFrom);
    $ts = mktime(0, 0, 0, $dateInfo[0], $dateInfo[1], $dateInfo[2]);
    $u_dateFrom = date('Y-m-d', $ts);
    $u_dateFrom = $GLOBALS['dbh']->sql_safe($u_dateFrom);
    $sql .= "WHERE u.u_dateCreated >= {$u_dateFrom} ";

		$u_dateTo = $_GET['dateTo'];
    $dateInfo = split("-", $u_dateTo);
    $ts = mktime(0, 0, 0, $dateInfo[0], $dateInfo[1], $dateInfo[2]);
    $u_dateTo = date('Y-m-d', $ts);
    $u_dateTo = $GLOBALS['dbh']->sql_safe($u_dateTo);
    $sql .= "AND u.u_dateCreated <= {$u_dateTo} ";
	}

	$sql .= 'ORDER BY u.u_dateCreated DESC ';

  $incompleteUsers = $GLOBALS['dbh']->query_all($sql);

	// totals
	$incompleteUsersCount = count($incompleteUsers);


	// write out every user (pro-monthly, pro-yearly, personal-monthly, personal-yearly)
	$users = '';
	$color = 'FFFFFF';
	$count = 1;
	if(is_array($incompleteUsers))
	{
		$ageRange = array(0,0,0,0,0);
		foreach($incompleteUsers as $k => $v)
		{
			$birthdayTs = mktime(0, 0, 0, $v['U_BIRTHMONTH'], $v['U_BIRTHDAY'], $v['U_BIRTHYEAR']);
			$ageTs = time() - $birthdayTs;
			$age = floor($ageTs / (60*60*24*365));

			$users .= '<div style="background-color:#' . $color . '; width:910px; margin-top:5px;">
									 <div style="float:left; width:100px; text-align:center;"><a href="/cp/?action=users.single_result&u_id=' . $v['U_ID'] . '">' . $v['U_USERNAME'] . '</a></div>
									 <div style="float:left; width:50px; text-align:center;">' . $age. '</div>
									 <div style="float:left; width:350px; text-align:center;">' . $v['U_RESPONSE']. '</div>
									 <div style="float:left; width:350px; text-align:center;">' . $v['U_CUSTOMRESPONSE']. '</div>
									 <br clear="all" />
								 </div>';

			$count++;
			if($count % 2 == 0)
			{
				$color = 'EDEDED';
			}
			else
			{
				$color = 'FFFFFF';
			}

			// add to age range frequency
			if($age <= 17)
			{
				$ageRange[0]++;
			}
			elseif($age >= 18 && $age <= 25)
			{
				$ageRange[1]++;
			}
			elseif($age >= 26 && $age <= 35)
			{
				$ageRange[2]++;
			}
			elseif($age >= 36 && $age <= 50)
			{
				$ageRange[3]++;
			}
			elseif($age >= 50)
			{
				$ageRange[4]++;
			}
		}
	}



	echo '<div style="padding-top:25px;" class="f_8">
					<div style="float:left;">
						<div style="float:left; width:200px; text-align:right;">Number of Incomplete Signups:</div>
						<div style="float:left; width:50px; text-align:right;">' . $incompleteUsersCount . '</div>
					</div>
					<br clear="all" />
					<div style="padding-top:15px;">
						<div style="padding-bottom:5px;">Age Range Totals</div>
						<div style="float:left; width:50px; text-align:right;">< 17:</div>
						<div style="float:left; width:30px; text-align:right;">' . $ageRange[0] . '</div>
						<br clear="all" />
						<div style="float:left; width:50px; text-align:right;">18 - 25:</div>
						<div style="float:left; width:30px; text-align:right;">' . $ageRange[1] . '</div>
						<br clear="all" />
						<div style="float:left; width:50px; text-align:right;">26 - 35:</div>
						<div style="float:left; width:30px; text-align:right;">' . $ageRange[2] . '</div>
						<br clear="all" />
						<div style="float:left; width:50px; text-align:right;">36 - 50:</div>
						<div style="float:left; width:30px; text-align:right;">' . $ageRange[3] . '</div>
						<br clear="all" />
						<div style="float:left; width:50px; text-align:right;">> 50:</div>
						<div style="float:left; width:30px; text-align:right;">' . $ageRange[4] . '</div>
						<br clear="all" />
					</div>
					<div style="padding-top:30px;">
						<form name="dateForm" method="GET" action="/cp/">
							<input type="hidden" name="action" value="incomplete_users.home" />
							<div style="float:left; padding-right:50px;">Date Created From (mm-dd-yyyy): <input type="text" name="dateFrom" value="' . $_GET['dateFrom'] . '" class="formfield" /></div>
							<div style="float:left; padding-right:30px;">Date Created To (mm-dd-yyyy): <input type="text" name="dateTo" value="' . $_GET['dateTo'] . '" class="formfield" /></div>
							<div style="float:left;"><input type="submit" value="submit" class="formfield" /></div>
						</form>
						<br clear="all" />
					</div>
					<div style="padding-top:30px;">
						<div style="background-color:#EDEDED; width:910px;">
							<div style="float:left; width:100px; text-align:center; padding-top:12px;">Username</div>
							<div style="float:left; width:50px; text-align:center; padding-top:12px;">Age</div>
							<div style="float:left; width:350px; text-align:center; padding-top:12px;">Response</div>
							<div style="float:left; width:350px; text-align:center; padding-top:12px;">Custom Response</div>
							<br clear="all" />
						</div>
						<div style="padding-top:10px;">' . $users . '</div>
					</div>
					<br clear="all" />
				</div>';
?>
<?php
	// current cancelled pro users - monthly
	$sql = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, '
			 . 'u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, '
			 . 'u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, '
			 . 'u.u_businessName AS U_BUSINESSNAME, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, '
			 . 'UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS, '
			 . 'er.er_initialDate AS ER_INITIAL_DATE, er_amount AS ER_AMOUNT '
			 . 'FROM users AS u INNER JOIN ecom_recur AS er ON u.u_id = er.er_u_id '
			 . 'WHERE u_isTrial = ' . USER_IS_NOT_TRIAL . ' '
			 . 'AND er.er_status = \'Disabled\' '
			 . 'AND er.er_period = \'Monthly\' '
			 . 'AND u.u_accountType = 1 '
       . 'AND u.u_status = \'Cancelled\' ';

	if(!empty($_GET['dateFrom']))
	{
		$u_dateFrom = $_GET['dateFrom'];
    $dateInfo = split("-", $u_dateFrom);
    $ts = mktime(0, 0, 0, $dateInfo[0], $dateInfo[1], $dateInfo[2]);
    $u_dateFrom = date('Y-m-d', $ts);
    $u_dateFrom = $GLOBALS['dbh']->sql_safe($u_dateFrom);
    $sql .= "AND u.u_dateCreated >= {$u_dateFrom} ";

		$u_dateTo = $_GET['dateTo'];
    $dateInfo = split("-", $u_dateTo);
    $ts = mktime(0, 0, 0, $dateInfo[0], $dateInfo[1], $dateInfo[2]);
    $u_dateTo = date('Y-m-d', $ts);
    $u_dateTo = $GLOBALS['dbh']->sql_safe($u_dateTo);
    $sql .= "AND u.u_dateCreated <= {$u_dateTo} ";
	}

	$cancelledProUsersMonthly = $GLOBALS['dbh']->query_all($sql);

	// current cancelled pro users - yearly
	$sql = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, '
			 . 'u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, '
			 . 'u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, '
			 . 'u.u_businessName AS U_BUSINESSNAME, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, '
			 . 'UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS, '
			 . 'er.er_initialDate AS ER_INITIAL_DATE, er_amount AS ER_AMOUNT '
			 . 'FROM users AS u INNER JOIN ecom_recur AS er ON u.u_id = er.er_u_id '
			 . 'WHERE u_isTrial = ' . USER_IS_NOT_TRIAL . ' '
			 . 'AND er.er_status = \'Disabled\' '
			 . 'AND er.er_period = \'Yearly\' '
			 . 'AND u.u_accountType = 1 '
       . 'AND u.u_status = \'Cancelled\' ';

	if(!empty($_GET['dateFrom']))
	{
		$u_dateFrom = $_GET['dateFrom'];
    $dateInfo = split("-", $u_dateFrom);
    $ts = mktime(0, 0, 0, $dateInfo[0], $dateInfo[1], $dateInfo[2]);
    $u_dateFrom = date('Y-m-d', $ts);
    $u_dateFrom = $GLOBALS['dbh']->sql_safe($u_dateFrom);
    $sql .= "AND u.u_dateCreated >= {$u_dateFrom} ";

		$u_dateTo = $_GET['dateTo'];
    $dateInfo = split("-", $u_dateTo);
    $ts = mktime(0, 0, 0, $dateInfo[0], $dateInfo[1], $dateInfo[2]);
    $u_dateTo = date('Y-m-d', $ts);
    $u_dateTo = $GLOBALS['dbh']->sql_safe($u_dateTo);
    $sql .= "AND u.u_dateCreated <= {$u_dateTo} ";
	}

	$cancelledProUsersYearly = $GLOBALS['dbh']->query_all($sql);

	// current cancelled personal users - monthly
	$sql = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, '
			 . 'u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, '
			 . 'u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, '
			 . 'u.u_businessName AS U_BUSINESSNAME, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, '
			 . 'UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS, '
			 . 'er.er_initialDate AS ER_INITIAL_DATE, er_amount AS ER_AMOUNT '
			 . 'FROM users AS u INNER JOIN ecom_recur AS er ON u.u_id = er.er_u_id '
			 . 'WHERE u_isTrial = ' . USER_IS_NOT_TRIAL . ' '
			 . 'AND er.er_status = \'Disabled\' '
			 . 'AND er.er_period = \'Monthly\' '
			 . 'AND u.u_accountType = 0 '
       . 'AND u.u_status = \'Cancelled\' ';

	if(!empty($_GET['dateFrom']))
	{
		$u_dateFrom = $_GET['dateFrom'];
    $dateInfo = split("-", $u_dateFrom);
    $ts = mktime(0, 0, 0, $dateInfo[0], $dateInfo[1], $dateInfo[2]);
    $u_dateFrom = date('Y-m-d', $ts);
    $u_dateFrom = $GLOBALS['dbh']->sql_safe($u_dateFrom);
    $sql .= "AND u.u_dateCreated >= {$u_dateFrom} ";

		$u_dateTo = $_GET['dateTo'];
    $dateInfo = split("-", $u_dateTo);
    $ts = mktime(0, 0, 0, $dateInfo[0], $dateInfo[1], $dateInfo[2]);
    $u_dateTo = date('Y-m-d', $ts);
    $u_dateTo = $GLOBALS['dbh']->sql_safe($u_dateTo);
    $sql .= "AND u.u_dateCreated <= {$u_dateTo} ";
	}

	$cancelledPersonalUsersMonthly = $GLOBALS['dbh']->query_all($sql);

	// current cancelled personal users - yearly
	$sql = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, '
			 . 'u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, '
			 . 'u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, '
			 . 'u.u_businessName AS U_BUSINESSNAME, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, '
			 . 'UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS, '
			 . 'er.er_initialDate AS ER_INITIAL_DATE, er_amount AS ER_AMOUNT '
			 . 'FROM users AS u INNER JOIN ecom_recur AS er ON u.u_id = er.er_u_id '
			 . 'WHERE u_isTrial = ' . USER_IS_NOT_TRIAL . ' '
			 . 'AND er.er_status = \'Disabled\' '
			 . 'AND er.er_period = \'Yearly\' '
			 . 'AND u.u_accountType = 0 '
       . 'AND u.u_status = \'Cancelled\' ';

	if(!empty($_GET['dateFrom']))
	{
		$u_dateFrom = $_GET['dateFrom'];
    $dateInfo = split("-", $u_dateFrom);
    $ts = mktime(0, 0, 0, $dateInfo[0], $dateInfo[1], $dateInfo[2]);
    $u_dateFrom = date('Y-m-d', $ts);
    $u_dateFrom = $GLOBALS['dbh']->sql_safe($u_dateFrom);
    $sql .= "AND u.u_dateCreated >= {$u_dateFrom} ";

		$u_dateTo = $_GET['dateTo'];
    $dateInfo = split("-", $u_dateTo);
    $ts = mktime(0, 0, 0, $dateInfo[0], $dateInfo[1], $dateInfo[2]);
    $u_dateTo = date('Y-m-d', $ts);
    $u_dateTo = $GLOBALS['dbh']->sql_safe($u_dateTo);
    $sql .= "AND u.u_dateCreated <= {$u_dateTo} ";
	}

	$cancelledPersonalUsersYearly = $GLOBALS['dbh']->query_all($sql);


	// totals
	$cancelledProUsersMonthlyCount = count($cancelledProUsersMonthly);
	$cancelledProUsersYearlyCount = count($cancelledProUsersYearly);
	$cancelledPersonalUsersMonthlyCount = count($cancelledPersonalUsersMonthly);
	$cancelledPersonalUsersYearlyCount = count($cancelledPersonalUsersYearly);
	$numberProUsers = $cancelledProUsersMonthlyCount + $cancelledProUsersYearlyCount;
	$numberPersonalUsers = $cancelledPersonalUsersMonthlyCount + $cancelledPersonalUsersYearlyCount;
	$percentProMonthlyUsers = @round(doubleval(count($cancelledProUsersMonthly) / $numberProUsers)*100, 2);
	$percentPersonalMonthlyUsers = @round(doubleval(count($cancelledPersonalUsersMonthly) / $numberPersonalUsers)*100, 2);


	// write out every user (pro-monthly, pro-yearly, personal-monthly, personal-yearly)
	$users = '';
	$color = 'FFFFFF';
	$count = 1;
	$usersArr = array_merge($cancelledProUsersMonthly, $cancelledProUsersYearly, $cancelledPersonalUsersMonthly, $cancelledPersonalUsersYearly);
	$ageRange = array(0,0,0,0,0);
	foreach($usersArr as $k => $v)
	{
		$birthdayTs = mktime(0, 0, 0, $v['U_BIRTHMONTH'], $v['U_BIRTHDAY'], $v['U_BIRTHYEAR']);
		$ageTs = time() - $birthdayTs;
		$age = floor($ageTs / (60*60*24*365));

		// users photos 1st day
		$sql = 'SELECT up_views AS P_VIEWS '
				 . 'FROM user_fotos '
				 . 'WHERE up_u_id = ' . $v['U_ID'] . ' '
				 . 'AND DATE_FORMAT(up_created_at, \'%m%d%Y\') = ' . date('mdY', $v['U_DATECREATED']) . ' ';

		$usersPhotos = $GLOBALS['dbh']->query_all($sql);

		// users slideshows 1st day
		$sql = 'SELECT us_views AS US_VIEWS '
				 . 'FROM user_slideshows '
				 . 'WHERE us_u_id = ' . $v['U_ID'] . ' '
				 . 'AND DATE_FORMAT(us_dateCreated, \'%m%d%Y\') = ' . date('mdY', $v['U_DATECREATED']) . ' ';

		$usersSlideshows = $GLOBALS['dbh']->query_all($sql);

		$numberUsersPhotos = count($usersPhotos);
		$numberUsersSlideshows = count($usersSlideshows);

		// users number of views 1st day
		$numberUsersViews = 0;
		foreach($usersPhotos as $k2 => $v2)
		{
			$numberUsersViews += intval($v2['P_VIEWS']);
		}
		foreach($usersSlideshows as $k3 => $v3)
		{
			$numberUsersViews += intval($v3['US_VIEWS']);
		}


		// users photos 7 days
		$sevenDays = $v['U_DATECREATED'] + (60*60*24*7);

		$sql = 'SELECT up_views AS P_VIEWS '
				 . 'FROM user_fotos '
				 . 'WHERE up_u_id = ' . $v['U_ID'] . ' '
				 . 'AND up_created_at >= ' . $GLOBALS['dbh']->sql_safe(date('Y-m-d 00:00:00', $v['U_DATECREATED'])) . ' AND up_created_at <= ' . $GLOBALS['dbh']->sql_safe(date('Y-m-d 23:59:59', $sevenDays)) . ' ';

		$usersPhotos7Days = $GLOBALS['dbh']->query_all($sql);

		// users slideshows 1st day
		$sql = 'SELECT us_views AS US_VIEWS '
				 . 'FROM user_slideshows '
				 . 'WHERE us_u_id = ' . $v['U_ID'] . ' '
				 . 'AND us_dateCreated >= ' . $GLOBALS['dbh']->sql_safe(date('Y-m-d 00:00:00', $v['U_DATECREATED'])) . ' AND us_dateCreated <= ' . $GLOBALS['dbh']->sql_safe(date('Y-m-d 23:59:59', $sevenDays)) . ' ';

		$usersSlideshows7Days = $GLOBALS['dbh']->query_all($sql);

		$numberUsersPhotos7Days = count($usersPhotos7Days);
		$numberUsersSlideshows7Days = count($usersSlideshows7Days);

		// users number of views 7 day
		$numberUsersViews7Days = 0;
		foreach($usersPhotos7Days as $k2 => $v2)
		{
			$numberUsersViews7Days += intval($v2['P_VIEWS']);
		}
		foreach($usersSlideshows7Days as $k3 => $v3)
		{
			$numberUsersViews7Days += intval($v3['US_VIEWS']);
		}


		// users photos total
		$sql = 'SELECT up_views AS P_VIEWS '
				 . 'FROM user_fotos '
				 . 'WHERE up_u_id = ' . $v['U_ID'] . ' ';

		$usersPhotosTotal = $GLOBALS['dbh']->query_all($sql);

		// users slideshows total
		$sql = 'SELECT us_views AS US_VIEWS '
				 . 'FROM user_slideshows '
				 . 'WHERE us_u_id = ' . $v['U_ID'] . ' ';

		$usersSlideshowsTotal = $GLOBALS['dbh']->query_all($sql);

		$numberUsersPhotosTotal = count($usersPhotosTotal);
		$numberUsersSlideshowsTotal = count($usersSlideshowsTotal);

		// users number of views total
		$numberUsersViewsTotal = 0;
		foreach($usersPhotosTotal as $k2 => $v2)
		{
			$numberUsersViewsTotal += intval($v2['P_VIEWS']);
		}
		foreach($usersSlideshowsTotal as $k3 => $v3)
		{
			$numberUsersViewsTotal += intval($v3['US_VIEWS']);
		}


		$lifespanTs = time() - $v['U_DATECREATED'];
		$totalDays = floor($lifespanTs / (60*60*24));
		$totalMonths = floor($totalDays / 30);
		$lifespanYears = floor($totalMonths / 12);
		$lifespanMonths = $totalMonths - ($lifespanYears * 12);
		$lifespanDays = $totalDays - ($totalMonths * 30);

		$users .= '<div style="background-color:#' . $color . '; width:930px; margin-top:5px;">
								 <div style="float:left; width:100px; text-align:center;"><a href="/cp/?action=users.single_result&u_id=' . $v['U_ID'] . '">' . $v['U_USERNAME'] . '</a></div>
								 <div style="float:left; width:100px; text-align:center;">' . $v['U_NAMEFIRST'] . '</div>
								 <div style="float:left; width:50px; text-align:center;">' . $v['ER_AMOUNT'] . '</div>
								 <div style="float:left; width:50px; text-align:center;">' . $age. '</div>
								 <div style="float:left; width:150px; text-align:center;">' . $numberUsersPhotos . '/' . $numberUsersSlideshows . '/' . $numberUsersViews . '</div>
								 <div style="float:left; width:150px; text-align:center;">' . $numberUsersPhotos7Days . '/' . $numberUsersSlideshows7Days . '/' . $numberUsersViews7Days . '</div>
								 <div style="float:left; width:150px; text-align:center;">' . $numberUsersPhotosTotal . '/' . $numberUsersSlideshowsTotal . '/' . $numberUsersViewsTotal . '</div>
								 <div style="float:left; width:180px; text-align:left;">' . $lifespanYears . ' years, ' . $lifespanMonths . ' months, ' . $lifespanDays . ' days</div>
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

	echo '<div style="padding-top:25px;" class="f_8">
					<div style="float:left;">
						<div style="float:left; width:200px; text-align:right;">Cancelled Professional Users:</div>
						<div style="float:left; width:50px; text-align:right;">' . $numberProUsers . '</div>
					</div>
					<div style="float:left;">
						<div style="float:left; width:200px; text-align:right;">Cancelled Personal Users:</div>
						<div style="float:left; width:50px; text-align:right;">' . $numberPersonalUsers . '</div>
					</div>
					<br clear="all" />
					<div style="float:left; padding-top:3px;">
						<div style="float:left; width:200px; text-align:right;">Percent Professional Monthly:</div>
						<div style="float:left; width:50px; text-align:right;">' . $percentProMonthlyUsers . '</div>
						<div style="float:left;">%</div>
					</div>
					<div style="float;left; padding-top:3px;">
						<div style="float:left; width:188px; text-align:right;">Percent Personal Monthly:</div>
						<div style="float:left; width:50px; text-align:right;">' . $percentPersonalMonthlyUsers . '</div>
						<div style="float:left;">%</div>
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
							<input type="hidden" name="action" value="cancelled_users.home" />
							<div style="float:left; padding-right:50px;">Date Created From (mm-dd-yyyy): <input type="text" name="dateFrom" value="' . $_GET['dateFrom'] . '" class="formfield" /></div>
							<div style="float:left; padding-right:30px;">Date Created To (mm-dd-yyyy): <input type="text" name="dateTo" value="' . $_GET['dateTo'] . '" class="formfield" /></div>
							<div style="float:left;"><input type="submit" value="submit" class="formfield" /></div>
						</form>
						<br clear="all" />
					</div>
					<div style="padding-top:30px;">
						<div style="background-color:#EDEDED; width:930px;">
							<div style="float:left; width:100px; text-align:center; padding-top:12px;">Username</div>
							<div style="float:left; width:100px; text-align:center; padding-top:12px;">First Name</div>
							<div style="float:left; width:50px; text-align:center; padding-top:12px;">Amount</div>
							<div style="float:left; width:50px; text-align:center; padding-top:12px;">Age</div>
							<div style="float:left; width:150px; text-align:center;">1st Day <span class="f_7">(Photos/Slideshows/Views)</span></div>
							<div style="float:left; width:150px; text-align:center;">7 Days <span class="f_7">(Photos/Slideshows/Views)</span></div>
							<div style="float:left; width:150px; text-align:center;">Total <span class="f_7">(Photos/Slideshows/Views)</span></div>
							<div style="float:left; width:180px; text-align:center;">Lifespan</div>
							<br clear="all" />
						</div>
						<div style="padding-top:10px;">' . $users . '</div>
					</div>
					<br clear="all" />
				</div>';
?>
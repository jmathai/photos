<?php
  $fb = &CFotobox::getInstance();
  $f = &CFlix::getInstance();
  $vi = &CVideo::getInstance();


  // professional users - monthly
  $sql = 'SELECT COUNT(u.u_id) AS CNT '
       . 'FROM users AS u INNER JOIN ecom_recur AS er ON u.u_id = er.er_u_id '
       . 'WHERE er.er_period = \'Monthly\' '
       . 'AND u.u_isTrial = ' . USER_IS_NOT_TRIAL . ' '
       . 'AND er.er_status = \'Active\' '
       . 'AND u.u_accountType = 1 '
       . 'AND u.u_status = \'Active\' ';

  $proUsersMonthly = $GLOBALS['dbh']->query_first($sql);

  // professional users yearly
  $sql = 'SELECT COUNT(u.u_id) AS CNT '
       . 'FROM users AS u INNER JOIN ecom_recur AS er ON u.u_id = er.er_u_id '
       . 'WHERE er.er_period = \'Yearly\' '
       . 'AND u.u_isTrial = ' . USER_IS_NOT_TRIAL . ' '
       . 'AND er.er_status = \'Active\' '
       . 'AND u.u_accountType = 1 '
       . 'AND u.u_status = \'Active\' ';

  $proUsersYearly = $GLOBALS['dbh']->query_first($sql);
  
  // personal users monthly
  $sql = 'SELECT COUNT(u.u_id) AS CNT '
       . 'FROM users AS u INNER JOIN ecom_recur AS er ON u.u_id = er.er_u_id '
       . 'WHERE er.er_period = \'Monthly\' '
       . 'AND u.u_isTrial = ' . USER_IS_NOT_TRIAL . ' '
       . 'AND er.er_status = \'Active\' '
       . 'AND u.u_accountType = 0 '
       . 'AND u.u_status = \'Active\' ';

  $personalUsersMonthly = $GLOBALS['dbh']->query_first($sql);
  
  // personal users yearly
  $sql = 'SELECT COUNT(u.u_id) AS CNT '
       . 'FROM users AS u INNER JOIN ecom_recur AS er ON u.u_id = er.er_u_id '
       . 'WHERE er.er_period = \'Yearly\' '
       . 'AND u.u_isTrial = ' . USER_IS_NOT_TRIAL . ' '
       . 'AND er.er_status = \'Active\' '
       . 'AND u.u_accountType = 0 '
       . 'AND u.u_status = \'Active\' ';

  $personalUsersYearly = $GLOBALS['dbh']->query_first($sql);
  
  // trial users personal
  $sql = 'SELECT COUNT(u.u_id) AS CNT '
       . 'FROM users AS u '
       . 'WHERE u.u_accountType = 0 '
       . 'AND u.u_isTrial = ' . USER_IS_TRIAL . ' '
       . 'AND u.u_status = \'Active\' ';

  $trialUsersPersonal = $GLOBALS['dbh']->query_first($sql);
  
  // trial users professional
  $sql = 'SELECT COUNT(u.u_id) AS CNT '
       . 'FROM users AS u '
       . 'WHERE u.u_accountType = 1 '
       . 'AND u.u_isTrial = ' . USER_IS_TRIAL . ' '
       . 'AND u.u_status = \'Active\' ';

  $trialUsersPro = $GLOBALS['dbh']->query_first($sql);
  
  // current cancelled trial users
  $sql = 'SELECT COUNT(u.u_id) AS CNT '
       . 'FROM users AS u  '
       . 'WHERE u.u_isTrial = ' . USER_IS_TRIAL . ' '
       . 'AND u.u_status = \'Cancelled\' ';

  $cancelledTrialUsers = $GLOBALS['dbh']->query_first($sql);
  
  // current cancelled users
  $sql = 'SELECT COUNT(u.u_id) AS CNT '
       . 'FROM users AS u INNER JOIN ecom_recur AS er ON u.u_id = er.er_u_id '
       . 'WHERE u.u_isTrial = ' . USER_IS_NOT_TRIAL . ' '
       . 'AND er.er_status = \'Disabled\' '
       . 'AND u.u_status = \'Cancelled\' ';

  $cancelledUsers = $GLOBALS['dbh']->query_first($sql);
  
  // total sign ups
  $sql = 'SELECT COUNT(u.u_id) AS CNT '
       . 'FROM users AS u ';

  $signups = $GLOBALS['dbh']->query_first($sql);
  // media
  $photosPublic = $GLOBALS['dbh']->query_first('SELECT COUNT(up_id) AS CNT FROM user_fotos WHERE up_privacy >= ' . PERM_PHOTO_PUBLIC . " AND up_status = 'active'");
  $photosPrivate = $GLOBALS['dbh']->query_first('SELECT COUNT(up_id) AS CNT FROM user_fotos WHERE up_privacy = ' . PERM_PHOTO_PRIVATE . " AND up_status = 'active'");

  $slideshowsPublic = $GLOBALS['dbh']->query_first('SELECT COUNT(us_id) AS CNT FROM user_slideshows WHERE us_privacy >= ' . PERM_SLIDESHOW_PUBLIC . " AND us_status = 'active'");
  $slideshowsPrivate = $GLOBALS['dbh']->query_first('SELECT COUNT(us_id) AS CNT FROM user_slideshows WHERE us_privacy = ' . PERM_SLIDESHOW_PRIVATE . " AND us_status = 'active'");
  
  $videosPublic = $GLOBALS['dbh']->query_first('SELECT COUNT(v_id) AS CNT FROM user_videos WHERE v_privacy >= ' . PERM_VIDEO_PUBLIC . " AND v_status = 'active'");
  $videosPrivate = $GLOBALS['dbh']->query_first('SELECT COUNT(v_id) AS CNT FROM user_videos WHERE v_privacy = ' . PERM_VIDEO_PRIVATE . " AND v_status = 'active'");

  // totals

  // users
  $proUsersMonthlyCount = $proUsersMonthly['CNT'];
  $proUsersYearlyCount = $proUsersYearly['CNT'];
  $proUsersCount = $proUsersMonthlyCount + $proUsersYearlyCount;

  $personalUsersMonthlyCount = $personalUsersMonthly['CNT'];
  $personalUsersYearlyCount = $personalUsersYearly['CNT'];
  $personalUsersCount = $personalUsersMonthlyCount + $personalUsersYearlyCount;

  $trialUsersPersonalCount = $trialUsersPersonal['CNT'];
  $trialUsersProCount = $trialUsersPro['CNT'];

  $totalUsersMonthlyCount = $proUsersMonthlyCount + $personalUsersMonthlyCount;
  $totalUsersYearlyCount = $proUsersYearlyCount + $personalUsersYearlyCount;
  $totalUsersCount = $totalUsersMonthlyCount + $totalUsersYearlyCount;

  // media
  $photosPublicCount = $photosPublic['CNT'];
  $photosPrivateCount = $photosPrivate['CNT'];
  $photosCount = $photosPublicCount + $photosPrivateCount;

  $slideshowsPublicCount = $slideshowsPublic['CNT'];
  $slideshowsPrivateCount = $slideshowsPrivate['CNT'];
  $slideshowsCount = $slideshowsPublicCount + $slideshowsPrivateCount;

  $videosPublicCount = $videosPublic['CNT'];
  $videosPrivateCount = $videosPrivate['CNT'];
  $videosCount = $videosPublicCount + $videosPrivateCount;

  $totalMediaPublicCount = $photosPublicCount + $slideshowsPublicCount + $videosPublicCount;
  $totalMediaPrivateCount = $photosPrivateCount + $slideshowsPrivateCount + $videosPrivateCount;
  $totalMediaCount = $totalMediaPublicCount + $totalMediaPrivateCount;
?>

<div class="f_14" style="padding-top:10px; padding-bottom:25px;">Site Statistics</div>

<div style="float:left;">
  <div class="bold" style="padding-bottom:3px;">Total Active Users</div>
  <div class="f_7" style="padding-bottom:10px; padding-left:15px;">total (monthly total/yearly total)</div>
  <div style="float:left;">
    <div class="f_10 center" style="padding-top:5px; width:150px; text-align:right;">Professional Users:</div>
    <div class="f_10 center" style="padding-top:5px; width:150px; text-align:right;">Personal Users:</div>
    <div class="f_10 center" style="padding-top:5px; width:150px; text-align:right;">Total:</div>
    <div class="f_10 center" style="padding-top:15px; width:150px; text-align:right;">Professional Trial:</div>
    <div class="f_10 center" style="padding-top:5px; width:150px; text-align:right;">Personal Trial:</div>
  </div>

  <div style="float:left;">
    <div class="f_10 center" style="padding-top:5px; padding-left:10px;"><?php echo $proUsersCount . ' (' . $proUsersMonthlyCount . '/' . $proUsersYearlyCount . ')'; ?></div>
    <div class="f_10 center" style="padding-top:5px; padding-left:10px;"><?php echo $personalUsersCount . ' (' . $personalUsersMonthlyCount . '/' . $personalUsersYearlyCount . ')'; ?></div>
    <div class="f_10 center" style="padding-top:5px; padding-left:10px;"><?php echo $totalUsersCount . ' (' . $totalUsersMonthlyCount . '/' . $totalUsersYearlyCount . ')'; ?></div>
    <div class="f_10 center" style="padding-top:15px; padding-left:10px;"><?php echo $trialUsersProCount; ?></div>
    <div class="f_10 center" style="padding-top:5px; padding-left:10px;"><?php echo $trialUsersPersonalCount; ?></div>
  </div>
</div>

<div style="float:left; padding-left:150px;">
  <div class="bold" style="padding-bottom:3px;">Media</div>
  <div class="f_7" style="padding-bottom:10px; padding-left:15px;">total (public total/private total)</div>
  <div style="float:left;">
    <div class="f_10 center" style="padding-top:5px; width:100px; text-align:right;">Photos:</div>
    <div class="f_10 center" style="padding-top:5px; width:100px; text-align:right;">Slideshows:</div>
    <div class="f_10 center" style="padding-top:5px; width:100px; text-align:right;">Videos:</div>
    <div class="f_10 center" style="padding-top:15px; width:100px; text-align:right;">Total:</div>
  </div>

  <div style="float:left;">
    <div class="f_10 center" style="padding-top:5px; padding-left:10px;"><?php echo $photosCount . ' (' . $photosPublicCount . '/' . $photosPrivateCount . ')'; ?></div>
    <div class="f_10 center" style="padding-top:5px; padding-left:10px;"><?php echo $slideshowsCount . ' (' . $slideshowsPublicCount . '/' . $slideshowsPrivateCount . ')'; ?></div>
    <div class="f_10 center" style="padding-top:5px; padding-left:10px;"><?php echo $videosCount . ' (' . $videosPublicCount . '/' . $videosPrivateCount . ')'; ?></div>
    <div class="f_10 center" style="padding-top:15px; padding-left:10px;"><?php echo $totalMediaCount . ' (' . $totalMediaPublicCount . '/' . $totalMediaPrivateCount . ')'; ?></div>
  </div>
</div>
<br clear="all" />

<?php
  $monthArray = array();
  $yearArray = array();

  $thisMonth = date('m', strtotime('-11 months'));
  $thisYear = date('Y', strtotime('-11 months'));
  $startMonth = isset($_GET['start_month']) ? $_GET['start_month'] : $thisMonth;
  $year = isset($_GET['year']) ? $_GET['year'] : $thisYear;
  for($j = 0; $j < 12; $j++)
  {
    $m = $startMonth + $j;

    if($m > 12)
    {
      $m = $m - 12;
    }

    if($m == 1 && $j != 0)
    {
      $year++;
    }

    $yearArray[$j] = $year;
    $monthArray[$j] = $m;
  }

  $nextYear = $yearArray[0];
  if($monthArray[0] == 12)
  {
    $nextYear = $yearArray[0] + 1;
  }

  $prevYear = $yearArray[0];
  if($monthArray[0] == 1)
  {
    $prevYear = $yearArray[0] - 1;
  }

  $nextMonth = $monthArray[1];
  $prevMonth = $monthArray[0] - 1;
  if($prevMonth == 0)
  {
    $prevMonth = 12;
  }
?>

<img src="/images/spacer.gif" id="graphImagePer" vspace="10" border="0" / >
<img src="/images/spacer.gif" id="graphImagePro" vspace="10" border="0" / >
<img src="/images/spacer.gif" id="graphImageCon" vspace="10" border="0" / >

<form name="_yearForm" id="_yearForm" method="GET" action="/cp/" style="display:inline;">
  <input type="hidden" name="action" value="stats.home" />
  <div class="bold" style="padding-top:45px; padding-bottom:10px;">Information By Month &nbsp;&nbsp;<a href="/cp/?action=stats.home&year=<?php echo $prevYear; ?>&start_month=<?php echo $prevMonth; ?>" class="f_7 plain"><</a> &nbsp;&nbsp;<a href="/cp/?action=stats.home&year=<?php echo $nextYear; ?>&start_month=<?php echo $nextMonth; ?>" class="f_7 plain">></a></div>
</form>
<div class="f_8 center" style="float:left; width:110px;">Month</div>
<div class="f_8 center" style="float:left; width:90px;">Personal Reg</div>
<div class="f_8 center" style="float:left; width:90px;">Professional Reg</div>
<div class="f_8 center" style="float:left; width:90px;">Trial Cancellations</div>
<div class="f_8 center" style="float:left; width:90px;">Cancellations <span class="f_7">(non-trial) (personal/pro)</span></div>
<div class="f_8 center" style="float:left; width:210px;">Conversions <div class="f_7">(# personal - %/# pro - %)</div></div>
<div class="f_8 center" style="float:left; width:90px;"># Created Slideshows</div>
<div class="f_8 center" style="float:left; width:90px;"># Slideshows With Hotspots</div>
<br clear="all" />

<?php
  $graphArray = $tickLabels = array();
  $shortMonths = $gDateLocale->GetShortMonth();

  foreach($monthArray as $k => $i)
  {
    switch($i)
    {
      case 1:
        $month = 'January';
        break;
      case 2:
        $month = 'February';
        break;
      case 3:
        $month = 'March';
        break;
      case 4:
        $month = 'April';
        break;
      case 5:
        $month = 'May';
        break;
      case 6:
        $month = 'June';
        break;
      case 7:
        $month = 'July';
        break;
      case 8:
        $month = 'August';
        break;
      case 9:
        $month = 'September';
        break;
      case 10:
        $month = 'October';
        break;
      case 11:
        $month = 'November';
        break;
      case 12:
        $month = 'December';
        break;
    }

    $tickLabels[] = $shortMonths[$i-1];

    $year = $yearArray[$k];

    $color = '#FFFFFF';
    if($i % 2 == 0)
    {
      $color = '#dddddd';
    }

    // professional users - monthly
    $sql = 'SELECT COUNT(u.u_id) AS CNT '
         . "FROM (ecom_recur_results  AS err INNER JOIN ecom_recur AS er ON err.err_er_id = er.er_id AND err.err_result = 'Success') INNER JOIN users AS u ON u.u_id = er.er_u_id "
         . 'WHERE er.er_period = \'Monthly\' '
         . 'AND u.u_accountType = 1 '
         . 'AND DATE_FORMAT(u.u_dateCreated, \'%c%Y\') = ' . $i . $year . ' '
         . 'GROUP BY err.err_er_id ';
         //. "HAVING err.er_status = 'Success'";

    $proUsersMonthly = $GLOBALS['dbh']->query_first($sql);


    // professional users yearly
    $sql = 'SELECT COUNT(u.u_id) AS CNT '
         . "FROM (ecom_recur_results  AS err INNER JOIN ecom_recur AS er ON err.err_er_id = er.er_id AND err.err_result = 'Success') INNER JOIN users AS u ON u.u_id = er.er_u_id "
         . 'WHERE er.er_period = \'Yearly\' '
         . 'AND u.u_accountType = 1 '
         . 'AND DATE_FORMAT(u.u_dateCreated, \'%c%Y\') = ' . $i . $year . ' '
         . 'GROUP BY err.err_er_id ';

    $proUsersYearly = $GLOBALS['dbh']->query_first($sql);


    // personal users monthly
    $sql = 'SELECT COUNT(u.u_id) AS CNT '
         . "FROM (ecom_recur_results  AS err INNER JOIN ecom_recur AS er ON err.err_er_id = er.er_id AND err.err_result = 'Success') INNER JOIN users AS u ON u.u_id = er.er_u_id "
         . 'WHERE er.er_period = \'Monthly\' '
         . 'AND u.u_accountType = 0 '
         . 'AND DATE_FORMAT(u.u_dateCreated, \'%c%Y\') = ' . $i . $year . ' '
         . 'GROUP BY err.err_er_id ';

    $personalUsersMonthly = $GLOBALS['dbh']->query_first($sql);


    // personal users yearly
    $sql = 'SELECT COUNT(u.u_id) AS CNT '
         . "FROM (ecom_recur_results  AS err INNER JOIN ecom_recur AS er ON err.err_er_id = er.er_id AND err.err_result = 'Success') INNER JOIN users AS u ON u.u_id = er.er_u_id "
         . 'WHERE er.er_period = \'Yearly\' '
         . 'AND u.u_accountType = 0 '
         . 'AND DATE_FORMAT(u.u_dateCreated, \'%c%Y\') = ' . $i . $year . ' '
         . 'GROUP BY err.err_er_id ';

    $personalUsersYearly = $GLOBALS['dbh']->query_first($sql);

    // reg personal
    $sql = 'SELECT COUNT(u.u_id) AS CNT '
         . 'FROM users AS u '
         . 'WHERE u.u_accountType = 0 '
         . 'AND DATE_FORMAT(u.u_dateCreated, \'%c%Y\') = ' . $i . $year . ' '
         . "AND u.u_status != 'FotoFlix_Pending' ";

    $regPersonal = $GLOBALS['dbh']->query_first($sql);

    // reg pro
    $sql = 'SELECT COUNT(u.u_id) AS CNT '
         . 'FROM users AS u '
         . 'WHERE u.u_accountType = 1 '
         . 'AND DATE_FORMAT(u.u_dateCreated, \'%c%Y\') = ' . $i . $year . ' '
         . "AND u.u_status != 'FotoFlix_Pending' ";

    $regPro = $GLOBALS['dbh']->query_first($sql);

    // current cancelled trial users personal
    $sql = 'SELECT COUNT(u.u_id) AS CNT '
         . 'FROM users AS u '
         . 'WHERE u.u_accountType = 0 '
         . 'AND DATEDIFF(u.u_dateExpires, u.u_dateCreated) < 8 '
         . 'AND u_status = \'Cancelled\' '
         . 'AND u_isTrial = ' . USER_IS_TRIAL . ' '
         . 'AND DATE_FORMAT(u.u_dateCreated, \'%c%Y\') = ' . $i . $year . ' ';

    $cancelledTrialUsersPersonal = $GLOBALS['dbh']->query_first($sql);

    // current cancelled trial users pro
    $sql = 'SELECT COUNT(u.u_id) AS CNT '
         . 'FROM users AS u '
         . 'WHERE u.u_accountType = 1 '
         . 'AND DATEDIFF(u.u_dateExpires, u.u_dateCreated) < 8 '
         . 'AND u_status = \'Cancelled\' '
         . 'AND u_isTrial = ' . USER_IS_TRIAL . ' '
         . 'AND DATE_FORMAT(u.u_dateCreated, \'%c%Y\') = ' . $i . $year . ' ';

    $cancelledTrialUsersPro = $GLOBALS['dbh']->query_first($sql);

    // current cancelled personal users
    $sql = 'SELECT COUNT(u.u_id) AS CNT '
         . 'FROM (users AS u INNER JOIN ecom_recur AS er ON u.u_id = er.er_u_id) LEFT JOIN user_cancellations AS uc ON u.u_id = uc.uc_u_id '
         . 'WHERE er.er_status = \'Disabled\' '
         . 'AND u_status = \'Cancelled\' '
         . 'AND u_isTrial = ' . USER_IS_NOT_TRIAL . ' '
         . 'AND DATE_FORMAT(uc.uc_dateCreated, \'%c%Y\') = ' . $i . $year . ' '
				 . 'AND u.u_accountType = 0 ';

    $cancelledPersonalUsers = $GLOBALS['dbh']->query_first($sql);

		// current cancelled pro users
    $sql = 'SELECT COUNT(u.u_id) AS CNT '
         . 'FROM (users AS u INNER JOIN ecom_recur AS er ON u.u_id = er.er_u_id) LEFT JOIN user_cancellations AS uc ON u.u_id = uc.uc_u_id '
         . 'WHERE er.er_status = \'Disabled\' '
         . 'AND DATE_FORMAT(uc.uc_dateCreated, \'%c%Y\') = ' . $i . $year . ' '
         . 'AND u_status = \'Cancelled\' '
         . 'AND u_isTrial = ' . USER_IS_NOT_TRIAL . ' '
				 . 'AND u.u_accountType = 1 ';

    $cancelledProUsers = $GLOBALS['dbh']->query_first($sql);


    // slideshows
    $sql = 'SELECT * '
         . 'FROM user_slideshows AS us '
         . 'WHERE DATE_FORMAT(us.us_dateCreated, \'%c%Y\') = ' . $i . $year . ' ';

    $slideshows = $GLOBALS['dbh']->query_all($sql);


    // totals
    $regPersonalCount = $regPersonal['CNT'];
    $regProCount = $regPro['CNT'];
    $totalRegCount = $regPersonalCount + $regProCount;

    $trialUsersCancelledCount = $cancelledTrialUsersPersonal['CNT'] + $cancelledTrialUsersPro['CNT'];
    $usersCancelledPersonalCount = $cancelledPersonalUsers['CNT'];
		$usersCancelledProCount = $cancelledProUsers['CNT'];

    $proUsersMonthlyCount = $proUsersMonthly['CNT'];
    $proUsersYearlyCount = $proUsersYearly['CNT'];
    $proUsersCount = $proUsersMonthlyCount + $proUsersYearlyCount;

    $personalUsersMonthlyCount = $personalUsersMonthly['CNT'];
    $personalUsersYearlyCount = $personalUsersYearly['CNT'];
    $personalUsersCount = $personalUsersMonthlyCount + $personalUsersYearlyCount;

    $conversions = $proUsersCount + $personalUsersCount;
    $personalConversionRate = 0;
    $proConversionRate = 0;
    $totalConversionRate = 0;

    $slideshowsCount = count($slideshows);

    // loop through every slideshow
    //   loop through each of its elements if they exist
    //     if it contains a hotspot, add to the hotspot total and go to the next slideshow (break out of the elements for loop)
    $hotspots = 0;
    foreach($slideshows as $vSlideshow)
    {
    	$elementsArr = jsonDecode($vSlideshow['us_elements']);

    	if(is_array($elementsArr))
    	{
	    	foreach($elementsArr as $v2)
	    	{
	    		if(is_array($v2))
	    		{
			      // if hotspots exist
			      if(array_key_exists('hotSpot_arr', $v2))
			      {
			      	// this slideshow has a hotspot
			        $hotspots++;
			        break;
			      }
	    		}
	    	}
    	}
    }


    // graph stuff
    $graphArray['personal'][$k] = $regPersonalCount;
    $graphArray['professional'][$k] = $regProCount;
    $graphArray['conversion'][$k] = $conversions;

    if($regPersonalCount > 0)
    {
      $personalConversionRate = doubleval($personalUsersCount / $regPersonalCount) * 100;
    }
    if($regProCount > 0)
    {
      $proConversionRate = doubleval($proUsersCount / $regProCount) * 100;
    }
    if($totalRegCount > 0)
    {
      $totalConversionRate = doubleval($conversions / $totalRegCount) * 100;
    }

    echo '<div class="f_8" style="float:left; width:110px; padding-top:10px; background-color:' . $color . ';">' . $month . ' - ' . $year . '</div>';
    echo '<div class="f_8 center" style="float:left; width:90px; padding-top:10px; background-color:' . $color . ';">' . $regPersonalCount . '</div>';
    echo '<div class="f_8 center" style="float:left; width:90px; padding-top:10px; background-color:' . $color . ';">' . $regProCount . '</div>';
    echo '<div class="f_8 center" style="float:left; width:90px; padding-top:10px; background-color:' . $color . ';">' . $trialUsersCancelledCount . '</div>';
    echo '<div class="f_8 center" style="float:left; width:90px; padding-top:10px; background-color:' . $color . ';">' . $usersCancelledPersonalCount . '/' . $usersCancelledProCount . '</div>';
    echo '<div class="f_8 center" style="float:left; width:210px; padding-top:10px; background-color:' . $color . ';">' . $conversions . '(' . number_format($totalConversionRate,1) . '%) (' . $personalUsersCount . ' - ' . number_format($personalConversionRate, 1) . '%/' . $proUsersCount . ' - ' . number_format($proConversionRate, 1) . '%)</div>';
    echo '<div class="f_8 center" style="float:left; width:90px; padding-top:10px; background-color:' . $color . ';">' . $slideshowsCount . '</div>';
    echo '<div class="f_8 center" style="float:left; width:90px; padding-top:10px; background-color:' . $color . ';">' . $hotspots . '</div>';
    echo '<br clear="all" />';

  }
  
  // personal
  $graphPer = new Graph(700,175,'auto');
  $graphPer->img->SetMargin(40,40,40,40);
  $graphPer->SetMarginColor('gray9');
  $graphPer->SetScale('textlin');
  $graphPer->xaxis->SetTickLabels($tickLabels);
  $graphPer->xaxis->SetFont(FF_FONT1);
  $graphPer->img->SetAntiAliasing();
  $graphPer->SetShadow();
  $graphPer->title->Set($tickLabels[0] . '(' . $yearArray[0] . ') - ' . $tickLabels[11] . '(' . $yearArray[11] . ')');
  $graphPer->SetColor('cornsilk');
  $graphPer->yscale->SetGrace(10,10);
  
  // professional
  $graphPro = new Graph(700,175,'auto');
  $graphPro->img->SetMargin(40,40,40,40);
  $graphPro->SetMarginColor('gray9');
  $graphPro->SetScale('textlin');
  $graphPro->xaxis->SetTickLabels($tickLabels);
  $graphPro->xaxis->SetFont(FF_FONT1);
  $graphPro->img->SetAntiAliasing();
  $graphPro->SetShadow();
  $graphPro->title->Set($tickLabels[0] . '(' . $yearArray[0] . ') - ' . $tickLabels[11] . '(' . $yearArray[11] . ')');
  $graphPro->SetColor('cornsilk');
  $graphPro->yscale->SetGrace(10,10);
  
  // conversion
  $graphCon = new Graph(700,175,'auto');
  $graphCon->img->SetMargin(40,40,40,40);
  $graphCon->SetMarginColor('gray9');
  $graphCon->SetScale('textlin');
  $graphCon->xaxis->SetTickLabels($tickLabels);
  $graphCon->xaxis->SetFont(FF_FONT1);
  $graphCon->img->SetAntiAliasing();
  $graphCon->SetShadow();
  $graphCon->title->Set($tickLabels[0] . '(' . $yearArray[0] . ') - ' . $tickLabels[11] . '(' . $yearArray[11] . ')');
  $graphCon->SetColor('cornsilk');
  $graphCon->yscale->SetGrace(10,10);
  
  foreach($graphArray as $k => $v)
  {
    $pl = new LinePlot($v);
    $pl->mark->SetWidth(6);
    $pl->SetCenter();
    $pl->SetLegend($k);
    $pl->value->Show();
    $pl->value->SetFormat('%d');
    $pl->value->SetFont(FF_FONT0);
    
    switch($k)
    {
      case 'personal':
        $marker = 'blue';
        $line   = 'blue';
        $mark = MARK_UTRIANGLE;
        $pl->mark->SetType($mark);
        $pl->mark->SetFillColor($marker);
        $pl->SetColor($line);
        $graphPer->Add($pl);
        break;
      case 'professional':
        $marker = 'blueviolet';
        $line   = 'blueviolet';
        $mark = MARK_DTRIANGLE;
        $pl->mark->SetType($mark);
        $pl->mark->SetFillColor($marker);
        $pl->SetColor($line);
        $graphPro->Add($pl);
        break;
      case 'conversion':
        $marker = 'red';
        $line   = 'red';
        $mark = MARK_SQUARE;
        $pl->mark->SetType($mark);
        $pl->mark->SetFillColor($marker);
        $pl->SetColor($line);
        $graphCon->Add($pl);
        break;
    }
  }

  $graphPer->legend->SetColumns(1);
  $graphPer->legend->Pos(.5, .96, 'center', 'bottom');
  
  $graphPro->legend->SetColumns(1);
  $graphPro->legend->Pos(.5, .96, 'center', 'bottom');
  
  $graphCon->legend->SetColumns(1);
  $graphCon->legend->Pos(.5, .96, 'center', 'bottom');
  
  $graphPer->Stroke($graphUrlPer = './graphs/' . date('m-d-Y', NOW) . '-per.jpeg');
  $graphPro->Stroke($graphUrlPro = './graphs/' . date('m-d-Y', NOW) . '-pro.jpeg');
  $graphCon->Stroke($graphUrlCon = './graphs/' . date('m-d-Y', NOW) . '-con.jpeg');
?>

<script>
  $('graphImagePer').src = '<?php echo $graphUrlPer; ?>?<?php echo NOW; ?>';
  $('graphImagePro').src = '<?php echo $graphUrlPro; ?>?<?php echo NOW; ?>';
  $('graphImageCon').src = '<?php echo $graphUrlCon; ?>?<?php echo NOW; ?>';
</script>


<!-- information for user's currently on trial -->
<div class="bold" style="padding-top:35px; padding-bottom:10px;">Trial Information</div>
<div class="f_10 center" style="float:left; width:150px;">Users on Trial</div>
<div class="f_10 center" style="float:left; width:65px;"># Photos</div>
<div class="f_10 center" style="float:left; width:150px;"># Trial Days Left</div>
<div class="f_10 center" style="float:left; width:250px;">Email</div>
<div class="f_10 center" style="float:left; width:120px;">Login Link</div>
<!--
<div class="f_10 center" style="float:left; width:6px; height:35px; border-right:1px dotted gray;">&nbsp;</div>
<div class="f_10 center" style="float:left; width:150px;">Total Registrations</div>
<div class="f_10 center" style="float:left; width:150px;">Total Trial Cancellations</div>
<div class="f_10 center" style="float:left; width:150px;">Total Cancellations (after trial)</div>
-->
<br clear="all" />

<div class="f_10 center" style="float:left; width:800px;">
  <?php

    // current trial users
    $sql = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, '
         . 'u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, '
         . 'u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, '
         . 'u.u_businessName AS U_BUSINESSNAME, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, '
         . 'UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS '
         . 'FROM users AS u '
         . 'WHERE u.u_status = \'Active\' '
         . 'AND u_isTrial = ' . USER_IS_TRIAL . ' '
         . 'AND DATEDIFF(NOW(), u.u_dateCreated) <= 7 '
         . 'ORDER BY u.u_dateCreated DESC ';

    $trialUsers = $GLOBALS['dbh']->query_all($sql);


    if(!empty($trialUsers))
    {
      $j = 0;
      foreach($trialUsers as $k => $v)
      {
        // get the trial user's photos
        $sql = 'SELECT COUNT(*) AS CNT '
             . 'FROM user_fotos '
             . 'WHERE up_u_id = ' . $v['U_ID'] . " AND up_status = 'active'";

        $photos = $GLOBALS['dbh']->query_first($sql);

        $color = '#FFFFFF';
        if($j % 2 == 0)
        {
          $color = '#dddddd';
        }
        $j++;

        $sevenDays = 60*60*24*7;
        $endDate = $v['U_DATECREATED'] + $sevenDays;
        $numberDaysLeft = ceil(($endDate - NOW) / 86400);

        echo '<div style="padding-bottom:5px;">';
        echo '<div style="float:left; width:150px; background-color:' . $color . ';"><a class="plain" href="/cp/?action=users.single_result&u_id=' . $v['U_ID'] . '">' . $v['U_USERNAME'] . '</a></div>';
        echo '<div style="float:left; width:65px; background-color:' . $color . ';" class="center">' . $photos['CNT'] . '</div>';
        echo '<div style="float:left; width:150px; background-color:' . $color . ';" class="center">' . $numberDaysLeft . '</div>';
        echo '<div style="float:left; width:250px; background-color:' . $color . ';" class="center"><a href="mailto:' . $v['U_EMAIL'] . '">' . $v['U_EMAIL'] . '</a></div>';
        echo '<div style="float:left; width:120px; background-color:' . $color . ';" class="center"><a class="plain" href="' . $FF_SERVER_NAME . '/f0t09r.php?username=' . $v['U_USERNAME'] . '" target="_blank">Login</a></div>';
        echo '</div>';
        echo '<br clear="all" />';
      }
    }
    else
    {
      echo '<div style="padding-bottom:5px;">';
      echo '<div style="float:left; width:150px;">No Trial Users</div>';
      echo '<div style="float:left; width:65px;">&nbsp;</div>';
      echo '<div style="float:left; width:150px;">&nbsp;</div>';
      echo '<div style="float:left; width:120px;">&nbsp;</div>';
      echo '</div>';
      echo '<br clear="all" />';
    }
  ?>
</div>
<br clear="all" />

<!-- information for user's currently on extended trial -->
<div class="bold" style="padding-top:35px; padding-bottom:10px;">Extended Trial Information</div>
<div class="f_10 center" style="float:left; width:200px;">Users on Extended Trial</div>
<div class="f_10 center" style="float:left; width:65px;"># Photos</div>
<div class="f_10 center" style="float:left; width:150px;"># Ext. Trial Days Left</div>
<div class="f_10 center" style="float:left; width:250px;">Email</div>
<div class="f_10 center" style="float:left; width:120px;">Login Link</div>
<br clear="all" />

<div class="f_10 center" style="float:left; width:800px;">
  <?php
    // current extended trial users
    $sql = 'SELECT u.u_id AS U_ID, u.u_key AS U_KEY, u.u_username AS U_USERNAME, u.u_email AS U_EMAIL, u.u_nameFirst AS U_NAMEFIRST, '
         . 'u.u_nameLast AS U_NAMELAST, u.u_birthDay AS U_BIRTHDAY, u.u_birthMonth AS U_BIRTHMONTH, u.u_birthYear AS U_BIRTHYEAR, u.u_address AS U_ADDRESS, '
         . 'u.u_city AS U_CITY, u.u_state AS U_STATE, u.u_zip AS U_ZIP, u.u_country AS U_COUNTRY, u.u_secret AS U_SECRET, u.u_accountType AS U_ACCOUNTTYPE, '
         . 'u.u_businessName AS U_BUSINESSNAME, u.u_spaceTotal AS U_SPACETOTAL, u.u_spaceUsed AS U_SPACEUSED, UNIX_TIMESTAMP(u.u_dateModified) AS U_DATEMODIFIED, '
         . 'UNIX_TIMESTAMP(u.u_dateCreated) AS U_DATECREATED, UNIX_TIMESTAMP(u.u_dateExpires) AS U_DATEEXPIRES, u.u_status AS U_STATUS '
         . 'FROM users AS u '
         . 'WHERE DATEDIFF(NOW(), u.u_dateCreated) BETWEEN 8 AND 15 '
         . 'AND u.u_status = \'Active\' '
         . 'AND u_isTrial = ' . USER_IS_TRIAL . ' '
         . 'ORDER BY u.u_dateCreated DESC ';

    $extendedTrialUsers = $GLOBALS['dbh']->query_all($sql);


    if(!empty($extendedTrialUsers))
    {
      $j = 0;
      foreach($extendedTrialUsers as $k => $v)
      {
        // get the extended trial user's photos
        $sql = 'SELECT COUNT(*) AS CNT '
             . 'FROM user_fotos '
             . 'WHERE up_u_id = ' . $v['U_ID'] . ' ';

        $photos = $GLOBALS['dbh']->query_first($sql);

        $color = '#FFFFFF';
        if($j % 2 == 0)
        {
          $color = '#dddddd';
        }
        $j++;

        $fourteenDays = 60*60*24*14;
        $endDate = $v['U_DATECREATED'] + $fourteenDays;
        $numberDaysLeft = ceil(($endDate - NOW) / 86400);

        echo '<div style="padding-bottom:5px;">';
        echo '<div style="float:left; width:200px; background-color:' . $color . ';"><a class="plain" href="/cp/?action=users.single_result&u_id=' . $v['U_ID'] . '">' . $v['U_USERNAME'] . '</a></div>';
        echo '<div style="float:left; width:65px; background-color:' . $color . ';" class="center">' . $photos['CNT'] . '</div>';
        echo '<div style="float:left; width:150px; background-color:' . $color . ';" class="center">' . $numberDaysLeft . '</div>';
        echo '<div style="float:left; width:250px; background-color:' . $color . ';" class="center"><a href="mailto:' . $v['U_EMAIL'] . '">' . $v['U_EMAIL'] . '</a></div>';
        echo '<div style="float:left; width:120px; background-color:' . $color . ';" class="center"><a class="plain" href="' . $FF_SERVER_NAME . '/f0t09r.php?username=' . $v['U_USERNAME'] . '" target="_blank">Login</a></div>';
        echo '</div>';
        echo '<br clear="all" />';
      }
    }
    else
    {
      echo '<div style="padding-bottom:5px;">';
      echo '<div style="float:left; width:200px;">No Extended Trial Users</div>';
      echo '<div style="float:left; width:65px;">&nbsp;</div>';
      echo '<div style="float:left; width:150px;">&nbsp;</div>';
      echo '<div style="float:left; width:120px;">&nbsp;</div>';
      echo '</div>';
      echo '<br clear="all" />';
    }
  ?>
</div>
<br clear="all" />

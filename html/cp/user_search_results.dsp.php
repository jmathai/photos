<?php
  $searchParams = 'WHERE 1 ';
  
	$qs = '';
  if(!empty($_GET['u_username']))
  {
    $u_username = preg_replace('/\W/', '', $_GET['u_username']);
    $searchParams .= "AND u_username LIKE '%$u_username%' ";
		$qs .= '&u_username=' . $u_username;
  }
  
  if(!empty($_GET['u_email']))
  {
    $u_email = preg_replace('/\W/', '', $_GET['u_email']);
    $searchParams .= "AND u_email LIKE '%$u_email%' ";
		$qs .= '&u_email=' . $u_email;
  }
  
  if(!empty($_GET['u_nameFirst']))
  {
    $u_nameFirst = preg_replace('/\W/', '', $_GET['u_nameFirst']);
    $searchParams .= "AND u_nameFirst LIKE '%$u_nameFirst%' ";
		$qs .= '&u_nameFirst=' . $u_nameFirst;
  }
  
  if(!empty($_GET['u_nameLast']))
  {
    $u_nameLast = preg_replace('/\W/', '', $_GET['u_nameLast']);
    $searchParams .= "AND u_nameLast LIKE '%$u_nameLast%' ";
		$qs .= '&u_nameLast=' . $u_nameLast;
  }
  
  if(!empty($_GET['u_dateCreatedFrom']))
  {
    $u_dateFrom = $_GET['u_dateCreatedFrom'];
    $dateInfo = split("-", $u_dateFrom);
    $ts = mktime(0, 0, 0, $dateInfo[0], $dateInfo[1], $dateInfo[2]);
    $u_dateFrom = date('Y-m-d', $ts);
    $u_dateFrom = $GLOBALS['dbh']->sql_safe($u_dateFrom);
    $searchParams .= "AND u_dateCreated >= {$u_dateFrom} ";
  }
  
  if(!empty($_GET['u_dateCreatedTo']))
  {
    $u_dateTo = $_GET['u_dateCreatedTo'];
    $dateInfo = split('-', $u_dateTo);
    $ts = mktime(0, 0, 0, $dateInfo[0], $dateInfo[1], $dateInfo[2]);
    $u_dateTo = date('Y-m-d', $ts);
    $u_dateTo = $GLOBALS['dbh']->sql_safe($u_dateTo);
    $searchParams .= "AND u_dateCreated <= {$u_dateTo} ";
  }
  
  $sql = 'SELECT * FROM users LEFT JOIN ecom_recur ON u_id = er_u_id ' . $searchParams . ' ORDER BY u_id DESC LIMIT 100 ';
  $users = $GLOBALS['dbh']->query_all($sql);
	
  echo '<div class="padding_top_10"></div>
        <div class="bold" style="padding-left:15px; padding-top:3px; padding-bottom:3px; background-color:#ffffff;">
          <div style="width:150px; float:left;">Username</div>
          <div style="width:225px; float:left;">Email</div>
          <div style="width:125px; float:left;">Date Created</div>
          <div style="width:100px; float:left;"># photos</div>
          <div style="width:150px; float:left;">Account Type</div>
          <br clear="all" />
        </div>';
  
  foreach($users as $k => $v)
  {
    switch($v['u_accountType'])
    {
      case '0':
				$aType = 'Personal - ' . $v['er_period'];
				break;
			case '1':
				$aType = 'Professional - ' . $v['er_period'];
				break;
      default:
        $aType = 'Error';
        break;
    }
    
		$sql = 'SELECT * FROM user_fotos WHERE up_u_id = ' . intval($v['u_id']);
		$photos = $GLOBALS['dbh']->query_all($sql);
		
    echo '<div style="padding-top:3px; padding-bottom:3px; background-color:' . ($k % 2 == 0 ? '#f9f6c7' : '#ffffff') . ';">';
    
            if( $v['u_status'] == 'Disabled' )
            {
              echo '<div style="float:left; padding-right:15px;"><a href="javascript:confirmSubmitEnable('. $v['u_id'] . ');" style="color:blue;">+</a></div>';
            }
            else
            {
              echo '<div style="float:left; padding-right:15px;"><a href="javascript:confirmSubmitDisable('. $v['u_id'] . ');" style="color:red;">x</a></div>';
            }
						      
    echo   '<div style="width:150px; float:left;"><a href="/cp/?action=users.single_result&u_id=' . $v['u_id'] . $qs  . '" class="plain"><span title="' . $v['u_username'] . '">' . str_mid($v['u_username'], 10) . '</span></a></div>
            <div style="width:225px; float:left;"><span title="' . $v['u_email'] . '">' . str_mid($v['u_email'], 20) . '</span></div>
            <div style="width:125px; float:left;">' . date('Y-m-d', strtotime($v['u_dateCreated'])) . '</div>
            <div style="width:100px; float:left;">' . count($photos) . '</div>
            <div style="width:150px; float:left;">' . $aType . '</div>
            <br clear="all" />
          </div>';
  }
  
  echo '<div class="padding_top_10"></div>';
?>

<script language="Javascript">
  function Querystring(qs) { // optionally pass a querystring to parse
  	this.params = new Object()
  	this.get=Querystring_get
  	
  	if (qs == null)
  		qs=location.search.substring(1,location.search.length)
  
  	if (qs.length == 0) return
  
  // Turn <plus> back to <space>
  // See: http://www.w3.org/TR/REC-html40/interact/forms.html#h-17.13.4.1
  	qs = qs.replace(/\+/g, ' ')
  	var args = qs.split('&') // parse out name/value pairs separated via &
  	
  // split out each name=value pair
  	for (var i=0;i<args.length;i++) {
  		var value;
  		var pair = args[i].split('=')
  		var name = unescape(pair[0])
  
  		if (pair.length == 2)
  			value = unescape(pair[1])
  		else
  			value = name
  		
  		this.params[name] = value
  	}
  }
  
  function Querystring_get(key, default_) {
  	// This silly looking line changes UNDEFINED to NULL
  	if (default_ == null) default_ = null;
  	
  	var value=this.params[key]
  	if (value==null) value=default_;
  	
  	return value
  }

  function confirmSubmitDisable( userid )
  {
    var agree = confirm("Disable this user?");
    
    var qs = new Querystring()
    var q_username = qs.get("u_username", "")
    var q_email = qs.get("u_email", "")
    var q_dateCreatedFrom = qs.get("u_dateCreatedFrom", "")
    var q_dateCreatedTo = qs.get("u_dateCreatedTo", "")
    
    if (agree)
    {
      agree = confirm("Are you 100% sure you want to disable this user?  There's no turning back after this.");
      
      if (agree)
      {
        document.location.href = './?action=users.disable.act&u_id=' + userid + '&u_username=' + q_username + '&u_email=' + q_email + '&u_dateCreatedFrom=' + q_dateCreatedFrom + '&dateCreatedTo=' + q_dateCreatedTo;
      }
    }
  }
  
  function confirmSubmitEnable( userid )
  {
    var agree = confirm("Enable this user?");
    
    var qs = new Querystring()
    var q_username = qs.get("u_username", "")
    var q_email = qs.get("u_email", "")
    var q_dateCreatedFrom = qs.get("u_dateCreatedFrom", "")
    var q_dateCreatedTo = qs.get("u_dateCreatedTo", "")
    
    if (agree)
    {
      agree = confirm("Are you 100% sure you want to enable this user?  There's no turning back after this.");
      
      if (agree)
      {
        document.location.href = './?action=users.enable.act&u_id=' + userid + '&u_username=' + q_username + '&u_email=' + q_email + '&u_dateCreatedFrom=' + q_dateCreatedFrom + '&dateCreatedTo=' + q_dateCreatedTo;
      }
    }
  }
</script>
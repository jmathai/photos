<?php
  $_SESSION_HASH = $_FF_SESSION->value('sess_hash');

  $u      =& CUser::getInstance();
  $mail   =& CMail::getInstance();
  $um     =& CUserManage::getInstance();
  $idat   =& CIdat::getInstance();

  $error  = false;

  //trap(serialize($_POST));

  $continue = true;

  $redirect = $_GET['redirect'];

  if($continue === true)
  {
    $array_omit = array('u_password_confirm'); // any field not beginning with u_ gets omitted

    $_p_safe = array();
    foreach($_POST as $k => $v)
    {
      if(!in_array($k, $array_omit) && strncmp('u_', $k, 2) == 0)
      {
        $_p_safe[$k] = $v;
      }
    }

		$_p_safe['u_password'] = md5($_p_safe['u_password']);
		$_p_safe['u_status'] = 'Active';
		$_p_safe['u_dateExpires'] = date('Y-m-d', NOW + 604800);

    $result = $um->add( $_p_safe );
    $username = $_POST['u_username'];

    if( $result > 0 )
    {
      $email = $_POST['u_email'];

			$persistent_login = false;
			$user_id = $result;
			$username = $_POST['u_username'];
			$email = $_POST['u_email'];
			$account_type = $_POST['u_accountType'];
			$account_perm = $_POST['u_accountType'];
			$is_trial = USER_IS_TRIAL;

			include_once PATH_DOCROOT . '/login_manual.act.php';

      $url = '/?newAccount=1';

      if(!empty($_POST['promotion']))
      {
        $promotion = $GLOBALS['dbh']->sql_safe($_POST['promotion']);

        $to = FF_EMAIL_FROM_FORMATTED;
        $from = FF_EMAIL_FROM_FORMATTED;
        $subject = 'Promotional Code Entered';

        switch($_POST['promotion'])
        {
          // if there's a second promotion we'll put this in a class
          case 'holiday':
            $forUsername = 'uckevin111';
            $GLOBALS['dbh']->execute("INSERT INTO promotions(p_u_id, p_name) VALUES('{$user_id}', '{$promotion}')");
            $message = str_replace(array('{USERNAME}','{PROMOTIONAL_CODE}','{FOR_USERNAME}'), array($_POST['u_username'], $_POST['promotion'], $forUsername), file_get_contents(PATH_DOCROOT . '/promotion.tpl.php'));
            $headers = "MIME-Version: 1.0\n"
                     . "Content-type: text/html; charset=iso-8859-1\n"
                     . 'Return-Path: ' . $from . "\n"
                     . 'From: ' . $from;

            $mail->send($to, $subject, $message, $headers, $from);
            break;
        }
      }
    }
    else
    {
      $error = true;
    }

    if($error === true)
    {
      $qs = '';
      foreach($_POST as $k => $v)
      {
      if(strstr($k, 'password') == false && strncmp('u_', $k, 2) == 0)
        {
          $qs .= '&' . $k . '=' . $v;
        }
      }

      $parts  = parse_url($_SERVER['HTTP_REFERER']);

      if($u->find($_POST['u_email']))
      {
        $message = 'email_exists';
      }
      elseif($u->checkIncomplete($_POST['u_email']))
      {
        $message = 'email_exists';
      }
      else
      {
      	$message = 'username_exists';
      }

      $url = $parts['path'] . '?' . $parts['query'] . $qs . '&message=' . $message;
    }
  }
  else
  {
    $qs = '';
    foreach($_POST as $k => $v)
    {
      if(strstr($k, 'password') == false && strncmp('u_', $k, 2) == 0)
      {
        $qs .= '&' . $k . '=' . $v;
      }
    }
    $parts  = parse_url($_SERVER['HTTP_REFERER']);
    $url = $parts['path'] . '?' . $parts['query'] . $qs . '&message=password_email_mismatch';
  }
?>
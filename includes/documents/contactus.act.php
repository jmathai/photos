<?php
  $m =& CMail::getInstance();
  
  foreach($_POST as $k => $v)
  {
    $_POST[$k] = htmlspecialchars($v);
  }
  
  $from     = $_POST['email_name'] . '<' . $_POST['email_from'] . '>';
  $subject  = 'Inquiry - ' . $_POST['email_topic'];
  $message  = "Name: {$_POST['email_name']}\nEmail: {$_POST['email_from']}\n\n{$_POST['email_message']}";
  $headers  = "MIME-Version: 1.0\n"
                  . "Content-type: text/plain; charset=iso-8859-1\n";
  
  $m->send(FF_EMAIL_FROM, $subject, $message, $headers, '-f' . FF_EMAIL_FROM);
  
  $url = '/contactus/?message=email_sent';
?>
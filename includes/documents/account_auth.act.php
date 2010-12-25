<?php
// we don't really care about passing out keys
$us = CUser::getInstance();
$user_data = $us->find($_USER_ID);
if(isset($_GET['callbackurl']) && !empty($_GET['callbackurl']) && $user_data['U_KEY'])
{
  $url = $_GET['callbackurl'];
  if(stristr($url, '?'))
    $url .= "&key={$user_data['U_KEY']}";
  else
    $url .= "?key={$user_data['U_KEY']}";
}
else
{
  $url = '/';
}
header("Location: {$url}");
die();

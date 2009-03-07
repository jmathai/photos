<?php
  $result = '';
  if(count($_POST) > 0)
  {
    $u = &CUser::getInstance();
    $userRs = $u->search(array('CP_USER_ID' => $_POST['es_user_id']));
    
    $result = '[id],[username],[key],[fname],[lname],[email],[birthday],[address1],[city],[state],[zip],[country],[accounttype],[business],[dateexpires]' . "\n";
    foreach($userRs as $k => $v)
    {
      $v = str_replace(',', ' ', $v);
      $birthday = $v['U_BIRTHMONTH'] . '/' . $v['U_BIRTHDAY'] . '/' . $v['U_BIRTHYEAR'];
      $dateExpires = date('m/d/Y', $v['U_DATEEXPIRES']);
      $businessName = $v['U_BUSINESSNAME'] != '' ? $v['U_BUSINESSNAME'] : '';
      $result .= $v['U_ID'] . ',' . $v['U_USERNAME'] . ',' . $v['U_KEY'] . ',' . $v['U_NAMEFIRST'] . ',' . $v['U_NAMELAST'] . ',' . $v['U_EMAIL'] . ',' . $birthday . ',' . $v['U_ADDRESS'] . ',' . $v['U_CITY'] . ',' . $v['U_STATE'] . ',' . $v['U_ZIP'] . ',' . $v['U_COUNTRY'] . ',' . $v['U_ACCOUNTTYPE'] . ',' . $businessName . ',' . $dateExpires . "\r\n";
    }
    
    /*
    $filename = "C:\Documents and Settings\All Users\Desktop\intellicontact_list.csv";
    if(!$handle = fopen($filename, 'w')) 
    {
         echo "Cannot open file ($filename)";
    }
    if(fwrite($handle, $result) === false) 
    {
       echo "Cannot write to file ($filename)";
    }
    if($handle != null)
    {
      fclose($handle);
    }
    */
  }
?>

<form name="es_form" action="/cp/?action=email.home" method="POST">
  <div style="padding-top:25px;">Users with user id greater than <input type="text" name="es_user_id" maxlength="6" size="6" /> &nbsp; <input name="es_submit" type="submit" value="Submit" /></div>
  <div style="padding-top:10px;"><textarea id="es_textarea" rows="30" cols="75"><?php echo $result; ?></textarea></div>
</form>
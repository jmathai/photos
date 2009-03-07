<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
      
<head>
<title></title>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1" />
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="pragma" content="no-cache">

<style type="text/css">
  body, td{
    color:#808080;
    font-family:verdana,tahoma,arial;
    font-size:10pt;
  }
  
  .header{
    color:#73787C;
    font-weight:bold;
  }
  
  .bold{
    font-weight:bold;
  }
  
  .f_8{
    font-size:8pt;
  }
  
  .f_7_brown
  {
    color:#A97200;
    font-size:8pt;
  }
  
  .f_14_orange_eml{
    color:#F4612B;
    font-family:verdana,tahoma,arial;
    font-size:12pt;
    font-weight: bold;
  }
  
  .f_14_blue_eml{
    color:#5C9CCA;
    font-family:verdana,tahoma,arial;
    font-size:12pt;
    font-weight: bold;
  }
.style1 {font-size: 9pt}
</style>

</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table border="0" cellpadding="0" cellspacing="0" width="475" height="80" background="{BASE_URL}/images/email/gradient_row_bg.gif">
  <tr>
    <td align="left" valign="top" width="350"><img src="{BASE_URL}/images/email/logo_gradient.gif" width="129" height="35" hspace="10" vspace="5" /></td>
    <td align="right" valign="top" width="75" rowspan="2"><img src="{BASE_URL}/images/email/smiley_cut_in.gif" width="73" height="80" border="0" /></td>
    <td width="50" rowspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td align="left">
      <table border="0" cellspacing="0" cellpadding="0" width="350">
        <tr>
          <td width="15">&nbsp;</td>
          <td align="left" width="335">
            <span class="f_14_orange_eml">{GREETING}</span>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="475" height="80">
  <tr>
    <td width="15">&nbsp;</td>
    <td width="460">
      <span class="f_14_blue_eml">You're Invited</span>
      <br />
      <span class="header">{SENDER_NAME} has invited you to join {GROUP_NAME}</span>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><img src="{BASE_URL}/images/email/private_lock.gif" width="16" height="21" border="0" /></td>
          <td width="3"></td>
          <td valign="middle">(This is a private group accessed by members only)</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="475" height="80">
  <tr>
    <td width="15">&nbsp;</td>
    <td align="center" valign="top" width="225">
      <table border="0" cellpadding="0" cellspacing="0" width="100%" background="{BASE_URL}/images/email/box_brown_bg.gif">
        <tr height="5">
          <td background="{BASE_URL}/images/email/box_brown_top.gif" width="225"></td>
          <td width="5"><img src="{BASE_URL}/images/email/box_brown_top_right.gif" width="5" height="5" border="0" /></td>
        </tr>
        <tr>
          <td>
            <table border="0" cellpadding="3" cellspacing="0">
              <tr>
                <td>
                  <span class="f_7_brown bold">{SENDER_NAME} wrote:</span>
                    <br />
                  <span class="f_7_brown">{SENDER_MESSAGE}</span>
                  
                  <br /><br />
                </td>
              </tr>
            </table>
          </td>
          <td width="5" background="{BASE_URL}/images/email/box_brown_right.gif"></td>
        </tr>
        <tr height="5">
          <td align="left" background="{BASE_URL}/images/email/box_brown_bottom.gif" width="225"><img src="{BASE_URL}/images/email/box_brown_bottom_left.gif" widht="4" height="5" border="0" /></td>
          <td width="5"><img src="{BASE_URL}/images/email/box_brown_bottom_right.gif" width="5" height="5" border="0" /></td>
        </tr>
      </table>
      <br /><br />
      <a href="{ACCEPTANCE_LINK}"><img src="{BASE_URL}/images/email/join_now.gif" width="86" height="25" border="0" /></a>
      <br /><br />
    </td>
    <td width="10">&nbsp;</td>
    <td width="210" align="center" valign="top">
      {GROUP_FOTOS}
    </td>
    <td width="15">&nbsp;</td>
  </tr>
  <tr>
    <td width="15">&nbsp;</td>
    <td colspan="3" width="460">
      In FotoGroups you can:
      <ul>
        <li>View group fotos, FotoFlix and FotoGames </li>
        <li>Manage the group fotos collection</li>
        <li>Participate in the private group forum </li>
        <li>Order DVDs of group FotoFlix</li>
        <li>Create FotoFlix and FotoGames</li>
        <li>Transfer original hi-res fotos to your FotoBox</li>
        <li>and much more</li>
      </ul>
      <span class="f_8">
        If the join now button does not work, copy and paste the url below in the web browser.
        <br />
        <br />
        {ACCEPTANCE_LINK}
      </span>
      <br />
      The FotoFlix Team.
    </td>
  </tr>
</table>

</body>

</html>
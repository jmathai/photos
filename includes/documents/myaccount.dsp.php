<?php
  $_SESSION_HASH = $_FF_SESSION->value('sess_hash');
  
  $us =& CUser::getInstance();
  $ec =& new CEcom($_USER_ID, $_SESSION_HASH);
  
  $user_data = $us->find($_USER_ID);
  $order_data= $ec->getOrders($_USER_ID);
?>

<table border="0" cellpadding="0" cellspacing="0" width="545">
  <tr height="29" class="bg_medium">
    <td align="left" colspan="2" class="f_8 f_black bold"><img src="images/profile.gif" align="absmiddle" hspace="5" />Account for <?php echo $user_data['U_NAMEFIRST'] . ' ' . $user_data['U_NAMELAST']; ?></td>
  </tr>
  <tr height="1"><td colspan="2"><div class="line_dark"></div></td></tr>
  <tr height="28">
    <td width="125" align="right" valign="middle" class="bold">Account Type:&nbsp;</td>
    <td width="420" align="left" valign="middle">
      <?php
        $show_upgrades = false;
        switch($user_data['U_ACCOUNTTYPE'])
        {
          case 'basic':
            echo 'Basic';
            break;
          case 'premium_a_month':
            echo 'Standard Member';
            $show_upgrades = true;
            break;
          case 'premium_a':
            echo 'Standard Member';
            $show_upgrades = true;
            break;
          case 'premium_b':
            echo 'Shutter Bug';
            $show_upgrades = true;
            break;
          case 'premium_c':
            echo 'Power User';
            $show_upgrades = true;
            break;
          case 'premium_a_pro':
          case 'premium_b_pro':
          case 'premium_c_pro':
            echo 'Professional User';
            break;
          case FF_ACCT_TRIAL:
            echo 'Free Member';
            $show_upgrades = true;
            break;
        }
        
        if($show_upgrades === true)
        {
          $cancelled = $us->checkCancel($_USER_ID);
          if($cancelled === false)
          {
            echo '&nbsp;(<a href="/?action=account.upgrade_form">upgrade</a> | <a href="/?action=account.cancel_form">cancel</a>)';
          }
          else
          {
            echo '&nbsp;(Status: Cancelled)';
          }
        }
      ?>
    </td>
  </tr>
  <tr height="28">
    <td width="125" align="right" valign="middle" class="bold">Account Usage:&nbsp;</td>
    <td width="420" align="left" valign="middle">
      <?php
        if($show_upgrades === true)
        {
      ?>
          <table border="0">
            <tr>
              <td><?php include_once PATH_DOCROOT . '/fotobox_usage.dsp.php'; ?></td>
              <td>&nbsp;(<?php echo intval($usage / KB) . ' MB of ' . intval($total / KB) . ' MB'; ?>)</td>
              <td><?php if($show_upgrades === true){ echo '&nbsp;(<a href="/?action=account.add_space_form">add more space</a>)'; } ?></td>
            </tr>
          </table>
      <?php
        }
        else
        {
          echo '<a href="/?action=account.upgrade_form">Upgrade</a> to a premium account to upload fotos.';
        }
      ?>
    </td>
  </tr>
  <tr height="28">
    <td width="125" align="right" valign="middle" class="bold">Account Created:&nbsp;</td>
    <td width="420" align="left" valign="middle"><?php echo date(FF_FORMAT_DATE_LONG, $user_data['U_DATECREATED']); ?></td>
  </tr>
  <tr height="28">
    <td width="125" align="right" valign="middle" class="bold">Account Expires:&nbsp;</td>
    <td width="420" align="left" valign="middle"><?php echo date(FF_FORMAT_DATE_LONG, $user_data['U_DATEEXPIRES']); ?></td>
  </tr>
  <tr height="1"><td colspan="2"><div class="line_dark"></div></td></tr>
  <tr height="14">
    <td colspan="2"><img src="images/spacer.gif" width="1" height="14" /></td>
  </tr>
  <tr height="29">
    <td align="left" colspan="2" class="f_8 f_black bold bg_medium"><img src="images/icons/label_member.gif" width="13" height="24" border="0" hspace="5" vspace="2" align="absmiddle" />Personal Information <img src="images/icons/label_pencil.gif" width="16" height="22" border="0" align="absmiddle" style="padding-left:10px; padding-right:2px;" /><a href="/?action=account.profile_form" class="f_7 f_black">(edit)</a></td>
  </tr>
  <tr height="1"><td colspan="2"><div class="line_dark"></div></td></tr>
  <tr height="28">
    <td width="125" align="right" valign="middle" class="bold">Username:&nbsp;</td>
    <td width="420" align="left" valign="middle"><?php echo $user_data['U_USERNAME']; ?></td>
  </tr>
  <tr height="28">
    <td width="125" align="right" valign="middle" class="bold">Name:&nbsp;</td>
    <td width="420" align="left" valign="middle"><?php echo $user_data['U_NAMEFIRST'] . ' ' . $user_data['U_NAMELAST']; ?></td>
  </tr>
  <tr height="28">
    <td width="125" align="right" valign="middle" class="bold">Email Address:&nbsp;</td>
    <td width="420" align="left" valign="middle"><?php echo $user_data['U_EMAIL']; ?></td>
  </tr>
  <tr height="1"><td colspan="2"><div class="line_dark"></div></td></tr>
  <tr height="14">
    <td colspan="2"><img src="images/spacer.gif" width="1" height="14" /></td>
  </tr>
  <tr height="29">
    <td align="left" colspan="2" class="f_8 f_black bold bg_medium"><img src="images/orders.gif" width="13" height="24" border="0" hspace="5" vspace="2" align="absmiddle" />My Order History</td>
  </tr>
  <tr height="1"><td colspan="2"><div class="line_dark"></div></td></tr>
  <?php
    if(count($order_data) > 0)
    {
      echo '<tr height="28">
              <td colspan="2">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                  <colgroup colspan="6" valign="middle" />
                  <tr>
                    <td></td>
                    <td class="bold">Order ID</td>
                    <td class="bold">Number Of Items</td>
                    <td class="bold">Total Price</td>
                    <td class="bold">Date</td>
                  </tr>
                  <tr><td colspan="6"><div class="line_dark"></div></td></tr>';
      foreach($order_data as $k => $v)
      {
        $bg_css = $k % 2 == 0 ? 'bg_white' : 'bg_lite';
        echo '<tr height="28" onmouseover="this.className=\'bg_lite\';" onmouseout="this.className=\'bg_white\';">
                <td width="10"><img src="images/spacer.gif" width="10" height="28" /></td>
                <td><a href="/?action=account.order_details&order_id=' . $v['id'] . '" class="bold">' . $v['id'] . '</a></td>
                <td>' . $v['_count'] . ' items</td>
                <td>$' . number_format($v['_price'], 2) . '</td>
                <td>' . date(FF_FORMAT_DATE_LONG, $v['order_date']) . '</td>
              </tr>';
      }
      echo '      </table>
                </td>
              </tr>';
    }
    else
    {
      echo '<tr><td colspan="2" align="center">You do not have any orders.</td></tr>';
    }
  ?>
</table>

<?php
  include_once PATH_DOCROOT . '/ads_horizontal.dsp.php';
  
  $tpl->main($tpl->get());
  $tpl->clean();
?>
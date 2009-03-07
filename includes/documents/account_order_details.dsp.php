<?php
  $_SESSION_HASH = $_FF_SESSION->value('sess_hash');
  
  $user =& CUser::getInstance();
  $ecom =& new CEcom($_USER_ID, $_SESSION_HASH);
  
  $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : false;
  
  $order_data = $ecom->getOrderDetails($order_id, $_USER_ID);
  
  if(isset($_GET['message']))
  {
    switch($_GET['message'])
    {
      case 'account_created':
        $message_text = 'Welcome to FotoFlix!  Go to your <a href="/?action=fotobox.fotobox_main">FotoBox</a> or begin <a href="/?action=fotobox.upload_form">uploading</a> your fotos.';
        break;
      case 'confirm':
        $message_text = 'Your order was submitted successfully.  Please print this page for your records.<br />An email has been sent to your email address.';
        break;
    }
    
    echo    '<table class="confirm" align="center">'
          . '<tr><td align="center">'
          . $message_text
          . '</td></tr>'
          . '</table>';
  }
  
  if(isset($_GET['forward_to_group']))
  {
    echo 'To go directly to your newly joined FotoGroup...click <a href="/?action=fotogroup.group_home&group_id=' . $_GET['forward_to_group'] . '">here</a>.<br /><br />';
  }
?>
<table cellpadding="0" cellspacing="0" border="0" width="545">
  <tr height="28">
    <td align="left" class="f_10 bold">Order Receipt for Order #<?php echo $order_id; ?></td>
  </tr>
  <tr>
    <td><div class="line_dark"></div></td>
  </tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="545">
  <tr height="29">
    <td background="images/pixel_lt_grey.gif" align="left" class="bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Item Purchased</td>
    <td background="images/pixel_lt_grey.gif" align="center" class="bold">Quantity</td>
    <td background="images/pixel_lt_grey.gif" align="center" class="bold">Price</td>
  </tr>
  <tr height="1"><td colspan="3"><div class="line_dark"></div></td></tr>
  <?php
    $total = 0;
    foreach ($order_data as $key => $item)
    {
      if($item['quantity'] > 0)
      {
        $bg_css = $key % 2 == 0 ? 'bg_white' : 'bg_lite';
        $indent = '';
        if ($item['parent_id'] > 0)
        {
          $indent = '&nbsp;&nbsp;---------&nbsp;&nbsp;';
        }
  
        $name = !empty($item['details']['name']) ? '<b>"' . $item['details']['name'] . '" DVD</b>' : $item['name'];
  
        $key = intval($key);
        echo '
          <tr height="29" class="' . $bg_css . ' ">
            <td style="text-align:left;">' . $indent . $name . '</td>
            <td align="center">' . $item['quantity'] . '</td>
            <td align="center">$' . number_format($item['totalprice'], 2) . '</td>
          </tr>
        ';
        
        $total += $item['totalprice'];
      }
    }
  ?>
  <tr height="1"><td colspan="3"><div class="line_dark"></div></td></tr>
  <tr height="29">
    <td colspan="2" align="right" class="bold">Total:&nbsp;</td>
    <td align="left" class="bold">$<?php echo number_format($total, 2); ?></td>
  </tr>
</table>
<?php  
  $tpl->main($tpl->get());
  $tpl->clean();
?>
<?php
  $_SESSION_HASH = $_FF_SESSION->value('sess_hash');
  
  $ecom =& new CEcom($_USER_ID, $_SESSION_HASH);
  
  $items = $ecom->getCartItems();
  
  $cnt_items = count($items);
  
  $has_account = false;
  foreach($items as $v)
  {
    if(strncmp($v['ecg_type'], 'account', 7) == 0)
    {
      $has_account = true;
      break;
    }
  }
  
  if($has_account === false)
  {
    $redirect = '/?action=cart.checkout_form';
  }
  else
  {
    $redirect = '/?action=home.registration_form_d';
  }
  
  $fv = new CFormValidator;
  $fv -> setForm('_cart');
  $fv -> setDebugOutput(false);
  $fv -> setFunctionName('_val_cart');
?>
<table cellpadding="0" cellspacing="0" border="0" width="733" align="center">
  <tr height="28">
    <td colspan="4"></td>
  </tr>
  <tr>
    <td colspan="4"><div class="line_dark"></div></td>
  </tr>
  <form action="/?action=cart.update.act&redirect=<?php echo urlencode($redirect); ?>" name="_cart" method="POST">
    <tr height="28">
      <td background="images/pixel_lt_grey.gif" align="left" class="f_8 f_black bold" colspan="2"><img src="/images.spacer.gif" width="80" height="1" align="left" />Name</td>
      <td background="images/pixel_lt_grey.gif" align="center" class="f_8 f_black bold">Quantity</td>
      <td background="images/pixel_lt_grey.gif" align="center" class="f_8 f_black bold">Price</td>
    </tr>
    <tr>
      <td colspan="4"><div class="line_dark"></div></td>
    </tr>
    
    <?php
    if ($cnt_items > 0)
    {
      foreach ($items as $key => $item)
      {
        $indent = '';
        $image = '';
        $bg = $key % 2 == 0 ? 'class="bg_white"' : 'class="bg_lite"';
        // commented out because checkout calculates price wrong
        // $price = $item['price'] == 0 ? $item['price'] : $item['totalprice'];
        $price_display = $item['price'] == 0 ? $item['price'] : $item['totalprice'];
        $price = $item['price'];
        
        if($item['ecg_type'] == ECOM_TYPE_DVD)
        {
          $indent = ''; // THIS IS A DVD
          $image = '<img src="images/dvd2.gif" border="0" />';
        }
        else
        if($item['ecg_type'] == ECOM_TYPE_DVDCOPY && $prev_type == ECOM_TYPE_DVD)
        {
          $indent = '&nbsp;&nbsp;---------&nbsp;&nbsp;'; // THIS IS A COPY
          //$image = '<img src="images/dvd_copies.gif" border="0" />';
          $image = '<img src="images/spacer.gif" width="40" height="43" />';
        }
        else
        if($item['ecg_type'] == ECOM_TYPE_DVDCOPY)
        {
          $indent = ''; // THIS IS A DVD-COPY REORDER
          $image = '<img src="images/dvd2.gif" border="0" />';
        }
        else
        {
          $indent = '';
          $image = '<img src="images/spacer.gif" width="40" height="43" />';
        }
        
        $name = !empty($item['details']['name']) ? '<b>"' . $item['details']['name'] . '" DVD</b>' : $item['name'];
        //$price= $item['totalprice'] > 0 ? $item['totalprice'] : $item['price'];
        $key = intval($key);
        
        echo '
          <input type="hidden" name="ids[\'' . $key . '\']" value="' . $item['id'] . '" />
          <input type="hidden" name="prices[\'' . $key . '\']" value="' . $price . '" />          
          <tr height="29" ' . $bg .'>
            <td align="left" valign="middle">' . $image . '&nbsp;&nbsp;</td>
            <td class="f_9 f_dark_accent bold" align="left" valign="middle">'
              . $name . ($indent != '' ? '&nbsp;<br /><div class="item_desc">(' . $prev_name . ')</div>' : '') . '&nbsp;&nbsp;'
              . '<div class="item_desc">' . $item['description'] . '</div>
            </td>
            <td align="center" valign="middle">';
        // <input type="text" name="quantities[\'' . $key . '\']" value="' . $item['quantity'] . '" style="width:25px;" align="right" />
        if($item['max_quantity'] == 0)
        {
          echo $item['quantity'] . '<input type="hidden" name="quantities[\'' . $key . '\']" value="' . $item['quantity'] . '" />';
        }
        else 
        {
          echo '<select name="quantities[\'' . $key . '\'] style="formfield">';
          for($i = 0; $i <= $item['max_quantity']; $i++)
          {
            $selected = $item['quantity'] == $i ? ' SELECTED' : '';
            echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
          }
          echo '</select>';
        }
        echo '</td>
            <td align="center" valign="middle">$' . number_format($price_display, 2) . '</td>
          </tr>
          <tr>
            <td colspan="4"><div class="line_dark"></div></td>
          </tr>';
        
        $prev_name = $name;
        $prev_type = $item['ecg_type'];
      }
    }
    else
    {
      echo '<tr><td colspan="4"><br /><b>Your cart is currently empty.</b><br /><br /></td></tr>';
    }
    
    if ($cnt_items > 0)
    {
    ?>
    <tr>
      <td colspan="4">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr><td colspan="2">&nbsp;</td></tr>
          <tr>
            <td align="left" width="125"><a href="javascript:document.forms['_cart'].action='/?action=cart.update.act&task=remove'; document.forms['_cart'].submit();"><img src="images/buttons/empty.gif" width="111" height="23" border="0" /></a></td>
            <td align="right">
              <a href="javascript:document.forms['_cart'].action='/?action=cart.update.act'; document.forms['_cart'].submit();"><img src="images/buttons/update_cart.gif" width="111" height="23" border="0" /></a>
              <a href="javascript:document.forms['_cart'].submit();" /><img src="images/buttons/checkout.gif" width="111" height="23" border="0" /></a>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <?php
    }
    ?>
  </form>
</table>

<?php
  $fv -> validate();

  $tpl->main($tpl->get());
  $tpl->clean();
?>

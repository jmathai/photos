<?php
#last edit: 2004-June-28 3:25pm

class CEcom
{
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function addCartItems($catalog_ids, $quantities, $prices, $details=array())
  {
    $catalog_ids= (array)$catalog_ids;
    $quantities = (array)$quantities;
    $prices     = (array)$prices;
    
    if (is_array($catalog_ids))
    {
      foreach ($catalog_ids as $i => $catalog_id)
      {
        $details_insert = !empty($details[$i]) ? addslashes($details[$i]) : '';

        $sql = "
          INSERT INTO ecom_cart_details (ecd_ec_id, ecd_ecg_id, ecd_quantity, ecd_price, ecd_details)
          VALUES (
            " . $this->cart_id . ",
            " . (int)$catalog_ids[$i] . ",
            " . (int)$quantities[$i] . ",
            " . ($prices[$i] * (int)$quantities[$i]). ",
            '" . $details_insert . "'
          )
        ";

        $this->dbh->execute($sql);

        $detail_id = $this->dbh->insert_id();

        //check if this catalog item has children items
        $sql_children = "
          SELECT ecg_children
          FROM ecom_catalog
          WHERE ecg_id = " . (int)$catalog_ids[$i] . "
        ";
        $row_children = $this->dbh->query_first($sql_children);

        if ($row_children)
        {
          $children = explode(';', $row_children['ecg_children']);

          foreach ($children as $v)
          {
            if (strpos($v, ':') !== false)
            {
              $childinfo = explode(':', $v);

              $sql_child = "
                INSERT INTO ecom_cart_details (ecd_ec_id, parent_ecd_id, ecd_ecg_id, ecd_quantity, ecd_price, ecd_details)
                VALUES (
                  " . $this->cart_id . ",
                  '$detail_id',
                  " . (int)$childinfo[0] . ",
                  '0',
                  '0',
                  'dvd=$detail_id'
                )
              ";
              $this->dbh->query($sql_child);
            }
          }
        }
      }
    }
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function addRecurringPayment($data = false)
  {
    $retval = 0;
    include_once PATH_INCLUDE . '/functions.php'; // for encrypt function
    if(is_array($data))
    {
      $recurring_price = floatval($data['amount']);
      
      if($recurring_price > 0)
      {
        $cc_num_enc = encrypt($data['cc_num']);

        $dataIns = array( 'er_eo_id'  => $data['order_id'],
                          'er_ecg_id' => $data['catalog_id'],
                          'er_u_id'   => $data['user_id'],
                          'er_ccNameFirst'  => $data['first_name'],
                          'er_ccNameLast'   => $data['last_name'],
                          'er_ccCompany'    => $data['company'],
                          'er_ccNum'  => $cc_num_enc,
                          'er_ccExpMonth'   => $data['cc_month'],
                          'er_ccExpYear'    => $data['cc_year'],
                          'er_ccStreet'     => $data['address'],
                          'er_ccCity' => $data['city'],
                          'er_ccState'=> $data['state'],
                          'er_ccZip'  => $data['zip'],
                          'er_ccCcv'  => $data['cc_cvv'],
                          'er_initialDate'  => $data['initial_date'],
                          'er_period' => $data['period'],
                          'er_amount' => $data['amount']
                        );

        $dataIns = $this->dbh->asql_safe($dataIns);
        $sql  = 'INSERT INTO ecom_recur(' . implode(',', array_keys($dataIns)) .  ') '
              . 'VALUES(' . implode(',', $dataIns) . ')';
        
        $this->dbh->execute($sql);
        $retval = $this->dbh->insert_id();
      }
    }
    
    return $retval;
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function addRecurringResult($er_id = false, $result = false)
  {
    $retval = 0;
    
    if($er_id !== false && $result !== false)
    {
      //5940
      $er_id = $this->dbh->sql_safe($er_id);
      $result= $this->dbh->sql_safe($result);
      $sql = 'INSERT INTO ecom_recur_results(err_er_id, err_result, err_dateTime) '
           . "VALUES({$er_id}, {$result}, NOW())";
      
      $this->dbh->execute($sql);
      
      $retval = $this->dbh->insert_id();
    }
    
    return $retval;
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function addShipping($shipping = false)
  {
    if(is_array($shipping))
    {
      $shipping = $this->dbh->asql_safe($shipping);
      $keys = array_keys($shipping);
      $sql  = 'INSERT INTO ecom_order_shipping(' . implode(',', $keys) . ') '
            . 'VALUES(' . implode(',', $shipping) . ')';
      $this->dbh->execute($sql);
      return true;
    }
    else
    {
      return false;
    }
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function cc($cc_data)
  {
    include_once PATH_INCLUDE . '/functions.php'; // for encrypt function
    $cc_fname= $this->dbh->sql_safe($cc_data['first_name']);
    $cc_lname= $this->dbh->sql_safe($cc_data['last_name']);
    $cc_street = $this->dbh->sql_safe($cc_data['address']);
    $cc_state= $this->dbh->sql_safe($cc_data['state']);
    $cc_city = $this->dbh->sql_safe($cc_data['city']);
    $cc_zip  = $this->dbh->sql_safe($cc_data['zip']);
    $cc_type = $this->dbh->sql_safe($cc_data['cc_type']);
    $cc_month= $this->dbh->sql_safe(substr($cc_data['cc_exp'], 0, -4));
    $cc_year = $this->dbh->sql_safe(substr($cc_data['cc_exp'], -4));
    $cc_ccv  = $this->dbh->sql_safe($cc_data['cc_code']);

    $encrypted_data = encrypt($cc_data['cc_num']);

    $cc_num_enc = $this->dbh->sql_safe($encrypted_data);

    $sql  = 'UPDATE ecom_orders SET eo_cc_fname = ' . $cc_fname
          . ', eo_cc_lname = ' . $cc_lname
          . ', eo_cc_street = ' . $cc_street
          . ', eo_cc_state = ' . $cc_state
          . ', eo_cc_city = ' . $cc_city
          . ', eo_cc_zip = ' . $cc_zip
          . ', eo_cc_type = ' . $cc_type
          . ', eo_cc_month = ' . $cc_month
          . ', eo_cc_year = ' . $cc_year
          . ', eo_cc_ccv = ' . $cc_ccv
          . ', eo_cc_num = ' . $cc_num_enc
          . ' WHERE eo_id = ' . $this->cart_id;

    $this->dbh->execute($sql);
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function checkout($payment_info)
  {
    include_once PATH_CLASS . '/CEcomGateway.php';

    $gateway =& CEcomGateway::init(ECOMGATEWAY_AUTHORIZE);
    $status  =  $gateway->send($payment_info);

    return $status;
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function createCart()
  {
    if($this->cart_id == 0)
    {
      $sql = "
        INSERT INTO ecom_carts (ec_u_id, ec_us_hash, ec_dateCreated, ec_dateModified)
        VALUES (
          '" . $this->user_id . "',
          '" . $this->session_hash . "',
          '" . NOW . "',
          '" . NOW . "'
        )
      ";
      
      $this->dbh->execute($sql);

      $this->cart_id = (int)$this->dbh->insert_id();

      return true;
    }
    else
    {
      return false;
    }
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function finalize($userInfo = false)
  {
    include_once PATH_INCLUDE . '/functions.php'; // for encrypt function
    //move order details
    $sql_i1 = "
      INSERT INTO ecom_order_details
      SELECT ecd_id, parent_ecd_id , ecd_ec_id , ecd_ecg_id , ecd_quantity , ecd_price , ecd_details
      FROM ecom_cart_details
      WHERE ecd_ec_id = '" . $this->cart_id . "'
    ";
    $this->dbh->execute($sql_i1);
    
    $sql_d1 = "
      DELETE FROM ecom_cart_details WHERE ecd_ec_id = '" . $this->cart_id . "'
    ";
    $this->dbh->execute($sql_d1);

    //move order
    $sql_i2 = "
      INSERT INTO ecom_orders(eo_u_id, eo_dateCreated, eo_dateModified, eo_status)
      SELECT ec_u_id, ec_dateCreated, ec_dateModified, 'Pending'
      FROM ecom_carts
      WHERE ec_id = '" . $this->cart_id . "'
    ";
    $this->dbh->execute($sql_i2);
    $order_id = $this->dbh->insert_id();
    
    if(is_array($userInfo))
    {
      $sql_u2 = 'UPDATE ecom_orders SET ';
      foreach($userInfo as $k => $v)
      {
        if($k == 'eo_cc_num')
        {
          $v = encrypt($v);
        }
        $sql_u2 .= $k . ' = ' . $this->dbh->sql_safe($v) . ',';
      }
      $sql_u2 = substr($sql_u2, 0, -1) . ' ';
      
      $sql_u2 .= 'WHERE eo_id = ' . intval($order_id);
      
      $this->dbh->execute($sql_u2);
    }
    
    $sql_d2 = "
      DELETE FROM ecom_carts WHERE ec_id = '" . $this->cart_id . "'
    ";
    $this->dbh->execute($sql_d2);
    
    // \\retrieve dvd
    
    return $order_id;
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function getByType($type = false)
  {
    $return = array();

    if($type !== false)
    {
      $type   = $this->dbh->sql_safe($type);
      $sql  = 'SELECT ecg_id AS C_ID, ecg_name AS C_NAME, ecg_description AS C_DESC, ecg_edit_url AS C_EDITURL, ecg_price AS C_PRICE, ecg_shipping AS C_SHIPPING,  '
            . 'ecg_photos AS C_PHOTOS, ecg_availability AS C_AVAILABILITY '
            . 'FROM ecom_catalog '
            . 'WHERE ecg_type = ' . $type . ' AND ecg_active = 1';

      $return = $this->dbh->query_all($sql);
    }

    return $return;
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function getCart()
  {
    $sql = "
      SELECT ec_id
      FROM ecom_carts
      WHERE ec_u_id = '" . $this->user_id . "'
        AND ec_us_hash = '" . $this->session_hash . "'
    ";
    $row = $this->dbh->query_first($sql);

    if ($row)
    {
      $this->cart_id = (int)$row['ec_id'];
    }
    else
    {
      $this->createCart();
    }
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function getCartID()
  {
    return $this->cart_id;
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function getCartItems()
  {
    $sql = "
      SELECT ecd.ecd_id AS id, ecd.parent_ecd_id AS parent_id, ecd.ecd_quantity AS quantity, ecd.ecd_price AS totalprice, ecd.ecd_details AS details,
             ecg.ecg_id AS catalog_id, ecg.ecg_name AS name, ecg.ecg_description AS description, ecg.ecg_price AS price, ecg.ecg_max_quantity AS max_quantity, ecg.ecg_edit_url AS edit_url, ecg.ecg_shipping AS ecg_shipping, ecg.ecg_additional AS ecg_additional, ecg.ecg_type AS ecg_type, ecg.ecg_recurring
      FROM ecom_cart_details AS ecd
        INNER JOIN ecom_catalog AS ecg ON ecg.ecg_id = ecd.ecd_ecg_id
      WHERE ecd.ecd_ec_id = '" . $this->cart_id . "'
      ORDER BY ecd.ecd_id
    ";
    $items = $this->dbh->query_all($sql);

    foreach ($items as $k => $v)
    {
      $details_array = array();
      $tmp = (array)explode(';', $items[$k]['details']);
      foreach ($tmp as $k2 => $v2)
      {
        if (!empty($v2))
        {
          $tmp2 = (array)explode('=', $v2);
          if(count($tmp2) >= 2)
          {
            $details_array[$tmp2[0]] = $tmp2[1];
          }
        }
      }
      $items[$k]['details'] = $details_array;
      unset($details_array);
    }

    return $items;
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function getCartItemsCount()
  {
    $sql = 'SELECT COUNT(ecd_id) AS _COUNT FROM ecom_cart_details WHERE ecd_ec_id = ' . $this->dbh->sql_safe($this->cart_id);
    $data= $this->dbh->query_first($sql);
    $return = $data['_COUNT'];

    return $return;
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function getCatalogItem($id)
  {
    $sql = "
      SELECT ecg_id, ecg_name, ecg_type, ecg_description, ecg_price, ecg_priceSpecial, ecg_priceSpecialStart, ecg_priceSpecialEnd, ecg_additional, ecg_shipping
      FROM ecom_catalog
      WHERE ecg_id = '" . $id . "'
    ";
    $row = $this->dbh->query_first($sql);

    return $row;
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function getDvdOrders($user_id = false)
  {
    if($user_id != false)
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $sql  = 'SELECT eo.eo_id, eod.* '
            . 'FROM ((users AS u INNER JOIN ecom_orders AS eo ON u.u_id = eo.eo_u_id) '
            . 'INNER JOIN ecom_order_details AS eod ON eo.eo_id = eod.eod_eo_id) '
            . 'INNER JOIN ecom_catalog AS ecg ON eod.eod_ecg_id = ecg.ecg_id '
            . 'WHERE u.u_id = ' . $user_id . " AND ecg.ecg_type = 'dvd'";

      $ar   = $this->dbh->query_all($sql);

      $return = array();
      foreach($ar as $k => $v)
      {
        $order_id = $v['eo_id'];
        $details  = $v['eod_details'];
        $parts    = explode(';', $details);
        $return[$k]['eo_id'] = $order_id;
        foreach($parts as $v2)
        {
          $parts_inner = explode('=', $v2);
          $key    = $parts_inner[0];
          $value  = $parts_inner[1];
          $return[$k][$key] = $value;
        }
      }

      return $return;
    }
    else
    {
      return false;
    }
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function getGroup($id = false)
  {
    $return = array();

    if($id !== false)
    {
      $id   = $this->dbh->sql_safe($id);
      $sql  = 'SELECT ecg_id AS C_ID, ecg_name AS C_NAME, ecg_description AS C_DESC, ecg_edit_url AS C_EDITURL, ecg_price AS C_PRICE, ecg_shipping AS C_SHIPPING,  '
            . 'ecg_photos AS C_PHOTOS, ecg_availability AS C_AVAILABILITY '
            . 'FROM ecom_catalog '
            . 'WHERE ecg_group = ' . $id . ' AND ecg_active = 1';

      $return = $this->dbh->query_all($sql);
    }

    return $return;
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function getOrders($user_id = false)
  {
    if($user_id !== false)
    {
      $user_id = $this->dbh->sql_safe($user_id);
      $sql = "
        SELECT eo.eo_id AS id, eod.parent_eod_id AS parent_id, eod.eod_quantity AS quantity, eod.eod_price AS totalprice, eod.eod_details AS details, eo.eo_dateCreated AS order_date,
               ecg.ecg_id AS catalog_id, ecg.ecg_name AS name, ecg.ecg_description AS description, ecg.ecg_price AS price, ecg.ecg_max_quantity AS max_quantity, ecg.ecg_edit_url AS edit_url, ecg.ecg_shipping AS ecg_shipping, COUNT(eod.eod_id) AS _count, SUM(eod.eod_price) AS _price, ecg.ecg_type
        FROM (ecom_order_details AS eod
          INNER JOIN ecom_orders AS eo ON eod.eod_eo_id = eo.eo_id)
          INNER JOIN ecom_catalog AS ecg ON ecg.ecg_id = eod.eod_ecg_id
        WHERE eo.eo_u_id = {$user_id}
        GROUP BY eo.eo_id
        ORDER BY eod.eod_id
      ";

      $items = $this->dbh->query_all($sql);

      foreach ($items as $k => $v)
      {
        $details_array = array();
        $tmp = (array)explode(';', $items[$k]['details']);
        foreach ($tmp as $k2 => $v2)
        {
          if (!empty($v2))
          {
            $tmp2 = (array)explode('=', $v2);
            if(count($tmp2) >= 2)
            {
              $details_array[$tmp2[0]] = $tmp2[1];
            }
          }
        }
        $items[$k]['details'] = $details_array;
        unset($details_array);
      }

      return $items;
    }
    else
    {
      return false;
    }
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function getOrderDetails($order_id = false, $user_id = false)
  {
    if($order_id !== false && $user_id !== false)
    {
      $order_id = $this->dbh->sql_safe($order_id);
      $user_id = $this->dbh->sql_safe($user_id);
      $sql = "
        SELECT eod.eod_id AS id, eod.parent_eod_id AS parent_id, eod.eod_quantity AS quantity, eod.eod_price AS totalprice, eod.eod_details AS details, eo.eo_dateCreated AS order_date,
               ecg.ecg_id AS catalog_id, ecg.ecg_name AS name, ecg.ecg_description AS description, ecg.ecg_price AS price, ecg.ecg_max_quantity AS max_quantity, ecg.ecg_edit_url AS edit_url, ecg.ecg_shipping AS ecg_shipping
        FROM (ecom_order_details AS eod
          INNER JOIN ecom_orders AS eo ON eod.eod_eo_id = eo.eo_id)
          INNER JOIN ecom_catalog AS ecg ON ecg.ecg_id = eod.eod_ecg_id
        WHERE eo.eo_id = {$order_id} AND eo.eo_u_id = {$user_id}
        ORDER BY eod.eod_id
      ";
      $items = $this->dbh->query_all($sql);

      foreach ($items as $k => $v)
      {
        $details_array = array();
        $tmp = (array)explode(';', $items[$k]['details']);
        foreach ($tmp as $k2 => $v2)
        {
          if (!empty($v2))
          {
            $tmp2 = (array)explode('=', $v2);
            if(count($tmp2) >= 2)
            {
              $details_array[$tmp2[0]] = $tmp2[1];
            }
          }
        }
        $items[$k]['details'] = $details_array;
        unset($details_array);
      }

      return $items;
    }
    else
    {
      return false;
    }
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function getPromo($code = false, $time = NOW)
  {
    $code_safe = $this->dbh->sql_safe($code);
    $time_format  = $this->dbh->sql_safe(date('Y-m-d h:i:s', $time));
    $sql  = 'SELECT ep.ep_id AS P_ID, ecg.ecg_id AS C_ID, ecg.ecg_name AS C_NAME, ecg.ecg_type AS C_TYPE, ecg.ecg_description AS C_DESC, ecg.ecg_price AS C_PRICE '
          . 'FROM ecom_catalog AS ecg INNER JOIN ecom_promotions AS ep ON ecg.ecg_id = ep.ep_ecg_id '
          . 'WHERE ep.ep_code = ' . $code_safe . " AND ep.ep_timeStart < {$time_format} AND ep_timeEnd > {$time_format} AND ep.ep_active = 'Y'";

    return $this->dbh->query_first($sql);
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function getRecurringPayment($payment_id = false, $user_id = false, $status = false)
  {
    $return = false;

    if($payment_id !== false)
    {
      $payment_id = $this->dbh->sql_safe($payment_id);
      $user_id_safe = $this->dbh->sql_safe($user_id);

      $sql  = 'SELECT er_id AS R_ID, er_ecg_id AS R_ECG_ID, er_u_id AS R_U_ID, er_ccNameFirst AS R_CC_NAMEFIRST, er_ccNameLast AS R_CC_NAMELAST, er_ccStreet AS R_CC_STREET, er_ccCity AS R_CC_CITY, er_ccState AS R_CC_STATE, er_ccZip AS R_CC_ZIP, er_ccNum AS R_CC_NUM, er_ccExpMonth as R_CC_MONTH, er_ccExpYear AS R_CC_YEAR, er_period AS R_PERIOD, er_amount AS R_AMOUNT, er_initialDate AS R_INITIALDATE '
            . 'FROM ecom_recur '
            . 'WHERE er_id = ' . $payment_id . ' ';
      if($user_id !== false)
      {
        $sql .= 'AND er_u_id = ' . $user_id_safe . ' ';
      }

      if($status !== false)
      {
        $status = $this->dbh->sql_safe($statu);
        $sql .=  " AND er_status = {$status}";
      }

      $return = $this->dbh->query_first($sql);
    }

    return $return;
  }
  
  function getRecurringAccount($user_id = false, $status = false)
  {
    $retval = array();
    
    if($user_id !== false)
    {
      $sql  = 'SELECT er_id AS R_ID, er_ecg_id AS R_ECG_ID, er_u_id AS R_U_ID, er_ccNameFirst AS R_CC_NAMEFIRST, er_ccNameLast AS R_CC_NAMELAST, er_ccStreet AS R_CC_STREET, er_ccCity AS R_CC_CITY, er_ccState AS R_CC_STATE, er_ccZip AS R_CC_ZIP, er_ccNum AS R_CC_NUM, er_ccExpMonth as R_CC_MONTH, er_ccExpYear AS R_CC_YEAR, er_period AS R_PERIOD, er_amount AS R_AMOUNT, er_initialDate AS R_INITIALDATE '
            . 'FROM ecom_recur '
            . 'WHERE er_u_id = ' . intval($user_id) . ' AND er_ecg_id BETWEEN ' . intval(CATALOG_ACCOUNT_START) . ' AND ' . intval(CATALOG_ACCOUNT_END) . ' ';
      if($status !== false)
      {
        $sql .= 'AND er_status = ' . $this->dbh->sql_safe($status) . ' ';
      }
      
      $retval = $this->dbh->query_first($sql);
    }
    
    return $retval;
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function getTotalPrice()
  {
    $sql = "
      SELECT SUM(ecd.ecd_price) AS total_price
      FROM ecom_cart_details AS ecd
      WHERE ecd.ecd_ec_id = '" . $this->cart_id . "'
    ";
    $row = $this->dbh->query_first($sql);

    return floatval($row['total_price']);
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function migrate($cart_id = false, $user_id = false)
  {
    if($cart_id !== false && $user_id !== false)
    {
      $cart_id = $this->dbh->sql_safe($cart_id);
      $user_id = $this->dbh->sql_safe($user_id);

      $sql = 'UPDATE ecom_carts SET ec_u_id = ' . $user_id . ' WHERE ec_id = ' . $cart_id;
      $this->dbh->execute($sql);

      return true;
    }
    else
    {
      return false;
    }
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function removeCart()
  {
    if ($this->cart_id)
    {
      $sql = '
        DELETE FROM ecom_cart_details
        WHERE ecd_ec_id = ' . $this->cart_id;
      $this->dbh->query($sql);

      $sql = '
        DELETE FROM ecom_carts
        WHERE ec_id = ' . $this->cart_id;
      $this->dbh->query($sql);

      $this->cart_id = 0;

      return $this->dbh->affected_rows() ? true : false;
    }
    else
    {
      return false;
    }
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function removeCartItems($item_ids)
  {
    if (is_array($item_ids))
    {
      foreach ($item_ids as $item_id)
      {
        $sql = "
          DELETE FROM ecom_cart_details
          WHERE parent_ecd_id = '$item_id'
        ";
        $this->dbh->query($sql);

        $sql = "
          DELETE FROM ecom_cart_details
          WHERE ecd_id = '$item_id'
          AND parent_ecd_id = 0
        ";
        $this->dbh->query($sql);
      }
    }
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function setCartModificationTime()
  {
    $sql = "
      UPDATE ecom_carts SET
        ec_dateModified = '" . NOW . "'
      WHERE ec_id = '" . $this->cart_id . "'
    ";
    $this->dbh->query($sql);

    return $this->dbh->affected_rows() ? true : false;
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function updateCartItems($item_ids, $quantities, $prices, $details=array())
  {
    $details_update = '';
    $continue = false;

    if (is_array($item_ids))
    {
      foreach ($item_ids as $i => $item_id)
      {
        if ($quantities[$i] == 0)
        {
          $sql_item = "
            SELECT parent_ecd_id
            FROM ecom_cart_details
            WHERE ecd_id = '$item_id'
          ";
          $row_item = $this->dbh->query_first($sql_item);

          if ($row_item['parent_ecd_id'] == 0)
          {
            $this->removeCartItems(array($item_id));
          }
          else
          {
            $continue = true;
          }
        }
        else
        {
          $continue = true;
        }

        if ($continue)
        {
          if (!empty($details[$i]))
          {
            $details_update = ", ecd_details = '" . addslashes($details[$i]) . "'";
          }
          $sql = "
            SELECT ecg.ecg_max_quantity
            FROM ecom_catalog AS ecg
              INNER JOIN ecom_cart_details AS ecd ON ecd.ecd_ecg_id = ecg.ecg_id
            WHERE ecd.ecd_id = '" . (int)$item_ids[$i] . "'
          ";
          $row_item = $this->dbh->query_first($sql);

          $quantity = $quantities[$i] > $row_item['ecg_max_quantity'] && $row_item['ecg_max_quantity'] != 0 ? $row_item['ecg_max_quantity'] : $quantities[$i];

          $sql = "
            UPDATE ecom_cart_details SET
              ecd_quantity = '" . $quantity . "',
              ecd_price = '" . ($prices[$i] * $quantity) . "'
              $details_update
            WHERE ecd_id = " . (int)$item_ids[$i] . "
          ";
          $this->dbh->query($sql);
        }
      }
    }
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/
  function updateRecurringPayment($data = false)
  {
    include_once PATH_INCLUDE . '/functions.php'; // include for encrypt
    if(is_array($data))
    {
      if(isset($data['er_ccNum']))
      {
        $data['er_ccNum'] = encrypt($data['er_ccNum']);
      }
      
      $data_safe = $this->dbh->asql_safe($data);

      $sql = 'UPDATE ecom_recur SET ';

      foreach($data_safe as $k => $v)
      {
        $sql .= $k . ' = ' . $v . ', ';
      }

      $sql = substr($sql, 0, -2) . ' WHERE er_id = ' . $data_safe['er_id'] . ' AND er_u_id = ' . $data_safe['er_u_id'];

      $this->dbh->execute($sql);
    }
  }
  
 /*******************************************************************************************
  * Description
  *
  * Output
  *   
  *******************************************************************************************/  
  function CEcom($user_id = 0, $session_hash = '')
  {
    $this->dbh          =&$GLOBALS['dbh'];
    $this->user_id      = $user_id;
    $this->session_hash = $session_hash;
    $this->cart_id      = 0;

    $this->getCart();
  }
}
?>
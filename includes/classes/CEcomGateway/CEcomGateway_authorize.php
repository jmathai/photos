<?php
class CEcomGateway_authorize
{
  function &init()
  {
  }
  
  function send($options = false)
  {
    if(FF_ECOM_COMMIT === true)
    {
      $this->_setOpts($options);
      include_once PATH_CLASS . '/CNetworking.php';
      $cn =& CNetworking::getInstance();
      $curlopts = array(
                    'CURLOPT_HEADER' => 0,
                    'CURLOPT_POST' => 1,
                    'CURLOPT_POSTFIELDS' => $this->send_opts, 
                    'CURLOPT_RETURNTRANSFER' => 1
                  );
      $result = $cn->curl_request('secure.authorize.net', 'https', '/gateway/transact.dll', $curlopts);
      $result_array = explode(',', $result);
    }
    else
    {
      $result_array = array(FF_ECOM_RESULT_DEFAULT);
    }
    
    switch($result_array[0])
    {
    	case 1:
        $return = ECOM_APPROVED;
        break;
      case 2:
        $return = ECOM_DECLINED;
        break;
      case 3:
      default;
        $return = ECOM_ERROR;
        break;
    }
    
    return $return;
  }
  
  function overrideOpts($key = false, $value = false)
  {
    if($key !== false && $value !== false)
    {
      $this->send_opts[$this->_opts_map[$key]] = $value;
    }
  }
  
  function _setOpts()
  {
    $cnt_args = func_num_args();
    $return = false;
    if($cnt_args == 1)
    {
      $data = func_get_arg(0);
      if(is_array($data))
      {
        foreach($data as $name => $value)
        {
          $this->send_opts[$this->_opts_map[$name]] = $value;
        }
      }
    }
    else
    if($cnt_args == 2)
    {
      $name   = func_get_arg(0);
      $value  = func_get_arg(1);
      $this->send_opts[$this->_opts_map[$name]] = $value;
    }
  }
  
  function CEcomGateway_authorize()
  {
    $this->send_opts      = array(
                              'x_version'     => '3.1',
                              'x_delim_data'  => 'true',
                              'x_login'       => ECOM_AUTHORIZENET_LOGIN,
                              'x_password'    => ECOM_AUTHORIZENET_PASSWORD,
                              'x_type'        => 'AUTH_CAPTURE',
                              //'x_test_request'=> ECOM_AUTHORIZENET_TEST,
                              'x_duplicate_window' => '5',
                              'x_method'      => 'CC',
                              'x_email_customer'  => 'FALSE'
                            );
    $this->_opts_map      = array(
                              'auth_type'  => 'x_type',
                              'order_num' => 'x_po_num',
                              'invoice_num' => 'x_invoice_num',
                              'trans_type'=> 'x_type',
                              'amount'    => 'x_amount',
                              'tax'       => 'x_tax',
                              'shipping'  => 'x_freight',
                              
                              'cc_num'    => 'x_card_num',
                              'cc_exp'    => 'x_exp_date',
                              'cc_code'   => 'x_card_code',
                              'cc_type'   => 'null',
                              'recurring' => 'x_recurring_billing',
                              
                              'first_name'=> 'x_first_name',
                              'last_name' => 'x_last_name',
                              'company'   => 'x_company',
                              'address'   => 'x_address',
                              'city'      => 'x_city',
                              'state'     => 'x_state',
                              'zip'       => 'x_zip',
                              'country'   => 'x_country',
                              'phone'     => 'x_phone',
                              'fax'       => 'x_fax',
                              'email'     => 'x_email',
                              
                              'ship_first_name' => 'x_ship_to_first_name',
                              'ship_last_name'  => 'x_ship_to_last_name',
                              'ship_company'    => 'x_ship_to_company',
                              'ship_address'    => 'x_ship_to_address',
                              'ship_city'       => 'x_ship_to_city',
                              'ship_state'      => 'x_ship_to_state',
                              'ship_zip'        => 'x_ship_to_zip',
                              'ship_country'    => 'x_ship_to_country',
                              
                              'customer_id'     => 'x_cust_id',
                              'customer_ip'     => 'x_cust_ip',
                              'description'     => 'x_description'
                            );
  }
}
?>
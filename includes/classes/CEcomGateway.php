<?php
define('ECOMGATEWAY_MERCHANTID', '');

define('ECOMGATEWAY_AUTHORIZE', 'authorize');
define('ECOMGATEWAY_PAYPAL',    'paypal');

define('ECOMGATEWAY_TRANSACTION_TYPE_PURCHASE' ,  1);
define('ECOMGATEWAY_TRANSACTION_TYPE_AUTHORIZE' , 2);
define('ECOMGATEWAY_TRANSACTION_TYPE_VOID' ,      3);
define('ECOMGATEWAY_TRANSACTION_TYPE_CREDIT' ,    4);

define('ECOMGATEWAY_CC_TYPE_MASTERCARD',  'mc');
define('ECOMGATEWAY_CC_TYPE_VISA',        'visa');
define('ECOMGATEWAY_CC_TYPE_AMEX',        'amex');
define('ECOMGATEWAY_CC_TYPE_DISCOVER',    'disc');

class CEcomGateway
{
  function &init($type=ECOMGATEWAY_AUTHORIZE)
  {
    $classdir  = dirname(__FILE__) . '/CEcomGateway';
    $classname = 'CEcomGateway_' . $type;

    include_once $classdir . '/' . $classname . '.php';

    if (!class_exists($classname))
    {
      return false; 
    }

    $obj =& new $classname;
    return $obj;
  }
}
?>
function getMD5(md5_request)
{
  var myAjax = new Ajax.Request(
    '/xml_result',
    {
      method: 'get', parameters: 'action=get_md5&md5_request=' + md5_request,
      onComplete: function(response){ getMD5Rsp(response) }
    }
  );
}

function getMD5Rsp(response)
{
  var data = response.responseText.parseJSON();
  $('md5_result').innerHTML = 'Result: ' + data['RESULT'];
}

function decryptCC(ccNum)
{
  var myAjax = new Ajax.Request(
    '/xml_result',
    {
      method: 'get', parameters: 'action=decrypt_cc&cc_num=' + ccNum,
      onComplete: function(response){ decryptCCRsp(response) }
    }
  );
}

function decryptCCRsp(response)
{
  var data = response.responseText.parseJSON();
  $('ccNum').innerHTML = data['RESULT'];
}

function disablePayment(er_id, er_u_id)
{
  var myAjax = new Ajax.Request(
    '/xml_result',
    {
      method: 'get', parameters: 'action=disable_payment&er_id=' + er_id + '&er_u_id=' + er_u_id,
      onComplete: function(response){ disablePaymentRsp(response) }
    }
  );
}

function disablePaymentRsp(response)
{
  //var data = response.responseText.parseJSON();
  $('er_status').innerHTML = 'Disabled';
}

function enablePayment(er_id, er_u_id)
{
  var myAjax = new Ajax.Request(
    '/xml_result',
    {
      method: 'get', parameters: 'action=enable_payment&er_id=' + er_id + '&er_u_id=' + er_u_id,
      onComplete: function(response){ enablePaymentRsp(response) }
    }
  );
}

function enablePaymentRsp(response)
{
  //var data = response.responseText.parseJSON();
  $('er_status').innerHTML = 'Active';
}

function disableAccount(u_id)
{
  var myAjax = new Ajax.Request(
    '/xml_result',
    {
      method: 'get', parameters: 'action=disable_account&u_id=' + u_id,
      onComplete: function(response){ disableAccountRsp(response) }
    }
  );
}

function disableAccountRsp(response)
{
  //var data = response.responseText.parseJSON();
  $('u_status').innerHTML = 'Disabled';
}

function enableAccount(u_id)
{
  var myAjax = new Ajax.Request(
    '/xml_result',
    {
      method: 'get', parameters: 'action=enable_account&u_id=' + u_id,
      onComplete: function(response){ enableAccountRsp(response) }
    }
  );
}

function enableAccountRsp(response)
{
  //var data = response.responseText.parseJSON();
  $('u_status').innerHTML = 'Active';
}
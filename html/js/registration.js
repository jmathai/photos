function checkUsername(_field)
{
  if(_field.value.length >= 4)
  {
    var myAjax = new Ajax.Request(
    '/xml_result', 
    {
      method: 'get', 
      parameters: 'action=check_username&username='+_field.value, 
      onComplete: function(response){ checkUsernameRsp(response); }
    });
  }
}

function checkUsernameRsp(response)
{
  var data = response.responseText;
  if(data == 'failure')
  {
    var _string = '<span class="bold f_red">That username is already taken.</span>';
    
    $('u_username').value = '';
    $('_usernameSuggest').innerHTML = _string;
    $('_usernameSuggest').style.display = 'block';
  }
  else
  {
    $('_usernameSuggest').innerHTML = '';
    $('_usernameSuggest').style.display = 'none';
  }
}

function checkEmail(_field)
{
  if(_field.value.length >= 4)
  {
    var myAjax = new Ajax.Request(
    '/xml_result', 
    {
      method: 'get', 
      parameters: 'action=check_email&email='+_field.value, 
      onComplete: function(response){ checkEmailRsp(response); }
    });
  }
}

function checkEmailRsp(response)
{
  var _pieces = response.responseText;
  if(_pieces == 'failure')
  {
    var _string = '<span class="bold f_red">There is already an account with that email address</span>';
    
    $('u_email').value = '';
    $('_emailSuggest').innerHTML = _string;
    $('_emailSuggest').style.display = 'block';
  }
  else
  {
    $('_emailSuggest').innerHTML = '';
    $('_emailSuggest').style.display = 'none';
  }
}

function paymentVerify(_num, _mon, _year, _ccv, _fname, _lname, _addy, _city, _state, _zip, _amount, _id, _uId, _type, _token)
{
  new Insertion.After('updateButton', '<img src="/images/ajax_loader_snake.gif" id="updateLoader" width="16" height="16" hspace="3" vspace="3" border="0" />');
  var myAjax = new Ajax.Request(
  '/xml_result', 
  {
    method: 'post', 
    parameters: 'action=billing_update&1='+_num+'&2='+_mon+'&3='+_year+'&4='+_ccv+'&5='+_fname
                +'&6='+_lname+'&7='+_addy+'&8='+_city+'&9='+_state+'&10='+_zip+'&11='+_amount+'&12='+_id+'&13='+_uId+'&type='+_type+'&token='+_token, 
    onComplete:paymentVerifyRsp
  });
}

function paymentVerifyRsp(response)
{
  if(response.responseText == 'ok')
  {
    document.forms['updateBilling'].action += '&captured=1';
    document.forms['updateBilling'].submit();
  }
  else
  {
    $('updateLoader').remove();
    alert(response.responseText + 'Your credit card information could not be authorized.\nPlease verify that the information is correct.');
  }
}

function registrationPayment(data)
{
  $('registrationButton').style.display = 'none';
  $('registrationMessage').innerHTML = '<img src="images/ajax_loader_snake.gif" width="16" height="16" hspace="3" align="absmiddle" />Please wait...';
  /*var path = location.href;
  // strip out http:// and https://
  path = path.replace('https://', '');
  path = path.replace('http://', '');
  // use current protocol (http or https) - http for local development
  path = '//' + path.substring(0, path.indexOf('/'));*/
  
  var params = '';
  var alerts = '';
  for(i=0; i< data.length; i++)
  {
    if(i == parseInt(i))
    {
      params += '&' + data[i]['name'] + '=' + escape(data[i]['value']);
    }
  }
  
  var myAjax = new Ajax.Request(
     '/xml_result',
     {method: 'post', parameters: 'action=payment_auth' + params, onComplete:function(response){ registrationPaymentRsp(response); } }
  );
}

function registrationPaymentRsp(response)
{
  var data = response.responseText.parseJSON();
  switch(data)
  {
    case "APPROVED":
      document.forms['_registration'].submit();
      break;
    case "DECLINED":
      $('registrationButton').style.display = 'block';
      $('registrationMessage').innerHTML = '<div class="bold f_red"><img src="images/icons/stop_alt_2_16x16.png" class="png" width="16" height="16" hspace="3" align="absmiddle" />Your card was declined.</div>';
      
      new Element.scrollTo('registrationSubmit');
      new Effect.Highlight('registrationSubmit');
      break;
    case "ERROR":
      $('registrationButton').style.display = 'block';
      $('registrationMessage').innerHTML = '<div class="bold f_red"><img src="images/icons/warning_alt_2_16x16.png" class="png" width="16" height="16" hspace="3" align="absmiddle" />There was an error in the information you provided.</div>';
      
      new Element.scrollTo('registrationSubmit');
      new Effect.Highlight('registrationSubmit');
      break;
  }
}

function updatePayment(data)
{
  var params = '';
  params['ecom_amount'] = 0;
  for(i=0; i< data.length; i++)
  {
    if(i == parseInt(i))
    {
      params += '&' + data[i]['name'] + '=' + data[i]['value'];
    }
  }
  
  var myAjax = new Ajax.Request(
     '/xml_result',
     {method: 'post', parameters: 'action=update_payment_auth' + params, onComplete:function(response){ updatePaymentRsp(response); } }
  );
}

function updatePaymentRsp(response)
{
  var data = response.responseText.parseJSON();
  switch(data)
  {
    case "APPROVED":
      document.forms['updateBilling'].submit();
      break;
    case "DECLINED":
      alert('Sorry but your card was declined.');
      break;
    case "ERROR":
      alert('Sorry but there was an error in the information you provided.');
      break;
  }
}
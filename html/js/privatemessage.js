function pm_ban(who)
{
  getXmlHttp();
  xmlHttpSend('pm_banRsp()', '/xml_result', 'action=pm_ban&who=' + who);
}

function pm_banRsp()
{
  document.location.reload();
}

function pm_optIn()
{
  getXmlHttp();
  xmlHttpSend('pm_optInRsp()', '/xml_result', 'action=pm_optIn');
  
  return false;
}

function pm_optInRsp()
{
  document.location.reload();
}

function pm_optOut()
{
  getXmlHttp();
  xmlHttpSend('pm_optOutRsp()', '/xml_result', 'action=pm_optOut');
  
  return false;
}

function pm_optOutRsp()
{
  document.location.reload();
}

function pm_send(div_id, who, subject, message)
{
  var padding_top = arguments.length > 4 ? arguments[4] : 60;
  var padding_left = arguments.length > 5 ? arguments[5] : 30;
  
  getXmlHttp();
  xmlHttpSend('pm_sendRsp(' + padding_top + ', ' + padding_left + ')', '/xml_result', 'action=pm_send&div_id=' + div_id + '&who=' + who + '&subject=' + subject + '&message=' + message);
  
  return false;
}

function pm_sendRsp(padding_top, padding_left)
{
  var result = eval(xmlHttpResult);
  div_id = result[0];
  send = result[1];
  
  if( send == 1 )
  {
    _layer = $(div_id);
    
    _layer.innerHTML = '<div style="float:left; padding-top:' + padding_top + 'px; padding-left:' + padding_left + 'px;" class="f_8 bold">Message Sent</div>';
    
    _layer.style.display = 'inline';
    setTimeout("opacity(div_id, 100, 0, 500, true)", 1000);
  }
  else if( send == -1 )
  {
    _layer = $(div_id);
    
    html = '<div style="padding-left:10px;">';
    html += '<div style="text-align:right;"><span class="f_red">(<a href="javascript:opacity(\'' + div_id + '\', 100, 0, 500, true);" title="close this dialog" class="f_red">x</a>)</span></div>';
    html += '<div style="float:left; padding-top:25px;" class="f_8 bold">You have disabled the use of private messages.</div>';
    
    _layer.innerHTML = html;
  }
  else if( send == -2 )
  {
    _layer = $(div_id);
    
    html = '<div style="padding-left:10px;">';
    html += '<div style="text-align:right;"><span class="f_red">(<a href="javascript:opacity(\'' + div_id + '\', 100, 0, 500, true);" title="close this dialog" class="f_red">x</a>)</span></div>';
    html += '<div style="float:left; padding-top:25px;" class="f_8 bold">You are banned from sending private messages to this person.</div>';
    
    _layer.innerHTML = html;
  }
  
}

function pm_unBan(who)
{
  getXmlHttp();
  xmlHttpSend('pm_unBanRsp()', '/xml_result', 'action=pm_unBan&who=' + who);
}

function pm_unBanRsp()
{
  document.location.reload();
}
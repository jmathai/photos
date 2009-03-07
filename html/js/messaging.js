function deleteMessage(messageId)
{
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
    method: 'post', 
    parameters: 'action=message_delete&messageId='+messageId+'&mark=Read', 
    onComplete: deleteMessageRsp
    });
}

function deleteMessageRsp(response)
{
  var data = response.responseText.parseJSON();
  if(data > 0)
  {
    var eff = new fx.Height('messageRow_'+data, {onComplete:function(){ $('messageRow_'+data).remove() }});
    eff.toggle();
  }
}

function getMessage(messageId)
{
  if($('messageDetail_'+messageId) == undefined)
  {
    var myAjax = new Ajax.Request(
      '/xml_result', 
      {
      method: 'post', 
      parameters: 'action=message_get&messageId='+messageId+'&mark=Read', 
      onComplete: getMessageRsp
      });
  }
  else
  {
    var eff = new fx.Height('messageDetail_'+messageId, {onComplete:function(){ $('messageDetail_'+messageId).remove(); }}); 
    eff.toggle();
  }
}

function getMessageRsp(response)
{
  var data = response.responseText.parseJSON();
  var subject = data['UI_SUBJECT'].replace(/^Re\:\ /, '');
  subject = subject.replace('"', '');
  var message = data['UI_MESSAGE'].replace('<!-- reply:', '<div class="messageReply">');
  message = message.replace(':reply -->', '</div>');
  message = message.replace('[i]', '<span class="italic">');
  message = message.replace('[/i]', '</span>');
  var html = '<div id="messageDetail_'+data['UI_ID']+'">'
           + '<br/>'
           + message
           + '<br/>'
           + '<br /><div>-------------------------- reply --------------------------</div><br/>'
           + '<div id="replyFormDiv_'+data['UI_ID']+'">'
           + '<div class="bold">Subject</div>'
           + '<div><input type="text" id="messageSubject_'+data['UI_ID']+'" value="Re: '+subject+'" size="30" class="formfield" /></div>'
           + '<br/><div class="bold">Message</div>'
           + '<div><textarea rows="6" cols="40" id="messageBody_'+data['UI_ID']+'" class="formfield" id="messageReplyBox_'+data['UI_ID']+'"></textarea></div>'
           + '<div><a href="javascript:void(0);" onclick="replyToMessage('+data['UI_ID']+','+data['UI_SENDERID']+',$F(\'messageSubject_'+data['UI_ID']+'\'),$F(\'messageBody_'+data['UI_ID']+'\'));" class="plain bold"><img src="/images/icons/reply_16x16.png" class="png" width="16" height="16" vspace="3" border="0" align="absmiddle" />&nbsp;Reply to this</a></div>'
           + '</div>'
           + '<br/></div>';
  new Insertion.After('message_'+data['UI_ID'], html);
  var eff = new fx.Height('messageDetail_'+data['UI_ID'], {onComplete:function(){ $('messageRow_'+data['UI_ID']).setStyle({backgroundColor:$('messageRow_'+data['UI_ID']).readAttribute('rel')}); } });
  eff.hide();
  eff.toggle();
}

function messageForm(toUser, el)
{
  var rand = parseInt(Math.random()*1000);
  var divId= 'friendMessage_'+rand;
  var html = '<div id="'+divId+'" style="position:absolute; padding:5px; margin:5px; background-color:#eee;" class="border_medium">'
           + '  <div class="bold">Send a message to ' + toUser + ' &nbsp;&nbsp;(<a href="javascript:void(0);" onclick="var eff = new fx.Opacity(\''+divId+'\', {onComplete:function(){ $(\''+divId+'\').remove(); } }); eff.toggle();">close</a>)</div>'
           + '  <br/>'
           + '  <div class="bold">Subject</div>'
           + '  <div><input type="text" id="messageSubject_'+rand+'" value="" size="30" class="formfield" /></div>'
           + '  <br/>'
           + '  <div class="bold">Message</div>'
           + '  <div><textarea rows="6" cols="40" id="messageBody_'+rand+'" class="formfield" id="messageReplyBox_'+rand+'"></textarea></div>'
           + '  <div><a href="javascript:void(0);" onclick="sendMessage(\''+divId+'\', \''+toUser+'\', $F(\'messageSubject_'+rand+'\'),$F(\'messageBody_'+rand+'\'));" class="plain bold"><img src="/images/icons/reply_16x16.png" class="png" width="16" height="16" vspace="3" border="0" align="absmiddle" />&nbsp;Send a message</a></div>'
           + '</div>';
  new Insertion.After(el, html);
  var effRequest = new fx.Opacity(divId);
  effRequest.hide();
  effRequest.toggle();
}


function replyToMessage(messageId, userId, subject, message)
{
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
    method: 'post', 
    parameters: 'action=message_reply&messageId='+messageId+'&userId='+userId+'&subject='+escape(subject)+'&message='+escape(message), 
    onComplete: replyToMessageRsp
    });
}

function replyToMessageRsp(response)
{
  var data = response.responseText.parseJSON();
  var html = '<img src="/images/icons/checkmark_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" vspace="10" />&nbsp;Your reply was sent.';
  if(data['id'] > 0)
  {
    $('replyFormDiv_'+data['replyTo']).update(html);
  }
}

function sendMessage(htmlId, toUser, subject, message)
{
  var myAjax = new Ajax.Request(
    '/xml_result', 
    {
    method: 'post', 
    parameters: 'action=message_send&htmlId='+htmlId+'&toUser='+toUser+'&subject='+escape(subject)+'&message='+escape(message), 
    onComplete: sendMessageRsp
    });
}

function sendMessageRsp(response)
{
  var data = response.responseText.parseJSON();
  var html = '<img src="/images/icons/checkmark_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" vspace="10" />&nbsp;Your message was sent. &nbsp;&nbsp;&nbsp;(<a href="javascript:void(0);" onclick="$(\''+data['htmlId']+'\').remove();">close</a>)';
  if(data['id'] > 0)
  {
    $(data['htmlId']).update(html);
  }
  else
  {
    alert(response.responseText);
  }
}
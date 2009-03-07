var Tips = Class.create();
Tips.prototype = {
  // properties
  displayTip: function(element)
  {
     var myAjax = new Ajax.Request(
        '/xml_result',
        {method: 'get', parameters: 'action=tips_get_tip'+this.tipKey, onComplete: this.displayTipRsp.bindAsEventListener(this, element)}
     );
  },
  
  displayTipRsp: function(response, element)
  {
    var data = response.responseText.parseJSON();
    var html = '';
    var next = '';
    if(data != false)
    {
      switch(this.style)
      {
        case 'quote':
          html  += '<table border="0" width="'+this.width+'" cellspacing="0" cellpadding="0"> <tr height="4"> <td width="4" background="/images/tip_top_left.png"></td> <td background="/images/tip_top.png"></td> <td width="4" background="/images/tip_top_right.png"></td> </tr>'
                + '<tr> <td width="4" background="/images/tip_left.png"></td> <td>'
                + ' <div class="tipTitle"><img src="/images/icons/information_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" />&nbsp;' + data.T_TITLE + '</div><div class="tipBody">' + data.T_BODY + '</div>'
                + '</td> <td width="4" background="/images/tip_right.png"></td> </tr>'
                + '<tr height="4"> <td width="4" background="/images/tip_bottom_left.png"></td> <td background="/images/tip_bottom.png"></td> <td width="4" background="/images/tip_bottom_right.png"></td> </tr> </table>';
          switch(this.pointer)
          {
            case 'right-left':
              html += '<div style="margin:-1px 0px 0px 50px;"><img src="/images/tip_pointer_right.png" width="83" height="39" border="0" /></div>';
              break;
            case 'left-left':
              html += '<div style="margin:-1px 0px 0px 50px;"><img src="/images/tip_pointer_left.png" width="83" height="39" border="0" /></div>';
              break;
          }
          break;
        case 'box-left':
          var width = this.width - 25;
          var left  = width + 24 + 14; // 8 for padding & border
          
          html += '<div class="border_medium" style="float:left; z-index:50; margin-left:24px; padding:6px; width:'+width+'px;">'
               + '<div class="tipTitle"><img src="/images/icons/information_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" />&nbsp;' + data.T_TITLE + '</div><div class="tipBody">' + data.T_BODY + '</div>'
               + '</div>'
               + '<div style="float:left; margin-left:-'+left+'px;"><img src="/images/tip_pointer_left_box.png" class="png" width="25" height="50" border="0" vspace="20" /></div>'
               + '<br clear="all" />';
          break;
        default:
          html = '<div class="tipTitle"><img src="/images/icons/information_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" />&nbsp;' + data.T_TITLE + '</div><div class="tipBody">' + data.T_BODY + '</div>';
          break;
      }
      
      element.update(html);
    }
  },
  
  next: function(element)
  {
     var myAjax = new Ajax.Request(
        '/xml_result',
        {method: 'get', parameters: 'action=tips_get_next'+this.tipKey, onComplete: this.displayTipRsp.bindAsEventListener(this, element)}
     );
  },
  
  reset: function(element)
  {
     var myAjax = new Ajax.Request(
        '/xml_result',
        {method: 'get', parameters: 'action=tips_reset'+this.tipKey, onComplete: this.displayTipRsp.bindAsEventListener(this, element)}
     );
  },
  
  setKey: function(key)
  {
    this.tipKey = '&key='+key;
  },
  
  setPointer: function(pointer)
  {
    this.pointer = pointer;
  },
  
  setStyle: function(style)
  {
    this.style = style;
  },
  
  setWidth: function(width)
  {
    this.width = parseInt(width);
  },
  
  initialize: function()
  {
    this.tipKey = '';
    this.width   = 300;
  }
}
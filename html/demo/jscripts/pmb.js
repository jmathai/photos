var PMB = function(){};

PMB.prototype = 
{
  identity: 'PMB',
  version: 1.2,
  
  // needs tweaked for url ending in .xxx
  host: 'http://'+location.href.substring(7).substring(0, location.href.substring(7).indexOf('/')),
  
  photo:
  {
    identity: 'photo',
    pmb: PMB,
    addTags: function(id, tags)
    {
      var url = pmb.host+'/api/json?action=photo.addTags&id='+id+'&tags='+tags;
      if(arguments.length > 2)
      {
        url += '&callback='+arguments[2];
      }
      
      pmb.load(url);
    },
    
    removeTags: function(id, tags)
    {
      var url = pmb.host+'/api/json?action=photo.removeTags&id='+id+'&tags='+tags;
      if(arguments.length > 2)
      {
        url += '&callback='+arguments[2];
      }
      
      pmb.load(url);
    },
    
    search: function(params)
    {
      var url = pmb.host+'/api/json?action=photo.search';
      for(i in params)
      {
        url += '&'+i+'='+params[i];
      }
      prompt('', url);
      pmb.load(url);
    }
  },
  
  slideshow:
  {
    identity: 'photo',
    pmb: PMB,
    editor: function(ids)
    {
      var src = pmb.host+'/swf/ff_editor.swf?server_name='+pmb.host+'&version=2.0e&timestamp=1150847591&ids='+ids+'&uid=1&ip=127.0.0.1';
      return pmb.embedSwf({SRC:src,WIDTH:740,HEIGHT:635,BGCOLOR:'#ffffff'});
    }
  },
  
  load: function()
  {
    if(arguments.length > 0)
    {
      var url = arguments[0] || null;
      var callback = arguments[1] || null;
      
      var head = document.getElementsByTagName('head').item(0);
      var scriptTag = document.getElementById('pmbLoadScript');
      if(scriptTag) head.removeChild(scriptTag);
      script = document.createElement('script');
      script.src = url + (callback != null ? '&callback='+callback : '');
      script.type = 'text/javascript';
      script.id = 'pmbLoadScript';
      head.appendChild(script);
    }
  },
  
  embedSwf: function(data)
  {
    return  '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="//download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="'+data.WIDTH+'" height="'+data.HEIGHT+'">'
          + '<param name="movie" value="'+data.SRC+'" />'
          + '<param name="menu" value="false" />'
          + '<param name="quality" value="high" />'
          + '<param name="bgcolor" value="'+data.BGCOLOR+'" />'
          + '<embed src="'+data.SRC+'" menu="false" quality="high" bgcolor="'+data.BGCOLOR+'" width="'+data.WIDTH+'" height="'+data.HEIGHT+'" type="application/x-shockwave-flash" pluginspage="//www.macromedia.com/go/getflashplayer"></embed>'
          + '</object>';
  },
  
  embedShow: function(data)
  {
    var size  = data.A_SIZE.split('x');
    var width = size[0];
    var height= size[1];
    var src   = '/swf/ff_editor.swf?server_name=http%3A%2F%2Fjaisen.photagious.com&version=2.0e&timestamp=1150815904&fastflix='+data.A_FASTFLIX+'&uid='+data.A_U_ID+'&ip=127.0.0.1';
    var bgcolor = data.A_BACKGROUND;
    
    return  '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="//download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="'+width+'" height="'+height+'">'
          + '<param name="movie" value="'+src+'" />'
          + '<param name="menu" value="false" />'
          + '<param name="quality" value="high" />'
          + '<param name="bgcolor" value="'+bgcolor+'" />'
          + '<embed src="'+src+'" menu="false" quality="high" bgcolor="'+bgcolor+'" width="'+width+'" height="'+height+'" type="application/x-shockwave-flash" pluginspage="//www.macromedia.com/go/getflashplayer"></embed>'
          + '</object>';
  }
};
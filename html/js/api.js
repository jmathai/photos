var PTG = function()
          {
            if(arguments.length == 1)
            {
              if(arguments[0].length == 32)
              {
                this.key = arguments[0];
              }
              else
              if(arguments[0].length == 64)
              {
                this.token = arguments[0];
              }
            }
          };

/**
 *  PTG is the JavaScript class to interface with the PTG API
 *  @constructor
 *  @param token The token for an authenticated session
 */
PTG.prototype = 
{
  /**
   * The identity property
   * @final
   * @type String
   */
  identity: 'PTG',
  /**
   * The version property
   * @final
   * @type Float
   */
  version: 1.5,
  /**
   * The token property which handles authentication
   * @type String
   */
  token: '',
  /**
   * The host property which fully qualifies all requests to the same domain as the JS library
   * @final
   * @type String
   */
  host: document.getElementById('__PTG') ? 'http://'+document.getElementById('__PTG').src.substring(7).substring(0, document.getElementById('__PTG').src.substring(7).indexOf('/')) : null,
  /**
   * The imagePath property which specifies the path to images relative to the web server
   * @final
   * @type String
   */
  imagePath: '/photos',
  
  /**
   * The html object which contains methods to generate and display content from the media server
   * @extends PTG
   * @type Object
   */
  html:
  {
    /**
     * The identity property
     * @final
     * @type String
     */
    identity: 'html',
    /**
     * The ptg property which can call methods statically
     * @type Object
     */
    ptg: PTG,
    
    /**
     * A method to generate the url for a custom sized image
     * @type Method
     * @param path The path of the image as returned by image.search or image.getImage
     * @param key The key of the image as returned by image.search or image.getImage
     * @param width The width of the image you would like to have returned
     * @param height The height of the image you would like to have returned
     * @return A fully qualified path to the image you requested
     * @see #search
     * @see #getImage
     */
    customImageSrc:function(path, key, width, height)
    {
      var dtmp = path.split('/');
        dtmp[1] = 'custom';
      var newsrc = dtmp.join('/');
      var filename = newsrc.substr(newsrc.lastIndexOf('/')+1);
      var dirname  = newsrc.substr(0, newsrc.lastIndexOf('/'));
      var filenamePrefix = filename.substr(0, filename.lastIndexOf('.'));
      var filenameSuffix = filename.substr(filename.lastIndexOf('.')+1);
      var filenameReturn = filenamePrefix + '_' + width + '_' + height + '.' + filenameSuffix;
      var retval = dirname + '/' + filenameReturn + '?' + key;
      
      return this.imageSrc(retval);
    },
    
    /**
     * A method to generate the html for a custom sized image
     * @type Method
     * @param path The source of the image as returned by image.search or image.getImage
     * @param key The key of the image as returned by image.search or image.getImage
     * @param width The width of the image you would like to have returned
     * @param height The height of the image you would like to have returned
     * @param params An optional paramter which is an object consisting of key value pairs 
     *                        to be placed in the html image tag (i.e. {'width':'100','height':'100'})
     * @return An image tag with a fully qualified path to the image you requested
     * @see #search
     * @see #getImage
     */
    customImageTag:function(path, key, width, height) // [, params]
    {
      var params = arguments.length > 4 ? arguments[4] : {};
      var src    = this.customImageSrc(path, key, width, height);
      return this.imageTag(src, params);
    },
    
    /**
     * A method to generate the html for a custom sized image while maintaining aspect ratio
     * @type Method
     * @param path The path of the image as returned by image.search or image.getImage
     * @param key The key of the image as returned by image.search or image.getImage
     * @param width The width of the image you would like to have returned
     * @param height The height of the image you would like to have returned
     * @return A fully qualified path to the image you requested
     * @see #search
     * @see #getImage
     */
    customImageLockSrc:function(path, key, oWidth, oHeight, dWidth, dHeight, rotation)
    {
      if(rotation == 90 || rotation == 270)
      {
        tmpWidth = oWidth;
        oWidth = oHeight;
        oHeight= tmpWidth;
      }
      
      var srcRatio = parseInt(oWidth) / parseInt(oHeight);
      var destRatio= dWidth / dHeight;
      
      if(destRatio > srcRatio) // height is maxed
      {
        factor = dHeight / oHeight;
        finalHeight = dHeight;
        finalWidth  = Math.ceil(oWidth * factor);
      }
      else
      if(destRatio < srcRatio) // width is maxed
      {
        factor = dWidth / oWidth;
        finalWidth  = dWidth;
        finalHeight = Math.ceil(oHeight * factor);
      }
      else
      {
        finalWidth  = dWidth;
        finalHeight = dHeight;
      }
      
      var dtmp = path.split('/');
        dtmp[1] = 'custom';
      var newsrc = dtmp.join('/');
      var filename = newsrc.substr(newsrc.lastIndexOf('/')+1);
      var dirname  = newsrc.substr(0, newsrc.lastIndexOf('/'));
      var filenamePrefix = filename.substr(0, filename.lastIndexOf('.'));
      var filenameSuffix = filename.substr(filename.lastIndexOf('.')+1);
      var filenameReturn = filenamePrefix + '_' + finalWidth + '_' + finalHeight + '.' + filenameSuffix;
      var retval = dirname + '/' + filenameReturn + '?' + key;
      
      return this.imageSrc(retval);
    },
    
    /**
     * A method to generate the html for a custom sized image while maintaining aspect ratio
     * @type Method
     * @param path The path of the image as returned by image.search or image.getImage
     * @param key The key of the image as returned by image.search or image.getImage
     * @param width The width of the image you would like to have returned
     * @param height The height of the image you would like to have returned
     * @param params An optional paramter which is an object consisting of key value pairs to be placed in the html image tag (i.e. {'width':'100','height':'100'}
     * @return An image tag with a fully qualified path to the image you requested
     * @see #search
     * @see #getImage
     */
    customImageLockTag:function(path, key, oWidth, oHeight, dWidth, dHeight, rotation) // [, params]
    {
      var params = arguments.length > 7 ? arguments[7] : {};
      var src    = this.customImageLockSrc(path, key, oWidth, oHeight, dWidth, dHeight, rotation);
      return this.imageTag(src, params);
    },
    
    /**
     * A method to take the path returned by image.search and image.getPhoto and fully qualify it
     * @type Method
     * @param path The path of the image as returned by image.search or image.getImage
     * @return A fully qualified url to the image
     * @see #search
     * @see #getImage
     */
    imageSrc:function(path)
    {
      return ptg.host + ptg.imagePath + path;
    },
    
    /**
     * A method to generate an image tag for an image and optionally makes it fully qualified if needed
     * @type Method
     * @param path The path of the image as returned by image.search or image.getImage
     * @return An html image tag with a fully qualified url to the image
     * @see #search
     * @see #getImage
     */
    imageTag:function(path) // [, params]
    {
      var imageHtml;
      var params = arguments.length > 1 ? arguments[1] : {};
      var regex = /^http\:\/\//;
      
      if(regex.test(path))
      {
        imageHtml = '<img src="' + path + '" ';
      }
      else
      {
        imageHtml = '<img src="' + this.imageSrc(path) + '" ';
      }
      
      for(i in params)
      {
        imageHtml += ' ' + i + '="' + params[i] + '" ';
      }
      
      imageHtml += ' />';
      
      return imageHtml;
    },
    
    /**
     * A method to generate html code to embed an swf
     * @type Method
     * @param src The path to the swf (relative or fully qualified).
     * @param width The width of the swf.
     * @param height The height of the swf.
     * @param bgcolor The background color of the swf.
     * @return A string with html code to embed an swf
     * @see #search
     * @see #getImage
     */
    swfCode: function(src, width, height, bgcolor)
    {
      return  '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="//download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="'+width+'" height="'+height+'">'
            + '<param name="movie" value="'+src+'" />'
            + '<param name="menu" value="false" />'
            + '<param name="quality" value="high" />'
            + '<param name="bgcolor" value="'+bgcolor+'" />'
            + '<param name="allowScriptAccess" value="always" />'
            + '<param name="swLiveConnect" value="true" />'
            + '<embed src="'+src+'" swLiveConnect="true" allowScriptAccess="always" menu="false" quality="high" bgcolor="'+bgcolor+'" width="'+width+'" height="'+height+'" type="application/x-shockwave-flash" pluginspage="//www.macromedia.com/go/getflashplayer"></embed>'
            + '</object>';
    }
  },
  
  /**
   * A method to make ajax calls
   * @type Method
   * @param url A relative url of the resource being accessed.
   * @param callback An optional string which represents the name of a function to be executed.  The function will be passed in whatever data the resource returns.
   */
  load: function(url)
  {
    if(arguments.length > 0)
    {
      var scriptId = 'ptgScriptId' + parseInt(Math.random()*100000);
      var callback = arguments[1] || null;
      
      if(url != undefined)
      {
        if(this.key.length == 32)
        {
          url += '&authenticationKey=' + this.key;
        }
        else
        if(this.token.length == 64)
        {
          url += '&authenticationToken=' + this.token;
        }
        
        url = this.host + url;
        var head = document.getElementsByTagName('head').item(0);
        var scriptTag = document.getElementById(scriptId);
        script = document.createElement('SCRIPT');
        script.src = url + (callback != null ? '&callback='+callback : '') + '&timestamp=' + parseInt(Math.random()*100000);
        script.type = 'text/javascript';
        script.id = scriptId;
        head.appendChild(script);
      }
    }
  },
  
  /**
   * An object which contains all properties and methods pertaining to images
   * @type Object
   */
  image:
  {
    /**
     * identity property
     * @final
     * @type string
     */
    identity: 'image',
    /**
     * The ptg property which can call methods statically
     * @type Object
     */
    ptg: PTG,
    
    /**
     * A method to add tags to an image
     * @type Method
     * @param imageId The imageId as returned by image.search or image.getImage
     * @param tags The tags which you would like to be added to this image with multiple tags separated by commas
     * @param callback An optional string which represents the name of a function to be executed.  The function will be passed in the image object of the image which was updated.
     * @see #search
     * @see #getImage
     */
    addTags: function(id, tags)
    {
      var url = '/api/json?action=image.addTags&id='+id+'&tags='+tags;
      if(arguments.length > 2)
      {
        url += '&callback='+arguments[2];
      }
      
      ptg.load(url);
    },
    
    /**
     * A method to retrieve an image
     * @type Method
     * @param identifier This parameter takes either the imageId or imageKey as returned by image.search or image.getImage
     * @param callback An optional string which represents the name of a function to be executed.  The function will be passed in the image object of the image which was updated.
     * @see #search
     * @see #getImage
     */
    getImage:function(identifier)
    {
      var url = '/api/json?action=image.getImage';
      if(identifier.length == 32)
      {
        url += '&key=' + identifier;
      }
      else
      if(parseInt(identifier) == identifier)
      {
        url += '&id=' + identifier;
      }
      
      if(arguments.length > 1)
      {
        url += '&callback='+arguments[1];
      }
      
      ptg.load(url);
    },
    
    /**
     * A method to remove tags from an image
     * @type Method
     * @param imageId The imageId as returned by image.search or image.getImage
     * @param tags The tags which you would like to be removed from this image with multiple tags separated by commas
     * @param callback An optional string which represents the name of a function to be executed.  The function will be passed in the image object of the image which was updated.
     * @see #search
     * @see #getImage
     */
    removeTags: function(id, tags)
    {
      var url = '/api/json?action=image.removeTags&id='+id+'&tags='+tags;
      if(arguments.length > 2)
      {
        url += '&callback='+arguments[2];
      }
      
      ptg.load(url);
    },
    
    /**
     * A method to retrieve an image
     * @type Method
     * @param identifier This parameter takes either the imageId or imageKey as returned by image.search or image.getImage
     * @param callback An optional string which represents the name of a function to be executed.  The function will be passed in the image object of the image which was updated.
     * @see #search
     * @see #getImage
     */
    search: function()
    {
      var url = '/api/json?action=image.search';
      if(arguments.length > 0)
      {
        params = arguments[0];
        for(i in params)
        {
          url += '&'+i+'='+params[i];
        }
      }
      
      if(arguments.length > 1)
      {
        url += '&callback='+arguments[1];
      }
      
      ptg.load(url);
    },
    
    transform:
    {
      /**
       * identity property
       * @final
       * @type string
       */
      identity: 'transorm',
      /**
       * The ptg property which can call methods statically
       * @type Object
       */
      ptg: PTG,
      crop: function(id, x1, y1, x2, y2)
      {
        var url = '/api/json?action=image.transform.crop&id='+id+'&x1='+x1+'&y1='+y1+'&x2='+x2+'&y2='+y2;
        if(arguments.length == 6)
        {
          url += '&callback='+arguments[5];
        }
        
        ptg.load(url);
      },
      
      greyscale: function(id)
      {
        var url = '/api/json?action=image.transform.greyscale&id='+id;
        if(arguments.length == 2)
        {
          url += '&callback='+arguments[1];
        }
        
        ptg.load(url);
      },
      
      restore: function(id)
      {
        var url = '/api/json?action=image.transform.restore&id='+id;
        if(arguments.length == 2)
        {
          url += '&callback='+arguments[1];
        }
        
        ptg.load(url);
      },
      
      rotate: function(id, degrees)
      {
        var url = '/api/json?action=image.transform.rotate&id='+id+'&degrees='+degrees;
        if(arguments.length == 3)
        {
          url += '&callback='+arguments[2];
        }
        
        ptg.load(url);
      },
      
      sepia: function(id)
      {
        var url = '/api/json?action=image.transform.sepia&id='+id;
        if(arguments.length == 2)
        {
          url += '&callback='+arguments[1];
        }
        
        ptg.load(url);
      },
      
      zoom: function(id, step)
      {
        var url = '/api/json?action=image.transform.zoom&id='+id+'&step='+step;
        if(arguments.length == 3)
        {
          url += '&callback='+arguments[2];
        }
        
        ptg.load(url);
      }
    }
  },
  
  result:
  {
    identity: 'result',
    ptg: PTG,
    index: 0,
    
    current:function(data)
    {
      if(data[this.index] != undefined)
      {
        return data[this.index];
      }
    },
    
    next:function(data)
    {
      var retval = false;
      if(data[this.index] != undefined)
      {
        retval = data[this.index];
        this.index++;
      }
      else
      {
        this.reset();
      }
      
      return retval;
    },
    
    reset:function()
    {
      this.index = 0;
    },
    
    totalRows:function(data)
    {
      if(data.misc.totalRows != undefined)
      {
        return data.misc.totalRows;
      }
      else
      {
        return 0;
      }
    }
  },
  
  slideshow:
  {
    /**
     * identity property
     * @final
     * @type string
     */
    identity: 'slideshow',
    /**
     * The ptg property which can call methods statically
     * @type Object
     */
    ptg: PTG,
    
    embed: function(params)
    {
      var src = ptg.host+'/swf/container/dynamic/container_865_570.swf?version3.0&timestamp='+parseInt(Math.random()*10000000)+'&server_name='+escape(ptg.host);
      var width   = params['width'] != undefined ? params['WIDTH'] : 865;
      var height  = params['height'] != undefined ? params['HEIGHT'] : 570;
      var bgcolor = params['bgcolor'] != undefined ? params['BGCOLOR'] : '#ffffff';
      
      if(params['tags'] != undefined)
      {
        src += '&tags='+params['tags'];
      }
      else
      if(params['key'] != undefined)
      {
        src += '&slideshowKey_str='+params['key'];
      }
      
      if(params['userKey'] != undefined)
      {
        src += '&userKey=' + params['userKey'];
      }
      
      
      return ptg.html.swfCode(src, width, height, bgcolor);
    },
    
    link: function(key)
    {
      return ptg.host + '/slideshow?' + key;
    },
    
    popup: function(key)
    {
      return ptg.host + '/popup/slideshow/' + key + '/';
    },
    
    search:function(params)
    {
      var url = '/api/json?action=slideshow.search';
      if(arguments.length > 0)
      {
        params = arguments[0];
        for(i in params)
        {
          url += '&'+i+'='+params[i];
        }
      }
      
      if(arguments.length > 1)
      {
        url += '&callback='+arguments[1];
      }
      
      ptg.load(url);
    }
  },
  
  upload:
  {
    /**
     * identity property
     * @final
     * @type string
     */
    identity: 'upload',
    ptg: PTG,
    sessionKey: null,
    userKey: null,
    
    initializeForm:function(formName, userKey)
    {
      var frm = document.forms[formName];
      var isAjax = arguments.length == 3 ? arguments[2] : false;
      var frameName = 'iframe_'+formName;
      if(isAjax == true)
      {
        iframe = document.getElementById('iframe_' + formName);
        iframe.setAttribute('width','1');
        iframe.setAttribute('height','1');
        iframe.setAttribute('frameBorder','0');
        iframe.setAttribute('name',frameName);
        iframe.setAttribute('id',frameName);
      }
      
      frm.target = frameName;
      frm.action = ptg.host+'/cgi-bin/upload_fotos.cgi?sessionid='+this.sessionKey+'&user_enc='+userKey;
      frm.enctype = 'multipart/form-data';
    },
    
    getHash:function(callback)
    {
      var url = '/api/json?action=photo.getHash&callback='+callback;
      ptg.load(url);
    },
    
    getUserKey:function(callback)
    {
      var url = '/api/json?action=upload.getKey&callback='+callback;
      ptg.load(url);
    },
    
    setUserKey:function(key)
    {
      this.userKey = key;
    },
    
    getSessionKey:function()
    {
      if(this.sessionKey == null)
      {
        this.setSessionKey();
      }
      
      return this.sessionKey;
    },
    
    setSessionKey:function()
    {
      this.sessionKey = parseInt(Math.random()*10000000);
    }
  },
  
  user:
  {
    /**
     * identity property
     * @final
     * @type string
     */
    identity: 'user',
    ptg: PTG,
    /**
     * A method to retrieve tags for a user
     * @type Method
     * @param id The id of the image
     * @param callback An optional string which represents the name of a function to be executed.  The function will be passed in the image object of the image which was updated.
     */
    getTags:function()
    {
      var url = '/api/json?action=user.getTags';
      
      if(arguments.length > 0)
      {
        var params = arguments[0];
        for(i in params)
        {
          if(typeof(params[i]) == 'string')
          {
            url += '&'+i+'='+params[i];
          }
        }
      }
      
      if(arguments.length > 1)
      {
        url += '&callback='+arguments[1];
      }
      
      ptg.load(url);
    },
    
    /**
     * A method to generate a tag cloud
     * @type Method
     * @param tags A tags object
     * @param sizes A five element array of font sizes
     */
    tagCloud: function(tags)
    {
      var sizes = arguments.length > 1 ? arguments[1] : [10,12,14,16,18];
      var step  = (tags[0]['maximum'] - tags[0]['minimum']) / 5;
      var lineHeight = sizes[4] + 3;
      var html  = fontSize = '';
      
      while(tag = ptg.result.next(tags))
      {
        if(tag.tagWeight < step)
        {
          fontSize = sizes[0];
        }
        else
        if(tag.tagWeight < (step * 2))
        {
          fontSize = sizes[1];
        }
        else
        if(tag.tagWeight < (step * 3))
        {
          fontSize = sizes[2];
        }
        else
        if(tag.tagWeight < (step * 4))
        {
          fontSize = sizes[3];
        }
        else
        {
          fontSize = sizes[4];
        }
        
        html += '<a href="'+ptg.host+'/users/'+tags[0]['username']+'/tags/'+tag.tag+'/" style="font-size:'+fontSize+'px; line-height:'+lineHeight+'px;">'+tag.tag+'</a>&nbsp; ';
      }
      
      return html;
    }
  }
}

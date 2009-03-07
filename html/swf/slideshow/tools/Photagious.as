/*
* Name: Photagiouis
* Description: Photagious ActionScript Class
* Usage: import tools.Photagious;
*        var ptg = new Photagious(this._url);
*/

import tools.JSON;

class tools.Photagious
{
  private var photoPath_str:String = '/photos', resource_str:String, resourceStatic_str:String, debug_bool:Boolean, debug_arr, debugMode_int;
  static var photoHash_str:String, permissions_obj;
  
  /*
  * Name: Photagious
  * Description: public method which represents the constructor
  * Input: String
  */
  public function Photagious(url)
  {
    if(url.indexOf('http://') >= 0) // server development
    {
      var tmp = url.split('/');
      /*resource_str = tmp[0]+'//'+tmp[2];
      resource_str = resource_str;*/
      
      var domain = tmp[2];
      switch(domain)
      {
       case 'photagious.com':
        case 'www.photagious.com':
          resource_str = 'http://www.photagious.com'; //'http://static.photagious.com';
          resourceStatic_str = 'http://static.photagious.com';
          break;
         case 'v3.fotoflix.com':
          resource_str = 'http://v3.fotoflix.com';
          resourceStatic_str = 'http://v3.fotoflix.com';
          break;
        default:
          resource_str = '';
          resourceStatic_str = '';
          break;
      }
      
      debug_bool = false;
      debugMode_int = 0;
    }
    else // local development
    {
      resource_str = 'http://www.photagious.com';
      resourceStatic_str = 'http://static.photagious.com';
      
      debug_bool = true;
      debugMode_int = 1; // make this 0 to not display output from server
      debug_arr = new Array();
    }
    
    setHash('');
  }
  
  /*
  * Name: checkPermission
  * Description: check the permissions between two binary values
  * Input: Object, Integer, Integer
  * Output: String
  */
  public function checkPermission(check:Number, against:Number):Boolean
  {
    return check & against == against ? true : false;
  }
  
  /*
  * Name: customImage
  * Description: public method to generate the url for a custom image size while zooming/cropping to generate width x height image
  * Input: Object, Integer, Integer
  * Output: String
  */
  public function customImage(image:Object, width:Number, height:Number):String
  {
    var src, dtmp, newsrc, dirname, filename, filenamePrefix, filenameSuffix, filenameReturn, retval;
    
    src  = photoPath_str + image.thumbnailPath_str;
    dtmp = src.split('/');
      dtmp[2] = 'custom';
    newsrc = dtmp.join('/');
    filename = newsrc.slice(newsrc.lastIndexOf('/')+1);
    dirname  = newsrc.slice(0, newsrc.lastIndexOf('/'));
    filenamePrefix = filename.slice(0, filename.lastIndexOf('.'));
    filenameSuffix = filename.slice(filename.lastIndexOf('.')+1);
    filenameReturn = filenamePrefix + '_' + width + '_' + height + '.' + filenameSuffix;
    retval = resourceStatic_str + dirname + '/' + filenameReturn + '?' + image.photoKey_str;
    
    addDebug("function", "customImage");
    addDebug("src", src);
    addDebug("newsrc", newsrc);
    addDebug("filename", filename);
    addDebug("retval", retval);
    separateDebug();
    showDebug();
    return retval;
  }
  
  /*
  * Name: customImageLock
  * Description: public method to generate the url for a custom image size while maintaining aspect ratio
  * Input: Object, Integer, Integer
  * Output: String
  */
  public function customImageLock(image:Object, width:Number, height:Number):String
  {
    var tmpWidth, src, factor, srcRatio, destRatio, baseWidth, baseHeight, finalHeight, finalWidth, dtmp, newsrc, dirname, filename, filenamePrefix, filenameSuffix, filenameReturn, retval;
    
    if(image.rotation_int == 90 || image.rotation_int == 270)
    {
      baseWidth = image.height_int;
      baseHeight= image.width_int;
    }
    else
    {
      baseWidth = image.width_int;
      baseHeight= image.height_int;
    }
    
    srcRatio = parseInt(baseWidth) / parseInt(baseHeight);
    destRatio= width / height;
    
    if(destRatio > srcRatio) // height is maxed
    {
      factor = height / baseHeight;
      finalHeight = height;
      finalWidth  = Math.ceil(baseWidth * factor);
    }
    else
    if(destRatio < srcRatio) // width is maxed
    {
      factor = width / baseWidth;
      finalWidth  = width;
      finalHeight = Math.ceil(baseHeight * factor);
    }
    else
    {
      finalWidth  = width;
      finalHeight = height;
    }
    
    src  = photoPath_str + image.thumbnailPath_str;
    dtmp = src.split('/');
      dtmp[2] = 'custom';
    newsrc = dtmp.join('/');
    filename = newsrc.slice(newsrc.lastIndexOf('/')+1);
    dirname  = newsrc.slice(0, newsrc.lastIndexOf('/'));
    filenamePrefix = filename.slice(0, filename.lastIndexOf('.'));
    filenameSuffix = filename.slice(filename.lastIndexOf('.')+1);
    filenameReturn = filenamePrefix + '_' + finalWidth + '_' + finalHeight + '.' + filenameSuffix;
    retval = resourceStatic_str + dirname + '/' + filenameReturn + '?' + image.photoKey_str;
    
    addDebug("function", "customImageLock");
    addDebug("rotation", image.rotation_int);
    addDebug("original widthxheight", image.width_int+"x"+image.height_int);
    addDebug("base widthxheight", baseWidth+"x"+baseHeight);
    addDebug("final widthxheight", finalWidth+"x"+finalHeight);
    addDebug("src", src);
    addDebug("newsrc", newsrc);
    addDebug("filename", filename);
    addDebug("retval", retval);
    separateDebug();
    showDebug();
    return retval;
  }
  
  /*
  * Name: getHash
  * Description: public method to get the hash for requesting custom photos
  * Input: String, Function
  * Callback: Function
  */
  public function getHash(execute:Function)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=getHash&timestamp='+date_obj.getMilliseconds();
    lv.load(requestUrl_str);
    lv.onLoad = function()
      {
        if(debugMode_int == 1)
        {
          serverDebug(requestUrl_str, unescape(this.json));
        }
        
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "getHash");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: getMusic
  * Description: public method to get music
  * Input: String, Function
  * Callback: Function
  */
  public function getMusic(genre:String, execute:Function)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=getMusic&GENRE='+escape(genre)+'&timestamp='+date_obj.getMilliseconds();
    lv.load(requestUrl_str);
    lv.onLoad = function()
      {
        if(debugMode_int == 1)
        {
          serverDebug(requestUrl_str, unescape(this.json));
        }
        
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "getMusic");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: getPermissions
  * Description: public method to retrieve permissions and set them to member variable permissions_obj
  */
  public function getPermissions()
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=getPermissions&timestamp='+date_obj.getMilliseconds();
    
    lv.load(requestUrl_str);
    lv.onLoad =  function()
      {
        if(debugMode_int == 1)
        {
          serverDebug(requestUrl_str, unescape(this.json));
        }
        
        permissions_obj = JSON.parse(unescape(this.json));
      }
      
    addDebug("function", "getPermissions");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: getResource
  * Description: public method to retrieve the resource which should be http://www.domain.com
  * Output: String
  */
  public function getResource():String
  {
    return resource_str;
  }
  
  /*
  * Name: getSlideshow
  * Description: public method to retrieve all slideshow information
  * Input: Number, Function
  * Output: Object
  */
  public function getSlideshow(slideshowKey:String, execute:Function)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=getSlideshow&SLIDESHOW_KEY='+slideshowKey+'&timestamp='+date_obj.getMilliseconds();
    
    lv.load(requestUrl_str);
    lv.onLoad =  function()
      {
        if(debugMode_int == 1)
        {
          serverDebug(requestUrl_str, unescape(this.json));
        }
        
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "getSlideshow");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: getThemes
  * Description: public method to retrieve all slideshow information
  * Input: Function
  * Output: Object
  */
  public function getThemes(execute:Function)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=getThemes&timestamp='+date_obj.getMilliseconds();
    
    lv.load(requestUrl_str);
    lv.onLoad =  function()
      {
        if(debugMode_int == 1)
        {
          serverDebug(requestUrl_str, unescape(this.json));
        }
        
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "getThemes");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: getUserByKey
  * Description: public method to retrieve a user by Key
  * Input: String, Function
  */
  function getUserByKey(userKey:String, execute:Function)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=getUserByKey&timestamp='+date_obj.getMilliseconds();
    
    lv.load(requestUrl_str);
    lv.onLoad =  function()
      {
        if(debugMode_int == 1)
        {
          serverDebug(requestUrl_str, unescape(this.json));
        }
        
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "getUserByKey");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: getUserPermission
  * Description: public method to retrieve user's permission level
  * Input: Function
  */
  function getUserPermission(execute:Function)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=getUserPermission&timestamp='+date_obj.getMilliseconds();
    
    lv.load(requestUrl_str);
    lv.onLoad =  function()
      {
        if(debugMode_int == 1)
        {
          serverDebug(requestUrl_str, unescape(this.json));
        }
        
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "getUserPermission");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: getVideo
  * Description: public method to retrieve a video by key
  * Input: String, Function
  */
  public function getVideo(key:String, execute:Function)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=getVideo&videoKey='+key+'&timestamp='+date_obj.getMilliseconds();
    
    lv.load(requestUrl_str);
    lv.onLoad = function()
      {
        trace(unescape(this));
        if(debugMode_int == 1)
        {
          serverDebug(requestUrl_str, unescape(this.json));
        }
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "getVideo");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: isAuthenticated
  * Description: public method to check if a user is logged in
  * Input: Function
  */
  public function isAuthenticated(execute:Function)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=isAuthenticated&timestamp='+date_obj.getMilliseconds();
    
    lv.load(requestUrl_str);
    lv.onLoad = function()
      {
        if(debugMode_int == 1)
        {
          serverDebug(requestUrl_str, unescape(this.json));
        }
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "isAuthenticated");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: loadToolbox
  * Description: public method to retrieve photos from user's toolbox
  * Input: Function
  * Callback: Function
  */
  public function loadToolbox(execute:Function)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=getToolbox&timestamp='+date_obj.getMilliseconds();
    
    lv.load(requestUrl_str);
    lv.onLoad =  function()
      {
        if(debugMode_int == 1)
        {
          serverDebug(requestUrl_str, unescape(this.json));
        }
        
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "loadToolbox");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: login
  * Description: log a user into photagious
  * Input: String, String, Function
  * Callback: Function
  * Notes: The callback function is passed an Integer if > 0 represents the userId else if == 0 represents a failure
  */
  public function login(username:String, password:String, execute:Function)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=login&USERNAME='+username+'&PASSWORD='+password+'&timestamp='+date_obj.getMilliseconds();
    lv.load(requestUrl_str);
    lv.onLoad =  function()
      {
        if(debugMode_int == 1)
        {
          serverDebug(requestUrl_str, unescape(this.json));
        }
        
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "login");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: logPhotoView
  * Description: Log a view for a photo
  * Input: String, Number
  */
  public function logPhotoView(key:String)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/', requestUrl_str = baseUrl_str+'?action=fotobox.foto_view.act&key='+key+'&timestamp='+date_obj.getMilliseconds();
    
    lv.load(requestUrl_str);
    
    addDebug("function", "logPhotoView");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: logSlideshowView
  * Description: Log a view for a photo
  * Input: String, Number
  */
  public function logSlideshowView(key:String)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/', requestUrl_str = baseUrl_str+'?action=flix.flix_view.act&key='+key+'&timestamp='+date_obj.getMilliseconds();
    
    lv.load(requestUrl_str);
    
    addDebug("function", "logSlideshowView");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: logSlideshowViewComplete
  * Description: Log a view for a photo
  * Input: String, Number
  */
  public function logSlideshowViewComplete(key:String)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/', requestUrl_str = baseUrl_str+'?action=flix.flix_view_complete.act&key='+key+'&timestamp='+date_obj.getMilliseconds();
    
    lv.load(requestUrl_str);
    
    addDebug("function", "logSlideshowViewComplete");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: saveSlideshow
  * Description: public method to save a slideshow
  * Input: Object, Object, Object, Function
  * Output: Boolean
  * Callback: Function
  * Notes: This function performs create and update actions based on wether or not slideshow.US_ID is defined
  *        If slideshow.US_ID is defined then all parameters are passed to updateSlideshow is called
  *        If slideshow.US_ID is undefined then all parameters are passed to createSlideshow is called
  *        The callback function is passed an Integer which if > 0 represents the slideshow ID else if == 0 represents a failure
  */
  public function saveSlideshow(slideshow:Object, settings:Object, elements:Object, execute:Function):Boolean
  {
    if(slideshow.NAME != undefined)
    {
      if(slideshow.US_KEY != undefined) // update
      {
        updateSlideshow(slideshow, settings, elements, execute);
      }
      else // create
      {
        createSlideshow(slideshow, settings, elements, execute);
      }
      
      return true;
    }
    else
    {
      return false;
    }
  }
  
  /*
  * Name: searchPhotos
  * Description: public method to search for photos
  * Input: String, Function
  * Callback: Function
  */
  public function searchPhotos(tags:String, execute:Function)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=searchPhotos&TAGS='+tags+'&timestamp='+date_obj.getMilliseconds();
    
    if(tags == '')
    {
      requestUrl_str += '&LIMIT=20';
    }
    
    lv.load(requestUrl_str);
    lv.onLoad =  function()
      {
        if(debugMode_int == 1)
        {
          serverDebug(requestUrl_str, unescape(this.json));
        }
        
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "searchPhotos");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: searchVideos
  * Description: public method to search a user's videos
  * Input: String, Function
  */
  public function searchVideos(tags:String, execute:Function)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=searchVideos&TAGS='+tags+'&timestamp='+date_obj.getMilliseconds();
    
    lv.load(requestUrl_str);
    lv.onLoad = function()
      {
        if(debugMode_int == 1)
        {
          serverDebug(requestUrl_str, unescape(this.json));
        }
        retval = JSON.parse(unescape(this.json));
        
        execute(retval);
      }
      
    addDebug("function", "searchVideos");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: shareSlideshow
  * Description: public method to share a slideshow (sends email(s))
  * Input: String, String, String, String, Function
  * Callback: Function
  */
  public function shareSlideshow(key:String, to:String, from:String, message:String, execute:Function)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=shareSlideshow&timestamp='+date_obj.getMilliseconds();
    
    lv.key  = key;
    lv.to   = to;
    lv.from = from;
    lv.message= message;
    lv.flash  = '1';
    
    lv.sendAndLoad(requestUrl_str, lv, 'POST');
    lv.onLoad = function()
      {
        if(debugMode_int == 1)
        {
          serverDebug(requestUrl_str, unescape(this.json));
        }
        
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "shareSlideshow");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  // *********************************************** PRIVATE METHODS   *********************************************** //
  
  /*
  * Name: addDebug
  * Description: private method to add debug information
  * Input: String
  */
  private function addDebug(name:String, description:String)
  {
    var tmp;
    if(debug_bool == true)
    {
      tmp = new Object;
      tmp.NAME = name;
      tmp.DESCRIPTION = description;
      debug_arr.push(tmp);
    }
  }
  
  /*
  * Name: createSlideshow
  * Description: private method to create a slideshow
  * Input: Object, Object, Object, Function
  * Callback: Function
  * Notes: This function is called through saveSlideshow if slideshow.US_ID is undefined
  *        The execute function behaves as a callback parameter and is passed the ID of the slideshow
  *        If the slideshow ID is > 0 then the slideshow was successfully created
  */
  private function createSlideshow(slideshow:Object, settings:Object, elements:Object, execute:Function)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=createSlideshow&timestamp='+date_obj.getMilliseconds();
    
    lv.main = JSON.stringify(slideshow);
    lv.settings = JSON.stringify(settings);
    lv.elements = JSON.stringify(elements);
    
    lv.sendAndLoad(requestUrl_str, lv, 'POST');
    lv.onLoad = function()
      {
        if(debugMode_int == 1)
        {
          serverDebug(requestUrl_str, unescape(this.json));
        }
        
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "createSlideshow");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: replace
  * Description: private method replace text in a string
  * Input: String, String, String
  */
  private function replace(arg_search:String, arg_replace:String, arg_subject:String):String
  {
    var position, endText, preText, newText; 
    if(arg_search.length==1) // if search term is single character then split on that character and join on replacement term
    {
      return arg_subject.split(arg_search).join(arg_replace);
    }
    
    position = arg_subject.indexOf(arg_search);
    
    if(position == -1) // no match
    {
      return arg_subject;
    }
    
    endText = arg_subject; 
    do 
    { 
      position = endText.indexOf(arg_search); 
      preText = endText.substring(0, position) 
      endText = endText.substring(position + arg_search.length) 
      newText += preText + arg_replace; 
    } while(endText.indexOf(arg_search) != -1)
    
    newText += endText; 
    
    return newText; 
  }
  
  /*
  * Name: separateDebug
  * Description: private method to add debug information
  * Input: String
  */
  private function separateDebug()
  {
    if(debug_bool == true)
    {
      debug_arr.push("--------");
    }
  }
  
  /*
  * Name: serverDebug
  * Description: private method to display debug information directly from the server
  * Input: String, String
  */
  private function serverDebug(url:String, output:String)
  {
    if(debugMode_int == 1)
    {
      trace("****************************************");
      trace("* Begin Server Debug Information From Photagious.as @ " + resource_str);
      trace("* Requested URL: " + url);
      trace("* Output from the server (below): ");
      trace("* " + replace(String.fromCharCode(10), String.fromCharCode(10)+"* ", output));
      trace("****************************************");
    }
  }
  
  /*
  * Name: showDebug
  * Description: private method to display debug information
  * Input: String
  */
  private function showDebug()
  {
    if(debug_bool == true)
    {
      var i;
      trace("****************************************");
      trace("* Begin Debug Information From Photagious.as");
      for(i=0; i<debug_arr.length; i++)
      {
        if(debug_arr[i].NAME != undefined)
        {
          trace("* " + debug_arr[i].NAME + ": " + debug_arr[i].DESCRIPTION);
        }
        else
        {
          trace("* " + debug_arr[i]);
        }
      }
      trace("* End Debug Information From Photagious.as");
      trace("****************************************");
      debug_arr = new Array();
    }
  }
  
  /*
  * Name: setHash
  * Description: private method to set the hash for requesting custom photos
  * Input: String
  */
  private function setHash(hash:String)
  {
    photoHash_str = hash;
  }
  
  /*
  * Name: updateSlideshow
  * Description: private method to update a slideshow
  * Input: Object, Object, Object, Function
  * Callback: Function
  * Notes: This function is called through saveSlideshow if slideshow.US_ID is defined
  *        The execute function behaves as a callback parameter and is passed the ID of the slideshow
  *        If the slideshow ID is > 0 then the update was successful
  */
  private function updateSlideshow(slideshow:Object, settings:Object, elements:Object, execute:Function)
  {
    var date_obj = new Date();
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=updateSlideshow&timestamp='+date_obj.getMilliseconds();
    
    lv.main = JSON.stringify(slideshow);
    lv.settings = JSON.stringify(settings);
    lv.elements = JSON.stringify(elements);
    
    lv.sendAndLoad(requestUrl_str, lv, 'POST');
    lv.onLoad = function()
      {
        trace(this);
        if(debugMode_int == 1)
        {
          serverDebug(requestUrl_str, unescape(this.json));
        }
        
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "updateSlideshow");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
}
/*
* Name: Photagiouis
* Description: Photagious ActionScript Class
* Usage: import tools.Photagious;
*        var ptg = new Photagious(this._url);
*/

import tools.JSON;

class tools.Photagious
{
  private var photoPath_str:String = '/fotos', resource_str:String, debug_bool:Boolean, debug_arr;
  static var photoHash_str:String;
  
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
      resource_str = tmp[0]+'//'+tmp[2];
      debug_bool = false;
    }
    else // local development
    {
      debug_bool = true;
      debug_arr = new Array();
      resource_str = 'http://v3.fotoflix.com';
      //resource_str = 'http://jaisen.photagious.com';
    }
    
    getHash(setHash);
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
    retval = resource_str + dirname + '/' + filenameReturn + '?' + photoHash_str + '-' + image.photoKey_str;
    
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
    var tmpWidth, src, factor, srcRatio, destRatio, finalHeight, finalWidth, dtmp, newsrc, dirname, filename, filenamePrefix, filenameSuffix, filenameReturn, retval;
    
    if(image.rotation_int == 90 || image.rotation_int == 270)
    {
      tmpWidth = image.width_int;
      image.width_int = image.height_int;
      image.height_int= tmpWidth;
    }
    
    srcRatio = parseInt(image.width_int) / parseInt(image.height_int);
    destRatio= width / height;
    
    if(destRatio > srcRatio) // height is maxed
    {
      factor = height / image.height_int;
      finalHeight = height;
      finalWidth  = Math.ceil(image.width_int * factor);
    }
    else
    if(destRatio < srcRatio) // width is maxed
    {
      factor = width / image.width_int;
      finalWidth  = width;
      finalHeight = Math.ceil(image.height_int * factor);
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
    retval = resource_str + dirname + '/' + filenameReturn + '?' + photoHash_str + '-' + image.photoKey_str;
    
    addDebug("function", "customImageLock");
    addDebug("rotation", image.rotation_int);
    addDebug("original widthxheight", image.width_int+"x"+image.height_int);
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
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=getHash';
    lv.load(requestUrl_str);
    lv.onLoad = function()
      {
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
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=getMusic&GENRE='+escape(genre);
    lv.load(requestUrl_str);
    lv.onLoad = function()
      {
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "getMusic");
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
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=getSlideshow&SLIDESHOW_KEY='+slideshowKey;
    
    lv.load(requestUrl_str);
    lv.onLoad =  function()
      {
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "getSlideshow");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
  /*
  * Name: isAuthenticated
  * Description: public method to check if a user is logged in
  * Input: Function
  * Output: Integer (0 = not logged in, >0 = userId of user that's logged in)
  */
  public function isAuthenticated(execute:Function)
  {
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=isAuthenticated';
    
    lv.load(requestUrl_str);
    lv.onLoad = function()
      {
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
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=getToolbox';
    
    lv.load(requestUrl_str);
    lv.onLoad =  function()
      {
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
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=login&USERNAME='+username+'&PASSWORD='+password;
    lv.load(requestUrl_str);
    lv.onLoad =  function()
      {
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "login");
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
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=searchPhotos&TAGS='+tags;
    
    if(tags == '')
    {
      requestUrl_str += '&LIMIT=20';
    }
    
    lv.load(requestUrl_str);
    lv.onLoad =  function()
      {
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "searchPhotos");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
  
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
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=createSlideshow';
    
    lv.main = JSON.stringify(slideshow);
    lv.settings = JSON.stringify(settings);
    lv.elements = JSON.stringify(elements);
    
    lv.sendAndLoad(requestUrl_str, lv, 'POST');
    lv.onLoad = function()
      {
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "createSlideshow");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
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
    var retval, lv = new LoadVars(), baseUrl_str = resource_str+'/api/flash/', requestUrl_str = baseUrl_str+'?action=updateSlideshow';
    
    lv.main = JSON.stringify(slideshow);
    lv.settings = JSON.stringify(settings);
    lv.elements = JSON.stringify(elements);
    trace(requestUrl_str);
    lv.sendAndLoad(requestUrl_str, lv, 'POST');
    lv.onLoad = function()
      {
        retval = JSON.parse(unescape(this.json));
        execute(retval);
      }
      
    addDebug("function", "updateSlideshow");
    addDebug("requestUrl_str", requestUrl_str);
    separateDebug();
    showDebug();
  }
}
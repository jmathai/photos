function xmlObjLoad(_url, _func)
{
  if(arguments.length > 0)
  {
    this.xmlDoc.load(_url);
    //alert('loaded file ' + _url);
  }

  if(arguments.length > 1)
  {
    if (document.implementation && document.implementation.createDocument)
    {
      this.xmlDoc.onload = eval(_func+'()');
      //alert('set function ' + _func);
    }
    else if (window.ActiveXObject)
    {
      this.xmlDoc.onreadystatechange = eval(_func+'()'); /*alert(this.xmlDoc.readyState);*/ // function () {
        //alert(this.xmlDoc.readyState);
        //if (this.xmlDoc.readyState == 4){ alert("hihhh"); }
      //};
    }
    else
    {
      alert('Your browser can\'t handle this script');
    }
  }
}

function xmlObjLoadRss2Array()
{
  alert(this.xmlDoc.getElementsByTagName('item').length);
}

function xmlObj()
{
  if(window.ActiveXObject)
  {
    this.xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
  }
  else
  {
    this.xmlDoc = document.implementation.createDocument("","",null);
  }
  
  this.xmlDoc.async = false;
  this.loadUrl = xmlObjLoad;
  this.rss2Array = xmlObjLoadRss2Array;
  //alert('created xmlDoc ' + this.xmlDoc);
}

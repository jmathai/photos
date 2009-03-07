// Aurigma Image Uploader Embedding Script 
// Version 2.0.1.0 July 1, 2006
// Copyright(c) Aurigma Inc. 2002-2006

function __Browser(){
	var a=navigator.userAgent.toLowerCase();
	this.isOpera=(a.indexOf("opera")!=-1);
	this.isKonq=(a.indexOf('konqueror')!=-1);
	this.isSafari=(a.indexOf('safari')!=-1)&&(a.indexOf('mac')!=-1);
	this.isKhtml=this.isSafari||this.isKonq;
	this.isIE=(a.indexOf("msie")!=-1)&&!this.isOpera;
	this.isWinIE=this.isIE&&(a.indexOf("win")!=-1);
	this.isCSS1Compat=(!this.isIE)||(document.compatMode&&document.compatMode=="CSS1Compat");
}

var __browser=new __Browser();
		
//Create set/get expando methods for ActiveX
function _createExpandoMethods(id){
	var o=document.getElementById(id);
	var props=new Array();
	for (propName in o){
		var c=propName.charAt(0);
		if (c==c.toUpperCase()){
			props.push(propName);
		}
	}
	for (i=0;i<props.length;i++){
		//Check whether property is indexed
		if (typeof(o[props[i]])=="unknown"){
			eval("o.set"+props[i]+"=function(i,v){this."+props[i]+"(i)=v;};");
			eval("o.get"+props[i]+"=function(i){return this."+props[i]+"(i);};");
		}
		else{				
			eval("o.set"+props[i]+"=function(v){this."+props[i]+"=v};");
			eval("o.get"+props[i]+"=function(){return this."+props[i]+"};");
		}
	}	
}

function ControlWriter(id,width,height){
	//Private
	this._params=new Array();
	this._events=new Array();    

	this._getObjectParamHtml=function(name,value){
		return "<param name=\""+name+"\" value=\""+value+"\" />";
	}

	this._getObjectParamsHtml=function(){
		var r="";
		var p=this._params;
		var i;
		for (i=0;i<p.length;i++){
			r+=this._getObjectParamHtml(p[i].name,p[i].value);
		}
		return r;
	}
	
	this._getObjectEventsHtml=function(){	
		var r="";
		var e=this._events;
		for (i=0;i<e.length;i++){
			r+=this._getObjectParamHtml(e[i].name+"Listener",e[i].listener);
		}
		return r;
	}

	this._getEmbedParamHtml=function(name,value){
		return " "+name+"=\""+value+"\"";	
	}

	this._getEmbedParamsHtml=function(){
		var r="";
		var p=this._params;
		var i;
		for (i=0;i<p.length;i++){
			r+=this._getEmbedParamHtml(p[i].name,p[i].value);
		}
		return r;
	}

	this._getEmbedEventsHtml=function(){	
		var r="";
		var e=this._events;
		for (i=0;i<e.length;i++){
			r+=this._getEmbedParamHtml(e[i].name+"Listener",e[i].listener);
		}
		return r;
	}

	//Public

	//Properties
	this.id=id;
	this.width=width;
	this.height=height;

	this.activeXControlEnabled=true;
	this.activeXControlVersion="";

	this.javaAppletEnabled=true;
	this.javaAppletCodeBase="./";
	this.javaAppletCached=true;
	this.javaAppletVersion="";

	this.fullPageLoadListenerName=null;
	
	//Installation instructions
	this.instructionsEnabled=true;
	this.instructionsCommon="Image Uploader ActiveX control is necessary to upload "+
		"your files quickly and easily. You will be able to select multiple images "+
		"in user-friendly interface instead of clumsy input fields with Browse button. "+
		"Installation will take up to few minutes, please be patient. To install Image Uploader, ";
	this.instructionsNotWinXPSP2="please reload page and select \"Yes\" button " +
		"when you will see control installation dialog."
	this.instructionsWinXPSP2="please click on Information Bar. After page reloading select \"Yes\" when "+
		"you will see control installation dialog.";	    
	    
	//Methods
	this.addParam=function(paramName,paramValue){		
		var p=new Object();
		p.name=paramName;
		p.value=paramValue;
		this._params.push(p);
	}

	this.addEventListener=function(eventName,eventListener){
		var p=new Object();
		p.name=eventName;
		p.listener=eventListener;
		this._events.push(p);		
	}

	this.getActiveXInstalled=function(){
		if (this.activeXProgId){
			try{
				var a=new ActiveXObject(this.activeXProgId);
				return true;
			}
			catch(e){
				return false;
			}
		}
		return false;
	}

	this.getHtml=function(){
		var r="";		
		if (this.fullPageLoadListenerName){
			r+="<" + "script type=\"text/javascript\">";
			r+="var __"+this.id+"_pageLoaded=false;";
			r+="var __"+this.id+"_controlLoaded=false;";				
			r+="function __fire_"+this.id+"_fullPageLoad(){";
			r+="if (__"+this.id+"_pageLoaded&&__"+this.id+"_controlLoaded){";
			r+=this.fullPageLoadListenerName + "();";
			r+="}";
			r+="}";
			var pageLoadCode="new Function(\"__"+this.id+"_pageLoaded=true;__fire_"+this.id+"_fullPageLoad();\")";
			if (__browser.isWinIE){
				r+="window.attachEvent(\"onload\","+pageLoadCode+");";
			}
			else{ 
				r+="var r=window.addEventListener?window:document.addEventListener?document:null;";
				r+="if (r){r.addEventListener(\"load\","+pageLoadCode+",false);}";
			}
			r+="<"+"/script>";
		}
		
		//ActiveX control
		if(__browser.isWinIE&&this.activeXControlEnabled){
			var v=this.activeXControlVersion.replace(/\./g,",")
			var cb=this.activeXControlCodeBase+(v==""?"":"#version="+v);
			
			r+="<object id=\""+this.id+"\" name=\""+this.id+"\" classid=\"clsid:"+this.activeXClassId+"\" codebase=\""+cb+"\" width=\""+this.width+"\" height=\""+this.height+"\">";
			if (this.instructionsEnabled){
				r+=this.instructionsCommon;				
				var isXPSP2=(window.navigator.userAgent.indexOf("SV1") != -1);
				if (isXPSP2){
					r+=this.instructionsWinXPSP2;
				}
				else{
					r+=this.instructionsNotWinXPSP2;				
				}
			}
			r+=this._getObjectParamsHtml();
			r+="</object>";
			
			//Event handlers
			var e=this._events;
			var eventParams;
			for (i=0;i<e.length;i++){
				switch (e[i].name){
					case "Progress":
						eventParams="Status, Progress, ValueMax, Value, StatusText";
						break;
					case "InnerComplete":
						eventParams="Status, StatusText";
						break;						
					case "ViewChange":
					case "SortModeChange":					
						eventParams="Pane";
						break;
					default:
						eventParams="";
				}
				r+="<" + "script for=\""+this.id+"\" event=\""+e[i].name+"("+eventParams+")\">";
				if (e[i].name=="BeforeUpload"){
					r+="return ";
				}
				r+=e[i].listener+"("+eventParams+");";
				r+="<"+"/script>";
			}
			
			r+="<" + "script for=\""+this.id+"\" event=\"InitComplete()\">";
			r+="_createExpandoMethods(\""+this.id+"\");";
			if (this.fullPageLoadListenerName){
				r+="__"+this.id+"_controlLoaded=true;";
				r+="__fire_"+this.id+"_fullPageLoad();";
			}
			r+="<"+"/script>";						
		}
		else 
		//Java appplet
		if(this.javaAppletEnabled){
			if (this.fullPageLoadListenerName){
				r+="<" + "script type=\"text/javascript\">";
				r+="function __"+this.id+"_InitComplete(){";
				r+="__"+this.id+"_controlLoaded=true;";
				r+="__fire_"+this.id+"_fullPageLoad();";
				r+="}";
				r+="<"+"/script>";
			}

			//<object> for IE and <applet> for Safari
			if (__browser.isWinIE||__browser.isKhtml){
				if (__browser.isWinIE){
					r+="<object id=\""+this.id+"\" classid=\"clsid:8AD9C840-044E-11D1-B3E9-00805F499D93\" codebase=\""+window.location.protocol+"//java.sun.com/update/1.4.2/jinstall-1_4-windows-i586.cab#Version=1,4,0,0\" width=\""+this.width+"\" height=\""+this.height+"\">";
				}
				else{
					r+="<applet id=\""+this.id+"\" code=\""+this.javaAppletClassName+"\" java_codebase=\"../\" align=\"baseline\" archive=\""+this.javaAppletJarFileName+"\" mayscript=\"true\" scriptable=\"true\" width=\""+this.width+"\" height=\""+this.height+"\">";
				}

				if (this.javaAppletCached&&this.javaAppletVersion!=""){
					r+=this._getObjectParamHtml("cache_archive",this.javaAppletJarFileName);
					var v=this.javaAppletVersion.replace(/\,/g,".");
					r+=this._getObjectParamHtml("cache_version",v+","+v);
				}

				r+=this._getObjectParamHtml("type","application/x-java-applet;version=1.4");
				r+=this._getObjectParamHtml("codebase",this.javaAppletCodeBase);
				r+=this._getObjectParamHtml("archive",this.javaAppletJarFileName);
				r+=this._getObjectParamHtml("code",this.javaAppletClassName);
				r+=this._getObjectParamHtml("scriptable","true");
				r+=this._getObjectParamHtml("mayscript","true");

				r+=this._getObjectParamsHtml();

				r+=this._getObjectEventsHtml();

				if (this.fullPageLoadListenerName){
					r+=this._getObjectParamHtml("InitCompleteListener","__"+this.id+"_InitComplete");
				}
				if (__browser.isWinIE){
					r+="</object>";
				}
				else{
					r+="</applet>";
				}								
			}
			//<embed> for all other browsers
			else{
				r+="<embed id=\""+this.id+"\" type=\"application/x-java-applet;version=1.4\" codebase=\""+this.javaAppletCodeBase+"\" code=\""+this.javaAppletClassName+"\" archive=\""+this.javaAppletJarFileName+"\" width=\""+this.width+"\" height=\""+this.height+"\" scriptable=\"true\" mayscript=\"true\" pluginspage=\""+window.location.protocol+"//java.sun.com/products/plugin/index.html#download\"";

				if (this.javaAppletCached&&this.javaAppletVersion!=""){
					r+=this._getEmbedParamHtml("cache_archive",this.javaAppletJarFileName);
					var v=this.javaAppletVersion.replace(/\,/g,".");
					r+=this._getEmbedParamHtml("cache_version",v+","+v);
				}

				r+=this._getEmbedParamsHtml();

				r+=this._getEmbedEventsHtml();

				if (this.fullPageLoadListenerName){
					r+=this._getEmbedParamHtml("InitCompleteListener","__"+this.id+"_InitComplete");
				}
				r+=">";
				r+="</embed>";
			}
		}
		else
		{
			r+="Your browser is not supported.";
		}
		
		//For backward compatibility
		this.controlType=this.getControlType();
			
		return r;
	}
	
	this.getControlType=function(){
		return (__browser.isWinIE&&this.activeXControlEnabled)?"ActiveX":(this.javaAppletEnabled?"Java":"None");
	}	
	
	this.writeHtml=function(){
		document.write(this.getHtml());
	}
}

function ImageUploaderWriter(id,width,height){
	this._base=ControlWriter;
	this._base(id,width,height);
	//These properties should be modified for private label versions only
	this.activeXControlCodeBase="ImageUploader4.cab";
	this.activeXClassId="6E5E167B-1566-4316-B27F-0DDAB3484CF7";
	this.activeXProgId="Aurigma.ImageUploader.4";
	this.javaAppletJarFileName="ImageUploader2.jar";
	this.javaAppletClassName="com.aurigma.imageuploader.ImageUploader.class";
	
	//Extend
	this.showNonemptyResponse="off";
	this._getHtml=this.getHtml;
	this.getHtml=function(){
		var r="";
		if (this.showNonemptyResponse!="off"){
			r+="<" + "script type=\"text/javascript\">";
			r+="function __"+this.id+"_InnerComplete(Status,StatusText){";
			r+="if (new String(Status)==\"COMPLETE\" && new String(StatusText).replace(/\\s*/g,\"\")!=\"\"){";
			if (this.showNonemptyResponse=="dump"){
				r+="var f=document.createElement(\"fieldset\");";
				r+="var l=f.appendChild(document.createElement(\"legend\"));";
				r+="l.appendChild(document.createTextNode(\"Server Response\"));";
				r+="var d=f.appendChild(document.createElement(\"div\"));";				
				r+="d.innerHTML=StatusText;";
				r+="var b=f.appendChild(document.createElement(\"button\"));";	
				r+="b.appendChild(document.createTextNode(\"Clear Server Response\"));";				
				r+="b.onclick=function(){var f=this.parentNode;f.parentNode.removeChild(f)};";
				r+="document.body.appendChild(f);";			
			}
			else{
				var s="";
				for (var i=0;i<80;i++){s+="-";}
				r+="alert(\""+s+"\\r\\nServer Response\\r\\n"+s+"\\r\\n\"+StatusText);";
			}
			r+="}";
			r+="}";
			r+="<"+"/script>";
			this.addEventListener("InnerComplete","__"+this.id+"_InnerComplete");
		}
		return r+this._getHtml();
	}
}

function ThumbnailWriter(id,width,height){
	this._base=ControlWriter;
	this._base(id,width,height);
	//These properties should be modified for private label versions only
	this.activeXControlCodeBase="ImageUploader4.cab";
	this.activeXClassId="C619C11D-333B-4379-9062-E52FC1332CA2";
	this.activeXProgId="Aurigma.ImageUploader.4";
	this.javaAppletJarFileName="ImageUploader2.jar";
	this.javaAppletClassName="com.aurigma.imageuploader.Thumbnail.class";	
}

function ShellComboBoxWriter(id,width,height){
	this._base=ControlWriter;
	this._base(id,width,height);
	//These properties should be modified for private label versions only
	this.activeXControlCodeBase="ImageUploader4.cab";
	this.activeXClassId="BBF89515-EDB6-4236-8FBB-B6045290076D";
	this.activeXProgId="Aurigma.ImageUploader.4";
	this.javaAppletJarFileName="ImageUploader2.jar";
	this.javaAppletClassName="com.aurigma.imageuploader.ShellComboBox.class";
}

function UploadPaneWriter(id,width,height){
	this._base=ControlWriter;
	this._base(id,width,height);
	//These properties should be modified for private label versions only
	this.activeXControlCodeBase="ImageUploader4.cab";
	this.activeXClassId="72D36C78-CC12-445C-8169-CCC942B61100";
	this.activeXProgId="Aurigma.UploadPane.4";
	this.javaAppletJarFileName="ImageUploader2.jar";
	this.javaAppletClassName="com.aurigma.imageuploader.UploadPane.class";
}

function getImageUploader(id){
	if (__browser.isSafari){
		return document[id];
	}
	else{
		return document.getElementById(id);
	}
}
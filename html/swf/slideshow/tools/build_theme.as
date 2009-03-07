/*
function arrange_mc()
function build_object(instancePath_str)
function center_mc(instanceName_str, type, showPhoto)
function change_text(thisText_str)
function create_mask(i)
function create_mask_array()
function create_mc()
function changeTextBoxEdit() 
function getDragControl()
function load_NewTheme()
function remove_mcs()
function resize_mc(instanceName_str,subInstanceName_str, type_str)
function setConfig()
function startLoading()
function start_mask_build()
function set_slideshow_title() 
variables: 
				alpha_int
				align_str
				autoCrop_bool
				backgroundCurrentPhoto_bool
				backgroundColor_str
				borderThickness_int
				bold_bool
				blur_int
				buttonLarger_bool								
				buttonDownload_bool 
				buttonPrint_boo
				buttonLarger_bool
				buttonDownload_bool
				controlsVisible_bool
				customizableColor_bool
				customizableDisplay_bool
				depth_int
				endFrame_bool
				fontSize_int
				height_int
				highlightColor_str 
				hotSpotRollOver_bool
				hotSpotFreeDrag_bool
				indexWidth_int
				indexHeight_int
				indexX_int
				indexY_int
				indexPath_str
				indexOpen_bool
				indexBackgroundPhoto_str
				instanceName_str
				logoVisible_bool 
				loop_bool
				mainColor_str 
				maskPath_str 
				motion_str
				motion_bool
				moreSlideshows_bool								
				musicPath_str				
				password_str 
				photoOptionsOpen_bool
				photoOptions_bool
				photoMask_bool
				photoShadow_bool
				photoBorder_bool
				photoBackgroundFixed_bool				
				picWidth_int
				picHeight_int			
				resizable_bool
				remoteEdit_int
				scrollVisible_bool				
				shareVisible_bool 
				shadowAngle_int
				shadowDistance_int
				shadowX_int
				shadowY_int
				shadowAlpha_int
				shadowColor_int
				shadowInner_bool
				slideshowLink_str
				slideshowLinkName_str
				startAutoPlay_bool
				swfPath_str
				textField_bool
				title_str
				tip_bool
				titleShadow_bool 
				visible_bool
				width_int
				x_int
				y_int


*/

imageDirectory_str  = "/fotos";
videoDirectory_str  = "/videos";

 if(this._url.indexOf('http://') >= 0) // server development
    {

	thumbnailDirectory_str= "/photos";
	hotSpotDirectory_str= serverName_str + "/swf/slideshow/" + "assets/hotspots/";
	toolsDirectory_str =  serverName_str + "/swf/slideshow/" + "tools/"
	mcDirectory_str = serverName_str + "/swf/slideshow/"
	audioDirectory_str =  serverName_str + "/swf/slideshow/audio"
	mp3Directory_str =  serverName_str + "/photos";
	themeDirectory_str = serverName_str + "/swf/slideshow/assets/themes/";


}else{

	thumbnailDirectory_str= "/photos";
	hotSpotDirectory_str= "assets/hotspots/";
	toolsDirectory_str = "tools/"
	mcDirectory_str = "";
	audioDirectory_str = "audio"
	themeDirectory_str = "assets/themes/";


}


//---------------------------
//
//---------------------------

function arrange_mc(){
	trace("function: arrange_mc()")
	
	for(name in mc_arr[createNum_int])
	{
		
		arrayElement = mc_arr[createNum_int][name];
		//trace(" NAME = "+ name +" ----- "+arrayElement);
						
		switch (name) {
			  
			  		  
			  case "borderColor_str":
			  //trace("CHANGE BORDER COLOR")
			   if(mc_arr[createNum_int].mainColor_int != undefined){
			   		newColor= new Color(this[thismc_str]);
			   		newColor.setRGB(arrayElement);
			  
			  }else{
				   
				   	newColor= new Color(this[thismc_str]);
			   		newColor.setRGB(highlightColor_str); 
			   }
			   break;
			   
						
			   case "alpha_int":
			   //trace("change the alpha")
			   this[thismc_str]._alpha = arrayElement;
			   break;
						  
		}
		
		
	}
	// X  Y-----------------------------
	//if the x is defined move the mc to the specified x/y
	if(mc_arr[createNum_int].x_int != undefined){
		
		this[thismc_str]._x = mc_arr[createNum_int].x_int
		this[thismc_str]._y = mc_arr[createNum_int].y_int
	
	//else move the mc to the config x/y of the congig image placement
	}else{
		
		this[thismc_str]._x = picX_int
		this[thismc_str]._y = picY_int
		
		mc_arr[i].x_int = picX_int
		mc_arr[i].y_int = picY_int
	}
	// WIDTH AND HEIGHT -----------------------------
	//if the width is defined resize mc to the specific width and height
	if(mc_arr[createNum_int].width_int != undefined ){
				
		this[thismc_str]._width = mc_arr[createNum_int].width_int
		this[thismc_str]._height = mc_arr[createNum_int].height_int
	
	//else if change the size according to the width and height set in the config
	
	}else if (mc_arr[createNum_int].resizable_bool == undefined || mc_arr[createNum_int].resizable_bool == true){
		
		//trace("MC width and height changed to: "+picWidth_int+"x"+picHeight_int)
		this[thismc_str]._width = picWidth_int
		this[thismc_str]._height = picHeight_int
		
		mc_arr[i].width_int = picWidth_int
		mc_arr[i].height_int = picHeight_int
		
	//else if it's a SHADOW OR BORDER
	}else if(mc_arr[createNum_int].instanceName_str =="shadow_mc" || mc_arr[createNum_int].instanceName_str =="border_mc" ){
		
		
		//trace("SHADOW/BORDER MC ALIGNED");
		
		targetWidth_int = this[thismc_str]._width
		targetHeight_int= this[thismc_str]._height
														
		sourceRatio = sourceWidth_int/sourceHeight_int
		targetRatio = targetWidth_int/targetHeight_int
							
		resize_mc(mc_arr[createNum_int].instanceName_str);
		center_mc(mc_arr[createNum_int].instanceName_str);
		
		//move the shadow 3% of the width of the shadow_mc
		shadowMove_int = targetWidth_int*.015
		
		trace(shadowMove_int+"shadowMove_int");
		//
		switch (mc_arr[createNum_int].align_str) {
			   case "center" :
			     trace("center")
				 shadow_mc._y +=shadowMove_int;
				 break;
			   
			   case "left" :
				 trace("left")
				 shadow_mc._x -=shadowMove_int;
				 shadow_mc._y +=shadowMove_int;
				 break;
			   
			   case "right" :
				 trace("right")
				 shadow_mc._x +=shadowMove_int;
				 shadow_mc._y +=shadowMove_int;
				 break;
			//stroke BORDER 
		}
		
		//create a border
			   
		if(mc_arr[createNum_int].borderStroke_int != undefined){
			
			border_mc.shape.gotoAndStop(mc_arr[createNum_int].borderStroke_int);
		}
		
	}
	
	if(mc_arr[createNum_int].visible_bool == false){
		
		this[thismc_str]._visible = false;
		
	}
				
	
	
	
}

//---------------------------

//---------------------------

function build_object(instancePath_str)
{
	trace("function: sbuild_object()  instancePath_str:"+instancePath_str)
	//trace("build_object function called "+this[instancePath_str]+" "+ mc_arr[createNum_int].backgroundColor_str );
	//this[instancePath_str].lineStyle(2,mc_arr[createNum_int].borderColor_str,100);
		if(mc_arr[createNum_int].backgroundColor_str != undefined){
			
			this[instancePath_str].beginFill(mc_arr[createNum_int].backgroundColor_str, 100);
	
		}else if(instancePath_str != "mediaContainer_mc"){
			//trace("set to config bg color");
			this[instancePath_str].beginFill(backgroundColor_str, 100);
		}
	// draw the next lines, each starting from the last point the pen was at
	if(mc_arr[createNum_int].resizable_bool != undefined){
		
		this[instancePath_str].lineTo(mc_arr[createNum_int].width_int,0);
		this[instancePath_str].lineTo(mc_arr[createNum_int].width_int,mc_arr[createNum_int].height_int);
		this[instancePath_str].lineTo(0,mc_arr[createNum_int].height_int);
		this[instancePath_str].lineTo(0,0);
	
	}else{
		
		this[instancePath_str].lineTo(picWidth_int,0);
		this[instancePath_str].lineTo(picWidth_int, picHeight_int);
		this[instancePath_str].lineTo(0,picHeight_int);
		this[instancePath_str].lineTo(0,0);
		
	}
	
	this[instancePath_str].endFill();
	
	//make mc a button
	
	if(thismc_str=="imageButton_mc")
	{
			
		this[thismc_str].onRollOver=function()
		{
			end_help();
			
			if(data_arr[currentPhoto_int].description_str != undefined)
			{
			
				_level0.note = data_arr[currentPhoto_int].description_str;
				start_help()
				
			}
	
			//this.attachMovie("phoOptions","phoOptions_mc",900)
			
			//phoOptions_mc._x = mediaContainer_mc._x;
			//phoOptions_mc._y = mediaContainer_mc._y;
			//trace(editMode_bool)
			/*
			if(idMatch_bool == false && editMode_bool == false && data_arr[currentPhoto_int].description_str != undefined && showingControlOptions_bool == false && indexOpen_bool != true)
			{
			
				controlContainer_mc.gotoAndPlay("open");
			}
			*/
			
			//show_hotSpots()
		}
			
		
		this[thismc_str].onRollOut=function()
		{
			//phoOptions_mc.removeMovieClip()
			phoOptions_mc._visible = false;
			end_help();
			trace(editMode_bool)
			/*
			if(idMatch_bool == false && editMode_bool == false && data_arr[currentPhoto_int].description_str != undefined && showingControlOptions_bool == true)
			{
			//_root.note = data_arr[currentPhoto_int].description_str;
			//start_help();
				controlContainer_mc.gotoAndPlay("close");
				
			}
			*/
			/*
			if((_ymouse < mediaContainer_mc._y  || _ymouse > (mediaContainer_mc._y + mediaContainer_mc.photo_mc._height)) || (_xmouse < mediaContainer_mc._x || _xmouse > (mediaContainer_mc._x + mediaContainer_mc.photo_mc._width)))
			{
				
				hide_hotSpots()
			
			}
			*/
		}
		
		this[thismc_str].onRelease=function()
		{
			
			/*
			if( photoOptions_bool != false && indexOpen_bool == false && editMode_bool == false && showingControlOptions_bool != true && data_arr[currentPhoto_int].photoPath_str != undefined && data_arr[currentPhoto_int].photoPath_str != "blank")
			{
				controlContainer_mc.gotoAndPlay("open");
			
			
			}else if(indexOpen_bool == false && editMode_bool == false && showingControlOptions_bool == true && data_arr[currentPhoto_int].photoPath_str != undefined && data_arr[currentPhoto_int].photoPath_str != "blank" )
			{
				
				controlContainer_mc.gotoAndPlay("close");
			}
			if(data_arr[currentPhoto_int].link_str != undefined)
			{
				if(editMode_bool == false)
				{
					
					getURL(data_arr[currentPhoto_int].link_str, data_arr[currentPhoto_int].target_str)
				}
				
			}
			*/
			if( editMode_bool != true && mp3Loading_bool != true)
			{
				next_photo();
			}
			
			
		}
	/*
			this.attachMovie("handOpen","handOpen_mc",11160)
			this.attachMovie("handGrab","handGrab_mc",11161)
			handOpen_mc._visible = false;
			handGrab_mc._visible = false;
		
		this[thismc_str].onRelease=function(){
				//trace("RELEASE BUTTON")
				delete this.onEnterFrame;
				Mouse.hide();
				
				stopDrag();
				
				handGrab_mc._visible = false;
				handOpen_mc._visible = true;
				
				handOpen_mc._x = _xmouse;
				handOpen_mc._y = _ymouse;
					
				handOpen_mc.startDrag();
					
		}

		this[thismc_str].onRollOver=function(){
			if(draggable_bool == true){
											
				Mouse.hide();
				
				handOpen_mc._x = _xmouse;
				handOpen_mc._y = _ymouse;
				
				handOpen_mc._visible = true;
				
				handOpen_mc.startDrag();
				
			}else{
				//Mouse.hide();
				
			}
			
		}
		
		this[thismc_str].onPress=function(){
			
			delete this.onEnterFrame;
			
			if(draggable_bool == true){
				
				Mouse.hide();
				
				
				
				handGrab_mc._x = _xmouse;
				handGrab_mc._y = _ymouse;
				handGrab_mc._visible = true;
				handOpen_mc._visible = false;
				//trace(handGrab_mc)
				//trace("handGrab_mc._x:"+handGrab_mc._x)
				//handGrab_mc.startDrag();
				startDrag(mediaContainer_mc);
				
				this.onEnterFrame = function(){
					
					
					handGrab_mc._x = _xmouse;
					handGrab_mc._y = _ymouse;
					
				}
				
			}//else{
				
				//click_photo();
			//}
			
					
		}
		
		this[thismc_str].onRollOut=function(){
			
			//if(_level0.hotSpotsVisible_bool==false){
		
				//_level0.clear_hotspots();
				//_level0.get_hotspot(0);
		
			//}
			handOpen_mc._visible = false;
			handGrab_mc._visible = false;
			Mouse.show();
			stopDrag();
			delete this.onEnterFrame;
			//startDrag(picX_int
			//rollover_photo();
			//trace("button_rollover")
					
		}
			
		//this[thismc_str].onRelease=function(){
			
			///stopDrag();
			
		//}

		//}
		*/
	}
	
	
	arrange_mc();
	
}


//-----------------------------------
//this also center the photo as well as movie clips
//-----------------------------------

function center_mc(instanceName_str, type, showPhoto)
{
	
	trace("function: center_mc()  :InstanceName: "+instanceName_str+" Type: "+type+ "")
	
	widthDifference_int = sourceWidth_int - targetWidth_int;
	heightDifference_int = sourceHeight_int - targetHeight_int; 
	//trace("heightDifference_int: "+heightDifference_int)
	widthDifference_int = widthDifference_int/2;
	heightDifference_int = heightDifference_int/2;
	//trace("heightDifference_int: "+heightDifference_int)
	
	if(type == "photo")
	{
		
		mediaContainer_mc[instanceName_str]._x+= widthDifference_int;
		mediaContainer_mc[instanceName_str]._y+= heightDifference_int;
		
		//clearInterval(intervalID_centerPhoto)
		
		
		if(instanceName_str == "photo2_mc"){
			//line_mc._visible = false
			if(photoBorder_bool == true)
			{
				makeBorder(widthDifference_int, heightDifference_int,"photo3_mc")
			}
			
			}else{
			//line_mc._visible = false
			if(photoBorder_bool == true)
			{
				makeBorder(widthDifference_int, heightDifference_int,"photo_mc")
			}
		}
			if(showPhoto == true)
			{
				
				mediaContainer_mc.photo_mc._alpha = 100
			}
			
			
			
			mediaContainer_mc.photo_mc._visible = true
			mediaContainer_mc._visible = true
		//generate shadow
		//trace("photoShadow_bool: "+photoShadow_bool)
		if(photoShadow_bool==true && instanceName_str == "photo_mc")
		{
						

				getShadow_mc(shadowDistance_int, shadowAngle_int, shadowColor_int , shadowAlpha_int , shadowX_int, shadowY_int, shadowInner_bool, "imageBackground_mc")
				
				imageBackground_mc._visible = true;
				imageBackground_mc._x = mediaContainer_mc._x;
				imageBackground_mc._y = mediaContainer_mc._y;
				
				if(data_arr[currentPhoto_int].photoPath_str != undefined && data_arr[currentPhoto_int].photoPath_str != "blank" && photoBackgroundFixed_bool == false)
				{
					//trace(">>>>>>>>>>>>   imageBackground_mc photo  <<<<<<<<<<<<<<< ")
					imageBackground_mc._width = mediaContainer_mc.photo_mc._width; 
					imageBackground_mc._height = mediaContainer_mc.photo_mc._height;
					
					imageBackground_mc._x += widthDifference_int;
					imageBackground_mc._y += heightDifference_int;
				
				}else if(data_arr[currentPhoto_int].title_str != undefined || data_arr[currentPhoto_int].photoPath_str == "blank" || data_arr[currentPhoto_int].videoPath_str != undefined || photoBackgroundFixed_bool == true){
					
					//trace(">>>>>>>>>>>>   imageBackground_mc for a title  <<<<<<<<<<<<<<< ")
					imageBackground_mc._width += picWidth_int; 
					imageBackground_mc._height += picHeight_int;
				}
				
				getShadow_mc(shadowDistance_int, shadowAngle_int, shadowColor_int , shadowAlpha_int , shadowX_int, shadowY_int, shadowInner_bool,"imageBackground_mc")
				
		}
	}else{
		
		//trace(widthDifference_int)
		//trace(heightDifference_int)
		
		if(type != "mask")
		{
			this[instanceName_str]._x+= widthDifference_int;
			this[instanceName_str]._y+= heightDifference_int;
			
		}//line_mc._visible = false;
		
		if(border_mc._alpha == 0 && photoBorder_bool == true)
		{
			makeBorder(widthDifference_int, heightDifference_int);
		}
		
	}
	/*
	trace(" ");
	trace(instanceName_str)
	trace("X: "+mediaContainer_mc[instanceName_str]._x)
	trace("Y: "+mediaContainer_mc[instanceName_str]._y)
	
	trace(targetWidth_int+"targetWidth_int-DONE");
	trace(targetHeight_int+"targetHeight_int-DONE");
	trace(widthDifference_int+"widthDifference-DONE");
	trace(heightDifference_int+"heightDifference-DONE");
	trace(" ");
	*/
	get_hotSpot(0);
	
}

//---------------------------------------

//---------------------------------------
function change_text(thisText_str)
{
	trace("function: change_text()")
	data_arr[currentPhoto_int].title_str = thisText_str;
	
}
//-----------------------------------------------
//_root[thismc_str]== mediaContainer_mc)
//-----------------------------------------------
function create_mask(i)
{
	 trace("function: startLoading() i:"+i)
	//trace("MASK--"+mask_arr[maskNum_int][1]);
			//path to the movieClip that needs to be masked
			thismc_str = mask_arr[maskNum_int][1];
			
			//name of the empty movieClip that will contain the mask
			thismask_str = "mask_mc"+i;
			
			//create movie clip to the mask in
			
			_root.createEmptyMovieClip(thismask_str,maskNum_int+4000);
			//trace(_root[thismask_str])
			
			loadMovie(mcDirectory_str + mask_arr[maskNum_int][0], _root.thismask_str);
			//loadMovie(mask_arr[maskNum_int][0], _root.thismask_str);
			
			function load_mask_now() { 
			trace("setInterval: load_mask_now")
					infoLoaded = _root[thismask_str].getBytesLoaded();
					//trace("MASK thismask_str: "+_root[thismask_str])
					//trace("MASK infoLoaded: "+infoLoaded)
					infoTotal = _root[thismask_str].getBytesTotal();
					//trace("MASK infoTotal: "+infoTotal)
					percentage = Math.floor(infoLoaded/infoTotal*100);
		
					if (percentage==100) {
						trace("")
						trace("|-------------- "+_root.thismask_str+" LOADED-------------|")
						trace("percentage:"+percentage)
						clearInterval(intervalID_mask)
				
						//trace(_root[thismask_str]+" --");

						
						if(_root[thismc_str]== mediaContainer_mc)
						{
							trace("---- DO NOT MASK THIS IMAGE")
							mediaContainerMask_str = "mask_mc"+i;
							trace("mediaContainerMask_str: "+mediaContainerMask_str)
							if(_root.photoMask_bool == false)
							{
															
								//trace("mediaContainerMask_str: "+mediaContainerMask_str)
								_root[thismask_str]._visible = false;
							
							}else{
								
								trace("instanceName: "+_root[thismc_str])
							_root[thismc_str].setMask(_root[thismask_str]);
								
							}
							
							
						}else if(_root[thismc_str]== transition_mc)
						{
							transitionMask_str = thismask_str
							trace("transitionMask_str: "+transitionMask_str)
							_root[thismc_str].setMask(_root[thismask_str]);
							
						}else{
							
							trace("instanceName: "+_root[thismc_str])
							_root[thismc_str].setMask(_root[thismask_str]);
						
							
						}
						
						//adjust position
						_root[thismask_str]._x = _root[thismc_str]._x
						_root[thismask_str]._y = _root[thismc_str]._y
						
						sourceWidth_int = _root.picWidth_int;
						sourceHeight_int = _root.picHeight_int;
						
						targetWidth_int =  _root[thismask_str]._width
						targetHeight_int=  _root[thismask_str]._height
														
						sourceRatio = sourceWidth_int/sourceHeight_int
						targetRatio = targetWidth_int/targetHeight_int
							
							//trace("")
							//trace(targetWidth_int)
							//trace(targetHeight_int)
							//trace(sourceRatio)
							//trace(targetRatio)
							//trace("")
							
						resize_mc(thismask_str);
						//trace("resize_mask")
						center_mc(thismask_str,"mask");
						
						
						maskNum_int++;
						if(maskNum_int< mask_arr.length){
							
							start_mask_build();
					
						}else{
							//move on to the data array
							//_root.load_complete();
							
							_root.intervalID_complete = setInterval(_root.delay_complete,1500)
						}
				
					
					}
			
	
			
				}
		
	
	 intervalID_mask = setInterval(load_mask_now, 100);
	 trace("")
 }
 
//------------------------------

//------------------------------
function create_mask_array()
{
	
	positionButtons()
	trace("function:  create_mask_array() ") 
	//trace(mc_arr.length)
	mask_arr = new Array();

	for(i=0; i<mc_arr.length; i++){

		if(mc_arr[i].maskPath_str !=undefined ){
			
			//mask_arr[].maskPath
			mask_arr.push([mc_arr[i].maskPath_str, mc_arr[i].instanceName_str])//push i
			//trace(mask_arr+" >----> mask_arr")
		
		}

	}
	
	maskNum_int=0; 
	create_mask(maskNum_int);
	
}
//------------------------------

//------------------------------
function create_mc()
{
trace("function: create_mc()")
	//trace("createNum_int: "+createNum_int)
	
	loadAsset_int++;
	thismc_str = mc_arr[createNum_int].instanceName_str;
	
	if(thismc_str != undefined){
		this.createEmptyMovieClip(thismc_str, mc_arr[createNum_int].depth_int);
		trace("");
		trace("");
		trace(" -----------------------------> CREATED MOVIE CLIP <-------------------------------");
		trace("thismc_str: "+thismc_str)
		trace(this[thismc_str]+ " depth = "+mc_arr[createNum_int].depth_int);
		
	}

//loadMovieClip and make sure it's loaded
	if(mc_arr[createNum_int].swfPath_str != undefined)
	//if(mc_arr[createNum_int].swfPath_str != undefined || mc_arr[createNum_int].photoPath_str != undefined)
		{
			
			//trace("");
			trace("swfPath_str: " + mc_arr[createNum_int].swfPath_str);
			startLoading();
			
		}else if(mc_arr[createNum_int].textField_bool == true){
			
			trace("textField Created "+mc_arr[createNum_int].instanceName_str)
			this.createTextField(mc_arr[createNum_int].instanceName_str,mc_arr[createNum_int].depth_int,mc_arr[createNum_int].x_int, mc_arr[createNum_int].y_int, mc_arr[createNum_int].width_int, mc_arr[createNum_int].height_int);

			textFormat = "format_"+mc_arr[createNum_int].instanceName_str
						
			this[textFormat] = new TextFormat();
			this[textFormat].font = "Tahoma";
			this[textFormat].bold = mc_arr[createNum_int].bold_bool;
			this[textFormat].size = mc_arr[createNum_int].fontSize_int;
			
			
			if(mc_arr[createNum_int].mainColor_str != undefined){
				//= the global color set
				this[textFormat].color = mc_arr[createNum_int].mainColor_str;
				
				
			}else{
				
				this[textFormat].color = highlightColor_str;
			}
			
			//this[textFormat].bullet = mc_arr[createNum_int].bullet_bool;
			//this[textFormat].underline = mc_arr[createNum_int].underline_str;
			//this[textFormat].align = mc_arr[createNum_int].alignment_str;
			//this[textFormat].leading = mc_arr[createNum_int].leading_int;
			
			_root[thismc_str].selectable =  false;
			_root[thismc_str].autoSize = true;
			_root[thismc_str].multiline = true;
			_root[thismc_str].wordWrap =  true;
			//this.thismc_str.background =  0xffffff;
			_root[thismc_str].setTextFormat(this[textFormat]);
			//position and size the mc
			arrange_mc();
			
			createNum_int++;
			
			if(createNum_int<mc_arr.length){
				
				create_mc();
				
			}else{
				trace("")
				trace("")
				trace("CREATE MASK ARRAY")
				create_mask_array();
				create_mc();
				
				
			}
			
	}else if(createNum_int<mc_arr.length && thismc_str != undefined){
			//draw the object out
			build_object(thismc_str,createNum_int)
			
			createNum_int++;
			if(createNum_int<=mc_arr.length){
				
				create_mc();
			}else{
				trace("")
				trace("")
				trace("CREATE MASK ARRAY 2")
				create_mask_array();
				//play();
			}
			
	}else{
		//mask movieClips!
		trace("")
		trace("")
		trace("CREATE MASK ARRAY 3")
		create_mask_array();
		
	}
	//}
}
//---------------------------------------

//---------------------------------------
function changeTextBoxEdit() 
{
	title_mc.textBox_mc.setTextFormat(titleFormat);
	//title_mc.textBox_mc.html = true;
	title_mc.textBox_mc.background = false;
	title_mc.textBox_mc.multiline = false;
	title_mc.textBox_mc.autoSize = false;
	//title_mc.textBox_mc.selectable = true;
}

//---------------------------------------

//---------------------------------------
function getDragControl()
{
	this.attachMovie("drag", "drag_mc", 11160);
	this.attachMovie("color", "color_mc", 11161);
	
	trace("drag_mc: "+title_mc.drag_mc);
	
	drag_mc._x = title_mc._x;
	drag_mc._y = title_mc._y;
	drag_mc._x -= 30;
	
	color_mc._x = title_mc._x;
	color_mc._y = title_mc._y;
	color_mc._visible = true;
	color_mc._x = drag_mc._x;
	color_mc._y += 20;
	
	drag_mc._visible = true;
	for (i=0; i<mc_arr.length; i++)
	{
		if (mc_arr[i].instanceName_str == "title_mc") {
			thisItem_int = i;
			break;
		}
	}
	
	this.onEnterFrame = function()
	{
		drag_mc._visible = true;
		drag_mc._x = title_mc._x;
		drag_mc._y = title_mc._y;
		color_mc._x = title_mc._x;
		color_mc._y = title_mc._y+20;
	}
	
	drag_mc.onPress = function() 
	{
		startDrag(title_mc);
	}
	
	color_mc.onRelease = function() 
	{
		editingTitleFrame_bool = true;
		loadSiteTitle();
	}
	
	drag_mc.onRelease = function() 
	{
		stopDrag();
		mc_arr[thisItem_int].x_int = title_mc._x;
		mc_arr[thisItem_int].y_int = title_mc._y;
		delete this.onEnterFrame;
		removeMovieClip(color_mc);
		removeMovieClip(drag_mc);
	}
	
	drag_mc.onRollOver = function() 
	{
		drag_mc.gotoAndStop(2);
	}
	
	drag_mc.onRollOut = function() 
	{
		drag_mc.gotoAndStop(1);
	}
}

//-------------------------------------

//-------------------------------------
function load_NewTheme()
{
	trace("function: load_NewTheme()")
	//remove all movieClips
	trace("mc_arr.length: "+mc_arr.length)
	remove_mcs();
	//remove contents of previous array
	mask_arrNum = mask_arr.length;
	for(i=0; i<mask_arrNum; i++){
			//remove contents of mask array
			mask_arr.pop();
				
		}
	
	//this changes the theme
	if (_level2.loaded_bool){
		for(i=0; i<mc_arr.length; i++){
				trace(i)
				mc_arr[i].pop();
		}
		
		mc_arr = new Array;
			
			
			
	
	   ///mc_arr=_level2.mc_arr.copy();
		for(m=0; m<_level2.mc_arr.length; m++){
				//trace(m)
				mc_arr[m] = new Object();
				mc_arr[m].alpha_int= _level2.mc_arr[m].alpha_int
				mc_arr[m].autoCrop_bool = _level2.mc_arr[m].autoCrop_bool;
				//dynamic border
				
				
				mc_arr[m].align_str = _level2.mc_arr[m].align_str
				
				mc_arr[m].backgroundCurrentPhoto_bool = _level2.mc_arr[m].backgroundCurrentPhoto_bool
				mc_arr[m].backgroundColor_str = _level2.mc_arr[m].backgroundColor_str;
				mc_arr[m].borderThickness_int = _level2.mc_arr[m].borderThickness_int;
				mc_arr[m].bold_bool = _level2.mc_arr[m].bold_bool
				mc_arr[m].blur_int =_level2.mc_arr[m].blur_int
				
				if(mc_arr[m].buttonLarger_bool == false)
				{
					
					buttonLarger_bool = false;
									
				}
				if(mc_arr[m].buttonDownload_bool == false)
				{
					
					buttonDownload_bool = false;
					
				}
								
				mc_arr[m].buttonPrint_bool = buttonPrint_bool;
				mc_arr[m].buttonLarger_bool = buttonLarger_bool;
				mc_arr[m].buttonDownload_bool = buttonDownload_bool;
				
				mc_arr[m].controlsVisible_bool = controlsVisible_bool;
				mc_arr[m].customizableColor_bool = _level2.mc_arr[m].customizableColor_bool;
				mc_arr[m].customizableDisplay_bool = _level2.mc_arr[m].customizableDisplay_bool;
				
				mc_arr[m].fontSize_int = _level2.mc_arr[m].fontSize_int
				
				mc_arr[m].height_int = _level2.mc_arr[m].height_int
				mc_arr[m].highlightColor_str =_level2.mc_arr[m].highlightColor_str;
				mc_arr[m].hotSpotRollOver_bool = hotSpotRollOver_bool;
				mc_arr[m].hotSpotFreeDrag_bool= hotSpotFreeDrag_bool;
				//index configuration
				mc_arr[m].indexWidth_int = _level2.mc_arr[m].indexWidth_int
				mc_arr[m].indexHeight_int =_level2.mc_arr[m].indexHeight_int
				mc_arr[m].indexX_int = _level2.mc_arr[m].indexX_int
				mc_arr[m].indexY_int =_level2.mc_arr[m].indexY_int
				
				mc_arr[m].indexPath_str = _level2.mc_arr[m].indexPath_str
				mc_arr[m].indexOpen_bool = _level2.mc_arr[m].indexOpen_bool
				mc_arr[m].indexBackgroundPhoto_str = _level2.mc_arr[m].indexBackgroundPhoto_str
				
				mc_arr[m].instanceName_str = _level2.mc_arr[m].instanceName_str;
				
				mc_arr[m].depth_int = _level2.mc_arr[m].depth_int;
				
				if(mc_arr[m].logoVisible_bool == false)
				{
					logoVisible_bool = false
					
				}
				
				
				
				mc_arr[m].logoVisible_bool = logoVisible_bool
				mc_arr[m].loop_bool = loop_bool
								
				mc_arr[m].mainColor_str =_level2.mc_arr[m].mainColor_str;
				mc_arr[m].maskPath_str =_level2.mc_arr[m].maskPath_str
				mc_arr[m].motion_str = motion_str;
				mc_arr[m].motion_bool = motion_bool;
				mc_arr[m].moreSlideshows_bool = moreSlideshows_bool;
				
				
				//if(mc_arr[m].motion_bool == undefined || mc_arr[m].motion_bool == true)
				//{
					//mc_arr[m].motion_str = "grow"
					
				//}else{
					
					//mc_arr[m].motion_str = "none"
				//}
				mc_arr[m].musicPath_str = musicPath_str
				
				mc_arr[m].password_str = password_str;
				mc_arr[m].photoOptionsOpen_bool = photoOptionsOpen_bool
				mc_arr[m].photoOptions_bool = photoOptions_bool
				mc_arr[m].photoMask_bool = _level2.mc_arr[m].photoMask_bool;
				mc_arr[m].photoShadow_bool =  _level2.mc_arr[m].photoShadow_bool;
				mc_arr[m].photoBorder_bool= _level2.mc_arr[m].photoBorder_bool;
				mc_arr[m].photoBackgroundFixed_bool = _level2.mc_arr[m].photoBackgroundFixed_bool;
				
				mc_arr[m].picWidth_int = _level2.mc_arr[m].picWidth_int
				mc_arr[m].picHeight_int = _level2.mc_arr[m].picHeight_int
				
				
				mc_arr[m].endFrame_bool =  _level2.mc_arr[m].endFrame_bool
				//is the mc resizable (shadow_mc, transition_mc, photobackground, border_mc)
				mc_arr[m].resizable_bool = _level2.mc_arr[m].resizable_bool
				mc_arr[m].remoteEdit_int= remoteEdit_int;
				//hide scroll bar
				mc_arr[m].scrollVisible_bool = _level2.mc_arr[m].scrollVisible_bool;
				//hide share options... pro user
				mc_arr[m].shareVisible_bool = shareVisible_bool;
				
				//shadow parameters for photo
				mc_arr[m].shadowAngle_int = _level2.mc_arr[m].shadowAngle_int;
				mc_arr[m].shadowDistance_int = _level2.mc_arr[m].shadowDistance_int;
				mc_arr[m].shadowX_int = _level2.mc_arr[m].shadowX_int;
				mc_arr[m].shadowY_int = _level2.mc_arr[m].shadowY_int;
				mc_arr[m].shadowAlpha_int = _level2.mc_arr[m].shadowAlpha_int;
				mc_arr[m].shadowColor_int = _level2.mc_arr[m].shadowColor_int;
				mc_arr[m].shadowInner_bool = _level2.mc_arr[m].shadowInner_bool;
				
				mc_arr[m].slideshowLink_str = slideshowLink_str;
				mc_arr[m].slideshowLinkName_str =  slideshowLinkName_str;
				mc_arr[m].startAutoPlay_bool = startAutoPlay_bool;	
				mc_arr[m].swfPath_str = _level2.mc_arr[m].swfPath_str
												
				mc_arr[m].textField_bool = _level2.mc_arr[m].textField_bool
				mc_arr[m].title_str = title_str;
				mc_arr[m].tip_bool = false;
				mc_arr[m].titleShadow_bool = _level2.mc_arr[m].titleShadow_bool;
				mc_arr[m].visible_bool = _level2.mc_arr[m].visible_bool
				mc_arr[m].width_int = _level2.mc_arr[m].width_int
				
				mc_arr[m].x_int=  _level2.mc_arr[m].x_int;
				mc_arr[m].y_int = _level2.mc_arr[m].y_int;
				
				trace("LEVEL2: "+_level2.mc_arr[m].instanceName_str)
				
				//trace("LEVEL2: "+_level2.mc_arr[m].swfPath_str)
				//trace("LOCAL: "+mc_arr[m].instanceName_str)
				//trace("LOCAL: "+mc_arr[m].swfPath_str)
		}
			
			
			//mc_arr_length = mc_arr.length
			_level2.loaded_bool = false;
			
			clearInterval(intervalID_theme)
			createNum_int=0;
			loadAsset_int = 0
			loadAsset_int--;
			setConfig();
	}
	
	editTheme_bool =false;
		
		
}
		




//-------------------------------------------------------
//function is called when a new theme is loaded
//-------------------------------------------------------
function remove_mcs()
{
		
	trace("function: remove_mcs()")
	trace("mc_arr.length "+mc_arr.length)

	borderShadow_bool = false;
	removeMovieClip(background_mc);
	removeMovieClip(backgroundGraphic_mc);
	removeMovieClip(backgroundPhoto_mc);
	removeMovieClip(border_mc);
	removeMovieClip(config_mc);
	removeMovieClip(controlContainer_mc);
	removeMovieClip(detail_mc);
	removeMovieClip(imageBackground_mc);
	removeMovieClip(imageButton_mc);
	removeMovieClip(line_mc)
	removeMovieClip(logo_mc);
 	removeMovieClip(mediaContainer_mc);
	removeMovieClip(preview_mc);
	removeMovieClip(title_mc);
	removeMovieClip(transition_mc);
	removeMovieClip(transitionAnimate_mc);

}
//---------------------------

//---------------------------
function resize_mc(instanceName_str,subInstanceName_str, type_str)
{
	
	trace("function: resize_mc() : instanceName:"+instanceName+"  subInstanceName_str:"+subInstanceName_str+"  type_str:"+type_str)
	
	if(sourceRatio > targetRatio)
	{
		
		//trace("SOURCE >  TARGET")
		targetWidth_int=(sourceHeight_int/targetHeight_int)*targetWidth_int;
		targetHeight_int=sourceHeight_int;
		
		if(type_str=="resizable")
		{
			
			this[instanceName_str][subInstanceName_str]._height= targetHeight_int;
			this[instanceName_str][subInstanceName_str]._width= targetWidth_int;
			
			//should be the text only???
			buffer_int = Math.floor(picWidth_int*.05);
			
			this[instanceName_str][subInstanceName_str]._width -= buffer_int;
			//_level0.mediaContainer_mc.photo_mc._width = 300;
			//trace(this[instanceName_str][subInstanceName_str]._width+"RIZABLE")
			break;
		
		}else{
			
			this[instanceName_str]._height= targetHeight_int;
			this[instanceName_str]._width= targetWidth_int;
			break;
		}
		
		//if the source is greater thn the targer
	}else if(sourceRatio < targetRatio){
		
		//trace("TARGET >  SOURCE")	
		targetHeight_int=(sourceWidth_int/targetWidth_int)*targetHeight_int;
		targetWidth_int=sourceWidth_int;
		
		
			
		if(type_str=="resizable"){
			
			buffer_int = Math.floor(picWidth_int*.08);
			this[instanceName_str][subInstanceName_str]._width -= buffer_int;
			
			//trace(this[instanceName_str][subInstanceName_str]+"RIZABLE")
			this[instanceName_str][subInstanceName_str]._height= targetHeight_int;
			this[instanceName_str][subInstanceName_str]._width= targetWidth_int;
			
			
			
			break;
			
		}else {
			
			this[instanceName_str]._height= targetHeight_int;
			this[instanceName_str]._width= targetWidth_int;
			break;
		}
		
		
		
	
	}else if(sourceRatio == targetRatio && (targetWidth_int != sourceWidth_int  || targetWidth_int != sourceWidth_int)){
			
			
			this[instanceName_str]._height = sourceHeight_int;
			this[instanceName_str]._width = sourceWidth_int;
			
			break;

		
	
	}else{
		
			//trace("no resize??");
			targetWidth_int=sourceWidth_int;
			targetHeight_int=sourceHeight_int;
		
	}
	
	//trace("------------------******")
	//trace(this[instanceName_str]._width)
	//trace(this[instanceName_str]._height)
	//trace("------------------*******")
		
	
}

//-------------------------------------------------

//-------------------------------------------------
//loook for the config file in the array
function setConfig()
{
trace("function: setConfig()" + mc_arr.length)

 for(i=0; i<mc_arr.length; i++){
	//trace(i+"config_mc")
	if(mc_arr[i].instanceName_str == "config_mc")
	{
			//trace("")
			//trace("config settings---------------------")
			//trace("")
			
			autoCrop_bool = mc_arr[i].autoCrop_bool
			
			
			backgroundColor_str = mc_arr[i].backgroundColor_str
			backgroundCurrentPhoto_bool = mc_arr[i].backgroundCurrentPhoto_bool
			borderThickness_int = mc_arr[i].borderThickness_int;
			buttonDownload_bool = mc_arr[i].buttonDownload_bool;
			buttonLarger_bool = mc_arr[i].buttonLarger_bool;
			controlsVisible_bool = mc_arr[i].controlsVisible_bool
			//swf movie dimensions
			containerWidth_int = mc_arr[i].width_int
			containerHeight_int = mc_arr[i].height_int
			
			highlightColor_str = mc_arr[i].highlightColor_str
			hotSpotsVisible_bool = false;
			hotSpotRollOver_bool = mc_arr[i].hotSpotRollOver_bool;
			
			
			indexWidth_int = mc_arr[i].indexWidth_int
			indexHeight_int = mc_arr[i].indexHeight_int
			indexX_int = mc_arr[i].indexX_int
			indexY_int = mc_arr[i].indexY_int
			indexPath_str = mc_arr[i].indexPath_str;
			indexOpen_bool = mc_arr[i].indexOpen_bool;
			
			//shows the next public slideshow on the end frame
			endFrame_bool = mc_arr[i].endFrame_bool
			hotSpotFreeDrag_bool = mc_arr[0].hotSpotFreeDrag_bool;
			
			logoVisible_bool = mc_arr[i].logoVisible_bool;
			
			//trace("motion_str: "+motion_str)
			maskPath_str = mc_arr[i].maskPath_str
			
			musicPath_str = mc_arr[i].musicPath_str;
			moreSlideshows_bool = mc_arr[0].moreSlideshows_bool
			motion_bool = mc_arr[i].motion_bool			
					
			if(motion_bool == true)
			{
				motion_str = "grow"
			}
			
			loop_bool = mc_arr[i].loop_bool;
			
			mainColor_str = mc_arr[i].mainColor_str
			movieWidth_int = mc_arr[i].width_int
			movieHeight_int= mc_arr[i].height_int
			
			password_str = mc_arr[i].password_str;
			picWidth_int = mc_arr[i].picWidth_int
			picHeight_int = mc_arr[i].picHeight_int
			
			//where the pictures will be located
			picX_int = mc_arr[i].x_int
			picY_int = mc_arr[i].y_int
			photoBackgroundFixed_bool = mc_arr[i].photoBackgroundFixed_bool
			photoMask_bool = mc_arr[i].photoMask_bool
			photoShadow_bool = mc_arr[i].photoShadow_bool
			photoBorder_bool = mc_arr[i].photoBorder_bool
			
			//remoteEdit_int = mc_arr[0].remoteEdit_int;
			
			shareVisible_bool =  mc_arr[i].shareVisible_bool;
			shadowAngle_int = mc_arr[i].shadowAngle_int
			shadowDistance_int = mc_arr[i].shadowDistance_int
			shadowX_int =  mc_arr[i].shadowX_int
			shadowY_int = mc_arr[i].shadowY_int
			shadowAlpha_int = mc_arr[i].shadowAlpha_int
			shadowColor_int = mc_arr[i].shadowColor_int
			shadowInner_bool = mc_arr[i].shadowInner_bool
			
			siteBuilder_bool = mc_arr[0].siteBuilder_bool;
			sourceWidth_int = mc_arr[i].picWidth_int
			sourceHeight_int= mc_arr[i].picHeight_int
			slideshowLink_str = mc_arr[i].slideshowLink_str
			startAutoPlay_bool = mc_arr[i].startAutoPlay_bool;
			
			title_str = mc_arr[i].title_str;
			
			
						
			photoOptions_bool = mc_arr[0].photoOptions_bool;
			photoShadowAngle_int = mc_arr[i].photoShadowAngle_int
			
			//shows photo options
			photoOptionsOpen_bool = mc_arr[i].photoOptionsOpen_bool
						
			create_mc();
			
		}
		if(mc_arr[i].instanceName_str == "border_mc")
		{
			
			//border_mc.gotoAndStop(borderThickness_int)
			borderColor_str = mc_arr[i].mainColor_str;
			//trace("borderColor_str: "+borderColor_str)
			//if(mc_arr[i].photoShadow_bool== true)
			//{
				//borderIndex_int = i;
				//borderShadow_bool = true;
				
			//}
			
		}
		
		if(mc_arr[i].instanceName_str == "transition_mc")
		{
			transitionType_str = mc_arr[i].swfPath_str
		}
		
		if(mc_arr[i].instanceName_str == "preview_mc")
		{
			//trace("CONFIG PREVIEW THUMBNAILS")
			column_int = mc_arr[i].indexX_int
			row_int = mc_arr[i].indexY_int
		}
		
		if(mc_arr[i].instanceName_str == "title_mc")
		{
			//trace("SET SLIDESHOW TITLE")
			//trace("title_str: " + title_str)
			title_mc.textBox_mc.text = title_str;
			titleShadow_bool = mc_arr[i].titleShadow_bool;
			alignment_str = mc_arr[i].align_str
			trace("alignment_str: "+alignment_str)
			//trace("alignment_str: "+alignment_str)
			fontSize_int = mc_arr[i].fontSize_int
			
			themeFont_str= mc_arr[i].swfPath_str
						
			themeFont_str = substring(themeFont_str,14,themeFont_str.length);
									trace("themeFont_str:"+themeFont_str)
			//mc_arr[10].swfPath_str = "assets/fonts/arial.swf";
			if(mc_arr[i].mainColor_str != undefined)
			{
				titleColor_str = mc_arr[i].mainColor_str;
				titleColorDefined_bool = true;
			
			}else{
				
				titleColorDefined_bool = false;
			}
			//mc_arr[i].mainColor_str = highlightColor_str
			
		}
		
		if(mc_arr[i].instanceName_str == "backgroundPhoto_mc")
		{
			trace("mc_arr[i].instanceName_str == backgroundPhoto_mc")
			photoBackgroundIndex_int = i
			blur_int = mc_arr[i].blur_int;
			trace(blur_int)
			bgPhotoDepth_int = mc_arr[i].depth_int
		}
		
		if(mc_arr[i].instanceName_str == "buttonScroll_mc")
		{
			//trace("SET SLIDESHOW TITLE")
			//trace("title_str: " + title_str)
			scrollWidth_int = mc_arr[i].scrollWidth_int;
			
		}
		
		
						
	}
	trace("END: setConfig()------------------")
}

//-----------------------------------------
//
//-----------------------------------------
function startLoading()
{
	trace("function: startLoading()")
	//loadMovie(mc_arr[createNum_int].swfPath_str, this[thismc_str]);
	
	clip_status_mc = "idle";
	myMCL_mc = new MovieClipLoader();
	myListener_mc = new Object();

	myListener_mc.onLoadStart = function (targetMC)
	{
		clip_status_mc_mc = "started";
	}
	
	myListener_mc.onLoadComplete = function (targetMC) 
	{
		clip_status_mc = "loaded";
	}
	
	myListener_mc.onLoadError = function (targetMC, errorCode) 
	{
		clip_status_mc = "error";
	}

	myMCL_mc.addListener(myListener_mc);
	
	
	//loadMovie(mcDirectory_str + mc_arr[createNum_int].swfPath_str, this[thismc_str]);

	if(mc_arr[createNum_int].swfPath_str.indexOf(".swf")>0)
	{
		
		if(mc_arr[createNum_int].instanceName_str == "controlContainer_mc" && version_str != "LOCAL")
		{
			myMCL_mc.loadClip(mcDirectory_str + mc_arr[createNum_int].swfPath_str +"?timestamp="+timestamp , this[thismc_str])
		}else{
			
			myMCL_mc.loadClip(mcDirectory_str + mc_arr[createNum_int].swfPath_str, this[thismc_str])
		}
	
	}else{
		
		if(mc_arr[createNum_int].instanceName_str == "controlContainer_mc"  && version_str != "LOCAL")
		{
			myMCL_mc.loadClip(mc_arr[createNum_int].swfPath_str +"?timestamp="+timestamp , this[thismc_str])
		}else{
			
			myMCL_mc.loadClip(mc_arr[createNum_int].swfPath_str, this[thismc_str])
		}
		
		
	}
	
	//trace()
	function load_mc_now() { 
		trace("setInterval: load_mc_now")
		
		loading._visible = true;
		infoLoaded = _root[thismc_str].getBytesLoaded();
		infoTotal = _root[thismc_str].getBytesTotal();
		percentage = Math.floor(infoLoaded/infoTotal*100);
		
		//this.infoField._visible = true;
		//this.infoField.text = percentage+"%";
		trace(percentage)
		//if (percentage==100) {
		if(clip_status_mc == "loaded"){
			clearInterval(intervalID_mc)
			trace("| ---------------- : "+thismc_str+" : LOADED-----------------|")
			//trace(percentage)
			trace("")
			//this.infoField._visible = false;
			//loading._visible = false;
				
				//}else if(mc_arr[i].instanceName_str == "button"){
				//set the width/height x /y and alpha						
				arrange_mc();
				//mcCount_int = mc_arr.length;
				//mcCount_int--;
				if(createNum_int<=mc_arr.length){
					
					createNum_int++;
					create_mc();
				
				}else{
					//trace("")
					//trace("")
					//trace("CREATE MASK ARRAY 4")
					//create_mask_array();
					
				}
						
		}
						
	}
	 intervalID_mc = setInterval(load_mc_now,100);
}
//----------------------------------------------

//----------------------------------------------

function start_mask_build()
{
	 trace("function:  start_mask_build() ") 
	create_mask(maskNum_int);
 
	
}
		
//---------------------------------------

//---------------------------------------
function set_slideshow_title() 
{
	
	trace("function:  set_slideshow_title()");
	//trace("alignment_str: "+alignment_str);
	if (titleColorDefined_bool == false)
	{
		;
		title_mc.textBox_mc.text = title_str;
		titleFormat = new TextFormat();
		
		titleFormat.size = fontSize_int;
		titleFormat.align = alignment_str;
		titleFormat.color = highlightColor_str;
		
	} else {
		
		title_mc.textBox_mc.text = title_str;
		titleFormat = new TextFormat();
		//titleFormat.html = true;
		titleFormat.size = fontSize_int;
		titleFormat.align = alignment_str;
		titleFormat.color = titleColor_str;
	}
	//titleFormat.type = "input"
	title_mc.textBox_mc.setTextFormat(titleFormat);
	_root.changeTextBoxEdit();
	if (loggedIn_bool == true && idMatch_bool == true) 
	{
		
		trace(_root.loggedIn_bool+_root.idMatch_bool);
		title_mc.textBox_mc.selectable = true;
		title_mc.textBox_mc.type = "input";
		title_mc.textBox_mc.maxChars = 40;
		
		title_mc.textBox_mc.onSetFocus = function() 
		{
			if (editMode_bool == false)
			{
				title_mc.textBox_mc.background = true;
				title2Format = new TextFormat();
				title2Format.color = "0x000000";
				title_mc.textBox_mc.setTextFormat(title2Format);
				title_mc.textBox_mc.multiline = false;
				title_mc.textBox_mc.autoSize = true;
				//_root.changeTextBox_id = setInterval(_root.changeTextBoxEdit , 2000);
				getDragControl();
				
				title_mc.textBox_mc.onChanged = function(changedField) 
				{
					title_str = title_mc.textBox_mc.text;
					mc_arr[0].title_str = title_mc.textBox_mc.text;
					title_mc.textBox_mc.setTextFormat(title2Format);
					title_mc.textBox_mc.background = true;
					//title_mc.textBox_mc.setTextFormat(titleFormat);
					//_root.changeTextBox_id = setInterval(_root.changeTextBoxEdit , 2000);
				}
			}
			title_mc.textBox_mc.onKillFocus = function() {
				//removeMovieClip(color_mc)
				//removeMovieClip(drag_mc)
				_root.changeTextBoxEdit();
			}
		}
	}
	//_level0.startEditMode_int = 1;  
	//------------------------------------------------------------
	//get_toolBar();
	//loadEditTimeline();
	
	
	
}


	

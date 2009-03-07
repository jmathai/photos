/*

function changeButton()
function change_text(thisText_str)
function close_index()
function hide_hotSpots()
function get_download()
function get_larger_image()
function get_photo(from_str)
function get_photo_info()
function loadBitmapSmoothed(url:String, target:MovieClip) 
function makeBorder(wD_int, hD_int,whichPhoto)
function next_photo()
function permission_check(data)
function preload_image()
function preload_thumbnails()
function previous_photo()
function reset_photo()
function set_title_format()
function show_hotSpots()
function show_index()
function track_photo()

*/

import flash.display.*;

//------------------------------

//------------------------------
function changeButton()
{
	
	imageButton_mc.onReleaseOutside=function()
	{
		end_help();
	}
	imageButton_mc.onRollOver=function()
	{
			end_help();
			
			
			/*
			if(idMatch_bool == false && indexOpen_bool == false && editMode_bool == false && data_arr[currentPhoto_int].description_str != undefined && showingControlOptions_bool == false && indexOpen_bool != true)
			{
			
				controlContainer_mc.gotoAndPlay("open");
			}
			*/
			buttonRolledOver_bool = true;
			show_hotSpots();
			
	}
	
	imageButton_mc.onRollOut=function()
	{
			
			end_help();
			/*
			if(idMatch_bool == false && editMode_bool == false && data_arr[currentPhoto_int].description_str != undefined && showingControlOptions_bool == true)
			{
			//_root.note = data_arr[currentPhoto_int].description_str;
			//start_help();
				controlContainer_mc.gotoAndPlay("close");
				
			}
			*/
			if((_ymouse < mediaContainer_mc._y  || _ymouse >  (mediaContainer_mc._y+mediaContainer_mc.photo_mc._height) ) || (_xmouse < mediaContainer_mc._x || _xmouse >( mediaContainer_mc._x + mediaContainer_mc.photo_mc._width)))
			{
				trace("HIDE HOT SPOT");
				hide_hotSpots()
			
			}
			
	}
	
}
//---------------------------------------

//---------------------------------------
function change_text(thisText_str)
{
	trace("function: change_text()")
	data_arr[currentPhoto_int].title_str = thisText_str;
	
}
//---------------------------------------------

//---------------------------------------------
function close_index()
{
	trace("")
	trace("")
	trace("---------------------------------------------------------------")
	trace("function close_index():  location slide_show_prototype.swf  ")
	trace("")
	trace("description:  closes index, removing index_mc, indexBG_mc... setting indexOpen_bool to false ")
	trace("---------------------------------------------------------------")
	removeMovieClip(index_mc);
	removeMovieClip(indexBG_mc);
	indexOpen_bool = false;
	
	
}

//------------------------------
// hide hotspots on photo roll out
//------------------------------
function  hide_hotSpots()
{
	
	buttonRolledOver_bool = false;
	if(hotSpotRollOver_bool == true && buttonHSOver_bool == false )
			{
			
				for(i=0;i<data_arr[currentPhoto_int].hotSpot_arr.length;i++)
				{
			
					mediaContainer_mc.photo_mc["hotSpot"+i]._visible = false;
					
				}
				
				
			}
			
}


//-------------------------------------------------------------------

//------------------------------------------------------------------
function get_download()
{
	trace("function get_download():")
	getURL(serverName_str+"/download/?key="+data_arr[currentPhoto_int].photoKey_str);
	
	
}
//-------------------------------

//-------------------------------
function get_larger_image()
{
	trace("function get_larger_image():")
	getURL(serverName_str+"/handler/photo/"+data_arr[currentPhoto_int].photoKey_str+"/" , "_blank");
	//trace(serverName+"/handler/photo/"+data_arr[currentPhoto_int].photoKey_str)
}

//-------------------------------

//-------------------------------
function get_photo(from_str)
{
	
	//from_str scroll means that is will show the picture without a transition
	
	trace("function: get_photo()")
	
	end_help();
	//delete volume_mc.onEnterFrame;
	track_photo();
	//if(idMatch_bool == false)
	//{
		//loadVariables(serverName_str+"/?action=flix.flix_view.act&key="+slideshowKey_str);
	//}
	
	////////clear all hotspts();
	clear_hotSpot();
	reset_photo();
	reset_zoom();
	//timelineContainer_mc.deleteButton._x +=800
	close_index();
	
	removeMovieClip(preLoad2_mc);
	
	//selectPreviewPhoto
	timelineContainer_mc.timeline1_mc.select_frame();
	navContainer_mc.timeline1_mc.select_frame();
	navContainer_mc.timeline1_mc.buttonActive_bool = true;
	navContainer_mc.deleteButton_mc._alpha = 100;
	
	timelineContainer_mc.timeline1_mc.buttonActive_bool = true;
	timelineContainer_mc.deleteButton_mc._alpha = 100;
	

	
	if(preview_mc._visible == true)
	{
		
			preview_mc["bg"+currentPhoto_int].photoBackground_mc.gotoAndStop("hold")
			preview_mc["bg"+previousPhoto_int].photoBackground_mc.gotoAndStop(1)
		
		
	}
	
		
	//move the scroll button to the right marker
	controlContainer_mc.scroll_mc.button_mc._x = controlContainer_mc.scroll_mc.flixScroll[currentPhoto_int].thisPic
	
	//create the mc that the photos goes into
	mediaContainer_mc.createEmptyMovieClip("photo_mc",2);
	mediaContainer_mc.photo_mc._alpha = 0
	mediaContainer_mc.photo_mc._visible = false;

//----- MOVIE CLIP LOAD LISTENER ---  tells flash when the photo has been loaded
	clip_status = "idle";
	myMCL 		= new MovieClipLoader();
	myListener = new Object();

	myListener.onLoadStart = function (targetMC)
	{
		clip_status = "started";
	}
	
	myListener.onLoadComplete = function (targetMC) 
	{
		clip_status = "loaded";
	}
	
	myListener.onLoadError = function (targetMC, errorCode) 
	{
		clip_status = "error";
	}

	myMCL.addListener(myListener);
	
	//restore hotspot icons...
	if(loggedIn_bool == true && idMatch_bool == true)
	{
		hotSpotToolBar_mc.iconCircle_mc._alpha = 100;
		hotSpotToolBar_mc.iconSquare_mc._alpha = 100;
		hotSpotToolBar_mc.iconQuote_mc._alpha = 100;
		hotSpotToolBar_mc.iconThought_mc._alpha = 100;
		hotSpotToolBar_mc.advanced_mc._alpha = 100;
		hotSpotToolBar_mc.iconNote_mc._alpha = 100;
		hotSpotToolBar_mc.iconArrow_mc._alpha = 100;
		hotSpotToolBar_mc.zoomSpot_mc._alpha = 100;
	
	}
	//---------------------------------------------------

	

	if(data_arr[currentPhoto_int].photoPath_str != undefined && data_arr[currentPhoto_int].photoPath_str != "blank" ){
		if(mc_arr[0].autoCrop_bool == true)
		{
			
			myMCL.loadClip(ptg.customImage(data_arr[currentPhoto_int],picWidth_int,picHeight_int), mediaContainer_mc.photo_mc);
			//loadMovie(ptg.customImage(data_arr[currentPhoto_int],picWidth_int,picHeight_int), mediaContainer_mc.photo_mc);
			
		}else{
			
			myMCL.loadClip(ptg.customImageLock(data_arr[currentPhoto_int],picWidth_int,picHeight_int), mediaContainer_mc.photo_mc);
			//loadMovie(ptg.customImageLock(data_arr[currentPhoto_int],picWidth_int,picHeight_int), mediaContainer_mc.photo_mc);
		
		break;
		
		}
	
	}else if (data_arr[currentPhoto_int].videoPath_str != undefined){
			
			
			if(audioOn_bool == true)
	
			{
				//music_mc.volume(100)
				controlContainer_mc.buttonAudio_mc.button_mc.gotoAndStop(1)
				audioOn_bool = true;
				theVolume = 100
				global_sound.setVolume(theVolume);
							
			}
		trace("LOAD PHOTO FUNCTION videoPath_str: "+data_arr[currentPhoto_int].videoPath_str)
				//loadMovie(mcDirectory_str + "assets/videoPlayer.swf", mediaContainer_mc.photo_mc);
		myMCL.loadClip(mcDirectory_str + "assets/videoPlayer_"+mc_arr[0].picWidth_int+".swf", mediaContainer_mc.photo_mc);
		//clip_status = "loaded";
		break;
		
	}else if(data_arr[currentPhoto_int].photoPath_str == "blank")
	{
		trace("")
		trace("")
		trace("")
		trace("")
		trace("---------------------------------------------------------------")
		trace("BLANK FRAME")
		
		
		//if(data_arr[currentPhoto_int].themeMatch_bool == true)
		//{
			
			//myMCL.loadClip(mcDirectory_str +"assets/themes/frames/"+themeName_str+"_"+picWidth_int+".swf";
			
		//}else{
			
			//site builder mode makes frame bg color match theme color automatically
			if(siteBuilder_bool != 1)
			{
				
				mediaContainer_mc.photo_mc.beginFill(data_arr[currentPhoto_int].backgroundColor_str, 100);
				
				
			}else{
				
				
				if(data_arr[currentPhoto_int].parent_str != undefined)
				{
					
					background_mc.pageName_mc.textBox_mc.text = data_arr[currentPhoto_int].parent_str+" > "+data_arr[currentPhoto_int].description_str  ;
					
				}else{
					
					background_mc.pageName_mc.textBox_mc.text = data_arr[currentPhoto_int].description_str ;
					
				}
				mediaContainer_mc.photo_mc.beginFill(backgroundColor_str, 100);
				
			}
			
			
			mediaContainer_mc.photo_mc.lineTo(picWidth_int,0);
			mediaContainer_mc.photo_mc.lineTo(picWidth_int,picHeight_int);
			mediaContainer_mc.photo_mc.lineTo(0,picHeight_int);
			mediaContainer_mc.photo_mc.lineTo(0,0);
			
			mediaContainer_mc.photo_mc.endFill();
			clip_status = "loaded";
			//trace()
		//}
	}else{
		//if the frame is a title make the hotspots icons transparent to indicate that they are not available for use
		if(loggedIn_bool == true && idMatch_bool == true)
		{
			hotSpotToolBar_mc.iconCircle_mc._alpha = 30;
			hotSpotToolBar_mc.iconSquare_mc._alpha = 30;
			hotSpotToolBar_mc.iconQuote_mc._alpha = 30;
			hotSpotToolBar_mc.iconThought_mc._alpha = 30;
			hotSpotToolBar_mc.advanced_mc._alpha = 30;
			hotSpotToolBar_mc.iconNote_mc._alpha = 30;
			hotSpotToolBar_mc.iconArrow_mc._alpha = 30;
			hotSpotToolBar_mc.zoomSpot_mc._alpha = 30;
		}
		//text frame
		myMCL.loadClip(mcDirectory_str + data_arr[currentPhoto_int].swfPath_str, mediaContainer_mc.photo_mc);
		
		mediaContainer_mc.createEmptyMovieClip("blank_mc", 1)
		mediaContainer_mc.blank_mc.beginFill(data_arr[currentPhoto_int].backgroundColor_str, 100);
		
		mediaContainer_mc.blank_mc.lineTo(picWidth_int,0);
		mediaContainer_mc.blank_mc.lineTo(picWidth_int,picHeight_int);
		mediaContainer_mc.blank_mc.lineTo(0,picHeight_int);
		mediaContainer_mc.blank_mc.lineTo(0,0);
		
		mediaContainer_mc.blank_mc.endFill();
		mediaContainer_mc.blank_mc._alpha = 0
	}

		
		//add textBox for loading % diplay
		_root.createTextField("percentageBox_mc",42000,picX_int,picY_int,200, 40);
		_root.percentageBox_mc.selectable = false;
		//format text box
		percentageFormat = new TextFormat();
		percentageFormat.color= highlightColor_str;
		percentageFormat.font = "Arial"
		percentageFormat.size = 10
	
		//atach preloader circle animation
		attachMovie("preloader","preloader_mc",3332001)
		preloader_mc._visible = false;
		preloader_mc._x = picX_int;
		preloader_mc._y = picY_int;
		preloader_mc._x += picWidth_int/2;
		preloader_mc._y += picHeight_int/2;
		
		//newColor= new Color(preloader_mc);
		//newColor.setRGB(highlightColor_str);
		
		//percentage = 0;
		//
	function load_photo() 
	{ 
		//trace("interval: load_photo")
		
		infoLoaded=  mediaContainer_mc.photo_mc.getBytesLoaded();
		infoTotal =  mediaContainer_mc.photo_mc.getBytesTotal();
		//trace("infoLoaded: " + infoLoaded)
		//trace("infoTotal: " + infoTotal)
		
		if(infoTotal > 0 &&  infoTotal != "NaN")
			
		{
			
			percentage = Math.floor(infoLoaded/infoTotal*100);
		
		}
		
		//_root.percentageBox_mc._alpha = Math.random(
		
		//trace("percentage: " +percentage )
		if(data_arr[currentPhoto_int].photoPath_str == "blank")
		{
			percentage = 100;
			clip_status = "loaded"
		}
		_root.percentageBox_mc.text = "Loading: "+percentage+"%";
		_root.percentageBox_mc.setTextFormat(percentageFormat);
		_root.preloader_mc._visible = true;
		
		//once the image has loaded do these...
		if (clip_status == "loaded") {
		//if (percentage==100) {
			//trace("getTimer2:"+getTimer())
			/*
			if(trackTime_bool == true)
			{
				finishLoadTime_int = getTimer();
				
				downloadTime_int = (finishLoadTime_int - startLoadTime_int)/1000;
				
				//downloadPerSec_int = Math.ceil(downloadPerSec_int);
				//adding 70k
				//infoTotal = 1000;
				infoTotal = (infoTotal/1024);
				slideshowFileSize_int = infoTotal*data_arr.length;
				slideshowLength_int = 0
				for(i=0; i<data_arr.length; i++)
				{
				 	slideshowLength_int = slideshowLength_int + data_arr[i].delay_int
					
				}
				slideshowLength_int = slideshowLength_int/1000;
				
				downloadPerSec_int = infoTotal/downloadTime_int;
				
				idealDownloadRate = slideshowFileSize_int/slideshowLength_int
				
				
				
				if(downloadPerSec_int < idealDownloadRate)
				{
					
					amountNeededPreload_int = (idealDownloadRate-downloadPerSec_int)/idealDownloadRate;
					
					framesNeeded_int = amountNeededPreload_int * slideshowLength_int;
					
					
				
				}
				
				
				
				trackTime_bool = false;
				trace("framesNeeded_int "+framesNeeded_int)
				trace("amountNeededPreload_int "+amountNeededPreload_int)
				trace("idealDownloadRate:" + idealDownloadRate)
				trace("slideshowLength_int: " +slideshowLength_int );
				trace("startLoadTime_int: " +startLoadTime_int );
				trace("finishLoadTime_int: " +finishLoadTime_int );
				trace("downloadPerSec_int: "+downloadPerSec_int)
				trace("downloadTime_int: "+downloadTime_int)
				trace("infoTotal: "+infoTotal)
			}
			*/
			
			
			if(data_arr[currentPhoto_int].swfPath_str != undefined)
			{
			
			  getShadow_mc(4, 45, "0x000000" ,.4 , 5, 5, false,"mediaContainer_mc", "photo_mc", third_mc)
			  
			  // getShadow_mc(4, 10, 10, 25, "mediaContainer_mc", "photo_mc")
			}
			mediaContainer_mc.blank_mc._alpha = 100;
			
			
			//---------------------------- PERMISIONS   
			ptg.getPhoto(data_arr[currentPhoto_int].photoKey_str, permission_check); 
			 //---------------------------- PERMISIONS   
			  
			
			clearInterval(intervalID)
			//trace("clearInterval")
			_root.preloader_mc._visible = false;
			preloader_mc.swapDepths(0)
			removeMovieClip(preloader_mc)
			removeMovieClip(percentageBox_mc);
			targetHeight_int = mediaContainer_mc.photo_mc._height;
			targetWidth_int = mediaContainer_mc.photo_mc._width;
			
			
			
			//used to resize hotSpots
			currentPhotoWidth_int = mediaContainer_mc.photo_mc._width;
			currentPhotoHeight_int = mediaContainer_mc.photo_mc._height;
			sourceRatio = sourceWidth_int/sourceHeight_int
			targetRatio = targetWidth_int/targetHeight_int
					
			//for a title animation
			if(data_arr[currentPhoto_int].title_str != undefined && data_arr[currentPhoto_int].title_str.length>0){
				//arrange the text format fot the title frame
				set_title_format();
				resize_mc("mediaContainer_mc","photo_mc","resizable");
				//resize_mc("mediaContainer_mc","photo2_mc","resizable");
			
			}
						
			
			if(autoPlay_bool == true)
			{
				
				delay_int = data_arr[currentPhoto_int].delay_int
				
				//delay_int = data_arr[currentPhoto_int].delay_int
				//tempLength_int = data_arr.length;
				//tempLength_int--;
				if(data_arr[currentPhoto_int].motion_bool != false)
				{
					if(data_arr[currentPhoto_int].motion_str == "grow")
					{
						motion_str = "grow"
					
					}else if(data_arr[currentPhoto_int].motion_str == "shrink")
					{
						
						motion_str = "shrink"
					
					}else if(motion_str == "shrink"){
						
						
						motion_str = "grow"
						
					
					}else if(motion_str == "grow"){
						
						
						motion_str = "shrink"
						
					}else{
						
						motion_str = "grow"
					}
				}else{
					
						motion_str = "null"
					
				}
				
				
				//if()
				
				
				//
				if(data_arr[currentPhoto_int].hotSpot_arr == undefined && motion_bool == true)
				{
					
					loadBitmapSmoothed(ptg.customImageLock(data_arr[currentPhoto_int],picWidth_int,picHeight_int) , mediaContainer_mc.photo_mc)
					//blurBackground(10, true)
					switch (motion_str)
					{
					   
					   
					   case "grow":
					//   this[mediaContainerMask_str]._visible = true;
					//   mediaContainer_mc.setMask(mediaContainerMask_str);
						image_grow();
						//motion_str = "shrink"
					   break;
					   
					   case "shrink":
						this[mediaContainerMask_str]._visible = true;
						//mediaContainer_mc.setMask(mediaContainerMask_str);
						//imageBackground_mc._visible = true;
						image_shrink();
						//image_grow();
						//motion_str = "grow"
					   break;
							  
					}
				}
				
				//alpha2 forced an update for
				if(transitionType_str != "assets/transitions/alpha1.swf")
				{
					center_mc("photo_mc", "photo", true)
				
				}else{
					
					center_mc("photo_mc", "photo")
				}
				
			}else{
				
				center_mc("photo_mc", "photo", true)
			}
			
			//clear_hotspots();
			get_hotspot(0);
			
			if(backgroundCurrentPhoto_bool ==  true)
			{
				//trace("backgroundCurrentPhoto_bool : "+ backgroundCurrentPhoto_bool )
				//trace("blur_int: "+ blur_int )
				add_background_photo(data_arr[currentPhoto_int], blur_int,"currentPhoto")
				//add_background_photo(data_arr[currentPhoto_int].photoPath_str, data_arr[currentPhoto_int].photoKey_str, data_arr[currentPhoto_int].thumbnailPath_str, blurAmount_int);
	
			}
			
			//if the picture is being displayed while using the scroll
			if(from_str == "scroll")
			{
				mediaContainer_mc.blank_mc._alpha = 100;
				preload_image();
				//mediaContainer_mc.photo3_mc._visible=false;
			
			//otherwise play the transition
			}else{
				//preloads the next image
				preload_image();
				
				//transition override pulls in a transition specific for the current photo
				if(transitionOverRide_bool==true && from_str != "scroll"){
					
					transitionOverRide_bool = false
					///transition.gotoAndPlay("in");
						transition_mc.gotoAndPlay("in");
				}else{
					//template transition
					transition_mc.gotoAndPlay("in");
					
					
					
				}
				
				
			}
		}
	}

	intervalID = setInterval(load_photo, 200);
		
	previousPhoto_int = currentPhoto_int

}

//------------------------------------------------------------

//------------------------------------------------------------

function get_photo_info(){
	
	
	trace("function: get_photo_info()")
	
	if(editMode_bool==true)
	{
		
			save_hotSpot(currentHotSpot_int);
			editMode_bool = false;
	}
	
	
	
	displayCount_int = currentPhoto_int
	displayCount_int++;
	
	//text for the photo being displayed
	if(data_arr[currentPhoto_int].description_str != undefined){
		
		controlContainer_mc.descriptionText_mc.text = data_arr[currentPhoto_int].description_str;
		controlContainer_mc.textBackground_mc._visible = true;
	}else if(loggedIn_bool == true && idMatch_bool==true && startEditMode_int == true){
		
		
		controlContainer_mc.descriptionText_mc.text = "( Add Photo Description for this photo )"
		controlContainer_mc.textBackground_mc._visible = true;
			
	}else{
		
		
		controlContainer_mc.descriptionText_mc.text = "";
		controlContainer_mc.textBackground_mc._visible = false;
	}
	
		
	controlContainer_mc.countDisplay_mc.photoCountDisplay.text = displayCount_int + " of " + totalPhotos_int
	
	//trace("description: "+controlContainer_mc.descriptionText_mc.text)
	//change the format of the text
	
	format_countText_mc.color = highlightColor_str;
	format_descriptionText_mc = new TextFormat();
	format_descriptionText_mc.font = "Tahoma";
	//this[textFormat].bold = mc_arr[createNum_int].bold_bool;
	format_descriptionText_mc.size = 11;
	format_descriptionText_mc.color = highlightColor_str;
	//format_nameText_mc.color = highlightColor_str;
	
	//countText_mc.setTextFormat(format_countText_mc);
	controlContainer_mc.descriptionText_mc.setTextFormat(format_descriptionText_mc);
	//nameText_mc.setTextFormat(format_nameText_mc);
	controlContainer_mc.showControls()
	
	controlContainer_mc.tagText_mc.text = "tags: "+ data_arr[currentPhoto_int].tags_str;
	
	if(loggedIn_bool==true && idMatch_bool == true)
	{
		
		hotSpotToolBar_mc.editMotion_mc.getMotionSettings();
			
		controlContainer_mc.descriptionText_mc.type = "input";
		controlContainer_mc.descriptionText_mc.maxChars = 250;
		controlContainer_mc.descriptionText_mc.selectable = true;
		
		
		//controlContainer_mc.descriptionText_mc.multiline = true;
			controlContainer_mc.descriptionText_mc.onSetFocus = function ()
			{
				decriptFormat = new TextFormat();
					decriptFormat.color = "0x000000";
					controlContainer_mc.descriptionText_mc.background = true;
					controlContainer_mc.descriptionText_mc.setTextFormat(decriptFormat);
				
				controlContainer_mc.descriptionText_mc.onChanged = function(changedField)
				{
					data_arr[currentPhoto_int].description_str = controlContainer_mc.descriptionText_mc.text
					
				}
			}
		
		controlContainer_mc.descriptionText_mc.onKillFocus = function () 
			{ 
				controlContainer_mc.descriptionText_mc.text = data_arr[currentPhoto_int].description_str
				decriptFormat = new TextFormat();
				decriptFormat.color = "0xffffff";
			
				controlContainer_mc.descriptionText_mc.setTextFormat(decriptFormat);
				controlContainer_mc.descriptionText_mc.background = false;
				
			}
		
		
	
	}
	
	
	
	
	
}
//-------------------------------------

//-------------------------------------
function loadBitmapSmoothed(url:String, target:MovieClip) 
{   
	// Create a movie clip which will contain our    
	// unsmoothed bitmap    
	var bmc:MovieClip = target.createEmptyMovieClip("bmc", target.getNextHighestDepth());   
	// Create a listener which will notify us when    
	// the bitmap loaded successfully    
	var listener:Object = new Object();    
	// Track the target    
	listener.tmc = target;     
	a// If the bitmap loaded successfully we redraw the     
	// movie into a BitmapData object and then attach     
	// that BitmapData to the target movie clip with     
	// the smoothing flag turned on.    
	
	listener.onLoadInit = function(mc:MovieClip) 
	
	{        
		mc._visible = false;        
		var bitmap:BitmapData = new BitmapData( mc._width,  mc._height, true);
		this.tmc.attachBitmap(bitmap, this.tmc.getNextHighestDepth(), "auto", true);        
		bitmap.draw(mc);
	};    
	// Do it, load the bitmap now    
	var loader:MovieClipLoader = new MovieClipLoader();    
	loader.addListener(listener);  
	loader.loadClip(url, bmc);
}
//---------------------------------------

//----------------------------------------

function makeBorder(wD_int, hD_int,whichPhoto) 
{
	//called center mc in mc build layer
	trace("function:  makeBorder()" + wD_int + hD_int)
	//trace(line_mc)
	//removeMovieClip(line_mc)
	//trace(line_mc)
			var linethick = borderThickness_int;
			
			var linealpha = 100;
			if(borderColor_str != undefined)
			{
				var linecolor =borderColor_str
				
			}else{
				
				var linecolor = highlightColor_str;
			
			}
			
			
		
		
			
			this.createEmptyMovieClip("line_mc", 27);
			line_mc.visible = false;
			
			lineWidth_int = _root.mediaContainer_mc[whichPhoto]._width;
			lineHeight_int = _root.mediaContainer_mc[whichPhoto]._height; 
			
			if(_root.autoPlay_bool && motion_bool == true || _root.data_arr[currentPhoto_int].photoPath_str == undefined  || photoBackgroundFixed_bool == true){
				
				line_mc.lineStyle(linethick, linecolor, linealpha);
				line_mc.moveTo(0, 0);
				line_mc.lineTo(picWidth_int, 0);
				line_mc.lineTo(picWidth_int, picHeight_int);
				line_mc.lineTo(0, picHeight_int);
				line_mc.lineTo(0, 0);
			
				line_mc._x = picX_int
				line_mc._y = picY_int
			
				line_mc.visible = true;
				
			}else{
				
				line_mc.lineStyle(linethick, linecolor, linealpha);
				line_mc.moveTo(0, 0);
				line_mc.lineTo(lineWidth_int, 0);
				line_mc.lineTo(lineWidth_int, lineHeight_int);
				line_mc.lineTo(0, lineHeight_int);
				line_mc.lineTo(0, 0);
				
				line_mc._x = mediaContainer_mc._x
				line_mc._y = mediaContainer_mc._y
			
						
				line_mc._x += wD_int
				line_mc._y += hD_int
				
				if(transitionType_str != "assets/transitions/alpha1.swf")
				{
				
					line_mc.visible = true;
				
				}
				
			
			}
			
			
		
	
}

//-------------------------------------------------------------------

//-------------------------------------------------------------------
function next_photo()
{
	
	trace("function: next_photo()")
	
	
		
		
		
		stop_slideshow();
	
		direction_str = "next";
		
		tempCurrentPhoto_int = currentPhoto_int;
		tempCurrentPhoto_int++; 
		
		if(tempCurrentPhoto_int == totalPhotos_int){
			
			currentPhoto_int = 0
			
		
		}else{
			
			currentPhoto_int++;
		}
		
		
		//load_transition();
		get_photo("scroll");
		get_photo_info();
	
	
}

//------------------------------------------
//Checks sharing permisions for download original and larger image
//called from get_photo
//------------------------------------------
function permission_check(data) 
 { 
	 controlContainer_mc.controller_mc.extended_mc._visible = false;
	 controlContainer_mc.buttonDownload_mc._visible = false;
	 controlContainer_mc.buttonLarger_mc._visible = false;
 trace("function permision_check(data) ")

 trace(ptg.getPermission("photoDownload"))
 trace("data.P_PRIVACY: "+ data.P_PRIVACY)

 //trace(ptg.permissions_obj.PERM_PHOTO_DOWNLOAD)
 trace("downloadable? "+ ptg.checkPermission(data.P_PRIVACY, ptg.getPermission("photoDownload")))
	
	if(buttonDownload_bool != false)
	{
	
		if(ptg.checkPermission(data.P_PRIVACY, ptg.getPermission("photoDownload"))) 
		{ 
			trace("downloadable button is visible")
			
			controlContainer_mc.buttonDownload_mc._visible = true;
			//controlContainer_mc.controller_mc.extended_mc._visible = true;
					  
		}else{
			
			trace("downloadable button is not visible")
			controlContainer_mc.buttonDownload_mc._visible = false;
			//controlContainer_mc.controller_mc.extended_mc._visible = false;
			
		}
		
	}
	
	if(buttonLarger_bool != false)
	{
		if(ptg.checkPermission(data.P_PRIVACY, ptg.getPermission("photoPublic"))) 
		{
			
			//is public
			controlContainer_mc.buttonLarger_mc._visible = true;
			//controlContainer_mc.controller_mc.extended_mc._visible = true;
		}else{
			
			
			controlContainer_mc.buttonLarger_mc._visible = false;
			//controlContainer_mc.controller_mc.extended_mc._visible = false;
		}
	}
} 
//-----------------------------------
//function called from get_photo and gets the next photo
//-----------------------------------
function preload_image()
{
	trace("function: preload_image")
	
	//if(preloadAmount_int)
	this.createEmptyMovieClip("preLoad1_mc", 99999999);
	
	//_root.createEmptyMovieClip("preLoad2_mc", 334);
	
	
	
	preLoad1_mc._x = 6000;
	
	preLoad_int = currentPhoto_int;
	preLoad_int++;
	//trace("PRELOAD IMAGE "+preLoad_int)
	
	//loadMovie(serverName_str + imageDirectory_str+data_arr[preLoad_int].photoPath_str, "preLoad1_mc");
	if(preLoad_int < data_arr.length && data_arr[preLoad_int].photoPath_str != undefined)
	{
		
		//trace("PRELOAD THIS IMAGE: "+preLoad_int)
		loadMovie(ptg.customImageLock(data_arr[preLoad_int],picWidth_int,picHeight_int), "preLoad1_mc");
	}


	/*
	
	if(autoPlay_bool == true)
	{
		preLoad2_int = 2 + currentPhoto_int;
		preLoad3_int = 3 + currentPhoto_int;
		
		this.createEmptyMovieClip("preLoad"+i+"_mc", 999999999 + currentPhoto_int);
		this.createEmptyMovieClip("preLoad"+i+"_mc", 999999999 + currentPhoto_int);
		
		preLoad2_mc._x = 30;
		preLoad3_mc._x = 300;
		
		
		if(preLoad2_int < data_arr.length && data_arr[preLoad2_int].photoPath_str != undefined)
		{
			
			//trace("PRELOAD THIS IMAGE: "+preLoad_int)
			loadMovie(ptg.customImageLock(data_arr[preLoad2_int],picWidth_int,picHeight_int), "preLoad2_mc");
		}
		
		if(preLoad3_int < data_arr.length && data_arr[preLoad3_int].photoPath_str != undefined)
		{
			
			//trace("PRELOAD THIS IMAGE: "+preLoad_int)
			loadMovie(ptg.customImageLock(data_arr[preLoad3_int],picWidth_int,picHeight_int), "preLoad3_mc");
		}
	
	}
	
	*/
	
	//preLoad_int++;
	//loadMovie(data_arr[preLoad_int].photoPath_str, "preLoad2_mc");
}
//------------------------------------------------------------
//this is called the start 
//status: not being used
//------------------------------------------------------------

function preload_thumbnails()
{
	trace("function: preload_thumbnails()")
	this.createEmptyMovieClip("preLoad2_mc", 99999998);
	preLoad2_mc._x= 6000;
	
	function load_thumbnails_now()
	{
		//trace("PRELOAD THUMBNAILS NOW")
		infoLoaded=  preLoad2_mc.getBytesLoaded();
		infoTotal =  preLoad2_mc.getBytesTotal();
		percentage = Math.floor(infoLoaded/infoTotal*100);
		
		
		
		if (percentage>=100 || data_arr[t].thumbnailPath_str == undefined) {
			//trace("data_arr.length "+data_arr.length)
			if(t<data_arr.length){
				
				loadMovie(serverName_str + thumbnailDirectory_str + data_arr[t].thumbnailPath_str, "preLoad2_mc");
				t++;
				//trace(t+" :-- ")
			}else{
				
				clearInterval(intervalID_thumbnailLoad);
			}
		}
			
		//}
	}
	t=0
	
	 intervalID_thumbnailLoad = setInterval(load_thumbnails_now,300);
	
}

//-------------------------------------------------------------------

//-------------------------------------------------------------------
function previous_photo()
{
	trace("function: previous_photo()")
	
		stop_slideshow();
			
		direction_str = "previous";
		
		if(currentPhoto_int>0){
			
			currentPhoto_int--;
		
		}else{
			
			currentPhoto_int = totalPhotos_int;
			currentPhoto_int--;
		}
		
		
		get_photo("scroll");
		get_photo_info();
	

	
}




function reset_photo()
{
	trace("function: reset_photo()")
		mediaContainer_mc.photo_mc.swapDepths(0);
		removeMovieClip(mediaContainer_mc.photo_mc);
		
}
//---------------------------------------

//---------------------------------------
function set_title_format()
{
	
	trace("function: set_title_format()")
	if(idMatch_bool == true && loggedIn_bool == true)
	{
		
		
		mediaContainer_mc.photo_mc.textBox_mc.selectable =  true;
		//mediaContainer_mc.photo_mc.textBox_mc.type =  "input";
		
		mediaContainer_mc.photo_mc.textBox_mc.onSetFocus = function()
		//mediaContainer_mc.photo_mc.textBox_mc.onChanged = function(changedField)
		{
	
			//trace("the field has changed"+mediaContainer_mc.photo_mc.textBox_mc.text);
			//text_str = mediaContainer_mc.photo_mc.textBox_mc.text;
			
			//change_text(text_str)
			editTitleFrame();
		}
		
		
		
		
	}
	
	//should give the title some buffer room from
	//mediaContainer_mc.photo_mc.textBox_mc._width = picWidth_int;
	//buffer_int = Math.floor(picWidth_int*.03);
	//mediaContainer_mc.photo_mc.textBox_mc._width -= buffer_int;
	
	titleFrameFormat = new TextFormat();
	titleFrameFormat.color = data_arr[currentPhoto_int].mainColor_str;
	titleFrameFormat.align = "center";
	titleFrameFormat.leftMargin = 10;
 	titleFrameFormat.rightMargin = 10;
	//titleFrameFormat.bullet = data_arr[currentPhoto_int].bullet_bool;
	
	//titleFrameFormat.url = data_arr[currentPhoto_int].link_str;
	//titleFrameFormat.target = data_arr[currentPhoto_int].target_str;
 

	if(data_arr[currentPhoto_int].fontSize_int != undefined)
	{
		trace("set_title_format:  "+data_arr[currentPhoto_int].fontSize_int)
		titleFrameFormat.size = data_arr[currentPhoto_int].fontSize_int;
	}
	
	if(data_arr[currentPhoto_int].align_str != undefined)
	{
		titleFrameFormat.align = data_arr[currentPhoto_int].align_str
		
	}
	trace(""+mediaContainer_mc.photo_mc.textBox_mc.text)
	
	mediaContainer_mc.photo_mc.textBox_mc.text = data_arr[currentPhoto_int].title_str;
	mediaContainer_mc.photo2_mc.textBox_mc.text = data_arr[currentPhoto_int].title_str;
	
	mediaContainer_mc.photo_mc.textBox_mc.setTextFormat(titleFrameFormat);
	mediaContainer_mc.photo2_mc.textBox_mc.setTextFormat(titleFrameFormat);
	//mediaContainer_mc.photo_mc.textBox_mc.background = 0xffffff;
	
	mediaContainer_mc.photo_mc.textBox_mc.autoSize = true;
	mediaContainer_mc.photo_mc.textBox_mc.multiline = true;
	mediaContainer_mc.photo_mc.textBox_mc.wordWrap =  true;

	targetHeight_int = mediaContainer_mc.photo_mc._height;
	targetWidth_int = mediaContainer_mc.photo_mc._width;
	
	buffer_int = Math.floor(picHeight_int*.05);
	mediaContainer_mc.photo_mc.textBox_mc._height -= buffer_int;
	
	sourceRatio = sourceWidth_int/sourceHeight_int
	targetRatio = targetWidth_int/targetHeight_int
	
	
}
//hotspots are shown when image has been rolled over
function show_hotSpots()
{
	trace("show_hotSpots(): ")
	buttonRolledOver_bool = true;
	
	
	trace("hotSpotRollOver_bool: "+hotSpotRollOver_bool)
	if(hotSpotRollOver_bool == true)
			{
				
				for(i=0;i<data_arr[currentPhoto_int].hotSpot_arr.length;i++)
				{
			
					mediaContainer_mc.photo_mc["hotSpot"+i]._visible = true;
					
				}
				
			}
			
	
	
}
//---------------------------------------------

//---------------------------------------------
function show_index()
{
	
	trace("")
	trace("")
	trace("---------------------------------------------------------------")
	trace("function show_index():  location slide_show_prototype.swf  ")
	trace("")
	trace("description:  opens index, removing index_mc, stops slideshow playing, creates a background image for the index if necessary or loads one in ")
	trace("---------------------------------------------------------------")
	stop_slideshow();
	
	controlContainer_mc.buttonLarger_mc._visible = false;
	controlContainer_mc.buttonDownload_mc._visible = false;
	
	if(editMode_bool == false && showingControlOptions_bool == true)
	{
			
				controlContainer_mc.gotoAndPlay("close");
				
	}
	
	
	if(indexOpen_bool == true){
		
		close_index()
	
	}else{
		
		this.createEmptyMovieClip("index_mc", 110)
		this.createEmptyMovieClip("indexBG_mc", 109)
		loadMovie(mcDirectory_str + indexPath_str,"index_mc");
		
		
		indexBG_mc.beginFill(backgroundColor_str, 100);
		
		//movieHeight = movieHeight_int-90;
		//draw the next lines, each starting from the last point the pen was at
		indexBG_mc.lineTo(movieWidth_int,0);
		indexBG_mc.lineTo(movieWidth_int,movieHeight_int);
		indexBG_mc.lineTo(0,movieHeight_int);
		indexBG_mc.lineTo(0,0);
		
		indexBG_mc.endFill();
				
		indexOpen_bool = true;
		
		if(mc_arr[0].indexBackgroundPhoto_str != undefined)
		{
			trace("---->-loadBackgroundIndex")
			loadMovie(mcDirectory_str + mc_arr[0].indexBackgroundPhoto_str, "indexBG_mc")
			trace("---->- "+mcDirectory_str + mc_arr[0].indexBackgroundPhoto_str)
			trace("---->- "+mcDirectory_str )
		}
	}
	
	

	
}
//-----------------------------------
//-----------------------------------
function track_photo()
{
	//trace("photoKey_str: "+data_arr[currentPhoto_int].photoKey_str + "user_id: "+user_id)
	if(data_arr[currentPhoto_int].photoKey_str != undefined)
	{
		ptg.logPhotoView(data_arr[currentPhoto_int].photoKey_str);
	}
	
}








	 
		

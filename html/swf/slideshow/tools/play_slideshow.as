/*

function begin_slideshow()
function checkMusicType()
function end_transition()
function getMusicSWF()
function get_transition_now() 
function image_grow()
function image_shrink()
function load_transition()
function mp3_load()
function play_slideshow()
function previewMusic()
function reset_zoom(type)
function showEndFrame()
function stop_slideshow()
function toggle_audio()
function volume_down()
function volume_up()

*/

var zoomAmountWidthTotal = 0;
var zoomAmountHeightTotal = 0;

var global_sound:Sound = new Sound();
var currentVolume_int=100;
var audioOn_bool = true

//--------------------------------------------
firstPass_bool = true
//--------------------------------------------

function begin_slideshow()
{
		
	if(photoMask_bool == false)
	{
		maskStart_bool = false;
		
	}else{
		
		maskStart_bool = true;
	}
	
	//prep for MOTION
	if(photoMask_bool == false && motion_bool == true)
	{
		
		
		trace("MOTION PREP")
		//trace(mc_arr[0].themeName_str)
		
		imageBackground_mc._visible = true;
		imageBackground_mc._alpha = 100
		imageBackground_mc._width = mc_arr[0].picWidth_int
		imageBackground_mc._x = mc_arr[0].x_int
		imageBackground_mc._y = mc_arr[0].y_int
		imageBackground_mc._height = mc_arr[0].picHeight_int
		
		
		
		if( mc_arr[0].photoBackgroundFixed_bool== false )
		{
			startBGFixed_bool = false;
			//photoBackgroundFixed_bool = false;
			
			mc_arr[0].photoBackgroundFixed_bool = true;
			photoBackgroundFixed_bool = true;
			if(mc_arr[0].photoBorder_bool !=false)
			{
				makeBorder(picWidth_int, picHeight_int, currentPhoto_int) 
			}
		}else{
			
			startBGFixed_bool = true;
			
		}
	
		//mediaContainer_mc.setMask(mediaContainerMask_str);
		if(mediaContainerMask_str == "mask_mc2")
		{
		mediaContainer_mc.setMask(mask_mc2);
		
		}
		trace("mediaMsk"+mediaContainerMask_str)
	}
	
	trace("function: begin_slideshow()")
	if(startEditMode_int != 1)
	{
		//while previewing the slideshow turn off edit mode
		turn_off_edit_mode();
	}
	
	//remove tools: timeline, add photo, add title etc
	removeAllTools();
	removeMovieClip(endFrame_mc)
	
	//when mc_arr is loaded/config complete
	clearInterval(movieStartAutoPlay_id)
	
	//play 
	controlContainer_mc.buttonPlay_mc.icon_mc.gotoAndStop("show_stop");
	
	//if the current frame is the last from in the slideshow... go back to the beginning
	if(currentPhoto_int == data_arr.length-1)
	{
		currentPhoto_int = 0;
		get_photo_info();
		get_photo("scroll")
		
	}
	trace("function: begin_slideshow()")
	//load mp3
	autoPlay_bool=true;
	//mp3_load();
	if(musicPath_str.length > 3)
	{
		
		
		//check and see if the music is an swf or mp3 (mp3 layer)
		checkMusicType();
	
		
		//fade music in
		function play_audio2_now() { 
			trace("check audio now")
			
			
			if(musicPlaying_bool == true)
			{
				preloader_mc._visible = false;
				
				play_slideshow();
				clearInterval(intervalID_play_audio2)
				musicPlaying_bool = false;
			}else if(musicType_str == "swf")
			{
				
				clearInterval(intervalID_play_audio2)
				
			}
			
	}
	
	//intervalID_play_audio2 = setInterval(play_audio2_now, 200)
	
	}else{
		
		
		play_slideshow();
		
	}
}
//---------------------------------

//---------------------------------
function checkMusicType()
{
	trace("function checkMusicType(): ")

	sourceLength=musicPath_str.length;
		mNum=sourceLength-3;
		musicType_str = musicPath_str.substring(mNum,musicPath_str.length);
		
		
		
	//check and see if it is an mp3 or an swf
	if(musicType_str == "swf" ){
		trace("music = "+musicType_str )
		
		getMusicSWF();
	
	}else{
		trace("music = "+musicType_str)
		
		mp3_load();
	}
	
	
}

//---------------------------------------------
//called from the loaded transition in mc_arr swf
//---------------------------------------------
function end_transition()
{
	trace( "function: end_transition()" )
	trace( "autoPlay_bool: "+autoPlay_bool )
	if(autoPlay_bool==true)
	{
		trace("playing: "+currentPhoto_int)
		play_slideshow();
		
	}

}
//---------------------------------

//---------------------------------
function getMusicSWF()
{
	
	trace("function  getMusicSWF(): ")
		
	//trace("tempMusicPath_str: "+tempMusicPath_str)
	//myMusicMCL.loadClip(audioDirectory_str + musicPath_str, musicSWF_mc);
	loadMovieNum(audioDirectory_str + musicPath_str, 13);

	trace("function  getMusicSWF(): ")
		
	//trace("tempMusicPath_str: "+tempMusicPath_str)
	//myMusicMCL.loadClip(audioDirectory_str + musicPath_str, musicSWF_mc);
	loadMovieNum(audioDirectory_str + musicPath_str, 13);
	trace(audioDirectory_str + musicPath_str)
	function playMusicNow()
	{
		trace("interval: playMusicNow");
		if(_level13.musicLoaded==true){
			//_level13.swf_music.start();
			if(autoPlay_bool == true || fromPreview_bool == false){
				volume_up()
				play_slideshow();
				_level13.gotoAndStop("playSong")
				_parent._parent.fromPreview_bool =  false;
				
			}else if(fromPreview_bool == true)
			{
				volume_up()
				_level13.gotoAndStop("playSong")
				_parent._parent.fromPreview_bool =  false;
				
				
			}
			clearInterval(playMusic_id);
		}
	}
	

	playMusic_id=setInterval(playMusicNow, 200)
}
//---------------------------------------------
//

//---------------------------------------------
 
 function get_transition_now() 
 { 
		trace("function: get_transition_now()")
		/*
		_infoLoaded = mediaContainer_mc.transition.getBytesLoaded();
		_infoTotal =  mediaContainer_mc.transition.getBytesTotal();
		_percentage = Math.floor(_infoLoaded/_infoTotal*100);
		
		targetHeight_int = mediaContainer_mc.transition._height;
		targetWidth_int = mediaContainer_mc.transition._width;
		
		trace("_percentage: "+ _percentage)
		if (_percentage==100) 
		{
			
				
				clearInterval(intervalID_t)
								
				transition._x = mediaContainer_mc._x
				transition._y = mediaContainer_mc._y
				transition._width = picWidth_int
				transition._height = picHeight_int
				
				transition.gotoAndPlay("out");
				
			
		}
			*/
	
}
//----------------------------------------------

//---------------------------------------------
function image_grow()
{
	//line_mc._visible =false;
	trace(mediaContainer_mc.photo_mc._height)
	trace(mediaContainer_mc.photo_mc._width)
	if(zooming_bool != true)
	{
		
		zoomStartHeight_int = mediaContainer_mc.photo_mc._height;
		zoomStartWidth_int = mediaContainer_mc.photo_mc._width;
	}
	
	zooming_bool = true;
	
	if(data_arr[currentPhoto_int].videoPath_str == undefined)
	{
		autoPlayMovement_str = "grow";
		
		currentZoomHeight_int = mediaContainer_mc.photo_mc._height;
		currentZoomWidth_int = mediaContainer_mc.photo_mc._width;
		
		
		this.onEnterFrame = function()
		{
				
				currentZoomHeight_int = mediaContainer_mc.photo_mc._height;
				currentZoomWidth_int = mediaContainer_mc.photo_mc._width;
								

				widthAmount = mediaContainer_mc.photo_mc._width * .006
				heightAmount = mediaContainer_mc.photo_mc._height * .006
					
				
				mediaContainer_mc.photo_mc._width += widthAmount;
				mediaContainer_mc.photo_mc._height += heightAmount;
				
				//--- new code --
				//zoomWidthDifference_int = mediaContainer_mc.photo_mc._width - currentZoomWidth_int;
				//zoomHeightDifference_int = mediaContainer_mc.photo_mc._height - currentZoomHeight_int; 
				
				zoomAmountWidthTotal += widthAmount;
				zoomAmountHeightTotal += heightAmount;
				
				//trace(mediaContainer_mc.photo_mc._width);
				targetHeight_int = mediaContainer_mc.photo_mc._height;
				targetWidth_int = mediaContainer_mc.photo_mc._width;
				
				
					wd_int =  currentZoomWidth_int-targetWidth_int
					hd_int =  currentZoomHeight_int-targetHeight_int
					
					wd_int = wd_int/2;
					hd_int = hd_int/2;
					
				zoomX_int = 600
				zoomY_int = 400
				/*
				if(zoomX_int > (mc_arr[0].picWidth_int/2) && zoomY_int > (mc_arr[0].picHeight_int/2)  )
				{
					wdP_int =  currentZoomWidth_int/targetWidth_int
					hdP_int =  currentZoomHeight_int/targetHeight_int
					
				
					mediaContainer_mc.photo_mc._x -= (zoomWidthDifference_int * (zoomX_int/zoomStartWidth_int));
					mediaContainer_mc.photo_mc._y -= (zoomHeightDifference_int * (zoomY_int/zoomStartHeight_int));
				
				
				
				}else if(zoomX_int < (mc_arr[0].picWidth_int/2) && zoomY_int > (mc_arr[0].picHeight_int/2) )
				{
					
					mediaContainer_mc.photo_mc._x += zoomWidthDifference_int * (zoomX_int/zoomStartWidth_int);
					mediaContainer_mc.photo_mc._y -= zoomHeightDifference_int * (zoomY_int/zoomStartHeight_int);
				
					
				}else if(zoomX_int < (mc_arr[0].picWidth_int/2) && zoomY_int < (mc_arr[0].picHeight_int/2))
				{
					
					mediaContainer_mc.photo_mc._x += zoomWidthDifference_int * (zoomX_int/zoomStartWidth_int);
					mediaContainer_mc.photo_mc._y += zoomHeightDifference_int * (zoomY_int/zoomStartHeight_int);
				
					
				}else if(zoomX_int > (mc_arr[0].picWidth_int/2) && zoomY_int < (mc_arr[0].picHeight_int/2)){
					
					mediaContainer_mc.photo_mc._x -= zoomWidthDifference_int * (zoomX_int/zoomStartWidth_int);
					mediaContainer_mc.photo_mc._y += zoomHeightDifference_int * (zoomY_int/zoomStartHeight_int);
					
					
				}else
				{*/
				  mediaContainer_mc.photo_mc._x += wd_int;
				  mediaContainer_mc.photo_mc._y += hd_int;
				
				//}
				zoomAmount_int++;
					
				
			}
			
		
		}


			
}

//--------------------------------------

//--------------------------------------

function image_shrink()
{
	
	
	if(zooming_bool != true){
		
		zoomStartHeight_int = mediaContainer_mc.photo_mc._height;
		zoomStartWidth_int = mediaContainer_mc.photo_mc._width;
	}
	
	zooming_bool = true;
	
	//line_mc._visible =false;
	if(data_arr[currentPhoto_int].videoPath_str == undefined){
	
	autoPlayMovement_str = "shrink"; 
	//
	targetHeight_int = mediaContainer_mc.photo_mc._height;
	targetWidth_int = mediaContainer_mc.photo_mc._width;
	
	originalHeight_int =  mediaContainer_mc.photo_mc._height;
	originalWidth_int = mediaContainer_mc.photo_mc._width;
	
	originalX_int =  mediaContainer_mc.photo_mc._x;
	originalY_int = mediaContainer_mc.photo_mc._y;
	//trace("")
	//trace(targetHeight_int )
	//trace(targetWidth_int )
	mediaContainer_mc.photo_mc._width = mediaContainer_mc.photo_mc._width * 2
	mediaContainer_mc.photo_mc._height = mediaContainer_mc.photo_mc._height * 2
	
	///trace(mediaContainer_mc.photo_mc._width +" mediaContainer_mc.photo_mc._width")
	//trace(mediaContainer_mc.photo_mc._height +" mediaContainer_mc.photo_mc._height ")
	
	currentZoomHeight_int = mediaContainer_mc.photo_mc._height;
	currentZoomWidth_int = mediaContainer_mc.photo_mc._width;
					
	wd_int =  currentZoomWidth_int-targetWidth_int
	hd_int =  currentZoomHeight_int-targetHeight_int
	
	//trace(wd_int +" wd_int ")
	//trace(hd_int +" hd_int ")
	
	//howFar = targetWidth_int - currentZoomWidth_int
	zoomAmountWidthTotal =wd_int;
	zoomAmountHeightTotal =hd_int;
	
	if(currentPhoto_int == tempLength_int)
	{
		tempDelay_int = delay_int;
		
	}else{
		
		tempDelay_int = delay_int + 1000;
	}
	
	zoomAmount_int =(tempDelay_int/1000)*30;
	subtractionAmountW_int = (tempDelay_int/1000)*30
	subtractionAmountW_int = wd_int/subtractionAmountW_int
	
	subtractionAmountH_int = (tempDelay_int/1000)*30
	subtractionAmountH_int = hd_int/subtractionAmountH_int
	
	//trace(subtractionAmountW_int+" subtractionAmount_int")
	///trace(subtractionAmountW_int+" subtractionAmount_int")
	//trace(subtractionAmountH_int+" subtractionAmountH_int")
	//trace(subtractionAmountH_int+" subtractionAmountH_int")
	
	wd_int = wd_int/2;
	hd_int = hd_int/2;
			
	mediaContainer_mc.photo_mc._x -= wd_int;
	mediaContainer_mc.photo_mc._y -= hd_int;
	
	//trace(wd_int +" wd_int ")
	//trace(hd_int +" hd_int ")
	
	//this.onEnterFrame = function() {
			shrink_mc.gotoAndPlay("loop")
		//}
		
	}
			
}

//------------------------------------------------
//function is meant to load unique transitions per photo
//------------------------------------------------
function load_transition()
{
	trace("function: load_transition()")
	/*
	//unique transition
	if(data_arr[currentPhoto_int].transition_str != undefined )
	//if(data_arr[currentPhoto_int].transition_str != undefined || data_arr[previousPhoto_int].title_str != undefined)
	{
		
		if(data_arr[previousPhoto_int].title_str != undefined || data_arr[previousPhoto_int].photoPath_str == "blank" )
		{
			
			transitionPath_str = "assets/transitions/color_fade.swf";
			
		}else{
			
			transitionPath_str = data_arr[currentPhoto_int].transition_str;
		}
		trace("NEW TRANSITION WAS LOADED");
		transitionOverRide_bool = true;
		
		trace("Photo Transition: "+transitionPath_str)
		this.createEmptyMovieClip("transition", 17);
		
		loadMovie(transitionPath_str, transition)
		intervalID_t = setInterval(get_transition_now, 100);
		
	}else{
	*/
		
	 trace("PLAY THE TRANSITION!");
	 transitionOverRide_bool = false;
	
	
	transition_mc.gotoAndPlay("out");
	transitionAnimate_mc.gotoAndPlay("play");
	
}

//--------------------------------------------

//--------------------------------------------
function mp3_load()
{
	
	_root.createTextField("percentageBox_mc",42000,picX_int+50,picY_int+40,200, 40);
	_root.percentageBox_mc.selectable = false;
		//format text box
	percentageFormat = new TextFormat();
	percentageFormat.color= highlightColor_str;
	percentageFormat.font = "Arial"
	percentageFormat.size = 10
	
		//atach preloader circle animation
	attachMovie("preloader","preloader_mc",3332001)
	preloader_mc._visible = false;
	preloader_mc._x = picX_int+20;
	preloader_mc._y = picY_int+50;
	preloader_mc._visible = true;
	
	percentageBox_mc.text = "Loading Mp3"
	percentageBox_mc.setTextFormat(percentageFormat);
	
	trace("function   mp3_load(): ")
	trace("musicPath_str: "+musicPath_str)
		
		music_mc = new Sound();
		
		music_mc.loadSound(mp3Directory_str + musicPath_str, true);
		music_mc.stop();
		musicStartPosition_int = controlContainer_mc.scroll_mc.flixScroll[currentPhoto_int].seconds;
		musicStartPosition_int = parseInt(musicStartPosition_int);
		trace("musicStartPosition_int: "+musicStartPosition_int)
		
	function play_mp3()
	{ 
		//percentageBox_mc.text=
		
		infoLoaded=  music_mc.getBytesLoaded();
		infoTotal =  music_mc.getBytesTotal();
		percentage = Math.floor(infoLoaded/infoTotal*100);
		percentageBox_mc.text= "Loading Mp3:  "+ percentage + "%";
		percentageBox_mc.setTextFormat(percentageFormat);
		//autoPlay_bool == true
		controlContainer_mc.buttonNull_mc._visible = true;
		controlContainer_mc._alpha = 50;
		mp3Loading_bool = true;
		if(percentage > 50 )
		{
			//trace("??????????????? is the mp3 playing?");
			mp3Loading_bool = false;
			controlContainer_mc.buttonNull_mc._visible = false
			controlContainer_mc._alpha = 100;
			clearInterval(intervalID_mp3)
			preloader_mc.swapDepths(0)
			removeMovieClip(percentageBox_mc);
			removeMovieClip(preloader_mc)
			//musicPlaying_bool = true;
			
			if(fromPreview_bool == true)
			{
				music_mc.start()
				fromPreview_bool = false;
			}else{
				
				play_slideshow();
				music_mc.start(musicStartPosition_int);
				
			}
			volume_up();
			//music_mc.start();
			
		}
		
		
	}
	//
	intervalID_mp3 = setInterval(play_mp3, 100)
		
		
	trace(musicStartPosition_int)
		
		
	
	


	
	
}

//--------------------------------------------

//--------------------------------------------
function play_slideshow()
{
	trace("-----------------------------------")
	trace("function: play_slideshow()")
	controlContainer_mc.isPlaying_mc.gotoAndPlay(2);
	
	
	delay_int = data_arr[currentPhoto_int].delay_int
	
	function play_slideshow_now()
	{ 
		trace("interval:  play_slideshow_now")
		//autoPlay_bool = true
		
		direction_str = "next";
	
		tempCurrentPhoto_int = currentPhoto_int;
		tempCurrentPhoto_int++; 
	
	
	trace("?????????????loop_bool: "+loop_bool)
		if(tempCurrentPhoto_int == totalPhotos_int  &&  loop_bool == true){
				
				
				currentPhoto_int = 0
		
	
		}else if((loop_bool == false ||  loop_bool == undefined )&& tempCurrentPhoto_int == totalPhotos_int){
			
			
			trace("LAST FRAME HAS PLAYED" + mc_arr[0].slideshowLink_str)
			clearInterval(intervalID_play)
			
			if(slideshowLink_str.length > 1  &&  startEditMode_int == 1)
			{
				hotSpotToolBar_mc.editBG.autoLink_mc.gotoAndStop(3);
				
			}else if(slideshowLink_str.length > 2){
				
				getURL("http://www.photagious.com/slideshow?"+slideshowLink_str, "_self" );
				
			}else{
				
				showEndFrame();
			
			}
			stop_slideshow();
			
			autoPlay_bool = false;
			
		}else{
			
			currentPhoto_int++;
			
		}
	
		load_transition();
		get_photo_info();
	
		
		clearInterval(intervalID_play)
			
		
	}
	//this.createEmptyMovieClip("playSlideshow_mc",6767);
 	
	intervalID_play = setInterval(play_slideshow_now, delay_int);
	
	//playSlideshow_mc.intervalID_play = setInterval(_root.play_slideshow_now, _root.delay_int);
	
	
	
	
	
}

//--------------------------------------------

//--------------------------------------------
function previewMusic()
{
	trace("function preview_music(): ")

	checkMusicType();
}
//--------------------------------------------

//--------------------------------------------
function reset_zoom(type)
{
	
	trace("function: reset_zoom()")
	zooming_bool = false
	//if(photoMask_bool == false){
		//mediaContainer_mc.setMask(null);
	//}
	
	if(photoBorder_bool == true)
	{
		
		line_mc._visible =true;
	}
  delete this.onEnterFrame
	draggable_bool = false;
	//line_mc._visible =true;
	delete thisZoom_mc.onEnterFrame;
	buttonZoomOut_mc._visible = false;
	
	
	
	//mediaContainer_mc._width = picWidth_int
	//mediaContainer_mc._height = picHeight_int
	//trace(mediaContainer_mc._x)
	
	zoomAmount_int= 0
	zoomAmountWidthTotal = 0;
	zoomAmountHeightTotal = 0;
	
	
	
	
		
	
}
//--------------------------------------------

//--------------------------------------------
function showEndFrame()
{
	trace("function: showEndFrame()")
	
	ptg.logSlideshowViewComplete(slideshowKey_str);
	
	mediaContainer_mc.createEmptyMovieClip("endFrame_mc", 63)
	mediaContainer_mc.endFrame_mc._x = 0
	mediaContainer_mc.endFrame_mc._y = 0
	
	if(endFrame_bool != false)
	{
		loadMovie(mcDirectory_str + "assets/endFrame_4.swf", mediaContainer_mc.endFrame_mc)
		trace(mcDirectory_str + "assets/endFrame.swf")
	//removeMovieClip(line_mc)
	}else{
		
		currentPhoto_int = 0;
		get_photo_info();
		get_photo("scroll");
		
	}
	
}

//--------------------------------------------

//--------------------------------------------
function stop_slideshow()
{
	
	trace("function: stop_slideshow()")
	
	clearInterval(intervalID_play)
	
	//trace("function: stop_slideshow()")
	
	//remove mask for shrinking and growing
	if(zoomAmountWidthTotal>0)
	{
		reset_zoom();
		//mediaContainer_mc.setMask(null);
		mediaContainer_mc.photo_mc._alpha = 0;
		this[mediaContainerMask_str]._visible = false;
	}
	if(maskStart_bool== false && motion_bool == true)
	{
		
		mediaContainer_mc.setMask(null);
		this[mediaContainerMask_str]._alpha = 0;
	}
	
	
	if(startBGFixed_bool == false)
	{
		trace("change imageBackground_mc "+ imageBackground_mc._visible )
		//imageBackground_mc._visible = false;
		imageBackground_mc._alpha = 0
		
		mc_arr[0].photoBackgroundFixed_bool = false;
		photoBackgroundFixed_bool = false;
		
		if(photoShadow_bool == true)
		{	
			imageBackground_mc._alpha = 100
			configureShadow();
		}
	}else if(startBGFixed_bool == true)
	{
		
		imageBackground_mc._visible = true;
		imageBackground_mc._alpha = 100
		
		mc_arr[0].photoBackgroundFixed_bool = true;
		photoBackgroundFixed_bool = true;
		
	}
	
		
	clearInterval(endFrame_mc.waitToPlay)
	clearInterval(controlContainer_mc.actions_mc.login_mc.options_mc.waitToPlay)
	clearInterval(controlContainer_mc.buttonPlay_mc.icon_mc.waitToPlay);
	clearInterval(playMusic_id);
	
	//if(musicType_str == "swf" && autoPlay_bool == true)
	//{
		//unloadMovie(13)
			
	//}
	
	if(mediaContainer_mc.endFrame_mc._visible == true && autoPlay_bool == false){
		
		mediaContainer_mc.endFrame_mc.swapDepths(0);
		removeMovieClip(mediaContainer_mc.endFrame_mc);
	
	}
	
	controlContainer_mc.buttonPlay_mc.icon_mc.gotoAndStop(1);
			
	//stopAllSounds();
	volume_down();
		
	reset_zoom("slideshow");
		
	//change the play button to showing the pause icon
	controlContainer_mc.buttonPlay_mc.icon_mc.gotoAndStop(1);
		
	transition_mc.gotoAndStop(1);
	//transition stop playing
	mediaContainer.transition_mc.gotoAndStop(1);
	
	clearInterval(movieStartAutoPlay_id)
	clearInterval(intervalID_play);
	
	//little dot animation above/around the play button
	controlContainer_mc.isPlaying_mc.gotoAndStop(1);
	
	autoPlay_bool = false;
	
	
	
}
//--------------------------------------------

//--------------------------------------------
function toggle_audio()
{
	
	if(audioOn_bool == false)
	
	{
		//music_mc.volume(100)
		controlContainer_mc.buttonAudio_mc.button_mc.gotoAndStop(1)
		audioOn_bool = true;
		volume_up();
		theVolume = 100
		global_sound.setVolume(theVolume);
		
	
	}else{
		
		theVolume = 0
		global_sound.setVolume(theVolume);
		//music_mc.volume(0);
		
		audioOn_bool = false;
		controlContainer_mc.buttonAudio_mc.button_mc.gotoAndStop("audioOff")
	}
	
}
//--------------------------------------------

//--------------------------------------------
function volume_down()
{
		
		//set a few lines above
		if(audioOn_bool ==true)
		{
			
			
			
			trace("function:  volume_down(): ")
			delete volume_mc.onEnterFrame;
			theVolume = currentVolume_int
				
			
			
			this.createEmptyMovieClip("volume_mc", 1113);
				
			volume_mc.onEnterFrame = function()
			//function turnOff()
			{
			
			
					//trace("theVolume: "+theVolume)
					theVolume -=2;
					
					//music_mc.setVolume(theVolume);
					global_sound.setVolume(theVolume);
					
					
					if(theVolume <=0 )
					{
						//trace("DELETED VOLUME")
						global_sound.setVolume(0);
						music_mc.stop();
						//music_mc.setVolume(0);
						delete volume_mc.onEnterFrame;
						//clearInterval(volumeDown_id)
						stopAllSounds();
					}
					
				
				
			}
			//volumeDown_id = setInterval(turnOff, 50);
	
		}else{
			
			music_mc.stop();
			
		}
}
//--------------------------------------------

//--------------------------------------------
function volume_up()
{
	
	if(audioOn_bool ==true)
	{
		delete volume_mc.onEnterFrame;
		
		trace("function:  volume_up(): ")
		trace(autoPlay_bool);
		//trace("FADE -- VOLUME UP");
		//if(autoPlay_bool){
			
			//trace("FADE -- VOLUME UP");
			//trace(currentVolume_int);
			theVolume = 0;
			//music_mc.setVolume(0);
			global_sound.setVolume(0);
			this.createEmptyMovieClip("volume_mc", 1113);
			volume_mc.onEnterFrame = function()
		{
				
				trace(theVolume)
				theVolume+=2;
				//music_mc.setVolume(theVolume);
				global_sound.setVolume(theVolume);
				
				if(theVolume >=currentVolume_int){
					//trace("DELETED VOLUME")
					delete volume_mc.onEnterFrame;
					music_mc.setVolume(currentVolume_int);
					//music_mc.setVolume(100);
					global_sound.setVolume(100);
					//clearInterval(volumeUp_id)
				}
				
			
			
			}
			
	}
	
}


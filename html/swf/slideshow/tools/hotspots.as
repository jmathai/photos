/*
HOTSPOTS FUNCTION
HOT SPOT LOCATION:( container_mc ).mediaContainer_mc.photo_mc.hotSpot1
HOT SPOT EDITOR IS ( container_mc ).mediaContainer_mc.photo_mc.
Editor controls are embedded in the photo_mc.hotspot1.resize_mc

function add_highlight_photo(photoPath_str, thumbnailPath_str, photoKey_str, rotation_int, ID_int , thisWidth_int, thisHeight_int,slideshowKey_str,slideshowTitle_str,slideshowWidth_int,slideshowHeight_int ){
function add_scrapBookSpot(photoPath_str, thumbnailPath_str, photoKey_str, rotation_int, ID_int , thisWidth_int, thisHeight_int,slideshowKey_str,slideshowTitle_str,slideshowWidth_int,slideshowHeight_int )
function add_slideshowSpot(photoPath_str, thumbnailPath_str, photoKey_str, rotation_int, ID_int , thisWidth_int, thisHeight_int,slideshowKey_str,slideshowTitle_str,slideshowWidth_int,slideshowHeight_int )
function add_videoSpot(videoPath_str, screenPath_str)
function adjust_media_container()
function check_control_position()
function check_hot_spot_position(whichHotspot)
function clear_box()
function clear_hotSpot()
function create_hotspot()
function delete_hot_spot(i)
function edit_hot_spot(i)
function fade_box()
function get_hotSpot(hotSpotNum_int)
function highlightHotSpot(i)
function hotSpot_config(i)
function load_hotSpot(whichHot)
function move_to_back()
function new_hotspot(whichType_str)
function removeHighlightHotSpot(i)
function resize_hotspot(whichHotSpot, hsRatio_int)
function restore_media_container()
function show_hint_box()
function save_hotSpot(whichHS)

HOT SPOT PROPERTIES
trace("SAVED: alignment = "+data_arr[currentPhoto_int].hotSpot_arr[i].alignment_str)
trace("SAVED: alpha_int = "+data_arr[currentPhoto_int].hotSpot_arr[i].alpha_int )
trace("SAVED: bold ="+ data_arr[currentPhoto_int].hotSpot_arr[i].bold_bool)
trace("SAVED: bullet  ="+ data_arr[currentPhoto_int].hotSpot_arr[i].bullet_bool)
trace("SAVED: border_bool  ="+data_arr[currentPhoto_int].hotSpot_arr[i].border_bool);
trace("SAVED: depth_int = "+data_arr[currentPhoto_int].hotSpot_arr[i].depth_int)
trace("SAVED: draw_arr.length = "+data_arr[currentPhoto_int].hotSpot_arr[i].draw_arr.length )
trace("SAVED: height_int  = "+data_arr[currentPhoto_int].hotSpot_arr[i].height_int )
trace("SAVED: end_int = "+data_arr[currentPhoto_int].hotSpot_arr[i].end_int)
trace("SAVED: fontSize  ="+ data_arr[currentPhoto_int].hotSpot_arr[i].fontSize_int);
trace("SAVED: italic  ="+ data_arr[currentPhoto_int].hotSpot_arr[i].italic_bool);
trace("SAVED: fill  ="+data_arr[currentPhoto_int].hotSpot_arr[i].fill_bool);
trace("SAVED: fill_bool = "+data_arr[currentPhoto_int].hotSpot_arr[i].fill_bool)
trace("SAVED: link_str  ="+data_arr[currentPhoto_int].hotSpot_arr[i].link_str);
trace("SAVED: mainColor_str = "+data_arr[currentPhoto_int].hotSpot_arr[i].mainColor_str)
trace("SAVED: note_str = "+data_arr[currentPhoto_int].hotSpot_arr[i].note_str)
trace("SAVED: photoPath_str = "+data_arr[currentPhoto_int].hotSpot_arr[i].photoPath_str)
trace("SAVED: thumbnailPath_strt = "+data_arr[currentPhoto_int].hotSpot_arr[i].thumbnailPath_str)
trace("SAVED: photoKey_str = "+data_arr[currentPhoto_int].hotSpot_arr[i].photoKey_str)
trace("SAVED: photoPath_str = "+data_arr[currentPhoto_int].hotSpot_arr[i].photoPath_str)
trace("SAVED: rotation_int = "+data_arr[currentPhoto_int].hotSpot_arr[i].rotation_int)
trace("SAVED: screenShot_str = "+data_arr[currentPhoto_int].screenShot_str)
trace("SAVED: scrollBar_bool  ="+ data_arr[currentPhoto_int].hotSpot_arr[i].scrollBar_bool)
trace("SAVED: shadow  ="+ data_arr[currentPhoto_int].hotSpot_arr[i].shadow_bool);
trace("SAVED: slideshowKey_str = "+data_arr[currentPhoto_int].hotSpot_arr[i].slideshowKey_str)
trace("SAVED: slideshowTitle_str = "+data_arr[currentPhoto_int].hotSpot_arr[i].slideshowTitle_str)																				 
trace("SAVED: slideshowWidth_int = "+data_arr[currentPhoto_int].hotSpot_arr[i].slideshowWidth_int)
trace("SAVED: slideshowHeight_int = "+data_arr[currentPhoto_int].hotSpot_arr[i].slideshowHeight_int)
trace("SAVED: sourceWidth = "+data_arr[currentPhoto_int].hotSpot_arr[i].sourceWidth_int)
trace("SAVED: start_int = "+data_arr[currentPhoto_int].hotSpot_arr[i].start_int)
trace("SAVED: stroke_int = "+data_arr[currentPhoto_int].hotSpot_arr[i].stroke_int)
trace("SAVED: swfPath_str = "+data_arr[currentPhoto_int].hotSpot_arr[i].swfPath_str)
trace("SAVED: target_str  ="+data_arr[currentPhoto_int].hotSpot_arr[i].target_str);
trace("SAVED: underline  ="+ data_arr[currentPhoto_int].hotSpot_arr[i].underline_bool);
trace("SAVED: width_int  = "+data_arr[currentPhoto_int].hotSpot_arr[i].width_int )
trace("SAVED: x = "+data_arr[currentPhoto_int].hotSpot_arr[i].x_int)
trace("SAVED: y = "+data_arr[currentPhoto_int].hotSpot_arr[i].y_int)

*/

hotSpotRecord_arr =  new Array();

editMode_bool = false;

totalHotSpots_int = 0;

alert = new Sound(this);
alert.attachSound("chord");

//used only with site builder
function add_highlight_photo(photoPath_str, thumbnailPath_str, photoKey_str, rotation_int, ID_int , thisWidth_int, thisHeight_int,slideshowKey_str,slideshowTitle_str,slideshowWidth_int,slideshowHeight_int ){
	trace("function:  add_highlight_photo")
	//add_scrapBookSpot(photoPath_str,thumbnailPath_str, photoKey_str, thisWidth_int, thisHeight_int);
	delete tempHighlight_obj
		
	trace("tempHighlight_obj.photoPath_str = "+ photoPath_str)
	trace("tempHighlight_obj.thumbnailPath_str = "+thumbnailPath_str)
	trace("tempHighlight_obj.photoKey_str = "+photoKey_str)
	trace("tempHighlight_obj.rotation_int = "+rotation_int)
	trace("tempHighlight_obj.photoId_int = "+ID_int)
	trace("tempHighlight_obj.width_int = "+thisWidth_int)
	trace("tempHighlight_obj.height_int = "+thisHeight_int)

	
	tempHighlight_obj = new Object()
	tempHighlight_obj.photoPath_str = photoPath_str;
	tempHighlight_obj.thumbnailPath_str = thumbnailPath_str;
	tempHighlight_obj.photoKey_str = photoKey_str;
	tempHighlight_obj.rotation_int = rotation_int;
	tempHighlight_obj.photoId_int = ID_int;
	tempHighlight_obj.width_int = thisWidth_int;
	tempHighlight_obj.height_int = thisHeight_int;

	
	highlighter2_bool = false;
	//new_hotspot("photo");
	
	
}
//-----------------------------------------------

//-----------------------------------------------

function add_scrapBookSpot(photoPath_str, thumbnailPath_str, photoKey_str, rotation_int, ID_int , thisWidth_int, thisHeight_int,slideshowKey_str,slideshowTitle_str,slideshowWidth_int,slideshowHeight_int ){
	trace("function: add_scrapBookSpot()")
	//add_scrapBookSpot(photoPath_str,thumbnailPath_str, photoKey_str, thisWidth_int, thisHeight_int);
	delete scrapbook_arr
	scrapbook_arr= new Array();
	scrapbook_arr[0] = new Object()
	scrapbook_arr[0].photoPath_str = photoPath_str;
	scrapbook_arr[0].thumbnailPath_str = thumbnailPath_str;
	scrapbook_arr[0].photoKey_str = photoKey_str;
	scrapbook_arr[0].rotation_int = rotation_int;
	scrapbook_arr[0].photoId_int = ID_int;
	scrapbook_arr[0].width_int = thisWidth_int;
	scrapbook_arr[0].height_int = thisHeight_int;
	scrapbook_arr[0].slideshowKey_str = slideshowKey_str;
	scrapbook_arr[0].slideshowTitle_str = slideshowTitle_str;
	scrapbook_arr[0].slideshowWidth_int = slideshowWidth_int;
	scrapbook_arr[0].slideshowHeight_int = slideshowHeight_int;
	
	new_hotspot("photo");
	
	
}


function add_slideshowSpot(photoPath_str, thumbnailPath_str, photoKey_str, rotation_int, ID_int , thisWidth_int, thisHeight_int,slideshowKey_str,slideshowTitle_str,slideshowWidth_int,slideshowHeight_int ){
	
	trace("function: add_slideshowSpot()")
	//add_scrapBookSpot(photoPath_str,thumbnailPath_str, photoKey_str, thisWidth_int, thisHeight_int);
	scrapbook_arr= new Array();
	scrapbook_arr[0] = new Object()
	scrapbook_arr[0].photoPath_str = photoPath_str;
	scrapbook_arr[0].thumbnailPath_str = thumbnailPath_str;
	scrapbook_arr[0].photoKey_str = photoKey_str;
	scrapbook_arr[0].rotation_int = rotation_int;
	scrapbook_arr[0].photoId_int = ID_int;
	scrapbook_arr[0].slideshowKey_str = slideshowKey_str;
	scrapbook_arr[0].slideshowTitle_str = slideshowTitle_str;
	scrapbook_arr[0].slideshowWidth_int = slideshowWidth_int;
	scrapbook_arr[0].slideshowHeight_int = slideshowHeight_int;
	
	trace("scrapbook_arr[0].photoPath_str = "+ photoPath_str)
	trace("scrapbook_arr[0].thumbnailPath_str = "+thumbnailPath_str)
	trace("scrapbook_arr[0].photoKey_str = "+photoKey_str)
	trace("scrapbook_arr[0].rotation_int = "+rotation_int)
	trace("scrapbook_arr[0].photoId_int = "+ID_int)
	trace("scrapbook_arr[0].width_int = "+thisWidth_int)
	trace("scrapbook_arr[0].height_int = "+thisHeight_int)
	trace("scrapbook_arr[0].slideshowKey_str = "+slideshowKey_str)
	trace("scrapbook_arr[0].slideshowTitle_str = "+slideshowTitle_str)
	trace("scrapbook_arr[0].slideshowWidth_int = "+slideshowWidth_int)
	trace("scrapbook_arr[0].slideshowHeight_int = "+slideshowHeight_int)
	
	new_hotspot("slideshow");
	
	
}


function add_videoSpot(videoPath_str, screenPath_str){
	
	trace("function: add_videoSpot()" + videoPath_str + "  screenPath_str: "+ screenPath_str)
	
	scrapbook_arr= new Array();
	scrapbook_arr[0] = new Object()
	scrapbook_arr[0].link_str = videoPath_str;
	scrapbook_arr[0].screenShot_str = screenPath_str;
	
	
	
	///tmp = new Object();
		//tmp.videoPath_str = video.V_PATH
		//tmp.photoId_int = video.V_ID
		//tmp.photoKey_str = video.V_KEY
		//tmp.name_str = video.V_NAME
		//tmp.thumbnailPath_str = video.V_SCREEN_75x75;
		//tmp.wide_str  = video.V_SCREEN_115x50;
		//tmp.medium_str = video.V_SCREEN_150x100;
		//tmp.large_str = video.V_SCREEN_400x300;
		//tmp.delay_int = 5000;
		
	//searchResult_arr.push(tmp);
	trace("scrapbook_arr[0].photoPath_str = "+ photoPath_str)
	trace("scrapbook_arr[0].thumbnailPath_str = "+thumbnailPath_str)
	trace("scrapbook_arr[0].photoKey_str = "+photoKey_str)
	trace("scrapbook_arr[0].rotation_int = "+rotation_int)
	trace("scrapbook_arr[0].photoId_int = "+ID_int)
	trace("scrapbook_arr[0].width_int = "+thisWidth_int)
	trace("scrapbook_arr[0].height_int = "+thisHeight_int)
	trace("scrapbook_arr[0].slideshowKey_str = "+slideshowKey_str)
	trace("scrapbook_arr[0].slideshowTitle_str = "+slideshowTitle_str)
	trace("scrapbook_arr[0].slideshowWidth_int = "+slideshowWidth_int)
	trace("scrapbook_arr[0].slideshowHeight_int = "+slideshowHeight_int)
	
	new_hotspot("video");
		
}

function adjust_media_container()
{
		line_mc._visible = false;
		
		if(hotSpotPath_str == "html_text.swf")
		{
			navContainer_mc._visible = false;
		}
		
		if(photoMask_bool == true)
		{
			
			border_mc._visible = false;
			mediaContainer_mc.setMask(null);
			this[mediaContainerMask_str]._visible = false;
			this[mediaContainerMask_str]._alpha = 0;
			
			
			
			
		}else{
			
			
			this[mediaContainerMask_str]._alpha = 0;
			
			
		}
		
		detail_mc._visible= false;
		mediaContainer_mc.photo_mc.createEmptyMovieClip("temp_mc", 1)
		mediaContainer_mc.photo_mc.temp_mc.beginFill("0xffffff", 20);
			
		mediaContainer_mc.photo_mc.temp_mc.lineTo(currentPhotoWidth_int,0);
		mediaContainer_mc.photo_mc.temp_mc.lineTo(currentPhotoWidth_int,currentPhotoHeight_int);
		mediaContainer_mc.photo_mc.temp_mc.lineTo(0,currentPhotoHeight_int);
		mediaContainer_mc.photo_mc.temp_mc.lineTo(0,0);
			
		mediaContainer_mc.photo_mc.temp_mc.endFill();
		
		
}


//-----------------------------------------------

//-----------------------------------------------
function check_control_position(){
	
		variableWidth_int = hotSpotControl_mc._width;
		variableWidth_int = mediaContainer_mc.photo_mc._width - variableWidth_int ;
		variableWidth_int-=10
		//trace("mediaContainer_mc.photo_mc._width : "+mediaContainer_mc.photo_mc._width );
		//trace("variableWidth_int: "+variableWidth_int);
		
		variableHeight_int = hotSpotControl_mc._height;
		variableHeight_int = mediaContainer_mc.photo_mc._height - variableHeight_int ;
		variableHeight_int -= 10;
		
		variableWidthResize_int = hotspotControl_mc._x
		variableWidthResize_int = mediaContainer_mc.photo_mc._width  - variableWidthResize_int;
		
		variableWidthResize_int-=10;
		
		variableHeightResize_int = hotspotControl_mc._y
		variableHeightResize_int = mediaContainer_mc.photo_mc._height  - variableHeightResize_int;
		
		variableHeightResize_int-=10;
		trace("variableHeight_int: "+variableHeight_int);
		trace("variableWidthResize_int: "+variableWidthResize_int);
			
}

//-----------------------------------------------

//-----------------------------------------------
function check_hot_spot_position(whichHotspot){
	
	if(data_arr[currentPhoto_int].photoPath_str == undefined || data_arr[currentPhoto_int].photoPath_str  == "blank" )
	{
		
		currentPhotoWidth_int = picWidth_int
		currentPhotoHeight_int = picHeight_int
		
	}
	
				
		variableWidth_int = mediaContainer_mc.photo_mc["hotSpot"+whichHotspot].graphic_mc._width;
		
		variableWidth_int = currentPhotoWidth_int - variableWidth_int ;
		
		if(hotSpotPath_str == "html_text.swf")
		{
			variableHeight_int =( mediaContainer_mc.photo_mc["hotSpot"+whichHotspot].graphic_mc._height)-mediaContainer_mc.photo_mc["hotSpot"+whichHotspot].graphic_mc.com.target_mc._height;
		}else{
			variableHeight_int = mediaContainer_mc.photo_mc["hotSpot"+whichHotspot].graphic_mc._height;
			
		}
		
		variableHeight_int = currentPhotoHeight_int - variableHeight_int ;
		
		variableWidthResize_int = mediaContainer_mc.photo_mc["hotSpot"+whichHotspot]._x
		variableWidthResize_int = currentPhotoWidth_int - variableWidthResize_int;
		
		variableHeightResize_int = mediaContainer_mc.photo_mc["hotSpot"+whichHotspot]._y
		variableHeightResize_int = currentPhotoHeight_int - variableHeightResize_int;
		
			
	trace("")
	trace("")
	trace("currentPhotoWidth_int: "+currentPhotoWidth_int)
	trace("currentPhotoHeight_int: "+currentPhotoHeight_int)
			
	trace("currentHotSpot_str: "+ whichHotspot)
	trace("variableWidth_int: "+variableWidth_int)
	trace("variableHeight_int: "+variableHeight_int)
	trace("variableWidthResize_int:"+variableWidthResize_int)
	trace("variableHeightResize_int: "+variableHeightResize_int)
	trace("")
	trace("")
}


function clear_box(){
	
	trace("function: clear_box()")
	function clear_now()
	{ 
					trace("clear now");
					//removeMovieClip(mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].line_mc);
					
					clearInterval(ID_clear)
					fade_box();
					
					
					
						
	}
					

			
			 ID_clear = setInterval(clear_now,500);
			 
			
	
}
//------------------------------------

//------------------------------------
function create_hotspot(){
	
	stop_slideshow();
	trace("function: create_hotspot()")
	
	removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
	
	hotSpotsCreated_int++;
	
	//create white over lay
	adjust_media_container();
	
	//close index
	close_index();
	
	trace("")
	trace("~~~~~~~~~~~~~~~~~^^^^^^^^ CREATE NEW HOT SPOT  ^^^^^^^^^^^^^^^~~~~~~~~~~~~~~~~~~~~~~``")
	trace("totalHotSpots_int: "+totalHotSpots_int)
	
	//show the edit controls for the hot spots
	editMode_bool= true;
	
	if(data_arr[currentPhoto_int].hotSpot_arr.length == undefined)
	{
		
		trace("FIRST HOTSPOT ON THIS PICTURE")
		//make new hotSpot array if it is undefined
		data_arr[currentPhoto_int].hotSpot_arr = new Array();
		
		currentHotSpot_int = 0;
		//totalHotSpots_int++;
	}else{
		

		totalHotSpots_int++;
		currentHotSpot_int = totalHotSpots_int;
		
		
	}
	
	hotSpotRecord_arr.push(currentHotSpot_int)
	trace(hotSpotRecord_arr)
	trace("currentHotSpot_int: "+currentHotSpot_int)
	currentHotSpot_str = ["hotSpot"+currentHotSpot_int];
	editingThisHotSpot_int = currentHotSpot_int;
	
	newDepth_int = mediaContainer_mc.photo_mc.getNextHighestDepth(); 
	trace("newDepth_int: "+newDepth_int)
	
	
	mediaContainer_mc.photo_mc.createEmptyMovieClip("hotSpot"+currentHotSpot_int, newDepth_int)
	
	//also loaded in from 'hot spots || LOAD MOVIE || HS CONFIG' layer
	loadMovie(hotSpotDirectory_str+"edit8.swf", mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int])
	/*
	if(version_str == "LOCAL")
			{
				loadMovie(hotSpotDirectory_str+"edit.swf", mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int])
				
			}else{
				
				loadMovie(hotSpotDirectory_str+"edit.swf"+"?timestamp="+timestamp, mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int])
				//loadMovie(toolsDirectory_str+"add_graphic.swf"+"?timestamp="+timestamp, addGraphic_mc);
			}
			*/
	
	function load_hotspot_now()
	{ 
	
		//loading._visible = true;
		
		infoLoaded = mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].getBytesLoaded();
		infoTotal = mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].getBytesTotal();
		percentage = Math.floor(infoLoaded/infoTotal*100);
		
		if (percentage>=100) {
			
			//right_click(currentHotSpot_int);
			
			mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int]._x += 50;
			mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int]._y += 50;
			check_hot_spot_position();
			
			hotSpotsLoadedDone_bool = true;
			
			clearInterval(intervalID_hotspot)
			
			

		}

			
	}
	 intervalID_hotspot = setInterval(load_hotspot_now,100);
trace("~~~~~~~~~~~~~~~~~^^^^^^^^ CREATE NEW HOT SPOT  END ^^^^^^^^^^^^^^^~~~~~~~~~~~~~~~~~~~~~~``")
}


//-----------------------------------------------

//-----------------------------------------------
function clear_hotSpot(){
	trace("function: clear_hotSpot()")
	totalHotSpots_int=0;
	
	
	delete hotSpotRecord_arr
	hotSpotRecord_arr =  new Array();
	//data_arr[previousPhoto_int].hotSpot_arr.length
	removeMovieClip(createHotSpot_mc);
	delete createHotSpot_mc.onEnterFrame;
	clearInterval(intervalID_hotspot);
	removeMovieClip(mediaContainer_mc.blank_mc)
	clearInterval(intervalID_centerPhoto)
	clearInterval(intervalID);
	//delete hotSpot clear setInternval
	clearInterval(ID_clear)
	clearInterval(intervalID_loadBG)
	
	editMode_bool = false;
	delete createHotSpot_mc.onEnterFrame;
	
	if(data_arr[previousPhoto_int].hotSpot_arr.length != undefined)
	{
		for(i=0; i<hotSpotsCreated_int; i++){
			//hotSpotsCreated_int
			//clears the animatation interval for the drawing hotspot
			clearInterval(mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.intervalID_animate)
			clearInterval(mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.intervalID_hotspot);
			clearInterval(mediaContainer_mc.photo_mc["hotSpot"+i].intervalID_hotspot);
			clearInterval(mediaContainer_mc.photo_mc["hotSpot"+i].intervalID_removeHotSpot)
			clearInterval(mediaContainer_mc.photo_mc["hotSpot"+i].intervalID_showHotSpot)
			clearInterval(mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.interval_zoomSpot)
			clearInterval(mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.textGrow_id)
			
			delete mediaContainer_mc.photo_mc["hotSpot"+i].tempMovie.onEnterFrame;
			mediaContainer_mc.photo_mc["hotSpot"+i].swapDepths(0);
			mediaContainer_mc.photo_mc["hotSpot"+i].removeMovieClip();
			
		}
	
	}
	//from load photo...

	

	
	hotSpotsCreated_int = 0
	
}
//-----------------------------------------------

//-----------------------------------------------
function delete_hot_spot(i){
	
	delete scrapbook_arr
	scrapbook_arr= new Array();
	
	removeMovieClip(mediaContainer_mc.photo_mc["hotSpot"+whichHS].arrowButtons_mc)
	restore_media_container();
		
	//set imageButton behavior (button behavior is cleared by the hotspot rollover
	changeButton();
		trace("");
	trace("**---------- DELETE ------------**");
	trace("length: "+data_arr[currentPhoto_int].hotSpot_arr.length)
	
	trace("i: "+i)//currentHotSpot_int = i;
	
	trace(hotSpotRecord_arr)
	for(d=0; d<data_arr[currentPhoto_int].hotSpot_arr.length; d++){
	
		if(hotSpotRecord_arr[d] == i)
		{
			removeThis_int = d;
			currentHotSpot_int = hotSpotRecord_arr[d];
			trace("delete_  "+currentHotSpot_int);
			break;
		}
	
	}
	
	mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].swapDepths(0);
	mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].removeMovieClip();
	
	data_arr[currentPhoto_int].hotSpot_arr.splice(removeThis_int,1);
	hotSpotRecord_arr.splice(removeThis_int, 1);	
	trace(hotSpotRecord_arr)
	
	if(data_arr[currentPhoto_int].hotSpot_arr.length == 0)
	{
		

		delete data_arr[currentPhoto_int].hotSpot_arr;
		
	}
	trace("length: "+data_arr[currentPhoto_int].hotSpot_arr.length)
	trace("**----------------------**");
	trace("");
	editMode_bool= false;
	get_photo("scroll")
	
}

//-----------------------------------------------

//-----------------------------------------------
function edit_hot_spot(i){
	trace("")
	trace("----------------- EDIT THIS HOTSPOT -----------------")
	
	stop_slideshow();
	trace("hotSpotsLoadedDone_bool: "+ hotSpotsLoadedDone_bool)
	if(editMode_bool == false && loggedIn_bool==true  && idMatch_bool == true && hotSpotsLoadedDone_bool == true)
	
	{
		
		
		hotSpotsCreated_int++  //used to clear out set inetervals
		adjust_media_container();//changes the media container to hide the border and the detail_mc overlay if present
		
		mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.textBox_mc.selectable = true;
		
		currentHotSpot_int = i;
		
		mediaContainer_mc.photo_mc["hotSpot"+i].gotoAndStop("edit");
		
		newDepth_int = mediaContainer_mc.photo_mc.getNextHighestDepth(); 
		trace("newDepth_int: "+newDepth_int)
		//newDepth_int++;
		
		mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].swapDepths(newDepth_int);
		
		trace(mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].getDepth())
		
		temp_str = data_arr[currentPhoto_int].hotSpot_arr[i].swfPath_str;
		//trace(temp_str)
		
		temp_int = temp_str.length
		temp_int = temp_int - 4;
		hotSpotType_str = temp_str.substring(0,temp_int);
		hotSpotPath_str = data_arr[currentPhoto_int].hotSpot_arr[i].swfPath_str;
		
		//trace("i: "+i);
		//let's save function know that this is not a new hotSpot && allows the depth to change
		thisEdit_bool = true;
		
		mediaContainer_mc.photo_mc["hotSpot"+i].edit_mc._visible = false;
		mediaContainer_mc.photo_mc["hotSpot"+i].grahic_mc.textBox_mc.selectable = true;
		mediaContainer_mc.photo_mc["hotSpot"+i].save_mc._visible= true;
		mediaContainer_mc.photo_mc["hotSpot"+i].resize_mc._visible= true;
		mediaContainer_mc.photo_mc["hotSpot"+i].drag_mc._visible= true;
		
		//this function called for the controls in the html advanced editor
		mediaContainer_mc.photo_mc["hotSpot"+i].showHTMLControls();
		
		//edit mode is for hotspots not for edit mode
		editMode_bool = true;
		editingThisHotSpot_int = i;
		
		mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.canvas_mc._visible = true;
		mediaContainer_mc.photo_mc["hotSpot"+i].resize_mc.check_controls();
		
		removeMovieClip(mediaContainer_mc.photo_mc["hotSpot"+i].drawing_mc)
		
		trace("i: "+i);
		trace(">>>>>>>>.EDIT THIS HOTSPOT ")
	}
	
	
}
//-----------------------------------------------

//-----------------------------------------------

function fade_box()
{
	trace("function: fade_box()")
	this.onEnterFrame = function()
	{			
			
		mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].line_mc._alpha -= 5;
		
		if(mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].line_mc._alpha <=0)
		{
							
				removeMovieClip(mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].line_mc);
				delete this.onEnterFrame;
				//intro_mc._visible = false;
							
		}
	
	
	}
}

//-----------------------------------------------

//-----------------------------------------------

function get_hotSpot(hotSpotNum_int)
{
	
	trace("function: get_hotSpot()")
	//trace("length:  " + data_arr[currentPhoto_int].length)
	if(data_arr[currentPhoto_int].hotSpot_arr != undefined)
	{
		
		
		_root.whichhotSpot = hotSpotNum_int
		//data_arr[currentPhoto_int].hotSpot_arr.length
		load_hotSpot(whichhotSpot)
			
		
	}
		
}

//-----------------------------------------------

//-----------------------------------------------

function highlightHotSpot(i)
{
	mediaContainer_mc.menu = lettersCM;
	if(loggedIn_bool==true && idMatch_bool == true)
	{
		mediaContainer_mc.photo_mc["hotSpot"+i].createEmptyMovieClip("drawing_mc",10000);
		trace(_parent.i)
		
		mediaContainer_mc.photo_mc["hotSpot"+i].drawing_mc.lineStyle(2, 0x0099ff, 80)
		mediaContainer_mc.photo_mc["hotSpot"+i].drawing_mc.lineTo(0,mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc._height);
		mediaContainer_mc.photo_mc["hotSpot"+i].drawing_mc.lineTo(mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc._width,mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc._height);
		mediaContainer_mc.photo_mc["hotSpot"+i].drawing_mc.lineTo(mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc._width,0);
		mediaContainer_mc.photo_mc["hotSpot"+i].drawing_mc.lineTo(0,0);	
	}
	
}

//-----------------------------------------------

//-----------------------------------------------
function hotSpot_config(i){
	
	trace("function: hotSpot_config()")
	
	function load_hotspot_now() 
	{ 
		//trace("hotSpotPath_str:"+ hotSpotPath_str)
			//mediaContainer_mc.photo_mc["hotSpot"+i]._x = data_arr[currentPhoto_int].hotSpot_arr[i].x_int;
			//mediaContainer_mc.photo_mc["hotSpot"+i]._y = data_arr[currentPhoto_int].hotSpot_arr[i].y_int;
			if(hotSpotPath_str != "text.swf" && hotSpotPath_str != "html_text.swf"){
				
					
				if(data_arr[currentPhoto_int].hotSpot_arr[i].mainColor_str.length > 2)
				{
					thisColor= new Color(mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc);
					thisColor.setRGB(data_arr[currentPhoto_int].hotSpot_arr[i].mainColor_str);
					
					
				}
				
				
				mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc._alpha = data_arr[currentPhoto_int].hotSpot_arr[i].alpha_int;
				mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.fill_mc._visible = data_arr[currentPhoto_int].hotSpot_arr[i].fill_bool;
				mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.textBox_mc.text = data_arr[currentPhoto_int].hotSpot_arr[i].note_str;
							
				mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.gotoAndStop(data_arr[currentPhoto_int].hotSpot_arr[i].alignment_str);
				mediaContainer_mc.photo_mc["hotSpot"+i].alignment_str = data_arr[currentPhoto_int].hotSpot_arr[i].alignment_str;
				
				trace("rotation_int: " + data_arr[currentPhoto_int].hotSpot_arr[i].rotation_int)
				
				mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.object_mc._rotation = data_arr[currentPhoto_int].hotSpot_arr[i].rotation_int;
				
			}else if(hotSpotPath_str == "html_text.swf")
			{
				
				if(data_arr[currentPhoto_int].hotSpot_arr[i].mainColor_str.length > 2)
				{
					thisColor= new Color(mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.com.editorField);
					thisColor.setRGB(data_arr[currentPhoto_int].hotSpot_arr[i].mainColor_str);
					
					
				}
				
			}else{
				
				//trace("----------start: generate text ");
				tWidth=data_arr[currentPhoto_int].hotSpot_arr[i].width_int;
				tHeight=data_arr[currentPhoto_int].hotSpot_arr[i].height_int;
				//tx=data_arr[currentPhoto_int].hotSpot_arr[i].x_int;
				//ty=data_arr[currentPhoto_int].hotSpot_arr[i].y_int;
				
				mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.createTextField("textBox_mc",2,0,0,tWidth,tHeight)
				
				//trace("tx:"+tx+" "+"ty:"+ty+" "+tWidth+"x"+tHeight);
				//trace("text:"+data_arr[currentPhoto_int].hotSpot_arr[i].note_str);
				
				mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.clearButton_mc._width = data_arr[currentPhoto_int].hotSpot_arr[i].width_int
				mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.clearButton_mc._height = data_arr[currentPhoto_int].hotSpot_arr[i].height_int
				
				//if(data_arr[currentPhoto_int].hotSpot_arr[i].swfPath_str != "text_type.swf")
				//{
					mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.textBox_mc.text = data_arr[currentPhoto_int].hotSpot_arr[i].note_str;
				//}
				theFormat = new TextFormat();
				
				theFormat.font = "tahoma";
				theFormat.bullet  = data_arr[currentPhoto_int].hotSpot_arr[i].bullet_bool;
				theFormat.underline  = data_arr[currentPhoto_int].hotSpot_arr[i].underline_bool;
				theFormat.bold = data_arr[currentPhoto_int].hotSpot_arr[i].bold_bool;
				theFormat.size = data_arr[currentPhoto_int].hotSpot_arr[i].fontSize_int;
				theFormat.align = data_arr[currentPhoto_int].hotSpot_arr[i].alignment_str;
				theFormat.italic = data_arr[currentPhoto_int].hotSpot_arr[i].italic_bool;
				theFormat.color =  data_arr[currentPhoto_int].hotSpot_arr[i].mainColor_str;
				theFormat.url =  data_arr[currentPhoto_int].hotSpot_arr[i].link_str;

				mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.textBox_mc.setTextFormat(theFormat);
				mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.textBox_mc.selectable = true;
				mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.textBox_mc.multiline = true;
				mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.textBox_mc.wordWrap = true;
				
				if(data_arr[currentPhoto_int].hotSpot_arr[i].fill_bool == undefined || data_arr[currentPhoto_int].hotSpot_arr[i].fill_bool == true)
				{
					mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.textBox_mc.background = true;
					mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.textBox_mc.backgroundColor = 0xffffff;
							
				}else{
							
					mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.textBox_mc.background = false;
							
				}
			
						
				if(data_arr[currentPhoto_int].hotSpot_arr[i].border_bool == false)
				{
							
						
				}else{
							
						mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.textBox_mc.border = true;
						mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.textBox_mc.borderColor = data_arr[currentPhoto_int].hotSpot_arr[i].mainColor_str;
				}
			
				
				//trace("----------end: generate text");
				
				
				
			}
			
			hotSpotRatio_int = currentPhotoWidth_int/data_arr[currentPhoto_int].hotSpot_arr[i].sourceWidth_int
			
			
			resize_hotspot(i, hotSpotRatio_int)
			
			
			mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.textBox_mc.selectable = false;
			
			hotSpotNum = data_arr[currentPhoto_int].hotSpot_arr.length;
			hotSpotNum--;
			
			//trace("buttonRolledOver_bool: "+buttonRolledOver_bool)
			//if(hotSpotRollOver_bool != true || buttonRolledOver_bool == true)
			//{
				mediaContainer_mc.photo_mc["hotSpot"+i]._visible = true;
			
			//}else if(autoPlay_bool == true)
			//{
				
				//mediaContainer_mc.photo_mc["hotSpot"+i]._visible = true;
				
			//}
			
			//if there are still hotspots left to load get hotspots
			if(i < hotSpotNum)
			{
				hotSpotsLoadedDone_bool = false;
				trace("hotSpotLoadComplete_bool: "+hotSpotLoadComplete_bool)
					//hotSpotLoadComplete_bool = false;
					whichhotSpot++;
					get_hotSpot(whichhotSpot);
					
			
			}else{
				
				hotSpotsLoadedDone_bool = true;
				
			}
			
			
			
			
				
			if(data_arr[currentPhoto_int].hotSpot_arr[i].shadow_bool == true)
			{
					
				getShadow_mc(3, 45, "0x000000" , .5 , 3, 3, false, "mediaContainer_mc","photo_mc","hotSpot"+i )
					
			}
			
			clearInterval(intervalID_hotspot)
			
				

			
	}
 
 intervalID_hotspot = setInterval(load_hotspot_now,50);
	

//trace("| >>>>>>>>>>>>>>>>>>>>>> END CREATE : hotSpot"+ i +"<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< |" );
		trace("");		
	
}

//-----------------------------------------------

//-----------------------------------------------
function load_hotSpot(whichHot){
	
	trace("function: load_hotSpot()")
	clearInterval(hotSpotDelay);
	i = whichhotSpot
	totalHotSpots_int = whichhotSpot;
			trace("");
			trace("");
			
			trace("| >>>>>>>>>>>>>>>>>>>>>> CREATE : hotSpot"+ i +"<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< |" );
			trace("whichhotSpot: "+whichhotSpot)
			trace("totalHotSpots_int"+ totalHotSpots_int);
			trace("depth_int:  "+data_arr[currentPhoto_int].hotSpot_arr[i].depth_int);
			trace("swf_type: "+data_arr[currentPhoto_int].hotSpot_arr[i].swfPath_str);
			trace("object: "+data_arr[currentPhoto_int].hotSpot_arr[i]);
			
		hotSpotRecord_arr.push(i);
		trace("hotSpotRecord_arr: "+ hotSpotRecord_arr)
		
		mediaContainer_mc.photo_mc.createEmptyMovieClip("hotSpot"+i, data_arr[currentPhoto_int].hotSpot_arr[i].depth_int)
		mediaContainer_mc.photo_mc["hotSpot"+i]._x -= 900;
		//mediaContainer_mc.photo_mc["hotSpot"+i]._y -= 900;
		hotSpotPath_str = data_arr[currentPhoto_int].hotSpot_arr[i].swfPath_str;
		trace("hotSpotPath_str: "+ hotSpotPath_str)
		trace(" ")
		trace(" ")
		temp_str = hotSpotPath_str;
		temp_int = temp_str.length
		temp_int = temp_int - 4;
		hotSpotType_str = temp_str.substring(0,temp_int);
		
		//also loaded in from create_hotspot || LOAD MOVIE
		loadMovie(hotSpotDirectory_str+"edit8.swf", mediaContainer_mc.photo_mc["hotSpot"+i])
		hotSpotsCreated_int++;
		
		/*
		trace("depth_int:  "+data_arr[currentPhoto_int].hotSpot_arr[i].depth_int)
		trace("swf_type: "+data_arr[currentPhoto_int].hotSpot_arr[i].swfPath_str);
		trace("path: "+hotSpotDirectory_str+"edit.swf")
		trace("target: "+ mediaContainer_mc.photo_mc["hotSpot"+i])
		trace("x: "+data_arr[currentPhoto_int].hotSpot_arr[i].x_int)
		trace("y: "+data_arr[currentPhoto_int].hotSpot_arr[i].y_int)
		trace("width: "+data_arr[currentPhoto_int].hotSpot_arr[i].width_int);
		trace("height: "+data_arr[currentPhoto_int].hotSpot_arr[i].height_int);
		trace("alignment: "+data_arr[currentPhoto_int].hotSpot_arr[i].alignment_str);
		trace("note: "+data_arr[currentPhoto_int].hotSpot_arr[i].note_str);
		trace("alpha: "+data_arr[currentPhoto_int].hotSpot_arr[i].alpha_int);
		trace("fill: "+data_arr[currentPhoto_int].hotSpot_arr[i].fill_bool);
		trace("fontSize: "+data_arr[currentPhoto_int].hotSpot_arr[i].fontSize_int);
		trace("--slideshowKey_str: "+data_arr[currentPhoto_int].hotSpot_arr[i].slideshowKey_str);
		*/
		
		this.createEmptyMovieClip("createHotSpot_mc", 200003);
		
		createHotSpot_mc.onEnterFrame = function()
		{
		
			//hs_infoLoaded =mediaContainer_mc.photo_mc["hotSpot"+i].getBytesLoaded();
			//hs_infoTotal = mediaContainer_mc.photo_mc["hotSpot"+i].getBytesTotal();
			//hs_percentage = Math.floor(hs_infoLoaded/hs_infoTotal*100);
					
			//hs_percentage==100 && 
			//is the hotspot loaded and is the graphic present
			if (mediaContainer_mc.photo_mc["hotSpot"+i].graphic == true)
			{
			
				delete createHotSpot_mc.onEnterFrame;
				mediaContainer_mc.photo_mc["hotSpot"+i]._visible = false;
				
				hotSpot_config(i);
				
				//-------------------------				trace("hotSpotRatio_int = "+hotSpotRatio_int)
									
				
				
			}
			
		}
		
		
	
}

//-------------------------------

//-------------------------------
function move_to_back()
{
	//how many hotspots are there
	numberHS_int = data_arr[currentPhoto_int].hotSpot_arr.length
	trace("numberHS_int: "+numberHS_int)
	
	currentDepth_int = data_arr[currentPhoto_int].hotSpot_arr[currentHotSpot_int].depth_int;
	
	trace("currentDepth_int: "+currentDepth_int)
	
	//what are their depths
	depth_arr =  new Array()
	
	for(i=0; i<data_arr[currentPhoto_int].hotSpot_arr.length; i++)
	{
		
		depth_arr.push(data_arr[currentPhoto_int].hotSpot_arr[i].depth_int);
				
	}
	
	//array of all the depths
	trace(depth_arr)
	
	
	//which one do you want to target
	mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].swapDepths(newDepth_int);
	
	
}

//----------------------

//--------------------------

function new_hotspot(whichType_str){
		
		
		trace("function: new_hotspot()")
		//removeMovieClip(timelineContainer_mc);
		
		//removeMovieClip(addBlank_mc)
		removeMovieClip(addTitle_mc)
		///'removeMovieClip(addTitle_mc)
		///removeMovieClip(addTitle_mc)
		
	if(editMode_bool == false)
	{
			
			/*
			mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].graphic_mc.allowDraw = false;
			save_hotSpot(mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].i);
			mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].editMode_bool = false;
			//save_mc._visible= false;
			mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].resize_mc._visible= false;
			mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].drag_mc._visible= false;

			mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].save_mc._visible= false;
		*/
		//}
		
			if(editMode_bool == false && data_arr[currentPhoto_int].title_str == undefined){
				hotSpotType_str = whichType_str;
				hotSpotPath_str  =whichType_str+".swf";
				//trace(hotSpotPath_str)
				for(i=0; i<data_arr[currentPhoto_int].hotSpot_arr.length; i++)
				{
				
					mediaContainer_mc.photo_mc["hotSpot"+i].edit_mc._visible = false;
				}
			
				
				create_hotspot();
				
				editMode_bool = true;
				
				
				
			}
			
	}else{
			
			show_hint_box();
			
	}
}
function removeHighlightHotSpot(i)
{
	
	removeMovieClip(mediaContainer_mc.photo_mc["hotSpot"+i].drawing_mc)
}


function resize_hotspot(whichHotSpot, hsRatio_int)
{
		trace("function: resize_hotspot() ----------------------------")
		trace("whichHotSpot = "+ whichHotSpot + "  & hsRatio_int = "+hsRatio_int)
		trace("OLD X: " +data_arr[currentPhoto_int].hotSpot_arr[whichHotSpot].x_int);
		trace("OLD Y: " +data_arr[currentPhoto_int].hotSpot_arr[whichHotSpot].y_int);
		trace("OLD Width" + data_arr[currentPhoto_int].hotSpot_arr[whichHotSpot].width_int )
		trace("OLD Height" + data_arr[currentPhoto_int].hotSpot_arr[whichHotSpot].height_int)
		
		mediaContainer_mc.photo_mc["hotSpot"+whichHotSpot]._x = data_arr[currentPhoto_int].hotSpot_arr[whichHotSpot].x_int * hsRatio_int;
		mediaContainer_mc.photo_mc["hotSpot"+whichHotSpot]._y =data_arr[currentPhoto_int].hotSpot_arr[whichHotSpot].y_int * hsRatio_int;
		
		trace("X: " +data_arr[currentPhoto_int].hotSpot_arr[whichHotSpot].x_int * hsRatio_int);
		trace("Y: " +data_arr[currentPhoto_int].hotSpot_arr[whichHotSpot].y_int * hsRatio_int);
		
		mediaContainer_mc.photo_mc["hotSpot"+whichHotSpot].graphic_mc._width = data_arr[currentPhoto_int].hotSpot_arr[whichHotSpot].width_int* hsRatio_int;
		mediaContainer_mc.photo_mc["hotSpot"+whichHotSpot].graphic_mc._height = data_arr[currentPhoto_int].hotSpot_arr[whichHotSpot].height_int* hsRatio_int;
		
		trace("Width" + data_arr[currentPhoto_int].hotSpot_arr[whichHotSpot].width_int* hsRatio_int )
		trace("Height" + data_arr[currentPhoto_int].hotSpot_arr[whichHotSpot].height_int* hsRatio_int)
	
}

function restore_media_container()
{
	detail_mc._visible= true;
	navContainer_mc._visible = true;
	if(photoBorder_bool == true)
	{
		line_mc._visible = true

	}
	
	if(photoMask_bool == true)
	{
		
		if(photoBorder_bool == true)
		{
			
			border_mc._visible = true;
		}
		
		this[mediaContainerMask_str]._visible = true;
		mediaContainer_mc.setMask(this[mediaContainerMask_str]);

		
	}
		
		this[mediaContainerMask_str]._alpha = 0;
		removeMovieClip(mediaContainer_mc.photo_mc.temp_mc);
	
}

//-----------------------------------------------
// mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int]save_hotSpot(i)
//-----------------------------------------------
function save_hotSpot(whichHS)
{
	//set imageButton behavior (button havior is cleared by the hotspot rollover
	changeButton();
	removeMovieClip(mediaContainer_mc.photo_mc["hotSpot"+whichHS].arrowButtons_mc)
	
	if(hotSpotPath_str != undefined)
	{
		restore_media_container()
		
		for(d=0; d<data_arr[currentPhoto_int].hotSpot_arr.length; d++)
		{
		
			if(hotSpotRecord_arr[d] == whichHS)
			{
				
				i = d;
				break;
			}
		
		}
		trace(" ")
		trace(" -------------------- SAVED HOTSPOT: "+i+" --------------------")
		//trace("Fill: "+data_arr[currentPhoto_int].hotSpot_arr[i].fill_bool);
		
		//create the object for the hotspot array.  If it's a text hotspot the object has already been created
		if(data_arr[currentPhoto_int].hotSpot_arr[i]== undefined)
		{
			
			data_arr[currentPhoto_int].hotSpot_arr[i] = new Object();
		}
		
		data_arr[currentPhoto_int].hotSpot_arr[i].x_int = mediaContainer_mc.photo_mc["hotSpot"+whichHS]._x;
		
		data_arr[currentPhoto_int].hotSpot_arr[i].y_int = mediaContainer_mc.photo_mc["hotSpot"+whichHS]._y;
		data_arr[currentPhoto_int].hotSpot_arr[i].height_int = mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc._height;
		data_arr[currentPhoto_int].hotSpot_arr[i].width_int = mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc._width;
		
		data_arr[currentPhoto_int].hotSpot_arr[i].swfPath_str = hotSpotPath_str;
		
		if(data_arr[currentPhoto_int].hotSpot_arr[i].swfPath_str == "photo.swf")
		{
			data_arr[currentPhoto_int].hotSpot_arr[i].border_bool = mediaContainer_mc.photo_mc["hotSpot"+whichHS].resize_mc.photoBorder_mc.border_bool
			data_arr[currentPhoto_int].hotSpot_arr[i].stroke_int  = mediaContainer_mc.photo_mc["hotSpot"+whichHS].resize_mc.photoBorder_mc.stroke_int
			
			if(scrapbook_arr[0].photoKey_str != undefined)
			{
			
				data_arr[currentPhoto_int].hotSpot_arr[i].thumbnailPath_str = scrapbook_arr[0].thumbnailPath_str;
				data_arr[currentPhoto_int].hotSpot_arr[i].photoKey_str = scrapbook_arr[0].photoKey_str;
				data_arr[currentPhoto_int].hotSpot_arr[i].photoPath_str = scrapbook_arr[0].photoPath_str;
			}
			
		}
		if(data_arr[currentPhoto_int].hotSpot_arr[i].photoPath_str == undefined && data_arr[currentPhoto_int].hotSpot_arr[i].swfPath_str == "slideshow.swf")
		{
			
			if(scrapbook_arr[0].photoKey_str != undefined)
			{
			
				data_arr[currentPhoto_int].hotSpot_arr[i].thumbnailPath_str = scrapbook_arr[0].thumbnailPath_str;
				data_arr[currentPhoto_int].hotSpot_arr[i].photoKey_str = scrapbook_arr[0].photoKey_str;
				data_arr[currentPhoto_int].hotSpot_arr[i].photoPath_str = scrapbook_arr[0].photoPath_str;
				data_arr[currentPhoto_int].hotSpot_arr[i].slideshowKey_str = scrapbook_arr[0].slideshowKey_str;
				//trace("scrapbook_arr[0].slideshowTitle_str; "+scrapbook_arr[0].slideshowTitle_str)
				data_arr[currentPhoto_int].hotSpot_arr[i].slideshowTitle_str = scrapbook_arr[0].slideshowTitle_str;
				data_arr[currentPhoto_int].hotSpot_arr[i].slideshowWidth_int = scrapbook_arr[0].slideshowWidth_int;
				data_arr[currentPhoto_int].hotSpot_arr[i].slideshowHeight_int = scrapbook_arr[0].slideshowHeight_int;
			}
		}
		
		//save the start and end point when the hotspot is visible
		data_arr[currentPhoto_int].hotSpot_arr[i].start_int = mediaContainer_mc.photo_mc["hotSpot"+whichHS].startPoint_int;
		data_arr[currentPhoto_int].hotSpot_arr[i].end_int = mediaContainer_mc.photo_mc["hotSpot"+whichHS].endPoint_int;
		
		if(data_arr[currentPhoto_int].hotSpot_arr[i].end_int != undefined && data_arr[currentPhoto_int].hotSpot_arr[i].start_int == undefined)
		{
			
			data_arr[currentPhoto_int].hotSpot_arr[i].start_int = 0;
			
		}
		
		trace("timing_mc: "+mediaContainer_mc.photo_mc["hotSpot"+whichHS].startPoint_int)
		data_arr[currentPhoto_int].hotSpot_arr[i].rotation_int = mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.object_mc._rotation;
		data_arr[currentPhoto_int].hotSpot_arr[i].animated_bool  = mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.animated_bool
		data_arr[currentPhoto_int].hotSpot_arr[i].shadow_bool  = mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.shadow_bool;
		
		data_arr[currentPhoto_int].hotSpot_arr[i].target_str = mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.target_str;
		data_arr[currentPhoto_int].hotSpot_arr[i].link_str = mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.link_str;
		trace("link_str: "+mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.link_str)
		
		data_arr[currentPhoto_int].hotSpot_arr[i].draw_arr = new Array();
		data_arr[currentPhoto_int].hotSpot_arr[i].draw_arr = mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.draw_arr;
		
		if(hotSpotPath_str == "calendar.swf")
		{
			
			data_arr[currentPhoto_int].hotSpot_arr[i].draw_arr = new Array();
			data_arr[currentPhoto_int].hotSpot_arr[i].draw_arr = mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.calendar_arr
			
		} else if(hotSpotPath_str == "faq.swf")
		{
			
			data_arr[currentPhoto_int].hotSpot_arr[i].draw_arr = new Array();
			data_arr[currentPhoto_int].hotSpot_arr[i].draw_arr = mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.faq_arr
		
		}else if(hotSpotPath_str == "highlights.swf")
		{
			
			data_arr[currentPhoto_int].hotSpot_arr[i].draw_arr = new Array();
			data_arr[currentPhoto_int].hotSpot_arr[i].draw_arr = mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.faq_arr
			
		}else if(hotSpotPath_str == "video.swf")
		{
			
			data_arr[currentPhoto_int].hotSpot_arr[i].link_str = scrapbook_arr[0].link_str;
			data_arr[currentPhoto_int].screenShot_str = scrapbook_arr[0].screenShot_str
			
		}else if(hotSpotPath_str == "contact.swf")
		{
			data_arr[currentPhoto_int].hotSpot_arr[i].link_str = mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.editEmail_mc.to_mc.text;
		
		}else if(hotSpotPath_str == "text.swf" || hotSpotPath_str == "buttonWhite.swf" || hotSpotPath_str == "buttonBlack.swf" || hotSpotPath_str == "buttonRed.swf"|| hotSpotPath_str == "buttonBlue.swf" || hotSpotPath_str == "text_1.swf" || hotSpotPath_str == "text_2.swf" || hotSpotPath_str == "text_3.swf"  || hotSpotPath_str == "quoteBox.swf" || hotSpotPath_str ==  "quote.swf" || hotSpotPath_str ==  "thought.swf" )
		{
				trace("| TEXT | QUOTE | THOUGHT: " +hotSpotType_str )
				data_arr[currentPhoto_int].hotSpot_arr[i].note_str = mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.textBox_mc.text
			
			if(hotSpotPath_str ==  "quote.swf" || hotSpotPath_str ==  "thought.swf" || hotSpotPath_str == "quoteBox.swf")
			{
				
				data_arr[currentPhoto_int].hotSpot_arr[i].alignment_str = mediaContainer_mc.photo_mc["hotSpot"+whichHS].alignment_str;
				if(mediaContainer_mc.photo_mc["hotSpot"+whichHS].alignment_str == undefined)
				{
					data_arr[currentPhoto_int].hotSpot_arr[i].alignment_str = "right_down";
				}
			}
			//data_arr[currentPhoto_int].hotSpot_arr[i].fill_bool = mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.fill._visible;
		}else if(hotSpotPath_str == "html_text.swf")
		{
			
			//this is an hmtl string
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.viewHTML.viewHTMLtext.text = mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.htmlView();
			data_arr[currentPhoto_int].hotSpot_arr[i].note_str =  mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.viewHTML.viewHTMLtext.text;
			data_arr[currentPhoto_int].hotSpot_arr[i].scrollBar_bool = mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.scrollBar_bool
			//data_arr[currentPhoto_int].hotSpot_arr[i].note_str = html_str
			
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.editorField.selectable = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.underline_btn._visible = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.italic_btn._visible = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.bold_btn._visible = false;
			//mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.FComboBoxSymbolSize._visible = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.leftAlign._visible = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.centerAlign._visible = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.rightAlign._visible = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.bulletText._visible = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.myPalette._visible = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.showLink._visible = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.link_bg._visible = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.editorField.selectable = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.up_btn._visible  = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.down_btn._visible  = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.sizer_mc._visible  = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.hyper_link._visible = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.link._visible = false;
			mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.com.target_mc._visible = false;
			
			
			
		}else{
			//trace("hotSpotType_str:" +hotSpotType_str )
			trace(mediaContainer_mc.photo_mc["hotSpot"+i].resize_mc.textBox_mc.text)
			data_arr[currentPhoto_int].hotSpot_arr[i].alignment_str = alignment_str;
			data_arr[currentPhoto_int].hotSpot_arr[i].note_str = mediaContainer_mc.photo_mc["hotSpot"+whichHS].resize_mc.textBox_mc.text
			data_arr[currentPhoto_int].hotSpot_arr[i].fill_bool = mediaContainer_mc.photo_mc["hotSpot"+whichHS].graphic_mc.fill_mc._visible;
			
		}
		
		thisDepth_int = getDepth(mediaContainer_mc.photo_mc["hotSpot"+i])
	
			
		data_arr[currentPhoto_int].hotSpot_arr[i].depth_int = newDepth_int;
		
	
		
		//current width of the photo - used to resize a hotspot when a slideshow has been resized from it's original
		data_arr[currentPhoto_int].hotSpot_arr[i].sourceWidth_int =currentPhotoWidth_int
		
		trace("mediaContainer_mc.photo_mc: "+ mediaContainer_mc.photo_mc._width)
		
		data_arr[currentPhoto_int].hotSpot_arr[i].alpha_int = mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc._alpha;
		//data_arr[currentPhoto_int].hotSpot_arr[i].rotation_int = mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc._rotation;
		
		if(mediaContainer_mc.photo_mc["hotSpot"+i].resize_mc.hexNum != undefined){
			data_arr[currentPhoto_int].hotSpot_arr[i].mainColor_str = "0x"+mediaContainer_mc.photo_mc["hotSpot"+whichHS].resize_mc.hexNum;
		}
		
		//text in quote box/thought box etc. uneditable...
		mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.textBox_mc.selectable = false;
		
		//used to show where the canvas for draw functionality
		mediaContainer_mc.photo_mc["hotSpot"+i].graphic_mc.canvas_mc._visible = false;
		
		trace("SAVED: swfPath_str = "+data_arr[currentPhoto_int].hotSpot_arr[i].swfPath_str)
		
		
		trace("SAVED: alignment = "+data_arr[currentPhoto_int].hotSpot_arr[i].alignment_str)
		trace("SAVED: alpha_int = "+data_arr[currentPhoto_int].hotSpot_arr[i].alpha_int )
		trace("SAVED: bold ="+ data_arr[currentPhoto_int].hotSpot_arr[i].bold_bool)
		trace("SAVED: bullet  ="+ data_arr[currentPhoto_int].hotSpot_arr[i].bullet_bool)
		trace("SAVED: border_bool  ="+data_arr[currentPhoto_int].hotSpot_arr[i].border_bool);
		trace("SAVED: depth_int = "+data_arr[currentPhoto_int].hotSpot_arr[i].depth_int)
		trace("SAVED: draw_arr.length = "+data_arr[currentPhoto_int].hotSpot_arr[i].draw_arr.length )
		trace("SAVED: height_int  = "+data_arr[currentPhoto_int].hotSpot_arr[i].height_int )
		trace("SAVED: end_int = "+data_arr[currentPhoto_int].hotSpot_arr[i].end_int)
		trace("SAVED: fontSize  ="+ data_arr[currentPhoto_int].hotSpot_arr[i].fontSize_int);
		trace("SAVED: italic  ="+ data_arr[currentPhoto_int].hotSpot_arr[i].italic_bool);
		trace("SAVED: fill  ="+data_arr[currentPhoto_int].hotSpot_arr[i].fill_bool);
		trace("SAVED: fill_bool = "+data_arr[currentPhoto_int].hotSpot_arr[i].fill_bool)
		trace("SAVED: link_str  ="+data_arr[currentPhoto_int].hotSpot_arr[i].link_str);
		trace("SAVED: mainColor_str = "+data_arr[currentPhoto_int].hotSpot_arr[i].mainColor_str)
		trace("SAVED: note_str = "+data_arr[currentPhoto_int].hotSpot_arr[i].note_str)
		//trace("SAVED: photoPath_str = "+data_arr[currentPhoto_int].hotSpot_arr[i].photoPath_str)
		trace("SAVED: thumbnailPath_strt = "+data_arr[currentPhoto_int].hotSpot_arr[i].thumbnailPath_str)
		trace("SAVED: photoKey_str = "+data_arr[currentPhoto_int].hotSpot_arr[i].photoKey_str)
		trace("SAVED: photoPath_str = "+data_arr[currentPhoto_int].hotSpot_arr[i].photoPath_str)
		trace("SAVED: rotation_int = "+data_arr[currentPhoto_int].hotSpot_arr[i].rotation_int)
		trace("SAVED: screenShot_str = "+data_arr[currentPhoto_int].screenShot_str)
		trace("SAVED: scrollBar_bool  ="+ data_arr[currentPhoto_int].hotSpot_arr[i].scrollBar_bool)
		trace("SAVED: shadow  ="+ data_arr[currentPhoto_int].hotSpot_arr[i].shadow_bool);
		trace("SAVED: slideshowKey_str = "+data_arr[currentPhoto_int].hotSpot_arr[i].slideshowKey_str)
		trace("SAVED: slideshowTitle_str = "+data_arr[currentPhoto_int].hotSpot_arr[i].slideshowTitle_str)																				 
		trace("SAVED: slideshowWidth_int = "+data_arr[currentPhoto_int].hotSpot_arr[i].slideshowWidth_int)
		trace("SAVED: slideshowHeight_int = "+data_arr[currentPhoto_int].hotSpot_arr[i].slideshowHeight_int)
		trace("SAVED: sourceWidth = "+data_arr[currentPhoto_int].hotSpot_arr[i].sourceWidth_int)
		trace("SAVED: start_int = "+data_arr[currentPhoto_int].hotSpot_arr[i].start_int)
		trace("SAVED: stroke_int = "+data_arr[currentPhoto_int].hotSpot_arr[i].stroke_int)
		trace("SAVED: target_str  ="+data_arr[currentPhoto_int].hotSpot_arr[i].target_str);
		trace("SAVED: underline  ="+ data_arr[currentPhoto_int].hotSpot_arr[i].underline_bool);
		trace("SAVED: width_int  = "+data_arr[currentPhoto_int].hotSpot_arr[i].width_int )
		trace("SAVED: x = "+data_arr[currentPhoto_int].hotSpot_arr[i].x_int)
		trace("SAVED: y = "+data_arr[currentPhoto_int].hotSpot_arr[i].y_int)
		
		
		delete scrapbook_arr
		scrapbook_arr= new Array();
		
		trace("--------------------FINISH SAVED HOTSPOT: "+i)
		 
		//for(){
			
			
		//}
		
		
	}
	
	temploopNum_int = data_arr[currentPhoto_int].hotSpot_arr.length
		for(a=0; a<temploopNum_int; a++){
			
			trace(a+" :swf path: "+data_arr[currentPhoto_int].hotSpot_arr[a].swfPath_str)
			//if(data_arr[currentPhoto_int].hotSpot_arr[a].swfPath_str == undefined){
				
				//data_arr[currentPhoto_int].hotSpot_arr.splice(a,1);
				//trace("delete"+a)
			//}
		}
		trace("")
		trace(" ")
}

//-----------------------------------------------

//-----------------------------------------------

function show_hint_box(){
	alert.start();
	trace("function: show_hint_box()")
			
	mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].createEmptyMovieClip("line_mc",10000);
			
	mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].line_mc.lineStyle(2, 0xff0033, 100)
	mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].line_mc.lineTo(0,mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].graphic_mc._height);
	mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].line_mc.lineTo(mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].graphic_mc._width,mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].graphic_mc._height);
	mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].line_mc.lineTo(mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].graphic_mc._width,0);
	mediaContainer_mc.photo_mc["hotSpot"+currentHotSpot_int].line_mc.lineTo(0,0);
			
	clear_box();
	
	
}






	
	


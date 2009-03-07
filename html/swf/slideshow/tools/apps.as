/*
function add_background_photo(bgObject, blurAmount, type,alpha_int)
function add_blank(bgColor_str)
function add_duplicate()
function add_page(text_str, layout_str, parent_str)
function add_photo(thisObject_passed)
function add_siteHeader(photoPath_str, thumbnailPath_str, photoKey_str, rotation_int, ID_int , thisWidth_int, thisHeight_int)
function add_title(text_str, textColor_str, bgColor_str, fontName_str, NEWfontSize_int, NEWalign_str, link_str, target_str,bullet_bool)
function editTitleFrame() ** this function has changed to point to another function!
function get_photo_now2() 
function get_toolBar()
function loadAddMusic()
function loadBuildPages()
function loadCreateBlank()
function loadCreatePage()
function loadCreatePhoto()
function loadCreateSlideshow()
function loadCreateTitle(frame)
function loadCreateVideo(info)
function loadEditTimeline()
function loadEditNav()
function loadEditTheme()
function loadLinkSlideshow()
function loadManagePages()
function loadOrderDVD()
function loadSiteColors()
function loadSiteTitle(frame)
function loadToolBar(info)
function loadWidget()
function moveItem_now() 
function reloadPreview()
function removeAllTools()
function update_title(text_str, textColor_str, bgColor_str, fontName_str, whichFrame_int, NEWfontSize_int,NEWalign_str, link_str, target_str, bullet_bool)

*/

windowXPosition_int =80
windowYPosition_int =60

myDate = new Date(); 
timestamp=escape(myDate.toString());


function add_background_photo(bgObject, blurAmount, type,alpha_int)
{
	trace("function: add_background_photo() photoPath_str: ")
	trace("bgObject[0].: "+bgObject.photoKey_str);
	trace("blurAmount:"+ blurAmount)
	
	if(type == "currentPhoto")
	{
		this.createEmptyMovieClip("backgroundPhoto_mc", bgPhotoDepth_int)
		backgroundPhoto_mc._alpha = 0;
	}
	//if(mc_arr[bg].instanceName_str == "backgroundPhoto_mc")
	
	//----- MOVIE CLIP LOAD LISTENER ---  tells flash when the photo has been loaded
		myBG_clip_status = "idle";
		myBGMCL 		= new MovieClipLoader();
		myBGListener = new Object()
		
		myBGListener.onLoadStart = function (targetMC)
		{
			myBG_clip_status = "started";
		}
		
		myBGListener.onLoadComplete = function (targetMC) 
		{
			myBG_clip_status = "loaded";
		}
		
		myBGListener.onLoadError = function (targetMC, errorCode) 
		{
			myBG_clip_status = "error";
		}
	
		myBGMCL.addListener(myListener);
	
	
	if(type != "currentPhoto"){
		for(i=0; i<mc_arr.length; i++)
		{
			
			if(mc_arr[i].instanceName_str == "backgroundPhoto_mc")
			{
				//delete  mc_arr[i];
				mc_arr[i] = bgObject;
				thisPhotoBG_int = i;
				trace(mc_arr[i].photoKey_str);
				mc_arr[i].instanceName_str = "backgroundPhoto_mc"
				mc_arr[i].photoPath_str =  bgObject.photoPath_str
				mc_arr[i].photoKey_str =  bgObject.photoKey_str
				trace("mc_arr[i]photoPath_str :  "+mc_arr[i].photoPath_str )
				trace("mc_arr[i].photoKey_str:  "+mc_arr[i].photoKey_str)
				mc_arr[i].swfPath_str = ptg.customImage(mc_arr[thisPhotoBG_int],movieWidth_int,movieHeight_int)
										//ptg.customImage(mc_arr[i],movieWidth_int,movieHeight_int)
				trace("mc_arr[i].swfPath_str"+ mc_arr[thisPhotoBG_int].swfPath_str)
				//mc_arr[i].swfPath_str ="http://www.photagious.com/photos/custom/200608/1157046420_DSCN1740_865_570.JPG?1261663d8efdd976c31eedfae0138f67"
				
				trace(retval)
				mc_arr[i].resizable_bool = false;
				mc_arr[i].depth_int = 3;
				mc_arr[i].visible_bool = true;
				mc_arr[i].x_int=0;
				mc_arr[i].y_int=0;
				mc_arr[i].alpha_int=alpha_int;
				mc_arr[i].blur_int=blurAmount;
				
				
				break;
			}
			
		}
			
			
			myBGMCL.loadClip(ptg.customImage(mc_arr[thisPhotoBG_int],movieWidth_int,movieHeight_int), backgroundPhoto_mc);
			
			//loadMovie(ptg.customImage(bgObject,movieWidth_int,movieHeight_int), backgroundPhoto_mc);
			
		
		
		}else if (data_arr[currentPhoto_int].photoPath_str != undefined && data_arr[currentPhoto_int].photoPath_str != "blank"){
			
			
			myBGMCL.loadClip(ptg.customImage(bgObject,movieWidth_int,movieHeight_int), backgroundPhoto_mc);
	
		}
		
		
		
		
		
		
		__percentage = 0;
		
		function load_bg()
		{
			__infoLoaded =backgroundPhoto_mc.getBytesLoaded();
			__infoTotal = backgroundPhoto_mc.getBytesTotal();
			__percentage = Math.floor(__infoLoaded/__infoTotal*100);
			//backgroundPhoto_mc._alpha= 0;
			_root.createTextField("percentageBox2_mc",42000,10,10,200, 40);
			_root.percentageBox2_mc.selectable = false;
			_root.percentageBox2_mc._x = movieWidth_int - 100
			_root.percentageBox2_mc._y = 5
			_root.percentageBox2_mc.text = "Loading: "+__percentage+"%";
			_root.percentageBox2_mc.setTextFormat(percentageFormat);
			_root.preloader_mc._visible = true;
			
			trace("myBG_clip_status: "+myBG_clip_status)
			
			
			if(_root.myBG_clip_status == "loaded" || __percentage == 100)
			{
				clearInterval(intervalID_loadBG);
				removeMovieClip(_root.percentageBox2_mc);
				_root.preloader_mc._visible = false;
				removeMovieClip(_root.preloader_mc)
				
				//blur_int = blurAmount;
				//blurBackground(blur_int);
				
				
				trace("backgroundPhoto_mc._width: "+backgroundPhoto_mc._width)
				if(backgroundPhoto_mc._width != movieWidth_int)
				{
					backgroundPhoto_mc._width = movieWidth_int
					
				}
				
				if(backgroundPhoto_mc._height != movieHeight_int)
				{
					backgroundPhoto_mc._height = movieHeight_int
					
				}
				
				for(bg=0; bg<_root.mc_arr.length; bg++)
				{
					if(_root.mc_arr[bg].instanceName_str == "backgroundPhoto_mc")
					{
						trace("((((((((((((((((((((((((((((((((((: "+bg)
						trace("((((((((((((((((((((((((((: "+blurAmount)
						trace("((((((((((((((((((: "+mc_arr[bg].alpha_int)
						
						_root.blur_int = _root.mc_arr[bg].blur_int;
						thisMC_item = bg
						_root.backgroundPhoto_mc._alpha= mc_arr[bg].alpha_int;
						_root.blurBackground(blur_int);
						break;
					}
					
				}
				
					
			}
			
			
		}
		
		intervalID_loadBG = setInterval(load_bg, 200)
		
}
//----------------------------

//----------------------------

function add_blank(bgColor_str){
	
	
	trace("function: add_blank()  : bgColor_str:" +bgColor_str)
	//trace("");
	//trace("");
	//trace("");
	//trace("-----------------------ADD TITLE");
	
	p=totalPhotoNum_int;
	
	//trace("data_arr.length: "+data_arr.length);
	
	tempObject = new Object();
	data_arr.push(tempObject);
	timelineContainer_mc.timeline1_mc.recordPhoto_arr.push(p);
	//trace(timelineContainer_mc.timeline1_mc.recordPhoto_arr)
	//trace("recordPhoto_arr.length: "+timelineContainer_mc.timeline1_mc.recordPhoto_arr.length);
	//trace("data_arr.length: "+data_arr.length);
	//trace(p)
	//trace(timelineContainer_mc.timeline1_mc.recordPhoto_arr[p])
	
	totalPhotoNum_int++;
	
	data_arr[p] = new Object;
	data_arr[p].delay_int = 4000;
	data_arr[p].photoPath_str = "blank";
	data_arr[p].backgroundColor_str = "0x"+bgColor_str;
	
	
		

	intervalID_moveTitle = setInterval(moveItem_now,400)
		
	
}

//----------------------------

//-----------------------------
function add_duplicate()
{
	
	trace("function: add_duplicate()")
	trace("data_arr.length: "+data_arr.length);
	
	tempObject = data_arr[currentPhoto_int];
	data_arr.push(tempObject);
	timelineContainer_mc.timeline1_mc.recordPhoto_arr.push(p);
		
	data_arr[p] = new Object;
	data_arr[p].delay_int = data_arr[currentPhoto_int].delay_int;
	data_arr[p].photoPath_str =  data_arr[currentPhoto_int].photoPath_str;
	data_arr[p].thumbnailPath_str = data_arr[currentPhoto_int].thumbnailPath_str;
	data_arr[p].description_str = data_arr[currentPhoto_int].description_str;
	data_arr[p].photoKey_str = data_arr[currentPhoto_int].photoKey_str;
	data_arr[p].delay_int = data_arr[currentPhoto_int].delay_int
	data_arr[p].width_int =  data_arr[currentPhoto_int].width_int
	data_arr[p].height_int =data_arr[currentPhoto_int].height_int
	data_arr[p].rotation_int =  data_arr[currentPhoto_int].rotation_int
	data_arr[p].tags_str = data_arr[currentPhoto_int].tags_str
	data_arr[p].photoId_int = data_arr[currentPhoto_int].photoId_int
		
		
	if(data_arr[currentPhoto_int].swfPath_str != undefined){
		
		data_arr[p].swfPath_str = data_arr[currentPhoto_int].swfPath_str
		data_arr[p].title_str  = data_arr[currentPhoto_int].title_str;
		data_arr[p].mainColor_str = data_arr[currentPhoto_int].mainColor_str
		data_arr[p].backgroundColor_str = data_arr[currentPhoto_int].backgroundColor_str
		
		
		
	}
	
	
	intervalID_moveTitle = setInterval(moveItem_now,400)
		
	
}
//------------------------
//-----------------------
function add_page(text_str, layout_str, parent_str)
{
	trace("")
	trace("")
	trace("function: add_page()  : text_str:" +text_str + " layout_str:" + layout_str)
	
	
	p=totalPhotoNum_int;
	tempObject = new Object();
		
		tempObject.delay_int = 4000;
		tempObject.photoPath_str = "blank";
		tempObject.description_str = text_str;
		tempObject.parent_str = parent_str;
		
	data_arr.push(tempObject);
	i = data_arr.length
	i--;
	trace(i)
	thisColor_str = substring(highlightColor_str,3,highlightColor_str.length)
	trace(thisColor_str)
	htmlNote_str = '<P ALIGN="LEFT"><FONT FACE="Arial" SIZE="12" COLOR="#'+thisColor_str+'" LETTERSPACING="0" KERNING="0"></FONT></P><P ALIGN="LEFT"><FONT FACE="Arial" SIZE="17" COLOR="#'+thisColor_str+'" LETTERSPACING="0" KERNING="0"><B>Add content here.</B><FONT SIZE="12"></FONT></FONT></P><P ALIGN="LEFT"><FONT FACE="Arial" SIZE="12" COLOR="#'+thisColor_str+'" LETTERSPACING="0" KERNING="0">You can add your content here.  Edit your text using the tools above.  </FONT></P><P ALIGN="LEFT"><FONT FACE="Arial" SIZE="12" COLOR="#'+thisColor_str+'" LETTERSPACING="0" KERNING="0"></FONT></P><P ALIGN="LEFT"><FONT FACE="Arial" SIZE="12" COLOR="#'+thisColor_str+'" LETTERSPACING="0" KERNING="0"><B><I>Resizing the text window</I></B></FONT></P><P ALIGN="LEFT"><FONT FACE="Arial" SIZE="12" COLOR="#'+thisColor_str+'" LETTERSPACING="0" KERNING="0">If you would like to resize the text window use the resize tool at the bottom right hand corner of this text box</FONT></P><P ALIGN="LEFT"><FONT FACE="Arial" SIZE="12" COLOR="#'+this.Color_str+'" LETTERSPACING="0" KERNING="0"></FONT></P><P ALIGN="LEFT"><FONT FACE="Arial" SIZE="12" COLOR="#'+thisColor_str+'" LETTERSPACING="0" KERNING="0"><B><I>Adding a link to text</I></B><B></B></FONT></P><P ALIGN="LEFT"><FONT FACE="Arial" SIZE="12" COLOR="#'+thisColor_str+'" LETTERSPACING="0" KERNING="0">To add a link to a word or phrase follow these steps.  </FONT></P><LI><FONT FACE="Arial" SIZE="12" COLOR="#'+thisColor_str+'" LETTERSPACING="0" KERNING="0">Type in your url in the link box above</FONT></LI><LI><FONT FACE="Arial" SIZE="12" COLOR="#'+thisColor_str+'" LETTERSPACING="0" KERNING="0">Highlight the text you want to apply the link to</FONT></LI><LI><FONT FACE="Arial" SIZE="12" COLOR="#'+thisColor_str+'" LETTERSPACING="0" KERNING="0">Click on the link button to apply</FONT></LI>'

	//htmlNoteSmall_str ='<P ALIGN="LEFT"><FONT FACE="Arial" SIZE="12" COLOR="#'+thisColor_str+'" LETTERSPACING="0" KERNING="0"></FONT></P><P ALIGN="LEFT"><FONT FACE="Arial" SIZE="17" COLOR="+'thisColor_str+'" LETTERSPACING="0" KERNING="0"><B>Add content here.</B></FONT></P><P ALIGN="LEFT"><FONT FACE="Arial" SIZE="12" COLOR="'+thisColor_str+'" LETTERSPACING="0" KERNING="0">You can add your content here.  Edit your text using the tools above. <FONT COLOR="'+thisColor_str+'"></FONT></FONT></P>'
	
	
	if(layout_str == "blank")
	{
	
	}else if(layout_str == "home")
	{
		data_arr[i].hotSpot_arr = new Array();
		//temp = new Object();
		data_arr[i].hotSpot_arr[0] =  new Object();
		data_arr[i].hotSpot_arr[0].x_int = 25
		data_arr[i].hotSpot_arr[0].y_int = 15
		data_arr[i].hotSpot_arr[0].swfPath_str = "highlights.swf"
		data_arr[i].hotSpot_arr[0].note_str = htmlNote_str
		data_arr[i].hotSpot_arr[0].depth_int = 2;
		data_arr[i].hotSpot_arr[0].alpha_int = 100;
		//data_arr[i].hotSpot_arr[0].sourceWidth_int = 667;
		data_arr[i].hotSpot_arr[0].sourceWidth_int = sourceWidth_int
		data_arr[i].hotSpot_arr[0].width_int  = 570;
		data_arr[i].hotSpot_arr[0].height_int  = 275;
		
	}else if(layout_str == "text_only")
	{
		
		data_arr[i].hotSpot_arr = new Array();
		//temp = new Object();
		data_arr[i].hotSpot_arr[0] =  new Object();
		data_arr[i].hotSpot_arr[0].x_int = 10
		data_arr[i].hotSpot_arr[0].y_int = 12
		data_arr[i].hotSpot_arr[0].swfPath_str = "html_text.swf"
		data_arr[i].hotSpot_arr[0].note_str = htmlNote_str
		data_arr[i].hotSpot_arr[0].depth_int = 2;
		data_arr[i].hotSpot_arr[0].alpha_int = 100;
		//data_arr[i].hotSpot_arr[0].sourceWidth_int = 667;
		data_arr[i].hotSpot_arr[0].sourceWidth_int = sourceWidth_int
		data_arr[i].hotSpot_arr[0].width_int  = 325;
		data_arr[i].hotSpot_arr[0].height_int  = 250;
			
	}else if(layout_str == "calendar")
	{
		data_arr[i].hotSpot_arr = new Array();
			
		data_arr[i].hotSpot_arr[0] =  new Object();
		data_arr[i].hotSpot_arr[0].x_int = 20
		data_arr[i].hotSpot_arr[0].y_int = 20
		data_arr[i].hotSpot_arr[0].swfPath_str = "calendar.swf"
		data_arr[i].hotSpot_arr[0].depth_int = 2;
		data_arr[i].hotSpot_arr[0].alpha_int = 100;
		//data_arr[i].hotSpot_arr[0].sourceWidth_int = 667;
		data_arr[i].hotSpot_arr[0].sourceWidth_int = sourceWidth_int
		data_arr[i].hotSpot_arr[0].width_int  = 636;
		data_arr[i].hotSpot_arr[0].height_int  = 271;
		
	}else if(layout_str == "slideshow")
	{
		data_arr[i].hotSpot_arr = new Array();
			
		data_arr[i].hotSpot_arr[0] =  new Object();
		data_arr[i].hotSpot_arr[0].x_int = 40
		data_arr[i].hotSpot_arr[0].y_int = 20
		data_arr[i].hotSpot_arr[0].swfPath_str = "public_slideshows.swf"
		data_arr[i].hotSpot_arr[0].depth_int = 2;
		data_arr[i].hotSpot_arr[0].alpha_int = 100;
		//data_arr[i].hotSpot_arr[0].sourceWidth_int = 667;
		data_arr[i].hotSpot_arr[0].sourceWidth_int = sourceWidth_int
		data_arr[i].hotSpot_arr[0].width_int  = 640;
		data_arr[i].hotSpot_arr[0].height_int  = 470;
	}else if(layout_str == "faq")
	{
		data_arr[i].hotSpot_arr = new Array();
			
		data_arr[i].hotSpot_arr[0] =  new Object();
		data_arr[i].hotSpot_arr[0].x_int = 20
		data_arr[i].hotSpot_arr[0].y_int = 25
		data_arr[i].hotSpot_arr[0].swfPath_str = "faq.swf"
		data_arr[i].hotSpot_arr[0].depth_int = 2;
		data_arr[i].hotSpot_arr[0].alpha_int = 100;
		//data_arr[i].hotSpot_arr[0].sourceWidth_int = 667;
		data_arr[i].hotSpot_arr[0].sourceWidth_int = sourceWidth_int
		data_arr[i].hotSpot_arr[0].width_int  = 490;
		data_arr[i].hotSpot_arr[0].height_int  = 475;
	}else if(layout_str == "contact")
	{
		data_arr[i].hotSpot_arr = new Array();
			
		data_arr[i].hotSpot_arr[0] =  new Object();
		data_arr[i].hotSpot_arr[0].x_int = 12
		data_arr[i].hotSpot_arr[0].y_int = 18
		data_arr[i].hotSpot_arr[0].swfPath_str = "contact.swf"
		data_arr[i].hotSpot_arr[0].depth_int = 2;
		data_arr[i].hotSpot_arr[0].alpha_int = 100;
		//data_arr[i].hotSpot_arr[0].sourceWidth_int = 667;
		data_arr[i].hotSpot_arr[0].sourceWidth_int = sourceWidth_int
		data_arr[i].hotSpot_arr[0].width_int  = 460;
		data_arr[i].hotSpot_arr[0].height_int  = 215;

	}
	currentPhoto_int = i;
	get_photo("scroll")
	//navContainer.timeline1_mc.
	navContainer_mc.timeline1_mc.timeline_start();
	
	
}


function add_photo(thisObject_passed)
{
	
	
	trace("function: add_photo()  :")
	trace("");
	trace("");
	trace("");
	trace("");
	trace("");
	trace("data_arr.length: "+ data_arr.length);
	trace("totalPhotoNum_int"+ totalPhotoNum_int);
	//tempNum_int = totalPhotoNum_int
	//p=totalPhotoNum_int;
	
	
	
	totalPhotoNum_int++;
	p = totalPhotoNum_int;
	p--;
	
	timelineContainer_mc.timeline1_mc.previousSelectedPhoto_int = ccurrentPhoto_int
	timelineContainer_mc.timeline1_mc.recordPhoto_arr.push(p);
	
	data_arr[p] = thisObject_passed;
	
	trace(data_arr[p].videoPath_str)
	trace("");
	trace("");
	trace("");
	trace("");
	trace("");
	trace("");
	intervalID_moveTitle = setInterval(moveItem_now, 200)
		
	
}

//---------------------
//----------------------
function add_siteHeader(photoPath_str, thumbnailPath_str, photoKey_str, rotation_int, ID_int , thisWidth_int, thisHeight_int)
{
	
	trace("function add_siteHeader" + photoPath_str + thumbnailPath_str + photoKey_str)
	//i= mc_arr.length;
	
	siteHeader_bool =  false;
		
	mc_arr[0].photoPath_str = photoPath_str;
	mc_arr[0].thumbnailPath_str = thumbnailPath_str;
	mc_arr[0].photoKey_str = photoKey_str;
	mc_arr[0].rotation_int = rotation_int;
	mc_arr[0].photoId_int = ID_int;
	mc_arr[0].headerWidth_int = thisWidth_int;
	mc_arr[0].headerHeight_int = thisHeight_int;
	
	trace('mc_arr[0].instanceName_str = "header_mc"')
	trace('mc_arr[0].photoPath_str = ' + photoPath_str)
	trace('mc_arr[0].thumbnailPath_str ='+ thumbnailPath_str)
	trace('mc_arr[0].photoKey_str ='+ photoKey_str)
	trace('mc_arr[0].rotation_int ='+ rotation_int)
	trace('mc_arr[0].photoId_int ='+ ID_int)

	loadMovie(ptg.customImage(mc_arr[0], mc_arr[0].headerWidth_int, mc_arr[0].headerHeight_int), background_mc.header_mc.photo_mc);
	
}

//---------------------
//---------------------
function add_title(text_str, textColor_str, bgColor_str, fontName_str, NEWfontSize_int, NEWalign_str, link_str, target_str,bullet_bool){
	
	
	trace("function: add_title()  : text_str:" +text_str+" textColor_str: "+textColor_str+"  bgColor_str: "+bgColor_str+"  fontName_str: "+NEWfontName_str+" NEWfontSize_int: "+NEWfontSize_int+ "NEWalign_str: " + NEWalign_str+link_str+ target_str+bullet_bool)
	//trace("");
	//trace("");
	//trace("");
	//trace("-----------------------ADD TITLE");
	
	p=totalPhotoNum_int;
	
	//trace("data_arr.length: "+data_arr.length);
	
	tempObject = new Object();
	data_arr.push(tempObject);
	
	timelineContainer_mc.timeline1_mc.recordPhoto_arr.push(p);
		
	totalPhotoNum_int++;
	
	data_arr[p] = new Object;
	data_arr[p].delay_int = 4000;
	data_arr[p].swfPath_str = "assets/fonts/"+fontName_str;
	data_arr[p].title_str  = text_str;
	data_arr[p].mainColor_str = "0x"+textColor_str;
	data_arr[p].backgroundColor_str = "0x"+bgColor_str;
	data_arr[p].fontSize_int = NEWfontSize_int;
	data_arr[p].align_str = NEWalign_str;
	data_arr[p].link_str =link_str
	data_arr[p].target_str = target_str
	data_arr[p].bullet_bool = bullet_bool;
	//data_arr[p].transition_str = "assets/transitions/color_fade.swf"
	//data_arr[p].textSize_int = 32;
	
		trace(data_arr[p].align_str)

	intervalID_moveTitle = setInterval(moveItem_now,400)
		
	//preview_mc.build_timeline(preview_mc.currentPage_int, preview_mc.tnMax_int);
}

//---------------------------------------

//---------------------------------------
function editTitleFrame()
{
	
	
	loadCreateTitle(currentPhoto_int)
	
}
	
//---------------------------------------------------------------------

//---------------------------------------------------------------------
function get_graphicSpot(info)

{
	stop_slideshow();
	removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
	whichGraphicSection_int = info; 
	trace("function: get_graphicSpot()")
	this.createEmptyMovieClip("addGraphic_mc", 42203);
	addGraphic_mc._y += windowYPosition_int;
	addGraphic_mc._x += windowXPosition_int;
	
	//loadMovie(toolsDirectory_str+"add_graphic.swf", addGraphic_mc);
	
	if(version_str == "LOCAL")
			{
				loadMovie(toolsDirectory_str+"add_graphic.swf", addGraphic_mc);
				
			}else{
				
				loadMovie(toolsDirectory_str+"add_graphic.swf"+"?timestamp="+timestamp, addGraphic_mc);
			}
}

//----------------------------

//-----------------------------
function get_photo_now2() 
{ 
		trace("function: get_photo_now2()")
			
		addingPhoto_bool = false;
		totalPhotos_int = data_arr.length;
		controlContainer_mc.scroll_mc.gotoAndPlay("scroller");
		
		reloadPreview();
		
		get_photo_info();
		get_photo("scroll");
		
		
		clearInterval(intervalID_addTitle)
		
		
}
//---------------------------------------------------------------------

//---------------------------------------------------------------------
function get_toolBar(){
	
		
	if(accountType_str == undefined || accountType_str == "")
	{
		trace("function: toolBar()")
		ptg.getUserPermission(loadToolBar);
	
	}else{
		
		trace("just load tool bar");
		if(accountType_str == "personal")
		{
			loadToolBar(0)
		}else if(accountType_str == "professional")
		
		{
			loadToolBar(1)
			
		}
	}
}


//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadAddMusic()
{
	
	trace("function loadAddMusic()")
	{
		
	}
	stop_slideshow()
	removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
	
	this.createEmptyMovieClip("addMusic_mc", 42203);
	addMusic_mc._y += windowYPosition_int;
	addMusic_mc._x += windowXPosition_int;
	
	
	
	if(version_str == "LOCAL")
	{
			loadMovie(toolsDirectory_str + "add_music.swf", addMusic_mc);
				
	}else{
			loadMovie(toolsDirectory_str +  "add_music.swf"+"?timestamp="+timestamp, addMusic_mc);
	}
		
}
//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadBuildPages()
{
	stop_slideshow()
	
	this.createEmptyMovieClip("pageContainer_mc", 42202)
	pageContainer_mc._y += 100
	pageContainer_mc._x += 200
	loadMovie(toolsDirectory_str + "page_builder.swf", pageContainer_mc);
}


//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadCreateBlank()
{
		
		stop_slideshow()
		removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
		
		this.createEmptyMovieClip("addTitle_mc", 42203);
		addTitle_mc._y += windowYPosition_int;
		addTitle_mc._x += windowXPosition_int;
		
	//loadMovie(toolsDirectory_str + "add_blank.swf", addTitle_mc);
	
	if(version_str == "LOCAL")
			{
				loadMovie(toolsDirectory_str + "add_blank.swf", addTitle_mc);
				
			}else{
				
				loadMovie(toolsDirectory_str + "add_blank.swf"+"?timestamp="+timestamp, addTitle_mc);
			}
}
//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadCreatePage()
{
	//stop_slideshow()
		removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
		
		this.createEmptyMovieClip("addPage_mc", 42203);
		addPage_mc._y += windowYPosition_int;
		addPage_mc._x += windowXPosition_int;
		
	//loadMovie(toolsDirectory_str + "add_blank.swf", addTitle_mc);
	
	if(version_str == "LOCAL")
			{
				loadMovie(toolsDirectory_str + "add_page.swf", addPage_mc);
				
			}else{
				
				loadMovie(toolsDirectory_str + "add_page.swf"+"?timestamp="+timestamp, addPage_mc);
			}
	
	
	
}

//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadCreatePhoto()
{
		
		stop_slideshow()
		removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
		
		this.createEmptyMovieClip("addPhoto_mc", 42203);
		addPhoto_mc._y += windowYPosition_int;
		addPhoto_mc._x += windowXPosition_int;
		loadMovie(toolsDirectory_str + "add_photo.swf", addPhoto_mc);
	
}

//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadCreateSlideshow()
{
	
		stop_slideshow()
		removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
		
		this.createEmptyMovieClip("addPhoto_mc", 42203);
		addPhoto_mc._y += windowYPosition_int;
		addPhoto_mc._x += windowXPosition_int;
		loadMovie(toolsDirectory_str + "add_slideshow.swf", addPhoto_mc);
	
}
//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadCreateTitle(frame){
		
		stop_slideshow()
		removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
		
		if(frame != undefined)
		{
			
			editingTitleFrame_bool = true
			editingTitleFrame_int = frame;
		
		}else{
			
			editingTitleFrame_bool = false;
			editingTitleFrame_int = undefined;
		}
		
		this.createEmptyMovieClip("addTitle_mc", 42204);
		addTitle_mc._y += windowYPosition_int;
		addTitle_mc._x += windowXPosition_int;
		//loadMovie(toolsDirectory_str+"add_title.swf", addTitle_mc);
		
			if(version_str == "LOCAL")
			{
				loadMovie(toolsDirectory_str+"add_title.swf", addTitle_mc);
				
			}else{
				
				loadMovie(toolsDirectory_str + "add_title.swf"+"?timestamp="+timestamp, addTitle_mc);
			}
}

//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadCreateVideo(info){
		
		stop_slideshow()
		removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
		
		this.createEmptyMovieClip("addVideo_mc", 42203);
		if(info == "embed")
		{
			embedVideo_bool =true
			
		}
		addVideo_mc._y += windowYPosition_int;
		addVideo_mc._x += windowXPosition_int;
		loadMovie(toolsDirectory_str + "add_video.swf", addVideo_mc);
	
}
//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadEditTimeline()
{
	stop_slideshow()
	removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
	this.createEmptyMovieClip("timelineContainer_mc", 42202)
	if(startEditMode_int == 1)
	{
		
		timelineContainer_mc._y =568;
	}
	
	//loadMovie(toolsDirectory_str + "timeline2.swf", timelineContainer_mc);
	
	if(version_str == "LOCAL")
			{
				loadMovie(toolsDirectory_str + "timeline2.swf", timelineContainer_mc);
				
			}else{

				
				loadMovie(toolsDirectory_str + "timeline2.swf"+"?timestamp="+timestamp, timelineContainer_mc);
			}
}

//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadEditNav()
{
	stop_slideshow()
	removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
	//navContainer_mc._y =
	this.createEmptyMovieClip("navContainer_mc", 42202)
	navContainer_mc._y += 135
	
	loadMovie(toolsDirectory_str + "navigation.swf", navContainer_mc);
}
//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadEditTheme(){
	
	stop_slideshow()
	removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
	
	this.createEmptyMovieClip("editTheme_mc", 42203);
	editTheme_mc._y += windowYPosition_int;
	editTheme_mc._x += windowXPosition_int;
	//loadMovie(toolsDirectory_str + "add_theme.swf"+"?timestamp="+timestamp, editTheme_mc);
	
	if(version_str == "LOCAL")
			{
				loadMovie(toolsDirectory_str + "add_theme.swf", editTheme_mc);
				
			}else{
				
				loadMovie(toolsDirectory_str + "add_theme.swf"+"?timestamp="+timestamp, editTheme_mc);
			}
			
	//loadMovie(toolsDirectory_str + "add_theme.swf", editTheme_mc);
}

//---------------------------------------------------------------------

//---------------------------------------------------------------------

function loadHR()
{
	this.createEmptyMovieClip("hrContainer_mc", 42202)
	hrContainer_mc._y += 40
	hrContainer_mc._x += 90
	loadMovie("hr.swf", hrContainer_mc);
}

//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadLinkSlideshow()
{
	
		stop_slideshow();
		removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
		
		this.createEmptyMovieClip("addPhoto_mc", 42203);
		addPhoto_mc._y += windowYPosition_int;
		addPhoto_mc._x += windowXPosition_int;
		loadMovie(toolsDirectory_str + "add_slideshow_link.swf", addPhoto_mc);
	
}

//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadManagePages()
{
	stop_slideshow()
	
	this.createEmptyMovieClip("pageContainer_mc", 42202)
	pageContainer_mc._y += 100
	pageContainer_mc._x += 200
	loadMovie(toolsDirectory_str + "manage_pages.swf", pageContainer_mc);
}
//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadOrderDVD()
{
	
	stop_slideshow()
	removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
		
		
	this.createEmptyMovieClip("orderDVD_mc", 42204);
	orderDVD_mc._y += mediaContainer_mc._y;
	orderDVD_mc._x +=  mediaContainer_mc._x;
	//loadMovie(toolsDirectory_str+"add_title.swf", addTitle_mc);
		
			if(version_str == "LOCAL")
			{
				loadMovie(toolsDirectory_str+"orderDVD.swf", orderDVD_mc);
				
			}else{
				
				loadMovie(toolsDirectory_str + "orderDVD.swf"+"?timestamp="+timestamp, orderDVD_mc);
			}
	
}

//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadSiteColors()
{
	
	stop_slideshow()
	removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
	
	this.createEmptyMovieClip("editTheme_mc", 42203);
	editTheme_mc._y += windowYPosition_int;
	editTheme_mc._x += windowXPosition_int;
	//loadMovie(toolsDirectory_str + "add_theme.swf"+"?timestamp="+timestamp, editTheme_mc);
	
	if(version_str == "LOCAL")
	{
				loadMovie(toolsDirectory_str + "site_colors.swf", editTheme_mc);
				
	}else{
				
				loadMovie(toolsDirectory_str + "site_colors.swf"+"?timestamp="+timestamp, editTheme_mc);
	}
			
	//loadMovie(toolsDirectory_str + "add_theme.swf", editTheme_mc);
}

//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadSiteTitle(frame)
{
		
		trace("function loadSiteTitle(frame)")
		//stop_slideshow()
		removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
		
		this.createEmptyMovieClip("siteTitle_mc", 42204);
		siteTitle_mc._y += windowYPosition_int;
		siteTitle_mc._x += windowXPosition_int;
		//loadMovie(toolsDirectory_str+"add_title.swf", addTitle_mc);
		
		if(version_str == "LOCAL")
		{
			loadMovie(toolsDirectory_str+"site_title.swf", siteTitle_mc);
				
		}else{
				
			loadMovie(toolsDirectory_str + "site_title.swf"+"?timestamp="+timestamp, siteTitle_mc);
		}
}
//---------------------------------------------------------------------

//---------------------------------------------------------------------
function loadToolBar(info)
{
	
	trace("function loadToolBar(info): " + info )
	if(info== 0)
	{
		
		accountType_str = "personal"
		trace(accountType_str);
		
	}else if(info== 1){
		
		accountType_str = "professional"
		trace(accountType_str);
		
		
	}
	
	_root.createEmptyMovieClip("hotSpotToolBar_mc",4999);
	
	if(toolBarPositionX_int == undefined && startEditMode_int == undefined)
	{
	//position tools to the center vertically
		hotSpotToolBar_mc._y =(movieHeight_int *.50)-(326/2);
		
		if(hotSpotToolBar_mc._y < 0)
		{
			
			hotSpotToolBar_mc._y = 2;
			
		}
	}else if(startEditMode_int == 0 || startEditMode_int == undefined ){
		
		hotSpotToolBar_mc._x = toolBarPositionX_int
		hotSpotToolBar_mc._y = toolBarPositionY_int
	
	}else if(startEditMode_int == 1)
	{
		hotSpotToolBar_mc._x = 878;
		hotSpotToolBar_mc._y = 120;
		
	}
	
	
	
	//loadMovie(toolsDirectory_str + "controls_mc.swf", hotSpotToolBar_mc);
	if(version_str == "LOCAL")
			{
				loadMovie(toolsDirectory_str + "controls_mc.swf", hotSpotToolBar_mc);
				
			}else{
				
				loadMovie(toolsDirectory_str + "controls_mc.swf"+"?timestamp="+timestamp, hotSpotToolBar_mc);
			}
}
//---------------------------------------------------------------------

//---------------------------------------------------------------------

function loadWidget()
{
	
	stop_slideshow()
	removeMovieClip(controlContainer_mc.actions_mc.tip_mc);
	
	this.createEmptyMovieClip("addWidget_mc", 42203);
	addWidget_mc._y += windowYPosition_int;
	addWidget_mc._x += windowXPosition_int;
	loadMovie(toolsDirectory_str + "add_widget.swf", addWidget_mc);
	
}

//---------------------------------------------------------------------
//
//---------------------------------------------------------------------
function reloadPreview()
{
	for(i=0; i<mc_arr.length; i++)
			{
				trace(mc_arr[i].instanceName_str)
				if(mc_arr[i].instanceName_str == "preview_mc")
				{
					if(mc_arr[i].visible_bool != false || mc_arr[i].visible_bool == undefined )
					{
						//removeMovieClip(preview_mc);
						//this.createEmptyMovieClip("preview_mc",mc_arr[i].depth_int)
						//preview_mc._x = mc_arr[i].x_int;
						//preview_mc._y = mc_arr[i].y_int;
						
						loadMovie(mcDirectory_str+mc_arr[i].swfPath_str, "preview_mc")
						
						break;
					}
					
				}
				
				
			}
			
			
}

//----------------------------

//-----------------------------

function moveItem_now() 
{ 
	trace("function: moveItem_now()")
	//the position where the photo will be inserted
	photoDropPosition_int = currentPhoto_int;
	
	photoStartPosition_int=p;
	
	//function moves photos
	//add 1 to place to place the new photo to the right of the current photo selected
	photoDropPosition_int++;
	
	//add the data object in the array
	data_arr.splice(photoDropPosition_int,0,data_arr[photoStartPosition_int]);
	
	timelineContainer_mc.timeline1_mc.recordPhoto_arr.splice(photoDropPosition_int,0,timelineContainer_mc.timeline1_mc.recordPhoto_arr[photoStartPosition_int]);
	//trace(recordPhoto_arr)

	////delete item on the end... item was added above
	photoStartPosition_int++;
	data_arr.splice(photoStartPosition_int,1);
	
	
	if(photoWasDeleted_int > 0)
	{
		for(i=0; i<photoWasDeleted_int; i++)
		{
			tempNum_int=data_arr.length;
			tempNum_int--;
			data_arr.splice(tempNum_int,1);
		}
		
	}
	
timelineContainer_mc.timeline1_mc.recordPhoto_arr.splice(photoStartPosition_int,1);	
	
	
	timelineContainer_mc.timeline1_mc.deleteTimeline()
	timelineContainer_mc.timeline1_mc.timeline_start();
	timelineContainer_mc.timeline1_mc.intervalID_dphoto = setInterval(timelineContainer_mc.timeline1_mc.get_dphoto_now, 300)
	
	currentPhoto_int++;
	intervalID_addTitle = setInterval(_root.get_photo_now2, 200)
	
	clearInterval(intervalID_moveTitle)
	
		
	}
	



//---------------------------------------------------------------------

//---------------------------------------------------------------------
function removeAllTools()
{
	trace("function: removeAllTools()")
	if(startEditMode_int != 1)
	{
		removeMovieClip(hotSpotToolBar_mc)
		removeMovieClip(timelineContainer_mc)
	}
	
	removeMovieClip(addTitle_mc)
	removeMovieClip(addPhoto_mc)
	removeMovieClip(editTheme_mc)
	removeMovieClip(addMusic_mc)
	removeMovieClip(addGraphic_mc)
	removeMovieClip(orderDVD)
}

//----------------------------

//-----------------------------
function update_title(text_str, textColor_str, bgColor_str, fontName_str, whichFrame_int, NEWfontSize_int,NEWalign_str, link_str, target_str, bullet_bool)
{
	trace("function update_title: ")
	data_arr[whichFrame_int].swfPath_str = "assets/fonts/"+fontName_str;
	data_arr[whichFrame_int].title_str  = text_str;
	data_arr[whichFrame_int].mainColor_str = "0x"+textColor_str;
	data_arr[whichFrame_int].backgroundColor_str = "0x"+bgColor_str;
	data_arr[whichFrame_int].fontSize_int =  NEWfontSize_int;
	data_arr[whichFrame_int].align_str = NEWalign_str
	data_arr[whichFrame_int].link_str =link_str;
	data_arr[whichFrame_int].target_str = target_str;
	data_arr[whichFrame_int].bullet_bool = bullet_bool;
	
	thisNewColor= new Color(mediaContainer_mc.blank_mc);
	thisNewColor.setRGB(data_arr[whichFrame_int].backgroundColor_str);
	
	titleFrameFormat = new TextFormat();
	titleFrameFormat.color = "0x"+textColor_str;
	
	if(data_arr[whichFrame_int].align_str != undefined)
	{
		titleFrameFormat.align = data_arr[whichFrame_int].align_str;
		
	}
	
	if(data_arr[whichFrame_int].fontsize_int != undefined)
	{
		titleFrameFormat.size = data_arr[whichFrame_int].fontsize_int
		
	}
	
	//textContainer_mc.textBox_mc.setTextFormat(theFormat);
	preview_mc.config_preview();
}


	

function getMC_arr()
{
	trace("function: getMC_arr()")
	
	if(existingTheme_bool){
		//this is theme array loaded in
		mc_arr =  new Array();
	}else if(editTheme_bool){
		mc_arr =  new Array();
		//_root.createEmptyMovieClip("loadTheme_mc", 2)
		loadMovieNum(themeDirectory_str+themePath_str,2)
		trace(themeDirectory_str+themePath_str)
		trace(editTheme_bool);
		intervalID_theme = setInterval(load_NewTheme,200);
		
		//create_mc();
	}else if(changeMask_bool == false && siteEditor_bool != 1)
	{
	
	trace("changeMask_bool: "+changeMask_bool)
			
//  even numbers 
//  27 line_mc
			mc_arr =  new Array();
			mc_arr[0] = new Object();
			
			
			mc_arr[0].instanceName_str = "config_mc";
			mc_arr[0].depth_int = 21111;
						
		//colo
			mc_arr[0].highlightColor_str = "0xffffff"
			mc_arr[0].mainColor_str = "0x808080"
			mc_arr[0].backgroundColor_str = "0x333333"
			
			//x and y of where media container will be placed
			mc_arr[0].x_int=132.5;
			mc_arr[0].y_int=50;
			
			//boolean set for a particular theme, that pulls the current photo you are viewing and displays it in the background
			mc_arr[0].backgroundCurrentPhoto_bool = false;
			mc_arr[0].width_int = 865;
			mc_arr[0].height_int = 570;
			mc_arr[0].photoOptions_bool = true;
			
			//function getShadow_mc(blur_distance_int, blur_angle, blur_color_int , blur_alpha_int , blur_x_int, blur_y_int)
						
			mc_arr[0].title_str = "New Slideshow";
			mc_arr[0].musicPath_str = "";
			//mc_arr[0].musicPath_str = "media/music/noOtherMan.mp3";
			mc_arr[0].password_str = "";
			mc_arr[0].loop_bool = false;
			mc_arr[0].startAutoPlay_bool = false;
			mc_arr[0].shareVisible_bool = true;
						//mc_arr[0].motion_str ="grow"
			mc_arr[0].indexPath_str = "assets/index.swf";
			mc_arr[0].indexOpen_bool = false;
			mc_arr[0].moreSlideshows_bool = true;
			//mc_arr[0].motion_str = "grow"
			mc_arr[0].motion_bool = true;
			
			//mc_arr[0].controller_int = 2;
			//tells the hopstops to only show up when the mouse is over the photo
			mc_arr[0].hotSpotRollOver_bool  = false;
						
			//automatically crops the photo to the theme photo size
			mc_arr[0].autoCrop_bool =false;
			
			mc_arr[0].photoMask_bool=false;
			mc_arr[0].photoShadow_bool=true;
			
			//shows the dynamic border
			mc_arr[0].photoBorder_bool=true;
			
			//meas photo background image that is generated will not adjust to the size of the photo
			mc_arr[0].photoBackgroundFixed_bool = false;
			
			mc_arr[0].borderThickness_int = 1;
			mc_arr[0].shadowAngle_int = 45
			mc_arr[0].shadowDistance_int = 10
			mc_arr[0].shadowX_int = 10
			mc_arr[0].shadowY_int = 10
			mc_arr[0].shadowAlpha_int = .5;
			mc_arr[0].shadowInner_bool = false;
			mc_arr[0].shadowColor_int = "0x000000";
			
			//allows users to adjust the display of the 
			mc_arr[0].customizableDisplay_bool = true;
			//allows users to adjust the color of the theme
			mc_arr[0].customizableColor_bool = true;
			
			
			mc_arr[0].tip_bool = true;
			mc_arr[0].logoVisible_bool= true;
			mc_arr[0].controlsVisible_bool = true;
			
			mc_arr[0].picWidth_int = 600;
			mc_arr[0].picHeight_int = 450;
			mc_arr[0].alpha_int=0;
			//mc_arr[0].maskPath_str ="assets/masks/rectangle.swf" 
			mc_arr[0].buttonPrint_bool = true;
			mc_arr[0].buttonLarger_bool = true;
			//mc_arr[0].buttonSound_bool = true;
			mc_arr[0].buttonDownload_bool = true;
			
			//mc_arr[0].remoteEdit_int = 1;
			
		mc_arr[1] = new Object();
		
			mc_arr[1].instanceName_str = "intro_mc";
			mc_arr[1].swfPath_str ="assets/intro.swf";
			mc_arr[1].backgroundColor_str="0x000000";
			mc_arr[1].resizable_bool = false;
			mc_arr[1].depth_int = 90000;
			
			mc_arr[1].x_int=0;
			mc_arr[1].y_int=0;
			mc_arr[1].visible_bool=true;
			
		mc_arr[2] = new Object();
		
			mc_arr[2].instanceName_str = "controlContainer_mc";
			
			//if(mc_arr[0].controller_int == 2)
			//{
			mc_arr[2].swfPath_str ="assets/controller2.swf"
			//}else{
				
				//mc_arr[2].swfPath_str ="assets/control_bg.swf"
			//}
			mc_arr[2].resizable_bool = false;
			mc_arr[2].depth_int = 111;
	
			
		mc_arr[3] = new Object();
		
			mc_arr[3].instanceName_str = "background_mc";
			//mc_arr[3].swfPath_str ="assets/themes/collage/polaroid.swf";
			mc_arr[3].resizable_bool = true;
			mc_arr[3].depth_int = 1;
			
			mc_arr[3].x_int=0;
			mc_arr[3].y_int=0;
		
			mc_arr[3].width_int=865;
			mc_arr[3].height_int=570;
			
		mc_arr[4] = new Object();
		
			mc_arr[4].instanceName_str = "backgroundPhoto_mc";
			//mc_arr[4].photoPath_str ="media/700x525/6.jpg";
			mc_arr[4].resizable_bool = false;
			mc_arr[4].depth_int = 3;
			mc_arr[4].visible_bool = false;
			mc_arr[4].x_int=0;
			mc_arr[4].y_int=0;
			mc_arr[4].alpha_int=100;
			//mc_arr[4].blur_int=10;
		
			
			
		mc_arr[5] = new Object();
			
			mc_arr[5].instanceName_str = "imageButton_mc";
			mc_arr[5].backgroundColor_str="0xFF0000";
			
			mc_arr[5].depth_int = 8;
			mc_arr[5].maskPath_str ="assets/masks/rectangle.swf" ;
			mc_arr[5].alpha_int=0;
			
		mc_arr[6] = new Object();
		
			mc_arr[6].instanceName_str = "imageBackground_mc";
			mc_arr[6].backgroundColor_str="0x000000";
			mc_arr[6].depth_int = 10;
			mc_arr[6].width_int = 600;
			mc_arr[6].height_int = 450;
			mc_arr[6].alpha_int=100;
			mc_arr[6].maskPath_str = "assets/masks/rectangle.swf";
			mc_arr[6].visible_bool=false;
			
			
		mc_arr[7] = new Object();
		
			mc_arr[7].instanceName_str = "mediaContainer_mc";
			mc_arr[7].depth_int = 14;
			mc_arr[7].width_int = picWidth_int;
			mc_arr[7].height_int = picHeight_int;
			mc_arr[7].maskPath_str =  "assets/masks/rectangle.swf";
			
		mc_arr[8] = new Object();
		
			mc_arr[8].instanceName_str = "transition_mc";
			mc_arr[8].swfPath_str = "assets/transitions/alpha1.swf";
			mc_arr[8].depth_int = 16;
			mc_arr[8].alpha_int=0;
			mc_arr[8].maskPath_str = "assets/masks/rectangle.swf";
		
		mc_arr[9] = new Object();
		
			mc_arr[9].instanceName_str = "border_mc";
			//mc_arr[9].mainColor_str = "0x0033CC";
			mc_arr[9].swfPath_str ="assets/borders/rectangle.swf";
			mc_arr[9].resizable_bool = false;
			mc_arr[9].borderStroke_int = 2;
			mc_arr[9].depth_int = 18;
			mc_arr[9].visible_bool=false;
			
					
		mc_arr[10] = new Object();
		
			mc_arr[10].instanceName_str = "title_mc";
			mc_arr[10].swfPath_str = "assets/fonts/arial.swf";
			mc_arr[10].resizable_bool = false;
			mc_arr[10].fontSize_int = 17;
			mc_arr[10].align_str = "center";
			//mc_arr[10].mainColor_str = "0xffffff";
			mc_arr[10].depth_int = 100;
			mc_arr[10].x_int=238;
			mc_arr[10].y_int=22; 
			/*
		mc_arr[11] = new Object();
			mc_arr[11].instanceName_str = "transition_motion_mc";
			mc_arr[11].swfPath_str = "assets/transitions/color_fade.swf";
			mc_arr[11].depth_int = 42;
			mc_arr[11].maskPath_str = "assets/masks/rectangle.swf";
			
			
		mc_arr[11] = new Object();
		
			mc_arr[11].instanceName_str = "preview_mc";
			mc_arr[11].depth_int = 42;
			mc_arr[11].swfPath_str ="assets/preview/preview_wide.swf";
			mc_arr[11].resizable_bool = false;
			mc_arr[11].x_int = 730;
			mc_arr[11].y_int = 37;
			mc_arr[11].visible_bool = true;
			mc_arr[11].indexX_int = 1
			mc_arr[11].indexY_int =7
			*/
		
		createNum_int=0;
		loadAsset_int = 0
		loadAsset_int--;
		setConfig();
		
	}else if(changeMask_bool== true  && siteEditor_bool != 1)
	{
		trace("mc_arr: "+ mc_arr.length)
		trace("changeMask_bool: "+changeMask_bool)
		//remove contents of previous array
		mask_arrNum = mask_arr.length;
		for(i=0; i<mask_arrNum; i++){
				
			mask_arr.pop();
			trace("delete: mask_arr"+mask_arr.length)
				
		}
		
		trace("mc_arr: "+mc_arr.length)
		createNum_int=0;
		loadAsset_int = 0
		loadAsset_int--;
		setConfig();
		
	}else if( siteEditor_bool == 1){
		
		
			mc_arr =  new Array();
			mc_arr[0] = new Object();
			
			mc_arr[0].photoOptions_bool = false;
			if(presenter_bool != 1)
			{
				
				mc_arr[0].controlsVisible_bool = false;
				
			}else{
				
				mc_arr[0].startAutoPlay_bool = true;
				mc_arr[0].endFrame_bool = false;
			}
			
			
			mc_arr[0].shareVisible_bool = false;
			mc_arr[0].logoVisible_bool = false;
			mc_arr[0].title_str = "Site Title";
			mc_arr[0].instanceName_str = "config_mc";
			mc_arr[0].depth_int = 21111;
			mc_arr[0].siteBuilder_bool = 1;
			//colorList_arr[2] = new Object();
			mc_arr[0].backgroundColor_str = "0xffffff";
			mc_arr[0].mainColor_str = "0x666666";
			mc_arr[0].highlightColor_str = "0x333333";
			
			//x and y of where media container will be placed
			mc_arr[0].x_int=90;
			mc_arr[0].y_int=155;
			
			mc_arr[0].backgroundCurrentPhoto_bool = false;
			mc_arr[0].width_int = 865;
			mc_arr[0].height_int = 700;
			
			mc_arr[0].photoShadowAngle_int = 45
			
			mc_arr[0].indexPath_str = "assets/index.swf";
			mc_arr[0].indexOpen_bool = false;
			
			
			mc_arr[0].autoCrop_bool=false;
			mc_arr[0].photoMask_bool=false;
			mc_arr[0].photoShadow_bool=true;
			mc_arr[0].photoBorder_bool=true;
			mc_arr[0].photoBackgroundFixed_bool = true;
			
			mc_arr[0].borderThickness_int = 0;
			mc_arr[0].shadowAngle_int = 45
			mc_arr[0].shadowDistance_int = 5
			mc_arr[0].shadowX_int = 5
			mc_arr[0].shadowY_int = 5
			mc_arr[0].shadowAlpha_int = .3;
			mc_arr[0].shadowInner_bool = false;
			mc_arr[0].shadowColor_int = "0x000000";
			
			mc_arr[0].customizableDisplay_bool = true;
			mc_arr[0].customizableColor_bool = true;
			
						
			mc_arr[0].tip_bool = true;
			//mc_arr[0].logoVisible_bool= true;
			
			mc_arr[0].picWidth_int = 680;
			mc_arr[0].picHeight_int = 510;
			mc_arr[0].alpha_int=0;
			//mc_arr[0].maskPath_str ="assets/masks/rectangle.swf" 
			mc_arr[0].visible_bool=false;
			
			
			mc_arr[0].photoPath_str = "/original/200410/1097508547_DSC03037.JPG";
			mc_arr[0].thumbnailPath_str ="/thumbnail/200410/1097508547_DSC03037.JPG";
			mc_arr[0].photoKey_str ="1357fa14d1d5ac6cf46cd9548b9d2b4f";
			mc_arr[0].rotation_int =0;
			mc_arr[0].photoId_int =2475;
			mc_arr[0].headerWidth_int = 836;
			mc_arr[0].headerHeight_int =120;
			
		mc_arr[1] = new Object();
		
			mc_arr[1].instanceName_str = "intro_mc";
			mc_arr[1].swfPath_str ="assets/intro.swf";
			mc_arr[1].backgroundColor_str="0x000000";
			mc_arr[1].resizable_bool = false;
			mc_arr[1].depth_int = 90000;
			
			mc_arr[1].x_int=0;
			mc_arr[1].y_int=0;
			mc_arr[1].visible_bool=true;
			
		mc_arr[2] = new Object();
		
			mc_arr[2].instanceName_str = "background_mc";
			mc_arr[2].swfPath_str ="assets/themes/site/base2.swf";
			//mc_arr[2].swfPath_str ="assets/themes/clients/acme.swf";
			mc_arr[2].resizable_bool = false;
			mc_arr[2].depth_int = 1;
			
			mc_arr[2].x_int=0;
			mc_arr[2].y_int=0;
		
			//mc_arr[3].width_int=865;
			//mc_arr[3].height_int=570;
			
		mc_arr[3] = new Object();
		
			mc_arr[3].instanceName_str = "backgroundPhoto_mc";
			//mc_arr[4].photoPath_str ="media/700x525/6.jpg";
			mc_arr[3].resizable_bool = false;
			mc_arr[3].depth_int = 3;
			mc_arr[3].visible_bool = false;
			mc_arr[3].x_int=0;
			mc_arr[3].y_int=0;
			mc_arr[3].alpha_int=100;
			//mc_arr[4].blur_int=10;
		
		mc_arr[4] = new Object();
		
			mc_arr[4].instanceName_str = "imageBackground_mc";
			mc_arr[4].backgroundColor_str="0x000000";
			mc_arr[4].depth_int = 10;
			mc_arr[4].width_int = picWidth_int;
			mc_arr[4].height_int = picHeight_int;
			mc_arr[4].alpha_int=100;
			mc_arr[4].maskPath_str = "assets/masks/rectangle.swf";
			//mc_arr[6].visible_bool=true;
			
		mc_arr[5] = new Object();
		
			mc_arr[5].instanceName_str = "mediaContainer_mc";
			mc_arr[5].depth_int = 14;
			mc_arr[5].width_int = picWidth_int;
			mc_arr[5].height_int = picHeight_int;
			//mc_arr[5].maskPath_str =  "assets/masks/rectangle_rounded_site.swf";
			mc_arr[5].maskPath_str =  "assets/masks/rectangle.swf";
			
		mc_arr[6] = new Object();
		
			mc_arr[6].instanceName_str = "transition_mc";
			mc_arr[6].swfPath_str = "assets/transitions/color_fade_site.swf";
			mc_arr[6].depth_int = 16;
			
			mc_arr[6].maskPath_str = "assets/masks/rectangle.swf";
		
		mc_arr[7] = new Object();
		
			mc_arr[7].instanceName_str = "border_mc";
			mc_arr[7].swfPath_str ="assets/borders/rectangle.swf";
			mc_arr[7].resizable_bool = false;
			mc_arr[7].borderStroke_int = 2;
			mc_arr[7].depth_int = 18;
			mc_arr[7].visible_bool=false;
			
			 
	mc_arr[8] = new Object();
		
			mc_arr[8].instanceName_str = "title_mc";
			mc_arr[8].swfPath_str = "assets/fonts/arial.swf";
			mc_arr[8].mainColor_str = "0xffffff";
			mc_arr[8].resizable_bool = false;
			//mc_arr[10].mainColor_str = "0xffffff";
			mc_arr[8].fontSize_int = 27;
			mc_arr[8].depth_int = 100;
			mc_arr[8].x_int=40;
			mc_arr[8].y_int=33; 
			mc_arr[8].align_str = "Left";
			mc_arr[8].visible_bool = true;
			
			
	mc_arr[9] = new Object();
		
			mc_arr[9].instanceName_str = "controlContainer_mc";
			if(version_str == "LOCAL")
			{
				mc_arr[9].swfPath_str ="assets/control_bg.swf";
				
			}else{
				
				mc_arr[9].swfPath_str ="assets/control_bg.swf"+"?timestamp="+timestamp;
			}
			//mc_arr[2].swfPath_str ="assets/control_bg.swf";
			mc_arr[9].resizable_bool = false;
			mc_arr[9].depth_int = 111;
	
	mc_arr[10] = new Object();
		
			mc_arr[10].instanceName_str = "navContainer_mc";
			mc_arr[10].swfPath_str = "tools/navigation_horizontal.swf";
			//mc_arr[11].swfPath_str = "tools/.swf";
			mc_arr[10].resizable_bool = false;
			mc_arr[10].depth_int = 113;
			mc_arr[10].x_int= 30;
			mc_arr[10].y_int=120; 
			mc_arr[10].visible_bool=true;
			
	/*
	mc_arr[11] = new Object();
		
			mc_arr[11].instanceName_str = "siteLogo_mc";
			mc_arr[11].swfPath_str = "assets/site_logo.swf";
			//mc_arr[11].swfPath_str = "tools/.swf";
			mc_arr[11].resizable_bool = false;
			mc_arr[11].depth_int = 115;
			mc_arr[11].x_int= 700;
			mc_arr[11].y_int=630; 
			mc_arr[11].visible_bool=true;
	*/
			/*
	mc_arr[11] = new Object();
		
			mc_arr[11].instanceName_str = "presentation_mc";
			mc_arr[11].swfPath_str = "assets/control_presentation.swf";
			//mc_arr[11].swfPath_str = "tools/.swf";
			mc_arr[11].resizable_bool = false;
			mc_arr[11].depth_int = 339;
			mc_arr[11].x_int=0;
			mc_arr[11].y_int=600; 
			mc_arr[11].visible_bool=true;
			*/
			
			
			
						
		createNum_int=0;
		loadAsset_int = 0
		loadAsset_int--;
		setConfig();
	
	}
		
		
}

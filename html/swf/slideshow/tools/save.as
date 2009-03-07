

//----------------------------------------------
//slideshowKey_str passed in through the URL
function saveRsp(info){
	
	trace("function: saveRsp(info): " + info)
	
	//info = info.parseJSON();
	if(slideshow.US_KEY == undefined){ // create a new slideshow
		//slideShowKey_str = info;
		slideshow.US_KEY = info;
		slideshowKey_str = info;
	}
	controlContainer_mc.actions_mc.login_mc.saving_mc._visible = true;
	controlContainer_mc.actions_mc.login_mc._visible = true;
	controlContainer_mc.actions_mc.login_mc.saving_mc.gotoAndStop(2);
	//trace(info.length)
	if(info.length == 32) // created successfully
	{
		trace("32 KEY"+ info)
		trace("_root.openSharePage_bool: "+_root.openSharePage_bool)
		controlContainer_mc.actions_mc.login_mc.saving_mc._visible = true;
		controlContainer_mc.actions_mc.login_mc._visible = true;
		controlContainer_mc.actions_mc.login_mc.saving_mc.gotoAndStop(2);
		if(_root.openSharePage_bool == true)
		{
			
			
			if(slideshowKey_str == undefined)
			{
				
				slideshowKey_str = slideshow.US_KEY;
			
			}
			
			trace("THIS URL: "+serverName_str+"/?action=home.slideshow_share&KEY="+slideshowKey_str)
			getURL(serverName_str+"/?action=home.slideshow_share&KEY="+slideshowKey_str, "_blank")
			openSharePage_bool =  false
			
			
			
		}
		
		if(openFullWindow_bool == true)
		{
			
						
			getURL(serverName_str+"/?action=flix.flix_form&key="+slideshowKey_str)
			openFullWindow_bool = false;
			
		}else if(openPreview_bool == true)
		{
			
						
			getURL(serverName_str+"/slideshow?"+slideshowKey_str,"_blank")
			openPreview_bool = false;
			
		}else if(openDVD_bool == true)
		{
			
			loadOrderDVD();
			
		}
	}
	else  if(info == true)
	{ 
	// updated successfully
	
		controlContainer_mc.actions_mc.login_mc.saving_mc.gotoAndStop(2);
		trace("UPDATED"+ info)
		
		if(_root.openSharePage_bool == true)
		{
			trace("THIS URL: "+serverName_str+"/?action=home.slideshow_share&KEY="+slideshowKey_str)
			getURL(serverName_str+"/?action=home.slideshow_share&KEY="+_root.slideshowKey_str, "_blank")
			openSharePage_bool =  false
		
		}else if(openFullWindow_bool == true)
		{
			
			getURL(serverName_str+"/?action=flix.flix_form&key="+slideshowKey_str)
			openFullWindow_bool = false;
			
		}else if(openPreview_bool == true)
		{
			
			getURL(serverName_str+"/slideshow?"+slideshowKey_str+"/remoteEdit_int-1","_blank")
			openPreview_bool = false;
			
		}else if(openDVD_bool == true)
		{
			//controlContainer_mc.actions_mc.login_mc.saving_mc.gotoAndStop(2);
			loadOrderDVD();
			
		}
		
	}
	else // something went wrong
	{
		trace("DMAN FOO WHAT U DO? "+ info)
		controlContainer_mc.actions_mc.login_mc.saving_mc.gotoAndStop(3);
	}
	
	//backupMC_arr= mc_arr;
	
	//backupData_arr = data_arr;
	
	//checkSaved_id = setInterval(checkHash, 5000);
	
}

function save_slideshow(from)
{
	
	trace("idMatch_bool: "+idMatch_bool + loggedIn_bool)
	controlContainer_mc.actions_mc.login_mc.saving_mc._visible = true;
	controlContainer_mc.actions_mc.login_mc._visible = true;
	controlContainer_mc.actions_mc.login_mc.saving_mc.save_mc.gotoAndPlay(2)
	
	if(from == "share")
	{
		trace("from:  "+from);
		openSharePage_bool = true;
	
	}else if(from == "full")
	{
		
		openFullWindow_bool = true;
		
	}else if(from == "preview")
	{
		
		openPreview_bool = true;
		
	}else if(from == "dvd")
	{
		openDVD_bool  = true;
		
	}
	trace("function: save_slideshow()")
	//title_str = "DAMN FOOL"
	slideshow.NAME =  title_str;
	//trace(slideshow)
	ptg.saveSlideshow(slideshow, mc_arr, data_arr, saveRsp)
	
}
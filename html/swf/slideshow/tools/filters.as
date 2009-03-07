

import flash.filters.BlurFilter;

import flash.filters.DropShadowFilter;
//------------------------------------------------
//BLUR
//------------------------------------------------
function blurPhotoBackground()
{
	trace("function blurPhotoBackground()")
	backgroundPhoto_mc.filters = blurfilterArray;
	trace(blurfilterArray)
	trace(blurX)
	trace(blurY)
	clearInterval(blurPhotoBG_id)
}

function blurBackground(amount, blurPhoto)
{
	delete blurfilterArray
	trace("function: blurBackground:  amount" + amount )
	
	var blurX:Number = amount;//values are from 0 to 255
	var blurY:Number = amount;//values are from 0 to 255
	var quality:Number = 3;
	var our_blur:BlurFilter = new BlurFilter(blurX, blurY, quality);
	var blurfilterArray:Array = new Array();
	blurfilterArray.push(our_blur);
	trace("blurfilterArray: "+ blurfilterArray)
	trace(blurX)
	trace(blurY)
	trace("backgroundPhoto_mc: " + backgroundPhoto_mc)
	if(blurPhoto == true)
	{
		
		mediaContainer_mc.filters = blurfilterArray;
	
	}else{
		
		backgroundPhoto_mc.filters = blurfilterArray;
		//blurPhotoBG_id = setInterval(blurPhotoBackground, 5000);
	}
	
}
//-----------------------------------------

//----------------------------------------



distance = 15;
//shadow_angle = 45
//getShadow_mc(photoShadowDistance_int, photoShadowAngle_int, photoShadowColor_int , photoShadowAlpha_int , photoShadowX_int, photoShadowY_int, "imageBackground_mc")
function getShadow_mc(shadow_distance_int, shadow_angle, shadow_color_int , shadow_alpha_int , shadow_x_int, shadow_y_int, shadow_inner_bool, first_mc, second_mc, third_mc)
{
	
	//trace("function: getShadow_mc()  first_mc:" +first_mc+second_mc+third_mc)
	
	/*
	trace("shadow_distance_int: "+shadow_distance_int)
	trace("shadow_angle: "+shadow_angle)
	trace("shadow_color_int: "+shadow_color_int)
	trace("shadow_alpha_int: "+shadow_alpha_int)
	trace("shadow_x_int: "+shadow_x_int)
	trace("shadow_y_int: "+shadow_y_int)
	*/
	//This is the offset the shadow has in pixels
	var distance:Number = shadow_distance_int;
	
	//Here we set the angle the shadow makes.
	var angle:Number= shadow_angle;
	
	//Set the color of the shadow
	var color:Number=shadow_color_int;
	
	//this sets the alpha of the shadow, dont use values like 10,60 etc
	//instead use values like .1 and .6
	var alpha:Number=shadow_alpha_int;
	
	//Setup how much horizontal shadow you want to have. 
	var shadowX:Number=shadow_x_int;
	
	//Setup how much vertical shadow you want to have. 
	var shadowY:Number=shadow_y_int;
	
	//This value means the strenght of the spread of the shadow
	//very high values wont give a nice result
	var strength:Number=1;
	
	//This represents the number of times the dropShadowFilter is applied to your object
	//so if you set this to a higher number the shadow will look more clear.
	var quality:Number=3;
	
	//Set this variable to true and you will have an inner shadow
	var inner:Boolean= shadow_inner_bool;
	
	//If you set 'knockout' to true it will make the object's fill transparent and reveals the background color of the document.
	var knockout:Boolean=false;
	
	//if you set this variable to 'true' the object on which we dropped the shadow will be inviible, so you
	//only see the shadow itself
	var hideObject:Boolean=false;
	
	//Here we actually create the drop shadow by creating an instance of the DropShadowFilter with the keyword 'new'.
	//We give this new instance the name 'our_shadow'.
	//The variables between the (...) are the variables we just setup above and those values are plugged into here to create the 
	//shadow how we wanted it to be.
	var our_shadow:DropShadowFilter = new DropShadowFilter(distance,angle,color,alpha,shadowX,shadowY,strength,quality,inner,knockout,hideObject);
	
	//Here we create a new empty array  
	var filterArray:Array = new Array();
	
	//In our array we have to push our DropShadowFilter instance.
	filterArray.push(our_shadow);
	//filters is applied as an array, so we plugin the filtersArray in it.
	//trace(second_mc)
	//trace(first_mc)
	
	
	if(third_mc != undefined )
	{
		trace("third_mc: "+third_mc)
		_root[first_mc][second_mc][third_mc].graphic_mc.filters = filterArray;
		
	}else if(second_mc != undefined && third_mc == undefined)
	{
		
		_root[first_mc][second_mc].filters = filterArray;
		
	}else{
		
		trace(_root[first_mc])
		_root[first_mc].filters = filterArray;
		
		
	}
	
	//here we setup and onRelease event, so when you press the square it will have a different shadow each time
	/*
	square.onRelease = function() {
		//get the current dropShadow ('this' refers to 'square', so this.filters[0] means square.filter)
		//Because filters is an array we get the first item from that array with filters[0] which is our dropShadow instance
		var filter:DropShadowFilter = this.filters[0];
		
		//reset the alpha transparency to random value between .1 and 1
		filter.alpha = .1 + Math.random()*.9;
		
		//reset the filters array with this newly updated shadow
		this.filters = new Array(filter);
	}
	*/
}

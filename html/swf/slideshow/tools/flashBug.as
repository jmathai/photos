/*
*  flashBug v1.3
*  by Aaron Bassett (htp://www.foobr.co.uk)
*
*  Use the Firebug console to debug flash
*  Thanks to joe hewitt for Firebug (http://www.getfirebug.com/)
*
*  Copyright (C) 2007  Aaron Bassett
*  http://creativecommons.org/licenses/LGPL/2.1/
*
*
*/

import flash.external.*;

class tools.flashBug {

	var doTrace;
	var live;

	var lastGroup;
	var groupPrefix;
	var timers;

	var warnBoundary;
	var errorBoundary;

	var prefixLog;
	var prefixInfo;
	var prefixDebug;
	var prefixWarning;
	var prefixError;
	var prefixGroup;
	
	/*
	  Setup flashBug
	*/
	function flashBug(doTrace, warnBoundary, errorBoundary) {

		this.live = false;
		this.timers = new Object();

		//setup trace if required
		if(doTrace) {
			this.doTrace = true;
			// Boundaries help highlight important messages in output console
			// as we cant color them :(
			this.warnBoundary  = (warnBoundary)  ? warnBoundary  : '--------------------------------------------------------------';
			this.errorBoundary = (errorBoundary) ? errorBoundary : '**************************************************************';
			this.setPrefixes();
		} else {
			this.doTrace = false;
		}
	}

	/* 
	*  When ready finished debugging & pushing live
	*  call console.goLive(); rather than having to remove all debug messages from code
	*  should save errors in browsers without Firebug & stop people snooping your
	*  debug messages
	*/
	function goLive() {
		this.live = true;
	}
	
	/*
	  Prefixes for trace()
	  Make sure they all aline up nicely in output console
	*/
	function setPrefixes() {
		this.prefixLog      = "LOG:       ";
		this.prefixInfo     = "INFO:      ";
		this.prefixDebug    = "DEBUG:     ";
		this.prefixWarning  = "WARNING:   ";
		this.prefixError    = "ERROR:     ";
		this.prefixGroup    = "";
	}
	
	/*
	  Can be used to check if the firebug is responding/installed
	*/
	function available() {
		if(this.live) return;
		return (ExternalInterface.call("console.firebug.toString") == null) ? false : true;
	}

	function log(msg) {
		if(this.live) return;
		if(this.doTrace) trace(this.prefixGroup + this.prefixLog + msg);
		ExternalInterface.call("console.log", msg);
	}
	
	function info(msg) {
		if(this.live) return;
		if(this.doTrace) trace(this.prefixGroup + this.prefixInfo + msg);

		ExternalInterface.call("console.info", msg);
	}

	function debug(msg) {
		if(this.live) return;
		if(this.doTrace) trace(this.prefixGroup + this.prefixDebug + msg);
		ExternalInterface.call("console.debug", msg);
	}

	function warn(msg) {
		if(this.live) return;
		if(this.doTrace) {
			trace(this.prefixGroup + this.warnBoundary);
			trace(this.prefixGroup + this.prefixWarning + msg);
			trace(this.prefixGroup + this.warnBoundary);
		}
		ExternalInterface.call("console.warn", msg);
	}

	function error(msg) {
		if(this.live) return;
		if(this.doTrace) {
			trace(this.prefixGroup + this.errorBoundary);
			trace(this.prefixGroup + this.prefixError + msg);
			trace(this.prefixGroup + this.errorBoundary);
		}
		ExternalInterface.call("console.error", msg);
	}
	
	/*
	  All group messages are prefixed to show they are in a group
	*/
	function group(msg) {
		if(this.live) return;
		this.lastGroup = msg;
		this.prefixGroup = " //  ";
		
		if(this.doTrace) {
			trace("");
			trace(this.prefixGroup + "[ Start group: " + msg + " ]");
		}
		ExternalInterface.call("console.group", msg);
	}

	function groupEnd() {
		if(this.live) return;
		if(this.doTrace) {
			trace(this.prefixGroup + "[ End group: " + this.lastGroup + " ]");
			trace("");
		}
		ExternalInterface.call("console.groupEnd");
		this.prefixGroup = '';
	}

	function time(name) {
		if(this.live) return;
		this.timers[name] = getTimer();
		ExternalInterface.call("console.time", name);
	}
	
	function timeEnd(name, returnTime) {
		if(this.live) return;
		var timeTaken = getTimer() - this.timers[name];
		if(returnTime) return timeTaken;

		if(this.doTrace) trace(this.prefixGroup + name +": " +timeTaken+"ms");
		ExternalInterface.call("console.timeEnd", name);
	}

	function assert(expression) {
		if(this.live) return;
		if(this.doTrace) {
			var check = Boolean(expression);
			if(check==false) {
				trace(this.prefixGroup + this.errorBoundary);
				trace(this.prefixGroup + this.prefixError +"Assertion Failure [ "+expression+" ]");
				trace(this.prefixGroup + this.errorBoundary);
			}
		}
		ExternalInterface.call("console.assert", expression);
	}
	
	// no trace() for this - Firebug only!
	function profile(name) {
		if(!this.live) ExternalInterface.call("console.profile", name);
	}

	function profileEnd(name) {
		if(!this.live) ExternalInterface.call("console.profileEnd", name);
	}

	function count(title) {
		if(!this.live) ExternalInterface.call("console.count", title);
	}

	function spacer() {
		if(this.live) return;
		if(this.doTrace) trace("");
		ExternalInterface.call("console.debug", "     ");
	}
}
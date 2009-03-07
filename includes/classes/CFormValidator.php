<?php
/*
	formValidator (version 1.0) [2/26/2002]
	parameters: 
		thisForm: number of form in document indexed at zero or name of form [required : numeric or string]
		theseFields: comma delimited list of field names or numbers to be validated [required : numeric or string]
		theseFieldNames: comma delimited list of string names for field (displayed to user) [required : string values]
		theseType: comma delimited list of string values specifying type of validation [required : length/email/numeric/minimumvalue/maximumvalue/minimumdate/maximumdate]
		theseMessages: comma delimited list of string to be displayed to user - description of field
		headerMessage: string to be displayed above error when validation does not pass [not required]
		maxElementsToDisplay: integer to set maximum number of errors to display.  keeps alert box from becoming too large (vertically) [not required : integer : default = "10"]
		javascriptSubmit: specify wether or not to submit form via javascript or to return true/false [not required : default="false"]
		debugOutput: specify to show debugging information [not required : true/false]
		
	example:
    $validator = new formValidator();
    $validator->setForm('myFormName');
    $validator->addElement('myFormField1', 'Field Name 1', '  - Error Message 1', 'length');
    $validator->addElement('myFormField2', 'Field Name 2', '  - Error Message 2', 'checkboxmax3');
    $validator->setDebugOutput(false);
    $validator->setFunctionName('myFunctionName');
    $validator->validate();
    
	NOTES:
		to use minumumnumber to test for a minimum value of 1000 use the following format:
			thisType="minumumnumber1000"
		to use maximumnumber to test for a maximum value of 1000 use the following format:
			thisType="maximumnumber1000"
		to use minimumdate to test for a minimum date of 1/1/2001 use the following format:
			thisType="minimumdate01/01/2001"
		to use maximumdate to test for a maximum date of 1/1/2001 use the following format:
			thisType="maximumdate01/01/2001"
		to use selectbox to test for an unaccepted value of null use the following format:
			thisType="selectboxnull"
		to use minimumlength to test for a minimum length of 5 use the following format:
			thisType="minimumlength5"
		to use maximumlength to test for a maximum length of 10 use the following format:
			thisType="maximumlength10"
		to use exactlenth to test for an exact length of 8 use the following format:
			thisType="exactlength8"
		to use checkboxmin to test for minimum number of checked checkboxes being less than 3 use the following format:
			thisType="checkboxmin3"
		to use checkboxmax to test for maximum number of checked checkboxes being more than 3 use the following format:
			thisType="checkboxmax3"
		to use regexp to test for a regular expression of an alpha value use the following format:
			thisType="regexp/^[a-zA-Z\s-]+$/"
*/
    
    class CFormValidator
    {
        
        function setForm($form){
            $this->form = $form;
        }
        
        function setTheseFields($fields){
            if(is_array($fields)){
                $this->theseFields = implode(',', $fields);
            }else{
                $this->theseFields = $fields;
            }
        }
        
        function setTheseFieldNames($fieldNames){
            if(is_array($fieldNames)){
                $this->theseFieldNames = implode(',', $fieldNames);
            }else{
                $this->theseFieldNames = $fieldNames;
            }
        }
        
        function setTheseTypes($types){
            if(is_array($types)){
                $this->theseTypes = implode(',', $types);
                $this->arrTypes = $types;
            }else{
                $this->theseTypes = $types;
                $this->arrTypes = array($types);
            }
        }
        
        function setTheseMessages($messages){
            if(is_array($messages)){
                $this->theseMessages = implode(',', $messages);
            }else{
                $this->theseMessages = $messages;
            }
        }
        
        function setHeaderMessage($headerMessage){
            $this->headerMessage = $headerMessage;
        }
        
        function setMaxElementsToDisplay($max){
            $this->maxElementsToDisplay = $max;
        }
        
        function setFunctionName($fName){
            $this->functionName = $fName;
        }
        
        function setJavascriptSubmit($bool){
            $this->javascriptSubmit = $bool;
        }
        
        function setDebugOutput($bool){
            $this->debugOutput = $bool;
        }
        
        function setEval($str)
        {
          $this->eval = $str;
        }
        
        function addElement($elementName, $displayName, $displayMessage, $valType){
            if(!is_array($this->theseFields)){
                $this->theseFields = array();
            }
            array_push($this->theseFields, $elementName);
            
            if(!is_array($this->theseFieldNames)){
                $this->theseFieldNames = array();
            }
            array_push($this->theseFieldNames, $displayName);
            
            if(!is_array($this->theseMessages)){
                $this->theseMessages = array();
            }
            array_push($this->theseMessages, $displayMessage);
            
            if(!is_array($this->theseTypes)){
                $this->theseTypes = array();
            }
            if(!is_array($this->arrTypes)){
                $this->arrTypes = array();
            }
            array_push($this->theseTypes, $valType);
            array_push($this->arrTypes, $valType);
        }
        
        function noProceed(){
            $this->proceed = false;
        }
        
        function setDefaults(){
            // initially set proceed to true
            // set to false when error occurs
            $this->proceed = true;
            if(!isset($this->eval) || empty($this->eval)){
              $this->eval = false;
            }
            
            if(strlen($this->form) == 0){
                $this->form = 0;
            }
            if(!is_array($this->theseFields)){
              if(strlen($this->theseFields) == 0)
              {
                $this->noProceed();
              }
            }
            
            if(!is_array($this->theseFields)){
              if(strlen($this->thisType) == 0)
              {
                $this->noProceed();
              }
            }
            
            if(strlen($this->headerMessage) == 0){
                $this->headerMessage = 'Please correct the following information.';
            }
            if(!is_numeric($this->maxElementsToDisplay)){
                $this->maxElementsToDisplay = 10;
            }
            if(strlen($this->functionName) == 0){
                $this->functionName = 'jsFormValidator';
            }
            if(strlen($this->javascriptSubmit) == 0){
                $this->javascriptSubmit = false;
            }
            if(strlen($this->debugOutput) == 0){
                $this->debugOutput = false;
            }
            
            // start imploding if arrays
            if(is_array($this->theseFields)){
                $this->theseFields = implode(',', $this->theseFields);
            }
            if(is_array($this->theseFieldNames)){
                $this->theseFieldNames = implode(',', $this->theseFieldNames);
            }
            if(is_array($this->theseMessages)){
                $this->theseMessages = implode(',', $this->theseMessages);
            }
            if(is_array($this->theseTypes)){
                $this->theseTypes = "'" . str_replace("`,", ',', preg_replace('/(?<!`)\,/', "','", implode(',', $this->theseTypes))) . "'";
            }
            if(is_array($this->arrTypes)){
                $this->strTypes = implode(" ", $this->arrTypes);
            }
        }
        
        /*
        *   function to check match in array
        *   used because array_search() and in_array() don't do wildcard searches
        */
        function checkArray($val){
            return true;
//            return (strlen($val, $this->checkType) > 0) ? (true) : (false);
        }
        
        function test(){
            $this->temp = array('a', 'bb', 'ccc');
            return array_filter($this->temp, "checkArray");
        }
        
        /*
        * function to write hidden fields which will be used by server side form validator
        */
        function prepareServer($names = '_validator_names', $types = '_validator_types', $messages = '_validator_messages'){
            $return =   "\n<input type=\"hidden\" name=\"$names\" value=\"".$this->theseFields."\"/>";
            $return .=  "\n<input type=\"hidden\" name=\"$types\" value=\"".$this->theseTypes."\"/>";
            $return .=  "\n<input type=\"hidden\" name=\"$messages\" value=\"".$this->theseMessages."\"/>";
            
            return $return;
        }
        
        /*
        *   method called to write javascript to window
        *   calls setDefaults() method to ensure all required data is set
        */
        function validate(){
            // validate data
            $this->setDefaults();
            if($this->proceed == true){
                echo '
                       <script language="javascript" type="text/javascript">
                          var _CFormValidatorPassed = false;
                          function '.$this->functionName.'(){	// start validation function'."\n
                            function __mod10(ccNumber)
                            {
                              // get the length
                              var ccLen = ccNumber.length;
                              
                              // for every digit starting from the right
                              //   if it's divisible by 2 then double it
                              //     if the doubled version is > 9 then sum its digits
                              //   add this to a running sum
                              var sum = 0;
                              var mod = ccLen % 2;
                              var temp;
                              for(i = (ccLen-1); i >= 0; i--)
                              {
                                temp = parseInt(ccNumber.charAt(i));
                                if(i % 2 == mod)
                                {
                                  temp *= 2;
                                  if(temp > 9)
                                  {
                                    temp = parseInt((temp / 10) + (temp % 10));
                                  }
                                }
                                     sum += temp;
                              }
                                 // see if it passes the mod 10 test
                              return ((sum % 10 == 0 && sum != 0) ? true : false);
                            }            
                    ";
                if(is_numeric($this->form)){
                  echo '      thisForm = document.forms['.$this->form.'];	// this sets thisForm to the form object';
                }else{
                  echo '      thisForm = document.forms[\''.$this->form.'\'];	// this sets thisForm to the form object';
                }
                echo '
                            theseFields = new Array(\''.str_replace(",","','",str_replace("'","\'",$this->theseFields)).'\');	// theseFields is an array of numeric or name values corresponding to the field names or numbers which need to validated
                            theseFieldNames = new Array(\''.str_replace(",","','",str_replace("'","\'",$this->theseFieldNames)).'\');	// theseFieldNames is an array of *user defined* strings correlating to theseFields
                            theseTypes = new Array(' . $this->theseTypes . ');	// thisType is an array of strings with values of [email,length,numeric]
                            theseMessages = new Array(\''.str_replace(",","','",str_replace("'","\'",$this->theseMessages)).'\');	// theseMessages is an array of *user defined* strings correlating to theseFields
                            numOfFields = theseFields.length;
                            maxElementsToDisplay = '.$this->maxElementsToDisplay.';	// set max items to display when validation fails';
                if($this->debugOutput == true){
                    echo '
                          // check to see if jsDebugOutput is wanted
                          // set debug info
                          // first alert is a length comparison for each passed attribute
                          // these should match in length
                          showDebug = "";
                          showDebug += "The following numbers must be equal.\n";
                          showDebug += "   " + theseFields.length + " - theseFields.\n";
                          showDebug += "   " + theseTypes.length + " - thisType.\n";
                          alert(showDebug);
                          // second alert is to match descriptive field name to validation type
                          fieldsHeader = "Here is a list of the fields and validation types (10 at a time).\n";
                          showDebug = "";
                          startIndex = 0;
                          // start displaying 10 at a time
                          while( startIndex + 1 <= theseFields.length ){	// run while there are fields left
                            for( innerLoop = 0; innerLoop < 10; innerLoop++ ){
                              if( theseFieldNames[startIndex] ){
                                showDebug += "   " + theseFieldNames[startIndex] + " - " + theseTypes[startIndex] + "\n";
                              }
                              startIndex++;
                            }
                            alert(fieldsHeader + showDebug);
                            showDebug = "";
                            innerLoop = 0;
                          }
                          return false;'."\n";
                }else{
                    echo '
                          // proceed with validation
                          fieldsToCorrect = new Array();	// blank array to hold fields which do not pass validation
                          for( i1 = 0; (i1 < numOfFields && fieldsToCorrect.length < maxElementsToDisplay); i1++ ){	// loop through numOfFields
                            // generate value for this field
                            thisType = theseTypes[i1];
                            // generate value for this field
                            if( !isNaN(theseFields[i1]) ){	// check if field is specified by number or name
                              if( theseTypes[i1].search(\'selectbox\') != -1 ){
                                thisValue = thisForm.elements[theseFields[i1]].options[thisForm.elements[theseFields[i1]].selectedIndex].value;
                              }else
                              if( theseTypes[i1].search(\'checkbox\') != -1 ){
                                thisValue = thisForm.thisField;
                              }else{
                                thisValue = thisForm.elements[theseFields[i1]].value;
                              }
                            }else{	// field was specified by name
                              thisField = theseFields[i1];
                              if( theseTypes[i1].search(\'selectbox\') != -1 ){
                                thisValue = thisForm.elements[theseFields[i1]].options[thisForm.elements[theseFields[i1]].selectedIndex].value;
                              }else
                              if( theseTypes[i1].search(\'checkbox\') != -1 ){
                                thisValue = thisForm[thisField];
                              }else{
                              thisValue = thisForm[thisField].value;
                            }
                          }
                    // perform check based on validation type
                    ';
                    
                    if(in_array('length', $this->arrTypes)){
                        echo '
                              if( theseTypes[i1] == \'length\' ){	// check if theseTypes[i1] is length
                                if( thisValue.length == 0 ){
                                  fieldsToCorrect[fieldsToCorrect.length] = i1;
                                }
                                continue;
                              }';                    }

                    if(in_array('mod10', $this->arrTypes))
                    {
                        echo'
                              if( theseTypes[i1] == \'mod10\' ){	// check if theseTypes[i1] is length
                                if( __mod10(thisValue) == false ){
                                  fieldsToCorrect[fieldsToCorrect.length] = i1;
                                }
                                continue;
                              }';
                    }

                    if(strstr($this->strTypes, 'minimumlength')){
                        echo '
                              if( theseTypes[i1].search(\'minimumlength\') != -1 ){	// check if theseTypes[i1] is minimumlength
                                minLength = thisType.substring(13,theseTypes[i1].length);
                                if( thisValue.length < minLength ){
                                  fieldsToCorrect[fieldsToCorrect.length] = i1;
                                }
                                continue;
                              }';
                    }

                    if(strstr($this->strTypes, 'maximumlength')){
                        echo '
                              if( theseTypes[i1].search(\'maximumlength\') != -1 ){	// check if theseTypes[i1] is maximumlength
                                maxLength = thisType.substring(13,theseTypes[i1].length);
                                if( thisValue.length > maxLength ){
                                  fieldsToCorrect[fieldsToCorrect.length] = i1;
                                }
                                continue;
                              }';
                    }

                    if(strstr($this->strTypes, 'exactlength')){
                        echo '
                              if( theseTypes[i1].search(\'exactlength\') != -1 ){	// check if theseTypes[i1] is exactlength
                                exactLength = thisType.substring(11,theseTypes[i1].length);
                                if( thisValue.length != exactLength ){
                                  fieldsToCorrect[fieldsToCorrect.length] = i1;
                                }
                                continue;
                              }';
                    }

                    if(strstr($this->strTypes, 'match')){
                        echo '
                              if( theseTypes[i1].search(\'match\') != -1 ){	// check if theseTypes[i1] is match
                                matchValue = thisForm.elements[theseTypes[i1].substring(5,theseTypes[i1].length)].value;
                                if( thisValue != matchValue ){
                                  fieldsToCorrect[fieldsToCorrect.length] = i1;
                                }
                                continue;
                              }';
                    }

                    if(in_array('email', $this->arrTypes)){
                        echo '
                              if( theseTypes[i1] == \'email\' ){	// check if theseTypes[i1] is email
                                if( thisValue.search(\'@\') == -1 || thisValue.substring(thisValue.length-4,thisValue.length).search(\'.\') == -1 ){
                                  fieldsToCorrect[fieldsToCorrect.length] = i1;
                                }
                                continue;
                              }';
                    }

                    if(in_array('numeric', $this->arrTypes)){
                        echo '
                              if( theseTypes[i1] == \'numeric\' ){	// check if theseTypes[i1] is numeric
                                if( isNaN(thisValue) || thisValue.length == 0 ){
                                  fieldsToCorrect[fieldsToCorrect.length] = i1;
                                }
                                continue;
                              }';
                    }

                    if(strstr($this->strTypes, 'minimumnumber')){
                        echo '
                              if( theseTypes[i1].search(\'minimumnumber\') != -1 ){	// check if theseTypes[i1] is minumumnumber
                                minValue = theseTypes[i1].substring(13,theseTypes[i1].length);
                                if( !isNaN(minValue) && thisValue < minValue || isNaN(thisValue) ){
                                  fieldsToCorrect[fieldsToCorrect.length] = i1;
                                }
                                continue;
                              }';
                    }

                    if(strstr($this->strTypes, 'maximumnumber')){
                        echo '
                              if( theseTypes[i1].search(\'maximumnumber\') != -1 ){	// check if theseTypes[i1] maximumnumber
                                maxValue = theseTypes[i1].substring(13,theseTypes[i1].length);
                                if( !isNaN(maxValue) && thisValue > maxValue || isNaN(thisValue) ){
                                  fieldsToCorrect[fieldsToCorrect.length] = i1;
                                }
                                continue;
                              }';
                    }

                    if(strstr($this->strTypes, 'minimumdate')){
                        echo '
                              if( theseTypes[i1].search(\'minimumdate\') != -1 ){	// check if theseTypes[i1] is minimumdate
                                // set user date to js date var
                                userdate = thisValue;
                                userdateArray = userdate.split("/");
                                userdate = new Date(userdateArray[2], userdateArray[1], userdateArray[0], "00", "00", "00");
                                
                                // set specified date to date var
                                specdate = theseTypes[i1];
                                specdate = specdate.substring(11,specdate.length);
                                specdateArray = specdate.split("/");
                                specdate = new Date(specdateArray[2], specdateArray[1], specdateArray[0], "00", "00", "00");
                                
                                if( !isNaN(userdate) && !isNaN(specdate) ){	// make sure that the dates were not malformed initially
                                  userdate = userdate.valueOf();	// convert to milliseconds
                                  specdate = specdate.valueOf();	// convert to milliseconds
                                    if( userdate <= specdate ){
                                    fieldsToCorrect[fieldsToCorrect.length] = i1;	// userdate is less than specified value
                                  }
                                }else{
                                  fieldsToCorrect[fieldsToCorrect.length] = i1;	// userdate or specdate is not in mm/dd/yyyy format
                                }
                                continue;
                              }';
                    }

                    if(strstr($this->strTypes, 'maximumdate')){
                        echo '
                              if( theseTypes[i1].search(\'maximumdate\') != -1 ){	// check if theseTypes[i1] is maximumdate
                                // set user date to js date var
                                userdate = thisValue;
                                userdateArray = userdate.split("/");
                                userdate = new Date(userdateArray[2], userdateArray[1], userdateArray[0], "00", "00", "00");
                                
                                // set specified date to date var
                                specdate = theseTypes[i1];
                                specdate = specdate.substring(11,specdate.length);
                                specdateArray = specdate.split("/");
                                specdate = new Date(specdateArray[2], specdateArray[1], specdateArray[0], "00", "00", "00");
                                
                                if( !isNaN(userdate) && !isNaN(specdate) ){	// make sure that the dates were not malformed initially
                                  userdate = userdate.valueOf();	// convert to milliseconds
                                  specdate = specdate.valueOf();	// convert to milliseconds
                                  if( userdate >= specdate ){
                                    fieldsToCorrect[fieldsToCorrect.length] = i1;	// userdate is greater than specified value
                                  }
                                }else{
                                  fieldsToCorrect[fieldsToCorrect.length] = i1;	// userdate or specdate is not in mm/dd/yyyy format
                                }
                                continue;
                              }';
                    }
                    
                    if(strstr($this->strTypes, 'selectbox')){
                        echo '
                              if( theseTypes[i1].search(\'selectbox\') != -1 ){	// check to see if type is a select box
                                userValue = theseTypes[i1].substring(9,theseTypes[i1].length);
                                if( userValue == thisValue ){
                                  fieldsToCorrect[fieldsToCorrect.length] = i1;
                                }
                                continue;
                              }';
                    }
                    
                    if(strstr($this->strTypes, 'regexp')){
                        echo '
                              if( theseTypes[i1].search(\'regexp\') != -1 ){	// check to see if type is regular expression
                                thisStart = theseTypes[i1].indexOf("/") + 1;
                                thisEnd   = theseTypes[i1].lastIndexOf("/");
                                thisRegExpStr = theseTypes[i1].substring(thisStart, thisEnd);
                                thisRegExpParams = theseTypes[i1].substring(thisEnd+1, theseTypes[i1].length);
                                thisRegExp    = new RegExp(thisRegExpStr, thisRegExpParams);
                                if( thisValue.match(thisRegExp) == null ){
                                  fieldsToCorrect[fieldsToCorrect.length] = i1;
                                }
                                continue;
                              }';
                    }
                    
                    if(strstr($this->strTypes, 'checkboxmin')){
                        echo '
                              if( theseTypes[i1].search(\'checkboxmin\') != -1 ){	// check to see if type is checkbox minimum
                                minNumberOfBoxes = theseTypes[i1].substring(11,theseTypes[i1].length);
                                checkboxcount = 0;
                                if(thisValue.length != undefined){
                                  for( icheckbox = 0; icheckbox < thisValue.length; icheckbox++ ){
                                    if( thisValue[icheckbox].checked ){
                                      checkboxcount++;
                                    }
                                  }
                                }
                                else {
                                  if( thisValue.checked ){
                                    checkboxcount++;
                                  }
                                }
                                if( checkboxcount < minNumberOfBoxes ){
                                  fieldsToCorrect[fieldsToCorrect.length] = i1;
                                }
                                continue;
                              }';
                    }
                    
                    if(strstr($this->strTypes, 'checkboxmax')){
                        echo '
                              if( theseTypes[i1].search(\'checkboxmax\') != -1 ){	// check to see if type is checkbox maximum
                                minNumberOfBoxes = theseTypes[i1].substring(11,theseTypes[i1].length);
                                checkboxcount = 0;
                                for( icheckbox = 0; icheckbox < thisValue.length; icheckbox++ ){
                                  if( thisValue[icheckbox].checked ){
                                    checkboxcount++;
                                  }
                                }
                                if( checkboxcount > minNumberOfBoxes ){
                                  fieldsToCorrect[fieldsToCorrect.length] = i1;
                                }
                                continue;
                              }';
                    }
                    
                    echo '
                          if( i1 >= maxElementsToDisplay - 1 ){
                            break;
                          }
                        }
                        messageString = \''.$this->headerMessage.'\n\n\';	// set blank message string
                        setFocusOn = \'x\';	// set to non numeric so it fails isNaN()
                        // revised because field names (non numeric) can be passed
                        for( i2 = 0; i2 < fieldsToCorrect.length; i2++ ){	// loop though fields which did not pass validation [fieldsToCorrect]
                          messageString += theseFieldNames[fieldsToCorrect[i2]] + \'\n\' + theseMessages[fieldsToCorrect[i2]] + \'\n\';
                          if( i2 == 0 ){	// set focus
                            setFocusOn = theseFields[fieldsToCorrect[i2]];
                          }
                        }';
                    // check to see if using onSubmit or different type of form submission
                    if($this->javascriptSubmit == false){
                        echo '
                              if( fieldsToCorrect.length > 0 ){	// check to see if all fields passed validation
                                alert(messageString);
                                // check to see if field name or number needs focus
                                if( !isNaN(setFocusOn) && thisForm.elements[setFocusOn]["focus"] ){	// set focus to field number
                                  thisForm.elements[setFocusOn].focus();
                                }else
                                if( thisForm[setFocusOn]["focus"] ){	// set focus to field name
                                  thisForm[setFocusOn].focus();
                                }
                                _CFormValidatorPassed = false;
                                return false;
                              }else{
                                _CFormValidatorPassed = true;
                                _CFormValidatorEval();
                                return true;
                              }';
                    }else{
                        echo '
                              if( fieldsToCorrect.length > 0 ){	// check to see if all fields passed validation
                                alert(messageString);
                                // check to see if field name or number needs focus
                                if( !isNaN(setFocusOn) && thisForm.elements[setFocusOn]["focus"] ){	// set focus to field number
                                  thisForm.elements[setFocusOn].focus();
                                }else
                                if( thisForm[setFocusOn]["focus"] ){	// set focus to field name
                                  _CFormValidatorPassed = false;
                                  thisForm[setFocusOn].focus();
                                }
                              }else{
                                _CFormValidatorPassed = true;
                                _CFormValidatorEval();
                                thisForm.submit();
                              }';
                    }
                }
                
                if($this->eval !== false)
                {
                  echo '    }
                            
                            function _CFormValidatorEval()
                            {
                              eval("' . str_replace('"', '\"', $this->eval) . '");
                            }
                          </script>';
                }
                else
                {
                  echo '    }
                            
                            function _CFormValidatorEval()
                            {
                            }
                          </script>';
                }
            }
        }
        
        function CFormValidator(){
          $this->theseFields = array();
          $this->theseFieldNames = array();
          $this->theseMessages = array();
          $this->theseTypes = array();
          $this->type = array();
          $this->arrTypes = array();
          $this->form = 0;
          $this->noProceed();
          $this->noProceed();
          $this->headerMessage = 'Please correct the following information.';
          $this->maxElementsToDisplay = 10;
          $this->functionName = 'jsFormValidator';
          $this->javascriptSubmit = false;
          $this->debugOutput = false;
        }
    }
/*
	Modification History:
      01/15/2007 - JM
          - Added mod10 function
       03/08/2002 - JM
           - Fixed bug associated with using in_array and replaced certain in_array w/ array_search
       02/26/2002 - JM
           - Converted from cfml to php
		07/26/2001 - JM
			- Added streamline feature so only needed validation is sent to browser.
		06/30/2001 - JM
			- Added variable name for function for more than one use on a page.
		03/15/2001 - JM
			- Added option to output debugging information.  Useful when the tag is throwing javascript errors.
			- Added maxElementsToDisplay to keep alert box from becoming too large (vertically).
		03/06/2001 - JM
			- Added ability to validate for minimum and maximum checkboxes
			- Added ability to validate using regular expressions
		03/05/2001 - JM
			- Added minimumlength, maximumlength, and exact length
			- Modified theseFields to replace single quotes with "\'"
		02/23/2001 - JM
			- Added ability to validate against selection lists
		02/20/2001 - JM
			- Added ability to accept field names in addition to field numbers
			- Modified javascript variable to be assigned form object instead of name/number of form
		02/16/2001 - JM
			- Added minimumdate and maximumdate validation types
		02/15/2001 - JM
			- Added minimumnumber and maximumnumber validation types
*/
?>

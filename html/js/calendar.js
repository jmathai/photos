function loadCalendar(month, day, year)
{
  var group_id = '';
  if(arguments.length > 3)
  {
    group_id = arguments[3];
  }
  
  orderBy = '';
  if(arguments.length > 4)
  {
    orderBy = arguments[4];
  }
  else
  {
    orderBy = 'dateTaken';
  }

  var effect = new fx.Opacity('calendarContent', {duration:100, onComplete: function()
                        { 
                          $('calendarContent').style.display = 'none';
                          $('calendarLoading').style.display = 'block';
                          var onCompleteEffect = new fx.Opacity('calendarLoading', {duration:100}); }
                        }
                      );
  effect.toggle();
  
  var myAjax = new Ajax.Request(
  '/xml_result', 
  {
    method: 'get', 
    parameters: 'action=calendar_fotos&month=' + month + '&day=' + day + '&year=' + year + '&group_id=' + group_id + '&orderBy=' + orderBy + '&timestamp='+parseInt(Math.random()*100000), 
    onComplete: function(response){ loadCalendarRsp(response); }
  });
}

function loadCalendarRsp(response)
{   
  var data = response.responseText.parseJSON();

  var monthArray = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  var yearArray = ['2007', '2006', '2005', '2004', '2003', '2002', '2001', '2000', '1999', '1998', '1997', '1996', '1995', '1994', '1993', '1992', '1991', '1990'];
  
  var monthOptions = '';
  for(i = 0; i < monthArray.length; i++)
  {
    if(data['PARAMETERS']['MONTH'] == (i+1))
    {
      monthOptions += '<option value="' + (i+1) + '" SELECTED>' + monthArray[i] + '</option>';
    }
    else
    {
      monthOptions += '<option value="' + (i+1) + '">' + monthArray[i] + '</option>';
    }
  }
  
  var yearOptions = '';
  for(j = 0; j < yearArray.length; j++)
  {
    if(data['PARAMETERS']['YEAR'] == yearArray[j])
    {
      yearOptions += '<option value="' + yearArray[j] + '" SELECTED>' + yearArray[j] + '</option>';
    }
    else
    {
      yearOptions += '<option value="' + yearArray[j] + '">' + yearArray[j] + '</option>';
    }
  }
  
  var prevMonth = parseInt(data['PARAMETERS']['MONTH']) - 1;
  var nextMonth = parseInt(data['PARAMETERS']['MONTH']) + 1;
  var prevYear = data['PARAMETERS']['YEAR'];
  var nextYear = data['PARAMETERS']['YEAR'];
  
  if(prevMonth == 0)
  {
    prevMonth = 12;
    prevYear--;
  }
  
  if(nextMonth == 13)
  {
    nextMonth = 1;
    nextYear++;
  }
  
  var _html = '<div style="padding-bottom:10px;" class="f_10 center">The calendar lets you view your photos by the date they were uploaded or taken</div>'
        + '<div style="text-align:center; padding-bottom:10px;">'
        + '  <span style="padding-right:10px;"><a href="javascript:loadCalendar(' + prevMonth + ', 1, ' + prevYear + ', \'' + data['GROUP_ID'] + '\', \'' + data['ORDER'] + '\');"><img src="images/paging_first.gif" border="0" align="absmiddle" /></a></span>'
        + '<span style="padding-right:10px;">'
        + '<select id="monthSelect" onchange="loadCalendar($(\'monthSelect\').value, 1, ' + data['PARAMETERS']['YEAR'] + ', \'' + data['GROUP_ID'] + '\', \'' + data['ORDER'] + '\');" class="formfield">'
        + monthOptions
        + '</select>'
        + '</span>'
        + '<span style="padding-right:4px;">'
        + '<select id="yearSelect" onchange="loadCalendar(' + data['PARAMETERS']['MONTH'] + ', 1, $(\'yearSelect\').value, \'' + data['GROUP_ID'] + '\', \'' + data['ORDER'] + '\');" class="formfield">'
        + yearOptions
        + '</select>'
        + '</span>'
        + '  <span><a href="javascript:loadCalendar(' + nextMonth + ', 1, ' + nextYear + ', \'' + data['GROUP_ID'] + '\', \'' + data['ORDER'] + '\');"><img src="images/paging_last.gif" border="0" align="absmiddle" /></a></span>'
        + '</div>'
  
        + '<br />'
        
        + '<div style="padding-left:70px;">View by '
        + '<form>'
        + '<select name="viewBy" id="viewBy" class="formfield" onchange="loadCalendar(' + parseInt(data['PARAMETERS']['MONTH']) + ', ' + parseInt(data['PARAMETERS']['DAY']) + ', ' + parseInt(data['PARAMETERS']['YEAR']) + ', \'' + data['GROUP_ID'] + '\', $(\'viewBy\').options[$(\'viewBy\').selectedIndex].value);">';
  if(data['ORDER'] == 'dateTaken')
  {
    _html += '<option value="dateTaken" SELECTED>Date Taken</option>'
          + '<option value="dateUploaded">Date Uploaded</option>';
  }
  else
  {
    _html += '<option value="dateTaken">Date Taken</option>'
          + '<option value="dateUploaded" SELECTED>Date Uploaded</option>';
  }
  
  _html += '</select>'
        + '</form>'
        + '</div>'
        
        + '<br />'
        
        + '<div style="border:solid 1px black; width:685px; margin:auto;">'
        + '  <div style="padding-top:5px; padding-bottom:10px;">'
        + '    <div style="float:left; padding-left:17px; width:91px; text-align:center;" class="f_10 bold">Sunday</div>'
        + '    <div style="float:left; width:91px; text-align:center;" class="f_10 bold">Monday</div>'
        + '    <div style="float:left; width:91px; text-align:center;" class="f_10 bold">Tuesday</div>'
        + '    <div style="float:left; width:91px; text-align:center;" class="f_10 bold">Wednesday</div>'
        + '    <div style="float:left; width:91px; text-align:center;" class="f_10 bold">Thursday</div>'
        + '    <div style="float:left; width:91px; text-align:center;" class="f_10 bold">Friday</div>'
        + '    <div style="float:left; width:91px; text-align:center;" class="f_10 bold">Saturday</div>'
        + '    <br clear="all" />'
        + '  </div>'
    
        + '  <div id="cal_main" class="cal_main">';
        
  num_days = data['NUM_DAYS'];
  first_day = data['FIRST_DAY_OF_MONTH'] + 1;
  
  // figure out how many rows we need in the calendar
  num_rows = Math.ceil((num_days - (7 - (first_day-1))) / 7) + 1;
  
  // keeps track if the first date has been found and what day should be printed next
  first_day_found = false;
  j = 1;
  
  for(i = 0; i < (num_rows*7); i++ )
  {
    // first row, first column
    if(i == 0)
    {
      // if it's the first day
      if(first_day == 1)
      {
        _html += '<div id="cal_1_1" class="cal_day cal_first_first">' + j;
        first_day_found = true;
        j++;
      }
      else
      {
        _html += '<div id="cal_1_1" class="cal_day cal_first_first">';
      }
    }
    // first row, any column after the first
    else if(i < 7)
    {
      // if the first day has already been found
      if(first_day_found == true)
      {
        _html += '<div id="cal_1_' + (i+1) + '" class="cal_day cal_first_all">' + j;
        j++;
      }
      else
      {
        // if it's the first day
        if((i+1) == first_day)
        {
          _html += '<div id="cal_1_' + (i+1) + '" class="cal_day cal_first_all">' + j;
          first_day_found = true;
          j++;
        }
        else
        {
          _html += '<div id="cal_1_' + (i+1) + '" class="cal_day cal_first_all">';
        }
      }
    }
    // any row after the first, first column
    else if(i % 7 == 0)
    {
      // make sure we don't go over the number of days of the month
      if(j <= num_days)
      {
        _html += '<div id="cal_' + (Math.floor(i/7)+1) + '_1" class="cal_day cal_mid_first">' + j;
        j++;
      }
      else
      {
        _html += '<div id="cal_' + (Math.floor(i/7)+1) + '_1" class="cal_day cal_mid_first">';
      }
    }
    // any row after the first, any column after the first
    else
    {
      // make sure we don't go over the number of days of the month
      if(j <= num_days)
      {
        _html += '<div id="cal_' + (Math.floor(i/7)+1) + '_' + ((i%7)+1) + '" class="cal_day cal_mid_all">' + j ;
        j++;
      }
      else
      {
        _html += '<div id="cal_' + (Math.floor(i/7)+1) + '_' + ((i%7)+1) + '" class="cal_day cal_mid_all">';
      }
    }
    
    m = data['PARAMETERS']['MONTH'];
    if(parseInt(data['PARAMETERS']['MONTH']) < 10)
    {
      m = '0' + data['PARAMETERS']['MONTH'];
    }
    
    day = j-1;
    if(day < 10)
    {
      day = '0' + day;
    }
    
    y = data['PARAMETERS']['YEAR'] += '';
    // removed this (revision540)
    // y = y.substr(2,4); // convert 4 digit year to 2 digit year
    d = y + m + day + '';
    
    if(data['FOTOS'][d] != undefined)
    {
      _date = new Date();
      _date.setMonth(m-1);
      _date.setDate(day);
      _date.setFullYear(y);
      _date.setHours(0);
      _date.setMinutes(0);
      _date.setSeconds(0);
      start = parseInt(_date.getTime() / 1000);
      end = start + 86400;

      if(data['ORDER'] == 'dateTaken')
      {
        orderStart = 'DATE_TAKEN_START';
        orderEnd = 'DATE_TAKEN_END';
      }
      else
      {
        orderStart = 'DATE_CREATED_START';
        orderEnd = 'DATE_CREATED_END';
      }
      
      if(data['GROUP_ID'] == '')
      {
        _html +=  '<div id="cal_' + (Math.floor(i/7)+1) + '_' + ((i%7)+1) + '_image" class="foto_border cal_foto"><a href="/?action=fotobox.fotobox_myfotos&' + orderStart + '=' + start + '&' + orderEnd + '=' + end + '"><img src="/photos' + data['FOTOS'][d][0]['P_THUMB_PATH'] + '" border="0" class="cal_image" /></a></div>'
              +   '<div id="cal_' + (Math.floor(i/7)+1) + '_' + ((i%7)+1) + '_text" class="cal_foto_text"><a href="/?action=fotobox.fotobox_myfotos&' + orderStart + '=' + start + '&' + orderEnd + '=' + end + '" class="plain">' + (data['FOTOS'][d].length + '') + ' Photos</a></div>';
      }
      else
      {
        _html +=  '<div id="cal_' + (Math.floor(i/7)+1) + '_' + ((i%7)+1) + '_image" class="foto_border cal_foto"><a href="/?action=group.photos&group_id=' + data['GROUP_ID'] + '&' + orderStart + '=' + start + '&' + orderEnd + '=' + end + '"><img src="/photos' + data['FOTOS'][d][0]['P_THUMB_PATH'] + '" border="0" class="cal_image" /></a></div>'
              +   '<div id="cal_' + (Math.floor(i/7)+1) + '_' + ((i%7)+1) + '_text" class="cal_foto_text"><a href="/?action=group.photos&group_id=' + data['GROUP_ID'] + '&' + orderStart + '=' + start + '&' + orderEnd + '=' + end + '" class="plain">' + (data['FOTOS'][d].length + '') + ' Photos</a></div>';
      }
    }
    
    _html += '</div>';
    
    // end of each row
    if(i % 7 == 6)
    {
      _html += '<br />';
    }
  }
  
  _html += '  </div>'
         + '  <br clear="all" />'
         + '</div>';
         
  $('calendarContent').innerHTML = '';
  $('calendarContent').innerHTML = _html;
  
  var effect = new fx.Opacity('calendarLoading', {duration:100, onComplete: function()
  
                        { 
                          $('calendarContent').style.display = 'block';
                          $('calendarLoading').style.display = 'none';
                          var onCompleteEffect = new fx.Opacity('calendarContent', {duration:100}); }
                        }
                       );
  effect.toggle();
}
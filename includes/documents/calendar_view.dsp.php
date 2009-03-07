<div id="calendarContent" style="display:none;"></div>
<div id="calendarLoading" style="padding-top:200px; width:100px; height:450px; margin:auto;">
  <div style="float:left;"><img src="images/ajax_loader_snake.gif" width="16" height="16" hspace="4" border="0" /></div>
  <div style="float:left;">Loading...</div>
  <br/>
</div>

<br/>

<script type="text/javascript">
  var d = new Date();
  var month = d.getMonth() + 1;
  var day = d.getDate();
  var year = d.getFullYear();
  
  loadCalendar(month, day, year);
</script>
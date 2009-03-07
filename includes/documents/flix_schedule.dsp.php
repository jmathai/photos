
<div class="bold f_9">
  <img src="images/icons/history_16x16.png" width="16 height="16" border="0" align="absmiddle" />&nbsp;Schedule a slideshow
</div>

<br/>

<div>
  <form action="" onsubmit="flixSchedule($('tagsField').value); return false;">
  <div style="float:left;"><input type="text" id="tagsField" onfocus="this.select();" value="tag search" class="formfield" style="width:110px;"/></div>
  <div style="float:left;"><input type="image" src="images/buttons/go.gif" width="25" height="17" border="0" hspace="4" align="absmiddle" /></div>
</div>
<br clear="all" />
<br clear="all" />
<div id="schedule">
  <div id="scheduleLoading"></div>
  <div id="scheduleContent"></div>
  <br clear="all" />
</div>

<script type="text/javascript">
  var effect = new fx.Opacity('scheduleContent');
  effect.hide();
  flixSchedule('');
</script>
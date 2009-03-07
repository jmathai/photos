<?php
  $themesOptions = '';
  $themes = $GLOBALS['dbh']->query_all('SELECT ft_name, ft_swf FROM flix_templates ORDER BY ft_name');
  foreach($themes as $v)
  {
    $themesOptions .= '<option value="' . $v['ft_swf'] . '" ' . ($_GET['ft_swf'] == $v['ft_swf'] ? ' selected="true" ' : '') . '>' . $v['ft_name'] . '</option>';
  }
?>

<div class="header">Search Flix</div>
<form id="flixSearchForm" method="get" action="./">
  <input type="hidden" name="action" value="flix.search_results" />
  <div class="padding_top_5">
    <div style="float:left;">
      <div>Username</div>
      <div><input name="u_username" type="text" value="<?php echo htmlspecialchars($_GET['u_username']); ?>" class="formfield" style="width:100px;" /></div>
    </div>
    <!--
    <div style="padding-left:20px; float:left;">
      <div>Theme</div>
      <div>
        <select name="ft_swf" class="formfield">
          <option value="">-- Select --</option>
          <?php echo $themesOptions; ?>
        </select>
      </div>
    </div>
    -->
    <div style="padding-left:20px; float:left;">
      <div>Date From (mm-dd-yyyy)</div>
      <div>
        <input type="text" name="us_dateCreatedFrom" class="formfield" value="<?php echo $dFrom; ?>" />
      </div>
    </div>
    <div style="padding-left:20px; float:left;">
      <div>Date To (mm-dd-yyyy)</div>
      <div>
        <input type="text" name="us_dateCreatedTo" class="formfield" value="<?php echo $dFrom; ?>" />
      </div>
    </div>
    <div style="padding-left:15px; float:left;">
      <div style="padding-top:10px;"><input type="button" value="Search" class="formfield bold" onclick="document.getElementById('flixSearchForm').submit();" /></div>
    </div>
    <br clear="all" />
  </div>
  <div class="padding_top_5"></div>
</form>

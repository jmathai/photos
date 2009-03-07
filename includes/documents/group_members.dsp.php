<?php
  $g = &CGroup::getInstance();
  $group_id = $_GET['group_id'];
  
  $moderator = $g->isModerator($_USER_ID, $group_id);
  
  $members = $g->members(array('GROUP_ID' => $group_id));
  $numberOfMembers = count($members);
?>

<div style="float:left; width:685px;">

  <form style="display:inline;" onsubmit="mlOpts.SEARCH_ITEM = $('_memberSearchBox').value; void(groupMemberSearch(mlOpts)); return false;">
    <div style="float:left; margin-left:25px; margin-right:10px; padding-top:5px;" class="bold">Member Search</div>
    <div style="float:left;" class="formfield"><input id="_memberSearchBox" type="text" /></div>
    <div style="float:left; padding-top:4px;"><a href="javascript:mlOpts.SEARCH_ITEM = $('_memberSearchBox').value; void(groupMemberSearch(mlOpts));"><img src="images/icons/search_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" hspace="3" title="Search by tags" /></a></div>
    <div id="_membersTotal" style="float:right; padding-top:5px; padding-right:35px;" class="bold">Total Members <?php echo $numberOfMembers; ?></div>
  </form>
  <br clear="all" />
  <div style="margin-top:30px;" class="gradient_lt_grey">
    <div style="float:left; width:82px; padding-top:5px; padding-left:15px;"><img src="/images/icons/png/User (16x16).png" class="png" border="0" /></div>
    <div style="float:left; padding-top:5px;" class="f_black bold">
      <div style="float:left; width:315px;">User Name</div>
      <div style="float:left; width:145px;">Date</div>
      <div>Photos Added</div>
    </div>
  </div>
  <div id="_memberListData">
    <script language="javascript">
      <?php
        $params = array('DIV_ID' => '_memberListData', 'GROUP_ID' => $group_id, 'SEARCH_ITEM' => '', 'OFFSET' => 0, 'LIMIT' => 8, 'MODERATOR' => $moderator);
        $params = jsonEncode($params);
        echo 'var mlOpts = ' . $params . ';
              groupMemberSearch(mlOpts);';
      ?>
    </script>
  </div>
  
</div>

<?php
  include_once PATH_DOCROOT . '/group_sponsors.dsp.php';
?>
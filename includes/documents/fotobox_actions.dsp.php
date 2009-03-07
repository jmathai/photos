<?php
  $prefix = substr($action, 0, strpos($action, '.'));
  $member_id = isset($_GET['member_id']) ? $_GET['member_id'] : 0;
  
  if(isset($_GET['group_id']))
  {
    $group_suffix_url = '&type=group&group_id=' . $_GET['group_id'];
    $get_group_id = $_GET['group_id'];
  }
  else
  {
    $group_suffix_url = '';
    $get_group_id = '';
  }
?>

<!-- grey action bar for fotos -->
<form name="_savetofotobox" id="_savetofotobox" target="_top" action="/?action=fotobox.save_to_fotobox_form&group_id=<?php echo $get_group_id; ?>" method="post" style="display:none">
  <input type="hidden" name="foto_ids" value="<?php if(isset($foto_ids)){ echo $foto_ids; } ?>" />
</form>

<div id="fotoboxActionsDiv">
<?php
  if($prefix == 'fotobox')
  {
?>
  <div style="width:535px; height:40px; border:solid #cde7ca 1px;" align="center">
    <div style="color:#2e6402; font-weight:bold; padding-left:3px; height:13px; background-color:#cdf2c9; border-bottom:solid #cde7ca 1px;" align="left">
      SHARING OPTIONS
    </div>
    <div class="fb_actions_primary">
      <div style="width:100%; height:26px; background-color:#f3fff1; float:left;">
        <div style="height:100%; padding-left:8px;" align="center">
          <div style="padding-top:6px; padding-right:20px; float:left;" class="bold">Send to:</div>
          <!-- flix -->
          <div style="padding-right:3px; float:left;"><img src="images/fb_actions_flix.gif" width="12" height="16" vspace="2" border="0" align="absmiddle" style="padding-top:4px;" /></div>
          <div style="padding-top:6px; padding-right:30px; float:left;"><a href="javascript:if(top.frames['_fotobox']._checked_number > 0 && top.frames['_fotobox']._checked_number <= 50){ showFlixList(top.frames['_fotobox']._checked_number); }else{ if(top.frames['_fotobox']._checked_number == 0){ selectFotos(); }else{ selectFotosMax(50); }}" class="fb_actions_primary" title="display flix options for selected fotos">Flix</a></div>
          <!-- group -->
          <div style="padding-right:3px; padding-top:1px; float:left;"><img src="images/fb_actions_group.gif" width="15" height="16" border="0" align="absmiddle" style="padding-top:4px;" /></div>
          <div style="padding-top:6px; padding-right:30px; float:left;"><a href="javascript:if(top.frames['_fotobox']._checked.length > 1){ showGroupList(); }else{ selectFotos(); }" class="fb_actions_primary" title="display group options for selected fotos">Group</a></div>
          <!-- blog -->
          <div style="padding-right:3px; margin-top:-1px; float:left;"><img src="images/fb_actions_blog.gif" width="16" height="16" vspace="3" border="0" align="absmiddle" style="padding-top:4px;" /></div>
          <div style="padding-top:6px; padding-right:30px; float:left;"><a href="javascript:if(top.frames['_fotobox']._checked.length > 1){ showBlogs(); }else{ selectFotos(); }" class="fb_actions_primary" title="display blog options for selected fotos">Blog</a></div>
          <!-- privacy -->
          <div style="padding-right:3px; margin-top:-1px; height:100%; float:left;"><img src="images/fb_actions_fotopage.gif" width="16" height="16" vspace="3" border="0" align="absmiddle" style="padding-top:4px;" /></div>
          <div style="padding-top:6px; padding-right:30px; float:left;"><a href="javascript:if(top.frames['_fotobox']._checked.length > 1){ showPrivacy(top.frames['_fotobox']._checked); }else{ selectFotos(); }" class="fb_actions_primary" title="display FotoPage options for selected fotos">My FotoPage</a></div>
        </div>
      </div>
    </div>
  </div>
  
  <div id="tagForm" style="display:block; z-index:75;" align="left"></div>
  
  <div style="width:535px; height:40px; border:solid #dfd1b7 1px; margin-top:5px;" align="center">
    <div style="color:#715000; font-weight:bold; padding-left:3px; height:13px; background-color:#fdf0c6; border-bottom:solid #dfd1b7 1px;" align="left">
      MANAGE MY FOTOS
    </div>
    <div class="fb_actions_secondary ">
      <div style="width:125px; height:26px; background-color:#fffaf0; float:left;">
        <div style="height:100%; padding-left:8px; border-right:solid #cde7ca 1px;" align="center">
          <div style="padding-right:3px; float:left;"><img src="images/fb_actions_label_assign.gif" width="13" height="17" vspace="2" border="0" align="absmiddle" style="padding-top:2px;" /></div>
          <div style="padding-top:6px; float:left;"><a href="javascript:if(top.frames['_fotobox']._checked.length > 1){ displayTagForm('tag'); }else{ selectFotos(); }" class="fb_actions_secondary" title="assign tags to selected fotos">Assign Tag(s)</a></div>
        </div>
      </div>
      <div style="width:140px; height:26px; background-color:#fffaf0; float:left;">
        <div style="height:100%; padding-left:8px;" align="center">
          <div style="padding-right:3px; float:left;"><img src="images/fb_actions_label_unassign.gif" width="13" height="17" vspace="2" border="0" align="absmiddle" style="padding-top:3px;" /></div>
          <div style="padding-top:6px; float:left;"><a href="javascript:if(top.frames['_fotobox']._checked.length > 1){ displayTagForm('untag');  }else{ selectFotos(); }" class="fb_actions_secondary" title="unassign tags from selected fotos">Unassign Tag(s)</a></div>
        </div>
      </div>
      <div style="width:270px; height:26px; background-color:#fffaf0; float:left;" align="right">
        <div style="height:100%; margin-left:90px; padding-right:10px;">
          <div style="padding-right:3px; height:100%; float:left;"><img src="images/fotopopup_delete.gif" width="12" height="16" vspace="3" border="0" align="absmiddle" style="padding-top:1px;" /></div>
          <div style="padding-top:6px; float:left;"><a href="javascript:if(top.frames['_fotobox']._checked.length > 1){ _deleteUserFotos(top.frames['_fotobox']._checked); }else{ selectFotos(); }" style="font-weight:normal; text-decoration:none;" class="normal" title="delete selected fotos">Delete selected foto(s)</a></div>
        </div>
      </div>
    </div>
  </div>
<?php
  }
  else
  {
?>
  <div style="width:535px; height:40px; border:solid #cde7ca 1px;" align="center">
    <div style="color:#2e6402; font-weight:bold; padding-left:3px; height:13px; background-color:#cdf2c9; border-bottom:solid #cde7ca 1px;" align="left">
      SHARING OPTIONS
    </div>
    <div class="fb_actions_primary">
      <div style="width:175px; height:26px; background-color:#f3fff1; float:left;">
        <div style="height:100%; padding-left:8px; border-right:solid #cde7ca 1px;" align="center">
          <div style="padding-right:3px; float:left;"><img src="images/fotobox_icon.gif" width="24" height="22" border="0" align="absmiddle" style="padding-top:4px;" /></div>
          <div style="padding-top:6px; float:left;"><a href="javascript:if(top.frames['_fotobox']._checked.length > 1){ location.href='/?action=fotobox.save_to_fotobox_form&group_id=<?php echo $_GET['group_id']; ?>&foto_ids='+top.frames['_fotobox']._checked; }else{ selectFotos(); }" class="fb_actions_primary">Save to My FotoBox</a></div>
        </div>
      </div>
      <?php
        if(!isset($_GET['member_id']) || $_USER_ID != $_GET['member_id'])
        {
          echo '<div style="width:80px; height:26px; background-color:#f3fff1; float:left;"></div>';
        }
        else
        {
      ?>
        <div style="width:360px; height:26px; background-color:#fffaf0; float:left;">
          <div style="height:100%; margin-left:185px; padding-right:10px;">
            <div style="padding-right:3px; height:100%; float:left;"><img src="images/fb_actions_delete.gif" width="21" height="20" vspace="3" border="0" align="absmiddle" style="padding-top:1px;" /></div>
            <div style="padding-top:6px; float:left;"><a href="javascript:if(top.frames['_fotobox']._checked.length > 1){ location.href='/?action=fotogroup.fotos_unshare_form&group_id=<?php echo $_GET['group_id']; ?>&ids='+top.frames['_fotobox']._checked; }else{ selectFotos(); }" style="font-weight:normal; text-decoration:none;" class="normal">Unshare selected foto(s)</a></div>
          </div>
        </div>
      <?php
        }
      ?>
    </div>
  </div>
<?php
//
  }
?>
</div>
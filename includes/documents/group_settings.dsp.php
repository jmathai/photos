<?php
  $g = &CGroup::getInstance();
  $group_id = isset($_GET['group_id']) ? $_GET['group_id'] : false;
  
  if($g->isModerator($_USER_ID, $group_id) == true)
  {
  
    $message = $_GET['message'];
    
    if($group_id === false)
    {
      $mode = 'add';
    }
    else 
    {
      $mode = 'edit';
      // get group data here
      $groupData = '';
    }
    
    $groupData = $g->groupData($group_id);
    
    $tempPhoto = $g->pref($group_id, 'HEADER_PHOTO');
    $title = $groupData['G_NAME'];
    $description = $groupData['G_DESC'];
    $rightTitle = $g->pref($group_id, 'RIGHT_TITLE');
    $rightTags = $g->pref($group_id, 'RIGHT_TAGS');
    $siteColors = $g->pref($group_id, 'SITE_COLORS');
    
    if($headerPhoto === false)
    {
      $headerPhoto = 'images/groupToolbarSampleLogo.gif';
    }
    else 
    {
      $headerPhoto = PATH_FOTO . $tempPhoto;
    }
    
    if($rightTitle === false)
    {
      $rightTitle = '';
    }
    if($rightTags === false)
    {
      $rightTags = '';
    }
    if($siteColors === false)
    {
      $siteColors = 'light_slate';
    }
    
    //$validator = new CFormValidator;
    //$validator -> setForm('_groupSettingsForm');
    //$validator -> addElement('_headerTitle', 'Title', '  - Please enter a title for the header.', 'length');
    //$validator -> addElement('_headerDescription', 'Description', '  - Please enter a description for the header.', 'length');
    //$validator -> addElement('_rightColumnTitle', 'Title', '  - Please enter a title for the right column.', 'length');
    //$validator -> addElement('_rightColumnTags', 'Tags', '  - Please enter tags for the right column.', 'length');
    //$validator -> setDebugOutput(false);
    //$validator -> setFunctionName('_val_post');
    //$validator -> validate();
  ?>
  
  <div style="float:left; width:684px;">
    <!--<div style="background-color:#dddddd; border:1px solid #444444; height:25px; margin-top:5px; margin-bottom:5px;" class="bold"><span style="float:left; padding-left:10px; padding-top:5px;">Your settings were saved</span></div>-->
    <div style="text-align:left; margin-top:27px; margin-left:100px; position:absolute;" id="headerBlank"></div>
    <form method="POST" action="/?action=group.settings.act" id="_groupSettingsForm" name="_groupSettingsForm">
      <div style="padding-left:15px;">
        <div style="float:left;">
          <div class="f_black bold">Header</div>
          <div style="padding-left:5px; padding-top:15px;">
            <div id="_headerPhoto" style="border:1px solid blue; width:75px; height:75px;"><img id="_headerPhotoImg" src="<?php echo $headerPhoto; ?>" border="0" width="75" height="75" /></div>
            <div id="_changeHeaderPhoto" style="padding-top:5px;"><a href="javascript:void(0);" onclick="changeHeaderPhoto(<?php echo $group_id; ?>);">Change Header Photo</a></div>
          </div>
        </div>
        <div style="float:left; padding-left:30px;">
          <div style="padding-top:15px;" class="f_black">Title (50 Characters Max)</div>
          <div style="padding-top:2px;"><input type="text" name="_headerTitle" id="_headerTitle" size="51" class="formfield" value="<?php echo $title; ?>" /></div>
          <div style="padding-top:8px;" class="f_black">Description (100 Characters Max)</div>
          <div style="padding-top:2px; height:50px;"><textarea cols="38" rows="2" id="_headerDescription" name="_headerDescription" class="formfield"><?php echo $description; ?></textarea></div>
        </div>
        <br clear="all" />
      </div>
      <div style="width:100%; height:1px; margin-top:15px; overflow:hidden; background-color:gray;"></div>
      <div style="padding-left:15px; padding-top:15px;">
        <div class="f_black bold">Right Column</div>
        <div style="padding-left:15px; padding-top:15px;">
          <div style="float:left; width:30px; padding-top:3px; padding-right:7px; text-align:right;" class="f_black">Title</div>
          <div><input type="text" id="_rightColumnTitle" name="_rightColumnTitle" size="50" class="formfield" value="<?php echo $rightTitle; ?>" /></div>
          <br clear="all" />
          <div style="float:left; width:30px; padding-top:3px; padding-right:7px; text-align:right;" class="f_black">Tags</div>
          <div><input type="text" id="_rightColumnTags" name="_rightColumnTags" size="50" class="formfield" value="<?php echo $rightTags; ?>" /></div>
        </div>
      </div> 
      <div style="width:100%; height:1px; margin-top:15px; overflow:hidden; background-color:gray;"></div>
      <div style="padding-left:15px; padding-top:15px;">
        <div class="f_black bold">Site Colors</div>
        <div style="padding-left:15px; padding-top:15px;">
          <script type="text/javascript">
            embedSwf({WIDTH: 480, HEIGHT: 215, SRC: 'swf/site/color_swatches.swf?whichColor=<?php echo $siteColors; ?>', BGCOLOR: '#ffffff'});
          </script>
          <input type="hidden" id="fotoPageColorChoice" name="p_colors" value="<?php echo $siteColors; ?>" />
        </div>
      </div>
      <input type="hidden" id="group_id" name="group_id" value="<?php echo $group_id; ?>" />
      <div style="padding-left:15px; padding-top:15px;">
        <!--<div><a href="javascript:if(_val_post()){ $('_groupSettingsForm').submit(); }"><img src="images/save_fp.gif" border="0" width="86" height="23" /></a></div>-->
        <div style="float:left;"><a href="javascript:$('_groupSettingsForm').submit();"><img src="images/save_fp.gif" border="0" width="86" height="23" /></a></div>
      </div>
    </form>
  </div>
  
  <?php
    include_once PATH_DOCROOT . '/group_sponsors.dsp.php';
    
  }
  else 
  {
    echo 'You are no a group administrator';
  }
  ?>

<script type="text/javascript">
  var headerEffect = new fx.Opacity("headerBlank"); 
  headerEffect.hide();
</script>
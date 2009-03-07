<?php
  $tb =& CToolbox::getInstance();
  
  if(isset($_GET['group_id']))
  {
    $g =& CGroup::getInstance();
    if($g->isOwner($_GET['group_id'], $_USER_ID))
    {
      $update = true;
      $group_data = $g->groupData($_GET['group_id'], $_USER_ID);
      $submit_src = 'update.gif';
    }
    else
    {
      $update = false;
      $submit_src = 'create_group.gif';
    }
  }
  else
  {
    $update = false;
    $submit_src = 'create_group.gif';
  }
  
  if($action == 'fotogroup.group_share_form')
  {
    $gp_form_mode = 'included';
  }
  else
  {
    $gp_form_mode = 'standalone';
  }
  
  $type = isset($_GET['type']) ? $_GET['type'] : 'fotos';
  
  $fv =  new CFormValidator;
  $fv -> setForm('_group');
  $fv -> addElement('g_name', 'Name', '  - Please enter a name for your group.', 'length');
  $fv -> setDebugOutput(false);
  $fv -> setFunctionName('_val_group');
  $fv -> setJavascriptSubmit(true);
  $fv -> validate();
?>

<div style="width:545px; text-align:left;">
  <form action="/?action=fotobox.group_form.act" method="post" name="_group" style="display:inline;">
    <input type="hidden" name="g_public" value="0" />
    
    <div class="bold">
      <span class="f_off_accent">Step 1</span> Name your Group
    </div>
    <div style="padding-left:10px;">
      <input type="text" name="g_name" value="<?php if($update === true){ echo $group_data['G_NAME']; } ?>" style="width:150px;" class="formfield" />
    </div>
    
    <div class="line_lite" style="padding-top:15px;"></div>
    <div style="padding-top:15px;"></div>
    
    <div class="bold">
      <span class="f_off_accent">Step 2</span> Describe your Group
    </div>
    <div style="padding-left:10px;">
      <textarea name="g_description" rows="3" cols="30" style="width:195px; height:50px;" class="formfield"><?php if($update === true){ echo $group_data['G_DESC']; } ?></textarea>
    </div>
    
    <div style="padding-top:10px; padding-left:10px;">
      <div style="float:left;"><input type="checkbox" name="g_tags" id="g_tags" <?php if($update === true){ echo ($group_data['G_TAGS'] == 'Y' ? ' CHECKED' : ''); }else{ echo ' CHECKED' ; } ?> /></div>
      <div style="padding-top:3px;" class="bold">Allow Tagging</div>
    </div>
    
    <div class="line_lite" style="padding-top:15px;"></div>
    <div style="padding-top:15px;"></div>
    
    <div>
      <span class="bold">
        <span class="f_off_accent">Step 3</span> Group <!--Type and -->Contributors
      </span>
      (<a href="javascript:_open('/popup/contributors/', 300, 250);">What are contributors?</a>)
    </div>
    <div style="padding-left:10px;">
      <div>
        <input type="radio" name="g_contributors" value="Owner" <?php if($update === true){ echo ($group_data['G_CONTRIBUTORS'] == 'Owner' ? ' CHECKED' : ''); }else{ echo ' CHECKED' ; } ?> />&nbsp;
        <span class='f_8 f_medium bold'>Owner</span> (only you can contribute and invite)
      </div>
      <div>
        <input type="radio" name="g_contributors" value="Group" <?php if($update === true){ echo ($group_data['G_CONTRIBUTORS'] == 'Group' ? ' CHECKED' : ''); } ?> />&nbsp;
        <span class='f_8 f_medium bold'>Group</span> (all members that you invite to contribute, only you can invite)
      </div>
      <div>
        <input type="radio" name="g_contributors" value="All" <?php if($update === true){ echo ($group_data['G_CONTRIBUTORS'] == 'All' ? ' CHECKED' : ''); } ?> />&nbsp;
        <span class='f_8 f_medium bold'>All</span> (all members can invite and contribute)
      </div>
    </div>
    
    <div class="line_lite" style="padding-top:15px;"></div>
    <div style="padding-top:15px;"></div>
    
  	<!--Invitations-->
    <?php
      if($update === false)
      {
    ?>
        <div class="bold">
          <span class="f_off_accent">Step 4</span> Enter the name and email address of those you want to invite to this Group
        </div>
        <div style="padding-left:10px; padding-bottom:10px;">
          <?php include_once PATH_DOCROOT . '/group_invite.frm.php'; ?>
        </div>
  
    <?php
        if(isset($_REQUEST['flix_ids']))
        {
          $flix_ids = $_REQUEST['flix_ids'];
          echo '<div style="padding-top:5px;">
                  Do not share fotos <input type="checkbox" name="do_not_share_fotos" value="1" /> <!--(<a href="">what\'s this?)</a>-->
                </div>
                <input type="hidden" name="flix_ids" value="' . $flix_ids . '" />';
        }
        else
        if(!isset($flix_ids))
        {
          $flix_ids = '';
        }
        else
        if(isset($_GET['toolbox']))
        {
          $fotos = $tb->get($_USER_ID, 'foto');
          $foto_ids = '';
          
          foreach($fotos as $v)
          {
            $foto_ids = $v['P_ID'] . ',';
          }
          
          echo '<input type="hidden" name="foto_ids" value="' . $foto_ids . '" />';
        }
      }
      else
      if($update === true)
      {
        echo '<input type="hidden" name="g_id" value="' . $group_data['G_ID'] . '" />';
      }
    ?>
    <div style="padding-top:5px;">
      <a href="javascript:_val_group();"><img src="images/buttons/<?php echo $submit_src; ?>" border="0" vspace="5" /></a>
    </div>
    <input type="hidden" name="g_u_id" value="<?php echo $_USER_ID; ?>" />
    <input type="hidden" name="type" value="<?php echo $type; ?>" />
  </form>
</div>
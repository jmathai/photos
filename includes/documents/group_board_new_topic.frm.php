<?php

  $board = CBoard::getInstance();
  $g = &CGroup::getInstance();
  $user = CUser::getInstance();
  $validator = new CFormValidator;
  
  $bID = $_GET['group_id'];
  
  $user_group = $g->groups($_USER_ID, $bID);
  if(count($user_group) == 0)
  {
    $tpl->kill("You are not a member of this group");
  }
  
  $boardData = $board->board( $bID );
  $profileData = $user->profile($_USER_ID);
  $avatarSrc = $profileData['P_AVATAR'] != '' ? PATH_FOTO . $profileData['P_AVATAR'] : 'images/avatar.jpg';
  
  $validator -> setForm('_newTopic');
  $validator -> addElement('bp_title', 'Title', '  - Please enter a title for the post.', 'length');
  $validator -> addElement('bpc_content', 'Content', '  - Please enter the content of your post.', 'length');
  $validator -> setDebugOutput(false);
  $validator -> setFunctionName('_val_post');
  $validator -> validate();
?>
  
  <div style="padding-bottom:10px;">
    <a href="/?action=group.board_main&group_id=<?php echo $bID ?>"><?php echo $boardData['B_TITLE']; ?></a> >> (New Topic)
  </div>
  
  <div style="width:685px;">
    <div class="gradient_lt_grey">
      <div style="float:left; padding-top:5px; padding-right:5px;"><img src="images/board_reply.gif" width="16" height="16" border="0" /></div>
      <div style="float:left; padding-top:4px;" class="f_10 f_dark bold">New Topic</div>
      <br clear="all" />
    </div>
    
    <div style="height:385px; border-left:solid 1px #cbccd0; border-bottom:solid 1px #cbccd0; border-right:solid 1px #cbccd0;">
      <div style="width:454px; height:100%; float:left;" class="bg_lite">
        <form style="display:inline;" name="_newTopic" action="/?action=group.board_new_topic.act" method="post">
          <div>
            <div style="float:left;">
              <div style="margin-left:5px;">
                <div style="padding-top:10px; padding-bottom:4px;">
                  <div class="bold f_medium">Title</div>
                  <div><input type="text" name="bp_title" class="formfield" style="width:350px;" /></div>
                </div>
                
                <div style="padding-bottom:8px;">
                  <div class="bold f_medium">Message</div>
                  <div><textarea id="bpc_content" name="bpc_content" class="formfield" style="width:400px; height:250px;"></textarea></div>
                </div>
              </div>
            </div>
            
            <div style="float:right;">
              <div style="margin-top:32px;">
                <img src="images/board_post_arrow.gif" width="20" height="39" border="0" />
              </div>
            </div>
            <br clear="all" />
          </div>
          
          <input type="hidden" name="group_id" value="<?php echo $bID; ?>" />
          <div style="margin-left:301px;">
            <div style="cursor:pointer;" onclick="if(_val_post()){ document.forms['_newTopic'].submit(); }">
              <div style="float:left; padding-right:3px;"><img src="images/board_new.gif" width="16" height="16" border="0" /></div>
              <div style="padding-top:1px;" class="bold">Submit Post</div>
            </div>
          </div>
          <input type="hidden" name="bp_u_id" value="<?php echo $_USER_ID; ?>" />
          <input type="hidden" name="bp_b_id" value="<?php echo $bID; ?>" />
        </form>
      </div>

      <div style="height:100%; width:224px; padding-left:5px; background-color:#d4d4d4; float:left;">
        <div style="padding-top:10px; padding-bottom:5px; text-align:center;">
          <div class="bold" style="padding-right:5px;">Add a photo or a slideshow to your post</div>
        </div>
        
        <div class="bold">
          <div id="fotosTab" onclick="toggleTabs('Fotos');" style="width:75px; text-align:center; background-color:#ffffff; border-left:solid #c0c0c0 1px; border-top:solid #c0c0c0 1px; padding:4px; cursor:pointer; float:left;">
            <div style="float:left; padding-left:10px; padding-right:3px;"><img src="images/board_fotos.gif" width="16" height="16" border="0" /></div>
            <div style="float:left; padding-top:2px;">Photos</div>
            <br clear="all" />
          </div>
          <div id="flixTab" onclick="toggleTabs('Flix');" style="width:100px; text-align:center; border-left:solid #c0c0c0 1px; border-top:solid #c0c0c0 1px; border-right:solid #c0c0c0 1px; padding:4px;; cursor:pointer; float:left;">
            <div style="float:left;"><img src="images/board_flix.gif" width="16" height="16" border="0" /></div>
            <div style="float:left; padding-top:2px;">Slideshows</div>
            <br clear="all" />
          </div>
          <br clear="all" />
        </div>
        <div style="width:218px; height:283px; background-color:#ffffff; border-right:solid #c0c0c0 1px; border-bottom:solid #c0c0c0 1px; border-left:solid #c0c0c0 1px;">
          <form style="display:inline;" id="searchByTagForm" onsubmit="return toggleFotoOrFlix();">
            <div style="float:left; padding-left:5px; padding-top:8px;">
              <img src="images/tag_search_icon.gif" border="0" style="float:left; padding-right:3px;">
              <input id="searchBox" class="formfield" type="text" style="float:left; display:block; width:80px;" />
              <input id="searchBoxFlix" class="formfield" type="text" style="float:left; display:none; width:80px;" />
            </div>
            <div id="auto_complete_searchBox" class="auto_complete" style="float:left; width:80px; z-index:75;"></div>
            <div style="float:left; padding-left:3px; padding-top:10px;"><input type="submit" value="search tags" class="normal" style="margin-top:-2px; cursor:pointer; border:0px; background-color:#ffffff;" /></div>
          </form>
          <br clear="all" />
          <div id="dataField" style="display:block; margin-top:5px; padding-top:5px; padding-left:5px; overflow:auto; width:210px; height:225px;"></div>
          <div id="dataFieldFlix" style="display:none; margin-top:5px; padding-top:5px; padding-left:5px; overflow:auto; width:210px; height:225px;"></div>
          <div style="height:10px;">
            <div style="display:block; float:left; margin-top:5px; padding-left:5px; width:90px;" id="PagePrevious"></div>
            <div style="display:none; float:left; margin-top:5px; padding-left:5px; width:90px;" id="PagePreviousFlix"></div>
            <div style="display:block; float:left; margin-top:5px; padding-right:5px; width:90px; text-align:right;" id="PageNext"></div>
            <div style="display:none; float:left; margin-top:5px; padding-right:5px; width:90px; text-align:right;" id="PageNextFlix"></div>
          </div>
        </div>
        <br clear="all" />
        <div style="padding-top:5px; text-align:center;">Click a photo or slideshow to insert</div>
      </div>
      <br clear="all" />
    </div>
  </div>
  
  <script type="text/javascript">
    var foto = true;
    var firstTime = true;
    
    function toggleTabs( str )
    {
      switch( str )
      {
        case 'Fotos':
          
          foto = true;
          
          document.getElementById('fotosTab').style.backgroundColor = '#ffffff';
          document.getElementById('searchBox').style.display = 'block';
          document.getElementById('dataField').style.display = 'block';
          document.getElementById('PagePrevious').style.display = 'block';
          document.getElementById('PageNext').style.display = 'block';
          
          document.getElementById('flixTab').style.backgroundColor = '#d4d4d4';
          document.getElementById('searchBoxFlix').style.display = 'none';
          document.getElementById('dataFieldFlix').style.display = 'none';
          document.getElementById('PagePreviousFlix').style.display = 'none';
          document.getElementById('PageNextFlix').style.display = 'none';
          
          break;
          
        case 'Flix':
          
          foto = false;
        
          if( firstTime == true )
          {
            toggleFotoOrFlix();
            firstTime = false;
          }
          
          document.getElementById('fotosTab').style.backgroundColor = '#d4d4d4';
          document.getElementById('searchBox').style.display = 'none';
          document.getElementById('dataField').style.display = 'none';
          document.getElementById('PagePrevious').style.display = 'none';
          document.getElementById('PageNext').style.display = 'none';
          
          document.getElementById('flixTab').style.backgroundColor = '#ffffff';
          document.getElementById('searchBoxFlix').style.display = 'block';
          document.getElementById('dataFieldFlix').style.display = 'block';
          document.getElementById('PagePreviousFlix').style.display = 'block';
          document.getElementById('PageNextFlix').style.display = 'block';
          
          break;
      }
    }

    function insertFoto( key )
    {
      document.getElementById('bpc_content').value += ' [photo]' + key + '[/photo] ';
      document.getElementById('bpc_content').focus();
    }
    
    function insertFlix( key )
    {
      document.getElementById('bpc_content').value += ' [slideshow]' + key + '[/slideshow] ';
      document.getElementById('bpc_content').focus();
    }
    
    function toggleFotoOrFlix()
    {
      if( foto == true )
      {
        replyFrmGetFotos( document.getElementById('searchBox').value, <?php echo $_USER_ID; ?> );
      }
      else
      {
        replyFrmGetFlix( document.getElementById('searchBoxFlix').value, <?php echo $_USER_ID; ?> );
      }
      
      return false;
    }
    
    window.onload = function()
                    {
                      toggleFotoOrFlix();
                    }
    
    new Autocompleter.Local("searchBox", "auto_complete_searchBox", userTags, {tokens: ","});
    new Autocompleter.Local("searchBoxFlix", "auto_complete_searchBox", userTags, {tokens: ","});  
  </script>
  
  
 <?php  
  $tpl->main($tpl->get());
  $tpl->clean();
?>
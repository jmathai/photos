<?php

  $board = CBoard::getInstance();
  $user = CUser::getInstance();
  $validator = new CFormValidator;
  
  $pID = $_GET['post_id'];
  $postData = $board->singlePost( $pID );
  $boardData = $board->board( $postData['BP_BID'] );
  
  $profileData = $user->profile($_USER_ID);
  $avatarSrc = $profileData['P_AVATAR'] != '' ? PATH_FOTO . $profileData['P_AVATAR'] : 'images/avatar.jpg';
  
  $quoteStr = '';
  if( isset( $_GET['reply_id'] ) )
  {
    $rID = $_GET['reply_id'];
    $quoteData = $board->post( $rID );
    $quoteUser = $user->find( $quoteData[0]['BP_UID'] );
    
    $quoteStr = "[quote][i]Posted by: " . $quoteUser['U_USERNAME'] . " on " . date( 'D M j, Y g:i a',$quoteData[0]['BP_DATECREATED']) . "[/i]\n\n" . $quoteData[0]['BP_CONTENT'] . "[/quote]";
  }
  
  $validator -> setForm('_reply');
  $validator -> addElement('bpc_content', 'Content', '  - Please enter the content of your post.', 'length');
  $validator -> setDebugOutput(false);
  $validator -> setFunctionName('_val_post');
  $validator -> validate();
?>

<div class="dataSingleContent">
  <div>
    <img src="images/community_welcome.gif" width="300" heihgt="88" border="0" vspace="10" />
  </div>
  
  <div style="padding-bottom:5px;">
    <a href="/?action=board.main">Community</a> >> <a href="/?action=board.board_view&board_id=<?php echo $postData['BP_BID']; ?>"><?php echo $boardData['B_TITLE']; ?></a> >> <a href="/?action=board.board_post&post_id=<?php echo $pID; ?>"><?php echo $postData['BP_TITLE']; ?></a> >> (Reply)
  </div>
  
  <div style="width:735px; margin-bottom:15px; padding-bottom:15px;">
    <div class="gradient_lt_grey">
      <div style="float:left; padding-top:5px; padding-right:5px;"><img src="images/board_reply.gif" width="16" height="16" border="0" /></div>
      <div style="float:left; padding-top:4px;" class="f_10 f_dark bold">Reply</div>
      <br clear="all" />
    </div>
    
    <div style="height:385px; border-left:solid 1px #cbccd0; border-bottom:solid 1px #cbccd0; border-right:solid 1px #cbccd0;">
      <div style="width:504px; height:100%; float:left;" class="bg_lite">
        <form style="display:inline;" name="_reply" action="/?action=board.reply.act&post_id=<?php echo $pID; ?>" method="post">
          <div style="float:left;">
            <div style="margin-left:45px;">
              <div style="padding-top:10px; padding-bottom:8px;">
                <div class="bold">Message</div>
                <div><textarea id="bpc_content" name="bpc_content" class="formfield" style="width:400px; height:250px;"><?php echo $quoteStr; ?></textarea></div>
              </div>
            </div>
            
            <div style="margin-left:351px;">
              <div style="cursor:pointer;" onclick="if(_val_post()){ document.forms['_reply'].submit(); }">
                <div style="float:left; padding-right:3px;"><img src="images/board_new.gif" width="16" height="16" border="0" /></div>
                <div style="padding-top:1px;" class="bold">Submit Post</div>
              </div>
            </div>
          </div>
          
          <div style="float:right;">
            <div style="margin-top:32px;">
              <img src="images/board_post_arrow.gif" width="20" height="39" border="0" />
            </div>
          </div>
          
          <br clear="all" />
          <input type="hidden" name="bp_u_id" value="<?php echo $_USER_ID; ?>" />
          <input type="hidden" name="bp_b_id" value="<?php echo $postData['BP_BID']; ?>" />
          <input type="hidden" name="bp_p_id" value="<?php echo $pID; ?>" />
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
          <div id="flixTab" onclick="toggleTabs('Flix');" style="width:105px; text-align:center; border-left:solid #c0c0c0 1px; border-top:solid #c0c0c0 1px; border-right:solid #c0c0c0 1px; padding:4px;; cursor:pointer; float:left;">
            <div style="float:left; padding-left:10px; padding-right:3px;"><img src="images/board_flix.gif" width="16" height="16" border="0" /></div>
            <div style="float:left; padding-top:2px;">Slideshows</div>
            <br clear="all" />
          </div>
          <br clear="all" />
        </div>
        <div style="width:218px; height:283px; background-color:#ffffff; border-right:solid #c0c0c0 1px; border-bottom:solid #c0c0c0 1px; border-left:solid #c0c0c0 1px;">
          <form style="display:inline;" id="searchByTagForm" onsubmit="return toggleFotoOrFlix();">
            <div style="float:left; padding-left:5px; padding-top:8px; padding-bottom:3px;">
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
        replyFrmGetFotos( document.getElementById('searchBox').value );
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
    $userData = $user->find( $postData['BP_UID'] );
    $profileData = $user->profile( $postData['BP_UID'] );
    $avatarSrc = $profileData['P_AVATAR'] != '' ? PATH_FOTO . $profileData['P_AVATAR'] : 'images/avatar.jpg';
    $userNumberPosts = $board->getNumberPosts( $postData['BP_UID'] );
    $postData['BP_CONTENT'] = $board->parseContent($postData['BP_CONTENT']);
  ?>
  <div style="margin-top:5px; width:737px;">
    <div style="height:25px;" class="gradient_lt_grey">
      <div style="float:left; padding-top:1px;"><img src="images/board_new_medium.gif" border="0"></div>
      <div style="float:left; padding-top:4px; padding-left:5px;" class="f_10 f_dark bold">Original Post</div>
    </div>
  </div>
  
  <div style="width:735px;" class="border_dark">
    <div>
      <div style="border:solid white 1px; height:5px; width:733px; overflow:hidden;" class="bg_medium"></div>
    </div>
    <div style="margin-top:1px; height:100%; min-height:165px; _height:165px;" class="bg_lite">
      <div style="float:left; width:147px;">
        <div style="padding-left:2px;" class="f_8 f_black bold"><?php echo $userData['U_USERNAME']; ?></div>
        <div style="padding-top:10px; padding-left:35px;"><img src="<?php echo $avatarSrc; ?>" width="75" height="75" class="border_dark" /></div>
        <div style="padding-top:10px; padding-left:2px;" class="f_7 f_dark">Joined: <?php echo date( 'j M Y', $userData['U_DATECREATED'] ); ?></div>
        <div style="padding-left:2px;"class="f_7 f_dark">Posts: <?php echo $userNumberPosts['BP_COUNT']; ?></div>
        <div style="float:left; padding-left:3px; padding-top:10px;">
          <div style="float:left;"><a href="/users/<?php echo $userData['U_USERNAME']; ?>" style="text-decoration:none;"><img src="/images/board_fotopage.gif" border="0" width="16" height="16" /></a></div>
          <div style="float:left; padding-left:2px; padding-top:2px;" class="f_7 f_dark"><a href="/users/<?php echo $userData['U_USERNAME']; ?>" style="text-decoration:none;">Fotopage</a></div>
          <!-- <div style="float:left; padding-left:10px;"><img src="/images/board_invite.gif" border="0" /></div>
          <div style="float:left; padding-left:2px; padding-top:2px;" class="f_7 f_black">Invite</div> -->
        </div>
      </div>
      <div style="float:left; width:583px; border-left:solid 1px #ffffff; min-height:165px; _height:165px;">
        <div style="height:20px;">
          <div style="float:left; padding-top:6px; padding-left:10px;" class="f_7 f_dark">Posted: <?php echo date( 'D M j, Y g:i a', $postData['BP_DATECREATED'] ); ?></div>
          <div style="float:left; padding-top:6px; padding-left:15px;" class="f_7 f_dark">Post subject: <?php echo $postData['BP_TITLE']; ?></div>
        </div>
        
        <div style="margin-top:5px; margin-bottom:10px; padding-left:4px;">
          <div style="width:580px; height:1px; overflow:hidden;" class="bg_medium"></div>
        </div>
        <div style="padding-left:10px; min-height:110px;" class="f_8 f_black"><?php echo nl2br($postData['BP_CONTENT']); ?></div>
      </div>
      <br clear="all" />
    </div>
  </div>
</div>
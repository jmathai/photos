<?php
  $board = CBoard::getInstance();
  $user = CUser::getInstance();
  $g = &CGroup::getInstance();
  
  $g_id = $_GET['group_id'];
  if( $_GET['post_id'] == '' )
  {
    echo 'Error';
    exit;
  }
  
  $user_group = $g->groups($_USER_ID, $g_id);
  if(count($user_group) == 0)
  {
    $tpl->kill("You are not a member of this group");
  }
  
  $pID = $_GET['post_id'];
  
  
  // paging info
  $currentPage = isset($_GET['page']) ? intval($_GET['page'])  : 1;
  $limit = 10;
  $postData = $board->postByPage( $pID, ($currentPage-1)*$limit, $limit );
  $totalRows = $GLOBALS['dbh']->found_rows();
  $pagesToDisplay = 6;
  $totalPages = ceil($totalRows/$limit);

  $page  =& new CPaging($currentPage, $pagesToDisplay, $totalPages, 'page', '/', $_SERVER['QUERY_STRING']);
  
  $pages = $page->getPages();
  $firstPage = $page->getFirstPage('First');
  $lastPage = $page->getLastPage('Last');
  $nextPage = $page->getNextPage('Next');
  $prevPage = $page->getPrevPage('Previous');
  
  
  // data for the board this post resides in
  $boardData = $board->board( $postData[0]['BP_BID'] );
  
  // first post to get title
  $originalPost = $board->singlePost( $pID );
  if( $originalPost['BP_BID'] != $g_id )
  {
    $tpl->kill("This post is not a part of the group specified");
  }
  
  // add a view in the db
  $data = array();
  $data['bv_bp_id'] = $pID;
  $data['bv_u_id'] = $_USER_ID;
  $board->addView( $data );
  
  
  echo '<div style="float:left;">';
    echo '<div style="padding-top:3px; padding-right:10px;">
            <div style="float:left; width:580px;"><a href="/?action=group.board_main&group_id=' . $boardData['B_ID'] . '">' . $boardData['B_TITLE'] . '</a> >> ' . $originalPost['BP_TITLE'] . '</div>
            <div>
              <div style="float:left; padding-right:5px;"><a href="/?action=group.board_reply&group_id=' . $g_id . '&post_id=' . $pID . '" style="text-decoration:none;"><img src="images/board_reply.gif" border="0"></a></div>
              <div><a href="/?action=group.board_reply&group_id=' . $g_id . '&post_id=' . $pID . '">Reply To Topic</a></div>
            </div>
          </div>
    
          <div style="padding-top:15px; text-align:right; width:685px;">Pages: ' . $firstPage . ' ' . $prevPage . ' ' . $pages . ' ' . $nextPage . ' ' . $lastPage . '</div>
  
          <div style="margin-top:5px; width:687px;">
            <div style="height:25px;" class="gradient_lt_grey">
              <div style="float:left; padding-top:1px;"><img src="images/board_new_medium.gif" border="0"></div>
              <div style="float:left; padding-top:4px; padding-left:5px;" class="f_10 f_dark bold">' . $originalPost['BP_TITLE'] . '</div>
            </div>
          </div>
          
          <div style="width:685px;" class="border_dark">
          
            
            <div style="border:solid white 1px; height:5px; width:683px; overflow:hidden;" class="bg_medium"></div>';
            
            // retrieve each post
            foreach( $postData as $key => $post )
            {
              $userData = $user->find( $post['BP_UID'] );
              $profileData = $user->profile( $post['BP_UID'] );
              $userNumberPosts = $board->getNumberPosts( $post['BP_UID'] );
              $avatar = $user->pref($userData['U_ID'], 'AVATAR');
              $avatarSrc = $avatar != '' ? PATH_FOTO . $avatar : 'images/avatar.jpg';
              $post['BP_CONTENT'] = $board->parseContent( $post['BP_CONTENT'] );
            
      
      echo '  <a name="post' . $post['BP_ID'] . '"></a>
              <div style="margin-top:1px; min-height:165px; _height:165px;" class="bg_lite">
      
                <div style="float:left; width:147px;">
                  <div style="padding-left:2px;" class="f_10 f_black bold">' . $userData['U_USERNAME'] . '</div>
                  <div style="padding-top:10px; padding-left:35px;"><a href="/users/' . $userData['U_USERNAME'] . '/"><img src="' . $avatarSrc . '" width="75" height="75" border="0" class="border_dark" /></a></div>
                  <div style="padding-top:10px; padding-left:2px;" class="f_7 f_dark">Joined: ' . date( 'j M Y', $userData['U_DATECREATED'] ) . '</div>
                  <div style="padding-left:2px;"class="f_7 f_dark">Posts: ' . $userNumberPosts['BP_COUNT'] . '</div>
                  <div style="float:left; padding-left:3px; padding-top:10px;">
                    <div style="float:left;"><a href="/users/' . $userData['U_USERNAME'] . '" style="text-decoration:none;"><img src="images/board_fotopage.gif" border="0" /></a></div>
                    <div style="float:left; padding-left:2px; padding-top:2px;" class="f_7 f_dark"><a href="/users/' . $userData['U_USERNAME'] . '" style="text-decoration:none;">Fotopage</a></div>
                    <!-- <div style="float:left; padding-left:10px;"><img src="images/board_invite.gif" border="0" /></div>
                    <div style="float:left; padding-left:2px; padding-top:2px;" class="f_7 f_dark">Invite</div> -->
                  </div>
                </div>
    
                
                <div style="float:left; width:536px; border-left:solid white 1px; min-height:165px; _height:165px;">
                  <div style="height:20px;">
                    <div style="float:left; padding-top:6px; padding-left:10px;" class="f_7 f_dark">Posted: ' . date( 'D M j, Y g:i a', $post['BP_DATECREATED'] ) . '</div>
                  </div>
                  <div style="margin-top:5px; margin-bottom:10px; padding-left:4px;">
                    <div style="width:530px; height:1px; overflow:hidden;" class="bg_medium"></div>
                  </div>
                  <div style="padding-left:10px; min-height:110px; _height:110px;" class="f_8 f_black">
                    ' . nl2br($post['BP_CONTENT']) . '
                  </div>
                  <div>
                    <div style="float:right; padding-right:7px; padding-bottom:3px;"><a href="/?action=group.board_reply&group_id=' . $g_id . '&post_id=' . $pID . '&reply_id=' . $post['BP_ID'] . '" style="text-decoration:none;"><img src="images/board_quote.gif" border="0" /></a></div>
                    <div style="float:right; padding-right:5px;" class="f_8 f_dark"><a href="/?action=group.board_reply&group_id=' . $g_id . '&post_id=' . $pID . '&reply_id=' . $post['BP_ID'] . '" style="text-decoration:none;">Quote</a></div>
                  </div>
                </div>
               
                <br clear="all" />
                
              </div>';
      
              if( $postData[$key+1] != null )
              {
      echo '    <div>
                  <div style="border:solid white 1px; height:5px; width:683px; overflow:hidden;" class="bg_medium"></div>
                </div>';
              }
    
            }
            
            
    echo '</div>
    
          <div style="padding-top:15px; text-align:right; width:685px;">Pages: ' . $firstPage . ' ' . $prevPage . ' ' . $pages . ' ' . $nextPage . ' ' . $lastPage . '</div>
    
          <div style="padding-top:15px;">
            <div style="float:left; padding-left:580px; padding-right:5px;"><a href="/?action=group.board_reply&group_id=' . $g_id . '&post_id=' . $pID . '" style="text-decoration:none;"><img src="images/board_reply.gif" border="0"></a></div>
            <div><a href="/?action=group.board_reply&group_id=' . $g_id . '&post_id=' . $pID . '">Reply To Topic</a></div>
          </div>';
  
  echo '</div>';
  
include_once PATH_DOCROOT . '/group_sponsors.dsp.php';

?>
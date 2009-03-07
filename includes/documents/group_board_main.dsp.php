<?php
  $b = &CBoard::getInstance();
  $g = &CGroup::getInstance();
  $u = &CUser::getInstance();
  
  $g_id = $_GET['group_id'];
  $user_group = $g->groups($_USER_ID, $g_id);
  if(count($user_group) == 0)
  {
    $tpl->kill("You are not a member of this group");
  }
  
  // data for this board
  $boardData = $b->board( $g_id );
  
  // paging info
  $currentPage = isset($_GET['page']) ? intval($_GET['page'])  : 1;
  $limit = 20;
  $postData = $b->postsByPages( $g_id, ($currentPage-1)*$limit, $limit );
  $totalRows = $GLOBALS['dbh']->found_rows();
  $pagesToDisplay = 6;
  $totalPages = ceil($totalRows/$limit);
  
  $page  =& new CPaging($currentPage, $pagesToDisplay, $totalPages, 'page', '/', $_SERVER['QUERY_STRING']);
  
  $pages = $page->getPages();
  $firstPage = $page->getFirstPage('First');
  $lastPage = $page->getLastPage('Last');
  $nextPage = $page->getNextPage('Next');
  $prevPage = $page->getPrevPage('Previous');
?>

<div style="padding-top:3px; padding-right:10px;">
  <div style="float:left;"><a href="/?action=group.board_main&group_id=<?php echo $g_id; ?>"><?php echo $boardData['B_TITLE']; ?></div>
  <div>
    <div style="float:left; padding-left:510px; padding-right:5px;"><a href="/?action=group.board_new_topic&group_id=<?php echo $g_id; ?>" style="text-decoration:none;"><img src="images/board_reply.gif" border="0"></a></div>
    <div><a href="/?action=group.board_new_topic&group_id=<?php echo $g_id; ?>">Post New Topic</a></div>
  </div>
</div>

<div style="padding-top:15px; text-align:right; width:685px;">Pages:<?php echo $firstPage . ' ' . $prevPage . ' ' . $pages . ' ' . $nextPage . ' ' . $lastPage; ?></div>

<div style="padding-top:10px;">

  <div style="margin-top:5px; width:687px;">
    <div style="height:25px;" class="gradient_lt_grey">
      <div style="float:left; width:300px; height:25px; border-right:solid black 1px;">
        <div style="float:left; padding-top:6px; width:300px; text-align:center;" class="f_7 f_dark bold">Topics</div>
      </div>
      <div style="float:left; width:45px; height:25px; border-right:solid black 1px;">
        <div style="float:left; padding-top:6px; width:45px; text-align:center;" class="f_7 f_dark bold">Replies</div>
      </div>
      <div style="float:left; width:125px; height:25px; border-right:solid black 1px;">
        <div style="float:left; padding-top:6px; width:125px; text-align:center;" class="f_7 f_dark bold">Author</div>
      </div>
      <div style="float:left; width:45px; height:25px; border-right:solid black 1px;">
        <div style="float:left; padding-top:6px; width:45px; text-align:center;" class="f_7 f_dark bold">Views</div>
      </div>
      <div style="float:left; padding-top:6px; width:105px; text-align:center;" class="f_7 f_dark bold">Last Post</div>
    </div>
  </div>
  
  <div style="width:685px;" class="border_dark">
    
    <div class="bg_medium">
      <div style="border:solid white 1px; height:29px; background:url(images/board_category_gradient.gif) repeat-x;">
        <div style="float:left; padding-left:5px;"><img src="images/board_new_double_medium.gif" border="0" /></div>
        <div style="float:left; padding-left:4px; padding-top:4px;" class="f_10 f_dark bold"><?php echo $boardData['B_TITLE']; ?></div>
      </div>
    </div>
    
    <?php 
      if( $postData == null )
      {
        echo '<div style="padding-left:10px; padding-top:5px; height:300px;" class="f_10 black bold italic">No Topics</div>';
      }
      else
      {
      foreach( $postData as $key => $post )
      {
        $userData = $u->find( $post['BP_UID'] );
        $profileData = $u->profile( $post['BP_UID'] );
        $avatar = $u->pref($userData['U_ID'], 'AVATAR');
        $avatarSrc = $avatar != '' ? PATH_FOTO . $avatar : 'images/avatar.jpg';
        
        // paging info
        $limitPost = 10;
        $postData = $b->postByPage( $post['BP_ID'], 0, 0 );
        $totalRowsPost = $GLOBALS['dbh']->found_rows();
        $totalPagesPost = ceil($totalRowsPost/$limitPost);
        
        $pagePost  =& new CPaging(0, $pagesToDisplay, $totalPagesPost, 'page', '/', 'action=group.board_post&group_id=' . $g_id . '&post_id=' . $post['BP_ID'] );
        $pagesPost = $pagePost->getPages();
      
        echo '<div style="height:30px; border:solid white 1px;" class="bg_lite" onmouseover="this.className=\'bg_medium\';" onmouseout="this.className=\'bg_lite\';">
                <div style="float:left; width:30px; height:30px; border-right:solid white 1px;">';
  
        if( $post['BP_STICKY'] == 'Y' )
        {
          echo '<div></div>';
        }
        else 
        {
          echo '<div style="padding-left:6px; padding-top:6px;"><img src="images/board_new.gif" border="0" /></div>';
        }
          
        echo '</div>
            <div style="float:left; width:270px; height:30px; border-right:solid white 1px; cursor:pointer;" onclick="location.href=\'/?action=group.board_post&group_id=' . $g_id . '&post_id=' . $post['BP_ID'] . '\';">
            <div style="float:left; padding-top:8px; padding-left:3px;" class="f_8 f_dark bold"><a href="/?action=group.board_post&group_id=' . $g_id . '&post_id=' . $post['BP_ID'] . '">' . str_mid($post['BP_TITLE'], 40) . '</a></div>';
            
            if( strpos( $pagesPost, '2' ) != 0 )
            {
              echo '<div style="float:left; padding-left:15px; padding-top:8px;" class="f_8 f_dark">Pages: ' . $pagesPost . '</div>';
            }
            
        echo '</div>
            <div style="float:left; width:45px; height:30px; border-right:solid white 1px;">
              <div style="text-align:center; padding-top:8px;" class="f_8 f_dark">' . $post['BP_REPLIES'] . '</div>
            </div>
            <div style="float:left; width:125px; height:30px; border-right:solid white 1px;">
              <div style="margin:auto; padding-left:4px; padding-top:5px;" class="f_8 f_dark">
                <div style="float:left;"><img src="' . $avatarSrc . '" width="20" height="20" border="0" /></div>
                <div style="float:left; margin-top:2px; margin-left:4px;"><a href="/users/' . $userData['U_USERNAME'] . '/">' . $userData['U_USERNAME'] . '</a></div>
                <br clear="all" />
              </div>
            </div>
            <div style="float:left; width:45px; height:30px; border-right:solid white 1px;">
              <div style="text-align:center; padding-top:8px;" class="f_8 f_dark">' . $post['BP_VIEWS'] . '</div>
            </div>';
    
        if( $post['BP_LASTPOSTID'] == null )
        {
          $date = date( 'D M j, Y g:i a', $post['BP_DATECREATED'] );
          $lastPostUser = $userData['U_USERNAME'];
        }
        else 
        {
          $lastPost = $b->singlePost( $post['BP_LASTPOSTID'] );
          $lastUserData = $u->find( $lastPost['BP_UID'] );
          $date = date( 'D M j, Y g:i a', $lastPost['BP_DATECREATED'] );
          $lastPostUser = $lastUserData['U_USERNAME'];
        }
          
        echo '<div style="text-align:center;" class="f_7 f_dark">
            <div>' . $date . '</div>
            <div class="italic"><a href="/users/' . $lastPostUser . '/">' . $lastPostUser . '</a></div>
          </div>
        </div>';
  
      }
    }
                   
    ?>
  
  </div>
</div>

<?php  
  $tpl->main($tpl->get());
  $tpl->clean();
?>
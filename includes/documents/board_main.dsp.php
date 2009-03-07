<?php
  $boards = CBoard::getInstance();
  $user = CUser::getInstance();
?>

<div class="dataSingleContent">
  <div>
    <img src="images/community_welcome.gif" width="300" heihgt="88" border="0" vspace="10" />
  </div>
  
  <div style="width:735px; height:25px; margin:auto;">
    <div class="gradient_lt_grey f_10 f_dark bold">
      <div style="width:399px; height:25px; border-right:solid 1px #000000; float:left;">
        <div style="padding-top:4px; padding-left:5px;">Community</div>
      </div>
      <div style="width:74px; height:25px; border-right:solid 1px #000000; float:left;">
        <div style="padding-top:4px; text-align:center;">Topics</div>
      </div>
      <div style="width:74px; height:25px; border-right:solid 1px #000000; float:left;">
        <div style="padding-top:4px; text-align:center;">Posts</div>
      </div>
      <div style="width:180px; height:25px; float:left;">
        <div style="padding-top:4px; text-align:center;">Last Post</div>
      </div>
      <br clear="all" />
    </div>
  </div>
  
  <div style="width:733px; margin:auto;" class="border_dark">
    <?php
      $categoryData = $boards->categories();
      foreach( $categoryData as $key => $category )
      {
        $boardData = $boards->boardsByCategory( $category['BC_ID'] );
        if( ($boardData) != null )
        {
          echo '<div style="border-bottom:solid 1px #ffffff;" class="bg_medium">
                  <div class="f_10 bold" style="padding-left:5px; width:728px; height:29px; background:url(images/board_category_gradient.gif) repeat-x;">
                    <div style="padding-top:2px; padding-right:5px; float:left;"><img src="images/board_category.gif" width="18" height="21" border="0" /></div>
                    <div style="padding-top:4px; float:left;">' . $category['BC_TITLE'] . '</div>
                    <br clear="all" />
                  </div>
                </div>';
          $k = 0;
          foreach( $boardData as $board )
          {
            //B_BPID
            $lastPost = $boards->singlePost( $board['B_BPID'] );
            $lastUserData = $user->find( $lastPost['BP_UID'] );
            $posts = $boards->countPosts($board['B_ID']);
            $topics = $boards->countTopics($board['B_ID']);
            //date('D M d, g:i a', $lastPost['BP_DATECREATED']) . '<br />' . $lastUserData['U_USERNAME']
            if($lastPost['BP_DATECREATED'] > 0)
            {
              $lastPostText = date('D M d, g:i a', $lastPost['BP_DATECREATED']) . '<br /><a href="/users/' . $lastUserData['U_USERNAME'] . '/" class="italic">' . $lastUserData['U_USERNAME'] . '</a>';
            }
            else
            {
              $lastPostText = '';
            }
            
            echo '<div style="min-height:50px; _height:50px; border-bottom:solid 1px #ffffff;" class="bg_lite" onmouseover="this.className=\'bg_medium\';" onmouseout="this.className=\'bg_lite\';">
                    <div style="width:49px; height:50px; border-right:solid 1px #ffffff; float:left;">
                      <div style="padding-top:17px; padding-left:17px;"><img src="images/board_new_double.gif" width="16" height="16" border="0" /></div>
                    </div>
                    <div style="width:350px; height:50px; border-right:solid 1px #ffffff; float:left;">
                      <div style="padding-left:5px; padding-top:3px; cursor:pointer;" onclick="location.href=\'/?action=board.board_view&board_id=' . $board['B_ID'] . '\';">
                        <div class="bold"><a href="/?action=board.board_view&board_id=' . $board['B_ID'] . '">' . $board['B_TITLE'] . '</a></div>
                        <div>' . $board['B_DESCRIPTION'] . '</div>
                        <!--<div>Moderator: <span class="italic">Staff</span></div>-->
                      </div>
                    </div>
                    <div style="width:74px; height:30px; padding-top:20px; border-right:solid 1px #ffffff; text-align:center; float:left;" class="bold">' . $topics . '</div>
                    <div style="width:74px; height:30px; padding-top:20px; border-right:solid 1px #ffffff; text-align:center; float:left;" class="bold">' . $posts . '</div>
                    <div style="width:180px; height:37px; padding-top:13px; text-align:center; float:left;">' . $lastPostText . '</div>
                  </div>';
          }
        }
      }
    ?>
  </div>
</div>
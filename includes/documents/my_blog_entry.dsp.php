<?php
  $entryId = $options[1];
  $b =& CBlog::getInstance();
  $c =& CComment::getInstance();
  $entry = $b->entry($user_id, $entryId);
  $comments = $c->comments($entryId, 'blog');
  
  $editText = '';
  if($_USER_ID == $user_id)
  {
    $editText = ' &nbsp; <a href="/users/' . $username . '/blog/add_entry/' . $entry['B_ID'] . '/" class="plain f_9"><img src="/images/icons/edit_alt_2_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" /> Edit</a>';
  }
  
  echo '
    <div id="blogContent">
      <div class="entrySubject"><a href="/users/' . $username . '/blog/entry/' . $entry['B_ID'] . '/' . $entry['B_PERMALINK'] . '">' . $entry['B_SUBJECT'] . '</a>' . $editText . '</div>
      <div class="entryAuthor">Posted by ' . $displayName . ' on ' . date(FF_FORMAT_DATE_LONG, $entry['B_DATEPOSTED']) . '</div>
      <div class="entryBody">' . $entry['B_BODY'] . '</div>
      <div class="entryFooter">' . $entry['B_COMMENTS'] . ' comments | <a href="/users/' . $username . '/blog/entry/' . $entry['B_ID'] . '/' . $entry['B_PERMALINK'] . '">permalink</a></div>
      <br/>
      <hr size="1" />
      <br/>';
  foreach($comments as $v)
  {
    $avatarSrc = $v['C_AVATAR'] != '' ? PATH_FOTO . $v['C_AVATAR'] : 'images/avatar.jpg';
    $userString = $v['C_BY_USERNAME'] != '' ? '<a href="/users/' . $v['C_BY_USERNAME'] . '/">' . $v['C_BY_USERNAME'] . '</a>' : 'anonymous';
    echo '<div style="padding-bottom:10px; padding-left:2px;">
            <a name="comment' . $v['C_ID'] . '"></a>
            <div style="float:left; padding-right:5px; width:45px;"><img src="' . $avatarSrc . '" width="40" height="40" border="0" /></div>
            <div style="float:left; width:190px;">
              <a name="' . $v['C_ID'] . '"></a>
              <div style="padding-bottom:4px;">' . $userString . ' said:</div>
              <div style="padding-bottom:4px;">' . nl2br($v['C_COMMENT']) . '</div>
              <div class="italic">' . date(FF_FORMAT_DATE_LONG, $v['C_TIME']) . '</div>
              <div>(<a href="/users/' . $username . '/blog/entry/' . $v['C_ELEMENT_ID'] . '/#comment' . $v['C_ID'] . '">view comment</a>)</div>
            </div>
            <br clear="all"/>
            <br clear="all"/>
          </div>';
  }
  
  if($logged_in === true)
  {
    echo '
      <form action="/?action=comment.act" name="commentForm" method="post">
        <div class="entryComment">
          <div><textarea rows="8" cols="50" name="c_comment" id="c_comment" class="formfield"></textarea></div>
        </div>
        <input type="hidden" name="c_element_id" value="' . $entryId . '" />
        <input type="hidden" name="c_type" value="blog" />
        <input type="hidden" name="c_for_u_id" value="' . $user_id . '" />
        <input type="hidden" name="redirect" value="' . $_SERVER['REQUEST_URI'] . '" />
        <div style="margin-top:5px;">
          <a href="javascript:void(0);" onclick="if(blogEntryCommentForm()){ document.forms[\'commentForm\'].submit(); }" class="f_white plain f_10 bold"><img src="images/icons/chat_bubble_16x16.png" class="png" width="16" height="16" hspace="3" border="0" align="absmiddle" />Leave a Comment</a>
        </div>
      </form>';
  }
  
  echo '
      <hr size="1" />
    </div>
    <div id="blogSideBar">';
  
  include_once PATH_DOCROOT . '/my_sidebar.dsp.php';
      
  echo '
    </div>
  ';
?>
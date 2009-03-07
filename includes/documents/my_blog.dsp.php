<?php
  $b =& CBlog::getInstance();
  $entries = $b->entries($user_id, 10);
  
  echo '<div id="blogContent">';
  
  if(count($entries) > 0)
  {
    foreach($entries as $v)
    {
      $editText = '';
      if($_USER_ID == $user_id)
      {
        $editText = ' &nbsp; <a href="/users/' . $username . '/blog/add_entry/' . $v['B_ID'] . '/" class="plain f_9"><img src="/images/icons/edit_alt_2_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" /> Edit</a>'
                  . ' &nbsp; <a href="javascript:void(0);" onclick="blogEntryDelete(' . $v['B_ID'] . ');" class="plain f_9"><img src="/images/icons/document_remove_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" /> Delete</a>';
      }
      
      echo '
        <div id="blog_entry_' . $v['B_ID'] . '">
          <div class="entrySubject"><a href="/users/' . $username . '/blog/entry/' . $v['B_ID'] . '/' . $v['B_PERMALINK'] . '">' . $v['B_SUBJECT'] . '</a>' . $editText . '</div>
          <div class="entryAuthor">Posted by ' . $displayName . ' on ' . date(FF_FORMAT_DATE_LONG, $v['B_DATEPOSTED']) . '</div>
          <div class="entryBody">' . $v['B_BODY'] . '</div>
          <div class="entryFooter">' . $v['B_COMMENTS'] . ' comments | <a href="/users/' . $username . '/blog/entry/' . $v['B_ID'] . '/' . $v['B_PERMALINK'] . '">permalink</a></div>
          <br/>
          <hr size="1" />
          <br/>
        </div>
      ';
    }
  }
  else
  {
    if($_USER_ID == $user_id)
    {
      include_once PATH_DOCROOT . '/my_blog_begin.dsp.php';
    }
    else
    {
      echo '<br/><br/><div class="center f_12 bold">' . $displayName . ' does not have any blog posts.</div>';
    }
  }
  
  echo '&nbsp;
        </div>
        <div id="blogSideBar">';
  
  include_once PATH_DOCROOT . '/my_sidebar.dsp.php';
  
  echo '
        </div>
        ';
?>
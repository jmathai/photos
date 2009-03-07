<?php
  $pm = &CPrivateMessage::getInstance();
  $u = &CUser::getInstance();
  
  $hasOptedOut = $pm->hasOptedOut($_USER_ID);
  
  if( $hasOptedOut === true )
  {
    echo '<div style="padding-left:10px;" class="f_10 bold">Your inbox is not available because you have disabled private messaging.  <a href="javascript:void(pm_optIn());">Enable private messages</a></div>';
  }
  else 
  {
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'inbox';
  
    $currentPage = isset($_GET['page']) ? intval($_GET['page'])  : 1;
    $limit = 10;
    $offset = ($currentPage-1)*$limit;
    
    echo '<div style="margin-left:10px">';
    echo '<div style="padding-bottom:15px;" class="f_10 bold">Messages</div>';
    
    switch($tab)
    {
      case 'inbox':
        echo '<div style="float:left; border-left:1px solid #dddddd; border-right:1px solid #dddddd; border-top:1px solid #dddddd; width:100px; padding-top:5px; padding-bottom:5px;" class="f_8 bold center">Inbox</div>';
        echo '<a href="/?action=messaging.home&tab=sent" title="View sent messages" style="cursor:pointer;"><div style="float:left; border-bottom:1px solid #dddddd; border-right:1px solid #dddddd; border-top:1px solid #dddddd; background-color:#eeeeee; width:100px; padding-top:5px; padding-bottom:5px;" class="f_8 bold center">Sent</div></a>';
        echo '<a href="/?action=messaging.home&&tab=trash" title="View deleted messages" style="cursor:pointer;"><div style="float:left; border-bottom:1px solid #dddddd; border-right:1px solid #dddddd; border-top:1px solid #dddddd; background-color:#eeeeee; width:100px; padding-top:5px; padding-bottom:5px;" class="f_8 bold center">Trash</div></a>';
        echo '<div style="float:left; border-bottom:1px solid #dddddd; width:480px; height:24px;"></div>';
        echo '<br clear="all" />';
        echo '<div style="border-left:1px solid #dddddd; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd; margin-top:-1px; width:783px;">';
        
        echo '<div style="padding-top:10px; margin-left:16px; width:751px;">';
        echo '<div style="float:left; width:50px;">&nbsp;</div>';
        echo '<div style="float:left; width:200px;" class="bold">From</div>';
        echo '<div style="float:left; width:350px;" class="bold">Subject</div>';
        echo '<div class="bold">Actions</div>';
        echo '</div>';
        
        $received = $pm->getReceivedMessages($_USER_ID, $limit, $offset);
        
        // paging info
        $totalRows = $GLOBALS['dbh']->found_rows();
        $pagesToDisplay = 6;
        $totalPages = ceil($totalRows/$limit);
      
        $paging =& new CPaging($currentPage, $pagesToDisplay, $totalPages, 'page', '/', $_SERVER['QUERY_STRING']);
        
        $pages = $paging->getPages();
        $firstPage = $paging->getFirstPage('<img src="images/paging_first.gif" border="0" />');
        $lastPage = $paging->getLastPage('<img src="images/paging_last.gif" border="0" />');
        $nextPage = $paging->getNextPage('<img src="images/paging_next.gif" border="0" />');
        $prevPage = $paging->getPrevPage('<img src="images/paging_previous.gif" border="0" />');
    
        foreach($received as $k => $v)
        {
          $avatar = $u->pref($v['PM_SENDER_ID'], 'AVATAR');
          if($avatar != '')
          {
            $avatarSrc = PATH_FOTO . $avatar;
          }
          else
          {
            $avatarSrc = 'images/avatar.jpg';
          }
      
          if($k % 2 == 0)
          {
            echo '<div style="margin-top:10px; margin-left:16px; width:751px;"><a href="/?action=messaging.message&type=received&id=' . $v['PM_ID'] . '">';
          }
          else 
          {
            echo '<div style="margin-top:10px; margin-left:16px; width:751px; background-color:#eeeeee;"><a href="/?action=messaging.message&type=received&id=' . $v['PM_ID'] . '">';
          }
          
          if($v['PM_STATUS'] == 'New')
          {
            echo '<div style="float:left; margin-left:5px; width:45px; padding-top:10px;"><img src="images/icons/mail_alt_2_16x16.png" class="png" border="0" width="16" height="16" title="new message" /></div>';
          }
          else 
          {
            echo '<div style="float:left; margin-left:5px; width:45px; padding-top:10px;"><img src="images/icons/mail_16x16.png" class="png" border="0" width="16" height="16" title="read message" /></div>';
          }
          echo '<div style="float:left; width:200px;">';
          echo '<div style="float:left; padding-right:5px;"><img src="' . $avatarSrc . '" border="0" width="40" height="40" /></div>';
          echo '<div style="padding-bottom:5px;">' . $v['U_SENDER_USERNAME'] . '</div>';
          echo '<div class="f_7 italic">' . date('M d, Y g:i a', $v['PM_DATECREATED']) . '</div>';
          echo '</div>';
          echo '<div style="float:left; width:350px; padding-top:5px;">' . $v['PM_SUBJECT'] . '</div></a>';
          echo '<div style="padding-top:5px;"><img src="images/icons/pencil_16x16.png" class="png" border="0" width="16" height="16" onclick="showReply(' . $v['PM_ID'] . ');" style="cursor:pointer;" /> <span class="f_7 bold" onclick="showReply(' . $v['PM_ID'] . ');" style="cursor:pointer;">reply</span> &nbsp; <img src="images/icons/trash_empty_16x16.png" class="png" border="0" width="16" height="16" onclick="showDelete(' . $v['PM_ID'] . ');" style="cursor:pointer;" /> <span class="f_7 bold" onclick="showDelete(' . $v['PM_ID'] . ');" style="cursor:pointer;">delete</span></div>';
          echo '<br clear="all" />';
          
          echo '<div id="message_reply_' . $v['PM_ID'] . '" style=" display:none; padding-top:20px; padding-left:55px;">';
          echo '<form id="message_reply_form_' . $v['PM_ID'] . '" onsubmit="pm_send(\'message_reply_' . $v['PM_ID'] . '\', ' . $v['PM_SENDER_ID'] . ', $(\'subject_' . $v['PM_ID'] . '\').value, $(\'message_text_' . $v['PM_ID'] . '\').value, 10, 605); return false;">';
          echo '<div style="float:left; padding-right:3px;">Subject:</div>';
          echo '<div style="float:left; padding-right:10px;"><input id="subject_' . $v['PM_ID'] . '" type="text" value="Re: ' . $v['PM_SUBJECT'] . '" class="formfield" /></div>';
          echo '<div style="float:left; padding-right:3px;">Message:</div>';
          echo '<div style="float:left; padding-right:10px;"><textarea id="message_text_' . $v['PM_ID'] . '" rows="3" cols="40" class="formfield"></textarea></div>';
          echo '<div><input type="submit" value="Reply" /></div>';
          echo '</form>';
          echo '</div>';
          
          echo '<div id="message_delete_' . $v['PM_ID'] . '" style="display:none; padding-top:10px; padding-left:565px;">';
          echo '<form id="message_delete_form_' . $v['PM_ID'] . '" action="">';
          echo '<div>Delete this message?</div>';
          echo '<input type="hidden" name="action" value="messaging.home.act" />';
          echo '<input type="hidden" name="PM_ID" value="' . $v['PM_ID'] . '" />';
          echo '<input type="hidden" name="type" value="received" />';
          echo '<div style="float:left; padding-right:5px; padding-left:10px; padding-top:5px;"><input type="submit" value="Yes" /></div>';
          echo '<div style="padding-top:5px;"><input type="submit" value="No" onclick="$(\'message_delete_' . $v['PM_ID'] . '\').style.display = \'none\'; return false;" /></div>';
          echo '</form>';
          echo '</div>';
          echo '<br clear="all" />';
          
          echo '</div>';
        }
          
        echo '<br />';
        echo '</div>';
        
        if($totalPages > 1)
        {
          echo '<div style="float:right; padding-right:30px; padding-top:10px;">';
          echo '<div style="float:left;">' . $firstPage . $prevPage . '</div>';
          echo '<div style="float:left;">' . $pages . '</div>';
          echo '<div>' . $nextPage . $lastPage . '</div>';
          echo '</div>';
        }
        break;
        
      case 'sent':
        echo '<a href="/?action=messaging.home&tab=inbox" title="View sent messages" style="cursor:pointer;"><div style="float:left; border-bottom:1px solid #dddddd; border-left:1px solid #dddddd; border-right:1px solid #dddddd; border-top:1px solid #dddddd; background-color:#eeeeee; width:100px; padding-top:5px; padding-bottom:5px;" class="f_8 bold center">Inbox</div></a>';
        echo '<div style="float:left; border-right:1px solid #dddddd; border-top:1px solid #dddddd; width:100px; padding-top:5px; padding-bottom:5px;" class="f_8 bold center">Sent</div>';
        echo '<a href="/?action=messaging.home&tab=trash" title="View deleted messages" style="cursor:pointer;"><div style="float:left; border-bottom:1px solid #dddddd; border-right:1px solid #dddddd; border-top:1px solid #dddddd; background-color:#eeeeee; width:100px; padding-top:5px; padding-bottom:5px;" class="f_8 bold center">Trash</div></a>';
        echo '<div style="float:left; border-bottom:1px solid #dddddd; width:480px; height:24px;"></div>';
        echo '<br clear="all" />';
        echo '<div style="border-left:1px solid #dddddd; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd; margin-top:-1px; width:783px;">';
        
        echo '<div style="padding-top:10px; margin-left:16px; width:751px;">';
        echo '<div style="float:left; width:50px;">&nbsp;</div>';
        echo '<div style="float:left; width:200px;" class="bold">To</div>';
        echo '<div style="float:left; width:350px;" class="bold">Subject</div>';
        echo '<div class="bold">Actions</div>';
        echo '</div>';
        
        $sent = $pm->getSentMessages($_USER_ID, $limit, $offset);
        
        // paging info
        $totalRows = $GLOBALS['dbh']->found_rows();
        $pagesToDisplay = 6;
        $totalPages = ceil($totalRows/$limit);
      
        $paging =& new CPaging($currentPage, $pagesToDisplay, $totalPages, 'page', '/', $_SERVER['QUERY_STRING']);
        
        $pages = $paging->getPages();
        $firstPage = $paging->getFirstPage('<img src="images/paging_first.gif" border="0" />');
        $lastPage = $paging->getLastPage('<img src="images/paging_last.gif" border="0" />');
        $nextPage = $paging->getNextPage('<img src="images/paging_next.gif" border="0" />');
        $prevPage = $paging->getPrevPage('<img src="images/paging_previous.gif" border="0" />');
    
        foreach($sent as $k => $v)
        {
          $avatar = $u->pref($v['PM_RECEIVER_ID'], 'AVATAR');
          if($avatar != '')
          {
            $avatarSrc = PATH_FOTO . $avatar;
          }
          else
          {
            $avatarSrc = 'images/avatar.jpg';
          }
      
          if($k % 2 == 0)
          {
            echo '<div style="margin-top:10px; margin-left:16px; width:751px;"><a href="/?action=messaging.message&&type=sent&id=' . $v['PM_ID'] . '">';
          }
          else 
          {
            echo '<div style="margin-top:10px; margin-left:16px; width:751px; background-color:#eeeeee;"><a href="/?action=messaging.message&&type=sent&id=' . $v['PM_ID'] . '">';
          }
          
          if($v['PM_STATUS'] == 'New')
          {
            echo '<div style="float:left; margin-left:5px; width:45px; padding-top:10px;"><img src="images/icons/mail_alt_2_16x16.png" class="png" border="0" width="16" height="16" title="new message" /></div>';
          }
          else 
          {
            echo '<div style="float:left; margin-left:5px; width:45px; padding-top:10px;"><img src="images/icons/mail_16x16.png" class="png" border="0" width="16" height="16" title="read message" /></div>';
          }
          echo '<div style="float:left; width:200px;">';
          echo '<div style="float:left; padding-right:5px;"><img src="' . $avatarSrc . '" border="0" width="40" height="40" /></div>';
          echo '<div style="padding-bottom:5px;">' . $v['U_RECEIVER_USERNAME'] . '</div>';
          echo '<div class="f_7 italic">' . date('M d, Y g:i a', $v['PM_DATECREATED']) . '</div>';
          echo '</div>';
          echo '<div style="float:left; width:350px; padding-top:5px;">' . $v['PM_SUBJECT'] . '</div></a>';
          echo '<div style="float:left; padding-top:5px; padding-left:15px;"><img src="images/icons/trash_empty_16x16.png" class="png" border="0" width="16" height="16" onclick="showDelete(' . $v['PM_ID'] . ');" style="cursor:pointer;" /> <span class="f_7 bold" onclick="showDelete(' . $v['PM_ID'] . ');" style="cursor:pointer;">delete</span></div>';
          echo '<br clear="all" />';
          
          echo '<div id="message_delete_' . $v['PM_ID'] . '" style="display:none; padding-top:10px; padding-left:565px;">';
          echo '<form id="message_delete_form_' . $v['PM_ID'] . '" action="">';
          echo '<div>Delete this message?</div>';
          echo '<input type="hidden" name="action" value="messaging.home.act" />';
          echo '<input type="hidden" name="PM_ID" value="' . $v['PM_ID'] . '" />';
          echo '<input type="hidden" name="type" value="sent" />';
          echo '<div style="float:left; padding-right:5px; padding-left:10px; padding-top:5px;"><input type="submit" value="Yes" /></div>';
          echo '<div style="padding-top:5px;"><input type="submit" value="No" onclick="$(\'message_delete_' . $v['PM_ID'] . '\').style.display = \'none\'; return false;" /></div>';
          echo '</form>';
          echo '</div>';
          echo '<br clear="all" />';
          echo '</div>';
        }
          
        echo '<br />';
        echo '</div>';
        
        if($totalPages > 1)
        {
          echo '<div style="float:right; padding-right:30px; padding-top:10px;">';
          echo '<div style="float:left;">' . $firstPage . $prevPage . '</div>';
          echo '<div style="float:left;">' . $pages . '</div>';
          echo '<div>' . $nextPage . $lastPage . '</div>';
          echo '</div>';
        }
        break;
        
      case 'trash':
        echo '<a href="/?action=messaging.home&tab=inbox" title="View sent messages" style="cursor:pointer;"><div style="float:left; border-bottom:1px solid #dddddd; border-left:1px solid #dddddd; border-right:1px solid #dddddd; border-top:1px solid #dddddd; background-color:#eeeeee; width:100px; padding-top:5px; padding-bottom:5px;" class="f_8 bold center">Inbox</div></a>';
        echo '<a href="/?action=messaging.home&&tab=sent" title="View sent messages" style="cursor:pointer;"><div style="float:left; border-bottom:1px solid #dddddd; border-right:1px solid #dddddd; border-top:1px solid #dddddd; background-color:#eeeeee; width:100px; padding-top:5px; padding-bottom:5px;" class="f_8 bold center">Sent</div></a>';
        echo '<div style="float:left; border-right:1px solid #dddddd; border-top:1px solid #dddddd; width:100px; padding-top:5px; padding-bottom:5px;" class="f_8 bold center">Trash</div>';
        echo '<div style="float:left; border-bottom:1px solid #dddddd; width:480px; height:24px;"></div>';
        echo '<br clear="all" />';
        echo '<div style="border-left:1px solid #dddddd; border-right:1px solid #dddddd; border-bottom:1px solid #dddddd; margin-top:-1px; width:783px;">';
         
        echo '<div style="padding-top:10px; margin-left:16px; width:751px;">';
        echo '<div style="float:left; width:50px;">&nbsp;</div>';
        echo '<div style="float:left; width:200px;" class="bold">From</div>';
        echo '<div style="float:left; width:350px;" class="bold">Subject</div>';
        echo '<div class="bold">&nbsp;</div>';
        echo '</div>';
        
        $deleted = $pm->getDeletedMessages($_USER_ID, $limit, $offset);
        
        // paging info
        $totalRows = $GLOBALS['dbh']->found_rows();
        $pagesToDisplay = 6;
        $totalPages = ceil($totalRows/$limit);
      
        $paging =& new CPaging($currentPage, $pagesToDisplay, $totalPages, 'page', '/', $_SERVER['QUERY_STRING']);
        
        $pages = $paging->getPages();
        $firstPage = $paging->getFirstPage('<img src="images/paging_first.gif" border="0" />');
        $lastPage = $paging->getLastPage('<img src="images/paging_last.gif" border="0" />');
        $nextPage = $paging->getNextPage('<img src="images/paging_next.gif" border="0" />');
        $prevPage = $paging->getPrevPage('<img src="images/paging_previous.gif" border="0" />');
    
        foreach($deleted as $k => $v)
        {
          $avatar = $u->pref($v['PM_SENDER_ID'], 'AVATAR');
          if($avatar != '')
          {
            $avatarSrc = PATH_FOTO . $avatar;
          }
          else
          {
            $avatarSrc = 'images/avatar.jpg';
          }
      
          if($k % 2 == 0)
          {
            echo '<div style="margin-top:10px; margin-left:16px; width:751px;">';
          }
          else 
          {
            echo '<div style="margin-top:10px; margin-left:16px; width:751px; background-color:#eeeeee;">';
          }
          
          if($v['PM_STATUS'] == 'New')
          {
            echo '<div style="float:left; margin-left:5px; width:45px; padding-top:10px;"><img src="images/icons/mail_alt_2_16x16.png" class="png" border="0" width="16" height="16" title="new message" /></div>';
          }
          else 
          {
            echo '<div style="float:left; margin-left:5px; width:45px; padding-top:10px;"><img src="images/icons/mail_16x16.png" class="png" border="0" width="16" height="16" title="read message" /></div>';
          }
          echo '<div style="float:left; width:200px;">';
          echo '<div style="float:left; padding-right:5px;"><img src="' . $avatarSrc . '" border="0" width="40" height="40" /></div>';
          echo '<div style="padding-bottom:5px;">' . $v['U_SENDER_USERNAME'] . '</div>';
          echo '<div class="f_7 italic">' . date('M d, Y g:i a', $v['PM_DATECREATED']) . '</div>';
          echo '</div>';
          echo '<div style="float:left; width:350px; padding-top:5px;">' . $v['PM_SUBJECT'] . '</div>';
          echo '<div style="float:left; padding-top:5px;"></div>';
          echo '<br clear="all" />';
          echo '</div>';
        }
          
        echo '<br />';
        echo '</div>';
        
        if($totalPages > 1)
        {
          echo '<div style="float:right; padding-right:30px; padding-top:10px;">';
          echo '<div style="float:left;">' . $firstPage . $prevPage . '</div>';
          echo '<div style="float:left;">' . $pages . '</div>';
          echo '<div>' . $nextPage . $lastPage . '</div>';
          echo '</div>';
        }
        break;
    }
    
    echo '</div>';
  }
  
?>

<script type="text/javascript">
  function showReply(id)
  {
    if($('message_reply_' + id).style.display == 'block')
    {
      $('message_reply_' + id).style.display = 'none';
    }
    else
    {
      $('message_reply_' + id).style.display = 'block';
      $('message_delete_' + id).style.display = 'none';
    }
  }
  
  function showDelete(id)
  {
    if($('message_delete_' + id).style.display == 'block')
    {
      $('message_delete_' + id).style.display = 'none';
    }
    else
    {
      $('message_delete_' + id).style.display = 'block';
      $('message_reply_' + id).style.display = 'none';
    }
  }
</script>

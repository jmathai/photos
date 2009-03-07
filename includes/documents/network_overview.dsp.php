<?php
  $u =& CUser::getInstance();
  
  $friends = $u->getFriends($_USER_ID, 'Confirmed');
  $activity= $u->getFriendActivitiesFull($_USER_ID, 30);
  
  $friendIds = array();
  foreach($friends as $v)
  {
    $friendIds[$v['U_USERNAME']] = 1;
  }
?>

<div style="float:left; width:590px; border-right:solid 1px #eeeeee;">
  <div class="f_10 bold"><img src="/images/icons/network-wireless_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" /> Activity in the past 30 days</div>
  <br/>
  <?php
    if(count($activity) > 0)
    {
      foreach($activity as $k => $v)
      {
        $username   = $v[0]['A_EXTRA_1'];
        $today      = strtotime('today');
        $yesterday  = $today - 86400;
        $time       = $v[0]['A_TIMECREATED'];
        
        if($time >= $today)
        {
          $dayText = 'today';
        }
        else
        if($time >= $yesterday)
        {
          $dayText = 'yesterday';
        }
        else
        {
          $weeksAgo = floor((NOW - $time) / 86400 / 7);
          if($weeksAgo == 0)
          {
            $dayText = 'on ' . date('l', $time);
          }
          else
          {
            $dayText = numberWord($weeksAgo) . ' week' . ($weeksAgo > 1 ? 's' : '') . ' ago';
          }
        }
        
        $plural = (count($v) > 1 ? 's' : '');
        
        $underHtml = '';
        switch($v[0]['A_TYPE'])
        {
          case 'newPhoto':
            $message = 'uploaded ' . count($v) . ' new photo' . $plural . ' ' . $dayText;
            $pCount = 1;
            foreach($v as $photo)
            {
              $underHtml .= '<a href="/handler/photo/' . $photo['A_EXTRA_3'] . '/"><img src="' . PATH_FOTO . $photo['A_EXTRA_2'] . '" hspace="10" vspace="5" border="0" /></a>';
              $pCount++;
              
              if($pCount >= 6)
              {
                break;
              }
            }
            break;
          case 'newSlideshow':
            $message = 'created ' . count($v) . ' new slideshow' . $plural . ' ' . $dayText;
            $pCount = 1;
            foreach($v as $slideshow)
            {
              $underHtml .= '<a href="/slideshow?' . $slideshow['A_EXTRA_3'] . '/"><img src="' . PATH_FOTO . $slideshow['A_EXTRA_2'] . '" hspace="10" vspace="5" border="0" /></a>';
              $pCount++;
              
              if($pCount >= 2)
              {
                break;
              }
            }
            break;
          case 'newVideo':
            $message = 'uploaded ' . count($v) . ' new video' . $plural . ' ' . $dayText;
            $pCount = 1;
            foreach($v as $video)
            {
              $underHtml .= '<a href="/video?' . $video['A_EXTRA_3'] . '/"><img src="' . PATH_FOTO . $video['A_EXTRA_2'] . '" hspace="10" vspace="5" border="0" /></a>';
              $pCount++;
              
              if($pCount >= 2)
              {
                break;
              }
            }
            break;
          case 'newComment':
            $message = 'made ' . count($v) . ' new comment' . $plural . ' ' . $dayText;
            foreach($v as $comment)
            {
              $commentOn = null;
              switch($comment['A_EXTRA_4'])
              {
                case 'blog':
                  $commentOn = 'blog post';
                  $link = '/users/' . $comment['A_EXTRA_2'] . '/blog/entry/' . $comment['A_EXTRA_3'] . '/#comment' . $comment['A_ELEMENT'];
                  break;
                case 'flix':
                  $commentOn = 'slideshow';
                  $link = '/handler/slideshow/' . $comment['A_EXTRA_3'] . '/#comment' . $comment['A_ELEMENT'];
                  break;
                case 'foto':
                  $commentOn = 'photo';
                  $link = '/handler/photo/' . $comment['A_EXTRA_3'] . '/#comment' . $comment['A_ELEMENT'];
                  break;
              }
              
              $underHtml .= '<div class="bullet"><a href="' . $link . '">' . $comment['A_EXTRA_2'] . '\'s ' . $commentOn . '</a>';
              if(!isset($friendIds[$comment['A_EXTRA_2']]) && $_FF_SESSION->value('username') != $comment['A_EXTRA_2'])
              {
                $randId = 'addFriend_' . rand(0, 1000);
                $underHtml .= '&nbsp; (<a href="javascript:void(0);" onclick="inviteFriendForm(\'' . $comment['A_EXTRA_2'] . '\', $(\'' . $randId . '\'));" id="' . $randId . '" class="plain"><img src="/images/icons/user_add_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" /> add ' . $comment['A_EXTRA_2'] . '</a>)';
              }
              $underHtml .= '</div>';
            }
            break;
          case 'newBlogPost':
            $message = 'made ' . count($v) . ' new blog post' . $plural . ' ' . $dayText;
            foreach($v as $post)
            {
              $link = '/users/' . $post['A_EXTRA_1'] . '/blog/entry/' . $post['A_ELEMENT'] . '/';
              $underHtml .= '<div class="bullet"><a href="' . $link . '">' . $post['A_EXTRA_1'] . '\'s blog post (' . $post['A_EXTRA_2'] . ')</a></div>';
            }
            break;
          default:
            continue 2;
            break;
        }
        
        $avatarSrc = $v[0]['A_AVATAR'] != '' ? PATH_FOTO . $v[0]['A_AVATAR'] : '/images/avatar.jpg';
        
        echo '<br/>
              <div>
                ' . linkUserPage($username, '<img src="' . $avatarSrc . '" width="30" height="30" hspace="10" border="0" align="absmiddle" />') . linkUserPage($username) . ' ' . $message . '.<br clear="all" />
              </div>
              <div style="margin-left:50px;">' . $underHtml . '</div>
              <br/>
              <div class="line_lite"></div>';
      }
    }
    else
    {
      echo '<div class="bold italic">No recent activity</div>';
    }
  ?>
</div>

<div style="float:left; width:150px; margin-left:10px;">
  <div class="f_10 bold"><img src="/images/icons/user_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" /> Friends</div>
  <br/>
  <?php
    if(count($friends) > 0)
    {
      foreach($friends as $v)
      {
        $avatarSrc = $v['U_AVATAR'] != '' ? PATH_FOTO . $v['U_AVATAR'] : '/images/avatar.jpg';
        echo '<div>
                ' . linkUserPage($v['U_USERNAME'], '<img src="' . $avatarSrc . '" width="30" height="30" hspace="10" vspace="2" border="0" align="absmiddle" />') . $v['U_USERNAME'] . '<br clear="all" />
                <a href="javascript:void(0);" onclick="messageForm(\'' . $v['U_USERNAME'] . '\', this);" title="send ' . $v['U_USERNAME'] . ' a message"><img src="/images/icons/chat_bubble_alt_2_16x16.png" class="png" width="16" height="16" border="0" hspace="5" vspace="5" /></a>
                ' . linkUserPage($v['U_USERNAME'], '<img src="/images/icons/website_16x16.png" class="png" width="16" height="16" border="0" hspace="5" vspace="5" alt="visit ' . $v['U_USERNAME'] . '\'s personal page" />', false) . '
                <a href="javascript:alert(\'Cannot delete friends just yet.\nFor more information go to my delicious flickr face.\');" title="remove ' . $v['U_USERNAME'] . ' as a friend"><img src="/images/icons/delete_16x16.png" class="png" width="16" height="16" border="0" hspace="5" vspace="5" /></a>
              </div>
              <br/>';
      }
    }
    else
    {
      echo '<div class="bold italic">No friends</div>';
    }
  ?>
</div>
<br clear="all" />
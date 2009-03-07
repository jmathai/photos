<?php
  $u =& CUser::getInstance();
  $prefs = $u->prefs($_USER_ID);
  $activity = $u->getFriendActivitiesFull($_USER_ID, 14);
  $myFriends  = $u->getFriends($_USER_ID, 'Confirmed');
  
  $friendIds = array();
  foreach($myFriends as $v)
  {
    $friendIds[$v['U_USERNAME']] = 1;
  }
?>

<div style="width:750px; margin:auto;">
  <div style="width:500px; padding-right:10px; float:left;">
    <div style="padding-top:15px; border-right:1px solid #dddddd;">
      <div id="userTip"></div>
      <!--
      <script>
        var t = new Tips();
        t.displayTip($('userTip'));
      </script>
      -->
      <div>
        <?php
          switch($_GET['newAccount'])
          {
            case '1': // created an account
              echo '
                    <div class="f_11 bold center" style="padding-bottom:10px;"><img src="images/icons/smiley_24x24.png" class="png" width="24" height="24" border="0" hspace="5" align="absmiddle" />Your account was created successfully!</div>
                    ';
              break;
            case '2': // paid for an account
              $amount   = floatval(base64_decode($_GET['____']));
              $tracking = base64_decode($_GET['___']);
              echo '
                    <div class="f_11 bold center" style="padding-bottom:10px;"><img src="images/icons/smiley_24x24.png" class="png" width="24" height="24" border="0" hspace="5" align="absmiddle" />Thank you for purchasing an account!</div>
                    
                    <!-- Google Code for purchase Conversion Page -->
                    <script language="JavaScript" type="text/javascript">
                    <!--
                    var google_conversion_id = 1061541346;
                    var google_conversion_language = "en_US";
                    var google_conversion_format = "1";
                    var google_conversion_color = "FFFFFF";
                    if (1) {
                      var google_conversion_value = 1;
                    }
                    var google_conversion_label = "purchase";
                    //-->
                    </script>
                    <script language="JavaScript" src="http://www.googleadservices.com/pagead/conversion.js">
                    </script>
                    <noscript>
                    <img height=1 width=1 border=0 src="http://www.googleadservices.com/pagead/conversion/1061541346/imp.gif?value=' . $amount . '&label=purchase&script=0">
                    </noscript>
                    
                    <!-- shareasale conversion tracking code -->
                    <img src="https://shareasale.com/sale.cfm?amount=' . $amount . '&tracking=' . $tracking . '&transtype=SALE&merchantID=12918" width="1" height="1" />
                    ';
              break;
            default: // trial user
              if($_FF_SESSION->value('is_trial') == USER_IS_TRIAL)
              {
                echo '
                  <div style="padding-bottom:15px;">
                    <img src="images/icons/user_24x24.png" class="png" width="24" height="24" border="0" align="absmiddle" />&nbsp;&nbsp;<a href="https://' . FF_SERVER_NAME . '/?action=home.registration_form_b2" class="f_9 bold">Love Photagious? Purchase an account!</a>
                  </div>
                ';
              }
              break;
          }
          
          echo trackSignup($_USER_ID);
        ?>
      
        <div style="padding-bottom:15px;">
          <a href="javascript:findUploader();" title="upload your photos"><img src="images/start_uploading_fotos.gif" width="280" height="30" vspace="10" border="0" /></a>
        </div>
      </div>
      
      <div style="padding-bottom:5px;">
        <?php
          $cntActivity = count($activity);
          if($cntActivity > 0)
          {
            $count = 1;
            echo '<div class="f_11 bold" style="margin-bottom:4px;">Recent activity from your friends. (<a href="/users/' . $_FF_SESSION->value('username') . '/friends/">View more activity</a>)</div>';
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
                    $underHtml .= '<a href="/handler/photo/' . $photo['A_EXTRA_3'] . '/"><img src="' . PATH_FOTO . $photo['A_EXTRA_2'] . '" width="25" height="25" hspace="4" vspace="4" border="0" /></a>';
                    $pCount++;
                    
                    if($pCount >= 14)
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
                    $underHtml .= '<a href="/slideshow?' . $slideshow['A_EXTRA_3'] . '/"><img src="' . PATH_FOTO . $slideshow['A_EXTRA_2'] . '" width="25" height="25" hspace="4" vspace="4" border="0" /></a>';
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
                    $underHtml .= '<a href="/video?' . $video['A_EXTRA_3'] . '/"><img src="' . PATH_FOTO . $video['A_EXTRA_2'] . '" width="25" height="25" hspace="4" vspace="4" border="0" /></a>';
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
                case 'newFriend':
                  $message = 'made ' . count($v) . ' new friend' . $plural . ' ' . $dayText;
                  foreach($v as $friend)
                  {
                    $displayName = $friend['A_EXTRA_2'] == $_FF_SESSION->value('username') ? 'you' : $friend['A_EXTRA_2'];
                    $link = '/users/' . $friend['A_EXTRA_2'] . '/';
                    $underHtml .= '<div class="bullet"><a href="' . $link . '">' . $displayName . '</a>';
                    if($logged_in === true)
                    {
                      if(!isset($friendIds[$friend['A_EXTRA_2']]) && $_FF_SESSION->value('username') != $friend['A_EXTRA_2'])
                      {
                        $randId = 'addFriend_' . rand(0, 1000);
                        $underHtml .= '&nbsp; (<a href="javascript:void(0);" onclick="inviteFriendForm(\'' . $friend['A_EXTRA_2'] . '\', $(\'' . $randId . '\'));" id="' . $randId . '" class="plain"><img src="/images/icons/user_add_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" /> add ' . $friend['A_EXTRA_2'] . '</a>)';
                      }
                    }
                    $underHtml .= '</div>';
                  }
                  break;
                default:
                  continue 2;
                  break;
              }
              
              $avatarSrc = $v[0]['A_AVATAR'] != '' ? PATH_FOTO . $v[0]['A_AVATAR'] : '/images/avatar.jpg';
              
              echo '<div>
                      ' . linkUserPage($username, '<img src="' . $avatarSrc . '" width="30" height="30" hspace="10" border="0" align="absmiddle" />') . linkUserPage($username) . ' ' . $message . '.<br clear="all" />
                    </div>
                    <div style="margin-left:50px;">' . $underHtml . '</div>';
              
              if($count < 3 && $count != $cntActivity)
              {
                echo '<div style="height:5px;">&nbsp;</div>
                      <div class="line_lite"></div>
                      <div style="height:5px;">&nbsp;</div>';
              }
              else
              {
                echo '<div style="height:10px;">&nbsp;</div>';
                break;
              }
              
              $count++;
            }
            
            echo '<div class="line_lite"></div>';
          }
        ?>
      </div>
      
      <div class="f_11 bold" style="padding-bottom:5px;">The Photagious Blog</div>
    
      <div>
        <?php
          echo '<!-- pull feed in from /blogs/atom.xml' . ' -->';
          $xml = simplexml_load_file(PATH_HOMEROOT . '/blogs/atom.xml');
          
          $details = $snippit = '';
          
          $i = 0;
          foreach($xml->entry as $v)
          {
            $postTime = strtotime($v->published);
            $snippit .= '<div class="bullet"><a href="#blog_' . $postTime . '">' . $v->title . '</a></div>';
            $details .= '<div style="height:10px;"></div>
                  <div class="bold" style="padding-bottom:3px;"><a name="blog_' . $postTime . '"></a>' . $v->title . '</div>
                  <div class="italic f_7">Posted by: <a href="http://www.fotoflix.com/blogs/' . strtolower($v->author->name) . '/" target="_blank">' . $v->author->name . '</a> on ' . date('l, F d, Y @ h:i a', $postTime) . '</div>
                  <div style="align:justify;">' . $v->content . '</div>
                  <div style="height:10px;"></div>
                  <div class="line_lite"></div>';
            
            if($i == 4) // display 5
            {
              break;
            }
            
            $i++;
          }
          
          echo $details;
        ?>
      </div>
    </div>
  </div>
  
  <div style="width:220px; padding-left:10px; float:left; text-align:left;">
    <div style="padding-bottom:10px;">
      <div class="bold f_10" style="padding-top:20px; padding-bottom:5px;">Helpful Links</div>
      <?php
        if($prefs['HAS_UPLOADED'] == 1) // user has uploaded photos
        {
          echo '<div class="bullet"><a href="/?action=home.samples&subaction=editPhoto">How do edit my photos?</a></div>
                <div class="bullet"><a href="/?action=home.samples&subaction=network">How do I let others know when I add photos?</a></div>';
          if($prefs['HAS_SLIDESHOW'] == 1) // user has created a slideshow
          {
            echo '
                  <div class="bullet"><a href="/?action=home.samples&subaction=toolbar">How do I customize a slideshow?</a></div>
                  <div class="bullet"><a href="/?action=home.samples&subaction=all_themes">View all our slideshow themes?</a></div>';
          }
          else // user has photos but no slideshow
          {
            echo '<div class="bullet"><a href="/?action=home.samples&subaction=createSlideshow">How do I make a slideshow?</a></div>
                  <div class="bullet"><a href="/?action=home.samples&subaction=demoSlideshow">View a demo of our slideshow</a></div>';
          }
        }
        else // user has not uploaded photos
        {
          echo '<div class="bullet"><a href="/?action=fotobox.upload_installer">Begin uploading photos,</a></div>
                <div class="bullet"><a href="/?action=home.samples&subaction=network">How do I let others know when I add photos?</a></div>';
        }
      ?>
    </div>
    
    <div style="padding-bottom:40px;">
      <div class="bold f_10" style="padding-top:20px; padding-bottom:5px;">Blog Entries</div>
      <?php echo $snippit; ?>
      <br/>
      <div>More news on <a href="/blog/">our blog</a>!</div>
    </div>
    
    <!--
    <div>
      <a href="/?action=home.member" title="view the fotoflix.com home page">View the home page</a>
    </div>
    -->
    <!--
    <div style="padding-top:25px;">
      <div style="color:navy;" class="f_10 bold">Share your slideshows and photos in our</div>
      <div><a href="/?action=board.main" title="share your slideshows and photos"><img src="images/community_new.gif" width="163" height="30" border="0" /></a></div>
    </div>
    -->
  </div>
  
  <br clear="left" />
</div>

<div style="padding-top:135px;"></div>
<?php
  if(strncmp($action, 'my.', 3) == 0)
  {
    /* DO NOT REMOVE $userData or $pageData 
       ELSE DO A SEARCH FOR ~~userData~~ and/or ~~pageData~~ */
    include_once PATH_DOCROOT . '/my_check.dsp.php';
    $isMyPage = true;
    //$swf_src  .='&my_page_name=' . urlencode($displayName);
  }
  else
  if(strncmp($action, 'group.', 6) == 0)
  {
    if($logged_in === true)
    {
      include_once PATH_DOCROOT . '/group_check.dsp.php';
      $isGroup = true;
      $group_id = $_GET['group_id'];
    }
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">

<head>

<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<meta http-equiv="imagetoolbar" content="no">
<?php
  switch($action)
  {
    case 'home.visitor':
      echo '<meta name="description" content=" Photagious - Photo slideshow software by photo enthusiasts for photo enthusiasts.  Create flash slideshows quickly and easily, store and manage photos and share you photos online.">
            <meta name="keywords" content="photo, slideshow, flash, create, software">';
      $pageTitle = 'Photagious - Online Photo Slideshow Software - Create Flash Slideshows';
      break;
    case 'home.samples':
      switch($_GET['subaction'])
      {
        case 'all_themes':
          echo '<meta name="description" content="Slideshow Templates - Photagious presents slideshow templates with our slideshow picture viewer by photo enthusiasts for photo enthusiasts.  Create, manange, store and share your photos online.">
                <meta name="keywords" content="slideshow, templates, picture, viewer, slideshows">';
          $pageTitle = 'Photagious Slideshow Templates - Slideshow Picture Viewer';
          break;
        case 'storage':
          echo '<meta name="description" content="Online Photo Storage - Host and share your photos at Photagious.com, a service by photo enthusiasts for photo enthusiasts.  We offer unlimited storage and slideshow creation for sharing your photos online.">
                <meta name="keywords" content="photo, storage, hosting, sharing, share">';
          $pageTitle = 'Unlimited Online Photo Storage - Photo Hosting and Sharing at Photagious';
          break;
        case 'printing':
          echo '<meta name="description" content="Photo Printing - Print your photos and create unique personalized photo gifts at Photagious.com.  We offer complete photo services for photo enthusiasts by photo enthusiasts.">
                <meta name="keywords" content="photo, printing, personalized, gifts">';
          $pageTitle = 'Photo Printing and Personalized Photo Gifts at Photagious';
          break;
        case 'wedding':
          echo '<meta name="description" content="Wedding Slideshow - Create your wedding slideshow from your wedding photos at Photagious.com.  Create flash slideshows quickly and easily, store and manage photos and share your photos online.">
                <meta name="keywords" content="wedding, slideshow, photo, slideshows, pictures">';
          $pageTitle = 'Wedding Slideshow - Wedding Theme for Photo Slideshows at Photagious';
          break;
        default:
          echo '<meta name="description" content="Photagious - Photo slideshow software by photo enthusiasts for photo enthusiasts.  Create flash slideshows quickly and easily, store and manage photos and share your photos online.">
                <meta name="keywords" content="photo, slideshow, flash, create, software">';
          $pageTitle = 'Photagious - Online Photo Slideshow Software - Create Flash Slideshows';
          break;
      }
      break;
    default:
      echo '<meta name="description" content="Photagious - Photo slideshow software by photo enthusiasts for photo enthusiasts.  Create flash slideshows quickly and easily, store and manage photos and share your photos online.">
            <meta name="keywords" content="photo, slideshow, flash, create, software">';
      $pageTitle = 'Photagious - Online Photo Slideshow Software - Create Flash Slideshows';
      break;
  }
?>

<base href="<?php echo 'http'; if($_SERVER['SERVER_PORT'] == '443'){ echo 's'; } echo '://' . FF_SERVER_NAME . '/'; ?>">

<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="stylesheet" type="text/css" href="/css/layout.v12.css">
<?php
  if($isGroup === true)
  {
    include_once PATH_CLASS . '/CGroup.php';
    $g = &CGroup::getInstance();
    $siteColors = $g->pref($group_id, 'SITE_COLORS');
    
    echo '<link rel="stylesheet" type="text/css" href="/css/themes/default.css?' . $siteColors . '">
          <title>' . $groupName . '\'s Group - Share your photos and create slideshows on Photagious</title>
          <script type="text/javascript" src="/js/group.js?v=1.0"></script>';
  }
  else
  if($isMyPage === true)
  {
    $pageTitle =  $displayDescription != '' ? htmlspecialchars($displayDescription) : $displayName;
    $pageTitle .= ' / ' . ucfirst($subaction);
    if($subaction == 'tags')
    {
      $pageTitle .= ' / ' . $options[0];
    }
    else
    if(isset($tags))
    {
      $pageTitle .= ' / Tags: ' . $tags;
    }
    
    echo '<link rel="stylesheet" type="text/css" href="/css/themes/default.css?my_default">
          <title>' . $pageTitle . ' - Photagious - Online Photo Slideshow Software - Create Flash Slideshows</title>
          <script type="text/javascript" src="/js/photopage.v12.js"></script>';
    include_once PATH_DOCROOT . '/user_data_js.dsp.php';
  }
  else 
  {
    echo '
          <link rel="stylesheet" type="text/css" href="/css/themes/default.css">
          <title>' . $pageTitle . '</title>
          ';
    
    if($logged_in === true)
    {
      include_once PATH_DOCROOT . '/user_data_js.dsp.php';
    }
  }
?>

<script type="text/javascript" src="/js/prototype/prototype.v11.js"></script>
<script type="text/javascript" src="/js/prototype/moo.fx.v10.js"></script>
<script type="text/javascript" src="/js/javascript.v12.js"></script>
<script type="text/javascript" src="/js/http.v10.js"></script>

<?php
  if(FF_MODE == 'live')
  {
    if($_SERVER['SERVER_PORT'] == '80')
    {
      echo '<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
            <script type="text/javascript">
              _uacct = "UA-88708-5";
              urchinTracker();
            </script>';
    }
    else
    {
      echo '<script src="https://ssl.google-analytics.com/urchin.js" type="text/javascript"></script>
            <script type="text/javascript">
              _uacct = "UA-88708-5";
              urchinTracker();
            </script>';
    }
  }
  
  /*
    Determine any custom JavaScript which needs to be included or applied to this page
  */
  $scriptaculous  = false;
  $lightbox       = false;
  switch($action)
  {
    case 'account.billing_update_form':
      echo '<script type="text/javascript" src="/js/registration.v10.js"></script>';
      break;
    case 'board.new_topic':
      $scriptaculous = 'effects,controls';
      break;
    case 'board.reply':
      $scriptaculous = 'effects,controls';
      break;
    case 'flix.html_slideshow':
      echo '<script type="text/javascript" src="/js/html_slideshow.v11.js"></script>';
      break;
    case 'fotobox.calendar':
      echo '<script type="text/javascript" src="/js/photos.v11.js"></script>
            <script type="text/javascript" src="/js/calendar.v11.js"></script>';
      break;
    case 'fotobox.fotobox_main':
      //echo '<script type="text/javascript" src="/js/tips.js?v=1.0"></script>';
      break;
    case 'fotobox.fotobox_myfotos':
    case 'fotobox.fotobox_myfotos_create_flix':
    case 'fotobox.stats':
    case 'fotobox.view_all_tags':
      $lightbox = true;
      $scriptaculous = 'effects,controls';
      echo '<script type="text/javascript" src="/js/toolbox.v11.js"></script>
            <script type="text/javascript" src="/js/photos.v13.js"></script>';
      break;
    case 'fotobox.upload_flash':
    case 'fotobox.upload_form':
    case 'fotobox.upload_installer':
      echo '<script type="text/javascript" src="/js/tips.v11.js"></script>
            <script type="text/javascript" src="/js/swfupload.v1.js"></script>';
      break;
    case 'fotobox.upload_form_html':
      $scriptaculous = 'effects,controls';
      break;
    case 'fotobox.upload_form':
      echo '<script type="text/javascript" src="/js/swfupload.js"></script>';
      $scriptaculous = 'effects,controls';
      break;
    case 'fotobox.upload_form_compat':
      $scriptaculous = 'effects,controls';
      break;
    case 'fotobox.view_all_tags':
      $scriptaculous = 'effects,controls,dragdrop';
      break;
    case 'flix.flix_list':
    case 'flix.manage':
      $lightbox = true;
      $scriptaculous = 'effects,controls';
      break;
    case 'home.registration_form_b':
      echo '<script type="text/javascript" src="/js/registration.v10.js"></script>';
      $scriptaculous = 'effects';
      break;
    case 'manage.accounts':
      echo '<script type="text/javascript" src="/js/registration.v10.js"></script>';
      break;
    case 'messaging.home':
    case 'messaging.message':
      echo '<script type="text/javascript" src="/js/privatemessage.v10.js"></script>';
      break;
    case 'messaging.inbox':
      echo '<script type="text/javascript" src="/js/messaging.v10.js"></script>';
      break;
    case 'my.page':
      $lightbox = true;
      $scriptaculous = 'effects,controls';
      if(($subaction == 'profile' || $subaction == 'blog') && $_USER_ID == $user_id)
      {
        echo '<script type="text/javascript" src="/js/fck/fckeditor.v10.js"></script>';
      }
      break;
    case 'network.overview':
      echo '<script type="text/javascript" src="/js/messaging.v10.js"></script>';
      break;
    case 'video.upload_form':
      $scriptaculous = 'effects,controls';
      break;
  }
  
  if($scriptaculous !== false)
  {
    echo '<script type="text/javascript" src="/js/prototype/scriptaculous.js?v=1.0&load=' . $scriptaculous . '"></script>';
  }
  
  if($lightbox !== false)
  {
      echo '<script type="text/javascript" src="/js/lightbox_gw.v10.js"></script>
            <!-- lbg css -->
            <link rel="stylesheet" type="text/css" href="/css/lightbox_gw.v10.css">';
  }
?>
<script language="javascript" type="text/javascript">
  var _USER_ID = <?php echo intval(isset($_USER_ID) ? $_USER_ID : 0); ?>;
  var _USER_PERM = <?php echo intval($_FF_SESSION->value('account_perm')); ?>;
  var _FF_STATIC_URL = '<?php echo ( $_SERVER['SERVER_PORT'] == 80 ? FF_STATIC_URL : ('https://' . FF_SERVER_NAME) ); ?>';
</script>
</head>

<body>
  <a name="top"></a>
  <div id="site">
  <?php
    if(isset($isGroup)) // group header
    {
      include_once PATH_CLASS . '/CGroup.php';
      $g = &CGroup::getInstance();
      
      $moderator = $g->isModerator($_USER_ID, $group_id);
  ?>
      <div>
        <div id="groupToolbar">
          <div>
            <div style="float:left;">
              <a href="/"><img src="images/logo_top_black.gif" width="105" height="20" hspace="25" vspace="5" border="0" /></a>
            </div>
            <div style="float:right; margin-top:7px;" class="f_white">
              <div style="float:left;">Logged in as <span class="f_red"><?php echo $_FF_SESSION->value('username'); ?></span> | My Account</div>
              <?php
                if($moderator == true)
                {
                  echo '<div style="float:left; margin-left:20px; margin-right:25px;" class="f_red"><img src="images/orangeDot.gif" width="8" height="8" border="0" align="absmiddle" /> Group Administrator</div>';
                }
              ?>
            </div>
            <br clear="all" />
          </div>
          <div>
            <div style="float:right; margin-top:2px;">
              <?php
                if($moderator == true)
                {
              ?>
                  <div style="float:left; margin-left:15px;">
                    <a href="/?action=group.send_message&group_id=<?php echo $group_id ?>" class="f_white plain"><img src="images/groupToolbarMessage.gif" width="13" height="11" border="0" align="absmiddle" /> Send Message</a>
                  </div>
                  <div style="float:left; margin-left:15px;">
                    <a href="/?action=group.settings&group_id=<?php echo $group_id; ?>" class="f_white plain"><img src="images/groupToolbarSettings.gif" width="13" height="14" border="0" align="absmiddle" /> Edit Group Settings</a>
                  </div>
                  <div style="float:left; margin-left:15px;">
                    <a href="/?action=group.approve&group_id=<?php echo $group_id; ?>" class="f_white plain"><img src="images/groupToolbarContent.gif" width="16" height="15" border="0" align="absmiddle" /> Approve Content</a>
                  </div>
                  <div style="float:left; margin-left:15px;">
                    <a href="/?action=group.member_request&group_id=<?php echo $group_id; ?>" class="f_white plain"><img src="images/groupToolbarMember.gif" width="12" height="15" border="0" align="absmiddle" /> Member Requests</a>
                  </div>
              <?php
                }
              ?>
              <div style="float:left; margin-left:15px; margin-right:25px;">
                <a href="/xml_result?action=share_with_group&group_id=<?php echo $group_id; ?>" class="lbOn f_white plain"><img src="images/groupToolbarShare.gif" width="14" height="14" border="0" align="absmiddle" /> Share with Group</a>
              </div>
            </div>
            <br clear="all" />
          </div>
        </div>
        <div id="groupBanner">
          <?php
            $tempPath = $g->pref($group_id, 'HEADER_PHOTO');
            if($tempPath == '')
            {
              $headerPath = 'images/groupToolbarSampleLogo.gif';
            }
            else 
            {
              $headerPath = $tempPath;
              $headerKey = $g->pref($group_id, 'HEADER_KEY');
              $headerPath = dynamicImage($headerPath, $headerKey, 95, 95);
            }
            
            $groupData = $g->groupData($group_id);
            $headerTitle = $groupData['G_NAME'];
            $headerDescription = $groupData['G_DESC'];
          ?>
          <div style="float:left;"><img id="headerPhoto" src="<?php echo $headerPath; ?>" width="95" height="95" vspace="10" hspace="25" border="0" style="border:solid 3px white;" /></div>
          <div style="float:left; margin-left:40px; margin-top:40px;" class="f_white">
            <div id="groupHeaderTitle" class="f_16 bold"><?php echo $headerTitle; ?></div>
            <div id="groupHeaderDescription" class="f_12 bold"><?php echo $headerDescription; ?></div>
          </div>
        </div>
      </div>
      <!-- toolbar functions -->
      <script type="text/javascript">Event.observe(window, "load", initializeLB, false); </script>
  <?php
    }
    else
    if(isset($isMyPage)) // fotopage header
    {
  ?>
      <div>
        <div id="myToolbar">
          <div>
            <div style="float:left; height:30px;">
              <div style="float:left; margin-right:75px;"><a href="/"><img src="images/photagious_black_small.gif" width="94" height="21" hspace="25" vspace="5" border="0" /></a></div>
              <div style="margin-top:7px;">
              <?php
                if($parts[3] != '')
                {
                  echo '<a href="/users/' . $username . '/" class="f_white bold plain" style="text-transform:capitalize;">' . $username . '\'s Page</a>';
                }
                
                foreach($parts as $v)
                {
                  switch($v)
                  {
                    case 'blog':
                      if($options[0] == '')
                      {
                        echo ' / <span class="f_white bold">Blog</span>';
                      }
                      else
                      if($options[0] == 'entry')
                      {
                        echo ' / <a href="/users/' . $username . '/blog/" class="f_white bold plain">Blog</a> / <span class="f_white bold">Entry</span>';
                      }
                      break;
                    case 'photos':
                      echo ' / <span class="f_white bold">Photos</span>';
                      break;
                    case 'photo':
                    case 'photo-large':
                      echo ' / <a href="/users/' . $username . '/photos/" class="f_white bold plain">Photos</a> / <span class="f_white bold">Photo Detail</span>';
                      break;
                    case 'slideshows':
                      echo ' / <span class="f_white bold">Slideshows</span>';
                      break;
                    case 'videos':
                      echo ' / <span class="f_white bold">Videos</span>';
                      break;
                    case 'profile':
                      echo ' / <span class="f_white bold">Profile</span>';
                      break;
                    case 'settings':
                      echo ' / <span class="f_white bold">Edit Settings</span>';
                      break;
                    case 'tags':
                      if($options[0] == '')
                      {
                        echo ' / <span class="f_white bold">View all tags</span>';
                      }
                      else
                      {
                        echo ' / <a href="/users/' . $username . '/tags/" class="f_white bold plain">View all tags</a> / <span class="f_white bold">' . $options[0] . '</span>';
                      }
                      break;
                  }
                }
              ?>
              </div>
              <!--<div style="float:left; margin:7px 0px 0px 20px;"><a href="javascript:alert('This link does not work yet.');" class="f_white plain">view other personal pages &gt;&gt;</a></div>-->
            </div>
            <div style="float:right; margin-top:7px; margin-right:5px;" class="f_white">
              <?php
                if($logged_in === false)
                {
                  echo '<div><a href="https://' . FF_SERVER_NAME . '/?action=home.registration_form_b" class="f_white plain">sign up for a free trial</a>&nbsp;|&nbsp;<a href="/?action=home.login_form&redirect=' . urlencode($_SERVER['REQUEST_URI']) . '" class="f_white plain">login</a></div>';
                }
                else
                {
                  include_once PATH_CLASS . '/CToolbox.php';
                  $tb =& CToolbox::getInstance();
                  $cartItems = $tb->get($_FF_SESSION->value('sess_hash'));
                  $cartCnt = count($cartItems);
                  $cartStr = '';
                  if($cartCnt > 0)
                  {
                    $cartStr = '<a href="' . QOOP_LINK . '&user_token=' . $_FF_SESSION->value('sess_hash') . '" class="f_white plain"><img src="images/icons/shopping_chart_alt_2_16.png" width="16" height="16" hspace="3" border="0" align="absmiddle" /> Your Cart (' . $cartCnt . ')</a> | ';
                  }
                  echo '<div>' . $cartStr . ' logged in as <span class="f_red">' . $_FF_SESSION->value('username') . '</span> | <a href="/?action=member.logout.act" class="plain f_white">logout</a></div>';
                }
              ?>
            </div>
            <br clear="all" />
          </div>
          <div>
            <?php
              if($_USER_ID == $user_id)
              {
            ?>
                <div style="float:right; margin-top:4px; margin-right:25px;">
                  <?php
                    switch($subaction)
                    {
                      case 'home':
                      case 'tags':
                      case 'profile':
                      case 'photos':
                      case 'slideshows':
                      case 'settings':
                        echo '
                          <div style="float:left; margin-left:15px;">
                            <a href="/users/' . $username . '/settings/" class="f_white plain"><img src="images/icons/edit_alt_2_16x16.png" class="png" border="0" width="16" height="16" align="absmiddle" /> Edit Page Settings</a>
                          </div>
                          <div style="float:left; margin-left:15px;">
                            <a href="/xml_result?action=fotopage_list_fotos&subaction=' . $subaction . '" class="lbOn f_white plain"><img src="images/icons/add_alt_2_16x16.png" class="png" border="0" width="16" height="16" align="absmiddle" /> Add Photos and Slideshows</a>
                          </div>
                        ';
                        break;
                      case 'photo':
                        echo '
                          <div style="float:left; margin-left:15px;">
                            <a href="javascript:void(0);" onclick="photoSettingsEffect.toggle();" class="f_white plain"><img src="images/icons/edit_16x16.png" class="png" border="0" width="16" height="16" align="absmiddle" /> Edit this photo\'s settings</a>
                            <br/>
                            <div id="photoSettingDiv" style="position:absolute; width:315px; background-color:#ffffff;">
                              <!-- set via js in my_foto.dsp.php -->
                            </div>
                            <script type="text/javascript"> var photoSettingsEffect = new fx.Height("photoSettingDiv"); photoSettingsEffect.hide(); </script>
                          </div>
                          <div style="float:left; margin-left:15px;">
                            <a id="removeLink" href="javascript:void(0);" onclick="removePhotoFromPage(' . $options[0] . ');" class="f_white plain"><img src="images/icons/remove_alt_2_16x16.png" class="png" border="0" width="16" height="16" align="absmiddle" /> Remove this photo from My Page</a>
                            <br/>
                            <div id="removeLinkConfirm" style="position:absolute; width:212px; background-color:#ffffff;">
                              <div id="removeLinkConfirmText" class="center bold"></div>
                            </div>
                          </div>
                        ';
                    }
                  ?>
                  <script type="text/javascript"> var pageOpts = {"OFFSET":0,"LIMIT":12}; </script>
                </div>
                <br clear="all" />
            <?php
              }
              else
              {
                echo '<div><br clear="all" /></div>';
              }
            ?>
          </div>
        </div>
        <div id="groupBanner">
          <div style="float:left;">
            <?php
              include_once PATH_CLASS . '/CUser.php';
              $us =& CUser::getInstance();
              $avatar = $us->pref($user_id, 'AVATAR');
              if($_USER_ID != $user_id || $_USER_ID == 0)
              {
                $avatarSrc = $avatar !== false ? dynamicImage($avatar, $us->pref($user_id, 'AVATAR_KEY'), PAGE_AVATAR_WIDTH, PAGE_AVATAR_HEIGHT) : 'images/avatar_none.gif';
                echo '<div><img src="' . $avatarSrc . '" width="' . PAGE_AVATAR_WIDTH . '" height="' . PAGE_AVATAR_HEIGHT . '" hspace="25"border="0" style="margin-top:10px; border:solid 3px white;" /></div>';
              }
              else
              {
                $avatarSrc = $avatar !== false ? dynamicImage($avatar, $us->pref($user_id, 'AVATAR_KEY'), PAGE_AVATAR_WIDTH, PAGE_AVATAR_HEIGHT) : 'images/avatar_select.gif';
                echo '<a href="javascript:void(0);" onclick="changeAvatar(\'\');" title="click to change avatar">
                        <div style="margin-left:25px;"><img id="profileAvatar" src="' . $avatarSrc . '" width="' . PAGE_AVATAR_WIDTH . '" height="' . PAGE_AVATAR_HEIGHT . '" border="0" style="margin-top:10px; border:solid 3px white;" /></div>
                        <div style="position:absolute; padding-top:13px; padding-left:28px; margin-top:-112px;"><img id="avatarIcon" src="images/icons/vcard_24x24.png" class="png" width="24" height="24" border="0" /></div>
                      </a>
                      <div style="text-align:left; margin-top:-102px; margin-left:260px; position:absolute;" id="avatarBlank"></div>
                      <script type="text/javascript"> var avatarEffect = new fx.Opacity("avatarBlank"); avatarEffect.hide(); </script>';
              }
            ?>
          </div>
          <div style="float:left; margin-left:10px; margin-top:10px; width:675px;" class="f_dark">
            <div class="f_14 bold">
              <?php
                echo '<div style="margin-left:20px;">' . $displayDescription . '&nbsp;</div>';
              ?>
            </div>
            <div style="margin-top:15px; height:72px; text-align:justify;">
              <?php
                if($subaction != 'tags' || ($subaction == 'tags' && isset($options[1])))
                {
                  $t =& CTag::getInstance();
                  $tagsArray = $t->tags($user_id, 'RANDOM', 42);
                  if($tagsArray[0]['COUNT'] > 0)
                  {
                    $min = $tagsArray[0]['MIN'];
                    $max = $tagsArray[0]['MAX'];
                    $step= ($max - $min) / 5;
                    $sizes = array(10, 10, 12, 14, 16);
                    echo '<div style="height:55px;">';
                    foreach($tagsArray as $v)
                    {
                      $fontSize = tagsize(intval($v['WEIGHT']), $step, $sizes);
                      echo '&nbsp;<a href="/users/' . $username . '/tags/' . $v['TAG'] . '/" style="font-size:' . $fontSize . 'px;" class="plain headerTag" title="' . $v['TAG_COUNT'] . ' photos and slideshows tagged with ' . htmlspecialchars($v['TAG']) . '">' . $v['TAG'] . '</a> ';
                    }
                    
                    echo '</div>
                          <div style="text-align:right;"><a href="/users/' . $username . '/tags/" class="plain">view all tags</a></div>';
                  }
                }
              ?>
              
            </div>
          </div>
        </div>
        <br clear="all" />
      </div>
      <!-- toolbar functions -->
      <script type="text/javascript">
        Event.observe(window, "load", initializeLB, false);
      </script>
  <?php
    }
    else // default header
    {
  ?>
      <div id="header">
        <div style="width:180px; margin-top:15px; float:left;">
          <a href="/"><img src="images/logo_top.gif" width="117" height="21" hspace="15" border="0" /></a>
        </div>
        <div style="width:205px; margin-top:15px; float:left;" class="bold">
          <a href="/?action=home.help" class="plain"><span class="f_red">?</span> FAQ</a>
          <?php
            switch($action)
            {
              case 'flix.flix_form':
                echo ' | <a href="/?action=fotobox.fotobox_myfotos" class="plain"><img src="images/icons/camera_16x16.png" class="png" width="16" height="16" hspace="2" align="absmiddle" border="0" />Back to photos</a>';
                break;
            }
          ?>
        </div>
        <div style="width:575px; margin-top:15px; float:left;">
          <div style="text-align:right;">
            <?php
              if($logged_in === false)
              {
                if($action != 'home.registration_form_b')
                {
                  echo '<a href="https://' . FF_SERVER_NAME . '/?action=home.registration_form_b">sign up for a free trial</a> | <a href="/?action=home.login_form">login</a>';
                }
                else
                {
                  echo '<a href="http://' . FF_SERVER_NAME . '/home/tour/">take the tour</a> | <a href="http://' . FF_SERVER_NAME . '/?action=home.samples">view samples</a>';
                }
              }
              else
              {
                include_once PATH_CLASS . '/CUser.php';
                $u =& CUser::getInstance();
                $messageCnt = $u->countMessages($_USER_ID);
                echo '
                      <div style="float:right;">
                        <div style="float:left;">
                          ' . ($_FF_SESSION->value('is_trial') == USER_IS_TRIAL ? '<a href="https://' . FF_SERVER_NAME . '/?action=home.registration_form_b2">ready to buy?</a>&nbsp;|&nbsp;' : '') . 'logged in as ' . $_FF_SESSION->value('username') . '
                          &nbsp;&nbsp;(<a href="/?action=messaging.inbox" class="plain"><img src="/images/icons/mail' . ($messageCnt > 0 ? '_alt_2' : '') . '_16x16.png" class="png" width="16" height="16" align="absmiddle" border="0" /> ' . $messageCnt . ' unread</a>)
                          &nbsp; | &nbsp;
                        </div>
                        <div style="float:left;">
                          <div><a href="javascript:void(0);" onclick="myAccountDivEff.toggle();" class="plain">my account</a>&nbsp; | &nbsp;</div>
                          <div style="position:absolute; width:243px; margin-top:3px; margin-left:-125px; background-color:#efefef;" id="myAccountDiv">
                            <div style="text-align:left; padding:2px; border-right:solid 1px #c0c0c0; border-bottom:solid 1px #c0c0c0; border-left:solid 1px #c0c0c0;">';
                              if($_FF_SESSION->value('is_trial') == USER_IS_TRIAL)
                              {
                                echo '<div><a href="https://' . FF_SERVER_NAME . '/?action=home.registration_form_b2" class="plain"><img src="images/icons/credit_card_16x16.png" class="png" width="16" height="16" border="0" hspace="4" vspace="3" align="absmiddle" />Purchase an Account</a></div>';
                              }
                              else
                              {
                                echo '<div><a href="https://' . FF_SERVER_NAME . '/?action=account.billing_update_form" class="plain"><img src="images/icons/credit_card_16x16.png" class="png" width="16" height="16" border="0" hspace="4" vspace="3" align="absmiddle" />Update Billing Information</a></div>';
                              }
                echo '
                              <div><a href="/?action=account.profile_form" class="plain"><img src="images/icons/vcard_16x16.png" class="png" width="16" height="16" border="0" hspace="4" vspace="3" align="absmiddle" />Update Account Information</a></div>
                              <div><a href="/?action=account.password_form" class="plain"><img src="images/icons/key_16x16.png" class="png" width="16" height="16" border="0" hspace="4" vspace="3" align="absmiddle" />Change Password</a></div>
                            </div>
                          </div>
                        </div>
                        <div style="float:left;"><a href="/?action=member.logout.act" class="plain">logout</a></div>
                        <br clear="all" />
                      </div>
                      <script type="text/javascript"> var myAccountDivEff = new fx.Height("myAccountDiv", {duration:300}); myAccountDivEff.hide(); </script>
                      ';
              }
            ?>
          </div>
        </div>
      </div>
  <?php 
    }
    
    if(!isset($isMyPage))
    {
      echo '<div id="content">';
    }
    else
    {
      echo '<div id="contentMy">';
    }
  
    if($mode == 'double')
    {
      if(isset($isGroup))
      {
        include_once PATH_DOCROOT . '/navigation_group.dsp.php';
      }
      else
      if(isset($isMyPage))
      {
        include_once PATH_DOCROOT . '/navigation_my.dsp.php';
      }
      else
      {
        include_once PATH_DOCROOT . '/navigation.dsp.php';
      }
      
      echo '<div id="dataDouble">';
      
      if(isset($isGroup) || isset($isMyPage))
      {
        echo '<div style="height:10px;"></div>';
      }
    }
    else
    {
      echo '<div id="dataSingle">';
      
      if(isset($isGroup) || isset($isMyPage))
      {
        echo '<div style="height:10px;"></div>';
      }
    }
  ?>

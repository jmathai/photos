<?php
  $searchParams = "WHERE us_status = 'Active' ";
  
  if(!empty($_GET['u_username']))
  {
    $u_username = preg_replace('/\W/', '', $_GET['u_username']);
    $searchParams .= "AND u_username LIKE '%$u_username%' ";
  }
  
  /*
  if(!empty($_GET['ft_swf']))
  {
    $ft_swf = $GLOBALS['dbh']->sql_safe($_GET['ft_swf']);
    $searchParams .= "AND ft_swf = $ft_swf ";
  }
  */
  
  if(!empty($_GET['us_dateCreatedFrom']))
  {
    $us_dateFrom = $_GET['us_dateCreatedFrom'];
    $dateInfo = split("-", $us_dateFrom);
    $ts = mktime(0, 0, 0, $dateInfo[0], $dateInfo[1], $dateInfo[2]);
    $us_dateFrom = date('Y-m-d', $ts);
    $us_dateFrom = $GLOBALS['dbh']->sql_safe($us_dateFrom);
    $searchParams .= "AND us_dateCreated >= {$us_dateFrom} ";
  }
  
  if(!empty($_GET['us_dateCreatedTo']))
  {
    $us_dateTo = $_GET['us_dateCreatedTo'];
    $dateInfo = split('-', $us_dateTo);
    $ts = mktime(0, 0, 0, $dateInfo[0], $dateInfo[1], $dateInfo[2]);
    $us_dateTo = date('Y-m-d', $ts);
    $us_dateTo = $GLOBALS['dbh']->sql_safe($us_dateTo);
    $searchParams .= "AND us_dateCreated <= {$us_dateTo} ";
  }
  
  // paging info
  $currentPage = isset($_GET['page']) ? intval($_GET['page'])  : 1;
  $limit = 50;
  $offset = $limit * ($currentPage - 1);
  
  $sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM (users INNER JOIN user_slideshows ON u_id = us_u_id) ' . $searchParams . ' ORDER BY us_id DESC LIMIT ' . $limit . ' OFFSET ' . $offset . ' ';
  $flix = $GLOBALS['dbh']->query_all($sql);
  $totalRows = $GLOBALS['dbh']->found_rows();
  
  $pagesToDisplay = 6;
  $totalPages = ceil($totalRows/$limit);

  $page  =& new CPaging($currentPage, $pagesToDisplay, $totalPages, 'page', '/cp/', $_SERVER['QUERY_STRING']);
  
  if($totalPages > 1)
  {
    $pages = $page->getPages();
    if($currentPage != 1)
    {
      $firstPage = $page->getFirstPage('<img src="/images/paging_first.gif" border="0" width="15" height="15" />');
    }
    
    if($currentPage != $totalPages)
    {
      $lastPage = $page->getLastPage('<img src="/images/paging_last.gif" border="0" width="15" height="15" />');
    }
    
    $nextPage = $page->getNextPage('<img src="/images/paging_next.gif" border="0" width="15" height="15" />');
    $prevPage = $page->getPrevPage('<img src="/images/paging_previous.gif" border="0" width="15" height="15" />');
  }
  
  echo '<div class="padding_top_10"></div>
        <div style="padding-top:10px; padding-bottom:10px; padding-right:20px; text-align:right;" class="f_8">' . $firstPage . $prevPage . $pages . $nextPage . $lastPage . '</div>
        <div class="f_8 bold center" style="padding-top:3px; padding-bottom:3px; background-color:#ffffff;">
          <div style="width:80px; float:left;">Username</div>
          <div style="width:115px; float:left;">Title</div>
          <div style="width:180px; float:left;">Theme</div>
          <div style="width:50px; float:left;">Photos</div>
          <div style="width:50px; float:left;">Views</div>
          <div style="width:65px; float:left;">Views Complete</div>
          <div style="width:65px; float:left;">MP3/Lib</div>
          <div style="width:65px; float:left;">Autoplay</div>
          <div style="width:100px; float:left;">Privacy</div>
          <div style="width:100px; float:left;">Date Created</div>
          <br clear="all" />
        </div>';
  
  foreach($flix as $k => $v)
  {
    $privacy = '';
    if($v['us_privacy'] & PERM_SLIDESHOW_PUBLIC == PERM_SLIDESHOW_PUBLIC)
    {
      $privacy .= 'Public ';
    }
    if($v['us_privacy'] & PERM_SLIDESHOW_COMMENT == PERM_SLIDESHOW_COMMENT)
    {
      $privacy .= 'Comment ';
    }
    if($privacy == '')
    {
      $privacy = 'Private';
    }
    
    $settings = jsonDecode($v['us_settings']);
    $theme = 'Default Left-Right';
    $preview = false;
    $altTheme = '';
    $mp3 = 'No Music';
    foreach($settings as $v2)
    {
      if($v2['instanceName_str'] == 'background_mc')
      {
        if(isset($v2['swfPath_str']))
        {
          $theme = basename($v2['swfPath_str']);
        }
      }
      
      if($v2['instanceName_str'] == 'backgroundGraphic_mc')
      {
        if(isset($v2['swfPath_str']))
        {
          $altTheme = basename($v2['swfPath_str']);
        }
      }
      
      if($v2['instanceName_str'] == 'detail_mc')
      {
        if(isset($v2['swfPath_str']))
        {
          $altTheme = basename($v2['swfPath_str']);
        }
      }
        
      if($v2['instanceName_str'] == 'preview_mc')
      {
        $preview = true;
      }
      
      if(isset($v2['musicPath_str']) && $v2['musicPath_str'] != '')
      {
        if(stristr($v2['musicPath_str'], '.mp3'))
        {
          $mp3 = 'MP3';
        }
        elseif(stristr($v2['musicPath_str'], '.swf'))
        {
          $mp3 = 'LIB';
        }
      }
    }
    
    if($theme == 'Default Left-Right')
    {
      if($altTheme == '')
      {
        if($preview == false)
        {
          $theme = 'No Theme or Default Center';
        }
      }
      else 
      {
        $theme = $altTheme;
      }
    }
          
    //print_r($settings);
    
    $autoplay = 'N';
    if(intval($settings[0]['startAutoPlay_bool']) == 1)
    {
      $autoplay = 'Y';
    }
    
    /*
    $mp3 = 'No Music';
    if($settings[0]['musicPath_str'] != '')
    {
      if(strncmp($settings[0]['musicPath_str'], '.mp3', 4) == 0)
      {
        $mp3 = 'MP3';
      }
      elseif(strncmp($settings[0]['musicPath_str'], '.swf', 4) == 0)
      {
        $mp3 = 'LIB';
      }
    }
    */
    
    $title = str_mid($v['us_name'], 15);
    if($title == '')
    {
      $title = 'No Title';
    }
    
    echo '<div class="f_8 center" style="padding-top:3px; padding-bottom:3px; background-color:' . ($k % 2 == 0 ? '#f9f6c7' : '#ffffff') . ';">
            <div style="width:80px; float:left;"><a href="http://' . FF_SERVER_NAME . '/f0t09r.php?username=' . $v['u_username'] . '" target="_blank"><span title="' . $v['u_username'] . '">' . str_mid($v['u_username'], 10) . '</span></a></div>
            <div style="width:115px; float:left;"><a href="http://' . FF_SERVER_NAME . '/slideshow?' . $v['us_key'] . '" target="_blank" title="' . $v['us_name'] . '">' . $title . '</a></div>
            <div style="width:180px; float:left;">' . $theme . '</div>
            <div style="width:50px; float:left;">' . $v['us_fotoCount'] . '</div>
            <div style="width:50px; float:left;">' . $v['us_views'] . '</div>
            <div style="width:65px; float:left;">' . $v['us_viewsComplete'] . '</div>
            <div style="width:65px; float:left;">' . $mp3 . '</div>
            <div style="width:65px; float:left;">' . $autoplay . '</div>
            <div style="width:100px; float:left;">' . $privacy . '</div>
            <div style="width:100px; float:left;">' . date('m-d-Y', strtotime($v['us_dateCreated'])) . '</div>
            <br clear="all" />
          </div>';
  }
  
  echo '<div style="padding-top:10px; padding-bottom:10px; padding-right:20px; text-align:right;" class="f_8">' . $firstPage . $prevPage . $pages . $nextPage . $lastPage . '</div>';
  echo '<div class="padding_top_10"></div>';
?>
<?php
  $n =& new CNews;
  
  $detail = false;
  
  if(isset($_GET['sn_id']))
  {
    $pagemode   = 'detail';
    $news_data  = $n->newsData($_GET['sn_id']);
    
    if(count($news_data) > 0)
    {
      $detail = true;
    }
  }
  
  if($detail === false)
  {
    $pagemode       = 'list';
    $news_data  = $n->news();
  }
  
  echo '<br />
        <div style="padding-left:5px; padding-right:5px;">
        <div class="bold">What\'s happening at FotoFlix?' . ($pagemode == 'detail' ? '&nbsp;&nbsp;[<a href="/?action=home.news">view all</a>]' : '') . '</div><br />';
  
  switch($pagemode)
  {
    case 'list':
      foreach($news_data as $v)
      {
        echo '<div class="bold">' . date(FF_FORMAT_DATE_LONG, $v['N_DATE']) . '</div>
              <a href="/?action=home.news&sn_id=' . $v['N_ID'] . '">' . $v['N_HEADLINE'] . '</a>
              <div style="padding-bottom:15px;"></div>';
      }
      break;
    case 'detail':
      echo '<div class="bold">' . $news_data['N_HEADLINE'] . '</div>
            <div class="italic">' . date(FF_FORMAT_DATE_LONG, $news_data['N_DATE']) . '</div>
            <div style="padding-top:5px;">' . $news_data['N_BODY'] . '</div>';
      break;
  }
  
  echo '</div>';
  
  $tpl->main($tpl->get());
  $tpl->clean();
?>
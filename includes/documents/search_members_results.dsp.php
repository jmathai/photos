<?php
  $u = &CUser::getInstance();
  
  $group_id = isset($_GET['group_id']) ? $_GET['group_id'] : false;
  $u_username = false;
  $u_email = false;
  $u_firstName = false;
  $u_lastName = false;
  $limit = 25;
  
  if(!empty($_POST['u_username']))
  {
    $u_username = preg_replace('/\W/', '', $_POST['u_username']);
  }
  
  if(!empty($_POST['u_email']))
  {
    $u_email = preg_replace('/\W/', '', $_POST['u_email']);
  }
  
  if(!empty($_POST['u_firstName']))
  {
    $u_firstName = preg_replace('/\W/', '', $_POST['u_firstName']);
  }
  
  if(!empty($_POST['u_lastName']))
  {
    $u_lastName = preg_replace('/\W/', '', $_POST['u_lastName']);
  }
  
  // paging info
  $currentPage = isset($_GET['page']) ? intval($_GET['page'])  : 1;
  
  $users = $u->search($u_username, $u_email, $u_firstName, $u_lastName, $group_id, $limit, ($currentPage-1)*$limit);
  $totalRows = $GLOBALS['dbh']->found_rows();
  $pagesToDisplay = 30;
  $totalPages = ceil($totalRows/$limit);
  
  $pg  =& new CPaging($currentPage, $pagesToDisplay, $totalPages, 'page', '/', $_SERVER['QUERY_STRING']);
  
  if($totalPages != 0 )
  {
    echo '<div style="padding-top:25px; padding-left:10px; text-align:left;" class="f_7">Pages:';
  
    if(($currentPage - $pagesToDisplay) > 0)
    {
      echo $pg->getFirstPage('1') . '&nbsp;&middot;&middot;&middot;';
    }
    
    echo $pg->getPages();  
    
    if($currentPage < ($totalPages - $pagesToDisplay))
    {
      echo '&middot;&middot;&middot;&nbsp;' . $pg->getLastPage($totalPages);
    }
    echo '</div>';
  }
  
  echo '<div style="padding-top:5px;"></div>
        <div class="bold" style="padding-left:15px; padding-top:3px; padding-bottom:3px; background-color:#ffffff;">
          <div style="width:100px; float:left;">Username</div>
          <div style="width:150px; float:left;">Email</div>
          <div style="width:100px; float:left;">First Name</div>
          <div style="width:100px; float:left;">Last Name</div>
          <br />
        </div>';
  
  if(count($users) == 0)
  {
    echo '<div style="border:solid 1px pink; padding-top:3px; padding-bottom:3px; padding-left:15px; background-color:' . ($k % 2 == 0 ? '#f9f6c7' : '#ffffff') . ';">';
    echo  '<div style="float:left;">No users found</div>
           <br />
          </div>';
  }
  else 
  {
    foreach($users as $k => $v)
    {
      echo '<div style="border:solid 1px pink; padding-top:3px; padding-bottom:3px; padding-left:15px; background-color:' . ($k % 2 == 0 ? '#f9f6c7' : '#ffffff') . ';">';       
      echo   '<div style="width:100px; float:left;"><span title="' . $v['U_USERNAME'] . '">' . str_mid($v['U_USERNAME'], 10) . '</span></div>
              <div style="width:150px; float:left;"><span title="' . $v['U_EMAIL'] . '">' . str_mid($v['U_EMAIL'], 20) . '</span></div>
              <div style="width:100px; float:left;"><span title="' . $v['U_NAMEFIRST'] . '">' . str_mid($v['U_NAMEFIRST'], 15) . '</span></div>
              <div style="width:100px; float:left;"><span title="' . $v['U_NAMELAST'] . '">' . str_mid($v['U_NAMELAST'], 15) . '</span></div>
              <br />
            </div>';
    }
  }
  
  echo '<div class="padding_top_10"></div>';
?>

<?php  
  $tpl->main($tpl->get());
  $tpl->clean();
?>
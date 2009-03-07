<?php
  $b =& CBlog::getInstance();  

  if($_USER_ID != $user_id)
  {
    echo 'login';
  }
  else
  {
    $submitText = 'Add Entry';
    $submitUrl  = '/?action=my.blog_add_entry.act';
    $entryId    = 0;
    
    $day   = date('j', NOW);
    $month = date('n', NOW);
    $year  = date('Y', NOW);
    
    if(isset($options[1]))
    {
      $blogEntry = $b->entry($_USER_ID, $options[1]);
      
      if($blogEntry !== false)
      {
        $subject = htmlspecialchars($blogEntry['B_SUBJECT']);
        $body    = str_replace(array("\r", "\n", "'"), array(' ', ' ', '&quot;'), $blogEntry['B_BODY']);
        $day   = date('j', $blogEntry['B_DATEPOSTED']);
        $month = date('n', $blogEntry['B_DATEPOSTED']);
        $year  = date('Y', $blogEntry['B_DATEPOSTED']);
        $entryId = $blogEntry['B_ID'];
        $submitText = 'Update Entry';
        $submitUrl  = '/?action=my.blog_update_entry.act';
      }
    }
    
    $dateControl = '';
    $monthsArr = array('', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    
    $dateControl .= '<select name="datePostedMonth" class="formfield">';
    for($i=1; $i<13; $i++)
    {
      $selected = '';
      if($month == $i)
      {
        $selected = ' selected="true" ';
      }
      $dateControl .= '<option value="' . $i . '" ' . $selected . '>' . $monthsArr[$i] . '</option>';
    }
    
    $dateControl .= '</select> / <select name="datePostedDay" class="formfield">';
    for($i=1; $i<32; $i++)
    {
      $selected = '';
      if($day == $i)
      {
        $selected = ' selected="true" ';
      }
      $dateControl .= '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
    }
    $dateControl .= '</select> / <select name="datePostedYear" class="formfield">';
    $sYear = date('Y', NOW);
    for($i=0; $i<5; $i++)
    {
      $thisYear = $sYear - $i;
      $selected = '';
      if($year == $i)
      {
        $selected = ' selected="true" ';
      }
      $dateControl .= '<option value="' . $thisYear . '" ' . $selected . '>' . $thisYear . '</option>';
    }
    $dateControl .= '</select>';
    
    echo '
      <div id="blogContent">
        <form action="' . $submitUrl . '" name="entry" method="post">
          <div class="bold f_10">Date</div>
          <div>
            ' . $dateControl . '
          </div>
          
          <br/>
          <div class="bold f_10">Title</div>
          <div>
            <input type="text" name="ube_subject" id="ube_subject" value="' . $subject . '" size="45" class="formfield" />
          </div>
          
          <br/>
          
          <div class="bold f_10">Body</div>
          <div>';
    if(stristr($_SERVER['HTTP_USER_AGENT'], 'safari'))
    {
      echo '
            <div><img src="/images/icons/warning_16x16.png" class="png" width="16" height="16" border="0" align="absmiddle" />&nbsp;By using Firefox you can easily add photos and slideshows</div>
            <textarea name="fck_instance" class="formfield" style="width:550px; height:250px;">' . $body . '</textarea>
            ';
    }
    else
    {
    
      echo '
          <script type="text/javascript">
            <!--
            var sBasePath = "/js/fck/";
            
            var oFCKeditor = new FCKeditor( "fck_instance" ) ;
            oFCKeditor.BasePath	= sBasePath ;
            oFCKeditor.Height	= 250;
            oFCKeditor.Width	= 550;
            oFCKeditor.Create() ;
            
            function FCKeditor_OnComplete( editorInstance )
            {
              oEditor = FCKeditorAPI.GetInstance("fck_instance");
              oEditor.SetHTML(\'' . $body . '\');
            }
            
            function previewProfile()
            {
              var previewProfile = window.open("", "postProfile", "width=550,height=250,scrollbars=1");
              previewProfile.document.open("text/html", "");
              previewProfile.document.write("<html><head><title>Post Preview</title></head><body>" + oEditor.GetXHTML() + "</body></html>");
              previewProfile.document.close();
            }
            //-->
        	</script>';
    }
    
    echo '
          </div>
          
          <br/><br/>
          
        <div style="margin-top:5px;">
          <a href="javascript:void(0);" onclick="if(blogEntryForm()){ document.forms[\'entry\'].submit(); }" class="f_white plain f_11 bold"><img src="images/icons/save_24x24.png" class="png" width="24" height="24" hspace="3" border="0" align="absmiddle" />' . $submitText . '</a>
        </div>
        <input type="hidden" name="username" value="' . $username . '" />
        <input type="hidden" name="ube_id" value="' . $entryId . '" />
        </form>
      </div>
      <div id="blogSideBar">';
  
    include_once PATH_DOCROOT . '/my_sidebar.dsp.php';
    
    echo '
      </div>
        ';
  }
?>
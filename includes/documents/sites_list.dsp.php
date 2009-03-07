<?php
  $fl =& CFlix::getInstance();

  $sites = $fl->search(array('USER_ID' => $_USER_ID, 'TYPE' => 'site'));
  $cntSites = count($sites);
  
  $i = 0;
  if($cntSites > 0)
  {
    echo '<h3>Your sites &nbsp; (<a href="/?action=sites.form">create a new site</a>)</h3>';
    echo '<table border="0" width="100%" cellpadding="4">
            <tr>
              <th>&nbsp;</th>
              <th align="left">Name</th>
              <th align="left">Views</th>
            </tr>';
    foreach($sites as $site)
    {
      $bgColor = $i % 2 == 0 ? '#ffffff' : '#eeeeee';
      echo '<tr bgcolor="' . $bgColor . '">
              <td><a href="/?action=sites.form&key=' . $site['US_KEY'] . '">edit</a> | <a href="/site.php?' . $site['US_KEY'] . '">view</a> | <a href="javascript:void(0);" onclick="eff' . $site['US_ID'] . '.toggle();">embed</a></td>
              <td>' . $site['US_NAME'] . '</td>
              <td>' . $site['US_VIEWS'] . '</td>
            </tr>
            <tr bgcolor="' . $bgColor . '">
              <td colspan="3">
                <div id="code' . $site['US_ID'] . '">
                  <div class="bold">Link to this site</div>
                  <input type="text" size="100" value="http://' . FF_SERVER_NAME . '/site.php?' . $site['US_KEY'] . '" />
                  <br/>&nbsp;<br/>
                  <div class="bold">Embed this site into your own page</div>
                  <textarea class="formfield" rows="3" cols="100">' . htmlspecialchars('<script src="http://' . FF_SERVER_NAME . '/js/slideshow_remote/' . $site['US_KEY'] . '"></script>') . '</textarea>
                </div>
                <script> var eff' . $site['US_ID'] . ' = new fx.Height("code' . $site['US_ID'] . '"); eff' . $site['US_ID'] . '.hide(); </script>
              </td>
            </tr>
              ';
      $i++;
    }
    echo '</table>';
  }
  else
  {
    echo '<h3><a href="/?action=sites.form">Create your first site</a></h3>';
  }
?>

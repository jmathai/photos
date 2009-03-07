<?php
  include_once './../init_constants.php';
  include_once PATH_INCLUDE . '/functions.php';
  include_once PATH_DOCROOT . '/init_database.php';
  
  if(isset($_GET['doId']))
  {
    $GLOBALS['dbh']->execute('REPLACE INTO fotos_public_select(up_id, dateCreated) VALUES(' . intval($_GET['doId']) . ', NOW())');
  }
  else
  if(isset($_GET['removeId']))
  {
    $GLOBALS['dbh']->execute('DELETE FROM fotos_public_select WHERE up_id = ' . intval($_GET['removeId']));
  }
  
  $fotos = $GLOBALS['dbh']->query_all('SELECT * FROM user_fotos AS uf INNER JOIN fotos_public_select AS fps ON uf.up_id = fps.up_id ORDER BY dateCreated DESC');
  
  echo '<html>
        <head>
        <style type="text/css">
          body{
            margin:0px;
            background-color:#efefef;
            font-family:verdana,helvetica;
            font-size:10px;
          }
        </style>
        </head>
        <body>
          <div style="width:640px; height:80px;">';
  foreach($fotos as $v)
  {
    echo '    <div style="float:left; padding-bottom:20px; text-align:center;"><a href="' . PATH_FOTO . $v['up_original_path'] . '" target="_blank"><img src="' . PATH_FOTO . $v['up_thumb_path'] . '" hspace="10" border="0" /></a><br/>' . $v['up_width'] . 'x' . $v['up_height'] . '<br /><a href="show_public_fotos.php?removeId=' . $v['up_id'] . '">remove</a></div>';
  }
  echo '  </div>
        </body>
        </html>';
?>
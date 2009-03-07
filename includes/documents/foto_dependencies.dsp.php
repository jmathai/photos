<?php
  $fb =& CFotobox::getInstance();
  $fl =& CFlix::getInstance();
  
  $foto_ids = isset($_GET['ids']) ? $_GET['ids'] : '';
  $fotos_array = explode(',', $foto_ids);
  
  $dependencies_array = $fb->dependencies($fotos_array);
  
  $i = 0;
  $output = '';
  foreach($dependencies_array as $k => $v)
  {
    $foto_data  = $fb->fotoData($v['P_ID'], $_USER_ID);
    $flix_array = array();
    
    if(isset($v['FLIX_IDS']))
    {
      foreach($v['FLIX_IDS'] as $k2 => $v2)
      {
        $flix_array[] = $fl->flixData($v2, $_USER_ID);
        $i++;
      }
    }
    /*
    * image url is $foto_data['P_THUMB_PATH'];
    * image width/height tag is $image_info[3] -> width="x" height="x"
    */
    $output .= '<tr height="50"><td align="center" valign="middle"><img src="' . PATH_FOTO . $foto_data['P_THUMB_PATH'] . '" width="40" height="40" /></td><td align="left"><table cellpadding="0" cellspacing="0" border="0">';
    //echo 'thumbnail is -> ' . $foto_data['P_THUMB_PATH'] . '<br />';
    //echo 'dimensions are -> ' . $image_info[3] . '<br />';

    /*
    * loop over each flix that this foto is in
    */
    foreach($flix_array as $v3)
    {
      $output .= '<tr><td width="150"></td><td align="left"><img src="images/icons/foto_flix_icon.gif" border="0"></td><td align="left">&nbsp;&nbsp;&nbsp;' . $v3['A_NAME'] . '</td></tr>';
    }
    /*
    * loop over each group this foto is shared with
    */
    if(isset($v['GROUPS']))
    {
      foreach($v['GROUPS'] as $v4)
      {
        $output .= '<tr><td width="150"></td><td align="left"><img src="images/icons/foto_groups_icon.gif" border="0"></td><td align="left">&nbsp;&nbsp;&nbsp;' . $v4['G_NAME'] . '</td></tr>';
        $i++;
      }
    }
    
    $output .= '</table></td></tr><tr><td colspan="5"><img src="images/pixel_dk_blue.gif" height="1" width="543" /></td></tr>';
  }
  
  if($i > 0)
  {
?>
    <table cellpadding='0' cellspacing='0' border='0' width='545'>
      <tr>
       <td class='border_dark'>
         <table cellpadding='10' cellspacing='0' border='0'>
           <tr>
      	     <td><img src="images/warning.gif"></td>
             <td class='f_9 f_red bold'>
    	       Warning, the above foto(s) are shared with and will be removed from the following:
    	     </td>
    	     <td><img src="images/warning.gif"></td>
           </tr>  
         </table>
       </td>
      </tr>
    </table>
    <table cellpadding='0' cellspacing='0' border='0' width='545'>
      <tr>
        <td class='border_dark'>
          <table cellpadding='0' cellspacing='0' border='0' width='545'>
            <tr>
              <td align='center' background='images/pixel_dk_grey.gif' class='f_9 f_white bold' height="20">Foto</td>
              <td background='images/pixel_dk_grey.gif' class='f_9 f_white bold'>Included In</td>
            </tr>
            <?php
              echo $output;
            ?>
          </table>
        </td>
      </tr>
    </table>
<?php
  }
  
  if(!isset($no_set_template))
  {
    $tpl->main($tpl->get());
    $tpl->clean();
  }
?>
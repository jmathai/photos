<?php
  $prefix = substr($action, 0, strpos($action, '.'));
  $keywords = isset($_GET['keywords']) ? $_GET['keywords'] : '';
  $sort_by = '';
  
  if($prefix == 'fotobox')
  {
    $f =& CFotobox::getInstance();
    $bind_id = $_USER_ID;
    $group_suffix_url = '';
  }
  else
  {
    $f =& CGroup::getInstance();
    $bind_id = $_GET['group_id'];
    $group_suffix_url = '&group_id=' . $_GET['group_id'];
  }
  
  $array_results = $f->search($keywords, $bind_id);
  
  if(count($array_results) > 0)
  {
    $foto_ids = '0';
    foreach($array_results as $v)
    {
      $foto_ids .= ',' . $v['P_ID'];
    }
    
    $no_set_template  = true;
  
    include_once PATH_DOCROOT . '/fotobox_view.dsp.php';
  }
  else
  {
    echo  '<table border="0" cellpadding="0" width="545">
              <tr>
                <td align="left">
                  Sorry but your search for <i>' . $_GET['keywords'] . '</i> did not find any results.<br /><br />
                  You may refine your search criteria above or browse your <a href="/?action=' . $prefix . '.upload_history' . $group_suffix_url . '">upload history</a>.</span><br /><br />
                </td>
              </tr>
            </table>';
  }
  
  $tpl->main($tpl->get());
  $tpl->clean();
?>
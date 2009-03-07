<?php
  $fl  =& CFlix::getInstance();
  $flm =& CFlixManage::getInstance();
  $flix_id = intval($_GET['flix_id']);
  
  $flm->duplicate($flix_id, $_USER_ID);
  
  $flix_data = $fl->search(array('FLIX_ID' => $flix_id, 'USER_ID' => $_USER_ID));
  $fastflix = $flix_data['US_KEY'];
  $flm->writeXml($fastflix, $_USER_ID);
  
  $url = '/?action=flix.flix_list&message=duplicated';
?>
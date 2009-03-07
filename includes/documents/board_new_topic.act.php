<?php
  $board = CBoard::getInstance();
  $_postData = $_POST;
  
  $_postData['bp_title'] = sanitize($_postData['bp_title']);
  $_postData['bpc_content'] = sanitize($_postData['bpc_content'], array('PRESERVE_ANCHORS' => true, 'ANCHOR_TARGET' => '_blank'));
  
  $result = $board->addNewTopic($_postData);
  
  if( $result === false )
  {
    //error
  }
  
  $url = '/?action=board.board_post&post_id=' . $result;
?>
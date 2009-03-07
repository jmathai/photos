<?php
  $board = CBoard::getInstance();
  $pID = $_GET['post_id'];
  $_postData = $_POST;
  
  $_postData['bpc_content'] = sanitize($_postData['bpc_content'], array('PRESERVE_ANCHORS' => true, 'ANCHOR_TARGET' => '_blank'));
  $result = $board->addReply($_postData);
  
  if( $result === false )
  {
    //error
  }
  
  // paging info
  $currentPage = isset($_GET['page']) ? intval($_GET['page'])  : 1;
  $limit = 10;
  $postData = $board->postByPage( $pID, ($currentPage-1)*$limit, $limit );
  $totalRows = $GLOBALS['dbh']->found_rows();
  $totalPages = ceil($totalRows/$limit);
  
  $url = '/?action=board.board_post&post_id=' . $_postData['bp_p_id'] . '&page=' . $totalPages . '#post' . $result;
?>
<?php
	$error = '';
	
	//$m_swf_src = '/' . $_FILES['m_swf_src']['name'];
	//$m_swf_src = $GLOBALS['dbh']->sql_safe($m_swf_src);
	$m_swf_src = $_POST['m_swf_src'];
	$m_swf_src = $GLOBALS['dbh']->sql_safe($m_swf_src);
	
	$sql = 'SELECT COUNT(*) AS CNT '
	     . 'FROM music '
	     . 'WHERE m_swf_src = ' . $m_swf_src . ' ';
	     
	$musicRs = $GLOBALS['dbh']->query_first($sql);
	
	if($musicRs['CNT'] == 0)
	{
		//$src = $_FILES['m_swf_src']['tmp_name'];
		//$dest = PATH_DOCROOT . PATH_SWF_MUSIC . '/' . $_FILES['m_swf_src']['name'];
		
		//copy($src, $dest);
		
		$m_genre = $GLOBALS['dbh']->sql_safe($_POST['m_genre']);
		$m_tempo = !empty($_POST['m_tempo']) ? $GLOBALS['dbh']->sql_safe($_POST['m_tempo']) : 'null';
		$m_name = $GLOBALS['dbh']->sql_safe($_POST['m_name']);
		$m_description = !empty($_POST['m_description']) ? $GLOBALS['dbh']->sql_safe($_POST['m_description']) : 'null';
		
		$sql = 'INSERT INTO music (m_swf_src, m_genre, m_tempo, m_name, m_description, m_dateCreated, m_active) '
		     . 'VALUES (' . $m_swf_src . ',' . $m_genre . ',' . $m_tempo . ',' . $m_name . ',' . $m_description . ', NOW(), \'Y\') ';
		
		$GLOBALS['dbh']->execute($sql);
	}
	else 
	{
		$error = 'FILE_EXISTS';
	}
	
	$url = '/cp/?action=music.home&error=' . $error;
?>
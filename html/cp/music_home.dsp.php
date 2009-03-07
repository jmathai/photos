<?php
	$error = !empty($_GET['error']) ? $_GET['error'] : false;
	
	switch($error)
	{
		case 'FILE_EXISTS':
			$errorMessage = 'A file with this name already exists';
			break;
			
		default:
			$errorMessage = 'Unknown error';
			break;
	}
	
  $fv =  new CFormValidator;
  
  $fv -> setForm('_music');
  $fv -> addElement('m_swf_src', 'Music Source', '  - Must specify a music source', 'length');
  //$fv -> addElement('m_tempo', 'Tempo', '  - Must specify a tempo', 'length');
  $fv -> addElement('m_name', 'Name', '  - Must specify a name', 'length');
  //$fv -> addElement('m_description', 'Description', '  - Must specify a description', 'length');
  $fv -> setMaxElementsToDisplay(4);
  $fv -> setDebugOutput(false);
  $fv -> setFunctionName('_validate');
  $fv -> validate();
  
  
  $genre = array('My Music', 'Holiday', 'Classical', 'Country', 'Pop/Rock', 'Jazz/Blues', 'Romantic', 'Drama/Suspense', 'Children/Cartoon', 'Easy Listening', 'World', 'Solo Instrument', 'Ambience', 'Drums');
  $genreOptions = '<select name="m_genre" class="formfield">';
  foreach($genre as $v)
  {
    $genreOptions .= '<option value="' . $v . '">' . $v . '</option>';
  }
  $genreOptions .= '</select>';
?>

<form name="_music" method="POST" action="/cp/?action=music.home.act" onsubmit="return _validate();" />
  <div style="margin-left:50px; margin-top:25px;">
    <div style="padding-bottom:25px;" class="f_12 bold">Insert New Music</div>
    <?php
    	if($error !== false)
    	{
    		echo '<div style="padding:0px 0px 15px 0px;" class="f_7 bold error">' . $errorMessage . '</div>';
    	}
   	?>
    <div style="float:left; padding-left:5px;">
      <div style="padding-bottom:12px; width:100px; text-align:right;">Music Source</div>
      <div style="padding-bottom:9px; width:100px; text-align:right;">Genre</div>
      <div style="padding-bottom:9px; width:100px; text-align:right;">Tempo</div>
      <div style="padding-bottom:9px; width:100px; text-align:right;">Name</div>
      <div style="width:100px; text-align:right;">Description</div>
    </div>
    <div style="float:left; padding-left:15px;">
      <div style="padding-bottom:8px;"><input type="text" name="m_swf_src" class="formfield" /></div>
      <div style="padding-bottom:8px;"><?php echo $genreOptions; ?></div>
      <div style="padding-bottom:8px;"><input type="text" name="m_tempo" class="formfield" /></div>
      <div style="padding-bottom:8px;"><input type="text" name="m_name" class="formfield" /></div>
      <div style="padding-bottom:8px;"><input type="text" name="m_description" class="formfield" /></div>
    </div>
    <br clear="all" />
    <div style="padding-left:120px; padding-bottom:15px;"><input type="submit" value="Add" style="cursor:pointer;" class="formfield" /></div>
  </div>
</form>
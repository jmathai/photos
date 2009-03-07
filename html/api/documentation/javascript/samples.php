<?php
  $elements = array('html.customImageTag','html.customImageLockTag','html.imageTag','image.search');
                    
  foreach($elements as $k => $v)
  {
    $elements[$k] = '/' . str_replace('.', '\.', $v) . '/';
  }
  
  switch($_SERVER['QUERY_STRING'])
  {
    case '1':
      $title  = 'This example makes a request and outputs the response (a JavaScript object).';
      $sample = '
<script type="text/javascript" id="__PTG" src="http://www.photagious.com/js/api.js"></script>
<script>
  var ptg = new PTG("10fbbb7d87826e9301c7323cb9c5ddbc");
  window.onload = ptg.image.search({}, "basicExampleRsp");
  function basicExampleRsp(data)
  {
    document.getElementById("ptgPhotoDiv").innerHTML = data;
  }
</script>
<div id="ptgPhotoDiv"></div>
';
      break;
    case '2':
      $title  = 'This example makes a request and displays up to 10 images.';
      $sample = '
<script type="text/javascript" id="__PTG" src="http://www.photagious.com/js/api.js"></script>
<script>
  var ptg = new PTG("10fbbb7d87826e9301c7323cb9c5ddbc");
  window.onload = ptg.image.search({"limit":"10"}, "showImages");
  
  function showImages(data)
  {
    var html = "";
    while(image = ptg.result.next(data))
    {
      html += ptg.html.imageTag(image.thumbnailPath); 
    }
    document.getElementById("ptgPhotoDiv").innerHTML = html;
  }
</script>
<div id="ptgPhotoDiv"></div>
';
      break;
    case '3':
      $title  = 'This example makes a request and displays up to 10 custom sized images and has an AJAX search field.';
      $sample = '
<script type="text/javascript" id="__PTG" src="http://www.photagious.com/js/api.js"></script>
<script>
  var ptg = new PTG("10fbbb7d87826e9301c7323cb9c5ddbc");

  function searchImages(tags)
  {
    ptg.image.search({"tags":tags,"limit":"10"}, "searchImagesRsp");
  }
  
  function searchImagesRsp(data)
  {
    var html = "";
    while(image = ptg.result.next(data))
    {
      html += ptg.html.customImageTag(image.thumbnailPath, image.key, 150, 100, {"hspace":"5","vspace":"10","border":"0"}); 
    }
    document.getElementById("ptgPhotoDiv").innerHTML = html;
    return false;
  }
  
  window.onload = searchImages("");
</script>
<div>
  <input type="text size="12" id="searchField" />&nbsp;<input type="button" value="search" onclick="searchImages(document.getElementById(\'searchField\').value);" />&nbsp;(available tags: creative, tutorial, baby)
  </div>
<div id="ptgPhotoDiv"></div>
';
      break;
  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">

<head>

<meta HTTP.EQUIV="Content.Type" CONTENT="text/html; charset=iso.8859.1">

<link rel="icon" href="/favicon.ico" type="image/x.icon">
<link rel="stylesheet" type="text/css" href="css/styles.css">
<script type="text/javascript" src="js/prototype.js"></script>

<title>The Photagious Media Server</title>

</head>

<body>

  <div class="sampleDisclaimer">
    <div class="sampleDisclaimerTitle">
      <img src="images/information_24x24.png" width="24" height="24" border="0" align="absmiddle" />
      These examples use a temporary account on Photagious.
    </div>
    <div class="sampleNote">You can freely copy and paste these blocks of code for testing purposes.  You will need to change the key used (line 3) to be your user key.  If you have a local media server then you will want to update the path to the JavaScript file (line 1).</div>
  </div>
  
  <div class="sampleTitle"><?php echo $title; ?></div>
  <div class="sampleCode">
    <?php echo preg_replace($elements, '<a href="main.html#\0">\0</a>', nl2br(str_replace('  ', '&nbsp;&nbsp;', htmlspecialchars($sample)))); ?>
  </div>
  
  <div class="sampleTitle">Output (if any):</div>
  <div class="sampleOuput">
    <?php echo $sample; ?>
  </div>

</body>

</html>
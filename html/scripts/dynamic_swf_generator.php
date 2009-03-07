<?php
  include_once '../init_constants.php'; // this can stay since it's only called via web
  ming_useswfversion(6);
  $parts = explode('/', $_SERVER['REDIRECT_URL']);
  
  $swf = $parts[count($parts)-1];
  $swfBase = basename($swf, '.swf');
  $swfParts = explode('_', $swfBase);
  $height = array_pop($swfParts);
  $width = array_pop($swfParts);
  
  $m = new SWFMovie();
  $m->setDimension($width, $height);
  $m->setrate(30);
  
  $m->add(new SWFAction('this.createEmptyMovieClip("container_mc", 1);'));
  $m->add(new SWFAction('__d = new Date();'));
  $m->add(new SWFAction('loadMovie("http://' . FF_SERVER_NAME . '/swf/slideshow/slide_show_prototype.swf?__dynTS="+__d.getTime(),"container_mc");'));
  
  $m->setBackground(0, 0, 0);
  
  $m->save($finalPath = PATH_HOMEROOT . PATH_SWF . '/container/dynamic/' . $swf);
  
  echo $finalPath . ' was created.  <a href="javascript:history.go(-1);">Go back</a>.';
?>

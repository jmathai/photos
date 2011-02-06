<?php
  include_once dirname($_SERVER['SCRIPT_FILENAME']) . '/_reporter.php';
  chdir(dirname(__FILE__));
  ob_start();
  include_once str_replace('/scripts', '', dirname(__FILE__)) . '/init_constants.php';
  include_once PATH_DOCROOT . '/init_database.php';
  include_once PATH_CLASS . '/CUser.php';
  include_once PATH_CLASS . '/CFotobox.php';
  include_once PATH_CLASS . '/CTag.php';
  $u = CUser::getInstance();
  $fb = CFotobox::getInstance();
  $t = CTag::getInstance();
  $users = $u->search(array());
  $GLOBALS['dbh']->execute('TRUNCATE user_tag_sibling');
  foreach($users as $user)
  {
    $tags = array();
    $photos = $fb->fotosSearch(array());
    foreach($photos as $photo)
    {
      $pTags = (array)explode(',', $photo['P_TAGS']);
      foreach($pTags as $pTag)
      {
        if($pTag == '')
          continue;
        if(isset($tags[$pTag]))
          $tags[$pTag] = array_unique(array_merge($tags[$pTag], $pTags));
        else
          $tags[$pTag] = $pTags;
      }
    }

    foreach($tags as $tag => $siblings)
    {
      $t->addSiblings($user['U_ID'], $tag, (array)$siblings);
    }
  }
?>

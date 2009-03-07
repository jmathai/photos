<?php
  set_time_limit(180);
  include_once str_replace('scripts', '', dirname(__FILE__)) . 'init_constants.php';
  $alpha = range('z', 'a');
  $numeric = range(9,0);
  
  foreach($alpha as $v)
  {
    foreach($alpha as $w)
    {
      mkdir($one = PATH_FOTOROOT . '/xml/' . $v.$w);
      mkdir($two = PATH_FOTOROOT . '/xml/' . $w.$v);
      mkdir($one = PATH_FOTOROOT . '/xml/users/' . $v.$w);
      mkdir($two = PATH_FOTOROOT . '/xml/users/' . $w.$v);
      mkdir($one = PATH_HOMEROOT . '/reports/' . $v.$w);
      mkdir($two = PATH_HOMEROOT . '/reports/' . $w.$v);
      echo "$one<br/>$two<br/>";
    }
    
    foreach($numeric as $x)
    {
      mkdir($one = PATH_FOTOROOT . '/xml/' . $v.$x);
      mkdir($two = PATH_FOTOROOT . '/xml/' . $x.$v);
      mkdir($one = PATH_FOTOROOT . '/xml/users/' . $v.$x);
      mkdir($two = PATH_FOTOROOT . '/xml/users/' . $x.$v);
      mkdir($one = PATH_HOMEROOT . '/reports/' . $v.$x);
      mkdir($two = PATH_HOMEROOT . '/reports/' . $x.$v);
      echo "$one<br/>$two<br/>";
      foreach($numeric as $y)
      {
        mkdir($one = PATH_FOTOROOT . '/xml/' . $y.$x);
        mkdir($two = PATH_FOTOROOT . '/xml/' . $x.$y);
        mkdir($one = PATH_FOTOROOT . '/xml/users/' . $y.$x);
        mkdir($two = PATH_FOTOROOT . '/xml/users/' . $x.$y);
        mkdir($one = PATH_HOMEROOT . '/reports/' . $y.$x);
        mkdir($two = PATH_HOMEROOT . '/reports/' . $x.$y);
        echo "$one<br/>$two<br/>";
      }
    }
  }
?>
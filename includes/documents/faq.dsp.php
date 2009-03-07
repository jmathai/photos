<?php
  $fq =& CFaq::getInstance();
  
  if(isset($_GET['category']))
  {
    $faqs = $fq->faqs($_GET['category']);
  }
  else
  {
    $faqs = $fq->faqs();
  }
  
  echo '<div class="dataSingleContent">';
  
  if(isset($_GET['category']))
  {
    echo '<div class="bold f_14" style="padding-bottom:20px;"><a href="/?action=' . $action . '">FAQ</a> / ' . $_GET['category'] . '</div>';
  }
  
  $prev_category = '';
  foreach($faqs as $v)
  {
    $category = $v['F_CATEGORY'];
    $question = $v['F_QUESTION'];
    $answer   = $v['F_ANSWER'];
    $id = $v['F_ID'];
    
    if($category != $prev_category && !isset($_GET['category']))
    {
      echo '<div class="bold f_14" style="padding-top:10px; padding-bottom:5px;"><a href="/?action=' . $action . '&category=' . urlencode($category) . '">' . $category . '</a></div><div class="line_lite"></div>';
    }
    
    if(!isset($_GET['category']))
    {
      echo '<div style="padding-bottom:5px;" class="f_10"><a href="/?action=' . $action . '&category=' . urlencode($category) . '#' . $id . '">' . $question . '</a></div>';
    }
    else
    {
      echo '<div id="question_' . $id . '" style="padding-bottom:5px; padding-top:5px;" class="f_12 bold">' . $question . '<a name="' . $id . '"></a></div>
            <div id="answer_' . $id . '" style="padding-bottom:20px;" class="f_10">' . $answer . '</div><div class="line_lite"></div>';
    }
    
    $prev_category = $category;
  }
  echo '</div>';
?>

<script type="text/javascript">
  if(window.location.hash != '')
  {
    $('question_' + window.location.hash.replace('#', '')).style.backgroundColor = "yellow";
    $('answer_' + window.location.hash.replace('#', '')).style.backgroundColor = "yellow";
  }
</script>
<?php
  if(count($_POST) > 0)
  {
    $postArray = $GLOBALS['dbh']->asql_safe($_POST);
    if(isset($_POST['f_id']))
    {
      $sql = "UPDATE faqs SET f_category={$postArray['f_category']}, f_question={$postArray['f_question']}, f_answer={$postArray['f_answer']} WHERE f_id={$postArray['f_id']}";
      $message = urlencode('FAQ updated');
    }
    else
    {
      $sql = "INSERT INTO faqs(f_category, f_question, f_answer) VALUES({$postArray['f_category']}, {$postArray['f_question']}, {$postArray['f_answer']})";
      $message = urlencode('FAQ added');
    }
  }
  else
  if(isset($_GET['deleteFaqId']))
  {
    $faqId = intval($_GET['deleteFaqId']);
    $sql = "UPDATE faqs SET f_active = 'N' WHERE f_id = {$faqId}";
    $message = urlencode('FAQ deleted');
  }
  
  $GLOBALS['dbh']->execute($sql);
  
  $url = './?action=faq.home&message=' . $message;
?>
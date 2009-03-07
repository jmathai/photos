<?php
  $f =& CFaq::getInstance();
  $faqs = $f->faqs();
  
  if(isset($_GET['message']))
  {
    echo '<h2>' . $_GET['message'] . '</h2><br/>';
  }
  
  echo '<a href="./?action=faq.form">Add a new FAQ</a><br/><br/>';
  
  foreach($faqs as $v)
  {
    echo '<b>Category:</b> ' . $v['F_CATEGORY'] . '<br/>
          <b>Question:</b> ' . $v['F_QUESTION'] . '<br/>
          <b>Answer:</b> ' . $v['F_ANSWER'] . '<br/>
          <br/>
          <a href="./?action=faq.form&faqId=' . $v['F_ID'] . '">Edit this faq</a><br/>
          <br/>
          <a href="./?action=faq.form.act&deleteFaqId=' . $v['F_ID'] . '">Delete this faq</a><br/>
          <br/>
          <hr />
          <br/>';
  }
?>
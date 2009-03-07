<?php
  $f =& CFaq::getInstance();
  
  if(!empty($_GET['faqId']))
  {
    $faq = $f->faqData($_GET['faqId']);
    $faqId = intval($_GET['faqId']);
    $button = 'Update';
  }
  else
  {
    $button = 'Add';
  }
  
  $categories=array('Account','General','Photos','Slideshows','Videos','Personal Page');
?>

<form method="post" action="./?action=faq.form.act">
  <b>Category:</b><br/>
  <select name="f_category">
    <?php
      foreach($categories as $v)
      {
        $selected = $faq['F_CATEGORY'] == $v ? ' selected="true" ' : '';
        echo '<option value="' . $v . '" ' . $selected . '>' . $v . '</option>';
      }
    ?>
  </select><br/>
  <br/>
  <b>Question:</b><br/>
  <input type="text" name="f_question" value="<?php echo htmlentities($faq['F_QUESTION']); ?>" class="formfield" size="60" /><br/>
  <br/>
  <b>Answer:</b><br/>
  <textarea class="formfield" rows="10" cols="60" name="f_answer"><?php echo htmlentities($faq['F_ANSWER']); ?></textarea><br/>
  <br/>
  <input type="submit" value="<?php echo $button; ?>" />
  <?php
    if(isset($faqId))
    {
      echo '<input type="hidden" name="f_id" value="' . $faqId . '" />';
    }
  ?>
</form>
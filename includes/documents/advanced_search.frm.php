<?php
  $prefix = substr($action, 0, strpos($action, '.'));
?>

<div align="left" style="padding-left:5px;">
  <div class="bold">Advanced Foto Search</div>
  Enter your keywords below.  Search includes FotoLabels, foto names, and foto descriptions.
  <div style="padding-bottom:10px;"></div>
  <table border="0" cellpadding="0" cellspacing="0" border="0">
    <form action="/" method="get">
      <tr>
        <td valign="middle"><input type="text" name="keywords" value="<?php if(isset($_GET['keywords'])){ echo $_GET['keywords']; } ?>" size="25" class="formfield" />&nbsp;</td>
        <td valign="middle"><input type="image" src="images/buttons/search.gif" width="87" height="24" hspace="10" /></td>
      </tr>
      <input type="hidden" name="action" value="<?php echo $prefix; ?>.advanced_search_results" />
      <?php
        if(isset($_GET['group_id']))
        {
          echo '<input type="hidden" name="group_id" value="' . $_GET['group_id'] . '" />';
        }
      ?>
    </form>
  </table>
</div>
<?php
  if(strstr($action, 'advanced_search_form'))
  {
    $tpl->main($tpl->get());
    $tpl->clean();
  }
?>
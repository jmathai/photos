<?php
  $_width = $logged_in ? 545 : 720;
?>

<div class="dataSingleContent">
  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td><img src="images/pixel_md_grey.gif" height="1" width="<?php echo $_width; ?>" border="0" /></td>
    </tr>
    <tr>
      <td background="images/pixel_lt_grey.gif" class="f_8 f_black">
  	  <table cellpadding="0" cellspacing="0" border="0" width="<?php echo $_width; ?>">
  	  <form name="_faq" method="GET" style="display:inline;">
  	  <input type="hidden" name="action" value="member.help" />
  	    <tr>
  		  <td class="f_9 f_dark_accent bold" align="left" valign="middle">&nbsp;&nbsp;&nbsp;FAQ&nbsp;&nbsp;|&nbsp;&nbsp;<a href="/?action=home.help">Show All FAQs</a></td>
  		  <td align="right" width="150" valign="middle"><input type="text" name="search" <?php if(isset($_GET['search']) && !isset($_GET['hide_search'])){ echo 'value="' . htmlentities(implode(' ', array_unique(explode(' ', $_GET['search'])))) . '"'; } ?> style="width:120px;" class="formfield" />&nbsp;&nbsp;</td>
  		  <td align="right" width="100" valign="middle"><a href="javascript:document.forms['_faq'].submit();"><img src="images/buttons/search.gif" border="0" /></a>&nbsp;&nbsp;</td>
  		</tr>
        </form>
  	  </table>
  	</td>
    </tr>
    <tr>
      <td><img src="images/pixel_md_grey.gif" height="1" width="<?php echo $_width; ?>" border="0" /></td>
    </tr>
    <tr><td height="5"><img src="images/spacer.gif" width="1" height="5" /></td></tr>
  </table>
</div>
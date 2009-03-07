<?php
  // link to manage slideshow order
  echo '<div style="width:300px; height:30px; padding-top:5px; padding-left:25px;" class="f_12 bold"><img src="images/icons/png/manage_slideshows.png" class="png" border="0" width="24" height="22" /> Manage Slideshows</div>';
  echo '<div style="padding-top:5px; padding-left:25px;">';
  echo '<div id="manageSlideshows">';
  echo '<div style="float:left;">';
  echo '<div>All private slideshows</div>';
  echo '<iframe name="flix_configuration_private" id="flix_configuration_private" src="/popup/flix_manage_private/" frameborder="1" style="width:350px; height:250px;"></iframe>';
  echo '</div>';
  echo '<div>';
  echo '<div>Public slideshows</div>';
  echo '<iframe name="flix_configuration_public" id="flix_configuration_public" src="/popup/flix_manage_public/" frameborder="1" style="width:350px; height:250px;"></iframe>';
  echo '</div>';
  echo '</div>';
  echo '</div>';
?>
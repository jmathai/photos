<?php
  $g  =& CGroup::getInstance();
  $fr =& CForum::getInstance();
  
  if(isset($_GET['sort']))
  {
    $sort = $_GET['sort'] == 'name' ? 0 : 1;
  }
  else
  {
    $sort = 0;
  }
  
  $groups_array = $g->groups($_USER_ID, false, 'all', $sort);
  
  $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
?>

<script laguage="javascript">
  var _checked = ',';
</script>

<table border="0" cellpadding="0" cellspacing="0" width="545">
  <tr>
    <td><img src="images/pixel_md_grey.gif" height="1" width="545" border="0" /></td>
  </tr>
  <tr>
    <td background="images/pixel_lt_grey.gif" class="f_8 f_black">
      <table border="0" cellpadding="0" cellspacing="0" width="545">
        <tr>
          <td width="10"><img src="images/spacer.gif" width="10" height="15" border="0" /></td>
          <td align="left" valign="middle" width="370">You are currently active in <?php echo count($groups_array); ?> groups</td>
          <td align="right" valign="middle" width="50">sort by&nbsp;</td>
          <td align="left" valign="middle" width="110">
            <select class="formfield" onChange="location.href='/?action=<?php echo $action; ?>&sort=' + this.value;">
              <option value="0" <?php if($sort == 0){ echo 'SELECTED'; } ?>>Name</option>
              <option value="1" <?php if($sort == 1){ echo 'SELECTED'; } ?>>Last Modified</option>
            </select>
          </td>
          <td width="10"><img src="images/spacer.gif" width="10" height="25" border="0" /></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td><img src="images/pixel_md_grey.gif" height="1" width="545" border="0" /></td>
  </tr>
</table>

<br /><br />

<?php
  if(count($groups_array) > 0)
  {
    $i = 1;
    $popup_width  = FF_WEB_WIDTH + 10;
    $popup_height = FF_WEB_HEIGHT + 75;
    foreach($groups_array as $v)
    {
      if($limit < $i)
      {
        break;
      }
      
      $can_contribute = $g->canContribute($v['G_ID'], $_USER_ID, $v);
      $can_invite     = $g->canInvite($v['G_ID'], $_USER_ID, $v);
      $is_owner       = $g->isOwner($v['G_ID'], $_USER_ID, $v);
      
      $display = $i == 1 ? 'block' : 'none';
      $arrow   = $i == 1 ? 'open'  : 'close';
      
      $fotos_array  = $g->fotos($v['G_ID'], false, false, 0, 2);
      $stats_array  = $g->stats($v['G_ID'], $_USER_ID);
      $gp_us_info   = $g->fotosByMember($v['G_ID'], $_USER_ID);
      $forum_cnt    = $fr->countPosts($v['G_ID']);
      $forum_user_cnt = $fr->countPosts($v['G_ID'], $_USER_ID);
      
      $foto_count = count($gp_us_info);
      
      $foto_list = '';
      foreach($gp_us_info as $v2)
      {
        $foto_list .= ',' . $v2['P_ID'];
      }
      
      if($foto_count > 0)
      {
        $foto_src = '<a href="/?action=fotogroup.group_fotos&group_id=' . $v['G_ID'] . '&foto_ids=' . $foto_list . '&member_id=' . $_USER_ID . '" class="f_black">' . $foto_count . '</a>';
      }
      else
      {
        $foto_src = $foto_count;
      }
      
      if($v['G_DELETE'] == 0)
      {
        $css_class  = 'bg_medium';
        $markedForDelete = false;
      }
      else
      {
        $css_class  = 'bg_red';
        $markedForDelete = true;
      }
?>
      <img src="images/spacer.gif" width="545" height="2" border="0" /><br />
      <table border="0" cellpadding="0" cellspacing="0" width="545" height="35" class="<?php echo $css_class; ?>">
        <tr>
          <td align="center" valign="middle" width="40"><a href="javascript:_toggle('_group_main_<?php echo $v['G_ID']; ?>'); _toggle_arrow('_group_arrow_<?php echo $v['G_ID']; ?>');"><img src="images/navigation/sub_arrow_group_<?php echo $arrow; ?>.gif" id="_group_arrow_<?php echo $v['G_ID']; ?>" width="11" height="11" border="0" /></a></td>
          <td align="center" valign="middle" width="40"><img src="images/checkbox_unchecked.gif" name="_fotobox_checkbox_<?php echo $v['G_ID']; ?>" onClick="_toggle_image(this.name, 'images/' + _checkbox_image(this.src)); _checked = _track_checked('<?php echo $v['G_ID']; ?>', this.src, 'pass');" width="9" height="9" border="0" style="cursor:pointer;" /></td>
          <td align="center" valign="middle" width="60"><img src="images/group_header_small.gif" width="22" height="29" border="0" /></td>
          <td align="left" valign="middle" width="350"><a href="/?action=fotogroup.group_home&group_id=<?php echo $v['G_ID']; ?>" class="f_9 f_black bold"><?php echo $v['G_NAME']; ?></a></td>
          <td align="center" valign="middle" width="55">
            <?php
              if($is_owner)
              {
                echo '<a href="/?action=fotogroup.group_manage&group_id=' . $v['G_ID'] . '"><img src="images/icons/label_pencil.gif" width="16" height="22" hspace="2" border="0" /></a>';
                if($markedForDelete === false)
                {
                  echo '<a href="/?action=fotogroup.group_delete_form&group_id=' . $v['G_ID'] . '"><img src="images/icons/label_delete.gif" hspace="2" width="22" height="22" border="0" /></a>'; 
                }
                else
                {
                  echo '<img src="images/icons/label_delete_greyed.gif" hspace="2" width="22" height="22" border="0" />'; 
                }
              }
              else
              {
                echo '&nbsp;';
              }
            ?>
          </td>
          <td align="center" valign="middle" width="40"><img src="images/icons/label_lock.gif" width="11" height="13" border="0" /></td>
        </tr>
      </table>
      <div id="_group_main_<?php echo $v['G_ID']; ?>" style="display:<?php echo $display; ?>;">
        <table border="0" cellpadding="0" cellspacing="0" width="545" height="200" class="border_dark">
          <tr>
            <td width="280">
              <table border="0" cellpadding="5" cellspacing="0" width="225" height="100%">
                <tr>
                  <td align="left">
                    <table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td rowspan="2"><a href="/?action=fotogroup.members&group_id=<?php echo $v['G_ID']; ?>"><img src="images/icons/label_member.gif" width="13" height="24" border="0" hspace="3" /></a></td>
                        <td valign="middle" class="f_8 f_black">Members: <?php echo $g->memberCount($v['G_ID']); ?></td>
                      </tr>
                      <tr>
                        <td valign="middle" class="f_8 f_black">
                          Status:
                          <?php
                            if($is_owner)
                            {
                              echo 'Owner';
                            }
                            else
                            if($can_contribute)
                            {
                              echo 'Manager';
                            }
                            else
                            if($can_invite)
                            {
                              echo 'Contributor';
                            }
                            else
                            {
                              echo 'Visitor';
                            }
                            
                            if($can_contribute)
                            {
                              $my_stats = $g->stats($v['G_ID'], $_USER_ID);
                              $my_fotos = $g->fotosByMember($v['G_ID'], $_USER_ID);
                              $my_foto_ids = '';
                              foreach($my_fotos as $v2)
                              {
                                $my_foto_ids .= ',' . $v2['P_ID'];
                              }
                              
                              if($my_stats['COUNT_FOTOS_USER'] > 0)
                              {
                                echo '<br />Your Fotos: <a href="/?action=fotogroup.group_fotos&group_id=' . $v['G_ID'] . '&foto_ids=' . $my_foto_ids . '&member_id=' . $_USER_ID . '" class="f_black">' . $my_stats['COUNT_FOTOS_USER'] . '</a>';
                              }
                              else
                              {
                                echo '<br />Your Fotos: none';
                              }
                            }
                          ?>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td align="left">
                    <span class="f_8_mdgrey bold">Description:</span>
                    <br />
                    <span class="f_8 f_black"><?php echo (strlen($v['G_DESC']) > 0 ? $v['G_DESC'] : 'None'); ?></span>
                  </td>
                </tr>
                <tr>
                  <td>
                    <table border="0" cellpadding="0" cellspacing="5">
                      <tr>
                          <?php
                            foreach($fotos_array as $p)
                            {
                              echo '<td>
                                      <table border="0" cellpadding="0" cellspacing="0" width="87">
                                        <tr>
                                          <td colspan="3"><img src="images/fb_frame_top.gif" width="87" height="5" vspace="0" hspace="0" border="0" /></td>
                                        </tr>
                                        <tr>
                                          <td><img src="images/fb_frame_left.gif" width="5" height="75" vspace="0" hspace="0" border="0" /></td>
                                          <td><a href="/?action=fotogroup.image_show&group_id=' . $v['G_ID'] . '&image_id=' . $p['P_ID'] . '"><img src="' . PATH_FOTO . $p['P_THUMB_PATH'] . '?' . time() . '" ' . ' width="' . FF_THUMB_WIDTH . '" height="' . FF_THUMB_HEIGHT . '" hspace="0" vspace="0" border="0" /></a></td>
                                          <td><img src="images/fb_frame_right.gif" width="7" height="75" vspace="0" hspace="0" border="0" /></td>
                                        </tr>
                                        <tr>
                                          <td colspan="3"><img src="images/fb_frame_bottom.gif" width="87" height="7" vspace="0" hspace="0" border="0" /></td>
                                        </tr>
                                      </table>
                                    </td>';
                            }
                          ?>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
            <td width="1" bgcolor="#697281"><img src="images/spacer.gif" width="1" border="0" /></td>
            <td align="center" width="264">
              <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
                <tr height="35">
                  <td background="images/pixel_lt_grey.gif">&nbsp;</td>
                  <td background="images/pixel_lt_grey.gif">&nbsp;</td>
                  <td background="images/pixel_lt_grey.gif" class="f_8 f_black">Mine</td>
                  <td background="images/pixel_lt_grey.gif" class="f_8 f_black">Total</td>
                </tr>
                <tr>
                  <td colspan="4"><img src="images/pixel_dk_grey.gif" width="264" height="1" border="0" /></td>
                </tr>
                <tr height="35">
                  <td><img src="images/icons/label_forum.gif" width="19" height="19" border="0" /></td>
                  <td class="f_8 f_black">Forum Posts</td>
                  <td class="f_8 f_black"><?php echo $forum_user_cnt; ?></td>
                  <td class="f_8 f_black"><?php echo $forum_cnt; ?></td>
                </tr>
                <tr>
                  <td colspan="4"><img src="images/pixel_dk_grey.gif" width="264" height="1" border="0" /></td>
                </tr>
                <tr height="35">
                  <td background="images/pixel_lt_grey.gif"><a href="/?action=fotogroup.group_fotos&group_id=<?php echo $v['G_ID']; ?>"><img src="images/icons/label_foto.gif" width="24" height="20" border="0" /></a></td>
                  <td background="images/pixel_lt_grey.gif" class="f_8 f_black">Fotos</td>
                  <td background="images/pixel_lt_grey.gif" class="f_8 f_black"><?php echo $foto_src; ?></td>
                  <td background="images/pixel_lt_grey.gif" class="f_8 f_black"><?php echo $stats_array['COUNT_FOTOS']; ?></td>
                  </tr>
                <tr>
                  <td colspan="4"><img src="images/pixel_dk_grey.gif" width="264" height="1" border="0" /></td>
                </tr>
                <tr height="35">
                  <td><a href="/?action=fotogroup.flix_list&group_id=<?php echo $v['G_ID']; ?>"><img src="images/icons/label_flix.gif" width="22" height="17" border="0" /></a></td>
                  <td class="f_8 f_black">Flix</td>
                  <td class="f_8 f_black"><?php echo $stats_array['COUNT_FLIX_USER']; ?></td>
                  <td class="f_8 f_black"><?php echo $stats_array['COUNT_FLIX']; ?></td>
                </tr>
                <tr>
                  <td colspan="4"><img src="images/pixel_dk_grey.gif" width="264" height="1" border="0" /></td>
                </tr>
                <tr height="35">
                  <td background="images/pixel_lt_grey.gif"><img src="images/icons/label_game.gif" width="22" height="17" border="0" /></td>
                  <td background="images/pixel_lt_grey.gif" class="f_8 f_black">Games</td>
                  <td background="images/pixel_lt_grey.gif" class="f_8 f_black">0</td>
                  <td background="images/pixel_lt_grey.gif" class="f_8 f_black">0</td>
                </tr>
                <tr>
                  <td colspan="4"><img src="images/pixel_dk_grey.gif" width="264" height="1" border="0" /></td>
                </tr>
                <tr height="35">
                  <td><img src="images/icons/label_dvd.gif" width="30" height="18" border="0" /></td>
                  <td class="f_8 f_black">DVDs</td>
                  <td class="f_8 f_black">0</td>
                  <td class="f_8 f_black">0</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </div>
<?php
      $i++;
    }
  
    if($limit < count($groups_array))
    {
      echo '<table border="0" cellpadding="0" cellspacing="0" width="545">
              <tr><td>&nbsp;</td></tr>
              <tr>
                <td align="left">Showing ' . $limit . ' of ' . count($groups_array) . ' [<a href="/?action=' . $action . '&limit=' . count($groups_array) . '">show all</a>]</td>
              </tr>
            </table>';
    }
?>
    <table border="0" cellpadding="0" cellspacing="0" width="545">
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" width="545" height="20" class="border_complete">
      <tr>
        <td width="169">&nbsp;</td>
        <td background="images/column_divider.gif" rowspan="2"><img src="images/spacer.gif" width="2" height="1" /></td>
        <td valign="middle" align="left" width="205" style="cursor: pointer;" onClick="if(_checked.length > 1){ location.href='/?action=fotogroup.group_leave_form&ids='+_checked; }else{ alert('Please select the group(s) you would like to leave.'); }" style="cursor: pointer;">
          <table border="0">
            <tr>
              <td><img src="images/icons/label_unshare.gif" width="22" height="22" hspace="3" border="0" /></td>
              <td valign="middle">Remove me from this group</td>
            </tr>
          </table>
        </td>
        <td background="images/column_divider.gif" rowspan="2"><img src="images/spacer.gif" width="2" height="1" /></td>
        <td width="169">&nbsp;</td>
      </tr>
    </table>
<?php
  }
  else
  {
    include PATH_DOCROOT . '/first_time.dsp.php';
  }
  
  include_once PATH_DOCROOT . '/ads_horizontal.dsp.php';
  
  $tpl->main($tpl->get());
  $tpl->clean();
?>
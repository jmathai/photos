<?php
  include_once './init_constants.php';
  include_once PATH_INCLUDE . '/functions.php';
  include_once PATH_INCLUDE . '/variables.php';
  include_once PATH_HOMEROOT . '/init.php';

  // headers
  header('pragma: no-cache');
  header('Cache-Control: no-store, no-cache, must-revalidate');
  header('Cache-Control: post-check=0, pre-check=0', false);
  header('Last-Modified: Mon, 16 May 2005 00:00:00 GMT');

  include_once PATH_DOCROOT . '/init_database.php';

  include_once PATH_CLASS . '/CSession.php';
  include_once PATH_DOCROOT . '/init_session.php';

  $action_default = $logged_in === true ? 'fotobox.fotobox_main' : 'home.visitor';
  $action = !isset($_GET['action']) ? $action_default : $_GET['action'];

  if($action == 'home.main' || $action == 'home.visitor' || $action == 'home.member')
  {
    include_once PATH_HOMEROOT . '/home';
    die();
  }

  if(strncmp($action, 'my.', 3) == 0)
  {
    $my_page = true;
  }

  $action_ext = substr($action, -4);

  include_once PATH_CLASS . '/CTemplate.php';

  $mode   = !isset($pageModes[$action]) ? 'double' : $pageModes[$action];

  $error = false;

  $tpl =& new CTemplate;
  
  if($action_ext != '.act')
  {
    include_once PATH_DOCROOT . '/header.dsp.php';
  }

  //$tpl->start();
  switch($action)
  {
    /*** ACCOUNT ***/
    case 'account.account_main':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/myaccount.dsp.php';
      break;
    case 'account.add_space_form':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/add_space.frm.php';
      break;
    case 'account.billing_update_form':
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_CLASS . '/CToken.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/account_billing_update.frm.php';
      break;
    case 'account.billing_confirm':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_DOCROOT . '/account_billing_confirm.dsp.php';
      break;
    case 'account.cancel_form':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/account_cancel.frm.php';
      break;
    case 'account.cancel_confirm':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/account_cancel_confirm.dsp.php';
      break;
    case 'account.earn_free_storage':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/earn_free_storage.frm.php';
      break;
    case 'account.earn_free_storage_stats':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/earn_free_storage_stats.dsp.php';
      break;
    case 'account.incomplete_user_response';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/account_incomplete_user_response.dsp.php';
      break;
    case 'account.order_details':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_DOCROOT . '/account_order_details.dsp.php';
      break;
    case 'account.password_form':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/account_password.frm.php';
      break;
    case 'account.profile_form':
    case 'account.registration_form':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include PATH_DOCROOT . '/account_profile.frm.php';
      break;
    case 'account.renew':
      include_once PATH_DOCROOT . '/account_renew.dsp.php';
      break;
    case 'account.space_usage':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CPaging.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/space_usage.dsp.php';
      break;
    case 'home.upgrade_form':
    case 'account.upgrade_form':
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_DOCROOT . '/account_upgrade.frm.php';
      break;

    /*** BOARD ***/
    case 'board.board_post':
      include_once PATH_CLASS . '/CBoard.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CPaging.php';
      include_once PATH_DOCROOT . '/board_post.dsp.php';
      break;
    case 'board.board_view':
      include_once PATH_CLASS . '/CBoard.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CPaging.php';
      include_once PATH_DOCROOT . '/board_view.dsp.php';
      break;
    case 'board.main':
      include_once PATH_CLASS . '/CBoard.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/board_main.dsp.php';
      break;
    case 'board.new_topic':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CBoard.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/board_new_topic.frm.php';
      break;
    case 'board.reply':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CBoard.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/board_reply.frm.php';
      break;

    /*** CALENDAR **/
    case 'calendar.view':
      include_once PATH_DOCROOT . '/calendar_view.dsp.php';
      break;

    /*** CART ***/
    case 'cart.view':
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/cart_view.dsp.php';
      break;
    case 'cart.checkout_form':
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/cart_checkout.frm.php';
      break;
    case 'cart.checkout_confirmation':
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_DOCROOT . '/cart_checkout_confirmation.dsp.php';
      break;

    /*** CITIZEN IMAGE ***/
    case 'ci.agreements':
      include_once PATH_DOCROOT . '/ci_agreements.dsp.php';
      break;
    case 'ci.confirmation':
      include_once PATH_DOCROOT . '/ci_confirmation.dsp.php';
      break;
    case 'ci.home_page':
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/ci_home_page.dsp.php';
      break;
    case 'ci.register':
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CCitizenImage.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/ci_register.dsp.php';
      break;
    case 'ci.start':
      include_once PATH_DOCROOT .'/ci_start.dsp.php';
      break;
    case 'ci.upload_images':
      include_once PATH_CLASS . '/CToolbox.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/ci_upload_images.dsp.php';
      break;
    
    case 'facebook.home':
      include_once PATH_DOCROOT . '/require_login.dsp.php'; // for now until we have a public area
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CFacebook.php';
      include_once PATH_DOCROOT . '/facebook_home.dsp.php';
      break;

    /*** FLIX ***/
    case 'flix.dvd_reorder':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_DOCROOT . '/dvd_reorder.dsp.php';
      break;
    case 'flix.dvd_select':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/dvd_select.dsp.php';
      break;
    case 'flix.dvd_create':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/dvd_create.dsp.php';
      break;
    case 'flix.fastflix':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/fastflix.dsp.php';
      break;
    case 'flix.flix_create_prompt':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_DOCROOT . '/flix_create_prompt.dsp.php';
      break;
    case 'flix.flix_post':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/flix_post.dsp.php';
      break;
    case 'flix.flix_post_message':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/flix_post_message.dsp.php';
      break;
    case 'flix.flix_delete_form':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/flix_delete.frm.php';
      break;
    case 'flix.flix_form':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CToolbox.php';
      include_once PATH_DOCROOT . '/flix.frm.php';
      break;
    case 'flix.flix_list':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_DOCROOT . '/permission.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CPaging.php';
      include_once PATH_DOCROOT . '/flix_list.dsp.php';
      break;
    case 'flix.flix_successful':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/flix_successful.dsp.php';
      break;
    case 'flix.gallery_generator':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_DOCROOT . '/flix_gallery_generator.dsp.php';
      break;
    case 'flix.html_slideshow':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CUser.php';
      //include_once PATH_DOCROOT . '/flix_html_slideshow.dsp.php';
      break;
    case 'flix.manage':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_DOCROOT . '/flix_manage.dsp.php';
      break;
    case 'flix.schedule':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_DOCROOT . '/flix_schedule.dsp.php';
      break;
    case 'flix.stats':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CReport.php';
      include_once PATH_DOCROOT . '/reports_report_main.dsp.php';
      break;
    case 'flix.view_all_tags':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CTag.php';
      include_once PATH_DOCROOT . '/flix_viewtags.dsp.php';
      break;

    /*** FOTOBOX ***/
    case 'fotobox.advanced_search_form':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/advanced_search.frm.php';
      break;
    case 'fotobox.advanced_search_results':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/advanced_search.frm.php';
      include_once PATH_DOCROOT . '/advanced_search_results.dsp.php';
      break;
    case 'fotobox.all_sizes':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_DOCROOT . '/fotobox_all_sizes.dsp.php';
      break;
    case 'fotobox.calendar':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_DOCROOT . '/calendar_view.dsp.php';
      break;
    case 'fotobox.main': // backwards compatability
    case 'fotobox.fotobox_main':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/fotobox_main.dsp.php';
      break;
    case 'fotobox.fotobox_myfotos':
    case 'fotobox.fotobox_myfotos_advanced':
    case 'fotobox.fotobox_myfotos_create_flix':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/fotobox_myfotos.dsp.php';
      break;
    case 'fotobox.foto_post':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/foto_post.dsp.php';
      break;
    case 'fotobox.foto_post_message':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/foto_post_message.dsp.php';
      break;
    case 'fotobox.foto_viewer':
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/foto_viewer.dsp.php';
      break;
    case 'fotobox.no_space_left':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/fotobox_no_space_left.dsp.php';
      break;
    case 'fotobox.save_to_fotobox_form':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/save_to_fotobox.frm.php';
      include_once PATH_DOCROOT . '/fotobox_view.dsp.php';
      break;
    case 'fotobox.stats':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CReport.php';
      include_once PATH_DOCROOT . '/reports_report_main.dsp.php';
      break;
    case 'fotobox.upload_flash':
    case 'fotobox.upload_form':
    case 'fotobox.upload_installer':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/upload_flash.dsp.php';
      break;
    case 'fotobox.upload_form_compat':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/upload.frm.php';
      break;
    case 'fotobox.upload_form_html':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CTag.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/upload_html.frm.php';
      break;
    case 'fotobox.upload_successful':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/fotobox_upload_successful.dsp.php';
      break;
    case 'fotobox.view_all_tags':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CTag.php';
      include_once PATH_DOCROOT . '/fotobox_viewtags.dsp.php';
      break;

    /*** GROUP ***/
    case 'group.approve':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/group_approve.dsp.php';
      break;
    case 'group.board_post':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CBoard.php';
      include_once PATH_CLASS . '/CPaging.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_DOCROOT . '/group_board_post.dsp.php';
      break;
    case 'group.board_main':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CBoard.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CPaging.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/group_board_main.dsp.php';
      break;
    case 'group.board_new_topic':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CBoard.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/group_board_new_topic.frm.php';
      break;
    case 'group.board_reply':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CBoard.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/group_board_reply.frm.php';
      break;
    case 'group.calendar':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_DOCROOT . '/group_calendar_view.dsp.php';
      break;
    case 'group.home':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CBoard.php';
      include_once PATH_DOCROOT . '/group_home.dsp.php';
      break;
    case 'group.main':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      break;
    case 'group.member_accept':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_DOCROOT . '/group_member_accept.dsp.php';
      break;
    case 'group.member_request':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_DOCROOT . '/group_member_request.dsp.php';
      break;
    case 'group.members':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CPaging.php';
      include_once PATH_DOCROOT . '/group_members.dsp.php';
      break;
    case 'group.photo':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CFotoboxManage.php';
      include_once PATH_CLASS . '/CComment.php';
      include_once PATH_DOCROOT . '/group_photo.dsp.php';
      break;
    case 'group.photo_large':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/group_photo_large.dsp.php';
      break;
    case 'group.photos':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/group_photos.dsp.php';
      break;
    case 'group.send_message':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_DOCROOT . '/permission.dsp.php';
      include_once PATH_DOCROOT . '/group_send_message.dsp.php';
      break;
    case 'group.settings':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_DOCROOT . '/permission.dsp.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/group_settings.dsp.php';
      break;
    case 'group.slideshows':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_DOCROOT . '/permission.dsp.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CPaging.php';
      include_once PATH_DOCROOT . '/group_slideshows.dsp.php';
      break;

    /*** HOME ***/
    case 'home.aboutus';
      include_once PATH_DOCROOT . '/aboutus.dsp.php';
      break;
    case 'home.contactus';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/contactus.dsp.php';
      break;
    case 'home.fotoflix_logos':
      include_once PATH_DOCROOT . '/fotoflix_logos.dsp.php';
      break;
    case 'home.help':
    case 'member.help':
      include_once PATH_CLASS . '/CFaq.php';
      include_once PATH_DOCROOT . '/faq.dsp.php';
      break;
    case 'home.main':
    case 'home.member':
    case 'home.visitor':
      include_once PATH_DOCROOT . '/home.dsp.php';
      break;
    case 'home.login_form':
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/login.frm.php';
      break;
    case 'home.login_sub_form':
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/login_sub.frm.php';
      break;
    case 'home.more_info':
      include_once PATH_DOCROOT . '/home_more_info.dsp.php';
      break;
    case 'home.import_offer':
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/home_import_offer.dsp.php';
      break;
    case 'home.ipod_giveaway':
      include_once PATH_DOCROOT . '/home_ipod_giveaway.dsp.php';
      break;
    case 'home.ipod_registration':
      include_once PATH_DOCROOT . '/home_ipod_registration.dsp.php';
      break;
    case 'home.ipod_rules':
      include_once PATH_DOCROOT . '/home_ipod_rules.dsp.php';
      break;
    case 'home.password_reset_form':
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/password_reset.frm.php';
      break;
    case 'home.print':
      include_once PATH_DOCROOT . '/home_print.dsp.php';
      break;
    case 'home.privacy';
      include_once PATH_DOCROOT . '/privacy.dsp.php';
      break;
    case 'home.registration_form':
    case 'home.registration_form_a':
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/registration_a.dsp.php';
      break;
    case 'home.registration_form_b':
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/registration_b.frm.php';
      break;
    case 'home.registration_form_b2':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/registration_b2.frm.php';
      break;
    case 'home.samples':
      if(file_exists($src = PATH_DOCROOT . '/home_samples_' . $_GET['subaction'] . '.dsp.php'))
      {
        include_once $src;
      }
      else
      {
        include_once PATH_DOCROOT . '/home_samples.dsp.php';
      }
      break;
    case 'home.site_requirements':
      include_once PATH_DOCROOT . '/site_requirements.dsp.php';
      break;
    case 'home.slideshow_share':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/home_slideshow_share.dsp.php';
      break;
    case 'home.terms';
      include_once PATH_DOCROOT . '/terms.dsp.php';
      break;
    case 'home.terms_sale';
      include_once PATH_DOCROOT . '/terms_sale.dsp.php';
      break;
    case 'home.unsubscribe':
      include_once PATH_DOCROOT . '/unsubscribe.dsp.php';
      break;

    /*** MANAGE ***/
    case 'manage.user_sub_form':
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/manage_user_sub.frm.php';
      break;
    case 'manage.accounts':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/manage_accounts.dsp.php';
      break;

    /*** MESSAGING ***/
    /*case 'messaging.message':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CPrivateMessage.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/messaging_message.dsp.php';
      break;
    case 'messaging.home':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CPrivateMessage.php';
      include_once PATH_CLASS . '/CPaging.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/messaging_home.dsp.php';
      break;*/
    case 'messaging.inbox':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/messaging_inbox.dsp.php';
      break;

    /*** MY ***/
    case 'my.page':
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CTag.php';
      // included in header.dsp.php ~ include_once PATH_DOCROOT . '/my_check.dsp.php';
      switch($subaction)
      {
        case 'blog':
          switch($subsubaction)
          {
            case 'add_entry':
              include_once PATH_CLASS . '/CBlog.php';
              include_once PATH_DOCROOT . '/my_blog_add_entry.frm.php';
              break;
            case 'entry':
              include_once PATH_CLASS . '/CBlog.php';
              include_once PATH_CLASS . '/CComment.php';
              include_once PATH_DOCROOT . '/my_blog_entry.dsp.php';
              break;
            default:
              include_once PATH_CLASS . '/CBlog.php';
              include_once PATH_DOCROOT . '/my_blog.dsp.php';
              break;
          }
          break;
        case 'dne':
          include_once PATH_DOCROOT . '/my_dne.dsp.php';
          break;
        case 'friends':
          include_once PATH_CLASS . '/CUser.php';
          include_once PATH_DOCROOT . '/my_friends.dsp.php';
          break;
        case 'html_slideshow':
          include_once PATH_CLASS . '/CFlix.php';
          include_once PATH_CLASS . '/CFotobox.php';
          include_once PATH_CLASS . '/CUser.php';
          include_once PATH_DOCROOT . '/my_html_slideshow.dsp.php';
          break;
        case 'network':
          switch($subsubaction)
          {
            case 'tags':
              include_once PATH_CLASS . '/CUser.php';
              include_once PATH_CLASS . '/CFotobox.php';
              include_once PATH_DOCROOT . '/my_network_tags.dsp.php';
              break;
          }
          break;
        case 'slideshows':
          include_once PATH_CLASS . '/CPaging.php';
          include_once PATH_CLASS . '/CFlix.php';
          include_once PATH_DOCROOT . '/my_slideshows.dsp.php';
          break;
        case 'photo':
          include_once PATH_CLASS . '/CUser.php';
          include_once PATH_CLASS . '/CUserManage.php';
          include_once PATH_CLASS . '/CComment.php';
          include_once PATH_CLASS . '/CFotobox.php';
          include_once PATH_CLASS . '/CFotoboxManage.php';
          include_once PATH_DOCROOT . '/my_foto.dsp.php';
          break;
        case 'photos':
          include_once PATH_CLASS . '/CFotobox.php';
          include_once PATH_CLASS . '/CPaging.php';
          include_once PATH_DOCROOT . '/my_fotos.dsp.php';
          break;
        case 'photo-large':
          include_once PATH_CLASS . '/CFotobox.php';
          include_once PATH_DOCROOT . '/my_foto_large.dsp.php';
          break;
        case 'password':
          include_once PATH_DOCROOT . '/my_password.dsp.php';
          break;
        case 'profile':
          include_once PATH_CLASS . '/CUser.php';
          include_once PATH_DOCROOT . '/my_profile.dsp.php';
          break;
        case 'settings':
          include_once PATH_CLASS . '/CUser.php';
          include_once PATH_CLASS . '/CSubscription.php';
          include_once PATH_DOCROOT . '/my_settings.dsp.php';
          break;
          break;
        case 'tags':
          include_once PATH_CLASS . '/CTag.php';
          include_once PATH_CLASS . '/CFlix.php';
          include_once PATH_CLASS . '/CFotobox.php';
          include_once PATH_DOCROOT . '/my_tags.dsp.php';
          break;
        case 'videos':
          include_once PATH_CLASS . '/CVideo.php';
          include_once PATH_DOCROOT . '/my_videos.dsp.php';
          break;


        case 'home':
        default:
          include_once PATH_CLASS . '/CUser.php';
          include_once PATH_CLASS . '/CFotobox.php';
          include_once PATH_DOCROOT . '/my_home.dsp.php';
          break;
      }
      break;
    
    /** NETWORK **/
    case 'network.overview':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/network_overview.dsp.php';
      break;
      
    /** PRIVATE MESSAGES **/
    case 'pm.inbox':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CPrivateMessage.php';
      include_once PATH_CLASS . '/CPaging.php';
      include_once PATH_DOCROOT . '/pm_inbox.dsp.php';
      break;
    case 'pm.main':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CPrivateMessage.php';
      include_once PATH_DOCROOT . '/pm_main.dsp.php';
      break;
    case 'pm.message':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CPrivateMessage.php';
      include_once PATH_DOCROOT . '/pm_message.dsp.php';
      break;
    case 'pm.outbox':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CPrivateMessage.php';
      include_once PATH_CLASS . '/CPaging.php';
      include_once PATH_DOCROOT . '/pm_outbox.dsp.php';
      break;
    
    /** PRESS **/
    case 'press.citizen_image_03-12-2007':
      include_once PATH_DOCROOT . '/press_citizen_image_03-12-2007.dsp.php';
      break;
    case 'press.release_04-16-2007':
      include_once PATH_DOCROOT . '/press_release_04-16-2007.dsp.php';
      break;
    
    /** PRINTING **/
    case 'printing.home':
      include_once PATH_DOCROOT . '/printing_home.dsp.php';
      break;

    /** PUBLIC ***/
    case 'public.flix_list':
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/public_flix_list.dsp.php';
      break;
    case 'public.foto_show':
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_DOCROOT . '/public_foto_show.dsp.php';
      break;

    /*** REPORTS ***/
    case 'reports.report_archive':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CReport.php';
      include_once PATH_DOCROOT . '/reports_report_archive.dsp.php';
      break;
    case 'reports.report_archive_all':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CReport.php';
      include_once PATH_CLASS . '/CPaging.php';
      include_once PATH_DOCROOT . '/reports_report_archive_all.dsp.php';
      break;
    case 'reports.report_main':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CReport.php';
      include_once PATH_DOCROOT . '/reports_report_main.dsp.php';
      break;

    /*** SEARCH ***/
    case 'search.members':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_DOCROOT . '/search_members.frm.php';
      break;
    case 'search.members_results':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CPaging.php';
      include_once PATH_DOCROOT . '/search_members.frm.php';
      include_once PATH_DOCROOT . '/search_members_results.dsp.php';
      break;

    /*** SUBSCRIPTIONS ***/
    case 'subscriptions.home':
      include_once PATH_CLASS . '/CSubscription.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/subscriptions_home.dsp.php';
      break;

    /*** VIDEO ***/
    case 'video.list':
      include_once PATH_CLASS . '/CVideo.php';
      include_once PATH_DOCROOT . '/video_list.dsp.php';
      break;
    case 'video.upload_form':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CVideo.php';
      include_once PATH_CLASS . '/CFormValidator.php';
      include_once PATH_DOCROOT . '/video_upload.frm.php';
      break;

    /*** .act ***/
    /*** ACCOUNT ***/
    case 'account.auto_login.act':
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/account_auto_login.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'account.billing_update_form.act':
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/account_billing_update.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'account.cancel_form.act':
      include_once PATH_CLASS . '/CMail.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/account_cancel.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'account.incomplete_user_response.act';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/account_incomplete_user_response.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'account.password_form.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/account_password.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'account.profile_form.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/account_profile.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'account.preference_set.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/preference_set.act.php';
      header('Location: ' . $url);
      die();
      break;

    case 'beta.redeem.act':
      include_once PATH_DOCROOT . '/beta_redeem.act.php';
      header('Location: ' . $url);
      die();
      break;

    /*** BOARD ***/
    case 'board.reply.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CBoard.php';
      include_once PATH_DOCROOT . '/board_reply.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'board.new_topic.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CBoard.php';
      include_once PATH_DOCROOT . '/board_new_topic.act.php';
      header('Location: ' . $url);
      die();
      break;

    case 'cart.add.act':
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_DOCROOT . '/cart_add.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'cart.update.act':
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_DOCROOT . '/cart_update.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'cart.checkout.act':
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_CLASS . '/CMail.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/cart_checkout.act.php';
      if(isset($code_execute))
      {
        echo $code_execute;
      }
      else
      {
        header('Location: ' . $url);
      }
      die();
      break;

    /*** CITIZEN IMAGE ***/
    case 'ci.home.act':
      include_once PATH_CLASS . '/CCitizenImage.php';
      include_once PATH_DOCROOT . '/ci_home.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'ci.save.act':
      include_once PATH_CLASS . '/CCitizenImage.php';
      include_once PATH_DOCROOT . '/ci_save.act.php';
      header('Location: ' . $url);  // file might die before this and not redirect
      die();
      break;
    case 'ci.start.act':
      include_once PATH_CLASS . '/CCitizenImage.php';
      include_once PATH_DOCROOT . '/ci_start.act.php';
      header('Location: ' . $url);
      die();
      break;

    /*** FLIX ***/
    // flix.comment.act is above fotobox.comment.act
    //case 'flix.comment.act':
    case 'flix.comment_forward.act':
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_DOCROOT . '/flix_comment_forward.act.php';
      header('Location: ' . $url);
      break;
    case 'flix.flix_click.act':
      include_once PATH_CLASS . '/CLogging.php';
      include_once PATH_DOCROOT . '/flix_click.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'flix.flix_duplicate.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CFlixManage.php';
      include_once PATH_DOCROOT . '/flix_duplicate.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'flix.flix_delete.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFlixManage.php';
      include_once PATH_DOCROOT . '/flix_delete.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'flix.flix_reorder.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFlixManage.php';
      include_once PATH_DOCROOT . '/flix_reorder.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'flix.flix_manage_privacy.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CFlixManage.php';
      include_once PATH_CLASS . '/CFotoboxManage.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/flix_manage_privacy.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'flix.flix_privacy.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CFlixManage.php';
      include_once PATH_CLASS . '/CFotoboxManage.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/flix_privacy.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'flix.tags_refresh.act':
      include_once PATH_CLASS . '/CTag.php';
      include_once PATH_DOCROOT . '/flix_tags_refresh.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'flix.flix_view.act':
    case 'flix.flix_view_complete.act':
      include_once PATH_CLASS . '/CFlixManage.php';
      include_once PATH_DOCROOT . '/flix_view_complete.act.php';
      die();
      break;

    /*** FOTOBOX ***/
    case 'fotobox.avatar.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/fotobox_avatar.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'flix.comment.act':
    case 'fotobox.comment.act':
    case 'comment.act':
      include_once PATH_CLASS . '/CComment.php';
      include_once PATH_CLASS . '/CBlog.php'; // incrementCount
      include_once PATH_DOCROOT . '/comment.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'flix.flix_blog_api.act':
    case 'fotobox.foto_blog_api.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CApiClient.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_CLASS . '/CFotoboxManage.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CFlix.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/flix_blog_api.act.php';
      header('Location: ' . $url);
      die();
      break;
    // case 'fotobox.foto_blog_api.act': this case is above flix.flix_blog_api.act
    case 'fotobox.foto_view.act':
      include_once PATH_CLASS . '/CFotoboxManage.php';
      include_once PATH_DOCROOT . '/foto_view.act.php';
      die();
      break;
    case 'fotobox.group_form.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CGroupManage.php';
      include_once PATH_CLASS . '/CForumManage.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CMail.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CBoard.php';
      include_once PATH_DOCROOT . '/group.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'fotobox.mp3_delete.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFotoboxManage.php';
      include_once PATH_DOCROOT . '/mp3_delete.act.php';
      //echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=' . $url . '">';
      header('Location: ' . $url);
      die();
      break;
    case 'fotobox.mp3_update.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CFotoboxManage.php';
      include_once PATH_DOCROOT . '/mp3_update.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'fotobox.mp3_upload_finalize.act': // DO NOT REQUIRE LOGIN (PERL SCRIPT POST DOES NOT PRESERVE SESSION)
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CFotoboxManage.php';
      include_once PATH_DOCROOT . '/mp3_upload_finalize.act.php';
      //header('Location: ' . $url);
      echo '<script language="javascript"> location.href="' . $url . '"; </script>';
      die();
      break;
    case 'fotobox.tags_refresh.act':
      include_once PATH_CLASS . '/CTag.php';
      include_once PATH_DOCROOT . '/fotobox_tags_refresh.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'fotobox.toolbox_add.act':
    case 'fotobox.toolbox_add_slideshow.act':
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_CLASS . '/CFotobox.php';
      include_once PATH_CLASS . '/CToolbox.php';
      include_once PATH_DOCROOT . '/fotobox_toolbox_add.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'fotobox.upload_auto_choose.act':
      include_once PATH_DOCROOT . '/upload_auto_choose.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'fotobox.upload_preference_clear.act':
      include_once PATH_DOCROOT . '/upload_preference_clear.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'fotobox.upload_preference_set.act':
      include_once PATH_DOCROOT . '/upload_preference_set.act.php';
      header('Location: ' . $url);
      die();
      break;

    /*** GROUP ***/
    case 'group.board_new_topic.act':
      include_once PATH_CLASS . '/CBoard.php';
      include_once PATH_DOCROOT . '/group_board_new_topic.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'group.board_reply.act':
      include_once PATH_CLASS . '/CBoard.php';
      include_once PATH_DOCROOT . '/group_board_reply.act.php';
      header('Location: ' . $url);
      die();
      break;
    case'group.member_accept.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CGroupManage.php';
      include_once PATH_DOCROOT . '/group_member_accept.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'group.send_message.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CPrivateMessage.php';
      include_once PATH_DOCROOT . '/group_send_message.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'group.settings.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CGroupManage.php';
      include_once PATH_DOCROOT . '/group_settings.act.php';
      header('Location: ' . $url);
      die();
      break;

    /*** HOME ***/
    case 'home.contactus.act';
      include_once PATH_CLASS . '/CMail.php';
      include_once PATH_DOCROOT . '/contactus.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'home.password_reset_form.act':
      include_once PATH_CLASS . '/CMail.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/password_reset.act.php';
      header('Location: ' . $url);
      break;
    case 'home.registration_form_b.act':
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CMail.php';
      include_once PATH_CLASS . '/CIdat.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CGroupManage.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/registration_b.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'home.registration_form_b2.act':
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CMail.php';
      include_once PATH_CLASS . '/CEcom.php';
      include_once PATH_CLASS . '/CIdat.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CGroupManage.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/registration_b2.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'home.slideshow_share.act':
      include_once PATH_CLASS . '/CMail.php';
      include_once PATH_DOCROOT . '/home_slideshow_share.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'home.unsubscribe.act':
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/unsubscribe.act.php';
      header('Location: ' . $url);
      die();
      break;
    
    /*** MANAGE ***/
    case 'manage.account_create.act':
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/manage_account_create.act.php';
      header('Location: ' . $url);
      die();
      break;
    
    /*** MEMBER ***/
    case 'member.login_form.act':
      include_once PATH_CLASS . '/CSession.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CGroup.php';
      include_once PATH_CLASS . '/CGroupManage.php';
      include PATH_DOCROOT . '/login.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'member.login_sub_form.act':
      include_once PATH_CLASS . '/CSession.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/login_sub.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'member.logout.act':
      include_once PATH_DOCROOT . '/logout.act.php';
      header('Location: ' . $url);
      die();
      break;

    /*** MESSAGING ***/
    case 'messaging.home.act':
      include_once PATH_CLASS . '/CPrivateMessage.php';
      include_once PATH_DOCROOT . '/messaging_home.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'messaging.message.act':
      include_once PATH_CLASS . '/CPrivateMessage.php';
      include_once PATH_DOCROOT . '/messaging_message.act.php';
      header('Location: ' . $url);
      die();
      break;

    /*** MYPAGE ***/
    case 'my.blog_add_entry.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CBlog.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/my_blog_add_entry.act.php';
      header('Location: ' . $url);
      break;
    case 'my.blog_update_entry.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CBlog.php';
      include_once PATH_DOCROOT . '/my_blog_update_entry.act.php';
      header('Location: ' . $url);
      break;
    case 'my.page_settings.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_CLASS . '/CSubscriptionManage.php';
      include_once PATH_DOCROOT . '/my_settings.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'my.password.act':
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/my_password.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'my.profile.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/my_profile.act.php';
      header('Location: ' . $url);
      die();
      break;
      
    /*** NETWORK ***/
    case 'network.confirm_friend.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';    
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_CLASS . '/CUserManage.php';
      include_once PATH_DOCROOT . '/network_confirm_friend.act.php';
      header('Location: ' . $url);
      die();
      break;

    /*** NOTEPAD ***/
    case 'notepad.form.act':
      include_once PATH_CLASS . '/CNotepad.php';
      include_once PATH_DOCROOT . '/notepad_form.act.php';
      header('Location: ' . $url);
      die();
      break;
    
    /*** PRINTING ***/
    case 'printing.redirect.act':
      include_once PATH_DOCROOT . '/printing_redirect.act.php';
      header('Location: ' . $url);
      die();
      break;

    /*** PRIVATE MESSAGE ***/
    case 'pm.delete.act':
      include_once PATH_CLASS . '/CPrivateMessage.php';
      include_once PATH_DOCROOT . '/pm_delete.act.php';
      header('Location: ' . $url);
      die();
      break;
    
    /*** SECURE ***/
    case 'secure.login.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CUser.php';
      include_once PATH_DOCROOT . '/secure_login.act.php';
      header('Location: ' . $url);
      die();
      break;
      
    case 'video.delete.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CVideo.php';
      include_once PATH_DOCROOT . '/video_delete.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'video.update.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CVideo.php';
      include_once PATH_DOCROOT . '/video_update.act.php';
      header('Location: ' . $url);
      die();
      break;

    case 'reports.get_latest.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CReport.php';
      include_once PATH_DOCROOT . '/reports_get_latest.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'reports.report_main_add.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CReport.php';
      include_once PATH_DOCROOT . '/reports_report_main_add.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'reports.report_main_edit.act':
      include_once PATH_DOCROOT . '/require_login.dsp.php';
      include_once PATH_CLASS . '/CReport.php';
      include_once PATH_DOCROOT . '/reports_report_main_edit.act.php';
      header('Location: ' . $url);
      die();
      break;

    case 'subscriptions.home.act':
      include_once PATH_CLASS . '/CSubscriptionManage.php';
      include_once PATH_DOCROOT . '/subscriptions_home.act.php';
      header('Location: ' . $url);
      die();
      break;
    case 'subscriptions.remove.act':
      include_once PATH_CLASS . '/CSubscriptionManage.php';
      include_once PATH_DOCROOT . '/subscriptions_remove.act.php';
      header('Location: ' . $url);
      die();
      break;

    /*** CONFIRM ***/
    case 'confirm.main':
      switch($_GET['type'])
      {
        case 'account_expired':
          include_once PATH_DOCROOT . '/account_expired.dsp.php';
          break;
        case 'activation_email_sent':
          echo '<div align="center">We have sent an email to you with a link to activate your account.</div>';
          break;
        case 'error_general':
          echo '<div align="center"><div class="bold">Error Occurred!</div><br /><br />An error has occurred.  If you continue to have problems please contact us.</div>';
          break;
        case 'fastflix_dne':
          echo '<div align="center" width="500"><div class="bold">FastFlix Error!</div><br />Sorry, but we could not find the FastFlix you requested.  Possible reasons are:<table border="0"><tr><td align="left"><ol><li>The FotoFlix was deleted so this FastFlix is no longer valid.</li><li>The URL you followed was incorrectly typed or pasted into your browser.</li></ol></td></tr></table></div>';
          break;
        case 'group_deleted':
          echo '<div align="center" width="500"><div class="bold">Your request to delete a FotoGroup was successful.</div><br /><br />You have between 24 and 48 hours to use the <a href="/?action=fotogroup.groups_main">FotoGroup</a> and save <a href="/?action=fotogroup.groups_main">FotoGroup</a> fotos to your <a href="/?action=fotobox.fotobox_main">FotoBox</a>.</div>';
          break;
        case 'group_failed':
          echo '<div align="center" width="500"><div class="bold">You are not a member of this group.</div>';
          break;
        case 'incomplete_user_response':
          echo '<div class="center f_10 bold" style="margin-top:25px;"><img src="images/icons/checkmark_24x24.png" class="png" width="24" height="24" border="0" align="absmiddle" hspace="6" />Thank you for your additional feedback.</div>';
          break;
        case 'login_failed':
          include_once PATH_DOCROOT . '/login_failed.dsp.php';
          break;
        case 'login_not_active':
          include_once PATH_DOCROOT . '/login_not_active.dsp.php';
          break;
        case 'login_success':
          echo 'Your login was successful.<br /><br /><a href="/?action=fotobox.main">Click here to go to fotobox.</a>';
          break;
        case 'subscription_removed':
          echo '<div class="center f_10 bold" style="margin-top:25px;"><img src="images/icons/checkmark_24x24.png" class="png" width="24" height="24" border="0" align="absmiddle" hspace="6" />Your request was processed successfully.</div>';
          break;
        case 'user_key_already_activated':
          echo '<div style="width:733px;"><div class="bold">Account already activate</div><div>This account is already active.  You can <a href="/?action=home.login_form">login</a> using the username and password you registered with.  Please <a href="/contactus/">contact us</a> if you have questions.</div></div>';
          break;
        case 'user_key_not_found':
          echo '<div style="width:733px;"><div class="bold">Please verify your registration code</div><div>This registration key was not found in our system.  Your account may already be active and you might be able to <a href="/?action=home.login_form">login</a>.  If not then please check the link you clicked on or the URL you pasted into your browser.  You can <a href="/contactus/">contact us</a> if you have questions.</div></div>';
          break;
        case 'default':
          echo 'Default.';
          break;
      }
      break;

    case 'ff.phpinfo':
      phpinfo();
      break;
    case 'error':
    default:
      include PATH_DOCROOT . '/error.dsp.php';
      break;
  }

  include_once PATH_DOCROOT . '/footer.dsp.php';

  include_once PATH_DOCROOT . '/garbage_collector.act.php';
?>

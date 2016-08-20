<?php
/*
  Plugin Name: Spreadsheet Fetcher
  Plugin URI: http://meso.tokyo/spreadsheet-fetcher
  Description: スプレッドシートデータ埋め込みプラグイン
  Author: mesotokyo
  Version: 0.1
  Author URI: http://meso.tokyo/
*/

require (dirname(__FILE__) . '/admin/spsf_admin.php');
require (dirname(__FILE__) . '/admin/admin_functions.php');

/*
add_shortcode('session-entry-list', 'entrylist_shortcode_handler');
add_shortcode('session-member-list', 'memberlist_shortcode_handler');
add_shortcode('session-entry-history', 'entryhistory_shortcode_handler');
*/

/* 初期化 */
register_activation_hook(__FILE__, 'spsf_install');

/* 管理画面 */
/* admin menu -- impelmened in admin/spsf_admin.php */
add_action('admin_menu', 'sps_fetcher_menu');


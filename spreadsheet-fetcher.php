<?php
/*
  Plugin Name: Spreadsheet Fetcher
  Plugin URI: http://meso.tokyo/spreadsheet-fetcher
  Description: スプレッドシートデータ埋め込みプラグイン
  Author: mesotokyo
  Version: 0.1
  Author URI: http://meso.tokyo/
*/

require_once (dirname(__FILE__) . '/admin/admin.php');
require_once (dirname(__FILE__) . '/parser/tsv_parser.php');
require_once (dirname(__FILE__) . '/parser/common.php');

// [spsf_show slug="hogehoge"]
add_shortcode('spsf_show', 'spsf_show');

function spsf_show( $atts ) {
	if (!$atts["slug"]) {
		return "<span class='spsf_error'>error: no slug.</span>";
	}
	global $wpdb;
	$table_name = $wpdb->prefix . "spsfetcher";

	$slug = $atts["slug"];
	$sql = "SELECT * from $table_name WHERE slug = '$slug'";
	$sheet = $wpdb->get_row($sql, ARRAY_A);

	if (!$sheet) {
		return "<span class='spsf_error'>error: no sheet for slug: $slug.</span>";
	}

	return render_sps($sheet);
}

/* 初期化 */
register_activation_hook(__FILE__, 'spsf_install');

global $SPS_FETCHERT_DB_VERSION;
$SPS_FETCHERT_DB_VERSION = '1.0';

function spsf_install() {
	//create database table for this plugin
	global $wpdb;
	global $SPS_FETCHERT_DB_VERSION;
	
	$table_name = $wpdb->prefix . "spsfetcher";
	$charset_collate = $wpdb->get_charset_collate();
	
	$sql = <<<SQL_END
		CREATE TABLE $table_name (
		  id                 INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		  slug               VARCHAR(128) NOT NULL UNIQUE,
		  title              TEXT,
		  description        TEXT,
		  source_type        VARCHAR(128) NOT NULL,
		  source             TEXT NOT NULL,
		  template_header    TEXT,
		  template_body      TEXT,
		  template_footer    TEXT,
          options            TEXT,
		  PRIMARY KEY  (id)
		) $charset_collate;
SQL_END;

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta($sql);
	add_option('sps_fetcher_db_version', $SPS_FETCHERT_DB_VERSION);
}

/* 管理画面 */
/* admin menu -- impelmened in admin/spsf_admin.php */
add_action('admin_menu', 'sps_fetcher_menu');

function sps_fetcher_menu() {
	add_menu_page('Spreadsheet Fetcher',
				  'Spreadsheet',
				  'manage_options',
				  'sps-fetcher-options',
				  'sps_fetcher_options');
	
	add_submenu_page('sps-fetcher-options',
					 'Add Spreadsheet',
					 'Add Spreadsheet',
					 'manage_options',
					 'sps-fetcher-edit-page',
					 'sps_fetcher_edit_page');
}

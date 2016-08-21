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
require_once(WP_PLUGIN_DIR . "/spreadsheet-fetcher/tsv_parser.php" );


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

	$parser = new TSVParser();
	$opts = array();
	$row_opts = explode(',', $sheet["options"]);
	foreach($row_opts as $opt) {
		$kv = explode('=', $opt, 2);
		$opts[$kv[0]] = $kv[1];
	}
	$key_row = $opts["key"] ? $opts["key"] : 0;
	$start_row = $opts["start"] ? $opts["start"] : 0;
	$end_row = $opts["end"] ? $opts["end"] : 0;
		
	$parser->load($sheet["source"], $key_row, $start_row, $end_row);
	$result[] = $sheet["template_header"];
	$result[] = $parser->render($sheet["template_body"]);
	$result[] = $sheet["template_fotter"];
	return implode("\n", $result);
}

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


<?php

$ACCEPTABLE_KEYS = explode(" ", "id slug title description source_type source template_header template_body template_footer options");
$ACCEPTABLE_KEYS_NOID = explode(" ", "slug title description source_type source template_header template_body template_footer options");
$HIDDEN_FIELD_NAME = 'spsf_hidden';

require_once(WP_PLUGIN_DIR . "/spreadsheet-fetcher/parser/tsv_parser.php" );
require_once(WP_PLUGIN_DIR . "/spreadsheet-fetcher/parser/common.php" );

function create_preview($sheet) {
	return render_sps($sheet);
}

function add_sps($sheet) {
	global $ACCEPTABLE_KEYS_NOID;
	global $wpdb;
	$table_name = $wpdb->prefix . "spsfetcher";
	$data = array();

	// check integrity
	foreach ($ACCEPTABLE_KEYS_NOID as $key) {
		if($sheet[$key]) {
			$data[$key] = $sheet[$key];
		}
	}
	$result = $wpdb->insert($table_name, $data);
	return $result;
}

function update_sps($sheet) {
	global $ACCEPTABLE_KEYS_NOID;
	global $wpdb;
	$table_name = $wpdb->prefix . "spsfetcher";
	$data = array();

	// check integrity
	foreach ($ACCEPTABLE_KEYS_NOID as $key) {
		if($sheet[$key]) {
			$data[$key] = $sheet[$key];
		}
	}

	$where = array( 'id' => $sheet["id"] );
	$result = $wpdb->update($table_name, $data, $where);
	return $result;
}

function get_sps($slug) {
	global $wpdb;
	$table_name = $wpdb->prefix . "spsfetcher";

	$sql = "SELECT * from $table_name WHERE slug = '$slug'";
	$sps = $wpdb->get_row($sql, ARRAY_A);

	return $sps;
}

function _create_new_sps() {
	global $ACCEPTABLE_KEYS;
	$sps_values = array();
	foreach ($ACCEPTABLE_KEYS as $key) {
		$sps_value[$key] = '';
	};
	$sps_value[$id] = 0;
	echo('<div class="updated"><p><strong>');
	echo('new');
	echo('</strong></p></div>');
	include(dirname(__FILE__) . "/edit_page.php");
}

function _edit_sps() {
	$slug = $_GET[ "slug" ];
	$sps_values = get_sps($slug);
	echo('<div class="updated"><p><strong>');
	echo('edit');
	echo('</strong></p></div>');
	include(dirname(__FILE__) . "/edit_page.php");
}

function _add_sps($sheet) {
	if (add_sps($sheet)) {
		echo('<div class="updated"><p><strong>');
		echo('settings added.');
		echo('</strong></p></div>');
		$sps_values = get_sps($sheet['slug']);
	} else {
		echo('<div class="updated"><p><strong>');
		echo('insert failed.');
		echo('</strong></p></div>');
		$sps_values = $sheet;
	}
	include(dirname(__FILE__) . "/edit_page.php");
}

function _update_sps($sheet) {
	if (update_sps($sheet)) {
		echo('<div class="updated"><p><strong>');
		echo('settings saved.');
		echo('</strong></p></div>');
		$sps_values = get_sps($sheet['slug']);
	} else {
		echo('<div class="updated"><p><strong>');
		echo('save failed.');
		echo('</strong></p></div>');
		$sps_values = $sheet;
	}
	include(dirname(__FILE__) . "/edit_page.php");
}

function _preview_sps($sheet) {
	$sps_values = $sheet;
	echo('<div class="updated"><p><strong>');
	echo('preview');
	echo('</strong></p></div>');

	$preview = create_preview($sheet);
	include(dirname(__FILE__) . "/edit_page.php");
}


function sps_fetcher_edit_page() {
	global $ACCEPTABLE_KEYS;
	global $HIDDEN_FIELD_NAME;

	if( isset($_GET[ "action" ]) && $_GET[ "action" ] == 'edit' ) {
		_edit_sps();
		return;
	}

	if( isset($_POST[ $HIDDEN_FIELD_NAME ]) && $_POST[ $HIDDEN_FIELD_NAME ] == 'Y' ) {
		$sheet = array();
		foreach ($ACCEPTABLE_KEYS as $key) {
			$value = stripslashes($_POST[$key]);
			if ($value) {
				$sheet[$key] = $value;
			}
		}

		if ($_POST["preview"]) {
			_preview_sps($sheet);
			return;
		} 
		if ($_POST["id"] == 0) {
			_add_sps($sheet);
			return;
		}
		_update_sps($sheet);
		return;
	}

	_create_new_sps();
	return;
}

function sps_fetcher_options() {
	global $ACCEPTABLE_KEYS;
	global $HIDDEN_FIELD_NAME;

	$option_name_of = array();
	$option_value_of = array();

	/*
	foreach ($option_name_of as $opt_field => $opt_id) {
		$option_value_of[$opt_field] = get_option($opt_id);
	}
	*/
	if( isset($_POST[ $HIDDEN_FIELD_NAME ]) && $_POST[ $HIDDEN_FIELD_NAME ] == 'Y' ) {
		foreach ($option_name_of as $opt_field => $opt_id) {
			update_option($opt_id, stripslashes($_POST[$opt_field]) );
			$option_value_of[$opt_field] = stripslashes($_POST[$opt_field]);
		}
		// Put an settings updated message on the screen
		echo('<div class="updated"><p><strong>');
		echo(_e('settings saved.', 'menu-test'));
		echo('</strong></p></div>');
	}
	include(dirname(__FILE__) . "/admin_page.php");
}

<?php
$HIDDEN_FIELD_NAME = 'spsf_hidden';

require_once(WP_PLUGIN_DIR . "/spreadsheet-fetcher/parser/tsv_parser.php" );
require_once(WP_PLUGIN_DIR . "/spreadsheet-fetcher/parser/common.php" );
require_once(WP_PLUGIN_DIR . "/spreadsheet-fetcher/model/spreadsheet.php" );


function _create_new_sps() {
	$sps_values = array();
	foreach (acceptable_keys() as $key) {
		$sps_value[$key] = '';
	};
	$sps_value["id"] = 0;
	echo('<div class="updated"><p><strong>');
	echo('new');
	echo('</strong></p></div>');
	include(dirname(__FILE__) . "/edit_page.php");
}

function _duplicate_sps($slug) {
	$sps_values = get_sps($slug);
	if ($sps_values) {
		echo('<div class="updated"><p><strong>');
		echo('duplicated, nut not saved.');
		echo('</strong></p></div>');
	} else {
		foreach (acceptable_keys() as $key) {
			$sps_values[$key] = '';
		};
		echo('<div class="updated"><p><strong>');
		echo('error: no spreadsheet');
		echo('</strong></p></div>');
	}
	$sps_values["id"] = 0;
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

	$preview = render_sps($sheet);
	include(dirname(__FILE__) . "/edit_page.php");
}

function sps_fetcher_edit_page() {
	global $HIDDEN_FIELD_NAME;

	if( isset($_GET[ "action" ]) && $_GET[ "action" ] == 'edit' ) {
		_edit_sps();
		return;
	}

	if( isset($_GET[ "action" ]) && $_GET[ "action" ] == 'duplicate' ) {
		$src = $_GET[ "slug" ];
		if ($src) {
			_duplicate_sps($src);
			return;
		}
	}

	if( isset($_POST[ $HIDDEN_FIELD_NAME ]) && $_POST[ $HIDDEN_FIELD_NAME ] == 'Y' ) {
		$sheet = array();
		foreach (acceptable_keys() as $key) {
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
	global $HIDDEN_FIELD_NAME;

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

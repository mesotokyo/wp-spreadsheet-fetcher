<?php

function add_sps($sheet) {
	global $wpdb;
	$table_name = $wpdb->prefix . "spsfetcher";
	$data = array();

	// check integrity
	$acceptable_keys = explode(" ", "slug title description source_type source template options");
	foreach ($acceptable_keys as $key) {
		if($sheet[$key]) {
			$data[$key] = $sheet[$key];
		}
	}
	$result = $wpdb->insert($table_name, $data);
	return $result;
}

function update_sps($sheet) {
	global $wpdb;
	$table_name = $wpdb->prefix . "spsfetcher";
	$data = array();

	// check integrity
	$acceptable_keys = explode(" ", "slug title description source_type source template options");
	foreach ($acceptable_keys as $key) {
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
					 'sps-fetcher-edit-sps',
					 'sps_fetcher_edit_sps');
}

function sps_fetcher_edit_sps() {
	$hidden_field_name = 'spsf_hidden';

	$sps_values = array(
		'id' => 0,
		'slug' => '',
		'title' => '',
		'description' => '',
		'source_type' => '',
		'template' => '',
		'options' => ''
	);
	
	if( isset($_GET[ "action" ]) && $_GET[ "action" ] == 'edit' ) {
		$slug = $_GET[ "slug" ];
		$sps_values = get_sps($slug);
	}

	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
		$acceptable_keys = explode(" ", "id slug title description source_type source template options");
		$sheet = array();
		foreach ($acceptable_keys as $key) {
			$value = stripslashes($_POST[$key]);
			if ($value) {
				$sheet[$key] = $value;
			}
		}
		if ($sheet["id"] == 0) {
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
		} else {
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
		}
	}
	include(dirname(__FILE__) . "/edit_page.php");
}

function sps_fetcher_options() {
	$hidden_field_name = 'spsf_hidden';
	$option_name_of = array();
	$option_value_of = array();

	/*
	foreach ($option_name_of as $opt_field => $opt_id) {
		$option_value_of[$opt_field] = get_option($opt_id);
	}
	*/
	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
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

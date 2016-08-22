<?php

function acceptable_keys() {
	return explode(" ", "id slug title description source_type source template_header template_body template_footer options");
}

function acceptable_keys_noid() {
	return explode(" ", "slug title description source_type source template_header template_body template_footer options");
}	

function add_sps($sheet) {
	global $wpdb;
	$table_name = $wpdb->prefix . "spsfetcher";
	$data = array();

	// check integrity
	foreach (acceptable_keys_noid() as $key) {
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
	foreach (acceptable_keys_noid() as $key) {
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

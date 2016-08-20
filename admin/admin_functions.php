<?php

global $sps_fetcher_db_version;
$sps_fetcher_db_version = '1.0';

function spsf_install() {
	//create database table for this plugin
	global $wpdb;
	global $sps_fetcher_db_version;

	$table_name = $wpdb->prefix . "spsfetcher";
	$charset_collate = $wpdb->get_charset_collate();

	$sql = <<<SQL_END
		CREATE TABLE $table_name (
		  id          INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		  slug        VARCHAR(128) NOT NULL UNIQUE,
		  title TEXT,
		  description TEXT,
		  source_type VARCHAR(128) NOT NULL,
		  source      TEXT NOT NULL,
		  template    TEXT,
          options     TEXT,
		  PRIMARY KEY  (id)
		) $charset_collate;
SQL_END;

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta($sql);

	add_option('sps_fetcher_db_version', $sps_fetcher_db_version);
}



<?php

function show_sheets_tables() {
	//$lt = new Sheets_List_table();
	//$lt->prepare_items();
	//$lt->display();
	global $wpdb;
	$table_name = $wpdb->prefix . "spsfetcher";
	$sql = "SELECT id, slug, title, description from $table_name;";
	$sheets = $wpdb->get_results($sql);
	$head = <<<EOF
<table>
<thead><tr>
<th>slug</th>
<th>title</th>
<th>description</th>
<th>action</th>
</tr></thead>
<tbody>
EOF;
	echo($head);
	foreach ($sheets as $sheet) {
		$edit_url = admin_url("admin.php?page=sps-fetcher-edit-sps&action=edit&slug=$sheet->slug");
		$delete_url = admin_url("admin.php?page=sps-fetcher-edit-sps&action=delete&slug=$sheet->slug");

		$row = <<<EOF
<tr>
  <td>$sheet->slug</td>
  <td>$sheet->title</td>
  <td>$sheet->description</td>
  <td><A href="$edit_url">edit</a> <a href="$delete_url">delete</a></td>
</tr>
EOF;
		echo($row);
	}
	$foot = "</tbody></table>";
	echo($foot);
}

class Sheets_List_table extends WP_List_Table {
	function __construct() {
		parent::__construct( array(
			'Singular' => 'sheet',
			'plural' => 'sheets',
			'ajax' => false ) );
	}

	function get_columns() {
		return array(
			'slug' => 'slug',
			'title' => 'title',
			'description' => 'description'
		);
	}

	function prepare_items() {
		global $wpdb;
		$table_name = $wpdb->prefix . "spsfetcher";
		$sql = "SELECT id, slug, title, description from $table_name;";
		$sheets = $wpdb->get_results($sql);

		$screen = get_current_screen();
		$this->items = $sheets;
		$this->_column_headers = $this->get_columns();
		$this->process_bulk_action();

		$this->set_pagination_args( array(
			'total_items' => count($sheets),
			'per_page' => 10 ) );

		$columns = $this->get_columns();
		//$_wp_column_headers[$screen->id]=$columns;
	}

	function column_default($item, $column_name) {
		return $item[$column_name];
	}

}

?>
<div class="wrap">
  <h2>Spreadsheet Fetcher Configuration</h2>

	<hr>
    <?php show_sheets_tables(); ?>
	<hr>

  <form name="spsf-main-form" method="post" action="">
    <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

    <p class="submit">
      <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
    </p>

  </form>
</div>

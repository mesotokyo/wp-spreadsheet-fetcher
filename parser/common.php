<?php

function render_sps($sheet) {
	$parser = new TSVParser();
	$opts = array();
	$row_opts = explode(',', $sheet["options"]);
	foreach($row_opts as $opt) {
		$kv = explode('=', trim($opt), 2);
		$opts[$kv[0]] = $kv[1];
	}
	$opts = array(
		"key_row" => $opts["key"] ? $opts["key"] : 0,
		"start_row" => $opts["start"] ? $opts["start"] : 0,
		"end_row" => $opts["end"] ? $opts["end"] : 0,
		"order_reverse" => $opts["reverse"] ? 1 : 0,
		"header_row" => $opts["header"] ? $opts["header"] : 0,
	);
		
	$parser->load($sheet["source"], $opts);
	$result = array();
	$result[] = $parser->render_header($sheet["template_header"]);
	$result[] = $parser->render($sheet["template_body"]);
	$result[] = $sheet["template_footer"];
	return implode("\n", $result);
}
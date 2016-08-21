<?php
require_once('../tsv_parser.php');

$parser = new TSVParser();
$opts = array(
    "start_row" => 3,
    "key_row" => 1,
    "end_row" => 4,
    "header_row" => 2,
    "order_reverse" => 1,
);
$parser->load('./test.tsv', $opts);

$template = '$$ foo:$foo bar:${bar}_$moge $hoge';

$result = $parser->render($template);
//print_r($result);

$result = $parser->render_header($template, 1);
print_r($result);


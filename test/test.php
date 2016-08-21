<?php
require_once('../tsv_parser.php');

$parser = new TSVParser();
$opts = array(
    "start_row" => 3,
    "key_row" => 1,
    "end_row" => 5,
);
$parser->load('./test.tsv', $opts);

$template = '$$ foo:$foo bar:${bar}_$hoge $moge';

$result = $parser->render($template);
print_r($result);
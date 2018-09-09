<?php
require_once('../parser/tsv_parser.php');
require_once('../parser/common.php');

$parser = new TSVParser();
$opts = array(
    "start_row" => 3,
    "key_row" => 1,
    "end_row" => 4,
    "header_row" => 2,
	//    "order_reverse" => 1,
);
$parser->load('./test.tsv', $opts);

$template = '$$ foo:$foo bar:${bar}_$moge $hoge';
$template2 = '$$ $foo - ${@if $foo}foo:$foo${@end} bar:${bar}_$moge $hoge${@if $boo}$boo${@end}';

$result = $parser->render($template);
print($result);
print("\n");

$result = $parser->render_header($template2);
print($result);
print("\n");


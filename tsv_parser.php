<?php

class TSVParser {
	function __construct() {
	}
	
	function parse($tsv, $key_row=-1, $start_row=-1, $end_row=-1) {
		$rows = explode("\n", $tsv);
		$row_size = count($rows);
		if ($key_row >= 0) {
			$keys = explode("\t", trim($rows[$key_row]));
		}
		$result = array();
		for ($i = $start_row; $i < $row_size; $i++) {
			if ($key_row >= 0) { 
				// use key
				$items = array();
				$values = explode("\t", trim($rows[$i]));
				for ($j = 0; $j < count($values); $j++) {
					$key = $keys[$j];
					if ($key && $values[$j]) {
						$items[$key] = $values[$j];
					}
				}
			} else {
				$items = explode("\t", $rows[$i]);
			}
			array_push($result, $items);
		}
		return $result;
	}
}		


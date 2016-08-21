<?php

class TSVParser {
	function __construct() {
	}

	function load($url, $key_row=0, $start_row=0, $end_row=0) {
		$content = file_get_contents($url);
		$this->_parse_tsv($content, $key_row, $start_row, $end_row);
	}
	
	function _parse_tsv($tsv, $key_row=0, $start_row=0, $end_row=0) {
		$rows = explode("\n", $tsv);
		$row_size = count($rows);
		if ($key_row > 0) {
			$keys = explode("\t", trim($rows[$key_row-1]));
		}
		$result = array();
		if ($start_row > 0) {
			$start_row = $start_row - 1;
		}
		for ($i = $start_row; $i < $row_size; $i++) {
			if ($key_row > 0) { 
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
		$this->parsed = $result;
	}

	function render($template) {
		$result = array();
		foreach ($this->parsed as $item) {
			$result[] = $this->_render($item, $template);
		}
		return implode("\n", $result);
	}

	function _render($vars, $template) {
		$rex = '/\$(\w+|\{\w+\}|\$)/';
		$var_name = "";
		$result = preg_replace_callback($rex, function ($matches) use ($vars) {
				if ($matches[1] == "$") {
					return "$";
				} else if ($matches[1][0] == "{") {
					$var_name = substr($matches[1], 1, strlen($matches[1])-2);
				} else {
					$var_name = $matches[1];
				}
				if (isset($vars[$var_name])) {
					return $vars[$var_name];
				} else {
					//return $matches[0];
					return "";
				}
			},
			$template);
		return $result;
	}
}		



<?php
require_once (dirname(__FILE__) . '/template.php');

class TSVParser {
	function __construct() {
	}

	function load($url, $key_row=0, $start_row=0, $end_row=0) {
		$content = file_get_contents($url);
		$this->_parse_tsv($content, $key_row, $start_row, $end_row);
	}
	
	function _parse_tsv($tsv, $opts) {
		$rows = explode("\n", trim($tsv));
		$row_size = count($rows);
        $start_row = ($opts["start_row"] > 0) ? $opts["start_row"] - 1 : 0;
        $end_row = ($opts["end_row"] > 0) ? $opts["end_row"] - 1 : $row_size - 1;

		if ($opts["key_row"] > 0) {
			$keys = explode("\t", trim($rows[$opts["key_row"]-1]));
		}

        if ($opts["header_row"] > 0) {
			$values = explode("\t", trim($rows[$opts["header_row"]-1]));
            $headers = array();
            for ($j = 0; $j < count($values); $j++) {
                $key = $keys[$j];
                if ($key && $values[$j]) {
                    $headers[$key] = $values[$j];
                }
            }
            $this->headers = $headers;
        }

        $row_count = $end_row - $start_row + 1;
        $target_rows = array_slice($rows, $start_row, $row_count);
        if ($opts["order_reverse"]) {
            $target_rows = array_reverse($target_rows);
        }

		$result = array();
		for ($i = 0; $i < $row_count; $i++) {
			if ($opts["key_row"] > 0) { 
				// use key
				$items = array();
				$values = explode("\t", trim($target_rows[$i]));
				for ($j = 0; $j < count($values); $j++) {
					$key = $keys[$j];
					if ($key && $values[$j]) {
						$items[$key] = $values[$j];
					}
				}
			} else {
				$items = explode("\t", $target_rows[$i]);
			}
			array_push($result, $items);
		}
		$this->parsed = $result;
	}

	function render_header($template) {
        $renderer = new MiniTemplate();
        // return $this->_render($this->headers, $template);
        return $renderer->render($this->headers, $template);
    }

	function render($template) {
		$result = array();
        $renderer = new MiniTemplate();
		foreach ($this->parsed as $item) {
			//$result[] = $this->_render($item, $template);
            $result[] = $renderer->render($item, $template);
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



<?php

class MiniTemplate {
	function __construct() {
	}

    function render($vars, $template) {
        $preprocessed = $this->_preprocess_template($vars, $template);

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
        }, $preprocessed);

        return $result;
    }
 
    function _preprocess_template($vars, $template) {
        // process '${@if var_name}' - '${@end}'  keyword
        $rex = '/\$\{\s*\@if\s+\$(\w+)\s*\}(.*?)\$\{\s*\@end\s*\}/';
        // matches[1] -> varname
        // matches[2] -> template
        $result = preg_replace_callback($rex, function ($matches) use ($vars) {
            // print("match $matches[1] $matches[2]\n");
            if (isset($vars[$matches[1]])) {
                return $matches[2];
            } else {
                return "";
            }
        }, $template);

        // print "template: $template\n";
        // print "processed: $result\n";
        return $result;
    }
}

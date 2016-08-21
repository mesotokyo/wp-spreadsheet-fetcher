<?php

function form_item($type, $label, $name, $value="", $extra="") {
	if ($type == "text") {
		echo("<label for='$name'>$label</label>");
		echo("<input type='text' name='$name' id='$name' value='$value' $extra />");
		return;
	}
	if ($type == "textarea") {
		echo("<label for='$name'>$label</label>");
		echo("<textarea name='$name' id='$names' $extra >$value</textarea>");
		return;
	}
}


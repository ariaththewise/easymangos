<?php

	/*****************************************************
	* html.php - Author: Samnan ur Rehman, Zeeshan Khan  *
	* This file is a part of MyWebSQL package            *
	* Useful HTML related functions                      *
	* PHP5 compatible                                    *
	*****************************************************/

if (defined("CLASS_HTML_INCLUDED"))
	return true;

define("CLASS_HTML_INCLUDED", "1");
class Html {

	static function select($name, $attr="", $class="", $style="") {
		$select = "\n<select name='$name'";
		if ($attr != "")
			$select .= " $attr";

		if ($class == "")
			$select .= " class='defselect'";
		else
			$select .= " class='$class'";

		if ($style != "")
			$select .= " style='$style'";

		$select .= ">";
		print $select;
	}

	static function endselect() {
		print "</select>";
	}

	static function option($val, $data,$attr="") {
		print "\n<option value=\"".htmlspecialchars($val)."\" $attr>".htmlspecialchars($data)."</option>";
	}
	
	static function id($str) {
		$replace = array(' ', "'", '"', '<', '>', '&', '#', '/', '\\', ';');
		$str = str_replace($replace, '', $str);
		return $str;
	}
	
	static function arrayToOptions($array, $selected, $default=false) {
		$str = $default ? '<option value="">Default</option>' : '';
		foreach($array as $val) {
			if ($selected == $val)
				$str .= '<option selected="selected" value="'.htmlspecialchars($val).'">'.htmlspecialchars($val).'</option>';
			else
				$str .= '<option value="'.htmlspecialchars($val).'">'.htmlspecialchars($val).'</option>';
		}
		
		return $str;
	}
}

?>
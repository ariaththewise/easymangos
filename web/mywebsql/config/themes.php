<?php

	/****************************************
	 *  themes.php                          *
	 *  Theme listing configuration file    *
	 ****************************************/

	$THEMES = array(
		"default" => "Default",
		"light" => "Light (Gray)",
		"dark" => "Dark",
		"aero" => "Aero",
		"paper" => "Paper",
		//"chocolate" => "Chocolate (Minty)",
		"human" => "Humanity (Ubuntu style)"
	);


	include_once(dirname(__FILE__)."/constants.php");

	if (!defined('THEME_LOADED')) {
		if (isset($_GET["theme"]) && array_key_exists($_GET["theme"], $THEMES)) {
			define("THEME_PATH", $_GET["theme"]);
			setcookie("theme", $_GET["theme"], time()+(COOKIE_LIFETIME*60*60), EXTERNAL_PATH);
		}
		else if (isset($_COOKIE["theme"]) && array_key_exists($_COOKIE["theme"], $THEMES))
			define("THEME_PATH", $_COOKIE["theme"]);
			
		define('THEME_LOADED', '1');
	}
?>
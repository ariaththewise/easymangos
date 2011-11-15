<?php

	/************************************************
	 * some required constants for the application  *
	 * better to be here than hardcoded inside code *
	 ************************************************/

	define("EXTERNAL_PATH", str_replace(basename($_SERVER["SCRIPT_NAME"]), "", $_SERVER["SCRIPT_NAME"]));
	
	define('APP_VERSION', '2.5');
	define('PROJECT_SITEURL', 'http://mywebsql.net');
	define("DEVELOPER_EMAIL", "samnan_akhoond (at) yahoo.com");
	define("COOKIE_LIFETIME", 1440);	// in hours

	// below is required to adjust for serverside php configuration changes
	ini_set("display_errors", "off");

	if (!function_exists('v'))
	{
		function v(&$check, $alternate = FALSE)
		{
			return (isset($check)) ? $check : $alternate;
		}
		
		function stripdata($data)
		{
			if (is_array($data))
			{
				foreach($data as $key => $value)
					$data[$key] = stripdata($value);
				return $data;
			}
			return stripslashes($data);
		}
		
		// this must be done only once, so it's here
		if (get_magic_quotes_gpc())
		{
			foreach ($_REQUEST as $k=>$v)
				$_REQUEST[$k] = stripdata($v);
			foreach ($_POST as $k=>$v)
				$_POST[$k] = stripdata($v);
			foreach ($_GET as $k=>$v)
				$_GET[$k] = stripdata($v);
		}
	}
?>
<?php

	/*****************************************************
	*  cache.php - Author: Samnan ur Rehman              *
	*  This file is a part of MyWebSQL package           *
	*  outputs scripts and stylesheet for application    *
	*  PHP5 compatible                                   *
	******************************************************/

	$useCache = file_exists('js/min/minify.txt');
	include("config/themes.php");
	include("config/config.php");

	$fileList = v($_REQUEST["script"]);
	// concat theme path to make etags unique per theme
	if ($fileList == '')	$fileList = THEME_PATH . v($_REQUEST["css"]);
	if ($fileList == '')	exit();
	
	// cache scripts and css per version, if not in development mode
	if ($useCache) {
		$versionTag = md5($fileList.APP_VERSION);
		$eTag = v($_SERVER['HTTP_IF_NONE_MATCH']);
		if ($eTag != '' && $versionTag == $eTag) {   
			header($_SERVER['SERVER_PROTOCOL'].' 304 Not Modified');
			header('Content-Length: 0');
			exit();
		}
		header('Etag: '.$versionTag);
	}

   include("lib/functions.php");

	function_exists('ob_gzhandler') ? ob_start("ob_gzhandler") : ob_start();
	ob_implicit_flush(0);
	$regex = '#^(\w+/){0,2}\w+$#';

	if (v($_REQUEST["script"]) != "")
	{
		$script_path = $useCache ? "js/min" : "js";
		$scripts = explode(",", $_REQUEST["script"]);
		header("mime-type: text/javascript");
		header("content-type: text/javascript");
		echo "/* MyWebSQL script file */\n\n";
		foreach($scripts as $script)
			if ( preg_match($regex, $script) == 1 )
				if(file_exists("$script_path/$script".".js"))
					echo file_get_contents("$script_path/$script".".js") . "\n\n";
	}
	else if (v($_REQUEST["css"]) != "")
	{
		$styles = explode(",", $_REQUEST["css"]);
		header("mime-type: text/css");
		header("content-type: text/css");
		echo "/* MyWebSQL style sheet */\n\n";
		foreach($styles as $css)
			if ( preg_match($regex, $css) == 1 )
				if(file_exists("themes/".THEME_PATH."/$css".".css"))
					echo file_get_contents("themes/".THEME_PATH."/$css".".css") . "\n\n";
	}

	print_gzipped_output();
?>
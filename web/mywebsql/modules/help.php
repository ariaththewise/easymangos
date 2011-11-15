<?php

	/********************************************
	*  help.php - Author: Samnan ur Rehman      *
	*  This file is a part of MyWebSQL package  *
	*  PHP5 compatible                          *
	*********************************************/

	function processRequest(&$db) {
		if (!v($_REQUEST["p"]))
			$_REQUEST["p"] = "queries";
		showHelpTopic($_REQUEST["p"]);
	}

	// ==========================================
	function showHelpTopic($p) {
		$pages = array(
						"queries"=>'Executing queries',
						"results"=>'Working with results',
						"keyboard"=>'Keyboard shortcuts',
						"prefs"=>'Preferences',
						"misc"=>'Miscellaneous',
						"credits"=>'Credits',
						"about"=>'About'
						);

		$links = '';
		foreach($pages as $x=>$y) {
			if ($p == $x)
				$links .= "<li class=\"current\"><img border=\"0\" align=\"absmiddle\" src='img/help/t_$x".".gif' alt=\"\" />$y</li>";
			else
				$links .= "<li><a href=\"#$x\"><img border=\"0\" align=\"absmiddle\" src='img/help/t_$x".".gif' alt=\"\" />$y</a></li>";
		}
		
		$page = $p . ".php";
		$contents = view("help/$p");

		$replace = array(
			'PROJECT_SITEURL' => PROJECT_SITEURL,
			'LINKS' => $links,
			'CONTENT' => $contents
		);
		
		echo view('help', $replace);
	}

?>
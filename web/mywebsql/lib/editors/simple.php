<?php

	/***********************************************
	*	simple.php - Author: Samnan ur Rehman      *
	*	This file is a part of MyWebSQL package    *
	*	Contains simple text editor functionality  *
	*	PHP5 compatible                            *
	************************************************/

	function createSqlEditor() {
		print "<script type=\"text/javascript\" language=\"javascript\" src=\"cache.php?script=texteditor\"></script><script type=\"text/javascript\" language=\"javascript\">
			function editorHotkey(code, fn) {
				$('#commandEditor').bind('keydown', code, fn);
				$('#commandEditor2').bind('keydown', code, fn);
				$('#commandEditor3').bind('keydown', code, fn);
			}
			$(function() {
				commandEditor = new textEditor(\"#commandEditor\");
				commandEditor2 = new textEditor(\"#commandEditor2\");
				commandEditor3 = new textEditor(\"#commandEditor3\");
				initStart();
			}); </script>";
	}
?>
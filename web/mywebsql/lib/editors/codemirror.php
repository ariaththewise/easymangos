<?php

	/***********************************************
	*	codemirror.php - Author: Samnan ur Rehman   *
	*	This file is a part of MyWebSQL package     *
	*	Contains codemirror editor functionality    *
	*	PHP5 compatible                             *
	************************************************/


	function createSqlEditor() {
		$min = file_exists('js/min/minify.txt');
		$js = $min ? 'codemirror' : 'editor/codemirror';
		print '<link rel="stylesheet" type="text/css" href="cache.php?css=mysqlcolors" />';
		print "<script type=\"text/javascript\" language=\"javascript\" src=\"cache.php?script=$js\"></script><script type=\"text/javascript\" language=\"javascript\">
			function editorHotkey(code, fn) {
				$(document.getElementById('sqlEditFrame').contentWindow.document).bind('keydown', code, fn);
				$(document.getElementById('sqlEditFrame2').contentWindow.document).bind('keydown', code, fn);
				$(document.getElementById('sqlEditFrame3').contentWindow.document).bind('keydown', code, fn);
			}
			$(function() {\n";
		sqlEditorJs($min, 'commandEditor', 'sqlEditFrame', 'initStart();');
		sqlEditorJs($min, 'commandEditor2', 'sqlEditFrame2');
		sqlEditorJs($min, 'commandEditor3', 'sqlEditFrame3');
		print '}); </script>';
	}

	function sqlEditorJs($min, $id, $frameId, $init='') {
		if ($min)
			print $id.' = CodeMirror.fromTextArea("'.$id.'", { basefiles: ["js/min/codemirror_base.js"],';
		else
			print $id.' = CodeMirror.fromTextArea("'.$id.'", { parserfile: "mysql.js", path: "js/editor/",';

		print 'iframeId: "'.$frameId.'", iframeClass: "sqlEditFrame", autoMatchParens: true,
				height: "100%", tabMode : "default", stylesheet: "cache.php?css=mysqlcolors",
				lineNumbers: true, tabFunction : function() { document.getElementById("nav_query").focus(); },
				onLoad : function() { '.$init.' }
				});';
	}
	
?>
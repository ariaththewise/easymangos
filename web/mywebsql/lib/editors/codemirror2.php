<?php

	/***********************************************
	*	codemirror2.php - Author: Samnan ur Rehman  *
	*	This file is a part of MyWebSQL package     *
	*	Contains codemirror2 editor functionality   *
	*	PHP5 compatible                             *
	************************************************/

	function createSqlEditor() {
		$js = 'codemirror2,mysql';
		print '<link rel="stylesheet" type="text/css" href="cache.php?css=codemirror2" />';
		print "<script type=\"text/javascript\" language=\"javascript\" src=\"cache.php?script=$js\"></script><script type=\"text/javascript\" language=\"javascript\">
			function editorHotkey(code, fn) {
				//$(document.getElementById('sqlEditFrame').contentWindow.document).bind('keydown', code, fn);
				//$(document.getElementById('sqlEditFrame2').contentWindow.document).bind('keydown', code, fn);
				//$(document.getElementById('sqlEditFrame3').contentWindow.document).bind('keydown', code, fn);
			}
			$(function() {\n";
		sqlEditorJs('commandEditor', 'initStart();');
		sqlEditorJs('commandEditor2');
		sqlEditorJs('commandEditor3');
		print "\n}); </script>";
	}

	function sqlEditorJs($id, $init='') {
		print $id.' = CodeMirror.fromTextArea(document.getElementById("'.$id.'"), { mode: "text/x-mysql",
				lineNumbers: true, matchBrackets: true, indentUnit: 3,
				height: "100%", tabMode : "default",
				tabFunction : function() { document.getElementById("nav_query").focus(); },
				onLoad : function() { '.$init.' },
				onGutterClick: function(cm, n) {
					var info = cm.lineInfo(n);
					if (info.markerText)
						cm.clearMarker(n);
					else
						cm.setMarker(n, "<span style=\"color: #900\">?</span> %N%");
				}
			});';
	}
	
?>
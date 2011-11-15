<?php

	/***********************************************
	*	processes.php - Author: Samnan ur Rehman    *
	*	This file is a part of MyWebSQL package     *
	*	PHP5 compatible                             *
	************************************************/

	function processRequest(&$db) {
		// html and form is started by calling function
		print "<link href='cache.php?css=theme,default,alerts,results' rel=\"stylesheet\" />\n";
		
		$type = 'note';
		if (v($_REQUEST["processid"]) && ctype_digit(v($_REQUEST["processid"]))) {
			if (killProcess($db, $_REQUEST["processid"])) {
				$msg = str_replace('{{PID}}', $_REQUEST['processid'], __('The process with id [{{PID}}] was killed'));
				$type = 'success';
			}
			else {
				$msg = str_replace('{{PID}}', $_REQUEST['processid'], __('No such process [id = {{PID}}]'));
				$type = 'warning';
			}
		}
		else
			$msg = __('Select a process and click the button to kill the process');
		
		displayProcessList($db, $msg, $type);
	}
	
	function displayProcessList(&$db, $msg, $type="note") {
		print "<input type='hidden' name='q' value='wrkfrm' />";
		print "<input type='hidden' name='type' value='processes' />";
		print "<input type='hidden' name='id' value='' />";
		print "<input type='hidden' name='processid' value='' />";
		
		print "<table border=0 cellspacing=2 cellpadding=2 width='100%'>";
		if ($msg != "") {
			$div = '<div class="'.$type.'">'.$msg.'</div>';
			print "<tr><td height=\"25\">$div</td></tr>";
		}
		print "<tr><td colspan=2 valign=top>";
		
		if ($db->query("show full processlist")) {
			print "<table class='results postsort' border=0 cellspacing=1 cellpadding=2 width='100%' id='processes'><tbody>";
			print "<tr id='fhead'><th class='th'>".__('Process ID')."</th><th class='th'>".__('Command')."</th><th class='th'>".__('Time')."</th><th class='th'>".__('Info')."</th></tr>";
			
			while($row = $db->fetchRow())
				print "<tr class='row' onclick=\"frmquery.processid.value='$row[Id]';\"><td class='tl'>$row[Id]</td><td class='tl'>$row[Command]</td><td class='tl'>$row[Time]</td><td class='tl'>$row[Info]</td></tr>";
			
			print "</tbody></table>";
			
			print "<tr><td colspan=2 align=right><div id=\"popup_buttons\"><input type='submit' id=\"btn_kill\" name='btn_kill' value='".__('Kill Process')."' /></div></td></tr>";

			print "<script type=\"text/javascript\" language='javascript' src=\"cache.php?script=common,jquery,ui,query,sorttable,tables\"></script>\n";
			
			print "<script type=\"text/javascript\" language='javascript'>
				window.title = \"".__('Process Manager')."\";
				$('#btn_kill').button().click(function() { document.frmquery.submit(); });
				setupTable('processes', {sortable:true, highlight:true, selectable:true});
			</script>";
		}
		else
			print __('Failed to get process list');
	}
	
	function killProcess(&$db, $id) {
		if ($id) {
			traceMessage("killing process with id $id");
			if ($db->query("kill '".$db->escape($id)."'"))
				return true;
		}
		return false;
	}

?>
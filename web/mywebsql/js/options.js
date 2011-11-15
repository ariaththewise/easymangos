/************************************************
 *	options.js - Author: Samnan ur Rehman        *
 * contains top level menu commands used in js  *
 * dependencies: query.js                       *
 *	This file is a part of MyWebSQL package      *
 ************************************************/

function logout() {
	optionsConfirm(__("Are you sure you want to logout?"), 'sess.logout', function(result, id, confirm_always) {
		if (result)
		{
			if (confirm_always) optionsConfirmSave(id);
			wrkfrmSubmit("logout", '', '', '');
		}
	});
}

/* ------------------------------- */
function dbSelect() {
	if (arguments.length == 1) {
		loc = "?reload=1&db="+escape(arguments[0]);
		window.location = loc;
		return;
	}
	
	db =  document.getElementById("dblist").options[document.getElementById("dblist").selectedIndex].text;
	data = 'db='+escape(db);
	$.ajax({ type: 'GET',
		url: '?',
		data: data,
		success: function(res) { 
			success = $(res).html();
			if(success == '1') {
				objectsRefresh();
				infoDefault();
			} else
				jAlert(__('Operation failed'));
		},
		dataType: 'html'
	});
}

function dbDrop(name) {
	msg = str_replace('{{NAME}}', '<b>'+name+'</b>', __('Are you sure you want to DROP the database {{NAME}}?'));
	optionsConfirm(msg, 'db.drop', function(result, id, confirm_always) {
		if (result)
		{
			if (confirm_always) optionsConfirmSave(id);
				wrkfrmSubmit("dbdrop", type, name);
		}
	});
}

function dbEmpty(name) {
	msg = str_replace('{{NAME}}', '<b>'+name+'</b>', __('Are you sure you want to DROP all objects from the database {{NAME}}?'));
	optionsConfirm(msg, 'db.empty', function(result, id, confirm_always) {
		if (result)
		{
			if (confirm_always) optionsConfirmSave(id);
				wrkfrmSubmit("dbempty", type, name);
		}
	});
}

function dbCreate(submit) {
	if (arguments.length && submit)
	{
		name = $.trim($('#dbname').val());
		if (name != '')
		{
			wrkfrmSubmit("dbcreate", "", name, $('#dbselect').attr('checked') ? '1' : '0');
			$("#dialog-dbcreate").dialog('close');
			return false;
		}
	}
	else
	{
		$('#dbname').val('');
		$('#dbselect').attr('checked', false);
		uiCreateDialog('dbcreate');
		$('#dialog-dbcreate').dialog('open');
	}
}

/* ------------------------------- */
function objectsRefresh() {
	// save tree state
	state = [];
	$('#tablelist .expandable').each(function() {
		state.push($(this).attr('id'));
	});
	wrkfrmSubmit('objlist', '', '', '', function(data) { objListHandler(data, state); } );
}

function objDefault(item, id) {
	$(currentTreeItem).removeClass('current');
	currentTreeItem = $('#'+id);
	currentTreeItem.addClass('current').find('a').blur();
	name = currentTreeItem.find('a').text();
	wrkfrmSubmit("showinfo", item, name, "");
}


function objCreate(id) {
	taskbar.openDialog("objcreate_"+id, "?q=wrkfrm&type=objcreate&id="+id, 600, 490);
}

function objTruncate(type, name) {
	msg = str_replace('{{NAME}}', name, __('Are you sure you want to truncate the table {{NAME}}?'));
	optionsConfirm(msg, 'obj.truncate', function(result, id, confirm_always) {
		if (result) {
			if (confirm_always) optionsConfirmSave(id);
				wrkfrmSubmit("truncate", type, name);
		}
	});
}
function objDrop(type, name) {
	msg = str_replace('{{NAME}}', '<br>'+name, __('Are you sure you want to drop this object? {{NAME}}'));
	optionsConfirm(msg, 'obj.drop', function(result, id, confirm_always) {
		if (result) {
			if (confirm_always) optionsConfirmSave(id);
				wrkfrmSubmit("drop", type, name);
		}
	});
}
function objRename(type, name) {
	jPrompt(__('Enter new name for the database object'), name, __('Rename Object'), function(new_name) {
		if (new_name == null)
			return;
		else if (new_name && new_name != name)
			wrkfrmSubmit("rename", type, name, new_name);
		else
			jAlert(__('Enter new name for the database object'));
	});
}

function objCopy(type, name) {
	jPrompt(__('Enter new name for the database object'), name+"_copy", __('Copy Object'), function(new_name) {
		if (new_name == null)
			return;
		else if (new_name && new_name != name)
			wrkfrmSubmit("copy", type, name, new_name);
		else
			jAlert(__('Enter new name for the database object'));
	});
}

/* ------------------------------- */
function tableCreate() {
	taskbar.openDialog("editable", "?q=wrkfrm&type=createtbl", 780, 440, true);
}

function tableSelect(name)
{
	st = sql_delimiter + "select * from " + BACKQUOTE + name + BACKQUOTE;
	setSqlCode( st, 1 );
}
function tableInsert(name) { wrkfrmSubmit("tableinsert", "", name); }
function tableUpdate(name) { wrkfrmSubmit("tableupdate", "", name); }
function tableDescribe(name) { wrkfrmSubmit("query", "", "", "describe " + BACKQUOTE + name + BACKQUOTE); }
function tableViewData(name) {
	q = "select * from " + BACKQUOTE + name + BACKQUOTE;
	setSqlCode(sql_delimiter + q, 1);
	wrkfrmSubmit("query", "table", "", name);
}

function tableAlter(name) { taskbar.openDialog("editable-"+name, "?q=wrkfrm&type=altertbl&name="+escape(name), 780, 440, true); }
function tableIndexes(name) { taskbar.openDialog("indexes-"+name, "?q=wrkfrm&type=indexes&name="+escape(name), 680, 430, true); }
function tableEngine(name) { taskbar.openModal("table-engine", "?q=wrkfrm&type=enginetype&name="+escape(name), 280, 190, true); }
function showCreateCmd(type, name) {	wrkfrmSubmit("showcreate", type, name, ""); }

/* ------------------------------- */
function dataImport() {
	taskbar.openDialog("data-import", "?q=wrkfrm&type=import", 610, 360);
}

function resultsExport() {
	if (numRecords == 0)
		jAlert(__('There is no record in the results to export'), __('Exports results'));
	else
		taskbar.openModal("data-export", "?q=wrkfrm&type=exportres", 400, 290);
}

function dataExport() {
	taskbar.openDialog("data-export", "?q=wrkfrm&type=export", 600, 420);
}

function tableExport(tbl) {
	taskbar.openDialog("data-export", "?q=wrkfrm&type=exporttbl&table="+tbl, 400, 280);
}

function exportData() {
	id = arguments.length > 0 ? arguments[0] : '';
	name = arguments.length > 1 ? arguments[1] : '';
	query = arguments.length > 2 ? arguments[2] : '';
	wrkfrmSubmit('dl', 'export'+id, name, query);
}

function repairTables() {
	wrkfrmSubmit('dbrepair', '', '', '');
}


/* ------------------------------- */
function helpShowAll() { taskbar.openDialog('help', "?q=wrkfrm&type=help", 680, 440); }
function helpQuickTutorial() {
	$('<link id="ehelp_css">').appendTo('head').attr({ rel:  "stylesheet", type: "text/css", href: "img/ehelp.css" });
	$.getScript('cache.php?script=ehelp', function() {
		taskbar.minimizeAll();
		showEHelp();
	});
}
function helpOnlineDocs() { window.open('http://mywebsql.net/docs'); }
function helpReportBug() {	window.open('http://mywebsql.net/support/bugreport/'); }
function helpRequestFeature() { window.open('http://mywebsql.net/support/requests/'); }
function helpCheckUpdates() {
	//uiInitDialog();
	url = "http://mywebsql.net/updates.php?" + "c=MyWebSQL&l=" + escape(APP_LANGUAGE) + "&v="+escape(APP_VERSION) + "&t=" + escape(THEME_PATH);
	taskbar.openModal("update-check", url, 180, 160);
	$("#dialog-update-check").siblings('.ui-dialog-titlebar').find('.ui-dialog-title').html(__('Check for Updates'));
	/*$("#dialog_contents").attr("src", 'javascript:false');
	updatePopup(0);
	$('#dialog').dialog('option', 'width', 180);
	$('#dialog').dialog('option', 'height', 160);
	$('#dialog').dialog('open');
	$("#ui-dialog-title-dialog").html(__('Check for Updates'));
	$("#dialog_contents").attr("src", url);*/
}

function interfaceTheme(t) {
	data = 'theme='+escape(t);
	$.ajax({ type: 'GET',
		url: '?',
		data: data,
		success: function(res) { window.location = window.location; },
		dataType: 'html'
	});
}

function interfaceLang(t) {
	data = 'lang='+escape(t);
	$.ajax({ type: 'GET',
		url: '?',
		data: data,
		success: function(res) { window.location = window.location; },
		dataType: 'html'
	});
}

/* ------------------------------- */
function toolsOptions() {
	taskbar.openModal("tools-options", "?q=wrkfrm&type=options", 500, 260);
}

function toolsProcManager() {
	taskbar.openDialog("tools-proc", "?q=wrkfrm&type=processes", 560, 380);
}

function toolsDbCheck() {
	taskbar.openDialog("tools-check", "?q=wrkfrm&type=dbrepair", 600, 420);
}

function toolsDbSearch() {
	taskbar.openDialog("tools-search", "?q=wrkfrm&type=search", 620, 450);
}

function toolsUsers() {
	taskbar.openDialog("tools-users", "?q=wrkfrm&type=usermanager", 620, 440);
}

function infoDefault() {
	$('#screen-wait').remove();
	wrkfrmSubmit("info", "", "", "");
}

function infoServer() {
	wrkfrmSubmit("infoserver", "", "", "");
}

function infoVariables() {
	wrkfrmSubmit("infovars", "", "", "");
}

function infoDatabase() {
	wrkfrmSubmit("infodb", "", "", "");
}

/* ------------------------------- */
function copyColumn(t) {
}

function copyText(t) {
}

function sqlFilterText(t) {
}

/* ------------------------------- */
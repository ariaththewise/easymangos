/**********************************************************
 *  tables.js - Author: Samnan ur Rehman                  *
 *  This file is a part of MyWebSQL package               *
 *  collection of functions to help with                  *
 *  tables sorting/editing etc                            *
 **********************************************************/

var curEditField = null;			// current edited field
var curEditType = null;
var fieldInfo = null;				// information about fields in record set
var editToolbarTimer = null;		// timer for hiding editing toolbar during field editing
var editOptions = { sortable:true, highlight:true, selectable:true, editEvent:'dblclick', editFunc:editTableCell };

var selectedRow = -1;
var res_modified = false;			// is the result modified?

// options can include
// highlight: boolean: highlights row on mouse over
// selectable: boolean: makes row selectable
// editable: boolean: makes grid editable
// sortable: boolean: makes table sorting possible using its header

function setupTable(id, opt) {
	res_modified = false;

	opt.editEvent ? void(0) : opt.editEvent = 'dblclick';
	opt.editFunc ? void(0) : opt.editFunc = editTableCell;

	if (opt.sortable) {
		sorttable.DATE_RE = /^(\d\d?)[\/\.-](\d\d?)[\/\.-]((\d\d)?\d\d)$/;
		table = document.getElementById(id);
		sorttable.makeSortable(table);
	}

	if (opt.highlight) {
		$('#'+id+' tr').live('mouseover', function() {
			$(this).addClass("hl");
		});
		$('#'+id+' tr').live('mouseout', function() {
			$(this).removeClass('hl');
		});
	}

	if (opt.selectable) {
		$('#'+id+' tr').live('click', function() {
			if (selectedRow != null)
				$(selectedRow).removeClass('sel');
			$(this).addClass("sel");
			selectedRow = this;
		});
	}

	if (opt.editable) {
		editOptions = opt;
		$('#'+id+' td.edit').bind(opt.editEvent, opt.editFunc);
		$('#inplace-text textarea').unbind('keydown').bind('keydown', checkEditField).blur( function() { closeEditor(true); } );
	}
}

function editTableCell() {
	// avoid flickers in showing/hiding toolbar when navigating between result set fields
	if (editToolbarTimer) {
		window.clearTimeout(editToolbarTimer);
		editToolbarTimer = null;
	}

	td = $(this);
	if (curEditField != null)
		closeEditor(true);

	isBlob = td.find('span.i').length;
	isText = td.find('span.d').length;
	txt =  isText ? td.find('span.d').text() : (isBlob ? td.find('span.i').text() : td.text());
	tstyle = td.hasClass('tr') ? "right" : "left";

	td.data('defText', txt);

	curEditField = this;
	index = td.index()-2;
	fi = getFieldInfo(index);
	w = td.width() - (isBlob ? 22 : 0);
	h = td.height();
	td.attr('width', w);

	input = createCellEditor(td, fi, txt, w, h, tstyle);

	// this is needed for IE
	setTimeout( function() { input.focus(); }, 50 );

	if (document.getElementById('editToolbar')) {
		$('#editToolbar').show().position({ of: td, my: "left bottom", at: "left top", offset: 0 });
	}
}

function closeEditor(upd, value) {
	if (!curEditField)
		return;
	
	obj = $(curEditField);
	txt = '';
	var xt = new Object();
	if (upd) {
		if (arguments.length > 1 && value == null) {
			xt.value = "NULL";
			xt.setNull = true;
		}
		else {
			txt = xt.value = (curEditType == 'simple') ? obj.find('input').val() : $('#inplace-text textarea').val();
			xt.setNull = false;
		}

		// if not modified, don't bother
		if ( (xt.value != obj.data('defText')) || (xt.setNull && !obj.hasClass("tnl"))
				|| (!xt.setNull && obj.hasClass('tnl')) ) {
			if (!obj.parent().hasClass('n'))
				obj.parent().addClass('x');
			obj.data('edit', xt).addClass('x');
			res_modified = true;

			if (typeof showNavBtn == "function")
				showNavBtn('update', 'gensql');

			if(xt.setNull)
				obj.removeClass('tl').addClass('tnl');
			else
				obj.removeClass('tnl').addClass('tl');

			txt = xt.value;
			//txt = str_replace("<", "&lt;", xt.value);
			//txt = str_replace(">", "&gt;", txt);
		}
	}
	else
		txt = obj.data('defText');

	if (curEditType == 'text') {
		if (xt.setNull)
			obj.find('span.i').text('NULL').removeClass('tl').addClass('tnl');
		else {
			obj.find('span.i')
				.text(txt.length == 0 ? '' : 
					( txt.length <= MAX_TEXT_LENGTH_DISPLAY ? txt : 'Text Data [' + formatBytes(txt.length) + ']') )
				.removeClass('tnl');
		}
		obj.find('span.d').text(txt);
	}
	else {
		if (obj.find('span.i').length == 0)
			obj.text(txt);
		else
			obj.find('span.i').text(txt);
	}
	
	obj.removeAttr('width');
	curEditField = null;

	if (curEditType == 'text')
		$('#inplace-text').hide();
	
	if (document.getElementById('editToolbar'))
		editToolbarTimer = window.setTimeout(function() { document.getElementById('editToolbar').style.display = "none"; editToolbarTimer=null; }, 100 );
}

function checkEditField(event) {
	// enter, tab, up arrow, down arrow
	keys = (curEditType == 'text') ? [9] : [13,9,38,40];
	if (keys.indexOf(event.keyCode) != -1) {
		event.preventDefault();
		elem = false;
		if (event.keyCode == 9) {
			elem = event.shiftKey ? $(curEditField).prev('.edit') : $(curEditField).next('.edit');
			// move to next/previous record if possible
			if (!elem.length) {
				tr = event.shiftKey ? $(curEditField).parent().prev() : $(curEditField).parent().next();
				if (tr.length)
					elem = event.shiftKey ? tr.find('td.edit:last') : tr.find('td.edit:first');
			}
		}
		else if (event.keyCode == 38 || event.keyCode == 40) {
			tr = event.keyCode == 38 ? $(curEditField).parent().prev() : $(curEditField).parent().next();
			if (tr.length)
				elem = tr.find('td').eq($(curEditField).index());
		}
		closeEditor(true);
		if (elem && elem.length)    // edit next or previous element
			elem.trigger(editOptions.editEvent);
	}
	else if (event.keyCode == 27)
		closeEditor(false);
	/*else if ($(this).attr('readonly') != '' && [16,17,18].indexOf(event.keyCode) == -1 ) {
	   // focus is on a blob editor, need to open dialog for any keypress
		oldEditField = curEditField;
		closeEditor(false);
		$(oldEditField).find('span.blob').click();
	}*/
}

function createCellEditor(td, fi, txt, w, h, align) {
	curEditType = 'simple';
	keyEvent = 'keydown';
	input = null;
	code = '<form name="cell_editor_form" class="cell_editor_form" action="javascript:void(0);">';
	if (fi['blob'] == 1) {
		if (fi['type'] == 'binary') {
			code += '<input type="text" readonly="readonly" name="cell_editor" class="cell_editor" style="text-align:' + align + ';width: ' + w + 'px;" />';
			code += '</form>';
			td.find('span.i').html(code);
			input = td.find('input');
			input.val(txt).bind(keyEvent, checkEditField ).blur( function() { closeEditor(true); } );
		}
		else {
			span = $(td).find('span.d');
			txt = span.text();
			w = td.width()-20;
			if (w < 200) w = 200;
			textarea = $('#inplace-text textarea');
			textarea.width(w).val(txt);
			
			//$('#inplace-text').css({"position":"absolute", "z-index": 10000, "left":left+"px","top":top+"px"}).show();
			$('#inplace-text').show().position({ of: td, my: "left top", at: "left top", offset: 0 });
			
			curEditType = 'text';
			input = textarea;
		}
	}
	else {
		switch(fi['type']) {
			default:
				code += '<input type="text" name="cell_editor" class="cell_editor" style="text-align:' + align + ';width: ' + w + 'px;" />';
				code += '</form>';
				td.html(code);
				input = td.find('input');
				input.val(txt).select().bind(keyEvent, checkEditField ).blur( function() { closeEditor(true); } );
				break;
		}
	}
	return input;
}

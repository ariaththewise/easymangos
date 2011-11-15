/**********************************************
 *	options.js - Author: Samnan ur Rehman      *
 *	This file is a part of MyWebSQL package    *
 **********************************************/

function optionsSave() {
	for(i=0; i<arguments.length; i++) {
		arg = arguments[i];
		obj = document.getElementById(arg);
		// ! may warn user here that some options could not be saved
		if (obj) {
			if (obj.type == "checkbox")
				val = obj.checked ? "on" : "";
			else
				val = obj.value;
			$.cookies.set("prf_"+arg, val, {path: EXTERNAL_PATH, hoursToLive: COOKIE_LIFETIME});
		}
	}

	jAlert(__("New settings saved and applied."));
}

function optionsLoad() {
	/*for(i=0; i<arguments.length; i++) {
		arg = arguments[i];
		obj = document.getElementById(arg);

		if (obj) {
			val = $.cookies.get("prf_"+arg);
			if (obj.type == "checkbox")
				obj.checked = val == "on" ? true : false;
			else
				obj.value = val;
		}
	}*/
}

function optionsConfirm(msg, id, callback) {
	ask = $.cookies.get("prf_cnf_"+id);
	if (ask == 'no')
		return callback(true, '', false);
	return jConfirm(msg, __('Confirm Action'), callback, id);
}

function optionsConfirmSave(id) {
	$.cookies.set("prf_cnf_"+id, 'no', {path: EXTERNAL_PATH, hoursToLive: COOKIE_LIFETIME});
}
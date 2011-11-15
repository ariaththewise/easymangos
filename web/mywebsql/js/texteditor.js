/**********************************************
 *	texteditor.js - Author: Samnan ur Rehman  *
 *	This file is a part of MyWebSQL package   *
 *	Provides basic text editor functionality  *
 * for any textarea                           *
 **********************************************/

textEditor = function(id) {
	this.textarea = $(id);
};

textEditor.prototype.focus = function() {
	return this.textarea.focus();
};

textEditor.prototype.getCode = function(s) {
	return this.textarea.val();
};

textEditor.prototype.setCode = function(s) {
	this.textarea.val(s);
};

textEditor.prototype.getSelection = function(s) {
	return this.textarea.val();
};
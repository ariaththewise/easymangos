textEditor=function(a){this.textarea=$(a)};textEditor.prototype.focus=function(){return this.textarea.focus()};textEditor.prototype.getCode=function(){return this.textarea.val()};textEditor.prototype.setCode=function(a){this.textarea.val(a)};textEditor.prototype.getSelection=function(){return this.textarea.val()};
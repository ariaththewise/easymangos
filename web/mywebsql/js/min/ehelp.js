(function(a){a.fn.extend({embeddedHelp:function(u,n){function v(){var b,c={height:window.innerHeight,width:window.innerWidth};if(!c.height&&((b=document.compatMode)||!a.support.boxModel))b=b=="CSS1Compat"?document.documentElement:document.body,c={height:b.clientHeight,width:b.clientWidth};return c}function w(){return{top:window.pageYOffset||document.documentElement.scrollTop||document.body.scrollTop,left:window.pageXOffset||document.documentElement.scrollLeft||document.body.scrollLeft}}function r(){return{height:a(document).height(),
width:a(document).width()}}function C(a){return{height:a.height(),width:a.width()}}function z(a,c){var e=r();return(a>e.width/2?"L":"R")+(c>e.height/2?"T":"B")}function x(){a(".EHtoolgost").remove();a(".EHtooltipc").each(function(){var b=a(this),c=b.offset(),e=b.outerWidth(),f=b.outerHeight(),g=r(),d=v(),i=w(),o=i.top,i=i.left,l=o+d.height,m=i+d.width,b=b.find("span").html(),h=false;if(g.width>d.width)var k=50,j=true;else k=35,j=false;g.height>d.height?(g=50,d=true):(g=35,d=false);c.top>l?(f=l-k,
h=true):j==true&&c.top>l-20?(f=l-k,h=true):c.top+f<o?(f=o,h=true):f=c.top;c.left+e<i?(c=i+10,h=true):d==true&&c.left+e<i-20?(c=i+10,h=true):c.left>m?(c=m-g,h=true):c=c.left;h==true&&(e=a("<div>").addClass("EHtoolgost").html("<span>"+b+"</span>").appendTo("body"),D(b,f,c,e.outerHeight(),e.outerWidth())!=true&&e.attr("id","ghost_"+b).css("top",f+5+"px").css("left",c-5+"px").appendTo("body").fadeIn("slow"))});return false}function E(){F(a("#EHpointer"));return false}function D(b,c,e,f,g){v();w();var d=
false;a(".EHtoolgost").each(function(){var i=a(this).offset(),o=a(this).outerWidth(),l=a(this).outerHeight(),m=a(this).find("span").html();if(m!=b&&m!=""&&(e>=i.left&&e<=i.left+o||e+g>=i.left&&e+g<=i.left+o))if(c>=i.top&&c<=i.top+l||c+f>=i.top&&c+f<=i.top+l)a(this).find("span").html(m+", "+b),d=true});return d}function F(b){var c=b.offset(),e=b.height(),b=b.width(),f=v(),g=w(),d=g.top,g=g.left,i=d+f.height,o=g+f.width,l=r(),m=false,h=false,k=a.browser.opera?"html":"body,html";if(b>0){if(c.top+e>i){var j=
d+f.height;j+f.height>l.height&&(j=l.height-f.height);d!=j&&(m=true)}else c.top<d&&(j=d-f.height,j<0&&(j=0),d!=j&&(m=true));if(c.left<g){var p=g-f.width;g<0&&(g=0);g!=p&&(h=true)}else c.left+b>o&&(p=g+f.width,p+f.width>l.width&&(p=l.width-f.width),g!=p&&(h=true));m==true&&q!=true&&(q=true,a(k).animate({scrollTop:j},"slow",function(){q=false}));h==true&&q!=true&&(q=true,a(k).animate({scrollLeft:p},"slow",function(){q=false}))}return false}function y(b){if(n.callextf==true&&b.extf!=""&&b.extf!=void 0)a[b.extf]?
(b.object=a(b.element),a[b.extf](b)):jQuery.globalEval(b.extf);return false}function s(){clearInterval(A);t=null;a("#EHpointer").stop(true);a("#EHtooltip").stop();a(".EHtooltmp").remove();a("#EHtooltip").remove();a("#EHpointer").remove();a(".EHtooltipc").remove();a(".EHtoolgost").remove();a.isEmptyObject(u)||a.each(u,function(b,c){a.each(c.path,function(e,b){a(b.element).removeClass(b.marker)})});return false}function G(b,c){var e=b.offset();r();var f=c.length;if(!a.isEmptyObject(c)){a("body").append("<div id='EHtooltip'></div>");
a("body").append("<div id='EHpointer'></div>");a("#EHpointer").css("top",e.top+10+"px").css("left",e.left+10+"px").fadeIn("fast");var g=0;(function(){var d=c[g++],b=arguments.callee,e=a(d.element).offset(),l=a(d.element).height(),m=a(d.element).width(),h=e.left+m/2,k=e.top+l/2,j=C(a("#EHpointer"));a("#EHpointer").animate({left:h+"px",top:k+"px"},2E3,function(){a(d.element).addClass(d.marker);var e=a("#EHtooltip").css("width","auto");e.html(d.desc);d.ftriger!="E"&&y(d);var b=e.width(),c=e.height();
tbalign=n.autoalign!=true&&d.align!=""?d.align:z(h+5,k+5);switch(tbalign){case "L":topset=k;leftset=h-b-5;break;case "LT":topset=k-c-5;leftset=h-b-5;break;case "LB":topset=k+c+5;leftset=h-b-5;break;case "R":topset=k;leftset=h+j.width+5;break;case "RT":topset=k-5;leftset=h+j.width+5;break;case "RB":topset=k+j.height+5,leftset=h+j.width+5}e.css("top",topset+"px").css("left",leftset+"px").css("width",b+"px").fadeIn("fast").delay(d.duration).queue(function(){a(this).dequeue()})}).delay(d.duration).queue(function(){a(d.element).removeClass(d.marker);
d.ftriger=="E"&&y(d);g>=f&&(a("#EHtooltip").remove(),a("#EHpointer").remove(),clearInterval());a(this).dequeue()}).fadeTo(1,1,b)})()}n.animatedvp==true&&(A=setInterval(E,250));return false}function B(b){a(".EHtooltipc").remove();a(".EHtooltmp").remove();r();a.isEmptyObject(b)||a.each(b,function(b,e){var f=a(e.element).offset(),g=a(e.element).height(),d=a(e.element).width(),d=f.left+d/2,f=f.top+g/2,g="<span>"+(b+1)+"</span>";tbalign=n.autoalign!=true&&e.align!=""?e.align:z(d+5,f+5);a("<div>").addClass("EHtooltipc").html(g).attr("rel",
tbalign).attr("alt",e.desc).css("top",f+5+"px").css("left",d+5+"px").appendTo("body").fadeIn("slow");y(e)});return n.staticvp==true?x():false}function H(b){a.isEmptyObject(b)||a.each(b,function(b,e){a("#EHhelpBox").append("<a rel='"+e.rel+"' href=''>"+e.link+"</a><br/>")})}var t,q,A,n=a.extend({animatedvp:true,staticvp:true,autoalign:true,callextf:true,autolinks:true},n);return this.each(function(){var b=u,c=a(this),c=a("a[rel]",c);n.autolinks==true&&H(b);c.live("click",function(){var e=a(this),c=
a(this).attr("rel");s();a.isEmptyObject(b)||a.each(b,function(a,b){if(b.rel==c)b.method=="animated"?G(e,b.path):(B(b.path),t=b.path)});return false});a(".EHclose").live("click",function(){a(".EHtooltipc").fadeOut("slow").remove();s();return false});a(".EHstopAll").live("click",function(){s();return false});a(".EHtooltmp").live("mouseleave",function(){a(this).remove()});a(".EHtooltipc").live("mouseover",function(){a(".EHtooltmp").remove();var b=a(this).attr("rel"),c=a(this).offset(),g=a(this).outerWidth();
a(this).outerHeight();var d=a(this).find("span").html(),d=t[d-1].desc;switch(b){case "L":case "LT":case "LB":b=a("<div>").addClass("EHtooltmp").html("<p>"+d+"</p><a href='' class='EHclose'>X</a>").appendTo("body");tmpboxWidth=b.width();tmpboxHeight=b.height();b.css("top",c.top+"px").css("left",c.left-tmpboxWidth+"px").css("width",tmpboxWidth+"px").css("border-right","0px").fadeIn("slow");break;case "R":case "RT":case "RB":b=a("<div>").addClass("EHtooltmp").html("<p>"+d+"</p><a href='' class='EHclose'>X</a>").appendTo("body"),
tmpboxWidth=b.width(),tmpboxHeight=b.height(),b.css("top",c.top+"px").css("left",c.left+g+"px").css("width",tmpboxWidth+"px").css("border-left","0px").fadeIn("slow")}});a(window).resize(function(){B(t);n.staticvp==true&&x()});a(window).scroll(function(){n.staticvp==true&&x()});a(window).keydown(function(a){(a.keyCode=="27"||a.which=="27")&&s()});a(document).keydown(function(a){(a.keyCode=="27"||a.which=="27")&&s()})})}})})(jQuery);
var ehelp_topics=[{rel:"interface",method:"animated",path:[{element:"#toolbarHolder",desc:"Application main menu. Hover your mouse over options to see various commands",duration:4E3,align:"LT",marker:"EHpointer"},{element:"#dblist",desc:"List of databases on the server. Selection shows the database you are currently working with.",duration:4E3,align:"LT",marker:"EHpointer"},{element:"#object_list",desc:"Database objects e.g. tables, views, functions are displayed here",duration:4E3,align:"LT",marker:"EHpointer"},
{element:"#screenContent",desc:"Content area contains query results, success/error messages and other information",duration:4E3,align:"LT",marker:"EHpointer"},{element:"#sqlEditFrame",desc:"Multiple Sql Editors. You can switch between the editors by shortcuts keys to work with multiple queries without overwriting them",duration:6E3,align:"LT",marker:"EHpointer"},{element:"#nav_bar",desc:"Buttons for performing various result related operations.<br />As you work with results, additional buttons appear here based on the editing state of results",
duration:8E3,align:"LT",marker:"EHpointer"}]},{rel:"queries",method:"animated",path:[{element:"#sqlEditFrame",desc:"Type sql query in one of the sql editors, or select a part of the text to be executed as a query",duration:4E3,align:"LT",marker:"EHpointer"},{element:"#nav_query",desc:"Click on the [Query] button, or press Ctrl+Enter to run the query",duration:3E3,align:"LT",marker:"EHpointer"},{element:"#nav_queryall",desc:"To execute multiple queries at once, Click [Query All] or press Ctrl+Shift+Enter",
duration:3E3,align:"LT",marker:"EHpointer"},{element:"#screenContent",desc:"Successful query results will show up in the result pane<br />If an error occurs, the error will be shown in the messages pane",duration:6E3,align:"LT",marker:"EHpointer"}]},{rel:"editing_results",method:"animated",path:[{element:"#screenContent",desc:"Once results appear in the results pane after a query or table selection, you can:<br />- Sort the results by clicking the column headers<br />- Use the checkboxes in second column to select records for deletion<br />- Double click a cell to edit its value<br />- Use the [Add Record] button to add a new record (which can be later saved by [Update records] button)",
duration:1E4,align:"R",marker:"EHpointer"},{element:"#nav_bar",desc:"Once you have selected/edited the records you want to update, use one of the buttons on the navigation bar to perform an operation",duration:5E3,align:"L",marker:"EHpointer"},{element:"#nav_bar",desc:"Press [Update records] to generate and execute update queries for edited records",duration:4E3,align:"L",marker:"EHpointer"},{element:"#nav_bar",desc:"Press [Delete records] to generate and execute queries to delete selected records",
duration:4E3,align:"L",marker:"EHpointer"},{element:"#nav_bar",desc:"Press [Generate SQL] to only generate update/delete queries. You can then inspect the generated sql text and execute as desired after any modification",duration:1E4,align:"L",marker:"EHpointer"},{element:"#screenContent",desc:"Success/error messages and affected records will be shown in the messages pane",duration:5E3,align:"L",marker:"EHpointer"}]},{rel:"objects",method:"animated",path:[{element:"#object_list",desc:"Click on any of the objects in the database to perform default action.<br />Clicking on tables/views shows their data in the results pane.<br />For other objects, their creation command is shown by default",
duration:1E4,align:"LT",marker:"EHpointer"},{element:"#object_list",desc:"Right click on any object to see a list of commands that can be performed on that object",duration:8E3,align:"LT",marker:"EHpointer"},{element:"#object_list",desc:"Some of the commands generate sql queries when executed, which are then automatically added to the first sql editor",duration:7E3,align:"LT",marker:"EHpointer"}]}],showEHelp=function(){$("body").append('<div id="EHhelpOverlay" class="ui-widget-overlay"></div><div id="EHhelpBox" class="ui-widget-header"><p class="title">Quickstart Tutorials</p><a rel="interface" href="#">Introduction to interface elements</a><a rel="queries" href="#">Performing queries</a><a rel="editing_results" href="#">Editing and saving results</a><a rel="objects" href="#">Working with database objects</a><a id="EHstopAll" class="EHstopAll" href="#">Close Tutorial</a></div>');
$("#EHhelpBox").embeddedHelp(ehelp_topics,{animatedvp:true,staticvp:true,autoalign:true,autolinks:false});$("#EHstopAll").click(function(){$("#EHhelpOverlay,#EHhelpBox").remove();$("#ehelp_css").remove()});$("#EHhelpBox").one("click",function(){$(this).animate({top:"3%",right:"3%"})})};
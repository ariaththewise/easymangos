<?php

	/**********************************************
	*  index.php - Author: Samnan ur Rehman       *
	*  This file is a part of MyWebSQL package    *
	*  PHP5 compatible                            *
	**********************************************/
	
	define('BASE_PATH', dirname(__FILE__));

	header("Content-Type: text/html;charset=utf-8");
	include_once("lib/session.php");
	Session::init();

	include ("config/themes.php");
	include_once ("config/config.php");

	function_exists('ob_gzhandler') ? ob_start("ob_gzhandler") : ob_start();
	ob_implicit_flush(0);

	if (defined("TRACE_FILEPATH") && TRACE_FILEPATH && defined("TRACE_MESSAGES") && TRACE_MESSAGES)
		ini_set("error_log", TRACE_FILEPATH);

	include_once("lib/util.php");

	require('modules/auth.php');
	$auth_module = new MyWebSQL_Authentication();
	if (!$auth_module->authenticate()) {
		if (v($_REQUEST["q"]) == "wrkfrm")
			echo view('session_expired');
		else {
			include("modules/splash.php");
			$form = view( 'auth', array( 'LOGINID' => htmlspecialchars( $auth_module->getUserName() ) ) );
			echo getSplashScreen($auth_module->getError(), $form);
		}
		print_gzipped_output();
		exit();
	}

	$_db_info = getDBClass();
	include_once($_db_info[0]);
	$_db_class = $_db_info[1];
	$DB = new $_db_class();
	unset($_db_info);
	unset($_db_class);

	if (v($_REQUEST["db"]) && ( Session::get('db', 'name') != v($_REQUEST["db"]) ) ) {
		Session::set('db', 'changed', true);
		Session::set('db', 'name', $_REQUEST["db"]);
		if (v($_REQUEST['reload']) == '1')
			header('Location: '.EXTERNAL_PATH);
		else
			echo '<div id="results">1</div>';	// success
		exit;
	}

	if (v($_REQUEST["q"]) == "wrkfrm") {
		if (!$DB->connect(DB_HOST, DB_USER, DB_PASS, getDbName() ))
			die(showDBError());
		if (v($_REQUEST["type"]) == "dl") { // downloads
			include("modules/download.php");
			handleDownload($DB);
		} else
			doWork($DB); // usual stuff
		$DB->disconnect();
		print_gzipped_output();
		exit();
	}

	include("lib/html.php");
	include("lib/interface.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='utf-8';" />
<title>MyWebSQL</title>
	<link rel="stylesheet" type="text/css" href="cache.php?css=theme,default" />
	<link rel="SHORTCUT ICON" href="favicon.ico" />
	<link rel="stylesheet" type="text/css" href="cache.php?css=menu,treeview,results,context,alerts" />
	<script type="text/javascript" language="javascript" src="cache.php?script=jquery"></script>
	<!--[if lt IE 8]>
		<script type="text/javascript" language="javascript" src="cache.php?script=json2"></script>
	<![endif]-->
</head>
<body class="mainbody">
<?php
	if (!$DB->connect(DB_HOST,DB_USER,DB_PASS,getDbName())) {
		include("modules/splash.php");
		die(getSplashScreen(showDBError()));
	}
	if (Session::get('session', 'init') != '1') {
		// session just started, so we load information here
		Session::set('db', 'user', $DB->getCurrentUser(), true);
		Session::set('session', 'init', 1);
	}
?>
	<div id="editToolbar">
		<div class="tb-header"><?php echo __('Quick Edit Options'); ?></div>
		<div class="tb-row">[ <?php echo str_replace('{{KEY}}', $KEY_CODES['KEYCODE_SETNULL'][1], __('Press {{KEY}} to set NULL')); ?> ]</div>
	</div>

	<div id="inplace-text">
		<div class="tb-row"><textarea></textarea></div>
	</div>

	<div class="ui-layout-north">
		<div id="main_header">
			<a target="_blank" href="<?php echo PROJECT_SITEURL;?>"><img src="img/logo.png" class="logo" alt="MyWebSQL" width="45" height="38" border="0" /></a>
			<div class="title">
				<div class="main">MyWebSQL</div>
				<div class="version"><?php echo __('version') . ' ' . APP_VERSION; ?></div>
			</div>
			<div class="info">
				<span class="server"><?php echo htmlspecialchars(Session::get('auth', 'server_name', true)); ?></span> - <?php echo htmlspecialchars(Session::get('db', 'version_comment')); ?>&nbsp;<?php echo htmlspecialchars(Session::get('db', 'version_full')); ?><br />
				<?php echo str_replace('{{USER}}', htmlspecialchars(Session::get('db', 'user', true)), __('Logged in as: {{USER}}')); ?>
			</div>
		</div>
		<div id="toolbarHolder">

<?php
		createMenuBar();
?>
	</div>
</div>

<div class="ui-layout-west">

	<div class="ui-state-default" id="db_combo">
		<?php $db_list = printDbList($DB); ?>
	</div>

	<div id="object_list" class="ui-state-default">
		<?php createDatabaseTree($DB, $db_list); ?>
	</div>

</div>

<div class="ui-layout-center">
	<div id="screenContent" class="ui-layout-data-center">
		<ul>
			<li><a href="#tab-results" id="headerResults"><?php echo __('Results'); ?></a></li>
			<li><a href="#tab-messages" id="headerMessages"><?php echo __('Messages'); ?></a></li>
			<li><a href="#tab-info" id="headerInfo"><?php echo __('Information'); ?></a></li>
			<li><a href="#tab-history" id="headerHistory"><?php echo __('History'); ?></a></li>
		</ul>

		<div class="ui-layout-content ui-corner-bottom">
			<div id="tab-results">
				<div id="results-div"><div class="message" style="text-align:center; padding:20px"><?php echo __('There are no results to show in this view'); ?></div></div>
				<div id="rec_pager">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" class="ui-dialog-statusbar ui-widget-statusbar">
					<tr>
						<td id="recordCounter" class="footer" nowrap="nowrap">&nbsp;</td>
						<td id="timeCounter" class="footer" nowrap="nowrap">&nbsp;</td>
						<td id="modifyFlag" class="footer" nowrap="nowrap"><!--button id="nav_refresh">Refresh results</button--></td>
						<td id="messageContainer" class="footer" nowrap="nowrap"><?php echo __('Please wait'); ?> ...</td>
						<td id="pagingContainer" class="footer" nowrap="nowrap"></td>
					</tr>
					</table>
				</div>
			</div>
			<div id="tab-messages">
				<div id="messages-div"></div>
			</div>
			<div id="tab-info" class="ui-widget">
				<div id="info-div"></div>
			</div>
			<div id="tab-history">
				<table id="sql-history"><tbody><tr><td></td></tr></tbody></table>
			</div>
		</div>
	</div>
	<div id="sql-editor-pane" class="ui-layout-data-south">

		<ul>
			<li><a href="#editor_container"><?php echo __('Sql Editor'); ?></a></li>
			<li><a href="#editor_container2"><?php echo __('Sql Editor'); ?> 2</a></li>
			<li><a href="#editor_container3"><?php echo __('Sql Editor'); ?> 3</a></li>
		</ul>

		<div id="editor_container">
			<textarea class="sql-editor" id="commandEditor" name="commandEditor" rows="5" cols="40"></textarea>
		</div>
		<div id="editor_container2">
			<textarea class="sql-editor" id="commandEditor2" name="commandEditor2" rows="5" cols="40"></textarea>
		</div>
		<div id="editor_container3">
			<textarea class="sql-editor" id="commandEditor3" name="commandEditor3" rows="5" cols="40"></textarea>
		</div>

		<div id="nav_bar">
			<button id="nav_queryall"><?php echo __('Query All'); ?></button>
			<button id="nav_query"><?php echo __('Query'); ?></button>
			<button id="nav_addrec"><?php echo __('Add Record'); ?></button>
			<button id="nav_delete"><?php echo __('Delete Record(s)'); ?></button>
			<button id="nav_update"><?php echo __('Update Record(s)'); ?></button>
			<button id="nav_gensql"><?php echo __('Generate SQL'); ?></button>
		</div>
		
		<div id="loader">
			<img width="60" height="12" id="loaderImg" src="themes/<?php echo THEME_PATH; ?>/images/loading.gif" alt="<?php echo __('Loading'); ?>..." />
		</div>

	</div>
</div>

<div class="ui-layout-south">
	<div id="taskbar" class="ui-state-default">
		<button class="min-all" title="<?php echo __('Minimize All'); ?>"><?php echo __('Minimize All'); ?></button>
	</div>
</div>
<?php include('modules/views/dialogs.php'); ?>
<iframe src="javascript:false" name="wrkfrm" id="wrkfrm" frameborder="0" width="0" height="0"></iframe>
<div id="screen-wait" class="ui-widget-overlay">
	<div><span><?php echo __('Loading'); ?>...</span><img src="themes/<?php echo THEME_PATH; ?>/images/loading.gif" /></div>
	<div class="compat-notice" style="display:none;margin:200px auto;width:530px;color: #222222;font-family:segoe ui;font-size:13pt;font-weight:bold">
		<?php echo __('Your browser appears to be very old and does not support all features required to run MyWebSQL.'); ?><br /><br />
		<?php echo __('Try using a newer version of the browser to run this application.'); ?>
	</div>
</div>
<script type="text/javascript" language="javascript">
	var THEME_PATH = "<?php echo THEME_PATH;?>";
	var EXTERNAL_PATH = "<?php echo EXTERNAL_PATH; ?>";
	var COOKIE_LIFETIME = <?php echo COOKIE_LIFETIME; ?>; // hours
	var sqlEditMode = "<?php echo getSqlEditorType(); ?>";
	var MAX_TEXT_LENGTH_DISPLAY = <?php echo MAX_TEXT_LENGTH_DISPLAY; ?>;
	var APP_LANGUAGE = "<?php echo LANGUAGE; ?>";
	var APP_VERSION = "<?php echo APP_VERSION; ?>";
	var DB_DRIVER = "<?php echo Session::get('db', 'driver'); ?>";
	var DB_VERSION = <?php echo Session::get('db', 'version'); ?>;
	var BACKQUOTE = "<?php echo $DB->getBackQuotes(); ?>";
	var commandEditor = null;
	var commandEditor2 = null;
	var commandEditor3 = null;
<?php
	if (Session::get('db', 'changed')) {
		echo 'document.getElementById("messageContainer").innerHTML = "Database changed to: '.htmlspecialchars(Session::get('db', 'name')).'";';
		Session::del('db', 'changed');
	}
	else
		echo 'document.getElementById("messageContainer").innerHTML = "Connected to: '.DB_HOST.' as '.DB_USER.'";';
?>
</script>
<script type="text/javascript" language="javascript" src="cache.php?script=layout,ui,dialogs,context,alerts,cookies,select,interface,options,treeview,common,taskbar,settings,query,tables,sorttable,clipboard"></script>
<?php
	$DB->disconnect();

	createContextMenus();

	updateSqlEditor();

	setupHotkeys();
	
	generateJS();
?>
</body></html>
<?php
	print_gzipped_output();
?>
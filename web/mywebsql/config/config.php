<?php

	/****************************************
	 * Change the setting below if you like *
	 ****************************************/

	include(dirname(__FILE__).'/lang.php');		// we have to include language first for proper settings
	define('TRACE_MESSAGES', FALSE);			// logs verbose stuff in the error log file (only enable for debugging)
	define('TRACE_FILEPATH', "");				// if logs are to be directed to a separate file other than the default
	define('LOG_MESSAGES', FALSE);				// enabling this will send 'critical' messages to the default log file (including failed queries)
	define('MAX_RECORD_TO_DISPLAY', 100);		// only this much records will be shown in browser at one time to keep it responsive
	define('MAX_TEXT_LENGTH_DISPLAY', 80);		// blobs/text size larger than this is truncated in grid view format
	define('SQL_EDITORTYPE', "codemirror");		// valid values are [codemirror|codemirror2], defaults to codemirror
	define('HOTKEYS_ENABLED', TRUE);			// enable hotkeys
	define('SECURE_LOGIN', TRUE);				// avoid sending plain text login info for additional security (disabled for HTTPS automatically)
	
	if(!defined('THEME_PATH'))
		define('THEME_PATH', 'default');		// use this theme as default when user has not selected any theme
	
	/****************************************
	 * You should not change anything below *
	 *  Unless you know what you are doing  *
	 ****************************************/

	include_once(dirname(__FILE__).'/constants.php');
	include(dirname(__FILE__).'/auth.php');
	include(dirname(__FILE__).'/keys.php');

?>
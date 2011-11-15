<?php

	/********************************************************************
	*	functions.php - Author: Samnan ur Rehman                         *
	*	This file is a part of MyWebSQL package                          *
	*	Core functions for server side mysql related functionality       *
	*	PHP5 compatible                                                  *
	*********************************************************************/

	function __($text) {
		if (LANGUAGE == 'en') return $text;
		include('lang/'.LANGUAGE.'.php');
		return ( isset($LANGUAGE[$text]) ? $LANGUAGE[$text] : $text );
	}

	function generateJS() {
		if (LANGUAGE == 'en') return;
		
		$script = '<script language="javascript" type="text/javascript">'."\nwindow.lang = {\n";
		include('lang/'.LANGUAGE.'.php');
		foreach($LANGUAGE_JS as $key=>$txt)
			$script .= '"'.htmlspecialchars($key).'":"'.htmlspecialchars($txt)."\",\n";
			
		$script .= "\"MyWebSQL\":\"MyWebSQL\"\n};\n</script>\n";
		
		echo $script;
	}
	
	function traceMessage($str) {
		if (defined("TRACE_MESSAGES") && TRACE_MESSAGES) {
			ob_start();
				print_r($str);
				$str = ob_get_contents();
			ob_end_clean();
			error_log($str);
		}
	}

	function log_message($str) {
		if (defined('LOG_MESSAGES') && LOG_MESSAGES)
			error_log($str);
	}

	function print_gzipped_output() {
		if (function_exists('ob_gzhandler')) {
			ob_end_flush();
			return true;
		}
		
		$HTTP_ACCEPT_ENCODING = $_SERVER["HTTP_ACCEPT_ENCODING"];
		if( headers_sent() )
			$encoding = false;
		else if( strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== false )
			$encoding = 'x-gzip';
		else if( strpos($HTTP_ACCEPT_ENCODING,'gzip') !== false )
			$encoding = 'gzip';
		else
			$encoding = false;

		if( $encoding && function_exists("gzcompress") ) {
			$contents = ob_get_clean();
			$_temp1 = strlen($contents);
			if ($_temp1 < 2048)		// no need to waste time in compressing very little data
				print($contents);
			else {
				header('Content-Encoding: '.$encoding);
				print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
				$contents = gzcompress($contents, 9);
				$contents = substr($contents, 0, $_temp1);
				$_temp2 = strlen($contents);
				print($contents);
				traceMessage("$encoding: [$_temp1][$_temp2]");
			}
		}
		else
			ob_end_flush();
	}

	function matchFileHeader(&$str, $hdr) {
		if (is_array($hdr)) {
			foreach($hdr as $v) {
				if ( substr($str, 0, strlen($v)) == $v)
					return true;
			}
		}
		else
			return  ( substr($str, 0, strlen($hdr)) == $hdr);

		return false;
	}
	
	function bytes_value($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}
		return $val;
	}
	
	/* the reverse of bytes_value */
	function format_bytes($val) {
		if ($val < 1024)
			return $val.' B';
		$size = ($val < 1024*1024) ? number_format($val/1024).' KB' : number_format($val/(1024*1024), 2).' MB';
		return $size;
	}
	
	// enable encryption on login page only if we are not using HTTPS
	function secureLoginPage() {
		return (defined('SECURE_LOGIN') && SECURE_LOGIN==TRUE && v($_SERVER["HTTPS"]) == '') ? TRUE : FALSE;
	}	

	function view($name, $replace = array(), $data = NULL) {
		ob_start();
		
		// if a list of views is provided, load the one that is found first
		if (is_array($name)) {
			foreach($name as $view_name) {
				if (file_exists(BASE_PATH . '/modules/views/' . $view_name . '.php')) {
					include(BASE_PATH . '/modules/views/' . $view_name . '.php');
					break;
				}
			}
		} else
			include(BASE_PATH . '/modules/views/' . $name . '.php');
		$file = ob_get_clean();
		if (count($replace) > 0) {
			$find = array();
			foreach($replace as $key => $value)
				$find[] = '{{' . $key . '}}';
			$replace = array_values($replace);
			return str_replace($find, $replace, $file);
		}
		return $file; 
	}
	
	function getLanguageList() {
		include ("config/lang.php");
		foreach (glob("lang/*.php") as $lang)
			$langList[substr($lang, 5, -4)] = '';
		// keeping english as the first choice always		
		$langList = array_intersect_key($_LANGUAGES, $langList);
		return $langList;
	}
	
	function getServerList() {
		if ( AUTH_TYPE != 'LOGIN')
			return false;
		
		include ("config/servers.php");
		return $SERVER_LIST;
	}
	
	function createModuleId( $mod ) {
		return $mod . '-' . microtime();
	}

?>
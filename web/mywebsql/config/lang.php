<?php

	/**********************************
	 *  config/lang.php               *
	 *  Supported language listings   *
	 **********************************/

	$_LANGUAGES = array (
		'en' => 'English',
		'af' => 'Afrikaans',
		'sq' => 'Albanian',
		'bg' => 'Bulgarian',
		'ca' => 'Catalan',
		'zh' => 'Chinese',
		'hr' => 'Croatian',
		'cs' => 'Czech',
		'da' => 'Danish',
		'nl' => 'Dutch',
		'es' => 'Spanish',
		'et' => 'Estonian',
		'fi' => 'Finnish',
		'fr' => 'French',
		'gl' => 'Galician',
		'de' => 'German',
		'el' => 'Greek',
		'he' => 'Hebrew',
		'hu' => 'Hungarian',
		'id' => 'Indonesian',
		'it' => 'Italian',
		'ja' => 'Japanese',
		'ko' => 'Korean',
		'lt' => 'Lithuanian',
		'lv' => 'Latvian',
		'ms' => 'Malay',
		'no' => 'Norwegian',
		'pl' => 'Polish',
		'pt' => 'Portuguese',
		'ro' => 'Romanian',
		'ru' => 'Russian',
		'sk' => 'Slovak',
		'sl' => 'Slovenian',
		'sr' => 'Serbian',
		'sv' => 'Swedish',
		'th' => 'Thai',
		'tr' => 'Turkish',
		'uk' => 'Ukrainian'
	);
	
	if (!defined('LANGUAGE')) {
		$_lang = 'en';
		if (isset($_REQUEST["lang"]) && array_key_exists($_REQUEST["lang"], $_LANGUAGES) && file_exists('lang/'.$_REQUEST["lang"].'.php')) {
			$_lang = $_REQUEST["lang"];
			setcookie("lang", $_REQUEST["lang"], time()+(COOKIE_LIFETIME*60*60), EXTERNAL_PATH);
		}
		else if (isset($_COOKIE["lang"]) && array_key_exists($_COOKIE["lang"], $_LANGUAGES) && file_exists('lang/'.$_COOKIE["lang"].'.php'))
			$_lang = $_COOKIE["lang"];
		else if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$_temp_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
			if (array_key_exists($_temp_lang, $_LANGUAGES) && file_exists('lang/'.$_temp_lang.'.php'))
				$_lang = $_temp_lang;
			unset($_temp_lang);
		}
			
		define("LANGUAGE", $_lang);	
	}

?>
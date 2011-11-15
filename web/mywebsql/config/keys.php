<?php

	// definition of various keycodes used in browser
	
	/****************************************
	 * Change the setting below if you like *
	 ****************************************/
	
	$KEY_CODES = array(
		'KEYCODE_SETNULL'        => array('shift+del', "Shift + Del"),    	// sets value to NULL during edit
		'KEYCODE_QUERY'          => array('ctrl+return', "Ctrl + Enter"), 	// single query
		'KEYCODE_QUERYALL'       => array('ctrl+shift+return', "Ctrl + Shift + Enter"),	// query all
		'KEYCODE_SWITCH_EDITOR1' => array('alt+1', "Alt + 1"),
		'KEYCODE_SWITCH_EDITOR2' => array('alt+2', "Alt + 2"),
		'KEYCODE_SWITCH_EDITOR3' => array('alt+3', "Alt + 3")
	);

	/******************************************
	 * DO NOT change anything below this line *
	 ******************************************/
	
	$DOCUMENT_KEYS = array(
		'KEYCODE_SETNULL'       => 'closeEditor(true, null)',
		'KEYCODE_SWITCH_EDITOR1' => 'switchEditor(0)',
		'KEYCODE_SWITCH_EDITOR2' => 'switchEditor(1)',
		'KEYCODE_SWITCH_EDITOR3' => 'switchEditor(2)'
		
	);
	
	$SIMPLE_KEYS = array(
		'KEYCODE_QUERY'     => 'queryGo(0)',
		'KEYCODE_QUERYALL'  => 'queryGo(1)'
	);
	
	$CODEMIRROR_KEYS = array(
		'KEYCODE_QUERY'     => 'queryGo(0)',
		'KEYCODE_QUERYALL'  => 'queryGo(1)',
		'KEYCODE_SWITCH_EDITOR1' => 'switchEditor(0)',
		'KEYCODE_SWITCH_EDITOR2' => 'switchEditor(1)',
		'KEYCODE_SWITCH_EDITOR3' => 'switchEditor(2)'
	);
	
	$CODEMIRROR2_KEYS = array(
		'KEYCODE_QUERY'     => 'queryGo(0)',
		'KEYCODE_QUERYALL'  => 'queryGo(1)'
	);
?>
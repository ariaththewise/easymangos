<?php

	/**********************************************
	*  upload.php - Author: Samnan ur Rehman      *
	*  This file is a part of MyWebSQL package    *
	*  Contains code for data import (uploads)    *
	*  PHP5 compatible                            *
	***********************************************/

	function processRequest(&$db) {
		/*$targetFolder = '/tmp'; // Relative to the root
		if (!empty($_FILES)) {
			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
			$targetFile = rtrim($targetPath,'/') . $_FILES['Filedata']['name'];
			
			// Validate the file type
			$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
			$fileParts = pathinfo($_FILES['Filedata']['name']);
			
			if (in_array($fileParts['extension'],$fileTypes)) {
				move_uploaded_file($tempFile, $targetFile);
				echo '1';
			} else {
				echo 'Invalid file type';
			}
		}*/
	}

?>
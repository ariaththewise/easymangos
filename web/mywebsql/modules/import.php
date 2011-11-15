<?php

	/**********************************************
	*  import.php - Author: Samnan ur Rehman      *
	*  This file is a part of MyWebSQL package    *
	*  Contains code for data import              *
	*  PHP5 compatible                            *
	***********************************************/

	function processRequest(&$db) {
		$importDone = FALSE;
		$message = '';
		$max_upload_size = min(bytes_value(ini_get('post_max_size')), bytes_value(ini_get('upload_max_filesize'))) / 1024;
		$max_upload_size_text = ($max_upload_size < 1024) ? $max_upload_size.'KB' : ($max_upload_size/1024).' MB';
		
		if (isset($_FILES['impfile'])) {
			if (v($_FILES['impfile']['tmp_name']) != '' && file_exists($_FILES['impfile']['tmp_name'])) {
				$module_id = createModuleId( 'import' );
				include("lib/sqlparser.php");
				$parser = new sqlParser($db);
				$parser->stopOnError(v($_REQUEST['ignore_errors']) == 'yes' ? FALSE : TRUE);
				//$parser->setCallback( 'report_progress', $module_id );
				//$parser->collectStats(v($_REQUEST['stats']) == 'yes');
				$result = $parser->parse($_FILES['impfile']['tmp_name'], $_FILES['impfile']['size'], $_FILES['impfile']['name']);

				$executed = $parser->getExecutedQueries();
				$failed = $parser->getFailedQueries();
				if (!$result || $executed > 0 || $failed > 0) {
					$message .= '<div class="success">'.str_replace('{{NUM}}', $executed,  __('{{NUM}} queries successfully executed'));
					$message .= '<br />[ ' . str_replace('{{NUM}}', $parser->getRowsAffected(), __('{{NUM}} record(s) were affected')) . '. ]</div>';
					if ($failed > 0) {
						$message_tmp .= ($failed > 1) ? str_replace('{{NUM}}', $failed, __('{{NUM}} queries failed to execute')) : __('Error occurred while executing the query');
						$message .= '<div class="warning">'.$message_tmp.'</div>';
						if ($failed == 1) {
							$message .= '<div class="sql_error">' . htmlspecialchars($parser->getLastQuery()) . '</div>';
							$message .= '<div class="message">' . htmlspecialchars($parser->getError()) . '</div>';
						}
					}
				}
				else
					$message .= '<div class="success">'.__('No queries were executed during import').'.</div>';
			}
			else
				$message .= '<div class="warning">'.__('File upload failed. Please try again').'.</div>';
			
			$importDone = TRUE;
		}
		
		if (!$importDone) {
			$message = '<div class="sql_text">'.str_replace('{{SIZE}}', $max_upload_size_text, __('Maximum upload filesize is {{SIZE}}'));
			$message .= '<br/>' . str_replace('{{LIST}}', valid_import_files(), __('Supported filetypes / extensions are: ({{LIST}})')) . '</div>';
		}
		
		$replace = array( 'MESSAGE' => $message, 'MAX_SIZE' => $max_upload_size );
		echo view('import', $replace);
	}

	function valid_import_files() {
		$files = '*.sql, *.txt';
		if (function_exists('bzopen'))
			$files .= ', *.bz, *.bzip, *.bz2';
		if (function_exists('gzopen'))
			$files .= ', *.gz, *.gzip';
		return $files;
	}

	function report_progress( $module_id, $executed ) {
		$st = Session::get( 'status', $module_id );
		Session::set( 'status', $module_id, $executed );
	}
	
	function getModuleStatus( $id ) {
		$st = Session::get( 'status', $id );
		if ( $st['c'] == '100' ) {
			Session::del( 'status', $id );
			$st['r'] = '1';
		}
		return $st;
	}
?>
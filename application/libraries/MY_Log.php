<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class MY_Log extends CI_Log {
	
	var $logs = array();
	var $_log_name = 'CIapp';
	
	function write_log($level = 'error', $msg, $php_error = FALSE) {
		if(preg_match('/modify header information/', $msg)) { return FALSE; } // a dumb error that we should never show
		if ($this->_enabled === FALSE) { return FALSE; }
		$level = strtoupper($level);
		
		$config =& get_config();
		if(function_exists('get_instance') && class_exists('CI_Controller')) {
			$CI = &get_instance();
		}
		
		if($level == 'SYSLOG') { // only honor this special log level
			if(isset($CI) && $CI->input->is_cli_request()) {
				echo preg_replace('/[\r\n]+$/', "\n", $msg."\n");
			}
			
			$msg = preg_replace('/[\r\n]+/', " ", $msg."\n");
			
			// say where we came from:
			$fn_space = 15;
			$bt = debug_backtrace();
			
			// determine if we were called from the "say" helper function:
			$bt_index = 1;
			if($bt[2]['function'] == 'say') { $bt_index = 2; }
			
			$ct = $bt[$bt_index];
			$callInfo = substr(str_repeat(' ', $fn_space).$ct['file'].':', -1 * $fn_space, $fn_space).substr(str_repeat(' ', 5).$ct['line'].':', -5, 5);
			$msg = $callInfo.$msg;
			
			openlog($this->_log_name, LOG_PID, LOG_USER);
			syslog(LOG_WARNING, preg_replace('/[\r\n]+$/', "\n", $msg."\n")); // this can depend on system
			closelog();
		}
	}
}

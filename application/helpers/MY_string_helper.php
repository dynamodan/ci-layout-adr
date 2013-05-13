<?php

if(!function_exists('zpad')) {
	function zpad($num, $length = 5) {
		return sprintf('%0'.$length.'d', $num);
	}
}

# shorter form to a logging command:
if(!function_exists('say')) {
	function say($msg) {
		log_message('SYSLOG', $msg);
	}
}
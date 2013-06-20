<?php

if(!function_exists('is_loggedin')) {
	function is_loggedin() {
		$CI = &get_instance();
		return $CI->login->authenticate();
	}
}


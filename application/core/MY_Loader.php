<?php

// this class allows setting the path to the views folder if you want
// it different than the built-in CI one.
// also, there's a custom view function so you can inject some commonly
// needed variables

class My_Loader extends CI_Loader {
   function __construct() {
      //Change this property to match your new path
      $this->_ci_view_paths = array(APPPATH.'views/' => TRUE);
      $this->_ci_ob_level  = ob_get_level();
      $this->_ci_library_paths = array(APPPATH, BASEPATH);
      $this->_ci_helper_paths = array(APPPATH, BASEPATH);
      $this->_ci_model_paths = array(APPPATH);
      log_message('debug', "Custom Loader Class Initialized");
   }
   
   	public function get_view_paths($index = null) {
		if($index === null) {
			return $this->_ci_view_paths;
		} else {
			$i = 0;
			foreach($this->_ci_view_paths as $k => $v) {
				if($i == $index) { return $k; }
			}
			$i++;
		}
	}
	
	// if any vars need to be inserted, here's the place
	public function view($view, $vars = array(), $return = FALSE) {
		$CI = &get_instance();
		
		$vars['defaultTableRows'] = $CI->defaultTableRows;
		$vars['pagerSelector'] = parent::view('pagerSelector', $vars, true);
		
		return parent::view($view, $vars, $return);
	}

}

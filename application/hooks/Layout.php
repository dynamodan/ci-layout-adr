<?php

class Layout {

	function setup() {
		$CI = &get_instance();
		$ctrl = $CI->uri->segment(1);
		if(!isset($CI->user_tz_offset)) { $CI->user_tz_offset = 0; }
		
		// set template variables
		// customize these to your particular liking
		$CI->template_vars = array(
			'session'	=> $CI->login,
			'defaultTableRows' => $CI->defaultTableRows,
			'user_tz_offset' => &$CI->user_tz_offset,
		);
		
		// set up some defaults if they are not:
		if(!isset($CI->default_header)) { $CI->default_header = 'default_header'; }
		if(!isset($CI->default_body)) { $CI->default_body = 'default_body'; }
		if(!isset($CI->default_footer)) { $CI->default_footer = 'default_footer'; }
		
	}
	
	function display() {
		
		$CI = &get_instance();
		$ctrl = '';
		$valid_ctrl = $CI->uri->segment(1);
		
		// make view files match controllers somewhat:
		foreach($CI->uri->segment_array() as $seg) {
			
			// check for file
			if(file_exists($CI->load->get_view_paths(0).$ctrl.$seg.'.php')) {
				$valid_ctrl = $ctrl.$seg;
			}
			
			$ctrl .= $seg.'_';
		}

		$CI->content = array_merge($CI->content, $CI->template_vars);
		if(isset($CI->content['session']->userData['password'])) {
			unset($CI->content['session']->userData['password']); // don't need this belching out to the template
		}
		
		// a default body if we didn't get one from the controller
		if(!file_exists($CI->load->get_view_paths(0).$valid_ctrl)) {
			$valid_ctrl = $CI->default_body; // there *must* be a default body or CI will throw an error
		}
		
		// specific template rather than the one guessed from the url path above:
		if(isset($CI->output_template)) { $valid_ctrl = $CI->output_template; }
		
		if($CI->output_method == 'display') {
			$CI->load->view($CI->default_header, $CI->content);
			$CI->load->view($valid_ctrl, $CI->content);
			$CI->load->view($CI->default_footer, $CI->content);
		} else {
			if(method_exists($this, $CI->output_method)) {
				$this->{$CI->output_method}();
			} else {
				$error = 'method '.$CI->output_method.' doesnt exist.';
				log_message('syslog', $error);
				echo $error;
			}
		}
	}
	
	// simple enough?
	function ajax() {
		$CI = &get_instance();
		$CI->output->set_content_type('application/json');
		if(!isset($CI->content['ajax'])) { $CI->content['ajax'] = array(); }
		$CI->output->set_output(json_encode($CI->content['ajax']));
	}
	
	// don't return any template, output is handled by the controller:
	function void() {
		
	}

}

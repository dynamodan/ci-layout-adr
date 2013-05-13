<?php

class MY_Controller extends CI_Controller {

	var $output_method = 'display';
	var $content = array();
	var $session = null;
	var $template_vars = array();
	var $defaultTableRows = 20;
	
	function __construct() {
		
		// first thing:
		parent::__construct();
		$ctrl = $this->uri->segment(1);
		
		if($ctrl == '') { $ctrl = 'main'; }
		
		// default date() (and possibly other functions) to UTC:
		date_default_timezone_set('UTC');
		user_date('T');
		
		$this->default_header = 'default_header';
		$this->default_footer = 'default_footer';
		$this->public_urls = array('login', 'main', 'signup', 'recoverpassword');
		
		// for the occasional url that does not need to be secure, such as faq or about us pages outside of the login
		$this->insecure_urls = array('about', 'faq', 'someinsecureurl', 'main'); // this just says not to forward to the https equivalent.
		
		// detect if the url isn't secure, and if it should not be left insecure:
		log_message('syslog', 'handling uri: '.current_url().' (for controller '.$ctrl.')');
		
		// return right here, if we're running from command line and the path is in the cli exceptions
		if($this->input->is_cli_request()) {
			log_message('syslog', 'running in CLI mode...');
			return;
		}

		if(
			preg_match('/^http\:/', current_url()) &&
			!in_array($this->uri->uri_string(), $this->insecure_urls) &&
			!in_array($this->uri->segment(1), $this->insecure_urls)			
		) {
			// forward to the https:// proto:
			$secure_url = preg_replace('/^http\:/', 'https:', current_url());
			log_message('syslog', 'redirecting to secure url: '.$secure_url.' from '.$this->uri->uri_string());
			log_message('syslog', $this->uri->segment(0));
			redirect($secure_url);
			return;
		}
		
		// make sure the user is logged in, forward back to the login url if not
		$this->load->model('login');
		if (!$this->login->authenticate() && !in_array($ctrl, $this->public_urls)) {
			// $this->login->logout();
			redirect('main');
			return;
		}
	}


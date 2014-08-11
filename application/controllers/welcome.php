<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

	public function index() {
		$this->output_template = 'welcome_message';
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */

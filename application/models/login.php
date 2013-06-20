<?php

class Login extends CI_Model {

	function __construct() {
	
	}
	
	function authenticate() {
		return false;	
	}
	
	function logout() {
		return true;
	}

}

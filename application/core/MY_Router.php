<?php

class MY_Router extends CI_Router {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function _validate_request($segments) {
		if (count(explode('.', $_SERVER['HTTP_HOST'])) > 2) {
			if ($segments == null)
				$segments = array();
			if (explode('.', $_SERVER['HTTP_HOST'])[0] == 'ssapp-api-v1'){
				array_unshift($segments, 'api');
			}
		}
		
		return parent::_validate_request($segments);
	}
}
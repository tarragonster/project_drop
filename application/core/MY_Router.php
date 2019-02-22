<?php

class MY_Router extends CI_Router {
	protected $group = '';

	public function __construct() {
		$HTTP_HOST = str_replace('www.', '', $_SERVER['HTTP_HOST']);
		$first_uri = explode('.', $HTTP_HOST)[0];

		if ($first_uri == 'admin') {
			$this->group = 'admin';
		} else if ($first_uri == 'swagger') {
			$this->group = 'swagger';
		} else if ($first_uri == 'ssapp-api-v1' || $first_uri == 'api') {
			$this->group = 'api';
		} else if (in_array($HTTP_HOST, ['getblock10.us', 'get10block.com'])){
			$this->group = 'homepage';
		}

		parent::__construct();
	}

	public function _validate_request($segments) {
		$HTTP_HOST = str_replace('www.', '', $_SERVER['HTTP_HOST']);
		$first_uri = explode('.', $HTTP_HOST)[0];

		if ($segments == null)
			$segments = array();

		if ($first_uri == 'admin') {
			array_unshift($segments, 'admin');
		} else if ($first_uri == 'ssapp-api-v1' || $first_uri == 'api') {
			array_unshift($segments, 'api');
		} else if (in_array($HTTP_HOST, ['getblock10.us', 'get10block.com'])){
			$this->group = 'homepage';
			array_unshift($segments, 'homepage');
		}

		return parent::_validate_request($segments);
	}

	protected function _set_routing() {
		if (file_exists(APPPATH . 'config/routes.php')) {
			include(APPPATH . 'config/routes.php');
		}

		if (file_exists(APPPATH . 'config/' . ENVIRONMENT . '/routes.php')) {
			include(APPPATH . 'config/' . ENVIRONMENT . '/routes.php');
		}

		if (!(empty($this->group)) && file_exists(APPPATH . 'config/' . $this->group . '/routes.php')) {
			include(APPPATH . 'config/' . $this->group . '/routes.php');
		}

		// Validate & get reserved routes
		if (isset($route) && is_array($route)) {
			isset($route['default_controller']) && $this->default_controller = $route['default_controller'];
			isset($route['translate_uri_dashes']) && $this->translate_uri_dashes = $route['translate_uri_dashes'];
			unset($route['default_controller'], $route['translate_uri_dashes']);
			$this->routes = $route;
		}

		$this->_parse_routes();
	}
}
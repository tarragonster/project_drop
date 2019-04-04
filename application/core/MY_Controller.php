<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	protected $baseView = 'admin';
	protected $customJs = array();
	protected $customCss = array();
	protected $mainParams = array();

	public function __construct() {
		parent::__construct();
		ini_set('memory_limit', '-1');

		if (!empty($this->input->get('enable_profiler'))) {
			$this->output->enable_profiler(true);
		}
	}

	public function redirect($default = '') {
		$redirect = $this->input->get('redirect');
		if ($redirect != '')
			redirect($redirect);
		else if ($default != '')
			redirect($default);
		redirect(base_url());
	}

	public function render($layout = '', $params = array(), $parent = 1, $sub = 11) {
		$this->mainParams['parent_id'] = $parent;
		$this->mainParams['sub_id'] = $sub;
		$this->mainParams['customJs'] = $this->customJs;
		$this->mainParams['customCss'] = $this->customCss;


		if (empty($layout)) {
			die('Missing layout');
		} else {
			if (strpos($layout, "/") < 1) {
				$layout = $this->baseView . $layout;
			}
			$this->mainParams['content'] = $this->load->view($layout, $params, true);
		}

		$this->load->view('admin_main_layout', $this->mainParams);
	}

	public function pageRender($layout = '', $params = array()) {
		if (strpos($layout, "/") < 1) {
			$layout = $this->baseView . $layout;
		}
		$this->load->view($layout, $params);
	}

	public function renderLayout($layout = '', $params = array()) {
		$this->mainParams['customJs'] = $this->customJs;
		$this->mainParams['customCss'] = $this->customCss;
		if (count($params) > 0) {
			foreach ($params as $key => $value) {
				$this->mainParams[$key] = $value;
			}
		}
		if (strpos($layout, "/") < 1) {
			$layout = $this->baseView . $layout;
		}

		$this->load->view($layout, $this->mainParams);
	}

	protected function handle_post($redirect = 'admincp', $isExport = false) {
		$search = array();
		$post = $this->input->post(NULL, TRUE);
		foreach ($post as $key => $value) {
			if (!in_array($key, array('cmd', 'export'))) {
				if ($value != "") {
					if ($isExport) {
						$search[$key] = $value;
					} else {
						$search[] = $key . '=' . urlencode($value);
					}
				}
			}
		}
		if ($isExport)
			return $search;
		$query = "";
		if (count($search) > 0) {
			$query = "?" . implode('&', $search);
		}
		redirect(base_url($redirect) . $query);
	}

	protected function ajaxResponse($response = []) {
		header('Content-Type: application/json');
		echo json_encode($response, JSON_PRETTY_PRINT);
		die("");
	}

	protected function ajaxSuccess($data = []) {
		if ($data == null) {
			$response = array();
		} else {
			$response = $data;
		}
		$response['success'] = true;
		header('Content-Type: application/json');
		echo json_encode($response, JSON_PRETTY_PRINT);
		die("");
	}

	protected function ajaxError($error_message = 'Resource not found', $data = array()) {
		if ($data == null) {
			$response = array();
		} else {
			$response = $data;
		}
		$response['success'] = false;
		$response['error_message'] = $error_message;
		header('Content-Type: application/json');
		echo json_encode($response, JSON_PRETTY_PRINT);
		die("");
	}
}
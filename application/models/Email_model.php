<?php

class Email_model extends CI_Model {
	protected $base_params = [
		'home_page' => 'https://get10block.com',
		'twitter' => 'https://twitter.com/get10block/',
		'instagram' => 'https://www.instagram.com/get10block/',
		'support_email' => 'info@get10block.com'
	];

	public function __construct() {
		parent::__construct();

		$this->base_params['base_url'] = root_domain();
	}

	public function emailForgotPassword($email, $params) {
		$this->createNewEmail();
		$this->email->to($email);
		$this->email->subject('Reset your Second Screen password');
		$html = $this->loadTemplate("forgotpassword");
		$html = str_replace('{{var username}}', $params['username'], $html);
		$html = str_replace('{{var url_code}}', $params['url_code'], $html);
		$this->email->message($html);
		$this->email->send();
	}

	public function welcome($email, $params) {
		$this->createNewEmail();
		$this->email->to($email);
		$this->email->subject('Welcome');
		$html = $this->loadTemplate("welcome");
		$html = str_replace('{{var username}}', $params['username'], $html);
		$this->email->message($html);
		$this->email->send();
	}

	protected function createNewEmail($from_email = 'noreply@secondscreentv.us', $from_name = 'Second Screen') {
		$config = array('mailtype' => 'html', 'charset' => 'utf-8');
		$this->load->library('email', $config);
		$this->email->from($from_email, $from_name);
	}

	protected function loadTemplate($template) {
		$path = APPPATH . 'libraries/templates/' . $template . '.html';
		if (file_exists($path)) {
			$html = file_get_contents($path);
			foreach ($this->base_params as $key => $value) {
				$html = str_replace("{{var $key}}", $value, $html);
			}
			return $html;
		} else {
			echo null;
		}
	}
}

?>

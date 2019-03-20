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
		$this->email->subject('Reset your password');
		$html = $this->loadTemplate("forgotpassword");
		$html = str_replace('{{var username}}', $params['username'], $html);
		$html = str_replace('{{var url_code}}', $params['url_code'], $html);
		$this->email->message($html);
		$this->email->send();
	}

	public function welcome($email, $params) {
		$this->createNewEmail();
		$this->email->to($email);
		$this->email->subject('Welcome to 10 BLOCK!');
		$html = $this->loadTemplate("welcome");
		$html = str_replace('{{var username}}', $params['username'], $html);
		$this->email->message($html);
		$this->email->send();
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

	protected function createNewEmail($from_email = 'noreply@get10block.com', $from_name = '10 Block') {
		$config = array(
		    'protocol'  => 'smtp',
		    'smtp_host' => 'ssl://smtp.gmail.com',
		    'smtp_port' => 465,
		    'smtp_user' => 'dev@get10block.com',
		    'smtp_pass' => 'nIbkix-wotty9-duwraz',
		    'mailtype'  => 'html',
		    'charset'   => 'utf-8'
		);
		// $this->email->initialize($config);
		$this->load->library('email', $config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");
		$this->email->from($from_email, $from_name);
	}
}

?>

<?php

class Email_model extends CI_Model{
	public function __construct(){
		parent::__construct();
	}
	
	public function emailForgotPassword($email, $params){
		$this->createNewEmail();
		$this->email->to($email);
		$this->email->subject('Reset your Second Screen password');
		$html = $this->loadTemplate("forgotpassword");
		$html = str_replace('{{var username}}', $params['username'], $html);
		$html = str_replace('{{var url_code}}', $params['url_code'], $html);

		$this->email->message($html);
		$this->email->send();
	}

	protected function createNewEmail($from_email = 'noreply@secondscreentv.us', $from_name = 'Second Screen') {
		$config = array('mailtype' => 'html', 'charset' => 'utf-8');
		$this->load->library('email', $config);
		$this->email->from($from_email, $from_name);
	}
	protected function loadTemplate($template){
		$path = APPPATH . 'libraries/templates/' . $template . '.html';
		if (file_exists($path)) {
			$html = file_get_contents($path);
			return $html;
		} else {
			echo null;
		}
	}
}

?>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

	public function __construct() {
		parent::__construct();

		if (config_item('http_scheme') == 'http' && config_item('secure_mode')) {
			redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		}
	}

	public function index() {
		$this->load->view('sites/homepage/index', []);
	}

	public function support() {
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$email = $this->input->post('email');
			$name = $this->input->post('name');
			$content = $this->input->post('message');
			if (!empty($email) && !empty($name)) {
				$subject = 'Contact from 10Block';
				$message = '<html><body>';

				$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';

				$message .= "<tr><th>Email:</th><td>" . strip_tags($email) . "</td></tr>";
				$message .= "<tr><th>Message:</th><td>" . strip_tags($content) . "</td></tr>";

				$message .= "</table>";
				$message .= "</body></html>";

				$headers = "From: support@10-block.com\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				mail("cuongdoict@gmail.com", $subject, $message, $headers);
				mail("estella@watchsecondscreen.com", $subject, $message, $headers);
				mail("anastasia@watchsecondscreen.com", $subject, $message, $headers);
				redirect('');
			}
		}
		$this->load->view('sites/homepage/support', []);
	}

	public function privacypolicy() {
		$this->load->view('sites/homepage/privacy-policy', []);
	}

	public function register() {
		$time = time();
		$email = $this->input->post('email');
		$full_name = $this->input->post('full_name');

		if (!validate_email($email)) {
			redirect('');
		}
		if ($this->user_model->checkEmail($email)) {
			redirect('');
		}
		$user_name = explode("@", $email)[0];

		if ($this->user_model->checkUserName($user_name)) {
			$user_name = preg_replace('/[^a-z0-9]/i', '_', $full_name);
			if ($this->user_model->checkUserName($user_name)) {
				redirect('');
			}
		}
		$params = [];
		$params['email'] = $email;
		$params['password'] = '';
		$params['joined'] = $time;
		$params['last_login'] = $time;
		$params['user_name'] = $user_name;
		$params['full_name'] = $full_name;

		$this->user_model->insert($params);
		redirect('');
	}
}

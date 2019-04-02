<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function resetPassword() {
		$data = array();
		$url_code = $this->input->get('url_code');
		$data = explode('|', base64_decode($url_code));
		$validateError = '';
		if ($url_code == '' || count($data) != 2) {
			$validateError = 'Your request change password is invalid.';
		} else {
			$user_id = $data[0];
			$code = $data[1];
			if (mb_check_encoding($code, 'utf-8') == false) {
				$validateError = 'Your request change password is invalid.';
			} else {
				$checkCode = $this->user_model->checkCode($user_id, $code);
				if ($checkCode == 1) {
					$validateError = 'Your request change password is invalid.';
				} else if ($checkCode == 2 || $checkCode == 3) {
					$validateError = 'Your request change password has been expired.';
				}
			}
		}
		if (!empty($validateError)) {
			$this->session->set_flashdata('error2', $validateError);
			$data['customCss'] = array('assets/css/api/change_success.css');
			$data['content'] = $this->load->view('api/display_errors', array(), true);
			$this->load->view('api/main_layout', $data);
			return;
		}
		$data['customCss'] = array('assets/css/api/change_password.css');
		$data['customJs'] = array('assets/js/jquery.min.js', 'assets/app/frontend/change-password.js');
		$data['content'] = $this->load->view('api/change_password', array(), true);
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$password = $this->input->post('password');
			$re_password = $this->input->post('re_password');

			if ($password != $re_password) {
				$this->session->set_flashdata('error1', 'Password are not match.');
			} else if (strlen($password) < 6) {
				$this->session->set_flashdata('error1', 'Password length at least 6 characters.');
			} else {
				$this->user_model->updateCodeResetPassword($user_id, $code);
				$this->user_model->update(array('password' => md5($password)), $user_id);
				$data['customCss'] = array('assets/css/api/change_success.css');
				$data['content'] = $this->load->view('api/change_success', array(), true);
			}
		}
		$this->load->view('api/main_layout', $data);
	}

	public function genForgotLink() {
		$email = 'bethabie@gmail.com';
		$user = $this->user_model->checkEmail($email);
		if (!$user) {
			$this->create_error(-9);
		}
		$time = time();
		$code = md5(md5($email . $time . '|mDyN2U') . $time);
		$base_64 = base64_encode($user['user_id'] . '|' . $code);
		$params = array();
		$params['user_id'] = $user['user_id'];
		$params['code'] = $code;
		$params['created'] = $time;
		$this->user_model->insertCodeResetPassword($params);
		$params['url_code'] = root_domain() . '/reset-password?url_code=' . $base_64;
		$params['username'] = $user['full_name'];
		echo $params['url_code'];
	}
}

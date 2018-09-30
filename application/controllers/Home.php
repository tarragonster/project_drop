<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function resetPassword() {
		$code = $this->input->get('code');
		$arrs = explode('|', base64_decode($code));
		$user_id = $arrs[0];
		$code = $arrs[1];
		$isCheck = $this->user_model->checkCode($user_id, $code);

		if ($isCheck == 1) {
			$this->session->set_flashdata('error', 'Invalid requested password.');
		} else if ($isCheck == 2) {
			$this->session->set_flashdata('error', 'Requested password has been expired.');
		} else if ($isCheck == 3) {
			$this->session->set_flashdata('error', 'You have changed this code.');
		} else if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$password = $this->input->post('password');
			$re_password = $this->input->post('re_password');
			if ($password != $re_password) {
				$this->session->set_flashdata('error1', 'Password are not match.');
			} else if (strlen($password) < 6) {
				$this->session->set_flashdata('error1', 'Password length at least 6 characters.');
			} else {
				$this->user_model->updateCodeResetPassword($user_id, $code);
				$this->user_model->update(array('password' => md5($password)), $user_id);
				$this->session->set_flashdata('msg', 'Change password success.');
			}
		}
		$data = array();
		$data['customCss'] = array('assets/css/api/change_password.css');
		$data['content'] = $this->load->view('api/change_password', array(), true);
		$this->load->view('api/main_layout', $data);
	}
}

<?php
// require_once APPPATH . '/core/Base_Controller.php';

class Setting extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('admin_model');
	}

	public function index() {
		$params = [
			'page_index' => 'general_settings'
		];
		$this->customCss[] = 'module/css/submenu.css';
		$this->render('/setting/setting_page', $params, 5);
	}

	public function changeEmail() {
		$admin = $this->session->userdata('admin');
		if ($admin == null) {
			redirect(base_url('login'));
		}
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$current_email = $this->input->post('account_email');
			$new_email = $this->input->post('new_email');
			$password = $this->input->post('account_password');

			$account = $this->admin_model->getAdminAccountByEmail($admin['email']);
			$existEmail = $this->admin_model->getAdminAccountByEmail($new_email);

			if ($account['password'] != md5($password)) {
				$this->session->set_flashdata('error_email', 'Password is incorrect');
			}
			else if($account['email'] != $current_email) {
				$this->session->set_flashdata('error_email', 'Current email is incorrect');
			}
			else {
				if ($existEmail != null) {
					$this->session->set_flashdata('error_email', 'The email exists');
				} 
				else if(!validate_email($new_email)){
					$this->session->set_flashdata('error_email', 'The new email is invalid');
				} 
				else {
					$this->admin_model->update(array('email' => $new_email), $account['id']);
					$this->session->set_userdata('admin', array('email'=> $new_email, 'group'=> $account['group']));
					$this->session->set_flashdata('success_email', 'The email is updated');
				}
			}
		}
		$this->redirect('setting');
	}

	public function changePassword() {
		$admin = $this->session->userdata('admin');
		if ($admin == null) {
			redirect(base_url('login'));
		}
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$password = $this->input->post('account_password');
			$new_password = $this->input->post('new_password');
			$re_password = $this->input->post('re_password');

			$account = $this->admin_model->getAdminAccountByEmail($admin['email']);
			if ($account['password'] != md5($password)) {
				$this->session->set_flashdata('error_password', 'Current password is invalid');
			} 
			else if(strlen($new_password) < 6 || strlen($new_password) > 32) {
				$this->session->set_flashdata('error_password', 'Password length must be from 6 to 32 characters');
			}
			else if($re_password != $new_password) {
				$this->session->set_flashdata('error_password', 'Password does not match');
			} 
			else {
				$this->admin_model->update(array('password' => md5($new_password)), $account['id']);
				$this->session->set_flashdata('success_password', 'The password is updated');
			}
		}
		$this->redirect('setting');
	}
}
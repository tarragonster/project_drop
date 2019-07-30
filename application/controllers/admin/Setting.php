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
			if (validate_email($new_email)) {
				if ($account['password'] == md5($password) && $account['email'] == $current_email) {
					$this->admin_model->update(array('email' => $new_email), $account['id']);
					$this->session->set_userdata('admin', array('email'=> $new_email, 'group'=> $account['group']));
					$this->session->set_flashdata('success_email', 'The email is updated');
				} else {
					$this->session->set_flashdata('error_email', 'The information is incorrect');
				}
			} else {
				$this->session->set_flashdata('error_email', 'The new email is invalid');
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
			if ($account['password'] == md5($password)) {
				$this->admin_model->update(array('password' => md5($new_password)), $account['id']);
				$this->session->set_flashdata('success_password', 'The password is updated');
			} else if($new_password != $re_password) {
				$this->session->set_flashdata('error_password', 'Password does not match');
			} else {
				$this->session->set_flashdata('error_password', 'Password is invalid');
			}
		}
		$this->redirect('setting');
	}
}
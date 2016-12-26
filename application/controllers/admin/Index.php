<?php

require_once APPPATH . '/core/Base_Controller.php';

class Index extends Base_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model("admin_model");		
	}
	
	public function index() {
       
		$admin = $this->session->userdata('admin');
		if ($admin == null) {
			redirect(base_url('admin/login'));
		}
		$account = $this->admin_model->getAdminAccountByEmail($admin['email']);
		$lockdata = $this->session->userdata('lockdata');
		if ($lockdata != null) {
			redirect(base_url('admin/lockscreen'));
		}
		$data = array();
		$data['parent_id'] = 1;
		$data['sub_id'] = 10;
		$data['account'] = $account;
		// $info_dashbroad = $this->Mcatalog->getDashBroadInfo();
		$data['content'] = $this->load->view('admin/dashbroad_home', array(), true);
		$this->load->view('admin_main_layout', $data);
	}
	
	public function login() {
		$admin = $this->session->userdata('admin');
		if ($admin != null) {
			redirect(base_url('admin'));
		}
		
		$cmd = $this->input->post("cmd");
		if ($cmd != '') {
			$email = $this->input->post("email");
			$password = $this->input->post("password");
			if ($email == '' || $password == '') {
				$this->load->view('admin/login', array('error'=>'Thiếu trường'));
			} else {
				$account = $this->admin_model->getAdminAccount($email, $password);
				if ($account != null) {
					$this->session->set_userdata('admin', array('email'=>$account['email'], 'group'=>$account['group']));
					$this->redirect('admin');
				} else {
					$this->load->view('admin/login', array('error'=>'Sai thông tin đăng nhập'));
				}
			}
		} else {
			$this->load->view('admin/login');
		}
	}
	
	public function logout() {
		$this->session->unset_userdata('admin');
		$this->session->unset_userdata('lockdata');
		redirect(base_url('admin/login'));
	}
	
	public function lockscreen() {
		$admin = $this->session->userdata('admin');
		if ($admin == null) {
			redirect(base_url('admin/login'));
		}
		
		$account = $this->admin_model->getAdminAccountByEmail($admin['email']);
		$cmd = $this->input->post('cmd');
		if ($cmd == '') {
			$lockdata = $this->session->userdata('lockdata');
			if ($lockdata == null) {
				$this->session->set_userdata('lockdata', array('lock'=>1));
			}
			
			$this->load->view('admin/lockscreen', array('account'=>$account, 'other_account'=>base_url('admin/logout')));
		} else {
			$password = $this->input->post('password');
			if ($password == '') {
				$error = 'Password is missing!';
			} else {
				$account_check = $this->admin_model->getAdminAccount($admin['email'], $password);
				if ($account_check != null) {
					$this->session->unset_userdata('lockdata');
					redirect(base_url('admin'));
				} else {
					$error = 'Password is invalid!';
				}
			}
			$this->load->view('admin/lockscreen', array('account'=>$account, 'error' => $error, 'other_account'=>base_url('admin/logout')));
		}
	}
}
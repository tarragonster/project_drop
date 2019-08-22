<?php

class Index extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model("admin_model");		
		$this->load->model("dashboard_model");
		$this->load->model("email_model");
		$this->load->model('passcode_model');
	}

	public function index() {
		$this->dashboard();
	}

	public function dashboard($fromDate = '', $toDate = '', $secondFromDate = '', $secondToDate = '') {
		$admin = $this->session->userdata('admin');
		if ($admin == null) {
			redirect(base_url('login'));
		}
		if (!empty($fromDate)) {
			$startDate = strtotime($fromDate);
		} else {
			$startDate = strtotime(date('Y-m-d', time()));
		}
		if (!empty($toDate)) {
			$endDate = strtotime($toDate) + 86400;
		} else {
			$endDate = strtotime(date('Y-m-d', time())) + 86400;
		}
		if ($this->input->is_ajax_request()) {
			$dashboard = $this->dashboard_model->getDashBoard($startDate, $endDate, $secondFromDate, $secondToDate);
			header('Content-Type: application/json');
			$dashboard['success'] = true;
			echo json_encode($dashboard);
		} else {
			$this->customCss[] = 'assets/plugins/morris/morris.css';
			$this->customCss[] = 'assets/plugins/bootstrap-daterangepicker/daterangepicker.css';
			$this->customCss[] = 'assets/css/dashboard.css';
			$this->customJs[] = 'assets/vendor/peity/jquery.peity.min.js';
			$this->customJs[] = 'assets/vendor/jquery-sparkline/jquery.sparkline.min.js';
			$this->customJs[] = 'assets/plugins/moment/moment.js';
			$this->customJs[] = 'assets/vendor/moment/moment-timezone.js';
			$this->customJs[] = 'assets/plugins/bootstrap-daterangepicker/daterangepicker.js';
			$this->customJs[] = 'assets/vendor/jquery-number/jquery.number.min.js';
			$this->customJs[] = 'assets/plugins/raphael/raphael-min.js';
			$this->customJs[] = 'assets/plugins/morris/morris.min.js';
			$this->customCss[] = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css';
			$this->customJs[] = 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js';
			$this->customJs[] = 'assets/js/dashboard.js';

			$dashboard = [];
			$dashboard['top_users'] = $this->dashboard_model->countUsers();
			$dashboard['top_watched'] = $this->dashboard_model->countBlocksWatched();
			$dashboard['top_comments'] = $this->dashboard_model->countComments();
			$dashboard['top_reviews'] = $this->dashboard_model->countReviews();
			$dashboard['top_blocks'] = $this->dashboard_model->countBlocks();
			$dashboard['top_stories'] = $this->dashboard_model->countStories();
			$dashboard['top_header'] = 'admin/dashboard/top';
			$this->render('admin/dashboard/layout', $dashboard, 1, 10);
		}
	}

	public function login() {
		$admin = $this->session->userdata('admin');
		if ($admin != null) {
			redirect(base_url('dashboard'));
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
					$this->redirect('dashboard');
				} else {
					$this->load->view('admin/login', array('error'=>'Email or Password is incorrect. Please try again'));
				}
			}
		} else {
			$this->load->view('admin/login');
		}
	}
	
	public function logout() {
		$this->session->unset_userdata('admin');
		$this->session->unset_userdata('lockdata');
		redirect(base_url('login'));
	}
	
	public function lockscreen() {
		$admin = $this->session->userdata('admin');
		if ($admin == null) {
			redirect(base_url('login'));
		}
		
		$account = $this->admin_model->getAdminAccountByEmail($admin['email']);
		$cmd = $this->input->post('cmd');
		if ($cmd == '') {
			$lockdata = $this->session->userdata('lockdata');
			if ($lockdata == null) {
				$this->session->set_userdata('lockdata', array('lock'=>1));
			}
			
			$this->load->view('admin/lockscreen', array('account'=>$account, 'other_account'=>base_url('logout')));
		} else {
			$password = $this->input->post('password');
			if ($password == '') {
				$error = 'Password is missing!';
			} else {
				$account_check = $this->admin_model->getAdminAccount($admin['email'], $password);
				if ($account_check != null) {
					$this->session->unset_userdata('lockdata');
					redirect(base_url(''));
				} else {
					$error = 'Password is invalid!';
				}
			}
			$this->load->view('admin/lockscreen', array('account'=>$account, 'error' => $error, 'other_account'=>base_url('logout')));
		}
	}

	public function forgotPassword() {
		$cmd = $this->input->post("cmd");
		if ($cmd != '') {
			$email = $this->input->post('email');
			if ($email == '') {
				$error = 'Please type your valid email';
			}else {
				$admin = $this->admin_model->getAdminAccountByEmail($email);
				if ($admin != null) {
					$adminCode = $this->passcode_model->addRequestPassword($admin['id'], $group = 1);
					$params['url_code'] = base_url('reset-password/' . $adminCode);
					$params['base_url'] = base_url();
					$html = $this->email_model->emailForgotPassword($email, $params);
					$this->session->set_flashdata('mess', 'Check your email for reset password');
					$this->redirect('login');
				}else {
					$data['error'] = 'The email not linked to any existing admin account';
					$this->load->view('admin/forgot_password', $data);
				}
			}
		}else {
			$this->load->view('admin/forgot_password');
		}
	}

	public function resetPassword($code) {
		$verify = $this->passcode_model->verifyPasswordCode($code);
		if ($verify != null) {
			$data = array();
			if ($this->input->server('REQUEST_METHOD') == 'POST') {
				$password = $this->input->post('password');
				$re_password = $this->input->post('re_password');

				if (strlen($password) < 6 || strlen($password) > 32) {
					$data['error'] = 'Password length must be from 6 to 32 characters';
				} else if ($password != $re_password) {
					$data['error'] = 'Password does not match';
				} 
				if(!empty($data)) {
					$this->load->view('admin/reset_password', $data);
				}else {
					$this->admin_model->update(['password' => md5($password)], $verify['user_id']);
					$this->passcode_model->clearPasswordCode($verify['user_id']);
					$account = $this->admin_model->getObjectById($verify['user_id']);
					if ($account != null) {
						$this->session->set_userdata('admin', array('email'=>$account['email'], 'group'=>$account['group']));
						$this->load->view('admin/reset_success');
					}
				}
			}else {
				$this->load->view('admin/reset_password');
			}
		} else {
			$data['error'] = 'Your verify code is invalid';
			$this->load->view('admin/forgot_password', $data);

		}
	}
}
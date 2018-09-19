<?php
require APPPATH . '/core/BR_Controller.php';

class Login extends BR_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function facebook_post() {
		$facebook_id = $this->c_getNotNull('facebook_id');
		$email = $this->post('email');
		$device_id = $this->c_getNotNull('device_id');

		$this->load->library('oauths');

		$user = $this->user_model->getUserByFacebookId($facebook_id);
		if ($user == null && !empty($email)) {
			$user = $this->user_model->getUserByAccount($email);
		}
		if ($user != null) {
			$user_id = $user['user_id'];
			$this->user_model->update(['last_login' => time()], $user_id);

			$data = $this->__getUserProfile($user_id);
			$data['access_token'] = $this->oauths->success($user_id, $device_id);
			$this->create_success(['profile' => $data], 'Login Success');
		} else {
			$this->create_error(-80);
		}
	}

	public function google_post() {
		$google_id = $this->c_getNotNull('google_id');
		$email = $this->post('email');
		$device_id = $this->c_getNotNull('device_id');

		$this->load->library('oauths');

		$user = $this->user_model->getUserByGoogleId($google_id);
		if ($user == null && !empty($email)) {
			$user = $this->user_model->getUserByAccount($email);
		}
		if ($user != null) {
			$user_id = $user['user_id'];
			$this->user_model->update(['last_login' => time()], $user_id);

			$data = $this->__getUserProfile($user_id);
			$data['access_token'] = $this->oauths->success($user_id, $device_id);
			$this->create_success(['profile' => $data], 'Login Success');
		} else {
			$this->create_error(-80);
		}
	}
}
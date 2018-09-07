<?php
require APPPATH . '/core/BR_Controller.php';

class Login extends BR_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function facebook_post() {
		$email = $this->c_getNotNull('email');
		$device_id = $this->c_getNotNull('device_id');

		$this->load->library('oauths');

		$user = $this->user_model->getUserByAccount($email);
		if ($user == null) {
			$full_name = $this->c_getNotNull('full_name');
			$user_name = $this->c_getNotNull('user_name');
			$avatar = $this->c_getNotNull('avatar');

			$params = [
				'email' => $email,
				'password' => '',
				'joined' => time(),
				'last_login' => time(),
				'user_name' => $user_name,
				'full_name' => $full_name,
				'avatar' => $avatar
			];

			$user_id = $this->user_model->insert($params);

			$this->user_model->update($params, $user_id);

			$data = $this->__getUserProfile($user_id);
			$data['access_token'] = $this->oauths->success($user_id, $device_id);
			$this->create_success(['profile' => $data], 'Register success');
		} else {
			$user_id = $user['user_id'];
			$this->user_model->update(['last_login' => time()], $user_id);


			$data = $this->__getUserProfile($user_id);
			$data['access_token'] = $this->oauths->success($user_id, $device_id);
			$this->create_success(['profile' => $data], 'Login Success');
		}
	}

	public function google_post() {
		$email = $this->c_getNotNull('email');
		$device_id = $this->c_getNotNull('device_id');

		$this->load->library('oauths');

		$user = $this->user_model->getUserByAccount($email);
		if ($user == null) {
			$full_name = $this->c_getNotNull('full_name');
			$user_name = $this->c_getNotNull('user_name');
			$avatar = $this->post('avatar');

			$params = [
				'email' => $email,
				'password' => '',
				'joined' => time(),
				'last_login' => time(),
				'user_name' => $user_name,
				'full_name' => $full_name,
				'avatar' => $avatar
			];

			$user_id = $this->user_model->insert($params);

			$this->user_model->update($params, $user_id);

			$data = $this->__getUserProfile($user_id);
			$data['access_token'] = $this->oauths->success($user_id, $device_id);
			$this->create_success(['profile' => $data], 'Register success');
		} else {
			$user_id = $user['user_id'];
			$this->user_model->update(['last_login' => time()], $user_id);


			$data = $this->__getUserProfile($user_id);
			$data['access_token'] = $this->oauths->success($user_id, $device_id);
			$this->create_success(['profile' => $data], 'Login Success');
		}
	}
}
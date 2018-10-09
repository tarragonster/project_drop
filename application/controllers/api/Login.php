<?php
require APPPATH . '/core/BR_Controller.php';

class Login extends BR_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function facebook_post() {
		$access_token = $this->c_getNotNull('access_token');
		$device_id = $this->c_getNotNull('device_id');

		$this->load->library('facebook_lib');
		$response = $this->facebook_lib->me($access_token);
		if (!$response['success']) {
			$this->create_error(-82);
		}

		$facebook_id = $response['data']['id'];
		$email = $response['data']['email'];

		$user = $this->user_model->getUserByFacebookId($facebook_id);
		if ($user == null && !empty($email)) {
			$user = $this->user_model->getUserByAccount($email);
			if ($user != null) {
				$params = ['facebook_id' => $facebook_id];

				$this->load->library('contact_lib');
				$this->contact_lib->updateContact(CONTACT_TYPE_FACEBOOK, $facebook_id,  $user['user_id']);

				// Try to get long-live token
				$tokenResponse = $this->facebook_lib->longLive($access_token);
				if ($tokenResponse['success']) {
					$params['fb_token'] = $tokenResponse['data']['token'];
					$params['fb_expired_at'] = $tokenResponse['data']['expired_at']; // time() + 60 * 86400; // 60 days
				} else {
					$params['fb_token'] = $access_token;
					$params['fb_expired_at'] = time() + 2 * 3600; // 2 hours
				}
				$this->user_model->update($params, $user['user_id']);
			}
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
			if ($user != null) {
				$this->user_model->update(['google_id' => $google_id], $user['user_id']);
			}
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
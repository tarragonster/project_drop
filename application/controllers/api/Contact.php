<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class Contact extends BR_Controller {
	public function __construct() {
		parent::__construct();

		$this->validate_authorization();
	}

	public function syncContact_post() {
		$this->load->library('contact_lib');
		$list_emails = $this->post('list_emails');
		$list_phones = $this->post('list_phones');

		if ($list_emails != null) {
			if (!is_array($list_emails)) {
				$this->create_error(-8, 'list_emails must be an array');
			}
			$contacts = [];
			foreach ($list_emails as $email) {
				if (validate_email($email)) {
					$contacts[] = $email;
				}
			}
			if (count($contacts) > 0) {
				$this->contact_lib->pushFriends($this->user_id, CONTACT_TYPE_EMAIL, $contacts);
			}
		}

		if ($list_phones != null) {
			if (!is_array($list_phones)) {
				$this->create_error(-8, 'list_phones must be an array');
			}
			$phoneContacts = [];
			foreach ($list_phones as $phone) {
				if (validate_email($phone)) {
					$phoneContacts[] = $phone;
				}
			}
			if (count($phoneContacts) > 0) {
				$this->contact_lib->pushFriends($this->user_id, CONTACT_TYPE_PHONE, $phoneContacts);
			}
		}
		$this->user_model->update(['synced_contact' => 1, 'sync_skipped' => 0], $this->user_id);

		$response = [];
		$response['num_of_friends'] = $this->user_model->countContactFriends($this->user_id);
		$response['friends'] = $this->user_model->getContactFriends($this->user_id, 0);

		$this->create_success($response);
	}

	public function contactFriends_get() {
		$page = $this->get('page') * 1;
		$response = [];
		$response['num_of_friends'] = $this->user_model->countContactFriends($this->user_id);
		$response['friends'] = $this->user_model->getContactFriends($this->user_id, $page);

		$this->create_success($response);
	}

	public function facebookFriends_get() {
		$user = $this->user_model->get($this->user_id);
		if ($user == null) {
			$this->create_error(-10);
		}
		if (empty($user['facebook_id'])) {
			$this->create_error(-59);
		}
		if (empty($user['fb_token']) || $user['fb_expired_at'] < time()) {
			$this->create_error(-83);
		}
		$this->load->library('facebook_lib');
		$response = $this->facebook_lib->friends($user['fb_token']);
		if (!$response['success']) {
			if (is_numeric($response['data']) && $response['data'] == 190) {
				$this->create_error(-83);
			} else {
				$this->create_error(-82);
			}
		}

		$facebook_friends = $response['data']['friends'];
		$contacts = [];
		foreach ($facebook_friends as $friend) {
			$contacts[] = $friend['id'];
		}
		if (count($contacts) > 0) {
			$this->load->library('contact_lib');
			$this->contact_lib->pushFriends($this->user_id, CONTACT_TYPE_FACEBOOK, $contacts);
		}

		$page = $this->get('page') * 1;
		$response = [];
		$response['num_of_friends'] = $this->user_model->countFacebookFriends($this->user_id);
		$response['friends'] = $this->user_model->getFacebookFriends($this->user_id, $page);

		$this->create_success($response);
	}

	/**
	 * @SWG\Post(
	 *     path="/contact/skip",
	 *     summary="skip Sync Contact",
	 *     operationId="skipSyncContact",
	 *     tags={"Contact"},
	 *     produces={"application/json"},
	 *     security={
	 *       {"accessToken": {}}
	 *     },
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *     )
	 * )
	 */
	public function skip_post() {
		$this->validate_authorization();

		$user = $this->user_model->getObjectById($this->user_id);
		if ($user == null) {
			$this->create_error(-9);
		}
		$params = array();
		$params['sync_skipped'] = 1;
		$this->user_model->update($params, $this->user_id);

		$profile = $this->__getUserProfile($this->user_id);
		$this->create_success(['profile' => $profile], 'Skipped');
	}
}
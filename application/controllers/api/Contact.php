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
			if (count($contacts) > 0) {
				$this->contact_lib->pushFriends($this->user_id, CONTACT_TYPE_PHONE, $phoneContacts);
			}
		}

		$this->create_success([]);
	}

	public function syncFacebook_post() {
		$this->load->library('contact_lib');
		$list_facebook_id = $this->post('list_facebook_id');
		if (!is_array($list_facebook_id)) {
			$this->create_error(-8, 'list_facebook_id must be an array');
		}

		$contacts = [];
		foreach ($list_facebook_id as $facebook_id) {
			if (!empty($facebook_id)) {
				$contacts[] = $facebook_id;
			}
		}
		if (count($contacts) > 0) {
			$this->contact_lib->pushFriends($this->user_id, CONTACT_TYPE_FACEBOOK, $contacts);
		}

		$this->create_success([]);
	}

	public function contactFriends_get() {
		$page = $this->get('page') * 1;
		$response = [];
		$response['num_of_friends'] = $this->user_model->countContactFriends($this->user_id);
		$response['friends'] = $this->user_model->getContactFriends($this->user_id, $page);

		$this->create_success($response);
	}

	public function facebookFriends_get() {
		$page = $this->get('page') * 1;
		$response = [];
		$response['num_of_friends'] = $this->user_model->countFacebookFriends($this->user_id);
		$response['friends'] = $this->user_model->getFacebookFriends($this->user_id, $page);

		$this->create_success($response);
	}
}
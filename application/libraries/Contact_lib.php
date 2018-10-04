<?php

class Contact_lib {
	protected $CI;

	public function __construct() {
		$this->CI = &get_instance();
	}

	public function migration() {
		if ($this->CI->db->get('contact_contacts')->num_rows() > 0) {
			die('Can not run more than one time');
		}
		// Collect email
		$this->CI->db->group_by('email');
		$this->CI->db->where('email is not null');
		$this->CI->db->where('email <>', '');
		$users = $this->CI->db->get('user')->result_array();
//		pre_print($users);
//		pre_print($this->CI->db->last_query());
		foreach ($users as $user) {
			$this->CI->db->insert('contact_contacts', [
				'contact_type' => CONTACT_TYPE_EMAIL,
				'contact' => $user['email'],
				'reference_id' => $user['user_id'],
				'created_at' => time(),
			]);
		}

		// Collect Phone Number
		$this->CI->db->group_by('phone_number');
		$this->CI->db->where('phone_number is not null');
		$this->CI->db->where('phone_number <>', '');
		$users = $this->CI->db->get('user')->result_array();
		foreach ($users as $user) {
			$this->CI->db->insert('contact_contacts', [
				'contact_type' => CONTACT_TYPE_PHONE,
				'contact' => $user['phone_number'],
				'reference_id' => $user['user_id'],
				'created_at' => time(),
			]);
		}

		// Collect Phone Number
		$this->CI->db->group_by('facebook_id');
		$this->CI->db->where('facebook_id is not null');
		$this->CI->db->where('facebook_id <>', '');
		$users = $this->CI->db->get('user')->result_array();
		foreach ($users as $user) {
			$this->CI->db->insert('contact_contacts', [
				'contact_type' => CONTACT_TYPE_FACEBOOK,
				'contact' => $user['facebook_id'],
				'reference_id' => $user['user_id'],
				'created_at' => time(),
			]);
		}
	}

	protected function getKey($type) {
		switch ($type) {
			case CONTACT_TYPE_EMAIL:
				return 'email';
			case CONTACT_TYPE_PHONE:
				return 'email';
			case CONTACT_TYPE_FACEBOOK:
				return 'email';
		}
	}

	public function getReferenceId($type, $contact) {
		$this->CI->db->where($this->getKey($type), $contact);
		$user = $this->CI->db->get('user')->first_row('array');
		if ($user == null) {
			return 0;
		} else {
			return $user['user_id'];
		}
	}

	public function pushContact($type, $contact) {
		$this->CI->db->where('contact_type', $type);
		$this->CI->db->where('contact', $contact);

		$item = $this->CI->db->get('contact_contacts')->first_row('array');
		if ($item != null) {
			return $item['contact_id'];
		} else {
			$reference_id = $this->getReferenceId($type, $contact);
			$this->CI->db->insert('contact_contacts', [
				'contact_type' => $type,
				'contact' => $contact,
				'reference_id' => $reference_id,
				'created_at' => time(),
			]);
			return $this->CI->db->insert_id();
		}
	}

	public function updateContact($type, $contact, $reference_id) {
		$this->CI->db->where('contact_type', $type);
		$this->CI->db->where('contact', $contact);

		$item = $this->CI->db->get('contact_contacts')->first_row('array');
		if ($item != null) {
			if ($reference_id > 0) {
				$this->CI->db->where('contact_id', $item['contact_id']);
				$this->CI->db->update('contact_contacts', ['reference_id' => $reference_id]);
				return $item['contact_id'];
			} else {
				$this->CI->db->where('contact_id', $item['contact_id']);
				$this->CI->db->delete('contact_contacts');

				$this->CI->db->where('contact_id', $item['contact_id']);
				$this->CI->db->delete('contact_friends');
				return 0;
			}
		} else {
			if ($reference_id > 0) {
				$this->CI->db->insert('contact_contacts', [
					'contact_type' => $type,
					'contact' => $contact,
					'reference_id' => $reference_id,
					'created_at' => time(),
				]);
				return $this->CI->db->insert_id();
			}
		}
	}

	public function pushFriends($user_id, $type, $contacts) {
		foreach ($contacts as $contact) {
			$contact_id = $this->pushContact($type, $contact);

			$this->CI->db->where('user_id', $user_id);
			$this->CI->db->where('contact_id', $contact_id);

			$friend = $this->CI->db->get('contact_friends')->first_row('array');
			if ($friend == null) {
				$this->CI->db->insert('contact_friends', [
					'user_id' => $user_id,
					'contact_id' => $contact_id
				]);
			}
		}
	}
}
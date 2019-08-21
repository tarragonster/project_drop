<?php

require_once APPPATH . '/core/BaseModel.php';

class Featured_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'featured_profiles';
		$this->id_name = 'id';
	}

	public function getListUsers($page = -1) {
		$this->db->select('u.user_id, u.user_name, u.email, u.full_name, u.avatar, u.user_type, fp.priority as priority_profile, fp.id');
		$this->db->from('featured_profiles fp');
		$this->db->join('user u', 'u.user_id = fp.user_id');
		$this->db->where('u.status', 1);
		$this->db->order_by('fp.priority');
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getMaxProfile() {
		$this->db->order_by('priority', 'desc');
		$query = $this->db->get('featured_profiles');
		$this->db->limit(1);
		return $query->num_rows() > 0 ? $query->first_row()->priority : 0;
	}

	public function getOtherUsers() {
		$sql = "SELECT u.user_id, u.user_name, u.email, u.full_name, u.avatar, u.user_type FROM user u WHERE status = 1 AND user_id NOT IN (SELECT user_id FROM featured_profiles  GROUP BY user_id) ORDER BY full_name";
		$query = $this->db->query($sql);
		return $query->result_array();
	}


	public function addProfile($user_id) {
		if ($this->getProfile($user_id) == null) {
			$this->db->insert('featured_profiles', [
				'user_id' => $user_id,
				'priority' => $this->getMaxProfile() + 1,
				'status' => 1,
			]);
			return $this->db->insert_id();
		}
		return -1;
	}


	public function getProfile($user_id) {
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('featured_profiles');
		return $query->first_row('array');
	}

	public function removeProfile($user_id) {
		$profile = $this->getProfile($user_id);
		if ($profile != null) {
			$this->db->trans_start();
			$this->db->where('user_id', $user_id);
			$this->db->delete('featured_profiles');

			// Move up below
			$this->db->where('priority >', $profile['priority']);
			$this->db->set('priority', 'priority - 1', false);
			$this->db->update('featured_profiles');
			$this->db->trans_complete();
		}
	}

	public function upProfile($user_id) {
		$profile = $this->getProfile($user_id);
		if ($profile != null) {
			$this->db->trans_start();
			$this->db->where('priority', $profile['priority'] - 1);
			$this->db->set('priority', 'priority + 1', false);
			$this->db->update('featured_profiles');

			$this->db->where('user_id', $user_id);
			$this->db->set('priority', 'priority - 1', false);
			$this->db->update('featured_profiles');
			$this->db->trans_complete();
		}
	}

	public function downProfile($user_id) {
		$profile = $this->getProfile($user_id);
		if ($profile != null) {
			$this->db->trans_start();
			$this->db->where('priority', $profile['priority'] + 1);
			$this->db->set('priority', 'priority-1', false);
			$this->db->update('featured_profiles');

			$this->db->where('user_id', $user_id);
			$this->db->set('priority', 'priority + 1', false);
			$this->db->update('featured_profiles');
			$this->db->trans_complete();
		}
	}

	public function getUsers() {
		$this->db->select('fp.*, u.user_name, u.full_name, u.avatar');
		$this->db->from('featured_profiles fp');
		$this->db->join('user u', 'fp.user_id = u.user_id');
		$this->db->order_by('fp.priority');
		return $this->db->get()->result_array();
	}

	public function updatePriority($params, $id) {
		$this->db->where('user_id', $id);
		$this->db->update($this->table, $params);
	}

}
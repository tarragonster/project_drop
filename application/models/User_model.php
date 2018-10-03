<?php

require_once APPPATH . '/core/BaseModel.php';

class User_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'user';
		$this->id_name = 'user_id';
	}

	public function getUserById($user_id) {
		return $this->getObjectById($user_id);
	}

	public function getUserForAdmin($user_id) {
		$this->db->where($this->id_name, $user_id);
		$query = $this->db->get('user');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getProfileUser($user_id) {
		$this->db->where('status', 1);
		$this->db->where($this->id_name, $user_id);
		$query = $this->db->get('user_view');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getByUsername($user_name) {
		$this->db->where('status', 1);
		$this->db->where('user_name', $user_name);
		$query = $this->db->get('user_view');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getByEmail($email) {
		$this->db->where('status', 1);
		$this->db->where('email', $email);
		$query = $this->db->get('user_view');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getUserByFacebookId($facebook_id) {
		$this->db->from('user');
		$this->db->where('facebook_id', $facebook_id);
		$user = $this->db->get()->first_row('array');
		return $user;
	}

	public function getUserByGoogleId($google_id) {
		$this->db->from('user');
		$this->db->where('google_id', $google_id);
		$user = $this->db->get()->first_row('array');
		return $user;
	}

	public function searchUser($user_name, $user_id = -1) {
//		$this->db->select('user_id, user_name, avatar, full_name');
//		if ($user_id != -1) {
//			$this->db->where('user_id !=', $user_id);
//		}
//		$this->db->where('status', 1);
//		$this->db->like('user_name', $user_name);
//		$query = $this->db->get($this->table);
		$this->db->from('user u');
		$this->db->join('user_follow uf', 'uf.follower_id = u.user_id', 'left');
		$this->db->select('u.user_id, user_name, avatar, full_name, count(if(uf.user_id is not null, 1, 0)) as followers');
		if ($user_id != -1) {
			$this->db->where('u.user_id !=', $user_id);
		}
		$this->db->where('u.status', 1);
		$this->db->group_by('u.user_id');
		$this->db->like('user_name', $user_name, 'both');
		$this->db->order_by('followers', 'desc');
		$this->db->order_by('user_name');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getUsers($user_id = -1) {
		$this->db->from('user u');
		$this->db->join('user_follow uf', 'uf.follower_id = u.user_id', 'left');
		$this->db->select('u.user_id, user_name, avatar, full_name, count(if(uf.user_id is not null, 1, 0)) as followers');
		if ($user_id != -1) {
			$this->db->where('u.user_id !=', $user_id);
		}
		$this->db->where('u.status', 1);
		$this->db->group_by('u.user_id');
		$this->db->order_by('followers', 'desc');
		$this->db->order_by('user_name');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getListWatching($user_id, $page = -1) {
		$this->db->select('w.id, w.user_id, p.*');
		$this->db->from('watch_list w');
		$this->db->join('product_view p', 'p.product_id = w.product_id');
		$this->db->where('w.user_id', $user_id);
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$this->db->order_by('w.id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function checkInWatchList($product_id, $user_id) {
		$this->db->where('product_id', $product_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('watch_list');

		return $query->first_row('array');
	}

	public function getListContinue($user_id, $page = -1) {
		$this->db->select('w.user_id, p.*, w.start_time, w.episode_id');
		$this->db->from('user_watch w');
		$this->db->join('product p', 'p.product_id = w.product_id');
		$this->db->where('w.user_id', $user_id);
		$this->db->where('w.episode_id !=', 0);
		$this->db->where("w.start_time != 'nan'");
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$this->db->group_by('w.product_id');
		$this->db->order_by('w.id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function addWatch($params) {
		$this->db->insert('watch_list', $params);
		return $this->db->insert_id();
	}

	public function addFollow($params) {
		$this->db->insert('user_follow', $params);
		return $this->db->insert_id();
	}

	public function removeFollow($user_id, $follower_id) {
		$this->db->where('follower_id', $follower_id);
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_follow');
	}

	public function checkWatchList($user_id, $product_id) {
		$this->db->select('id');
		$this->db->where('product_id', $product_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('watch_list');
		return $query->num_rows() > 0;
	}

	public function getWatch($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('watch_list');
		return $query->first_row('array');
	}

	public function removeWatchList($user_id, $product_id) {
		if ($product_id != 0) {
			$this->db->where('product_id', $product_id);
		}
		$this->db->where('user_id', $user_id);
		$this->db->delete('watch_list');
	}

	public function removeWatch($id) {
		$this->db->where('id', $id);
		$this->db->delete('watch_list');
	}

	public function checkFollower($user_id, $follower_id) {
		$this->db->select('follow_id');
		$this->db->where('follower_id', $follower_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('user_follow');
		return $query->num_rows() > 0;
	}

	public function checkEpisodeExits($user_id, $episode_id) {
		$this->db->select('status');
		$this->db->where('episode_id', $episode_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('episode_like');
		if ($query->num_rows() > 0) {
			$item = $query->first_row('array');
			return $item['status'];
		} else {
			return -1;
		}
	}

	public function getAvatarUser($user_id) {
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('user');
		if ($query->num_rows() > 0) {
			$item = $query->first_row('array');
			return $item['avatar'];
		} else {
			return '';
		}
	}

	public function addLike($params) {
		$this->db->insert('episode_like', $params);
		return $this->db->insert_id();
	}

	public function updateLike($episode_id, $user_id, $status) {
		$this->db->where('episode_id', $episode_id);
		$this->db->where('user_id', $user_id);
		$this->db->update('episode_like', array('status' => $status));
	}

	public function getLike($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('episode_like');
		return $query->first_row('array');
	}

	public function removeLike($id) {
		$this->db->where('id', $id);
		$this->db->update('episode_like', array('status' => 0));
	}

	public function getThumbUpList($user_id, $page = -1) {
		$this->db->select('e.*, el.id, e.status as episode_status');
		$this->db->from('episode_like el');
		$this->db->join('episode e', 'e.episode_id = el.episode_id');
		$this->db->join('season s', 'e.season_id = s.season_id');
		$this->db->join('product p', 's.product_id = p.product_id');
		$this->db->where('el.user_id', $user_id);
		$this->db->where('el.status', 1);
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function deleteLike($episode_id, $user_id) {
		$this->db->where('episode_id', $episode_id);
		$this->db->where('user_id', $user_id);
		$this->db->delete('episode_like');
	}

	public function getFollowing($user_id, $page = -1) {
		$this->db->select('u.user_id, uf.follower_id, u.user_name, u.full_name, u.avatar');
		$this->db->from('user_follow uf');
		$this->db->join('user u', 'u.user_id = uf.follower_id');
		$this->db->where('uf.user_id', $user_id);
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getFollowers($follower_id, $page = -1) {
		$this->db->select('u.user_id, uf.follower_id, u.user_name, u.full_name, u.avatar');
		$this->db->from('user_follow uf');
		$this->db->join('user u', 'u.user_id = uf.user_id');
		$this->db->where('uf.follower_id', $follower_id);
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function countFollowing($user_id) {
		$this->db->from('user_follow');
		$this->db->where('user_id', $user_id);
		return $this->db->count_all_results();
	}

	public function countFollowers($follower_id) {
		$this->db->from('user_follow');
		$this->db->where('follower_id', $follower_id);
		return $this->db->count_all_results();
	}

	public function getTerms() {
		$this->db->from('user_terms');
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->first_row('array');
		} else {
			return null;
		}
	}

	public function getUidByAccount($account, $password) {
		$sql = 'select user_id from user '
			. "where (upper(email) = upper('$account') or upper(user_name) = upper('$account')) "
			. "and password=md5('$password') and status = 1";
		$result = $this->db->query($sql);

		if ($result->num_rows() > 0) {
			$item = $result->first_row('array');
			return $item['user_id'];
		} else {
			return -1;
		}
	}

	public function updateDeviceUser($user_id, $device_id) {
		if ($this->checkDeviceId($device_id)) {
			$this->db->where('device_id', $device_id);
			$this->db->update('device_user', array('user_id' => $user_id, 'last_activity' => time()));
		} else {
			$this->db->insert('device_user', array('user_id' => $user_id, 'device_id' => $device_id, 'last_activity' => time()));
		}
	}

	public function logoutDevice($user_id, $device_id) {
		$this->db->where('device_id', $device_id);
		$this->db->where('user_id', $user_id);
		$this->db->update('device_user', array('user_id' => 0));
	}

	public function checkDeviceId($device_id) {
		$this->db->where('device_id', $device_id);
		$query = $this->db->get('device_user');
		return $query->num_rows() > 0;
	}

	public function checkEmail($email) {
		$sql = "select user_id, full_name from user where upper(email) = upper('$email')";
		$result = $this->db->query($sql);
		return $result->num_rows() > 0 ? $result->first_row('array') : null;
	}

	public function checkUserName($user_name) {
		$sql = "select user_id from user where upper(user_name) = upper('$user_name')";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function checkUid($user_id) {
		$sql = "select user_id from user where user_id ='$user_id'";
		$result = $this->db->query($sql);
		return $result->num_rows() > 0;
	}

	public function insertTimeUse($params) {
		$this->db->insert('user_timeuse', $params);
	}

	public function getNumOfUser($status = 1) {
		$this->db->where('status', $status);
		$this->db->from('user');
		return $this->db->count_all_results();
	}

	public function getUsersForAdmin($page = 0, $status = 1) {
		$this->db->select('u.*');
		$this->db->from('user u');
		$this->db->where('status', $status);
		$this->db->order_by('user_id', 'desc');
		$this->db->limit(PERPAGE_ADMIN, $page * PERPAGE_ADMIN);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function clearData($user_id) {
		$this->db->trans_start();
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_watch');
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_follow');
		$this->db->where('follower_id', $user_id);
		$this->db->delete('user_follow');
		$this->db->where('user_id', $user_id);
		$this->db->delete('episode_like');
		$this->db->where('user_id', $user_id);
		$this->db->delete('episode_comment');
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_notify');
		$this->db->like('data', '"user_id":' . $user_id);
		$this->db->delete('user_notify');
		$this->db->trans_complete();
	}

	public function insertCodeResetPassword($params) {
		$this->db->insert("code_reset_password", $params);
		return $this->db->insert_id();
	}

	public function updateCodeResetPassword($user_id, $code) {
		$this->db->where('user_id', $user_id);
		$this->db->where('code', $code);
		$this->db->update("code_reset_password", array('has_reset' => 1, 'created' => time()));
	}

	public function checkCode($user_id, $code) {
		if ($code == '') {
			return 1;
		}
		$sql = "select * from code_reset_password where code = '$code' and user_id='$user_id'";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			$row = $query->first_row('array');
			if ($row['has_reset'] == 1) {
				return 2;
			} else {
				if ($row['created'] + 86400 < time()) {
					return 3;
				}
			}
			return 0;
		} else {
			return 1;
		}
	}

	public function getUserByAccount($account) {
		$this->db->from('user');
		$this->db->where("((upper(email) = upper('$account') or upper(user_name) = upper('$account')))");
		$this->db->where("status", 1);
		$user = $this->db->get()->first_row('array');
		return $user;
	}

	public function getFeaturedProfiles() {
		$this->db->from('featured_profiles fp');
		$this->db->join('user u', 'fp.user_id = u.user_id');
		$this->db->select('u.user_id, user_name, full_name, email, avatar, level, joined');
		$this->db->where('u.status', 1);
		$this->db->where('fp.status', 1);
		$this->db->group_by('u.user_id');
		$this->db->order_by('fp.priority');
		$this->db->order_by('user_name');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getUserPick($user_id, $product_id) {
		$this->db->where('product_id', $product_id);
		$this->db->where('user_id', $user_id);
		return $this->db->get('user_picks')->first_row('array');
	}

	public function getUserPicks($user_id) {
		$this->db->select('p.*, up.pick_id, up.quote');
		$this->db->from('user_picks up');
		$this->db->where('up.user_id', $user_id);
		$this->db->group_by('up.pick_id');
		$this->db->join('product_view p', 'p.product_id = up.product_id');
		return $this->db->get()->result_array();
	}

	public function getPick($pick_id) {
		$this->db->select('p.*, up.user_id, up.pick_id, up.quote');
		$this->db->from('user_picks up');
		$this->db->where('up.pick_id', $pick_id);
		$this->db->join('product_view p', 'p.product_id = up.product_id');
		return $this->db->get()->first_row('array');
	}

	public function insertPick($params) {
		$this->db->insert('user_picks', $params);
	}

	public function updatePick($params, $pick_id) {
		$this->db->where('pick_id', $pick_id);
		$this->db->update('user_picks', $params);
	}

	public function removePick($pick_id) {
		$this->db->where('pick_id', $pick_id);
		$this->db->delete('user_picks');
	}

	public function getProfileConfigs($user_id) {
		$this->db->where('user_id', $user_id);
		$item = $this->db->get('user_profile_configs')->first_row('array');
		if ($item != null) {
			return $item;
		}
		return [
			'user_id' => $user_id,
			'picks_enabled' => 1,
			'continue_enabled' => 1,
			'watch_enabled' => 1,
			'thumbs_up_enabled' => 1,
		];
	}

	public function updateProfileConfigs($configs, $user_id) {
		$this->db->where('user_id', $user_id);
		$item = $this->db->get('user_profile_configs')->first_row('array');
		if ($item != null) {
			$this->db->update('user_profile_configs', $configs);
		} else {
			$configs['user_id'] = $user_id;
			$this->db->insert('user_profile_configs', $configs);
		}
	}

	public function getTopPicks() {
		$this->db->select('up.pick_id, p.*, up.quote, u.user_name, u.full_name, u.avatar');
		$this->db->from('user_picks up');
		$this->db->join('product_view p', 'p.product_id = up.product_id');
		$this->db->join('user u', 'u.user_id = up.user_id');
		$this->db->group_by('up.pick_id');
		$this->db->order_by('up.pick_id', 'desc');
		$this->db->limit(100);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function findReport($user_id, $reporter_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('reporter_id', $reporter_id);

		return $this->db->get('user_reports')->first_row('array');
	}

	public function insertReport($user_id, $reporter_id) {
		if ($this->findReport($user_id, $reporter_id) != null) {
			return;
		}
		$this->db->insert('user_reports', [
			'user_id' => $user_id,
			'reporter_id' => $reporter_id,
			'created_at' => time(),
		]);
	}

	public function getNumReports() {
		$this->db->from('user_reports ur');
		$this->db->join('user u1', 'u1.user_id = ur.user_id');
		$this->db->join('user u2', 'u2.user_id = ur.reporter_id');
		$this->db->select('ur.report_id, u1.full_name, u2.full_name as reporter_name, ur.created_at');
		return $this->db->count_all_results();
	}

	public function getReports($page = 0) {
		$this->db->from('user_reports ur');
		$this->db->join('user u1', 'u1.user_id = ur.user_id');
		$this->db->join('user u2', 'u2.user_id = ur.reporter_id');
		$this->db->select('ur.report_id, u1.full_name, u2.full_name as reporter_name, ur.created_at');
		$this->db->order_by('report_id', 'desc');
		$this->db->limit(PERPAGE_ADMIN, $page * PERPAGE_ADMIN);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function countContactFriends($user_id) {
		$this->db->from('contact_friends cf');
		$this->db->join('contact_contacts cc', 'cc.contact_id = cf.contact_id');
		$this->db->join('user u', 'u.user_id = cc.reference_id');

		$this->db->where('cf.user_id', $user_id);
		$this->db->where('(cc.contact_type = ' . CONTACT_TYPE_EMAIL . ' or cc.contact_type = ' . CONTACT_TYPE_PHONE . ')');
		return $this->db->count_all_results();
	}

	public function getContactFriends($user_id, $page = -1) {
		$this->db->select('u.user_id, u.user_name, u.full_name, u.avatar, u.user_type, u.email, if(uf.follower_id is null, 0, 1) as is_follow');
		$this->db->from('contact_friends cf');
		$this->db->join('contact_contacts cc', 'cc.contact_id = cf.contact_id');
		$this->db->join('user u', 'u.user_id = cc.reference_id');
		$this->db->join('(select * from user_follow where user_id = ' . $user_id . ') uf', 'uf.follower_id = cc.reference_id', 'left');

		$this->db->where('cf.user_id', $user_id);
		$this->db->where('(cc.contact_type = ' . CONTACT_TYPE_EMAIL . ' or cc.contact_type = ' . CONTACT_TYPE_PHONE . ')');
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$this->db->order_by('is_follow');
		$this->db->order_by('full_name');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function countFacebookFriends($user_id) {
		$this->db->from('contact_friends cf');
		$this->db->join('contact_contacts cc', 'cc.contact_id = cf.contact_id');
		$this->db->join('user u', 'u.user_id = cc.reference_id');

		$this->db->where('cf.user_id', $user_id);
		$this->db->where('cc.contact_type = ' . CONTACT_TYPE_FACEBOOK);
		return $this->db->count_all_results();
	}

	public function getFacebookFriends($user_id, $page = -1) {
		$this->db->select('u.user_id, u.user_name, u.full_name, u.avatar, u.user_type, u.email, if(uf.follower_id is null, 0, 1) as is_follow');
		$this->db->from('contact_friends cf');
		$this->db->join('contact_contacts cc', 'cc.contact_id = cf.contact_id');
		$this->db->join('user u', 'u.user_id = cc.reference_id');
		$this->db->join('(select * from user_follow where user_id = ' . $user_id . ') uf', 'uf.follower_id = cc.reference_id', 'left');

		$this->db->where('cf.user_id', $user_id);
		$this->db->where('cc.contact_type = ' . CONTACT_TYPE_FACEBOOK);
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$this->db->order_by('is_follow');
		$this->db->order_by('full_name');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getUnFollowContactFriends($user_id) {
		$this->db->select('u.user_id, u.user_name, u.full_name, u.avatar, u.user_type, u.email');
		$this->db->from('contact_friends cf');
		$this->db->join('contact_contacts cc', 'cc.contact_id = cf.contact_id');
		$this->db->join('user u', 'u.user_id = cc.reference_id');
		$this->db->join('(select * from user_follow where user_id = ' . $user_id . ') uf', 'uf.follower_id = cc.reference_id', 'left');

		$this->db->where('cf.user_id', $user_id);
		$this->db->where('(cc.contact_type = ' . CONTACT_TYPE_EMAIL . ' or cc.contact_type = ' . CONTACT_TYPE_PHONE . ')');
		$this->db->where('uf.follower_id is null');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getUnFollowFacebookFriends($user_id) {
		$this->db->select('u.user_id, u.user_name, u.full_name, u.avatar, u.user_type, u.email');
		$this->db->from('contact_friends cf');
		$this->db->join('contact_contacts cc', 'cc.contact_id = cf.contact_id');
		$this->db->join('user u', 'u.user_id = cc.reference_id');
		$this->db->join('(select * from user_follow where user_id = ' . $user_id . ') uf', 'uf.follower_id = cc.reference_id', 'left');

		$this->db->where('cf.user_id', $user_id);
		$this->db->where('(cc.contact_type = ' . CONTACT_TYPE_EMAIL . ' or cc.contact_type = ' . CONTACT_TYPE_PHONE . ')');
		$this->db->where('uf.follower_id is null');
		$query = $this->db->get();
		return $query->result_array();
	}
}
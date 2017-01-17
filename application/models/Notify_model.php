<?php

class Notify_model extends CI_Model {

	public static $templates = array(
		'1' => 'is watching',
		'2' => "liked",
		'3' => "disliked",
		'4' => "wants to watch",
		'5' => "liked comment",
		'6' => "commented on",
		'7' => "Second Screen Series Suggestion:",
		'8' => "New Season:",
		'9' => "liked <<username>> 's comment on",
		'10' => "replied to <<username>> 's comment on",
		'11' => "mentioned <<username>> in a comment on",
		'12' => "started following <<username>> ",
		'51' => "started following you.",
		'52' => "mentioned you in a comment on",
		'53' => "liked your comment on",
		'54' => "replied to your comment on",
		'55' => "Welcome to Second Screen Beta",
	);

	public function __construct() {
		parent::__construct();
		$this->load->library('cipush');
	}

	public function createNotify($user_id, $type, $data = null, $sound = 'default') {
		$alert = $this->fillDataToTemplate(Notify_model::$templates[$type], $data, $type);
		if ($type == 55) {
			//only send in-app notification when added new user
			$this->insertUserNotify($user_id, $type, Notify_model::$templates[$type], $data);
		} else {
			$this->insertUserNotify($user_id, $type, Notify_model::$templates[$type], $data);
			$this->sendNotification($user_id, $type, $alert, $data, $sound);
		}
	}

	public function createNotifyToFollower($user_id, $type, $data = null, $sound = 'default') {
		$users = $this->getUserFollow($user_id);
//		pre_print($users);
		if ($users != null) {
			$alert = $this->fillDataToTemplate(Notify_model::$templates[$type], $data, $type);
			$count = count($users);
			foreach ($users as $user) {
				if (!$this->checkNotify($user['user_id'], $type, $data)) {
					$this->insertUserNotify($user['user_id'], $type, Notify_model::$templates[$type], $data);
				} else {
					$this->updateNotify($user['user_id'], $type, $data, array('status' => 1, 'timestamp' => time()));
				}
				$this->sendNotification($user['user_id'], $type, $alert, $data, $sound, $count < 20);
			}
		}
	}

	public function createNotifyMany($users, $type, $data = null, $sound = 'default') {
		if ($users != null) {
			$alert = $this->fillDataToTemplate(Notify_model::$templates[$type], $data, $type);
			$count = count($users);
			foreach ($users as $user) {
				if (!$this->checkNotify($user['user_id'], $type, $data)) {
					$this->insertUserNotify($user['user_id'], $type, Notify_model::$templates[$type], $data);
				} else {
					$this->updateNotify($user['user_id'], $type, $data, array('status' => 1, 'timestamp' => time()));
				}
				$this->sendNotification($user['user_id'], $type, $alert, $data, $sound, $count < 20);
			}
		}
	}

	public function removeNotify($user_id, $type, $data = null) {
		$this->db->where('type', $type);
		if ($data != null)
			$this->db->where('data', json_encode($data));
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_notify');
	}

	public function updateNotify($user_id, $type, $data = null, $params) {
		$this->db->where('type', $type);
		if ($data != null)
			$this->db->where('data', json_encode($data));
		$this->db->where('user_id', $user_id);
		$this->db->update('user_notify', $params);
	}

	private function insertUserNotify($user_id, $type, $template, $data) {
		$params = array();
		$params['user_id'] = $user_id;
		$params['type'] = $type;
		$params['timestamp'] = time();
		$params['content'] = $template;
		if ($data != null)
			$params['data'] = json_encode($data);
		$params['status'] = 1;
		$this->db->insert('user_notify', $params);
	}

	public function fillDataToTemplate($template, $data, $type) {
		$user_name = '';
		$product_name = '';
		$content = '';
		if (isset($data['user_id'])) {
			$user = $this->getUserForNotify($data['user_id']);
			$user_name = $user['user_name'] . ' ';
		}
		if ($type >= 50) {
			// if(isset($data['season_id'])){
			// 	$season = $this->getSeasonForNotify($data['season_id']);
			// 	$content = $content.' '.$season['name'];
			// }
			if (isset($data['episode_id'])) {
				$episode = $this->getPartEpisodeForNotify($data['episode_id']);
				$content = $content . ' part ' . $episode['position'];
			}
			if (isset($data['product_id'])) {
				$product = $this->getProductForNotify($data['product_id']);
			}
			if ($content != '') {
				$product_name = $content . ' of ' . $product['name'];
			}
		} else {
			if (isset($data['product_id'])) {
				$product = $this->getProductForNotify($data['product_id']);
				$product_name = ' ' . $product['name'];
			}
			if (isset($data['uid_comment'])) {
				if ($data['uid_comment'] == $data['user_id']) {
					if ($type == 10) {
						$template = str_replace("<<username>>", 'their', $template);
					} else {
						$template = str_replace("<<username>>", 'to their', $template);
					}
				} else {
					$template = str_replace("<<username>>", $this->getUserForNotify($data['uid_comment'])['user_name'], $template);
				}
			}
			if (isset($data['episode_id'])) {
				$episode = $this->getPartEpisodeForNotify($data['episode_id']);
				$content = $content . ' part ' . $episode['position'];
			}
			if ($content != '') {
				$template = $template . ' ' . $content . ' of';
			}
		}
		return $user_name . $template . $product_name;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

	private function getDeviceTokensOfUser($user_id) {
		$this->db->where('user_id', $user_id);
		$this->db->not_like('reg_id', '__NULL__');
		$query = $this->db->get('device_user');
		return $query->num_rows() > 0 ? $query->result_array('array') : null;
	}

	public function sendNotification($uid, $type, $alert, $data = null, $sound = 'default', $sendNow = true) {
		$data_send = array(
			'alert' => $alert,
			'type' => $type,
			'data' => $data,
			'sound' => $sound,
		);

		$devices = $this->getDeviceTokensOfUser($uid);
		if ($devices != null) {
			$this->cipush = new Cipush();
			foreach ($devices as $device) {
				if ($device['dtype_id'] == 1) {
					$this->cipush->addAndroid($device['reg_id'], $data_send, $sendNow);
				} else {
					$this->cipush->addIOS($device['reg_id'], $data_send, $sendNow);
				}
			}
		}
	}

	public function onlyPushNotify($device_list, $message, $sendNow = true) {
		if ($device_list == null || count($device_list) <= 0)
			return;
		$data_send = array(
			'alert' => $message,
			'type' => '0',
			'data' => array('uid' => 0),
			'sound' => 'default',
		);
		foreach ($device_list as $device) {
			if ($device['dtype_id'] == 1) {
				$this->cipush->addAndroid($device['reg_id'], $data_send, $sendNow);
			} else {
				$this->cipush->addIOS($device['reg_id'], $data_send, $sendNow);
			}
		}
	}

	public function getNewForFollowing($user_id, $page = -1) {
		$this->db->select('un.*');
		$this->db->from('user_notify un');
		$this->db->join('user_follow uf', 'uf.follower_id = un.user_id');
		$this->db->where('uf.user_id', $user_id);
		$this->db->where('type <', 50);
		$this->db->order_by('un.notify_id', 'desc');
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$query = $this->db->get();
		return $query->result_array('array');
	}

	public function getNewForYou($user_id, $page = -1) {
		$this->db->select('*');
		$this->db->from('user_notify');
		$this->db->where('user_id', $user_id);
		$this->db->where('type >', 50);
		$this->db->order_by('notify_id', 'desc');
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$query = $this->db->get();
		return $query->result_array('array');
	}

	public function getUserForNotify($user_id) {
		$this->db->select('user_name, avatar, full_name');
		$this->db->where('user_id', $user_id);
		$this->db->where('status', 1);
		$query = $this->db->get('user');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getProductForNotify($product_id) {
		$this->db->select('name');
		$this->db->where('product_id', $product_id);
		$this->db->where('status', 1);
		$query = $this->db->get('product');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getSeasonForNotify($season_id) {
		$this->db->select('name');
		$this->db->where('season_id', $season_id);
		$this->db->where('status', 1);
		$query = $this->db->get('season');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getEpisodeForNotify($episode_id) {
		$this->db->select('name');
		$this->db->where('episode_id', $episode_id);
		$this->db->where('status', 1);
		$query = $this->db->get('episode');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getUserFollow($user_id) {
		$this->db->select('u.*');
		$this->db->from('user_follow u');
		$this->db->where('u.follower_id', $user_id);
		$query = $this->db->get();
		return $query->result_array('array');
	}

	public function getPartEpisodeForNotify($episode_id) {
		$this->db->select('position');
		$this->db->where('episode_id', $episode_id);
		$this->db->where('status', 1);
		$query = $this->db->get('episode');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function checkNotify($user_id, $type, $data = null) {
		$this->db->where('type', $type);
		if ($data != null)
			$this->db->where('data', json_encode($data));
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('user_notify');
		return $query->num_rows() > 0;
	}
}
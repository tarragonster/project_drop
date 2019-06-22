<?php

class Notify_model extends CI_Model {

	public static $templates = array(
		'1' => [
			'setting_key' => NOTIFICATION_NEW_WATCHLIST,
			'formatted' => 'is watching',
			'delay_seconds' => 0,
		],
		'2' => [
			'setting_key' => NOTIFICATION_NEW_THUMBS_UP,
			'formatted' => "liked",
			'delay_seconds' => 0,
		],
		'3' => [
			'setting_key' => NOTIFICATION_NEW_THUMBS_UP,
			'formatted' => "disliked",
			'delay_seconds' => 0,
		],
		'4' => [
			'setting_key' => NOTIFICATION_NEW_THUMBS_UP,
			'formatted' => "wants to watch",
			'delay_seconds' => 5 * 60,
		],
		'5' => [
			'setting_key' => NOTIFICATION_COMMENT_LIKES,
			'formatted' => "liked comment",
			'delay_seconds' => 0,
		],
		'6' => [
			'setting_key' => NOTIFICATION_COMMENT_REPLIES,
			'formatted' => "commented on",
			'delay_seconds' => 0,
		],
		'7' => [
			'setting_key' => NOTIFICATION_NEW_STORIES,
			'formatted' => "Second Screen Series Suggestion:",
			'delay_seconds' => 0,
		],
		'8' => [
			'setting_key' => NOTIFICATION_NEW_STORIES,
			'formatted' => "New Season:",
			'delay_seconds' => 0,
		],
		'9' => [
			'setting_key' => NOTIFICATION_COMMENT_LIKES,
			'formatted' => "liked <<username>> 's comment: ",
			'delay_seconds' => 0,
		],

		'10' => [
			'setting_key' => NOTIFICATION_COMMENT_REPLIES,
			'formatted' => "replied to <<username>> 's comment on",
			'delay_seconds' => 0,
		],
		'11' => [
			'setting_key' => NOTIFICATION_COMMENT_MENTIONS,
			'formatted' => "mentioned <<username>> in a comment on",
			'delay_seconds' => 0,
		],
		'12' => [
			'setting_key' => NOTIFICATION_NEW_FOLLOWERS,
			'formatted' => "started following <<username>> ",
			'delay_seconds' => 0,
		],
		'13' => [
			'setting_key' => NOTIFICATION_NEW_THUMBS_UP,
			'formatted' => "just liked <<story_name>>. Check out the story.",
			'delay_seconds' => 5 * 60,
		],
		'14' => [
			'setting_key' => NOTIFICATION_NEW_WATCHLIST,
			'formatted' => "added <<story_name>> to their watch list. Are you going to watch it?",
			'delay_seconds' => 0,
		],
		'15' => [
			'setting_key' => NOTIFICATION_NEW_WATCHLIST,
			'formatted' => "just wrote a review of <<story_name>>. Go see what they said.",
			'delay_seconds' => 5 * 60,
		],
		'51' => [
			'setting_key' => NOTIFICATION_NEW_FOLLOWERS,
			'formatted' => "followed you. See who else they're following.",
			'delay_seconds' => 0,
		],
		'52' => [
			'setting_key' => NOTIFICATION_COMMENT_MENTIONS,
			'formatted' => "mentioned you",
			'delay_seconds' => 3 * 60, // Delay 3min
		],
		'53' => [
			'setting_key' => NOTIFICATION_COMMENT_LIKES,
			'formatted' => "liked your comment",
			'delay_seconds' => 0,
		],
		'54' => [
			'setting_key' => NOTIFICATION_COMMENT_REPLIES,
			'formatted' => "replied to you",
			'delay_seconds' => 2 * 60, // Delay 2min
		],
		'55' => [
			'setting_key' => NOTIFICATION_TRENDING,
			'formatted' => "Welcome to 10 Block Secret Society.",
			'delay_seconds' => 60, // Delay 1min
		],
		'56' => [
			'setting_key' => NOTIFICATION_TRENDING,
			'formatted' => "Welcome to 10 Block! Add a few stories to your watch list for a quick start.",
			'delay_seconds' => 3 * 3600, // Delay 3hr
		],
		'57' => [
			'setting_key' => NOTIFICATION_NEW_WATCHLIST,
			'formatted' => "shared <<story_name>> with you: \"<<message>>\"",
			'delay_seconds' => 0,
		],
		'58' => [
			'setting_key' => NOTIFICATION_NEW_STORIES,
			'formatted' => "A new story just added! Don't miss <<story_name>>.",
			'delay_seconds' => 0,
		],
		'59' => [
			'setting_key' => NOTIFICATION_TRENDING,
			'formatted' => "<<story_name>> is trending. Have you watched it?",
			'delay_seconds' => 0,
		],
		'60' => [
			'setting_key' => NOTIFICATION_NEW_WATCHLIST,
			'formatted' => "Add to <<story_name>> your watch list.",
			'delay_seconds' => 3600,
		],
		'61' => [
			'setting_key' => NOTIFICATION_NEW_PICKS,
			'formatted' => "Pick up <<story_name>> where you left off.",
			'delay_seconds' => 3600,
		],
		'62' => [
			'setting_key' => NOTIFICATION_NEW_PICKS,
			'formatted' => "Did you enjoy that? Add <<story_name>> to your thumbs up.",
			'delay_seconds' => 12 * 3600,
		],
		'64' => [
			'setting_key' => NOTIFICATION_NEW_PICKS,
			'formatted' => "Let your friend know what you think of <<story_name>>.",
			'delay_seconds' => 20 * 60,
		],
	);

	public function __construct() {
		parent::__construct();
		$this->load->library('cipush');
	}

	public function createNotify($user_id, $type, $data = null, $sound = 'default') {
		$template = Notify_model::$templates[$type];
		if ($this->checkSetting($user_id, $template['setting_key'])) {
			$alert = $this->fillDataToTemplate($template['formatted'], $data, $type);
			$this->insertUserNotify($user_id, $type, $template['formatted'], $data, $template['delay_seconds']);
			$this->sendNotification($user_id, $type, $alert, $data, $sound, $template['delay_seconds']);
		}
	}

	public function createNotifyToFollower($user_id, $type, $data = null, $sound = 'default', $sendPush = true) {
		$users = $this->getUserFollow($user_id);
		if ($users != null) {
			$template = Notify_model::$templates[$type];
			$alert = $this->fillDataToTemplate($template['formatted'], $data, $type);
			foreach ($users as $user) {
				if ($this->checkSetting($user['user_id'], $template['setting_key'])) {
					if (!$this->checkNotify($user['user_id'], $type, $data)) {
						$this->insertUserNotify($user['user_id'], $type, $template['formatted'], $data, $template['delay_seconds']);
					} else {
						$this->updateNotify($user['user_id'], $type, $data, array('status' => 1, 'timestamp' => time() + $template['delay_seconds']));
					}
					if ($sendPush)
						$this->sendNotification($user['user_id'], $type, $alert, $data, $sound, $template['delay_seconds']);
				}
			}
		}
	}

	public function sendToAllUser($type, $data = null, $sound = 'default') {
		$users = $this->db
			->select('user_id, full_name')
			->where('status', 1)
			->get('user')->result_array();
		$this->createNotifyMany($users, $type, $data, $sound);
	}

	public function createNotifyMany($users, $type, $data = null, $sound = 'default') {
		if ($users != null && is_array($users) && count($users) > 0) {
			$template = Notify_model::$templates[$type];
			$alert = $this->fillDataToTemplate($template['formatted'], $data, $type);
			foreach ($users as $user) {
				if ($this->checkSetting($user['user_id'], $template['setting_key'])) {
					if (!$this->checkNotify($user['user_id'], $type, $data)) {
						$this->insertUserNotify($user['user_id'], $type, $template['formatted'], $data, $template['delay_seconds']);
					} else {
						$this->updateNotify($user['user_id'], $type, $data, array('status' => 1, 'timestamp' => time() + $template['delay_seconds']));
					}
					$this->sendNotification($user['user_id'], $type, $alert, $data, $sound, $template['delay_seconds']);
				}
			}
		}
	}

	public function removeNotify($user_id, $type, $data = null) {
		$this->db->where('type', $type);
		if ($data != null)
			$this->db->where('data', json_encode($data));
		if ($user_id != 0) {
			$this->db->where('user_id', $user_id);
		}
		$this->db->delete('user_notify');
	}

	public function updateNotify($user_id, $type, $data = null, $params) {
		$this->db->where('type', $type);
		if ($data != null)
			$this->db->where('data', json_encode($data));
		$this->db->where('user_id', $user_id);
		$this->db->update('user_notify', $params);
	}

	private function insertUserNotify($user_id, $type, $template, $data, $delay_seconds = 0) {
		$params = array();
		$params['user_id'] = $user_id;
		$params['type'] = $type;
		$params['timestamp'] = time() + $delay_seconds;
		$params['content'] = $template;
		if ($data != null)
			$params['data'] = json_encode($data);
		$params['status'] = 1;
		$this->db->insert('user_notify', $params);
		$notify_id = $this->db->insert_id();

		if ($data == null || empty($data)) {
			return;
		}
		$reference_types = ['user' => 'user', 'product' => 'product', 'episode' => 'episode', 'comment' => 'comment', 'follower' => 'user', 'uid' => 'user'];
		foreach ($data as $key => $value) {
			foreach ($reference_types as $type => $mappedType) {
				if (startsWith($key, $type)) {
					$this->db->insert('notification_references', [
						'refer_type' => $mappedType,
						'notify_id' => $notify_id,
						'refer_id' => $value,
					]);
				}
			}
		}
	}

	public function deleteReference($refer_type, $refer_id) {
		$this->db->get('notification_references');
		$this->db->where('refer_type', $refer_type);
		$this->db->where('refer_id', $refer_id);
		$this->db->select('notify_id');
		$notify_items = $this->db->get()->result_array();
		if (count($notify_items) == 0) {
			return 0;
		}
		$notify_ids = [];
		foreach ($notify_items as $item) {
			$notify_ids[] = $item['notify_id'];
		}
		$this->db->where_in('notify_id', $notify_ids);
		$this->db->delete('user_notify');
		if ($refer_type == 'user') {
			$this->db->where('user_id', $refer_id);
			$this->db->delete('user_notify');
		}
	}

	public function fillDataToTemplate($template, $data, $type) {
		$user_name = '';
		$product_name = '';
		$content = '';
		if (isset($data['user_id'])) {
			$user = $this->getUserForNotify($data['user_id']);
			$user_name = $user['user_name'] . ' ';
		}
		if ($data != null && is_array($data)) {
			foreach ($data as $key => $value) {
				$template = str_replace("<<$key>>", $value, $template);
			}
		}
		if ($type >= 50) {
//			if (isset($data['episode_id'])) {
//				$episode = $this->getPartEpisodeForNotify($data['episode_id']);
//				$content = $content . ' part ' . $episode['position'];
//			}
			if (isset($data['product_id'])) {
				$product = $this->getProductForNotify($data['product_id']);
				if ($product != null) {
					$template = str_replace("<<story_name>>", $product['name'], $template);
				}
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
		$this->db->where('reg_id <>', '');
		$query = $this->db->get('device_user');
		return $query->num_rows() > 0 ? $query->result_array() : null;
	}

	public function sendNotification($uid, $type, $alert, $data = null, $sound = 'default', $delaySeconds = 0) {
		$data_send = array(
			'alert' => $alert,
			'type' => $type,
			'data' => $data,
			'sound' => $sound,
		);

		$devices = $this->getDeviceTokensOfUser($uid);
		log_message('debug', 'send-notification-query: ' . $this->db->last_query());
		if ($devices != null) {
			$this->cipush = new Cipush();
			foreach ($devices as $device) {
				if ($device['dtype_id'] == 1) {
					$this->cipush->addAndroid($device['reg_id'], $data_send, time() + $delaySeconds);
				} else {
					$this->cipush->addIOS($device['reg_id'], $data_send, time() + $delaySeconds);
				}
			}
		}
	}

	public function getUserForNotify($user_id) {
		$this->db->select('user_name, user_type, avatar, full_name, email');
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('user');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getProductForNotify($product_id) {
		$this->db->select('name');
		$this->db->where('product_id', $product_id);
		$query = $this->db->get('product');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getComment($comment_id) {
		$this->db->where('comment_id', $comment_id);
		$query = $this->db->get('comments');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getSeasonForNotify($season_id) {
		$this->db->select('name');
		$this->db->where('season_id', $season_id);
		$query = $this->db->get('season');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getEpisodeForNotify($episode_id) {
		$this->db->select('name');
		$this->db->where('episode_id', $episode_id);
		$query = $this->db->get('episode');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getUserFollow($user_id) {
		$this->db->select('u.*');
		$this->db->from('user_follow u');
		$this->db->where('u.user_id <>', $user_id);
		$this->db->where('u.follower_id', $user_id);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getPartEpisodeForNotify($episode_id) {
		$this->db->select('position');
		$this->db->where('episode_id', $episode_id);
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

	public function checkSetting($user_id, $key) {
		$this->db->from('user_notification_setting');
		$this->db->where('user_id', $user_id);
		$item = $this->db->get()->first_row('array');
		if ($item == null)
			return false;
		if (isset($item[$key]) && $item[$key] == 1) {
			return true;
		}
		return false;
	}
}
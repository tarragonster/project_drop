<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class News extends BR_Controller {
	const SUPPORTED_IOS_VERSION = '1.0.7';
	const SUPPORTED_ANDROID_VERSION = '1.0.4';
	public function __construct() {
		parent::__construct();
		$this->load->model('notify_model');
		$this->load->model('user_model');
	}

	/**
	 * @SWG\Get(
	 *     path="/news/get",
	 *     summary="Get notification",
	 *     operationId="get-notifications",
	 *     tags={"Account"},
	 *     produces={"application/json"},
	 *     consumes={"application/json"},
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *     ),
	 *     security={
	 *       {"accessToken": {}}
	 *     }
	 * )
	 */
	public function get_get() {
		$this->validate_authorization();
		$news = array();
		$following = array();
		$you = array();

		$newVersion = false;
		if (($this->device_type == DEVICE_TYPE_ANDROID && version_compare($this->app_version, News::SUPPORTED_ANDROID_VERSION) >= 0) || ($this->device_type == DEVICE_TYPE_IOS && version_compare($this->app_version, News::SUPPORTED_IOS_VERSION) >= 0)) {
			$newVersion = true;
		}

		$items = $this->notify_model->getNewForFollowing($this->user_id);
		if (is_array($items) && count($items) > 0) {
			foreach ($items as $item) {
				$filledItem = $newVersion ? $this->getNotifyData($item) : $this->fillData($item, 1);
				if ($filledItem != null) {
					array_push($following, $filledItem);
				}
			}
		}
		$items = $this->notify_model->getNewForYou($this->user_id);
		if (is_array($items) && count($items) > 0) {
			foreach ($items as $item) {
				$filledItem = $newVersion ? $this->getNotifyData($item) : $this->fillData($item, 2);
				if ($filledItem != null) {
					array_push($you, $filledItem);
				}
			}
		}
		$news['following'] = $following;
		$news['you'] = $you;
		$news['num_news'] = $this->notify_model->countNotification($this->user_id);
		$this->create_success(array('news' => $news));
	}

	/**
	 * @SWG\Get(
	 *     path="/news/getBadge",
	 *     summary="Get notification getBadge",
	 *     operationId="get-badge",
	 *     tags={"Account"},
	 *     produces={"application/json"},
	 *     consumes={"application/json"},
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *     ),
	 *     security={
	 *       {"accessToken": {}}
	 *     }
	 * )
	 */
	public function getBadge_get() {
		$this->validate_authorization();
		$news['num_news'] = $this->notify_model->countNotification($this->user_id);
		$this->create_success($news, 'Update success');
	}

	public function readNotify_post() {
		$notify_id = $this->c_getNumberNotNull('notify_id');

		$this->notify_model->update(array('status' => 0), $notify_id);
		$this->create_success(null, 'Update success');
	}

	public function readAllNotifies_post() {
		$this->notify_model->updateAll($this->user_id, array('status' => 0));
		$news['num_news'] = $this->notify_model->countNotification($this->user_id);
		$this->create_success($news, 'Update success');
	}

	public function fillData($item, $checkFill) {
		$notify = array();
		$notify['notify_id'] = $item['notify_id'];
		$notify['type'] = $item['type'];
		$notify['data'] = $item['data'] == null ? null : json_decode($item['data'], true);
		$notify['user_name'] = '';
		$notify['product_name'] = '';
		$notify['avatar'] = '';
		$notify['has_followed'] = '0';

		$item['content'] = Notify_model::$templates[$item['type']]['formatted'];

		if ($notify['data'] != null) {
			foreach ($notify['data'] as $key => $value) {
				$item['content'] = str_replace("<<$key>>", $value, $item['content']);
			}

			if (isset($notify['data']['user_id'])) {
				$user = $this->notify_model->getUserForNotify($notify['data']['user_id']);
				if ($user == null) {
					return null;
				}
				$notify['avatar'] = $user['avatar'];
				$notify['user_type'] = $user['user_type'];
				$notify['user_name'] = empty($user['user_name']) ? $user['full_name'] : $user['user_name'];
				$notify['has_followed'] = $this->user_model->checkFollower($this->user_id, $notify['data']['user_id']) ? '1' : '0';
			}
			if (isset($notify['data']['uid_comment'])) {
				if ($notify['data']['uid_comment'] == $notify['data']['user_id']) {
					if ($notify['type'] == 10) {
						$item['content'] = str_replace("<<username>>", 'their', $item['content']);
					} else {
						$item['content'] = str_replace("<<username>>", 'to their', $item['content']);
					}
				} else {
					$user = $this->notify_model->getUserForNotify($notify['data']['uid_comment']);
					$notify['user_name'] .= '*' . $user['user_name'];
					$notify['avatar2'] = $user['avatar'];
					$notify['user_id2'] = $user['user_id'];
					$notify['user_type2'] = $user['user_type'];
					$item['content'] = str_replace(" <<username>>", '*', $item['content']);
				}

				if ($item['type'] == 9 && isset($notify['data']['comment_id'])) {
					$comment = $this->notify_model->getComment($notify['data']['comment_id']);
					$item['content'] .= $comment['content'] . ' on';
				}
			}
			if ($checkFill == 1) {
				$content = '';
				$notify['product_name'] = '';
				if (isset($notify['data']['product_id'])) {
					$product = $this->notify_model->getProductForNotify($notify['data']['product_id']);
					$notify['product_name'] = $product != null ? $product['name'] : '';
					$notify['product_image'] = $product != null ? $product['image'] : '';
				}
				if (isset($notify['data']['episode_id'])) {
					$episode = $this->notify_model->getPartEpisodeForNotify($notify['data']['episode_id']);
					$notify['episode_image'] = $episode['image'];
					$content = $content . $episode['name'];
				}
				if ($content != '') {
					$item['content'] = $item['content'] . ' ' . $content . ' of';
				}
			} else {
				$episode_name = '';
				if (isset($notify['data']['episode_id'])) {
					$episode = $this->notify_model->getPartEpisodeForNotify($notify['data']['episode_id']);
					if ($episode != null) {
						$notify['episode_image'] = $episode['image'];
						$episode_name = $episode_name . $episode['name'];
					}
				}
				if ($episode_name != '' && in_array($notify['type'], [53, 54])) {
					$item['content'] = $item['content'] . ' on ' . $episode_name . ' of';
				}
				if (isset($notify['data']['product_id'])) {
					$product = $this->notify_model->getProductForNotify($notify['data']['product_id']);
					if ($product != null) {
						$notify['product_image'] = $product['image'];
						$notify['product_name'] = $notify['type'] == 52 ? '' : $product['name'];
						$item['content'] = str_replace("<<story_name>>", $product['name'], $item['content']);
					}
				}
			}
		}
		$notify['content'] = $item['content'];
		$notify['timestamp'] = $item['timestamp'];
		$notify['status'] = $item['status'];
		return $notify;
	}

	function testNews_get() {
		$this->load->model('notify_model');
		$following = [];
		$you = [];
		$items = $this->notify_model->getNewForFollowing($this->user_id);
		if (is_array($items) && count($items) > 0) {
			foreach ($items as $item) {
				$filledItem = $this->fillNotifyData($item);
				if ($filledItem != null) {
					array_push($following, $filledItem);
				}
			}
		}
//		$unset_keys = ['notify_id', 'data', 'avatar', 'has_followed', 'user_type', 'avatar2', 'user_id2', 'user_type2', 'product_image', 'episode_image', 'status', 'timestamp'];
//		$following = unset_keys_array($following, $unset_keys);

		$items = $this->notify_model->getNewForYou($this->user_id);
		if (is_array($items) && count($items) > 0) {
			foreach ($items as $item) {
				$filledItem = $this->fillNotifyData($item);
				if ($filledItem != null) {
					array_push($you, $filledItem);
				}
			}
		}
//		$you = unset_keys_array($you, $unset_keys);

		$this->create_success(
			[
				'following' => $following,
				'you' => $you
			]);
	}

	public function fillNotifyData($item, $fillAlert = false) {
		$notify = array();
		$notify['notify_id'] = $item['notify_id'];
		$notify['type'] = $item['type'];
		$notify['data'] = $item['data'] == null ? null : json_decode($item['data'], true);
		$notify['user_name'] = '';
		$notify['product_name'] = '';
		$notify['avatar'] = '';
		$notify['has_followed'] = '0';

		$alert_content = Notify_model::$templates[$item['type']]['alert_formatted'];
		$notify['content'] = Notify_model::$templates[$item['type']]['formatted'];

		if ($notify['data'] != null) {
			foreach ($notify['data'] as $key => $value) {
				if (!empty($value)) {
					$alert_content = str_replace("<<$key>>", $value, $alert_content);
				}
			}

			if (isset($notify['data']['user_id'])) {
				$user = $this->notify_model->getUserForNotify($notify['data']['user_id']);
				if ($user == null) {
					return null;
				}
				$notify['avatar'] = $user['avatar'];
				$notify['user_type'] = $user['user_type'];
				$notify['user_name'] = empty($user['user_name']) ? $user['full_name'] : $user['user_name'];
				if ($fillAlert) {
					$alert_content = str_replace("<<username>>", $notify['user_name'], $alert_content);
				} else {
					$alert_content = str_replace("<<username>> ", '', $alert_content);
				}
				$notify['has_followed'] = $this->user_model->checkFollower($this->user_id, $notify['data']['user_id']) ? '1' : '0';
			}
			if (isset($notify['data']['uid_comment'])) {
				if ($notify['data']['uid_comment'] == $notify['data']['user_id']) {
					if ($notify['type'] == 10) {
						$alert_content = str_replace("<<username_2nd>>", 'their', $alert_content);
					} else {
						$alert_content = str_replace("<<username_2nd>>", 'to their', $alert_content);
					}
				} else {
					$userSecond = $this->notify_model->getUserForNotify($notify['data']['uid_comment']);
					$notify['user_name'] .= '*' . $userSecond['user_name'];
					$notify['avatar2'] = $userSecond['avatar'];
					$notify['user_id2'] = $userSecond['user_id'];
					$notify['user_type2'] = $userSecond['user_type'];
					if ($fillAlert) {
						$alert_content = str_replace("<<username_seconds>>", $userSecond['user_name'], $alert_content);
					} else {
						$alert_content = str_replace(" <<username_seconds>>", '*', $alert_content);
					}
				}

				if ($item['type'] == 9 && isset($notify['data']['comment_id'])) {
					$comment = $this->notify_model->getComment($notify['data']['comment_id']);
					$alert_content = str_replace("<<comment_content>>", $comment['content'], $alert_content);
				}
			}

			if (isset($notify['data']['product_id'])) {
				$product = $this->notify_model->getProductForNotify($notify['data']['product_id']);
				if ($product != null) {
					$notify['product_image'] = $product['image'];
					$notify['product_name'] = $notify['type'] == 52 ? '' : $product['name'];

					$alert_content = str_replace("<<story_name>>", $product['name'], $alert_content);
					if ($fillAlert) {
						$alert_content = str_replace("<<story_name>>", $product['name'], $alert_content);
					} else {
						$alert_content = str_replace(" <<story_name>>", '', $alert_content);
					}
				}
			}
			if (isset($notify['data']['episode_id'])) {
				$episode = $this->notify_model->getPartEpisodeForNotify($notify['data']['episode_id']);
				if ($episode != null) {
					$notify['episode_image'] = $episode['image'];
					$alert_content = str_replace("<<block_name>>", $episode['name'], $alert_content);
				}
			}
		}
		$notify['timestamp'] = $item['timestamp'];
		$notify['status'] = $item['status'];
		$notify['content'] = $alert_content;
		return $notify;
	}

	public function getNotifyData($item) {
		$notify = array();
		$notify['notify_id'] = $item['notify_id'];
		$notify['type'] = $item['type'];
		$notify['data'] = $item['data'] == null ? null : json_decode($item['data'], true);
		$notify['user_name'] = '';
		$notify['username_2nd'] = '';
		$notify['product_name'] = '';
		$notify['block_name'] = '';
		$notify['avatar'] = '';
		$notify['has_followed'] = '0';

		$alert_content = Notify_model::$templates[$item['type']]['alert_formatted'];

		if ($notify['data'] != null) {
			foreach ($notify['data'] as $key => $value) {
				if (!empty($value) && $key != 'story_name') {
					$alert_content = str_replace("<<$key>>", $value, $alert_content);
				}
			}

			if (isset($notify['data']['user_id'])) {
				$user = $this->notify_model->getUserForNotify($notify['data']['user_id']);
				if ($user == null) {
					return null;
				}
				$notify['avatar'] = $user['avatar'];
				$notify['user_type'] = $user['user_type'];
				if (isset($notify['data']['user_id']) == $this->user_id) {
					$notify['user_name'] = empty($user['user_name']) ? $user['full_name'] : $user['user_name'];
					$notify['has_followed'] = $this->user_model->checkFollower($this->user_id, $notify['data']['user_id']) ? '1' : '0';
				} else {
					$notify['user_name'] = 'You';
					$notify['has_followed'] = '0';
				}
			}
			if (isset($notify['data']['uid_comment'])) {
				if ($notify['data']['uid_comment'] == $notify['data']['user_id']) {
					if ($notify['type'] == 10) {
						$alert_content = str_replace("<<username_2nd>>", 'their', $alert_content);
					} else {
						$alert_content = str_replace("<<username_2nd>>", 'to their', $alert_content);
					}
				} else {
					$userSecond = $this->notify_model->getUserForNotify($notify['data']['uid_comment']);
					$notify['avatar2'] = $userSecond['avatar'];
					$notify['user_id2'] = $userSecond['user_id'];
					$notify['user_type2'] = $userSecond['user_type'];
					$notify['username_2nd'] = $userSecond['user_name'];
				}

				if ($item['type'] == 9 && isset($notify['data']['comment_id'])) {
					$comment = $this->notify_model->getComment($notify['data']['comment_id']);
					$alert_content = str_replace("<<comment_content>>", $comment['content'], $alert_content);
				}
			}

			if (isset($notify['data']['product_id'])) {
				$product = $this->notify_model->getProductForNotify($notify['data']['product_id']);
				if ($product != null) {
					$notify['product_image'] = $product['image'];
					$notify['product_name'] = $product['name'];
				}
			}
			if (isset($notify['data']['episode_id'])) {
				$episode = $this->notify_model->getPartEpisodeForNotify($notify['data']['episode_id']);
				if ($episode != null) {
					$notify['episode_image'] = $episode['image'];
					$notify['block_name'] = $episode['name'];
				}
			}
		}
		$notify['timestamp'] = $item['timestamp'];
		$notify['status'] = $item['status'];
		$notify['content'] = $alert_content;
		return $notify;
	}
}
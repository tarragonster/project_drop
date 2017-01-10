<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class User extends BR_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('user_model');
	}

	public function registerDevice_post() {
		$time = time();
		$dtype_id = $this->post('dtype_id');
		$device_id = $this->post('device_id');
		$reg_id = $this->post('device_token');
		$user_id = $this->post('user_id') * 1;
		if ($dtype_id == '' || $device_id == '' || $reg_id == '') {
			$this->create_error(-1);
		}

		if (!$this->user_model->checkDeviceId($device_id)) {
			$q = array('dtype_id' => $dtype_id, 'device_id' => $device_id, 'reg_id' => $reg_id,
				'regtime' => $time, 'last_activity' => $time, 'user_id' => $user_id);

			$this->db->insert('device_user', $q);
		} else {
			$q = array("reg_id" => $reg_id, "last_activity" => $time);
			if ($user_id != 0)
				$q['user_id'] = $user_id;
			$this->db->where('device_id', $device_id);
			$this->db->update('device_user', $q);
		}
		$this->create_success(null);
	}

	public function login_post() {
		$time = time();
		$email = $this->post('email');
		$password = $this->post('password');
		$device_id = $this->post('device_id');

		if (!$this->user_model->checkDeviceId($device_id)) {
			$this->create_error(-47);
		}

		$user_id = $this->user_model->getUidByAccount($email, $password);
		if ($user_id == -1) {
			$this->create_error(-4);
		}

		$this->user_model->updateDeviceUser($user_id, $device_id);
		$params = array();
		$params['last_login'] = $time;
		$this->user_model->update($params, $user_id);
		$data = $this->__getUserProfile($user_id);
		$this->load->library('oauths');
		$data['access_token'] = $this->oauths->success($user_id, $device_id);
		$this->create_success(array('profile' => $data), 'Login Success');
	}

	public function logout_post() {
		$this->validate_authorization();
		if (!$this->user_model->checkUid($this->user_id)) {
			$this->create_error(-10);
		}

		$device_id = $this->post('device_id');
		$this->user_model->logoutDevice($this->user_id, $device_id);

		$this->load->library('oauths');
		$this->oauths->remove($this->user_id, $this->access_token);

		$this->create_success(null, "Success");
	}

	public function register_post() {
		$email = $this->c_getEmail('email');
		$password = $this->c_getWithLength('password', 32, 6);

		if ($this->user_model->checkEmail($email)) {
			$this->create_error(-5);
		}

		$this->create_success(null, 'Check success');
	}


	public function registerProfile_post() {
		$time = time();
		$email = $this->c_getEmail('email');
		$password = $this->c_getWithLength('password', 32, 6);
		$device_id = $this->c_getNotNull('device_id');
		$user_name = $this->c_getNotNull('user_name');
		$full_name = $this->c_getNotNull('full_name');

		if ($this->user_model->checkUserName($user_name)) {
			$this->create_error(-78);
		}

		$params = array();

		$params['email'] = $email;
		$params['password'] = ($password != '' ? md5($password) : '');
		$params['joined'] = $time;
		$params['last_login'] = $time;
		$params['user_name'] = $user_name;
		$params['full_name'] = $full_name;
		$params['avatar'] = 'media/avatar/user/user.png';

		$user_id = $this->user_model->insert($params);

		$avatar = isset($_FILES['avatar']) ? $_FILES['avatar'] : null;
		if ($avatar != null) {
			$this->load->model('file_model');
			if ($this->file_model->checkFileImage($avatar)) {
				$path = $this->file_model->createPathAvatar($user_id, $avatar);
				$this->file_model->saveFile($avatar, $path);
				$params['avatar'] = $path;
				$path_thumb = $this->file_model->createThumbnailName($path);
				$this->file_model->cropAndResizeThumbNail($path, $path_thumb);
			}
		}

		// add in-app notification
		$this->load->model('notify_model');
		$this->notify_model->createNotify($user_id, 55);

		$this->user_model->update($params, $user_id);

		$this->user_model->updateDeviceUser($user_id, $device_id);

		$data = $this->__getUserProfile($user_id);
		$this->load->library('oauths');
		$data['access_token'] = $this->oauths->success($user_id, $device_id);
		$this->create_success(array('profile' => $data), 'Register success');
	}

	public function terms_get() {
		$terms = $this->user_model->getTerms();
		$this->create_success(array('terms' => $terms));
	}

	public function addWatch_post() {
		$this->validate_authorization();
		if (!$this->user_model->checkUid($this->user_id)) {
			$this->create_error(-10);
		}
		$product_id = $this->c_getNotNull('product_id');
		$this->load->model('product_model');
		$product = $this->product_model->checkProduct($product_id);
		if (!$product) {
			$this->create_error(-35);
		}
		$watch = $this->user_model->checkWatchList($this->user_id, $product_id);
		if ($watch) {
			$this->create_error(-76);
		}
		$this->user_model->addWatch(array('product_id' => $product_id, 'user_id' => $this->user_id));
		$this->load->model('notify_model');
		$this->notify_model->createNotify($this->user_id, 4, array('user_id' => $this->user_id, 'product_id' => $product_id));
		$this->create_success(null);
	}

	public function removeWatch_post() {
		$this->validate_authorization();
		if (!$this->user_model->checkUid($this->user_id)) {
			$this->create_error(-10);
		}
		$product_id = $this->c_getNotNull('product_id');
		if ($product_id == 0) {

		} else {
			$this->load->model('product_model');
			$product = $this->product_model->checkProduct($product_id);
			if (!$product) {
				$this->create_error(-35);
			}
		}
		$this->user_model->removeWatchList($this->user_id, $product_id);
		$this->create_success(null);
	}

	public function watchEpisode_post() {
		$this->validate_authorization();
		if (!$this->user_model->checkUid($this->user_id)) {
			$this->create_error(-10);
		}
		$product_id = $this->c_getNotNull('product_id') * 1;
		$this->load->model('product_model');
		$product = $this->product_model->checkProduct($product_id);

		if ($product == null) {
			$this->create_error(-17, 'Unknown resource');
		}

		$episode_id = $this->post('episode_id') * 1;
		$time = $this->c_getNotNull('time');
		if ($episode_id != 0) {
			//update for episode
			$this->load->model('episode_model');
			$this->episode_model = new Episode_model();
			$episode = $this->episode_model->getEpisode($episode_id);
			if ($episode == null || $episode['product_id'] != $product_id) {
				$this->create_error(-17, 'Unknow episode_id');
			}
			$watch = $this->episode_model->getWatchProduct($this->user_id, $product_id);
			if ($watch != null) {
				if ($time == -1) {
					$this->episode_model->update(array('is_watched' => 1), $episode_id);
					$episodeNext = $this->episode_model->getNextEpisode($episode['position'], $episode['season_id']);
					if ($episodeNext == null) {
						$this->episode_model->removeWatchEpisode($watch['id']);
					} else {
						$this->episode_model->updateWatchEpisode(array('episode_id' => $episodeNext['episode_id'], 'start_time' => 0, 'update_time' => time()), $watch['id']);
					}
				} else {
					$this->episode_model->updateWatchEpisode(array('episode_id' => $episode_id, 'start_time' => $time, 'update_time' => time()), $watch['id']);
				}
			} else {
				if ($time != -1) {
					$product_id = $this->episode_model->getProdutID($episode['season_id']);
					$this->user_model->update(array('product_id' => $product_id), $this->user_id);
					$this->load->model('notify_model');
					$this->notify_model->createNotify($this->user_id, 1, array('user_id' => $this->user_id, 'product_id' => $product_id));
					$this->episode_model->addWatchEpisode(array('episode_id' => $episode_id, 'user_id' => $this->user_id, 'season_id' => $episode['season_id'], 'product_id' => $product_id, 'start_time' => $time, 'update_time' => time()));
				}
			}
		} else {
			//update for trailler
			$this->load->model('product_model');
			$product = $this->product_model->checkProduct($product_id);
			if (!$product) {
				$this->create_error(-35);
			}
			if ($time == -1) {
				$this->product_model->removeWatchTrailler($product_id, $this->user_id);
			} else {
				$watch = $this->product_model->checkWatchTrailler($this->user_id, $product_id);
				if ($watch) {
					$this->product_model->updateWatchTrailler($product_id, $this->user_id, array('start_time' => $time));
				} else {
					$this->product_model->addWatchTrailler(array('product_id' => $product_id, 'user_id' => $this->user_id, 'start_time' => $time));
				}
			}
		}
		$this->create_success(null);
	}

	public function addFollow_post() {
		$this->validate_authorization();
		if (!$this->user_model->checkUid($this->user_id)) {
			$this->create_error(-10);
		}
		$follower_id = $this->c_getNotNull('follower_id');
		if (!$this->user_model->checkUid($follower_id)) {
			$this->create_error(-10);
		}
		if ($this->user_model->checkFollower($this->user_id, $follower_id)) {
			$this->user_model->removeFollow($this->user_id, $follower_id);
			$this->load->model('notify_model');
			$this->notify_model->removeNotify($follower_id, 51, array('user_id' => $this->user_id));
		} else {
			$this->user_model->addFollow(array('user_id' => $this->user_id, 'follower_id' => $follower_id, 'timestamp' => time()));
			$this->load->model('notify_model');
			$this->notify_model->createNotify($follower_id, 51, array('user_id' => $this->user_id));
		}
		$this->create_success(null);
	}

	public function likeEpisode_post() {
		$this->validate_authorization();
		if (!$this->user_model->checkUid($this->user_id)) {
			$this->create_error(-10);
		}
		$episode_id = $this->c_getNotNull('episode_id');
		$status = $this->c_getNotNull('status');
		$this->load->model('episode_model');
		$episode = $this->episode_model->checkEpisode($episode_id);
		if (!$episode) {
			$this->create_error(-77);
		}
		$this->load->model('notify_model');
		$isCheck = $this->user_model->checkEpisodeExits($this->user_id, $episode_id);
		$product_id = $this->episode_model->getProdutID($episode['season_id']);
		if ($isCheck == -1) {
			$this->user_model->addLike(array('episode_id' => $episode_id, 'user_id' => $this->user_id, 'status' => $status));
			if ($status == 1) {
				$this->notify_model->createNotify($this->user_id, 2, array('user_id' => $this->user_id, 'episode_id' => $episode_id, 'season_id' => $episode['season_id'], 'product_id' => $product_id));
			} else {
				$this->notify_model->createNotify($this->user_id, 3, array('user_id' => $this->user_id, 'episode_id' => $episode_id, 'season_id' => $episode['season_id'], 'product_id' => $product_id));
			}
		} else {
			if ($isCheck != $status) {
				$this->user_model->updateLike($episode_id, $this->user_id, $status);
				if ($status == 1) {
					$this->notify_model->updateNotify($this->user_id, 3, array('user_id' => $this->user_id, 'episode_id' => $episode_id, 'season_id' => $episode['season_id'], 'product_id' => $product_id), array('type' => 2, 'content' => Notify_model::$templates[2], 'timestamp' => time()));
				} else {
					$this->notify_model->updateNotify($this->user_id, 2, array('user_id' => $this->user_id, 'episode_id' => $episode_id, 'season_id' => $episode['season_id'], 'product_id' => $product_id), array('type' => 3, 'content' => Notify_model::$templates[3], 'timestamp' => time()));
				}
			} else {
				$this->user_model->deleteLike($episode_id, $this->user_id);
				if ($status == 1) {
					$this->notify_model->removeNotify($this->user_id, 2, array('user_id' => $this->user_id, 'episode_id' => $episode_id, 'season_id' => $episode['season_id'], 'product_id' => $product_id));
				} else {
					$this->notify_model->removeNotify($this->user_id, 3, array('user_id' => $this->user_id, 'episode_id' => $episode_id, 'season_id' => $episode['season_id'], 'product_id' => $product_id));
				}
			}
		}
		$data = array();
		$data['num_like'] = $this->episode_model->countLike($episode_id, 1);
		$data['num_dislike'] = $this->episode_model->countLike($episode_id, 0);
		$data['has_like'] = $this->episode_model->hasLikeEpisode($episode_id, $this->user_id, 1);
		$data['has_dislike'] = $this->episode_model->hasLikeEpisode($episode_id, $this->user_id, 0);
		$this->create_success(array('likes' => $data));
	}

	public function profile_get() {
		$this->validate_authorization();
		$profile = $this->__getUserProfile($this->user_id);
		$this->create_success(array('profile' => $profile));
	}

	public function user_get($user_id) {
		if (!$this->user_model->checkUid($user_id)) {
			$this->create_error(-10);
		}
		$user = $this->__getUserProfile($user_id);
		$user['is_follow'] = '0';
		if ($this->user_id != null) {
			if ($this->user_model->checkFollower($this->user_id, $user_id)) {
				$user['is_follow'] = '1';
			}
		}
		$this->create_success(array('user' => $user));
	}

	public function authenticate_post() {
		$page = $this->post('page');
		$collection = null;
		$this->create_success(array('collection' => $collection));
	}

	public function following_get($user_id = -1) {

		if ($user_id != -1) {
			if (!$this->user_model->checkUid($user_id)) {
				$this->create_error(-10);
			}
			$following = $this->user_model->getFollowing($user_id);
			if ($this->user_id != null) {
				$follow_user = $this->user_model->getFollowing($this->user_id);
				foreach ($following as $key => $user) {
					$following[$key]['isFollow'] = '0';
					foreach ($follow_user as $follow) {
						if ($user['user_id'] == $follow['follower_id']) {
							$following[$key]['is_follow'] = '1';
							break;
						}
					}
				}
			} else {
				foreach ($following as $key => $follow) {
					$following[$key]['is_follow'] = 0;
				}
			}
		} else {
			$following = $this->user_model->getFollowing($this->user_id);
			foreach ($following as $key => $follow) {
				$following[$key]['is_follow'] = 1;
			}
		}
		$this->create_success(array('following' => $following));
	}

	public function followers_get($user_id = -1) {
		if ($user_id != -1) {
			if (!$this->user_model->checkUid($user_id)) {
				$this->create_error(-10);
			}
			$followers = $this->user_model->getFollowers($user_id);
			if ($this->user_id != null) {
				$following = $this->user_model->getFollowing($this->user_id);
				foreach ($followers as $key => $user) {
					$followers[$key]['isFollow'] = '0';
					foreach ($following as $follow) {
						if ($user['user_id'] == $follow['follower_id']) {
							$followers[$key]['is_follow'] = '1';
							break;
						}
					}
				}
			} else {
				foreach ($followers as $key => $follow) {
					$followers[$key]['is_follow'] = 0;
				}
			}

		} else {
			$followers = $this->user_model->getFollowers($this->user_id);
			$following = $this->user_model->getFollowing($this->user_id);
			foreach ($followers as $key => $user) {
				$followers[$key]['isFollow'] = '0';
				foreach ($following as $follow) {
					if ($user['user_id'] == $follow['follower_id']) {
						$followers[$key]['is_follow'] = '1';
						break;
					}
				}
			}
		}
		$this->create_success(array('followers' => $followers));
	}

	public function streamingQuality_post() {
		$page = $this->post('page');
		$collection = null;
		$this->create_success(array('collection' => $collection));
	}

	public function getWatchList_post() {
		$page = $this->post('page');
		$collection = null;
		$this->create_success(array('collection' => $collection));
	}

	public function getWatched_post() {
		$page = $this->post('page');
		$collection = null;
		$this->create_success(array('collection' => $collection));
	}

	public function changeAvatar_post() {
		$this->validate_authorization();
		if (!$this->user_model->checkUid($this->user_id)) {
			$this->create_error(-10);
		}
		$time = time();
		$params = array();
		$avatar = isset($_FILES['avatar']) ? $_FILES['avatar'] : null;
		if ($avatar != null) {
			$this->load->model('file_model');
			if ($this->file_model->checkFileImage($avatar)) {
				$path = $this->file_model->createPathAvatar($this->user_id, $avatar);
				$this->file_model->saveFile($avatar, $path);
				$path_thumb = $this->file_model->createThumbnailName($path);
				$this->file_model->cropAndResizeThumbNail($path, $path_thumb);
				$params['avatar'] = $path;
			}
		} else {
			$this->create_error(-1);
		}

		$oldavatar = $this->user_model->getAvatarUser($this->user_id);

		$this->user_model->update($params, $this->user_id);
		if ($oldavatar != 'media/avatar/user/user.png')
			$this->file_model->removeFileAndThumb($oldavatar);
		$profile = $this->__getUserProfile($this->user_id);
		$this->create_success(array('profile' => $profile), 'Edit success');

	}


	public function forgotPassword_post() {
		$email = $this->c_getEmail("email");
		$user = $this->user_model->checkEmail($email);
		if (!$user) {
			$this->create_error(-9);
		}
		$time = time();
		$code = md5(md5($email . $time . '|mDyN2U') . $time);
		$base_64 = base64_encode($user['user_id'] . '|' . $code);
		$params = array();
		$params['user_id'] = $user['user_id'];
		$params['code'] = $code;
		$params['created'] = $time;
		$this->user_model->insertCodeResetPassword($params);
		$params['url_code'] = 'http://secondscreentv.us/reset-password?code=' . $base_64;
		$params['username'] = $user['full_name'];
		$this->load->model("email_model");
		$this->email_model->emailForgotPassword($email, $params);
		$this->create_success(null, 'Check email for get new password');
	}

	public function __getUserProfile($user_id) {
		$profile = $this->user_model->getProfileUser($user_id);
		$profile['num_following'] = $this->user_model->countFollowing($user_id);
		$profile['num_followers'] = $this->user_model->countFollowers($user_id);
		$profile['watch_list'] = $this->user_model->getListWatching($user_id);
		$profile['continue_watching'] = $this->user_model->getListContinue($user_id);
		$this->load->model('news_model');
		$profile['num_news'] = $this->news_model->countNotification($this->user_id);
		return $profile;
	}
}
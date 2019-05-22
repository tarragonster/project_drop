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
			if ($user_id != 0) {
				$q['user_id'] = $user_id;
			}
			$this->db->where('device_id', $device_id);
			$this->db->update('device_user', $q);
		}
		$this->create_success(null);
	}

	/**
	 * @SWG\Post(
	 *     path="/user/login",
	 *     summary="Login via email",
	 *     operationId="login",
	 *     tags={"Authorization"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="User email",
	 *         in="formData",
	 *         name="email",
	 *         required=true,
	 *         type="string",
	 *         default="email@example.com"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Password",
	 *         in="formData",
	 *         name="password",
	 *         required=true,
	 *         type="string",
	 *         default="123456"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Device ID",
	 *         in="formData",
	 *         name="device_id",
	 *         required=false,
	 *         type="string",
	 *     ),
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *     )
	 * )
	 */
	public function login_post() {
		$time = time();
		$email = $this->c_getNotNull('email');
		$password = $this->c_getNotNull('password');
		$device_id = $this->c_getNotNull('device_id');

		$user_id = $this->user_model->getUidByAccount($email, $password);
		if ($user_id == -1) {
			$this->create_error(-4);
		}
		$this->user_id = $user_id;
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

	public function connectFacebook_post() {
		$this->validate_authorization();
		$access_token = $this->c_getNotNull('access_token');

		$this->load->library('facebook_lib');
		$response = $this->facebook_lib->me($access_token);
		if (!$response['success']) {
			$this->create_error(-82);
		}

		$facebook_id = $response['data']['id'];
		$otherUser = $this->user_model->getUserByFacebookId($facebook_id);
		if ($otherUser != null && $otherUser['user_id'] != $this->user_id) {
			$this->create_error(-63);
		}

		$params = ['facebook_id' => $facebook_id];

		$this->load->library('contact_lib');
		$this->contact_lib->updateContact(CONTACT_TYPE_FACEBOOK, $facebook_id,  $this->user_id);

		// Try to get long-live token
		$tokenResponse = $this->facebook_lib->longLive($access_token);
		if ($tokenResponse['success']) {
			$params['fb_token'] = $tokenResponse['data']['token'];
			$params['fb_expired_at'] = $tokenResponse['data']['expired_at']; // time() + 60 * 86400; // 60 days
		} else {
			$params['fb_token'] = $access_token;
			$params['fb_expired_at'] = time() + 2 * 3600; // 2 hours
		}
		$this->user_model->update($params, $this->user_id);

		$this->create_success();
	}



	/**
	 * @SWG\Post(
	 *     path="/user/registerProfile",
	 *     summary="Register an account",
	 *     operationId="registerProfile",
	 *     tags={"Authorization"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="User Name",
	 *         in="formData",
	 *         name="user_name",
	 *         required=true,
	 *         type="string",
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Full Name",
	 *         in="formData",
	 *         name="full_name",
	 *         required=true,
	 *         type="string",
	 *     ),
	 *     @SWG\Parameter(
	 *         description="User email",
	 *         in="formData",
	 *         name="email",
	 *         required=true,
	 *         type="string",
	 *         default="email@example.com"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Password",
	 *         in="formData",
	 *         name="password",
	 *         required=true,
	 *         type="string",
	 *         default="123456"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Device ID",
	 *         in="formData",
	 *         name="device_id",
	 *         required=true,
	 *         type="string",
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Facebook access token",
	 *         in="formData",
	 *         name="access_token",
	 *         required=false,
	 *         type="string",
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Google ID",
	 *         in="formData",
	 *         name="google_id",
	 *         required=false,
	 *         type="string",
	 *     ),
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *     )
	 * )
	 */
	public function registerProfile_post() {
		$time = time();
		$email = $this->c_getEmail('email');
		$password = $this->c_getWithLength('password', 32, 6);
		$device_id = $this->c_getNotNull('device_id');
		$user_name = $this->c_getNotNull('user_name');
		$full_name = $this->c_getNotNull('full_name');
		$access_token = $this->post('access_token');
		$google_id = $this->post('google_id');

		if ($this->user_model->checkUserName($user_name)) {
			$this->create_error(-78);
		}

		if ($this->user_model->checkEmail($email)) {
			$this->create_error(-5);
		}

		$facebook_id = '';
		$params = [];
		if (!empty($access_token)) {
			$this->load->library('facebook_lib');
			$response = $this->facebook_lib->me($access_token);
			if (!$response['success']) {
				$this->create_error(-82);
			}

			$facebook_id = $response['data']['id'];
			$otherUser = $this->user_model->getUserByFacebookId($facebook_id);
			if ($otherUser != null) {
				$this->create_error(-63);
			}

			$params['facebook_id'] = $facebook_id;

			$tokenResponse = $this->facebook_lib->longLive($access_token);
			if ($tokenResponse['success']) {
				$params['fb_token'] = $tokenResponse['data']['token'];
				$params['fb_expired_at'] = $tokenResponse['data']['expired_at']; // time() + 60 * 86400; // 60 days
			} else {
				$params['fb_token'] = $access_token;
				$params['fb_expired_at'] = time() + 2 * 3600; // 2 hours
			}
		}
		if (!empty($google_id)) {
			if ($this->user_model->getUserByGoogleId($google_id) != null) {
				$this->create_error(-81);
			} else {
				$params['google_id'] = $google_id;
			}
		}

		$params['email'] = $email;
		$params['password'] = ($password != '' ? md5($password) : '');
		$params['joined'] = $time;
		$params['last_login'] = $time;
		$params['user_name'] = $user_name;
		$params['full_name'] = $full_name;

		$user_id = $this->user_model->insert($params);
		$this->user_id = $user_id;

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

		$this->load->library('contact_lib');
		$this->contact_lib->updateContact(CONTACT_TYPE_EMAIL, $email, $user_id);
		if (!empty($facebook_id)) {
			$this->contact_lib->updateContact(CONTACT_TYPE_FACEBOOK, $facebook_id, $user_id);
		}

		$emailParams['username'] = $full_name;
		$this->load->model("email_model");
		$this->email_model->welcome($email, $emailParams);

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
		$notiParams = ['user_id' => $this->user_id, 'product_id' => $product_id, 'story_name' => $product['name']];
		$this->notify_model->createNotifyToFollower($this->user_id, 4, $notiParams, 'default', false);
		$this->create_success(null);
	}

	public function removeWatch_post() {
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
		$this->user_model->removeWatchList($this->user_id, $product_id);
		// Remove notification
		$this->load->model('notify_model');
		$notiParams = ['user_id' => $this->user_id, 'product_id' => $product_id, 'story_name' => $product['name']];
		$this->notify_model->removeNotify(0, 4, $notiParams);

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
		
		$this->load->model('episode_model');
		$this->episode_model->addRecentlyWatched($this->user_id, $product_id);

		$episode_id = $this->post('episode_id') * 1;
		$time = $this->c_getNotNull('time');
		if ($episode_id != 0) {
			//update for episode
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
					$this->load->model('notify_model');
					if ($this->post('start_play') == 1) {
						$this->notify_model->createNotifyToFollower($this->user_id, 1, array('user_id' => $this->user_id, 'product_id' => $product_id, 'episode_id' => $episode_id), 'default', false);
					}
				}
			} else {
				if ($time != -1) {
					$product_id = $this->episode_model->getProdutID($episode['season_id']);
					$this->user_model->update(array('product_id' => $product_id), $this->user_id);
					$this->episode_model->addWatchEpisode(array('episode_id' => $episode_id, 'user_id' => $this->user_id, 'season_id' => $episode['season_id'], 'product_id' => $product_id, 'start_time' => $time, 'update_time' => time()));
					$this->load->model('notify_model');
					if ($this->post('start_play') == 1) {
						$this->notify_model->createNotifyToFollower($this->user_id, 1, array('user_id' => $this->user_id, 'product_id' => $product_id, 'episode_id' => $episode_id), 'default', false);
					}
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
			$this->notify_model->createNotifyToFollower($this->user_id, 12, array('user_id' => $this->user_id, 'uid_comment' => $follower_id), 'default', false);
		}
		$this->create_success(null);
	}

	public function addFollowAll_post() {
		$this->validate_authorization();
		$type = $this->post('type');
		if ($type == 1) {
			$friends = $this->user_model->getUnFollowContactFriends($this->user_id);
		} else {
			$friends = $this->user_model->getUnFollowFacebookFriends($this->user_id);
		}

		$this->load->model('notify_model');
		foreach ($friends as $friend) {
			$follower_id = $friend['user_id'];
			$this->user_model->addFollow(array('user_id' => $this->user_id, 'follower_id' => $follower_id, 'timestamp' => time()));
			$this->notify_model->createNotify($follower_id, 51, array('user_id' => $this->user_id), false);
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
				$this->notify_model->createNotifyToFollower($this->user_id, 2, array('user_id' => $this->user_id, 'episode_id' => $episode_id, 'season_id' => $episode['season_id'], 'product_id' => $product_id), 'default', false);
			} else {
				$this->notify_model->createNotifyToFollower($this->user_id, 3, array('user_id' => $this->user_id, 'episode_id' => $episode_id, 'season_id' => $episode['season_id'], 'product_id' => $product_id), 'default', false);
			}
		} else {
			if ($isCheck != $status) {
				$this->user_model->updateLike($episode_id, $this->user_id, $status);
				if ($status == 1) {
					$template = Notify_model::$templates[2];
					$this->notify_model->updateNotify($this->user_id, 3, array('user_id' => $this->user_id, 'episode_id' => $episode_id, 'season_id' => $episode['season_id'], 'product_id' => $product_id), array('type' => 2, 'content' => $template['formatted'], 'timestamp' => time()));
				} else {
					$template = Notify_model::$templates[3];
					$this->notify_model->updateNotify($this->user_id, 2, array('user_id' => $this->user_id, 'episode_id' => $episode_id, 'season_id' => $episode['season_id'], 'product_id' => $product_id), array('type' => 3, 'content' => $template['formatted'], 'timestamp' => time()));
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


	/**
	 * @SWG\Get(
	 *     path="/user/profile",
	 *     summary="Get user profile",
	 *     operationId="profile",
	 *     tags={"Authorization"},
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
	public function profile_get() {
		$this->validate_authorization();
		$profile = $this->__getUserProfile($this->user_id);
		$this->create_success(array('profile' => $profile));
	}

	public function edit_post() {
		$this->validate_authorization();

		$user = $this->user_model->get($this->user_id);
		if ($user == null) {
			$this->create_error(-9);
		}

		$email = $this->c_getEmail('email');
		$user_name = $this->c_getNotNull('user_name');
		$full_name = $this->c_getNotNull('full_name');
		$birthday = $this->c_getDate('birthday', false);
		$bio = $this->post('bio');

		$user = $this->user_model->getByEmail($email);
		if ($user != null && $user['user_id'] != $this->user_id) {
			$this->create_error(-78);
		}

		$user = $this->user_model->getByUsername($user_name);
		if ($user != null && $user['user_id'] != $this->user_id) {
			$this->create_error(-78);
		}

		$params = array();

		$params['email'] = $email;
		$params['last_login'] = time();
		$params['user_name'] = $user_name;
		$params['full_name'] = $full_name;
		$params['bio'] = $bio;
		$params['birthday'] = $birthday;

		$this->user_model->update($params, $this->user_id);

		$this->load->library('contact_lib');
		if ($email != $user['email']) {
			$this->contact_lib->updateContact(CONTACT_TYPE_EMAIL, $user['email'], 0);
			$this->contact_lib->updateContact(CONTACT_TYPE_EMAIL, $email, $this->user_id);
		}

		$profile = $this->__getUserProfile($this->user_id);
		$this->create_success(['profile' => $profile], 'Edit success');
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

		$old_avatar = $this->user_model->getAvatarUser($this->user_id);

		$this->user_model->update($params, $this->user_id);
		if (!empty($old_avatar) && file_exists($old_avatar)) {
			$this->file_model->removeFileAndThumb($old_avatar);
		}
		$profile = $this->__getUserProfile($this->user_id);
		$this->create_success(array('profile' => $profile), 'Edit success');

	}

	public function changePassword_post() {
		$this->validate_authorization();

		$user = $this->user_model->getObjectById($this->user_id);
		if ($user == null) {
			$this->create_error(-9);
		}

		$password = $this->c_getWithLength('password', 32, 6);
		$new_password = $this->c_getWithLength('new_password', 32, 6);
		if (md5($password) != $user['password']) {
			$this->create_error(-4, 'Sory, your current password is incorrect.');
		}
		$params = array();
		$params['password'] = md5($new_password);
		$this->user_model->update($params, $this->user_id);

		$profile = $this->__getUserProfile($this->user_id);
		$this->create_success(['profile' => $profile], 'Change password successfully');
	}

	/**
	 * @SWG\Post(
	 *     path="/user/forgotPassword",
	 *     summary="forgotPassword",
	 *     operationId="forgotPassword",
	 *     tags={"Authorization"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="User email",
	 *         in="formData",
	 *         name="email",
	 *         required=true,
	 *         type="string",
	 *         default="email@example.com"
	 *     ),
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *     )
	 * )
	 */
	public function forgotPassword_post() {
		$email = $this->c_getEmail("email");
		$user = $this->user_model->checkEmail($email);
		if (!$user) {
			$this->create_error(-9);
		}
		$time = time();
		$code = md5(md5($email . $time . '|mDyN2U') . $time);
		$base_64 = str_replace('=', '', base64_encode($user['user_id'] . '|' . $code));
		$params = array();
		$params['user_id'] = $user['user_id'];
		$params['code'] = $code;
		$params['created'] = $time;
		$this->user_model->insertCodeResetPassword($params);
		$params['url_code'] = root_domain() . '/reset-password?code=' . $base_64;
		$params['username'] = $user['full_name'];
		$this->load->model("email_model");
		$this->email_model->emailForgotPassword($email, $params);
		$this->create_success(null, 'Check email for get new password');
	}


	public function forgotPassword_get() {
		$email = 'cuongdoict@gmail.com';

		$time = time();
		$code = md5(md5($email . $time . '|mDyN2U') . $time);
		$base_64 = base64_encode(100 . '|' . $code);
		$params = array();
		$params['code'] = $code;
		$params['created'] = $time;
		$params['url_code'] = root_domain() . '/reset-password?code=' . $base_64;
		$params['username'] = 'Cuong DO';
		$this->load->model("email_model");
		$this->email_model->welcome($email, $params);
		$this->create_success(null, 'Check email for get new password');
	}

	// Add Series to Your Picks
	public function addPick_post() {
		$this->load->model('product_model');
		$product_id = $this->c_getNumberNotNull('product_id');
		$quote = $this->c_getNotNull('quote');
		$product = $this->product_model->get($product_id);
		if ($product == null) {
			$this->create_error(-17);
		}
		$pick = $this->user_model->getUserPick($this->user_id, $product_id);
		if ($pick != null) {
			$pick_id = $pick['pick_id'];
			$this->user_model->updatePick(['quote' => $quote], $pick_id);
		} else {
			$params = [
				'product_id' => $product_id,
				'user_id' => $this->user_id,
				'quote' => $quote,
				'created_at' => time(),
			];
			$pick_id = $this->user_model->insertPick($params);
			$this->load->model('notify_model');
			$notiParams = ['user_id' => $this->user_id, 'product_id' => $product_id, 'story_name' => $product['name']];
			$this->notify_model->createNotifyToFollower($this->user_id, 15, $notiParams, 'default', false);
		}
		$this->product_model->putProductUserReview($this->user_id, $product_id, 1);
		$this->create_success(['pick' => $this->user_model->getPick($pick_id)]);
	}

	// Edit Your Picks
	public function editPick_post($pick_id) {
		$quote = $this->c_getNotNull('quote');
		$pick = $this->user_model->getPick($pick_id);
		if ($pick == null) {
			$this->create_error(-1005);
		}
		if ($pick != null) {
			$this->user_model->updatePick(['quote' => $quote], $pick['pick_id']);
		}
		$this->create_success(['pick' => $this->user_model->getPick($pick_id)]);
	}

	public function removePick_post() {
		$pick_id = $this->c_getNotNull('pick_id');
		$pick = $this->user_model->getPick($pick_id);
		if ($pick == null) {
			$this->create_error(-1005);
		}
		$this->user_model->removePick($pick['pick_id']);
		$product = $this->product_model->get($pick['product_id']);
		if ($product != null) {
			$notiParams = ['user_id' => $this->user_id, 'product_id' => $pick['product_id'], 'story_name' => $product['name']];
			$this->notify_model->removeNotify(0, 15, $notiParams);
		}
		$this->create_success();
	}

	public function updateConfigs_post() {
		$params = [];
		$configs = $this->input->post();
		if (array_key_exists('picks_enabled', $configs)) {
			$params['picks_enabled'] = $configs['picks_enabled'] > 0 ? 1 : 0;
		}
		if (array_key_exists('continue_enabled', $configs)) {
			$params['continue_enabled'] = $configs['continue_enabled'] > 0 ? 1 : 0;
		}
		if (array_key_exists('watch_enabled', $configs)) {
			$params['watch_enabled'] = $configs['watch_enabled'] > 0 ? 1 : 0;
		}
		if (array_key_exists('thumbs_up_enabled', $configs)) {
			$params['thumbs_up_enabled'] = $configs['thumbs_up_enabled'] > 0 ? 1 : 0;
		}
		if (count($params) > 0) {
			$this->user_model->updateProfileConfigs($params, $this->user_id);
		}
		$this->create_success(['configs' => $this->user_model->getProfileConfigs($this->user_id)]);
	}

	public function configs_get() {
		$configs = $this->user_model->getProfileConfigs($this->user_id);
		$this->create_success(['configs' => $configs]);
	}

	public function report_post() {
		$user_id = $this->post('user_id');
		if ($this->user_model->getUserById($user_id) != null) {
			$this->user_model->insertReport($user_id, $this->user_id);
		}
		$this->create_success();
	}

	public function hidden_post() {
		$list_type = $this->post('list_type');
		$id = $this->post('id');
		$is_hidden = $this->post('is_hidden');

		if ($list_type == 1) {
			// your_picks
			$this->user_model->hiddenYourPick($id, $is_hidden);

		} else if ($list_type == 2) {
			// continue_watching
			$this->user_model->hiddenContinueWatching($id, $is_hidden);
		} else if ($list_type == 3) {
			// watch_list
			$this->user_model->hiddenWatchList($id, $is_hidden);
		} else if ($list_type == 4) {
			// thumbs_up
			$this->user_model->hiddenProductThumbsUp($id, $is_hidden);
		}

		$this->create_success();
	}

	/**
	 * @SWG\Post(
	 *     path="/user/subscription",
	 *     summary="Create a subscription",
	 *     operationId="subscription",
	 *     tags={"Subscription"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="Stripe Token",
	 *         in="formData",
	 *         name="stripe_token",
	 *         required=false,
	 *         type="string",
	 *     ),
	 *     security={
	 *       {"accessToken": {}}
	 *     },
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *     )
	 * )
	 */
	public function subscription_post() {
		$this->validate_authorization();

		$user = $this->user_model->get($this->user_id);
		if ($user == null) {
			$this->create_error(-9);
		}
		if (!empty($user['subscription_id']) && $user['current_period_end'] > time() && $user['canceled_at'] > time()) {
			$this->create_error(-100, 'You are in a subscription');
		}
		$token = $this->input->post('stripe_token');
		$this->load->library('stripe_lib');
		if (!empty($user['stripe_customer'])) {
			$stripeResponse = $this->stripe_lib->updateCustomerWithToken($user, $token);
		} else {
			$stripeResponse = $this->stripe_lib->createCustomerWithToken($user, $token);
		}

		if ($stripeResponse['success']) {
			$card = $stripeResponse['data'];
			$this->user_model->update(['stripe_customer' => $card->customer], $this->user_id);
		} else {
			$this->create_error(-100, $stripeResponse['error_message']);
		}
		$subscriptionResponse = $this->stripe_lib->createSubscription($card->customer);
		if (!$subscriptionResponse['success']) {
			$this->create_error(-100, $stripeResponse['error_message']);
		}
		$subscription = $subscriptionResponse['data'];
		$canceled_at = empty($subscription->canceled_at) ? 0 : ($subscription->cancel_at_period_end ? $subscription->cancel_at : $subscription->canceled_at);
		$this->user_model->update([
			'stripe_customer' => $card->customer,
			'subscription_id' => $subscription->id,
			'current_period_start' => $subscription->current_period_start,
			'current_period_end' => $subscription->current_period_end,
			'canceled_at' => $canceled_at,
		], $this->user_id);

		$profile = $this->__getUserProfile($this->user_id);
		$this->create_success(['profile' => $profile]);
	}

	public function welcome_get() {
		$email = 'cuongdoict@gmail.com';
		$params['username'] = 'Cuong Do';
		$this->load->model("email_model");
		$this->email_model->welcome($email, $params);
	}

	/**
	 * @SWG\Post(
	 *     path="/user/notification",
	 *     summary="Submit notification setting",
	 *     operationId="postNotificationSetting",
	 *     tags={"Account"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="New Followers",
	 *         in="formData",
	 *         name="new_followers",
	 *         required=false,
	 *         type="string",
	 *         enum={"1", "0"}
	 *     ),
	 *     @SWG\Parameter(
	 *         description="New Picks",
	 *         in="formData",
	 *         name="new_picks",
	 *         required=false,
	 *         type="string",
	 *         enum={"1", "0"}
	 *     ),
	 *     @SWG\Parameter(
	 *         description="New Adds to Watchlist",
	 *         in="formData",
	 *         name="new_watchlist",
	 *         required=false,
	 *         type="string",
	 *         enum={"1", "0"}
	 *     ),
	 *     @SWG\Parameter(
	 *         description="New Thumbs up",
	 *         in="formData",
	 *         name="new_thumbs_up",
	 *         required=false,
	 *         type="string",
	 *         enum={"1", "0"}
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Trending",
	 *         in="formData",
	 *         name="trending",
	 *         required=false,
	 *         type="string",
	 *         enum={"1", "0"}
	 *     ),
	 *     @SWG\Parameter(
	 *         description="New Stories",
	 *         in="formData",
	 *         name="new_stories",
	 *         required=false,
	 *         type="string",
	 *         enum={"1", "0"}
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Product Updates",
	 *         in="formData",
	 *         name="product_updates",
	 *         required=false,
	 *         type="string",
	 *         enum={"1", "0"}
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Comment Mentions",
	 *         in="formData",
	 *         name="comment_mentions",
	 *         required=false,
	 *         type="string",
	 *         enum={"1", "0"}
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Comment Likes",
	 *         in="formData",
	 *         name="comment_likes",
	 *         required=false,
	 *         type="string",
	 *         enum={"1", "0"}
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Comment Replies",
	 *         in="formData",
	 *         name="comment_replies",
	 *         required=false,
	 *         type="string",
	 *         enum={"1", "0"}
	 *     ),
	 *     security={
	 *       {"accessToken": {}}
	 *     },
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *     )
	 * )
	 */
	/**
	 * @SWG\Post(
	 *     path="/user/notification/following",
	 *     summary="Submit following notification setting",
	 *     operationId="followingNotificationSetting",
	 *     tags={"Account"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="New Followers",
	 *         in="formData",
	 *         name="new_followers",
	 *         required=true,
	 *         type="string",
	 *         enum={"1", "0"},
	 *         default="1"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="New Picks",
	 *         in="formData",
	 *         name="new_picks",
	 *         required=true,
	 *         type="string",
	 *         enum={"1", "0"},
	 *         default="1"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="New Adds to Watchlist",
	 *         in="formData",
	 *         name="new_watchlist",
	 *         required=true,
	 *         type="string",
	 *         enum={"1", "0"},
	 *         default="1"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="New Thumbs up",
	 *         in="formData",
	 *         name="new_thumbs_up",
	 *         required=true,
	 *         type="string",
	 *         enum={"1", "0"},
	 *         default="0"
	 *     ),
	 *     security={
	 *       {"accessToken": {}}
	 *     },
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *     )
	 * )
	 */

	/**
	 * @SWG\Post(
	 *     path="/user/notification/app",
	 *     summary="Submit app notification setting",
	 *     operationId="appNotificationSetting",
	 *     tags={"Account"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="Trending",
	 *         in="formData",
	 *         name="trending",
	 *         required=true,
	 *         type="string",
	 *         enum={"1", "0"},
	 *         default="1"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="New Stories",
	 *         in="formData",
	 *         name="new_stories",
	 *         required=true,
	 *         type="string",
	 *         enum={"1", "0"},
	 *         default="1"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Product Updates",
	 *         in="formData",
	 *         name="product_updates",
	 *         required=true,
	 *         type="string",
	 *         enum={"1", "0"},
	 *         default="1"
	 *     ),
	 *     security={
	 *       {"accessToken": {}}
	 *     },
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *     )
	 * )
	 */

	/**
	 * @SWG\Post(
	 *     path="/user/notification/comment",
	 *     summary="Submit comment notification setting",
	 *     operationId="commentNotificationSetting",
	 *     tags={"Account"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="Comment Mentions",
	 *         in="formData",
	 *         name="comment_mentions",
	 *         required=true,
	 *         type="string",
	 *         enum={"1", "0"},
	 *         default="1"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Comment Likes",
	 *         in="formData",
	 *         name="comment_likes",
	 *         required=true,
	 *         type="string",
	 *         enum={"1", "0"},
	 *         default="1"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Comment Replies",
	 *         in="formData",
	 *         name="comment_replies",
	 *         required=true,
	 *         type="string",
	 *         enum={"1", "0"},
	 *         default="0"
	 *     ),
	 *     security={
	 *       {"accessToken": {}}
	 *     },
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *     )
	 * )
	 */
	public function notification_post($group = '') {
		$this->validate_authorization();
		$settings = $this->user_model->getNotificationSetting($this->user_id);
		$data = $this->post();
		$params = [];
		foreach (NOTIFICATION_KEYS as $key) {
			if (isset($data[$key]) && is_numeric($data[$key])) {
				$params[$key] = $data[$key];
			}
		}
		if (count($params) > 0) {
			if ($settings == null) {
				$params['user_id'] = $this->user_id;
				$this->db->insert('user_notification_setting', $params);
			} else {
				$this->db->where('user_id', $this->user_id);
				$this->db->update('user_notification_setting', $params);
			}
		}

		$response = array();
		$response['notification'] = $this->user_model->getNotificationSetting($this->user_id);
		$this->create_success($response);
	}

	/**
	 * @SWG\Get(
	 *     path="/user/notification",
	 *     summary="Get notification setting",
	 *     operationId="getNotificationSetting",
	 *     tags={"Account"},
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
	public function notification_get() {
		$this->validate_authorization();
		$response['notification'] = $this->user_model->getNotificationSetting($this->user_id);
		$this->create_success($response);
	}
}
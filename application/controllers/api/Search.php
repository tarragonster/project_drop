<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class Search extends BR_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('product_model');
		$this->load->model('cast_model');
		$this->load->model('user_model');
	}

	public function get_get($type, $key = '') {
		if ($type == 1) {
			$products = $this->product_model->getProductByName($key);
			$this->create_success(array('products' => $products));
		} else {
			if ($type == 2) {
				$casts = $this->cast_model->getCastByName($key);;
				$this->create_success(array('casts' => $casts));
			} else {
				if ($type == 3) {
					$user_type = $this->get('user_type');
					$user_type = is_numeric($user_type) ? $user_type * 1 : -1;
					if ($this->user_id != null) {
						if (empty($key)) {
							$users = $this->user_model->getUsers($this->user_id, $user_type);
						} else {
							$users = $this->user_model->searchUser($key, $this->user_id);
						}

						$following = $this->user_model->getFollowing($this->user_id);
						foreach ($users as $key => $user) {
							$users[$key]['isFollow'] = '0';
							foreach ($following as $follow) {
								if ($user['user_id'] == $follow['follower_id']) {
									$users[$key]['is_follow'] = '1';
									break;
								}
							}
						}
					} else {
						$users = $this->user_model->searchUser($key, -1, $user_type);
						foreach ($users as $key => $user) {
							$users[$key]['is_follow'] = '0';
						}
					}
					$this->create_success(array('users' => $users));
				}
			}
		}
		$this->create_error(-1);
	}

}
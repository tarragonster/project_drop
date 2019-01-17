<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class Discover extends BR_Controller {
	public function __construct() {
		parent::__construct();

		$this->validate_authorization();
	}

	public function index_get() {

		$this->load->model('product_model');
		$featured_profiles = $this->user_model->getFeaturedProfiles();

		$following = $this->user_model->getFollowing($this->user_id);
		foreach ($featured_profiles as $key => $user) {
			$featured_profiles[$key]['isFollow'] = '0';
			foreach ($following as $follow) {
				if ($user['user_id'] == $follow['follower_id']) {
					$featured_profiles[$key]['is_follow'] = '1';
					break;
				}
			}
		}
		$response = ['featured_profiles' => $featured_profiles];

		$explore_previews = $this->product_model->getExplorePreviews();
		foreach ($explore_previews as $key => $product) {
			$explore_previews[$key]['number_like'] = $this->product_model->countProductLikes($product['product_id']);
			$explore_previews[$key]['num_watching'] = $this->product_model->countUserWatching($product['product_id']);
			$explore_previews[$key]['watchlist_added'] = $this->user_model->checkWatchList($this->user_id, $product['product_id']);
		}
		$response['explore_previews'] = $explore_previews;
		$this->create_success($response, 'Successfully');
	}

	public function featured_get() {
		$this->load->model('product_model');
		$featured_profiles = $this->user_model->getFeaturedProfiles();

		$following = $this->user_model->getFollowing($this->user_id);
		foreach ($featured_profiles as $key => $user) {
			$featured_profiles[$key]['isFollow'] = '0';
			foreach ($following as $follow) {
				if ($user['user_id'] == $follow['follower_id']) {
					$featured_profiles[$key]['is_follow'] = '1';
					break;
				}
			}
		}
		$response = ['featured_profiles' => $featured_profiles];

		$this->create_success($response, 'Successfully');

	}

	public function previews_get() {
		$this->load->model('product_model');

		$explore_previews = $this->product_model->getExplorePreviews();
		foreach ($explore_previews as $key => $product) {
			$explore_previews[$key]['number_like'] = $this->product_model->countProductLikes($product['product_id']);
			$explore_previews[$key]['num_watching'] = $this->product_model->countUserWatching($product['product_id']);
		}
		$response = ['explore_previews' => $explore_previews];
		$this->create_success($response, 'Successfully');
	}
}
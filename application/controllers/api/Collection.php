<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class Collection extends BR_Controller {
	const SUPPORTED_IOS_VERSION = '1.0.8';
	const SUPPORTED_AND_VERSION = '1.0.4';
	public function __construct() {
		parent::__construct();
		$this->load->model('collection_model');
		$this->load->model('product_model');
	}
	/**
	 * @SWG\Get(
	 *     path="/collection/list",
	 *     summary="Get collection list",
	 *     operationId="get-collections",
	 *     tags={"Collection"},
	 *     produces={"application/json"},
	 *     consumes={"application/json"},
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *     ),
	 *     security={
	 *       {"userAgents": {}, "accessToken": {}}
	 *     }
	 * )
	 */
	public function list_get() {
		$collections = $this->collection_model->getCollections();
		$selected_collections = [];
		if (is_array($collections)) {
			$this->load->model('episode_model');
			foreach ($collections as &$collection) {
				if ($collection['collection_type'] == COLLECTION_TYPE_CONTINUE_WATCHING) {
					$episode_products = $this->product_model->getContinueWatching($this->user_id);
					$s_products = [];
					if (is_array($episode_products)) {
						foreach ($episode_products as $key => $product) {
							$episode = $this->product_model->getEpisode($product['episode_id']);
							$episode['product_id'] = $product['product_id'];
							$episode['product_name'] = $product['name'];
							$episode['start_time'] = $product['start_time'];
							$products[$key]['episode'] = $episode;
						}
					}
					$collection['products'] = $s_products;
					$selected_collections[] = $collection;
				} else if ($collection['collection_type'] == COLLECTION_TYPE_TOP_PICKS) {
					$top_picks = $this->user_model->getFriendsTopPicks($this->user_id);
					$collection['products'] = $top_picks;
					$selected_collections[] = $collection;
				} else if ($collection['collection_type'] == COLLECTION_TYPE_SUGGESTED_USERS) {
					if (($this->device_type == DEVICE_TYPE_ANDROID && version_compare($this->app_version, '1.0.4') >= 0)
						|| ($this->device_type == DEVICE_TYPE_IOS && version_compare($this->app_version, '1.0.8') >= 0)) {
						$featured_profiles = $this->user_model->getFeaturedProfiles(0);

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
						$collection['users'] = $featured_profiles;
						$selected_collections[] = $collection;
					}
				} else if ($collection['collection_type'] == COLLECTION_TYPE_FRIEND_WATCHING) {
					if (($this->device_type == DEVICE_TYPE_ANDROID && version_compare($this->app_version, Collection::SUPPORTED_AND_VERSION) >= 0)
						|| ($this->device_type == DEVICE_TYPE_IOS && version_compare($this->app_version, Collection::SUPPORTED_IOS_VERSION) >= 0)) {
						$friends_watching = $this->product_model->getFriendsWatching($this->user_id, 0);
						foreach ($friends_watching as $key => $product) {
							$friends_watching[$key]['number_like'] = $this->product_model->countProductLikes($product['product_id']);
							$friends_watching[$key]['num_watching'] = $this->product_model->countUserWatching($product['product_id']);
							$friends_watching[$key]['watchlist_added'] = $this->user_model->checkWatchList($this->user_id, $product['product_id']) ? '1' : '0';
						}
						$friends_watching = $this->user_model->expand($friends_watching, 'follower_id', 'friend', 'user_id, user_name, full_name, avatar');
						$collection['products'] = $friends_watching;
						$selected_collections[] = $collection;
					}
				} else {
					$products = $this->product_model->getListProductByCollection($collection['collection_id'], -1, $collection['limit']);
					if ($this->user_id == null) {
						foreach ($products as $key => $row) {
							$products[$key]['start_time'] = 0;
							$first_episode = $this->product_model->getFirstEpisode($row['product_id']);
							$products[$key]['episode'] = $first_episode;
						}
					} else {
						foreach ($products as $key => $row) {
							$products[$key]['start_time'] = $this->product_model->getProductContinue($this->user_id, $row['product_id']);
							$first_episode = $this->product_model->getFirstEpisode($row['product_id']);
							$products[$key]['episode'] = $first_episode;
						}
					}
					$collection['products'] = $products;
					$selected_collections[] = $collection;
				}
			}
		}

		$this->create_success(['collections' => $selected_collections]);
	}

	/**
	 * @SWG\Get(
	 *     path="/collection/suggestedUsers",
	 *     summary="Get suggested users",
	 *     operationId="getSuggestedUsers",
	 *     tags={"Collection"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="Page",
	 *         in="query",
	 *         name="page",
	 *         required=true,
	 *         type="number",
	 *         format="int64",
	 *         default="0"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Number of items per page.",
	 *         in="query",
	 *         name="limit",
	 *         required=true,
	 *         type="number",
	 *         format="int64",
	 *         default="24"
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
	public function suggestedUsers_get() {
		$page = $this->get('page') * 1;
		$limit = $this->get('limit') * 1;
		if ($limit <= 0) {
			$limit = 24;
		}
		$featured_profiles = $this->user_model->getFeaturedProfiles($page, $limit);

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
		$collection['users'] = $featured_profiles;
		$this->create_success(['users' => $featured_profiles]);
	}
}
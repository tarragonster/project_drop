<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class Product extends BR_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('product_model');
	}

	/**
	 * @SWG\Get(
	 *     path="/product/{product_id}",
	 *     summary="Get product Detail",
	 *     operationId="getStoryDetail",
	 *     tags={"Story"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="Product ID",
	 *         in="path",
	 *         name="product_id",
	 *         required=true,
	 *         type="number",
	 *         format="int64"
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
	public function get_get($product_id = -1) {
		$product = $this->product_model->getProductDetail($product_id);
		if ($this->user_id == null) {
			$product['start_time'] = 0;
		} else {
			$product['review_info'] = $this->product_model->getProductUserReview($this->user_id, $product_id);
			$product['start_time'] = $this->product_model->getProductContinue($this->user_id, $product_id);
		}
		$product['musics'] = $this->product_model->getListMusic($product_id);
		$product['casts'] = $this->product_model->getListCast($product_id);
		$seasons = $this->product_model->getListSeason($product_id);
		$product['num_season'] = count($seasons);
		$this->load->model('user_model');
		$product['watchlist_added'] = $this->user_model->checkInWatchList($product_id, $this->user_id * 1) == null ? '0' : '1';
		$product['num_watching'] = $this->product_model->countUserWatching($product_id);
		$product['has_like'] = $this->product_model->getUserProductLike($this->user_id, $product_id) != null ? '1' : '0';
		$product['number_like'] = $this->product_model->countProductLikes($product_id);
		$this->load->model('season_model');
		foreach ($seasons as $key => $season) {
			$films = $this->season_model->getListEpisodes($season['season_id']);
			if ($this->user_id == null) {
				foreach ($films as $key1 => $film) {
					$films[$key1]['start_time'] = 0;
				}
			} else {
				$continues = $this->season_model->getListContinue($this->user_id, $season['season_id']);

				foreach ($films as $key1 => $film) {
					$films[$key1]['start_time'] = 0;
					foreach ($continues as $temp) {
						if ($temp['episode_id'] == $film['episode_id']) {
							$films[$key1]['start_time'] = $temp['start_time'];
							break;
						}
					}
				}
			}
			$seasons[$key]['films'] = $films;
		}
		$product['seasons'] = $seasons;
		$paywall_episode_ids = [];
		if (isset($product['paywall_episode'])) {
			if ($product['paywall_episode'] > 0) {
				$episodes = $this->product_model->getEpisodeSeasons($product_id);
				if ($episodes != null && count($episodes) > 0) {
					$index_paywall = -1;
					foreach ($episodes as $key => $e) {
						if ($e['episode_id'] == $product['paywall_episode']) {
							$index_paywall = $key;
							break;
						}
					}
					if ($index_paywall > -1) {
						for ($i = $index_paywall; $i < count($episodes); $i++) {
							$paywall_episode_ids[] = $episodes[$i]['episode_id'];
						}
					}
				}
			}
		}
		$product['paywall_episode_ids'] = $paywall_episode_ids;

		$watchers = $this->product_model->getProductWatchers($product_id);
		$reviews = $this->product_model->getProductReviews($product_id, 0, 24);

		$response = [
			'product' => $product,
			'watchers' => $watchers,
			'reviews' => $reviews
		];

		$this->create_success($response);
	}


	/**
	 * @SWG\Get(
	 *     path="/product/{product_id}/captions",
	 *     summary="Get Story Captions",
	 *     operationId="getStoryCaptions",
	 *     tags={"Story"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="Product ID",
	 *         in="path",
	 *         name="product_id",
	 *         required=true,
	 *         type="number",
	 *         format="int64"
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
	public function captions_get($product_id) {
		$product = $this->product_model->getProductDetail($product_id);
		if ($product == null) {
			$this->create_error(-77);
		}
		if (empty($product['jw_media_id'])) {
			$this->create_error(-85);
		}
		$this->load->library('jw_lib');
		$captions = $this->jw_lib->getVideoCaptions($product['jw_media_id']);
		$this->create_success(['captions' => $captions]);
	}
	/**
	 * @SWG\Get(
	 *     path="/product/episode/{episode_id}",
	 *     summary="Get Episode detail",
	 *     operationId="getEpisodeDetail",
	 *     tags={"Episode"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="Episode ID",
	 *         in="path",
	 *         name="episode_id",
	 *         required=true,
	 *         type="number",
	 *         format="int64",
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
	public function episode_get($episode_id) {
		$this->load->model('episode_model');
		$episode = $this->episode_model->getEpisode($episode_id);
		if (!$episode) {
			$this->create_error(-77);
		}
//		if ($this->user_id) {
//			$productWatch = $this->episode_model->getWatchProduct($this->user_id, $episode['product_id']);
//			if ($productWatch == null || $productWatch['episode_id'] != $episode_id) {
//				$this->episode_model->updateOrAddUserWatch($this->user_id, $episode['product_id'], $episode_id, '0.001');
//			}
//		}
		$episode = $this->loadEpisode($episode);
		$this->create_success(array('episode' => $episode));
	}

	/**
	 * @SWG\Get(
	 *     path="/product/{product_id}/numWatching",
	 *     summary="Get Number Watching",
	 *     operationId="getNumWatching",
	 *     tags={"Story"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="Product ID",
	 *         in="path",
	 *         name="product_id",
	 *         required=true,
	 *         type="number",
	 *         format="int64",
	 *         default="34"
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
	public function numWatching_get($product_id) {
		$watching = $this->product_model->countUserWatching($product_id);

		$watchers = $this->product_model->getProductWatchers($product_id);

		$response = [
			'num_watching' => $watching,
			'watchers' => $watchers
		];

		$this->create_success($response);
	}

	/**
	 * @SWG\Get(
	 *     path="/product/nextEpisode/{episode_id}",
	 *     summary="Get Next Episode",
	 *     operationId="getNextEpisode",
	 *     tags={"Episode"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="Episode ID",
	 *         in="path",
	 *         name="episode_id",
	 *         required=true,
	 *         type="number",
	 *         format="int64"
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
	public function nextEpisode_get($episode_id) {
		$this->load->model('episode_model');
		$episodeOld = $this->episode_model->checkEpisode($episode_id);
		if (!$episodeOld) {
			$this->create_error(-77);
		}
		$episode = $this->episode_model->getNextEpisode($episodeOld['position'], $episodeOld['season_id']);
		if (!$episode) {
			$this->create_success(array('episode' => null));
		}
		$episode = $this->loadEpisode($episode);
		$this->create_success(array('episode' => $episode));
	}

	/**
	 * @SWG\Get(
	 *     path="/product/watching/{product_id}",
	 *     summary="Get Watching Users",
	 *     operationId="getWatchingUsers",
	 *     tags={"Story"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="Product ID",
	 *         in="path",
	 *         name="product_id",
	 *         required=true,
	 *         type="number",
	 *         format="int64"
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
	public function watching_get($product_id) {
		if ($this->user_id != null) {
			$watching = $this->product_model->getProductWatching($product_id, $this->user_id);
			$this->load->model('user_model');
			$follow_user = $this->user_model->getFollowing($this->user_id);
			foreach ($watching as $key => $user) {
				$watching[$key]['isFollow'] = '0';
				foreach ($follow_user as $follow) {
					if ($user['user_id'] == $follow['follower_id']) {
						$watching[$key]['is_follow'] = '1';
						break;
					}
				}
			}
		} else {
			$watching = $this->product_model->getProductWatching($product_id);
			foreach ($watching as $key => $follow) {
				$watching[$key]['is_follow'] = 0;
			}
		}
		$this->create_success(array('watching' => $watching));
	}

	public function loadEpisode($episode) {
		$this->load->model('comment_model');
		$episode['num_like'] = $this->episode_model->countLike($episode['episode_id'], 1);
		$episode['num_dislike'] = $this->episode_model->countLike($episode['episode_id'], 0);
		$comments = $this->comment_model->getBlockComments($episode['episode_id'], 1, 0);
		$episode['num_comment'] = $this->comment_model->countBlockComments($episode['episode_id']) + $this->comment_model->countAllBlockReplies($episode['episode_id']);

		if ($this->user_id != null) {
			$episode['review_info'] = $this->product_model->getProductUserReview($this->user_id, $episode['product_id']);
			$episode['has_like'] = $this->episode_model->hasLikeEpisode($episode['episode_id'], $this->user_id, 1);
			$episode['has_dislike'] = $this->episode_model->hasLikeEpisode($episode['episode_id'], $this->user_id, 0);
			foreach ($comments as $key => $comment) {
				$replies = $this->comment_model->getCommentReplies($comment['comment_id']);
				foreach ($replies as $t => $rep) {
					$replies[$t]['has_like'] = $this->comment_model->hasLikeReplies($rep['replies_id'], $this->user_id);
				}
				$comments[$key]['replies'] = $replies;
				$comments[$key]['has_like'] = $this->comment_model->hasLikeComment($comment['comment_id'], $this->user_id);
			}
			$watch = $this->episode_model->getWatchEpisode($this->user_id, $episode['episode_id']);
			$episode['start_time'] = $watch != null ? $watch['start_time'] : 0;
		} else {
			$episode['has_like'] = 0;
			$episode['has_dislike'] = 0;
			$episode['start_time'] = 0;
			foreach ($comments as $key => $comment) {
				$replies = $this->comment_model->getCommentReplies($comment['comment_id']);
				foreach ($replies as $t => $rep) {
					$replies[$t]['has_like'] = 0;
				}
				$comments[$key]['replies'] = $replies;
				$comments[$key]['has_like'] = 0;
			}
		}

		$episode['need_open_paywall'] = 0;
		$episode['user_has_watched_episode'] = 0;
		if ($this->user_id > 0 && $this->episode_model->getUserWatchedEpisode($this->user_id, $episode['episode_id'])) {
			$episode['user_has_watched_episode'] = 1;
		}
		if ($episode['user_has_watched_episode'] != 1 && isset($episode['product_paywall_episode'])) {
			if ($episode['product_paywall_episode'] > 0) {
				$episodes = $this->product_model->getEpisodeSeasons($episode['product_id']);
				if ($episodes != null && count($episodes) > 0) {
					$index_current = -1;
					$index_paywall = -1;
					foreach ($episodes as $key => $e) {
						if ($e['episode_id'] == $episode['product_paywall_episode']) {
							$index_paywall = $key;
						}
						if ($e['episode_id'] == $episode['episode_id']) {
							$index_current = $key;
						}
					}
					$episode['need_open_paywall'] = $index_current >= $index_paywall ? 1 : 0;
				}
			}
			unset($episode['product_paywall_episode']);
		}
		$episode['comments'] = $comments;
		if (!empty($episode['jw_media_id'])) {
			$this->load->library('jw_lib');
			$captions = $this->jw_lib->getVideoCaptions($episode['jw_media_id']);
			$episode['captions'] = $captions;
		}
		return $episode;
	}

	/**
	 * @SWG\Get(
	 *     path="/recentlyWatched",
	 *     summary="Get Watching Users",
	 *     operationId="getRecentlyWatched",
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
	public function recentlyWatched_get() {
		$this->validate_authorization();
		$recently = $this->product_model->getRecentlyWatched($this->user_id);
//		if (is_array($recently)) {
//			foreach ($recently as &$product) {
//				$episode = $this->product_model->getEpisode($product['episode_id']);
//				$episode['product_id'] = $product['product_id'];
//				$episode['product_name'] = $product['name'];
//				$episode['start_time'] = $product['start_time'];
//				$product['episode'] = $episode;
//			}
//		}
		$this->create_success(['recently' => $recently]);
	}

	/**
	 * @SWG\Post(
	 *     path="/product/like",
	 *     summary="Update like story status",
	 *     operationId="updateLikeStoryStatus",
	 *     tags={"Story"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="Product ID",
	 *         in="formData",
	 *         name="product_id",
	 *         required=true,
	 *         type="number",
	 *         format="int64"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Status",
	 *         in="formData",
	 *         name="status",
	 *         required=true,
	 *         type="number",
	 *         format="int64",
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
	public function like_post() {
		$this->validate_authorization();
		$product_id = $this->c_getNotNull('product_id');
		$status = $this->c_getNotNull('status');

		$product = $this->product_model->get($product_id);
		if ($product == null) {
			$this->create_error(-17);
		}
		$userLike = $this->product_model->getUserProductLike($this->user_id, $product_id);

		$this->load->model('notify_model');
		$notifyParams = ['user_id' => $this->user_id, 'product_id' => $product_id, 'story_name' => $product['name']];
		if ($userLike) {
			if ($status == 0) {
				$this->product_model->removeProductLike($this->user_id, $product_id);
				$this->notify_model->removeNotify(0, 13, $notifyParams);
			}
		} else {
			if ($status == 1) {
				$this->product_model->addProductLike($this->user_id, $product_id);
				$this->notify_model->createNotifyToFollower($this->user_id, 13, $notifyParams, 'default', true);
			}
		}
		$response = [
			'number_like' => $this->product_model->countProductLikes($product_id)
		];
		$this->create_success($response);
	}

	/**
	 * @SWG\Post(
	 *     path="/product/reviewStatus/{product_id}",
	 *     summary="Save review status",
	 *     operationId="productReviewStatus",
	 *     tags={"Story"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="Product ID",
	 *         in="path",
	 *         name="product_id",
	 *         required=true,
	 *         type="number",
	 *         format="int64",
	 *         default="1"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Has Reviewed",
	 *         in="formData",
	 *         name="has_reviewed",
	 *         required=true,
	 *         type="number",
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
	public function reviewStatus_post($product_id) {
		$this->validate_authorization();
		$product = $this->product_model->get($product_id);
		$has_reviewed = $this->c_getNumberNotNull('has_reviewed');
		if ($product == null) {
			$this->create_error(-77);
		}
		$this->product_model->putProductUserReview($this->user_id, $product_id, $has_reviewed);
		$this->create_success();
	}

	/**
	 * @SWG\Post(
	 *     path="/product/{product_id}/share",
	 *     summary="Share story with 10 block friends",
	 *     operationId="share-story",
	 *     tags={"Story"},
	 *     produces={"application/json"},
	 *     consumes={"application/json"},
	 *     @SWG\Parameter(
	 *         description="Product ID",
	 *         in="path",
	 *         name="product_id",
	 *         required=true,
	 *         type="number",
	 *         format="int64",
	 *     ),
	 *     @SWG\Parameter(
	 *          name="body",
	 *          in="body",
	 *          description="Sending message",
	 *          required=true,
	 *          @SWG\Schema(
	 *              @SWG\Property(
	 *                  property="message",
	 *                  type="string",
	 *              ),
	 *              @SWG\Property(
	 *                  property="user_ids",
	 *                  type="array",
	 *                  @SWG\Items (
	 *                      type="integer",
	 *                      format="int64"
	 *                  )
	 *              )
	 *          )
	 *     ),
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *     ),
	 *     security={
	 *       {"accessToken": {}}
	 *     }
	 * )
	 */
	public function share_post($product_id) {
		$product = $this->product_model->get($product_id);
		if ($product == null) {
			$this->create_error(-17);
		}
		$message = $this->c_getNotNull('message');
		$friend_ids = $this->post('user_ids');
		if (!is_array($friend_ids)) {
			$this->create_error(-6, 'Please select your friend');
		}
		$checked_ids = [];
		foreach ($friend_ids as $friend_id) {
			if (is_numeric($friend_id)) {
				$checked_ids[]= $friend_id;
			}
		}

		if (count($checked_ids) == 0) {
			$this->create_error(-6, 'Please select your friend');
		}
		$users = $this->user_model->getUserListByIds($this->user_id, $checked_ids);
		if (count($users) > 0) {
			$this->product_model->insertShared([
				'user_id' => $this->user_id,
				'story_id' => $product_id,
				'friends' => count($users),
				'shared_at' => time(),
			]);
			$this->load->model('notify_model');
			$meta = [
				'user_id' => $this->user_id,
				'product_id' => $product_id,
				'message' => $message,
				'story_name' => $product['name'],
			];
			$this->notify_model->createNotifyMany($users, 57, $meta);
		}
		$this->create_success();
	}

	/**
	 * @SWG\Post(
	 *     path="/product/{product_id}/localShare",
	 *     summary="Share story with 10 block friends",
	 *     operationId="share-story-local",
	 *     tags={"Story"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="Product ID",
	 *         in="path",
	 *         name="product_id",
	 *         required=true,
	 *         type="number",
	 *         format="int64",
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Episode ID",
	 *         in="formData",
	 *         name="episode_id",
	 *         required=false,
	 *         type="number",
	 *         format="int64"
	 *     ),
	 *     @SWG\Parameter(
	 *         description="Number of friends have been shared.",
	 *         in="formData",
	 *         name="num_of_shared",
	 *         required=true,
	 *         type="number",
	 *         format="int64",
	 *         default=1,
	 *     ),
	 *     @SWG\Response(
	 *         response=200,
	 *         description="Successful operation",
	 *     ),
	 *     security={
	 *       {"accessToken": {}}
	 *     }
	 * )
	 */
	public function localShare_post($product_id) {
		$product = $this->product_model->get($product_id);
		if ($product == null) {
			$this->create_error(-17);
		}
		$num_of_shared = $this->c_getNumberNotNull('num_of_shared');
		$this->product_model->insertShared([
			'user_id' => $this->user_id,
			'story_id' => $product_id,
			'friends' => $num_of_shared,
			'shared_at' => time(),
		]);
		$this->create_success();
	}

	/**
	 * @SWG\Get(
	 *     path="/product/{product_id}/reviews",
	 *     summary="Get Story Reviews",
	 *     operationId="getStoryReviews",
	 *     tags={"Story"},
	 *     produces={"application/json"},
	 *     @SWG\Parameter(
	 *         description="Product ID",
	 *         in="path",
	 *         name="product_id",
	 *         required=true,
	 *         type="number",
	 *         format="int64"
	 *     ),
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
	public function reviews_get($product_id) {
		$page = $this->get('page') * 1;
		$page_size = $this->get('limit') * 1;
		if ($page_size <= 0) {
			$page_size = 24;
		}
		$reviews = $this->product_model->getProductReviews($product_id, $page, $page_size);
		$this->create_success(['reviews' => $reviews]);
	}

}
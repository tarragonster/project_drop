<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class Product extends BR_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('product_model');
	}

	public function get_get($product_id = -1) {
		$product = $this->product_model->getProductDetail($product_id);
		if ($this->user_id == null) {
			$product['start_time'] = 0;
		} else {
			$product['start_time'] = $this->product_model->getProductContinue($this->user_id, $product_id);
		}
		$product['musics'] = $this->product_model->getListMusic($product_id);
		$product['casts'] = $this->product_model->getListCast($product_id);
		$seasons = $this->product_model->getListSeason($product_id);
		$product['num_season'] = count($seasons);
		$this->load->model('user_model');
		$product['watchlist_added'] = $this->user_model->checkInWatchList($product_id, $this->user_id * 1) == null ? '0' : '1';
		$product['num_watching'] = $this->product_model->countUserWatching($product_id);
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

		$response = [
			'product' => $product,
			'watchers' => $watchers
		];

		$this->create_success($response);
	}

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

	public function episode_get($episode_id) {
		$this->load->model('episode_model');
		$episode = $this->episode_model->getEpisode($episode_id);
		if (!$episode) {
			$this->create_error(-77);
		}
		if ($this->user_id) {
			$productWatch = $this->episode_model->getWatchProduct($this->user_id, $episode['product_id']);
			if ($productWatch == null || $productWatch['episode_id'] != $episode_id) {
				$this->episode_model->updateOrAddUserWatch($this->user_id, $episode['product_id'], $episode_id, '0.001');
			}
		}
		$episode = $this->loadEpisode($episode);
		$this->create_success(array('episode' => $episode));
	}

	public function numWatching_get($product_id) {
		$watching = $this->product_model->countUserWatching($product_id);

		$watchers = $this->product_model->getProductWatchers($product_id);

		$response = [
			'num_watching' => $watching,
			'watchers' => $watchers
		];

		$this->create_success($response);
	}

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
		$episode['num_like'] = $this->episode_model->countLike($episode['episode_id'], 1);
		$episode['num_dislike'] = $this->episode_model->countLike($episode['episode_id'], 0);
		$comments = $this->episode_model->getComments($episode['episode_id'], 1, 0);
		$episode['num_comment'] = $this->episode_model->countComment($episode['episode_id']) + $this->episode_model->countAllSubComment($episode['episode_id']);

		if ($this->user_id != null) {
			$episode['has_like'] = $this->episode_model->hasLikeEpisode($episode['episode_id'], $this->user_id, 1);
			$episode['has_dislike'] = $this->episode_model->hasLikeEpisode($episode['episode_id'], $this->user_id, 0);
			foreach ($comments as $key => $comment) {
				$replies = $this->episode_model->getReplies($comment['comment_id']);
				foreach ($replies as $t => $rep) {
					$replies[$t]['has_like'] = $this->episode_model->hasLikeReplies($rep['replies_id'], $this->user_id);
				}
				$comments[$key]['replies'] = $replies;
				$comments[$key]['has_like'] = $this->episode_model->hasLikeComment($comment['comment_id'], $this->user_id);
			}
			$watch = $this->episode_model->getWatchEpisode($this->user_id, $episode['episode_id']);
			$episode['start_time'] = $watch != null ? $watch['start_time'] : 0;
		} else {
			$episode['has_like'] = 0;
			$episode['has_dislike'] = 0;
			$episode['start_time'] = 0;
			foreach ($comments as $key => $comment) {
				$replies = $this->episode_model->getReplies($comment['comment_id']);
				foreach ($replies as $t => $rep) {
					$replies[$t]['has_like'] = 0;
				}
				$comments[$key]['replies'] = $replies;
				$comments[$key]['has_like'] = 0;
			}
		}

		$episode['need_open_paywall'] = 0;
		if (isset($episode['product_paywall_episode'])) {
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

	public function recentlyWatched_get() {
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



	public function like_post() {
		$this->validate_authorization();
		$product_id = $this->c_getNotNull('product_id');
		$status = $this->c_getNotNull('status');

		$product = $this->product_model->get($product_id);
		if ($product == null) {
			$this->create_error(-17);
		}
		$userLike = $this->product_model->getUserProductLike($this->user_id, $product_id);
		if ($userLike) {
			if ($status == 0) {
				$this->product_model->removeProductLike($this->user_id, $product_id);
			}
		} else {
			if ($status == 1) {
				$this->product_model->addProductLike($this->user_id, $product_id);
			}
		}
		$response = [
			'number_like' => $this->product_model->countProductLikes($product_id)
		];
		$this->create_success($response);
	}

}
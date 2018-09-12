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
		$this->create_success(array('product' => $product));
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
		$this->create_success(array('num_watching' => $watching));
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
					$replies[$t]['num_like'] = $this->episode_model->countRepliesLike($rep['replies_id']);
					$replies[$t]['has_like'] = $this->episode_model->hasLikeReplies($rep['replies_id'], $this->user_id);
				}
				$comments[$key]['num_like'] = $this->episode_model->countCommentLike($comment['comment_id']);
				$comments[$key]['replies'] = $replies;
				$comments[$key]['has_like'] = $this->episode_model->hasLikeComment($comment['comment_id'], $this->user_id);
			}
			$watch = $this->episode_model->getWatchEpisode($this->user_id, $episode['episode_id']);
			$episode['start_time'] = $watch != null ? $watch['start_time'] : 0;
		} else {
			$episode['has_like'] = 0;
			$episode['has_dislike'] = 0;
			foreach ($comments as $key => $comment) {
				$replies = $this->episode_model->getReplies($comment['comment_id']);
				foreach ($replies as $t => $rep) {
					$replies[$t]['num_like'] = $this->episode_model->countRepliesLike($rep['replies_id']);
					$replies[$t]['has_like'] = 0;
				}
				$comments[$key]['num_like'] = $this->episode_model->countCommentLike($comment['comment_id']);
				$comments[$key]['replies'] = $replies;
				$comments[$key]['has_like'] = 0;
			}
		}
		$episode['comments'] = $comments;
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

}
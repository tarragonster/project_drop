<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class Comment extends BR_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('comment_model');
		$this->load->model('user_model');
	}

	public function get_get($episode_id, $type = 1, $page = -1) {
		$this->load->model('episode_model');
		$episode = $this->episode_model->checkEpisode($episode_id);
		if (!$episode) {
			$this->create_error(-77);
		}
		$comments = $this->episode_model->getComments($episode_id, $type, $page);
		if ($this->user_id != null) {
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
		} else {
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
		$this->create_success(array('comments' => $comments));
	}

	public function add_post() {
		if (!$this->user_model->checkUid($this->user_id)) {
			$this->create_error(-10);
		}
		$episode_id = $this->post('episode_id') * 1;
		$comment_id = $this->post('comment_id') * 1;
		$content = $this->c_getNotNull('content');
		$this->load->model('episode_model');
		$this->load->model('notify_model');
		$this->notify_model = new Notify_model();
		if ($episode_id != 0) {
			$episode = $this->episode_model->checkEpisode($episode_id);
			if (!$episode) {
				$this->create_error(-77);
			}
			$product_id = $this->episode_model->getProdutID($episode['season_id']);
			$uid_comment = $this->user_id;

			$commentParams = [
				'episode_id' => $episode_id,
				'user_id' => $this->user_id,
				'content' => $content,
				'timestamp' => time()
			];
			$comment_id = $this->comment_model->insert($commentParams);
			$comment = $this->comment_model->getCommentById($comment_id);

			// Push notification to followers of the user
			$meta = [
				'user_id' => $this->user_id,
				'episode_id' => $episode_id,
				'season_id' => $episode['season_id'],
				'product_id' => $product_id
			];
			$this->notify_model->createNotifyToFollower($this->user_id, 6, $meta, 'default', false);
		} else {
			if ($comment_id == 0) {
				$this->create_error(-1000);
			}
			$check = $this->comment_model->checkComment($comment_id);
			if ($check == null) {
				$this->create_error(-14);
			}
			$episode = $this->episode_model->checkEpisode($check['episode_id']);
			$product_id = $this->episode_model->getProdutID($episode['season_id']);
			$episode_id = $check['episode_id'];
			$uid_comment = $check['user_id'];

			$replyParams = [
				'comment_id' => $comment_id,
				'user_id' => $this->user_id,
				'content' => $content,
				'timestamp' => time()
			];
			$replies_id = $this->comment_model->insertReplies($replyParams);
			$comment = $this->comment_model->getRepliesById($replies_id);

			// Push notification to owner of comment
			$meta = [
				'user_id' => $this->user_id,
				'episode_id' => $check['episode_id'],
				'season_id' => $episode['season_id'],
				'product_id' => $product_id,
				'comment_id' => $comment_id,
				'replies_id' => $replies_id
			];
			$this->notify_model->createNotify($check['user_id'], 54, $meta);
			$meta = [
				'user_id' => $this->user_id,
				'uid_comment' => $check['user_id'],
				'episode_id' => $check['episode_id'],
				'season_id' => $episode['season_id'],
				'product_id' => $product_id,
				'comment_id' => $comment_id,
				'replies_id' => $replies_id
			];
			$this->notify_model->createNotifyToFollower($this->user_id, 10, $meta, 'default', false);
		}
		$users = $this->__checkMentioned($content, $this->user_id);
		if (count($users) > 0) {
			$this->load->model('notify_model');
			// notify to user has been mentioned
			$meta = [
				'user_id' => $this->user_id,
				'episode_id' => $episode_id,
				'uid_comment' => $uid_comment,
				'product_id' => $product_id
			];
			$this->notify_model->createNotifyMany($users, 52, $meta);

			// notify to following user mentioning
			$meta = [
				'user_id' => $this->user_id,
				'episode_id' => $episode_id,
				'product_id' => $product_id
			];
			foreach ($users as $user) {
				$meta['uid_comment'] = $user['user_id'];
				$this->notify_model->createNotifyToFollower($this->user_id, 11, $meta, 'default', false);
			}
		}
		$comment['num_like'] = 0;
		$comment['has_like'] = 0;
		$this->create_success(array('comment' => $comment));
	}

	public function like_post() {
		if (!$this->user_model->checkUid($this->user_id)) {
			$this->create_error(-10);
		}

		$comment_id = $this->post('comment_id') * 1;
		$replies_id = $this->post('replies_id') * 1;

		$this->load->model('episode_model');
		$this->load->model('notify_model');
		$data = array();
		if ($comment_id != 0) {
			$comment = $this->comment_model->checkComment($comment_id);
			if ($comment == null) {
				$this->create_error(-14);
			}
			$hasLikeComment = $this->comment_model->checkLikeComment($this->user_id, $comment_id);
			$episode = $this->episode_model->checkEpisode($comment['episode_id']);
			$product_id = $this->episode_model->getProdutID($episode['season_id']);
			if (!$hasLikeComment) {
				$this->comment_model->insertCommentLike(['comment_id' => $comment_id, 'user_id' => $this->user_id]);
				if ($this->user_id != $comment['user_id']) {
					$meta = [
						'user_id' => $this->user_id,
						'episode_id' => $comment['episode_id'],
						'season_id' => $episode['season_id'],
						'product_id' => $product_id
					];
					$this->notify_model->createNotify($comment['user_id'], 53, $meta);

					$meta = [
						'user_id' => $this->user_id,
						'episode_id' => $comment['episode_id'],
						'season_id' => $episode['season_id'],
						'product_id' => $product_id,
						'uid_comment' => $comment['user_id'],
						'comment_id' => $comment_id
					];
					$this->notify_model->createNotifyToFollower($this->user_id, 9, $meta, 'default', false);
				}
			} else {
				$this->comment_model->removeCommentLike($this->user_id, $comment_id);
				$meta = [
					'user_id' => $this->user_id,
					'episode_id' => $comment['episode_id'],
					'season_id' => $episode['season_id'],
					'product_id' => $product_id,
					'uid_comment' => $comment['user_id'],
					'comment_id' => $comment_id
				];
				$this->notify_model->removeNotify($this->user_id, 9, $meta);
			}
			$data['num_like'] = $this->episode_model->countCommentLike($comment_id);
			$data['has_like'] = $this->episode_model->hasLikeComment($comment_id, $this->user_id);
		} else {
			if ($replies_id == 0) {
				$this->create_error(-1000);
			}
			$replies = $this->comment_model->checkRepliesById($replies_id);
			$comment = $this->comment_model->checkComment($replies['comment_id']);

			$hasLikeReply = $this->comment_model->checkLikeReplies($this->user_id, $replies_id);
			$episode = $this->episode_model->checkEpisode($comment['episode_id']);
			$product_id = $this->episode_model->getProdutID($episode['season_id']);
			if (!$hasLikeReply) {
				$this->comment_model->insertRepliesLike(array('replies_id' => $replies_id, 'user_id' => $this->user_id));
				if ($this->user_id != $replies['user_id']) {
					$meta = [
						'user_id' => $this->user_id,
						'episode_id' => $comment['episode_id'],
						'season_id' => $episode['season_id'],
						'product_id' => $product_id
					];
					$this->notify_model->createNotify($replies['user_id'], 53, $meta);
				}
			} else {
				$this->comment_model->removeRepliesLike($this->user_id, $replies_id);
				$meta = [
					'user_id' => $this->user_id,
					'episode_id' => $comment['episode_id'],
					'season_id' => $episode['season_id'],
					'product_id' => $product_id
				];
				$this->notify_model->removeNotify($replies['user_id'], 53, $meta);
			}

			$data['num_like'] = $this->episode_model->countRepliesLike($replies_id);
			$data['has_like'] = $this->episode_model->hasLikeReplies($replies_id, $this->user_id);
		}
		$this->create_success(array('likes' => $data));
	}

	public function remove_post() {
		if (!$this->user_model->checkUid($this->user_id)) {
			$this->create_error(-10);
		}
		$comment_id = $this->c_getNotNull('comment_id');
		$this->user_model->deleteComment($comment_id);
		$this->create_success(null);
	}

	public function update_post() {
		if (!$this->user_model->checkUid($this->user_id)) {
			$this->create_error(-10);
		}
		$comment_id = $this->c_getNotNull('comment_id');
		$content = $this->c_getNotNull('content');
		$this->comment_model->update($comment_id, array('content' => $content, 'timestamp' => time()));
		$this->create_success(null);
	}

	public function updateComment($comment, $type = 1) {
		if ($type == 2) {
			$comment['num_like'] = $this->episode_model->countRepliesLike($comment['replies_id']);
			$comment['has_like'] = $this->episode_model->hasLikeReplies($comment['replies_id'], $this->user_id);
		} else {
			$comment['num_like'] = $this->episode_model->countCommentLike($comment['comment_id']);
			$comment['has_like'] = $this->episode_model->hasLikeComment($comment['comment_id'], $this->user_id);
		}
		return $comment;
	}

	public function report_post() {
		$comment_id = $this->post('comment_id');
		if ($this->comment_model->getCommentById($comment_id) != null) {
			$this->comment_model->insertReport($comment_id, $this->user_id);
		}

		$this->create_success();
	}
}
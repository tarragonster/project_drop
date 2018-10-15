<?php

require_once APPPATH . '/core/BaseModel.php';

class Comment_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'episode_comment';
		$this->id_name = 'comment_id';
	}

	public function getCommentForAdmin($episode_id) {
		$this->db->select('c.comment_id, c.user_id, c.content, c.timestamp, u.user_name, u.full_name, u.avatar, u.user_id');
		$this->db->from('episode_comment c');
		$this->db->join('user u', 'u.user_id = c.user_id');
		$this->db->where('c.episode_id', $episode_id);
		$this->db->order_by('c.comment_id', 'desc');
		$query = $this->db->get();
		return $query->result_array('array');
	}

	public function getRepliesForAdmin($comment_id) {
		$this->db->select('r.replies_id, r.user_id, r.content, r.timestamp, u.user_name, u.full_name, u.avatar, u.user_id');
		$this->db->from('episode_replies r');
		$this->db->join('user u', 'u.user_id = r.user_id');
		$this->db->where('r.comment_id', $comment_id);
		$this->db->order_by('r.replies_id', 'desc');
		$query = $this->db->get();
		return $query->result_array('array');
	}

	public function countReplies($comment_id) {
		$this->db->from('episode_replies r');
		$this->db->where('r.comment_id', $comment_id);
		return $this->db->count_all_results();
	}

	public function getCommentById($comment_id) {
		$this->db->select('c.comment_id, c.user_id, c.content, c.timestamp, u.user_name, u.full_name, u.avatar, u.user_id');
		$this->db->from('episode_comment c');
		$this->db->join('user u', 'u.user_id = c.user_id');
		$this->db->where('c.comment_id', $comment_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function checkComment($comment_id) {
		$this->db->select('c.comment_id, c.user_id, c.episode_id');
		$this->db->from('episode_comment c');
		$this->db->where('c.comment_id', $comment_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getRepliesById($replies_id) {
		$this->db->select('r.replies_id, r.user_id, r.content, r.timestamp, u.user_name, u.full_name, u.avatar, u.user_id');
		$this->db->from('episode_replies r');
		$this->db->join('user u', 'u.user_id = r.user_id');
		$this->db->where('r.replies_id', $replies_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function checkRepliesById($replies_id) {
		$this->db->from('episode_replies r');
		$this->db->where('r.replies_id', $replies_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function insertReplies($params) {
		$this->db->insert('episode_replies', $params);
		return $this->db->insert_id();
	}

	public function insertCommentLike($params) {
		$this->db->insert('comment_like', $params);
		return $this->db->insert_id();
	}

	public function insertRepliesLike($params) {
		$this->db->insert('replies_like', $params);
		return $this->db->insert_id();
	}

	public function deleteReplies($replies_id) {
		$this->db->where('replies_id', $replies_id);
		$this->db->delete('episode_replies');
	}

	public function removeCommentLike($user_id, $comment_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('comment_id', $comment_id);
		$this->db->delete('comment_like');
	}

	public function removeRepliesLike($user_id, $replies_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('replies_id', $replies_id);
		$this->db->delete('replies_like');
	}

	public function checkLikeComment($user_id, $comment_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('comment_id', $comment_id);
		$query = $this->db->get('comment_like');
		return $query->num_rows() > 0 ? 1 : 0;
	}

	public function checkLikeReplies($user_id, $replies_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('replies_id', $replies_id);
		$query = $this->db->get('replies_like');
		return $query->num_rows() > 0 ? 1 : 0;
	}

	public function findReport($comment_id, $reporter_id) {
		$this->db->where('comment_id', $comment_id);
		$this->db->where('reporter_id', $reporter_id);

		return $this->db->get('comment_reports')->first_row('array');
	}

	public function insertReport($comment_id, $reporter_id) {
		if ($this->findReport($comment_id, $reporter_id) != null) {
			return;
		}
		$this->db->insert('comment_reports', [
			'comment_id' => $comment_id,
			'reporter_id' => $reporter_id,
			'created_at' => time(),
		]);
	}

	public function getNumReports() {
		$this->db->from('comment_reports cr');
		$this->db->join('episode_comment c', 'c.comment_id = cr.comment_id');
		$this->db->join('user u2', 'u2.user_id = cr.reporter_id');
		$this->db->select('cr.report_id, u1.full_name, u2.full_name as reporter_name, cr.created_at');
		return $this->db->count_all_results();
	}

	public function getReports($page = 0) {
		$this->db->from('comment_reports cr');
		$this->db->join('episode_comment c', 'c.comment_id = cr.comment_id');
		$this->db->join('user u2', 'u2.user_id = cr.reporter_id');
		$this->db->select('cr.report_id, c.content, u2.full_name as reporter_name, cr.created_at');
		$this->db->order_by('report_id', 'desc');
		$this->db->limit(PERPAGE_ADMIN, $page * PERPAGE_ADMIN);
		$query = $this->db->get();
		return $query->result_array();
	}
}
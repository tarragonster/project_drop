<?php

require_once APPPATH . '/core/BaseModel.php';

class Comment_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'episode_comment';
		$this->id_name = 'comment_id';
	}

	public function getCommentForAdmin($episode_id){
		$this->db->select('c.comment_id, c.user_id, c.content, c.timestamp, u.user_name, u.full_name, u.avatar, u.user_id');
		$this->db->from('episode_comment c');
		$this->db->join('user u', 'u.user_id = c.user_id');
		$this->db->where('c.episode_id', $episode_id);
		$this->db->order_by('c.comment_id', 'desc');
		$query = $this->db->get();
		return $query->result_array('array');
	}
	public function getRepliesForAdmin($comment_id){
		$this->db->select('r.replies_id, r.user_id, r.content, r.timestamp, u.user_name, u.full_name, u.avatar, u.user_id');
		$this->db->from('episode_replies r');
		$this->db->join('user u', 'u.user_id = r.user_id');
		$this->db->where('r.comment_id', $comment_id);
		$this->db->order_by('r.replies_id', 'desc');
		$query = $this->db->get();
		return $query->result_array('array');
	}
	
	public function countReplies($comment_id){
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

	public function checkComment($comment_id){
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
		$this->db->select('r.replies_id, r.comment_id');
		$this->db->from('episode_replies r');
		$this->db->where('r.replies_id', $replies_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function insertReplies($params){
		$this->db->insert('episode_replies', $params);
		return $this->db->insert_id();
	}

	public function insertCommentLike($params){
		$this->db->insert('comment_like', $params);
		return $this->db->insert_id();
	}

	public function insertRepliesLike($params){
		$this->db->insert('replies_like', $params);
		return $this->db->insert_id();
	}

	public function deleteReplies($replies_id){
		$this->db->where('replies_id', $replies_id);
		$this->db->delete('episode_replies');
	}

	public function removeCommentLike($user_id, $comment_id){
		$this->db->where('user_id', $user_id);
		$this->db->where('comment_id', $comment_id);
		$this->db->delete('comment_like');
	}

	public function removeRepliesLike($user_id, $replies_id){
		$this->db->where('user_id', $user_id);
		$this->db->where('replies_id', $replies_id);
		$this->db->delete('replies_like');
	}

	public function checkLikeComment($user_id, $comment_id){
		$this->db->where('user_id', $user_id);
		$this->db->where('comment_id', $comment_id);
		$query = $this->db->get('comment_like');
		return $query->num_rows() > 0 ? 1 : 0;
	}

	public function checkLikeReplies($user_id, $replies_id){
		$this->db->where('user_id', $user_id);
		$this->db->where('replies_id', $replies_id);
		$query = $this->db->get('replies_like');
		return $query->num_rows() > 0 ? 1 : 0;
	}
}
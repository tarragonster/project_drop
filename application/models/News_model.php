<?php

require_once APPPATH . '/core/BaseModel.php';

class News_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'user_notify';
		$this->id_name = 'notify_id';
	}

	public function getNewForFollowing($user_id, $page = -1) {
		$this->db->select('un.*');
		$this->db->from('user_notify un');
		$this->db->where('un.user_id', $user_id);
		$this->db->where('type <', 50);
		$this->db->order_by('un.notify_id', 'desc');
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getNewForYou($user_id, $page = -1) {
		$this->db->select('*');
		$this->db->from('user_notify');
		$this->db->where('user_id', $user_id);
		$this->db->where('type >', 50);
		$this->db->order_by('notify_id', 'desc');
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getUserForNotify($user_id) {
		$this->db->select('user_name, avatar, full_name, email');
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('user');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}


	public function getProductForNotify($product_id) {
		$this->db->select('*');
		$this->db->where('product_id', $product_id);
		$query = $this->db->get('product');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getComment($comment_id) {
		$this->db->where('comment_id', $comment_id);
		$query = $this->db->get('comments');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getReply($replies_id) {
		$this->db->where('replies_id', $replies_id);
		$query = $this->db->get('comment_replies');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getSeasonForNotify($season_id) {
		$this->db->select('name');
		$this->db->where('season_id', $season_id);
		$query = $this->db->get('season');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getEpisodeForNotify($episode_id) {
		$this->db->select('*');
		$this->db->where('episode_id', $episode_id);
		$query = $this->db->get('episode');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getPartEpisodeForNotify($episode_id) {
		$this->db->select('*');
		$this->db->where('episode_id', $episode_id);
		$query = $this->db->get('episode');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function countNotification($user_id) {
		$this->db->select('*');
		$this->db->from('user_notify');
		$this->db->where('user_id', $user_id);
		$this->db->where('status', 1);
		return $this->db->count_all_results();
	}

	public function updateAll($user_id, $params) {
		$this->db->where('user_id', $user_id);
		$this->db->update($this->table, $params);
	}

}	
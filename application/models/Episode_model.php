<?php

require_once APPPATH . '/core/BaseModel.php';

class Episode_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'episode';
		$this->id_name = 'episode_id';
	}

	public function getEpisodes($page = -1) {
		$this->db->where('status', 1);
		return $this->getList($page);
	}

	public function getEpisode($episode_id) {
		$this->db->select('e.*, p.rate_name as rate_name, s.name as season_name, p.name as product_name, p.product_id, p.paywall_episode as product_paywall_episode');
		$this->db->from('episode e');
		$this->db->join('season s', 's.season_id = e.season_id');
		$this->db->join('product_view p', 'p.product_id = s.product_id');
		$this->db->where('episode_id', $episode_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getProdutID($season_id) {
		$this->db->select('product_id');
		$this->db->from('season');
		$this->db->where('season_id', $season_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array')['product_id'] : null;
	}

	public function getEpisodeById($episode_id) {
		return $this->getObjectById($episode_id);
	}

	public function checkEpisode($episode_id) {
//		$this->db->select('episode_id, season_id, position');
		$this->db->where('episode_id', $episode_id);
		$this->db->where('status', 1);
		$query = $this->db->get('episode');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getEpisodeDetail($episode_id) {
		$this->db->select('e.*, s.name as season_name, p.name as product_name, p.product_id');
		$this->db->from('episode e');
		$this->db->join('season s', 's.season_id = e.season_id');
		$this->db->join('product p', 'p.product_id = s.product_id');
		$this->db->where('episode_id', $episode_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function updatePosition($season_id, $position) {
		$this->db->where('season_id', $season_id);
		$this->db->where('position >', $position);
		$this->db->set('position', 'position-1', false);
		$this->db->update('episode');
	}

	public function getPosition($season_id) {
		$this->db->where('season_id', $season_id);
		$this->db->where('status', 1);
		$this->db->order_by('position', 'desc');
		$query = $this->db->get($this->table);
		$this->db->limit(1);
		return $query->num_rows() > 0 ? $query->first_row()->position : 0;
	}

	public function countComment($episode_id) {
		$this->db->from('comments');
		$this->db->where('episode_id', $episode_id);
		return $this->db->count_all_results();
	}

	public function countAllSubComment($episode_id) {
		$this->db->from('comment_replies er');
		$this->db->join('comments ec', 'ec.comment_id = er.comment_id');
		$this->db->where('ec.episode_id', $episode_id);
		return $this->db->count_all_results();
	}

	public function countLike($episode_id, $status = 1) {
		$this->db->from('episode_like');
		$this->db->where('episode_id', $episode_id);
		$this->db->where('status', $status);
		return $this->db->count_all_results();
	}

	public function getComments($episode_id, $type, $page = -1) {
		$this->db->select('c.comment_id, c.user_id, c.content, c.timestamp, u.user_name, u.full_name, u.avatar, u.user_id, if(l.num_of_likes is null, 0, l.num_of_likes) as num_like');
		$this->db->from('comments c');
		$this->db->join('user u', 'u.user_id = c.user_id');
		$subQuery = 'select comment_id, count(*) as num_of_likes from comment_like group by comment_id';
		$this->db->join("($subQuery) l", 'l.comment_id = c.comment_id', 'left');
		$this->db->where('c.episode_id', $episode_id);
		$this->db->group_by('c.comment_id');
		$this->db->order_by('num_like', 'desc');
		$this->db->order_by('c.comment_id', 'asc');
		if ($page >= 0)
			$this->db->limit(10, 10 * $page);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getReplies($comment_id) {
		$this->db->select('r.replies_id, r.user_id, r.content, r.timestamp, u.user_name, u.full_name, u.avatar, u.user_id, if(rl.num_of_likes is null, 0, rl.num_of_likes) as num_like');
		$this->db->from('comment_replies r');
		$this->db->join('user u', 'u.user_id = r.user_id');
		$subQuery = 'select replies_id, count(*) as num_of_likes from replies_like group by replies_id';
		$this->db->join("($subQuery) rl", 'rl.replies_id = r.replies_id', 'left');
		$this->db->where('r.comment_id', $comment_id);
		$this->db->order_by('num_like', 'desc');
		$this->db->order_by('r.replies_id', 'asc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function hasLikeEpisode($episode_id, $user_id, $status) {
		$this->db->where('episode_id', $episode_id);
		$this->db->where('user_id', $user_id);
		$this->db->where('status', $status);
		$query = $this->db->get('episode_like');
		return $query->num_rows() > 0 ? 1 : 0;
	}

	public function checkWatchEpisode($user_id, $episode_id) {
		$this->db->select('id');
		$this->db->where('episode_id', $episode_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('user_watch');
		return $query->num_rows() > 0;
	}

	public function getWatchEpisode($user_id, $episode_id) {
		$this->db->where('episode_id', $episode_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('user_watch');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getWatchProduct($user_id, $product_id) {
		$this->db->where('product_id', $product_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('user_watch');
		return $query->first_row('array');
	}

	public function addWatchEpisode($params) {
		$this->db->insert('user_watch', $params);
		return $this->db->insert_id();
	}

	public function removeWatchEpisode($id) {
		$this->db->where('id', $id);
		$this->db->delete('user_watch');
	}

	public function updateWatchEpisode($params, $id) {
		$this->db->where('id', $id);
		$this->db->update('user_watch', $params);
	}

	public function addRecentlyWatched($user_id, $product_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('product_id', $product_id);
		$item = $this->db->get('watched')->first_row();
		if ($item != null) {
			$this->db->where('user_id', $user_id);
			$this->db->where('product_id', $product_id);
			$this->db->update('watched', ['updated_at' => time()]);
		} else {
			$this->db->insert('watched', ['user_id' => $user_id, 'product_id' => $product_id, 'updated_at' => time()]);
		}
	}

	public function up($season_id, $position, $episode_id) {
		$this->db->trans_start();
		$this->db->where('season_id', $season_id);
		$this->db->where('position', $position - 1);
		$this->db->set('position', 'position+1', false);
		$this->db->update('episode');
		$this->db->where('episode_id', $episode_id);
		$this->db->set('position', 'position-1', false);
		$this->db->update('episode');
		$this->db->trans_complete();
	}

	public function down($season_id, $position, $episode_id) {
		$this->db->trans_start();
		$this->db->where('season_id', $season_id);
		$this->db->where('position', $position + 1);
		$this->db->set('position', 'position-1', false);
		$this->db->update('episode');
		$this->db->where('episode_id', $episode_id);
		$this->db->set('position', 'position+1', false);
		$this->db->update('episode');
		$this->db->trans_complete();
	}

	public function getMaxEpisode($season_id) {
		$this->db->order_by('position', 'desc');
		$this->db->where('season_id', $season_id);
		$query = $this->db->get('episode');
		$this->db->limit(1);
		return $query->num_rows() > 0 ? $query->first_row()->position : 0;
	}

	public function getNextEpisode($position, $season_id) {
		$this->db->select('e.*, p.rate_name as rate_name, s.name as season_name, p.name as product_name, p.product_id, p.paywall_episode as product_paywall_episode');
		$this->db->from('episode e');
		$this->db->join('season s', 's.season_id = e.season_id');
		$this->db->join('product_view p', 'p.product_id = s.product_id');
		$this->db->where('e.position', $position + 1);
		$this->db->where('e.season_id', $season_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function updateOrAddUserWatch($user_id, $product_id, $episode_id, $time) {
		$episode = $this->checkEpisode($episode_id);
		$this->addRecentlyWatched($user_id, $product_id);
		if ($episode == null) {
			return false;
		}
		$watch = $this->getWatchProduct($user_id, $product_id);
		if ($watch != null) {
			if ($time == -1) {
				$episodeNext = $this->getNextEpisode($episode['position'], $episode['season_id']);
				if ($episodeNext == null) {
					$this->removeWatchEpisode($watch['id']);
				} else {
					$this->updateWatchEpisode(array('episode_id' => $episodeNext['episode_id'], 'start_time' => 0, 'update_time' => time()), $watch['id']);
				}
			} else {
				$this->updateWatchEpisode(array('episode_id' => $episode_id, 'start_time' => $time, 'update_time' => time()), $watch['id']);
			}
		} else {
			if ($time != -1) {
				$this->episode_model->addWatchEpisode(array('episode_id' => $episode_id, 'user_id' => $user_id, 'season_id' => $episode['season_id'], 'product_id' => $product_id, 'start_time' => $time, 'update_time' => time()));
			}
		}
		return true;
	}

	public function getEpisodesBySeason($season_ids, $conditions) {
		$this->db->where_in('season_id', $season_ids);
		if (!empty($conditions['sort_by']) && in_array($conditions['sort_by'], array('status'))) {
            if (!empty($conditions['inverse']) && $conditions['inverse'] == 1) {
                $this->db->order_by($conditions['sort_by'], 'desc');
            }else {
                $this->db->order_by($conditions['sort_by'], 'asc');
            }
        }
        $this->db->order_by('position');
		return $this->db->get($this->table)->result_array();
	}

	public function getAllComment($episode_ids) {
		$this->db->where_in('episode_id', $episode_ids);
		$this->db->from('comments');
		return $this->db->get()->result_array();
	}

	public function getAllLike($episode_ids) {
		$this->db->where_in('episode_id', $episode_ids);
		$this->db->from('episode_like');
		return $this->db->get()->result_array();
	}

	public function getEpisodesOfSeason($season_id) {
		$this->db->where('season_id', $season_id);
		return $this->db->get($this->table)->result_array();	
	}

}
<?php

require_once APPPATH . '/core/BaseModel.php';

class Season_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'season';
		$this->id_name = 'season_id';
	}

	public function getSeasons($page = -1) {
		$this->db->where('status', 1);
		return $this->getList($page, 'season_id', 'desc');
	}

	public function getSeasonById($season_id) {
		return $this->getObjectById($season_id);
	}

	public function getSeasonDetail($season_id) {
		$this->db->select('s.*, p.name as product_name');
		$this->db->from('season s');
		$this->db->join('product p', 's.product_id = p.product_id');
		$this->db->where('s.season_id', $season_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getSeasonForAdmin($page = -1) {
		$this->db->select('s.*, p.name as product_name');
		$this->db->from('season s');
		$this->db->join('product p', 's.product_id = p.product_id');
		$this->db->where('s.status', 1);
		$this->db->order_by('s.season_id', 'desc');
		$query = $this->db->get();
		return $query->result_array('array');
	}

	public function getListEpisodes($season_id, $page = -1) {
		$this->db->select('*');
		$this->db->from('episode');
		$this->db->where('season_id', $season_id);
		$this->db->order_by('position', 'asc');
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$query = $this->db->get();
		return $query->result_array('array');
	}

	public function getListContinue($user_id, $season_id) {
		$this->db->select('*');
		$this->db->from('user_watch');
		$this->db->where('user_id', $user_id);
		$this->db->where('season_id', $season_id);
		$query = $this->db->get();
		return $query->result_array('array');
	}

	public function getEpisodeContinue($user_id, $episode_id) {
		$this->db->select('*');
		$this->db->from('user_watch');
		$this->db->where('user_id', $user_id);
		$this->db->where('episode_id', $episode_id);
		$query = $this->db->get();
		return $query->first_row('array');
	}

	public function getNumEpisode($season_id) {
		$this->db->from('episode');
		$this->db->where('season_id', $season_id);
		return $this->db->count_all_results();
	}

	public function deleteSeason($season_id) {
		$this->db->trans_start();
		$this->db->where($this->id_name, $season_id);
		$this->db->update($this->table, array('status' => 0));
		$this->db->where($this->id_name, $season_id);
		$this->db->update('episode', array('status' => 0));
		$this->db->trans_complete();
	}
}
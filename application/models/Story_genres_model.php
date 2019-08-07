<?php

require_once APPPATH . '/core/BaseModel.php';

class Story_genres_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'story_genres';
		$this->id_name = 'id';
	}

	public function getAll($conditions = '') {
		if (!empty($conditions['sort_by'])) {
			if (!empty($conditions['inverse']) && $conditions['inverse'] == 1) {
				$this->db->order_by($conditions['sort_by'], 'desc');
			}else {
				$this->db->order_by($conditions['sort_by'], 'asc');
			}
		}
		$this->db->order_by('priority');
		return $this->db->get($this->table)->result_array();
	}

	public function getMaxPriority() {
		$this->db->select_max('priority');
		return $this->db->get($this->table)->first_row('array');
	}

	public function getDeselectGenre($product_id, $selected_genres) {
		$this->db->select('*');
		$this->db->from('story_genres sg');
		$this->db->where_not_in('sg.id', $selected_genres);
		return $this->db->get()->result_array();
	}
}
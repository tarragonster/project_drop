<?php
require_once APPPATH . '/core/BaseModel.php';

class Search_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'trending_search';
		$this->id_name = 'id';
	}

	public function storeSearch($keyword) {
		$this->db->where('keyword', $keyword);
		$item = $this->db->get('trending_search')->first_row();
		if ($item == null) {
			$item = ['keyword' => $keyword, 'times' => 1, 'updated_at' => time()];
			$this->db->insert('trending_search', $item);
		} else {
			$this->db->where('id', $item->id);
			$this->db->set('times', 'times + 1', false);
			$this->db->set('updated_at', time());
			$this->db->update('trending_search');
		}
	}

	public function getKeywordSuggestion($key) {
		$this->db->like('keyword', $key, 'both');
		$this->makeSearchQuery(['lower(keyword)'], strtolower($key));
		$this->db->order_by('times', 'desc');
		$this->db->limit(10);
		return $this->db->get('trending_search')->result_array();
	}

}
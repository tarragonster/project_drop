<?php
require_once APPPATH . '/core/BaseModel.php';

class Genre_model extends BaseModel {
	protected $table = 'story_genres';
	protected $id_name = 'id';

	public function __construct() {
		parent::__construct();
	}

	public function getGenresList() {
		$this->db->select('id, name');
		$this->db->order_by('priority');
		$this->db->order_by($this->id_name);
		$query = $this->db->get($this->table);
		return $query->result_array();
	}

	public function getGenreFilms($genre_id, $page = -1, $limit = 24) {
		$this->db->select('p.*');
		$this->db->from('product_view p');
		$this->db->where('p.genre_id', $genre_id);
		if ($page >= 0) {
			$this->db->limit($limit, $limit * $page);
		}
		return $this->db->get()->result_array();
	}
}
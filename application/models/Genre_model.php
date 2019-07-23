<?php
require_once APPPATH . '/core/BaseModel.php';

class Genre_model extends BaseModel {
	protected $table = 'story_genres';
	protected $id_name = 'id';

	public function __construct() {
		parent::__construct();
	}

	public function getGenresList() {
		$this->db->select('id, name, image');
		$this->db->order_by('priority');
		$this->db->where('status', 1);
		$this->db->order_by($this->id_name);
		$query = $this->db->get($this->table);
		return $query->result_array();
	}

	public function getGenreFilms($genre_id, $page = -1, $limit = 24) {
		$this->db->select('p.*, ep.id, ep.promo_image');
		$this->db->from('(select * from product_genres where genre_id = ' . $genre_id . ') pg');
		$this->db->join('product_view p', 'p.product_id = pg.product_id');
		$this->db->join('explore_previews ep', 'ep.product_id = p.product_id');
		$this->db->order_by('pg.added_at', 'desc');
		if ($page >= 0) {
			$this->db->limit($limit, $limit * $page);
		}
		return $this->db->get()->result_array();
	}
}
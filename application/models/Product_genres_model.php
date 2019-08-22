<?php

require_once APPPATH . '/core/BaseModel.php';

class Product_genres_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'product_genres';
		$this->id_name = 'id';
	}

	public function getAll($product_id) {
		$this->db->select('sg.id as genre_id, sg.name as genre_name');
		$this->db->where('product_id', $product_id);
		$this->db->from('product_genres pg');
		$this->db->join('story_genres sg', 'pg.genre_id = sg.id');
		return $this->db->get()->result_array();
	}

	public function countStoryByGenre($genre_id) {
		$this->db->where('genre_id', $genre_id);
		return $this->db->get($this->table)->result_array();
	}

	public function deleteByGenre($genre_id) {
		$this->db->where('genre_id', $genre_id);
		$this->db->delete($this->table);
	}

	public function getSelectedGenres($product_id) {
		$this->db->select('genre_id');
		$this->db->where('product_id', $product_id);
		return $this->db->get($this->table)->result_array();
	}
	public function checkIfExist($product_id, $item) {
		$this->db->where('product_id', $product_id);
		$this->db->where('genre_id', $item);
		return $this->db->count_all_results($this->table);
	}
}
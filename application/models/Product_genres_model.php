<?php

require_once APPPATH . '/core/BaseModel.php';

class Product_genres_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'product_genres';
		$this->id_name = 'id';
	}

	public function getAll($product_id) {
		$this->db->select('sg.name as genre_name');
		$this->db->where('product_id', $product_id);
		$this->db->from('product_genres pg');
		$this->db->join('story_genres sg', 'pg.genre_id = sg.id');
		return $this->db->get()->result_array();
	}
}
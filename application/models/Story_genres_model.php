<?php

require_once APPPATH . '/core/BaseModel.php';

class Story_genres_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'story_genres';
		$this->id_name = 'id';
	}

	public function getAll() {
		return $this->db->get($this->table)->result_array();
	}
}
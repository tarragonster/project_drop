<?php

require_once APPPATH . '/core/BaseModel.php';

class Category_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'category';
		$this->id_name = 'category_id';
	}

	public function getCategories($page = -1) {
		$this->db->where('status', 1);
		return $this->getList($page, 'priority', 'asc');
	}

	public function getCategoryById($category_id) {
		return $this->getObjectById($category_id);
	}

}
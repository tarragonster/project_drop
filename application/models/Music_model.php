<?php

require_once APPPATH . '/core/BaseModel.php';

class Music_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'music';
		$this->id_name = 'music_id';
	}

	public function getMusics($page = -1) {
		$this->db->where('status', 1);
		return $this->getList($page);
	}

	public function getOthers() {
		$this->db->select('*');
		$this->db->from('music');
		$this->db->where('product_id', null);
		$this->db->or_where('product_id', 0);
		$this->db->where('status', 1);
		$this->db->order_by('music_id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getMusicForAdmin($page = -1) {
		$this->db->select('m.*, p.name as product_name');
		$this->db->from('music m');
		$this->db->join('product p', 'p.product_id = m.product_id');
		$this->db->where('m.status', 1);
		$this->db->order_by('music_id', 'desc');
		if ($page >= 0)
			$this->db->limit(PERPAGE_ADMIN, PERPAGE_ADMIN * $page);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getMusicById($music_id) {
		return $this->getObjectById($music_id);
	}

	public function getMusicByName($music_name) {
		return $this->getObjectByName('name', $music_name);
	}
}
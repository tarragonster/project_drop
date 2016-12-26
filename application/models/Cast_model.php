<?php

require_once APPPATH . '/core/BaseModel.php';

class Cast_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'cast';
		$this->id_name = 'cast_id';
	}

	public function getCasts($page = -1) {
		return $this->getList($page);
	}

	public function getCastById($cast_id) {
		return $this->getObjectById($cast_id);
	}
	public function getCastOthers($product_id, $name){
		$sql = "SELECT * FROM `cast` WHERE status = 1 AND name LIKE '%".$name."%' AND cast_id NOT IN (SELECT cast_id FROM film_cast WHERE product_id = '$product_id' GROUP BY cast_id) ORDER BY cast_id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	public function getOthers($product_id) {
		$sql = "SELECT * FROM `cast` WHERE status = 1 AND  cast_id NOT IN (SELECT cast_id FROM film_cast WHERE product_id = '$product_id' GROUP BY cast_id) ORDER BY cast_id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getFilmOthers($cast_id) {
		$sql = "SELECT * FROM product WHERE status = 1 AND  product_id NOT IN (SELECT product_id FROM film_cast WHERE cast_id = '$cast_id' GROUP BY product_id) ORDER BY product_id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getCastByName($cast_name) {
		return $this->getObjectByName('name', $cast_name);
	}

	public function getListFilms($cast_id) {
		$this->db->select('p.*');
		$this->db->from('film_cast fc');
		$this->db->join('product_view p', 'fc.product_id = p.product_id');
		$this->db->where('fc.cast_id', $cast_id);
		$this->db->where('p.status', 1);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function deleteCast($cast_id) {
		$this->db->trans_start();
		$this->db->where($this->id_name, $cast_id);
		$this->db->update($this->table, array('status' => 0));
		$this->db->where($this->id_name, $cast_id);
		$this->db->delete('film_cast');
		$this->db->trans_complete();
	}
}
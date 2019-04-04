<?php

require_once APPPATH . '/core/BaseModel.php';

class Collection_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'collection';
		$this->id_name = 'collection_id';
	}

	public function getCollections($page = -1) {
		$this->db->where('status', 1);
		return $this->getList($page, 'priority', 'asc');
	}

	public function getCollectionById($collection_id) {
		return $this->getObjectById($collection_id);
	}

	public function addFilm($params) {
		$this->db->insert('collection_product', $params);
		return $this->db->insert_id();
	}

	public function removeFilm($collection_id, $product_id, $priority) {
		$this->db->trans_start();
		$this->db->where('collection_id', $collection_id);
		$this->db->where('product_id', $product_id);
		$this->db->delete('collection_product');
		$this->db->where('collection_id', $collection_id);
		$this->db->where('priority >', $priority);
		$this->db->set('priority', 'priority-1', false);
		$this->db->update('collection_product');
		$this->db->trans_complete();
	}

	public function upFilm($collection_id, $priority, $id) {
		$this->db->trans_start();
		$this->db->where('collection_id', $collection_id);
		$this->db->where('priority', $priority - 1);
		$this->db->set('priority', 'priority+1', false);
		$this->db->update('collection_product');
		$this->db->where('collection_id', $collection_id);
		$this->db->where('id', $id);
		$this->db->set('priority', 'priority-1', false);
		$this->db->update('collection_product');
		$this->db->trans_complete();
	}

	public function downFilm($collection_id, $priority, $id) {
		$this->db->trans_start();
		$this->db->where('collection_id', $collection_id);
		$this->db->where('priority', $priority + 1);
		$this->db->set('priority', 'priority-1', false);
		$this->db->update('collection_product');
		$this->db->where('collection_id', $collection_id);
		$this->db->where('id', $id);
		$this->db->set('priority', 'priority+1', false);
		$this->db->update('collection_product');
		$this->db->trans_complete();
	}

	public function getMaxFilm($collection_id) {
		$this->db->order_by('priority', 'desc');
		$this->db->where('collection_id', $collection_id);
		$query = $this->db->get('collection_product');
		$this->db->limit(1);
		return $query->num_rows() > 0 ? $query->first_row()->priority : 0;
	}

	public function getCollectionProducts($product_id) {
		$this->db->where('product_id', $product_id);
		$query = $this->db->get('collection_product');
		return $query->first_row('array');
	}

	public function getProductOthers($collection_id) {
		$sql = "SELECT * FROM product_view WHERE status = 1 AND product_id NOT IN (SELECT product_id FROM collection_product WHERE collection_id = '$collection_id' GROUP BY product_id) ORDER BY product_id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getMax() {
		$this->db->order_by('priority', 'desc');
		$query = $this->db->get('collection');
		$this->db->limit(1);
		return $query->num_rows() > 0 ? $query->first_row()->priority : 0;
	}

	public function getProductCollection($id) {
		$this->db->from('collection_product cp');
		$this->db->join('product p', 'p.product_id = cp.product_id');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->first_row('array');
	}

	public function updateFilm($params, $id) {
		$this->db->where('id', $id);
		$rows = $this->db->update('collection_product', $params);
		return $rows;
	}

	public function addFilmPreviews($params) {
		$this->db->insert('collection_product', $params);
		return $this->db->insert_id();
	}

	public function editPromo($product_id, $promo_image) {
		$this->db->where('product_id', $product_id);
		$this->db->set('promo_image', $promo_image);
		$this->db->update('collection_product');
	}

}
<?php

require_once APPPATH . '/core/BaseModel.php';

class Feed_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'feed';
		$this->id_name = 'feed_id';
	}

	public function getFeedById($id) {
		$this->db->where($this->id_name, $id);
		$query = $this->db->get($this->table);
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getFeedForAdmin() {
		$this->db->select('f.*, p.name as product_name');
		$this->db->from('feed f');
		$this->db->join('product_view p', 'p.product_id = f.product_id');
		$this->db->order_by('f.position', 'asc');
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getFeeds() {
		$this->db->select('f.*, p.name as product_name');
		$this->db->from('feed f');
		$this->db->join('product_view p', 'p.product_id = f.product_id');
		$this->db->order_by('f.position', 'asc');
		$query = $this->db->get();
		return $query->result_array('array');
	}

	public function getMax() {
		$this->db->order_by('position', 'desc');
		$this->db->limit(1);
		$query = $this->db->get($this->table);
		return $query->num_rows() > 0 ? $query->first_row()->position : 0;
	}
	
	public function remove($item){
		$this->db->where('position >', $item['position']);
		$this->db->set('position', 'position-1', false);
		$this->db->update($this->table);
		$this->db->where($this->id_name, $item['feed_id']);
		$this->db->delete($this->table);
	}

	public function up($item) {
		$this->db->where('position', $item['position'] - 1);
		$this->db->set('position', 'position+1', false);
		$this->db->update($this->table);
		$this->db->where($this->id_name, $item['feed_id']);
		$this->db->set('position', 'position-1', false);
		$this->db->update($this->table);
	}
	
	public function down($item) {
		$this->db->where('position', $item['position'] + 1);
		$this->db->set('position', 'position-1', false);
		$this->db->update($this->table);
		$this->db->where($this->id_name, $item['feed_id']);
		$this->db->set('position', 'position+1', false);
		$this->db->update($this->table);
	}

	public function getOthers($query = ''){
		if($query != ''){
			$temp = " AND name LIKE '%".$query."%' ";
		}else{
			$temp = "";
		}
		$sql = "SELECT * FROM product WHERE status = 1 ".$temp." AND product_id NOT IN (SELECT product_id FROM feed GROUP BY product_id) ORDER BY product_id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
}
<?php

require_once APPPATH . '/core/BaseModel.php';

class Preview_model extends BaseModel {
	public function __construct() {
		parent::__construct();

		$this->table = 'explore_previews';
		$this->id_name = 'id';
	}

	public function getListProducts($page = -1) {
		$this->db->select('p.*, ep.priority as priority_preview, ep.id');
		$this->db->from('explore_previews ep');
		$this->db->join('product_view p', 'p.product_id = ep.product_id');
		$this->db->where('p.status', 1);
		$this->db->order_by('ep.priority');
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getMaxFilm() {
		$this->db->order_by('priority', 'desc');
		$query = $this->db->get('explore_previews');
		$this->db->limit(1);
		return $query->num_rows() > 0 ? $query->first_row()->priority : 0;
	}

	public function getProductOthers() {
		$sql = "SELECT * FROM product_view WHERE status = 1 AND product_id NOT IN (SELECT product_id FROM explore_previews  GROUP BY product_id) ORDER BY name";
		$query = $this->db->query($sql);
		return $query->result_array();
	}


	public function addFilm($product_id) {
		if ($this->getFilm($product_id) == null) {
			$this->db->insert('explore_previews', [
				'product_id' => $product_id,
				'priority' => $this->getMaxFilm() + 1,
				'status' => 1,
			]);
			return $this->db->insert_id();
		}
		return -1;
	}


	public function getFilm($product_id) {
		$this->db->where('product_id', $product_id);
		$query = $this->db->get('explore_previews');
		return $query->first_row('array');
	}

	public function removeFilm($product_id) {
		$film = $this->getFilm($product_id);
		if ($film != null) {
			$this->db->trans_start();
			$this->db->where('product_id', $product_id);
			$this->db->delete('explore_previews');

			// Move up below
			$this->db->where('priority >', $film['priority']);
			$this->db->set('priority', 'priority - 1', false);
			$this->db->update('explore_previews');
			$this->db->trans_complete();
		}
	}

	public function upFilm($product_id) {
		$film = $this->getFilm($product_id);
		if ($film != null) {
			$this->db->trans_start();
			$this->db->where('priority', $film['priority'] - 1);
			$this->db->set('priority', 'priority + 1', false);
			$this->db->update('explore_previews');

			$this->db->where('product_id', $product_id);
			$this->db->set('priority', 'priority - 1', false);
			$this->db->update('explore_previews');
			$this->db->trans_complete();
		}
	}

	public function downFilm($product_id) {
		$film = $this->getFilm($product_id);
		if ($film != null) {
			$this->db->trans_start();
			$this->db->where('priority', $film['priority'] + 1);
			$this->db->set('priority', 'priority-1', false);
			$this->db->update('explore_previews');

			$this->db->where('product_id', $product_id);
			$this->db->set('priority', 'priority + 1', false);
			$this->db->update('explore_previews');
			$this->db->trans_complete();
		}
	}

}
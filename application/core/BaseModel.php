<?php

class BaseModel extends CI_Model {
	protected $table;
	protected $id_name;

	public function __construct() {
		parent::__construct();
	}

	public function getList($page = -1, $column = '', $order = 'desc') {
		if ($column == '') {
			$this->db->order_by($this->id_name, $order);
		} else {
			$this->db->order_by($column, $order);
		}
		if ($page >= 0)
			$this->db->limit(10, 10 * $page);
		$query = $this->db->get($this->table);
		return $query->result_array();
	}

	public function insert($params) {
		$this->db->insert($this->table, $params);
		return $this->db->insert_id();
	}

	public function update($params, $id) {
		$this->db->where($this->id_name, $id);
		// $rows = $this->db->update($this->table, $params);
		// return $rows;
		$this->db->update($this->table, $params);
	}

	public function get($id, $select = '') {
		return $this->getObjectById($id, $select);
	}

	public function getBy($field, $value) {
		$this->db->where($field, $value);
		$query = $this->db->get($this->table);
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getObjectById($id, $select = '') {
		$this->db->where($this->id_name, $id);
		// $this->db->where('status', 1);
		if ($select != '')
			$this->db->select($select);
		$query = $this->db->get($this->table);
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getObjectByName($name, $value, $select = '') {
		$this->db->where('status', 1);
		$this->db->like($name, $value);
		if ($select != '')
			$this->db->select($select);
		$query = $this->db->get($this->table);
		return $query->result_array();
	}

	public function getListByCondition($conditions, $column = '', $order = 'asc') {
		$this->db->where($this->id_name, $id);
		if ($select != '')
			$this->db->select($select);
		$query = $this->db->get($this->table);
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function delete($id) {
		$this->db->where($this->id_name, $id);
		$rows = $this->db->delete($this->table);
		return $rows;
	}

	public function countAll() {
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
}
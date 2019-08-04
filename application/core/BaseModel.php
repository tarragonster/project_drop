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

	public function delete($id) {
		$this->db->where($this->id_name, $id);
		$rows = $this->db->delete($this->table);
		return $rows;
	}

	public function countAll() {
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function merge(&$items, $results, $field, $resultField = null, $default = 0) {
		if (!is_array($items) || count($items) == 0) {
			return $items;
		}

		if (empty($resultField)) {
			$resultField = $field;
		}
		$map = [];
		foreach ($results as $result) {
			$map[$result[$this->id_name]] = $result;
		}

		foreach ($items as $key => $item) {
			if (isset($map[$item[$this->id_name]])) {
				$mapItem = $map[$item[$this->id_name]];
				$items[$key][$field] = $mapItem[$resultField];
			} else {
				$items[$key][$field] = $default;
			}
		}
	}

	public function expandFromArray($items, $field, $newField = '', $resources = [], $mapFiled = '') {

		if (!is_array($items) || count($items) == 0) {
			return $items;
		}

		$ref = empty($newField) ? str_replace('_id', '', $field) : $newField;;
		$map = [];
		foreach ($resources as $result) {
			$map[$result[$this->id_name]] = $result;
		}
		foreach ($items as $key => $item) {
			if (isset($map[$item[$field]])) {
				$items[$key][$ref] = $map[$item[$field]];
			}
		}

		return $items;
	}

	public function expand($items, $field, $newField = '', $select = '') {
		if (!is_array($items) || count($items) == 0) {
			return $items;
		}
		$array = [];
		foreach ($items as $key => $item) {
			if ($item[$field] != null && $item[$field] > 0 && !in_array($item[$field], $array)) {
				$array[] = $item[$field];
			}
		}
		if (count($array) == 0) {
			return $items;
		}
		$this->db->from($this->table);
		$this->db->where_in($this->id_name, $array);
		if (!empty($select)) {
			$this->db->select($select);
		} else if (!empty($this->simple_select)) {
			$this->db->select($this->simple_select);
		}
		$results = $this->db->get()->result_array();

		$ref = empty($newField) ? str_replace('_id', '', $field) : $newField;
		$map = [];
		foreach ($results as $result) {
			$map[$result[$this->id_name]] = $result;
		}
		foreach ($items as $key => $item) {
			if (isset($map[$item[$field]])) {
				$items[$key][$ref] = $map[$item[$field]];
			}
		}

		return $items;
	}

	public function expandOne($item, $field, $newField, $select = '') {
		$this->db->from($this->table);
		$this->db->where($this->id_name, $item[$field]);
		if (!empty($select)) {
			$this->db->select($select);
		} else if (!empty($this->simple_select)) {
			$this->db->select($this->simple_select);
		}
		$result = $this->db->get()->first_row('array');

		$ref = empty($newField) ? str_replace('_id', '', $field) : $newField;
		$item[$ref] = $result;

		return $item;
	}

	public function expandCollection($items, $field, $mappingField, $resultField, $columns = [], $order_field = '', $order_by = 'asc') {
		if (!is_array($items) || count($items) == 0) {
			return $items;
		}
		$array = [];
		foreach ($items as $key => $item) {
			if ($item[$field] != null && $item[$field] > 0 && !in_array($item[$field], $array)) {
				$array[] = $item[$field];
			}
		}
		if (count($array) == 0) {
			return $items;
		}
		$this->db->from($this->table);
		$this->db->where_in($mappingField, $array);
		$select = implode(', ', $columns);
		if (!in_array($mappingField, $columns)) {
			$select = $mappingField . ', '. $select;
		}
		if (!in_array($this->id_name, $columns)) {
			$select = $this->id_name . ', '. $select;
		}
		$this->db->select($select);
		$this->db->order_by($mappingField);
		if (!empty($order_field)) {
			$this->db->order_by($order_field, $order_by);
		}
		$results = $this->db->get()->result_array();

		return $this->expandCollectionResults($items, $results, $field, $mappingField, $resultField, $columns);
	}

	protected function expandCollectionResults($items, $results, $field, $mappingField, $resultField, $columns) {
		$groups = [];
		if (count($results) > 0) {
			$field_value = -1;
			$group = [];
			foreach ($results as $result) {
				if ($result[$mappingField] != $field_value) {
					if ($field_value != -1) {
						$groups[$field_value] = $group;
						$group = [];
					}
					$field_value = $result[$mappingField];
				}
				if (count($columns) == 1) {
					$group[] = $result[$columns[0]];
				} else {
					if (!in_array($mappingField, $columns)) {
						unset($result[$mappingField]);
					}
					if (!in_array($this->id_name, $columns)) {
						unset($result[$this->id_name]);
					}
					$group[] = $result;
				}
			}
			$groups[$field_value] = $group;
		}

		foreach ($items as $key => $item) {
			if (isset($groups[$item[$field]])) {
				$items[$key][$resultField] = $groups[$item[$field]];
			} else {
				$items[$key][$resultField] = [];
			}
		}
		return $items;
	}

    protected function makeSearchQuery($searchBy = array(), $keyword) {
        $keyword = str_replace('+', ' ', $keyword);
        if (is_array($searchBy) && count($searchBy) > 0) {
            $sql = $searchBy[0] . ' like "%' . $keyword . '%"';
            for ($i = 1; $i < count($searchBy); $i++) {
                $sql .= ' or ' . $searchBy[$i] . ' like "%' . $keyword . '%"';
            }
            $this->db->where('(' . $sql . ')');
        }
    }
}

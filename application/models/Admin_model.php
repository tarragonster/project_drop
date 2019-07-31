<?php

class Admin_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'admin';
		$this->id_name = 'id';
	}

	public function getAdminAccount($email, $password) {
		$sql = "select * from admin where email = '$email' and password=md5('$password')";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->result_array()[0];
		} else {
			return null;
		}
	}

	public function getAdminAccountByEmail($email) {
		$sql = "select * from admin where email = '$email'";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return $result->result_array()[0];
		} else {
			return null;
		}
	}
}
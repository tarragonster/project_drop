<?php

class Passcode_model extends BaseModel {

	public function __construct($params = null) {
		parent::__construct();
		$this->table = 'code_reset_password';
		$this->id_name = 'user_id';
	}

	public function setTable($table) {
		$this->table = $table;
	}

	public function setIdName($idName) {
		$this->id_name = $idName;
	}

	public function addRequestPassword($user_id, $group) {
		$code = md5(md5(time() . '|yDMP' . $user_id) . time());
		$userCode = base64_encode($user_id . '|' . $code);
		$params = array();
		$params['user_id'] = $user_id;
		$params['created'] = time();
		$params['code'] = $code;
		$params['group'] = $group;
		$this->db->insert($this->table, $params);
		$this->clearPasswordCode($user_id);
		return $userCode;
	}

	public function verifyPasswordCode($userCode) {
		$authorization = base64_decode($userCode);
		if ($authorization != false) {
			$arr = explode('|', $authorization);
			if (count($arr) == 2) {
				$code = $arr[1];
				$user_id = $arr[0] * 1;
				$this->db->where($this->id_name, $user_id);
				$this->db->where('code', $code);
				$item = $this->db->get($this->table)->first_row('array');
				return $item;
			}
		}
		return null;
	}

	public function clearPasswordCode($user_id) {
		$this->db->where($this->id_name, $user_id);
		$this->db->update($this->table, array('has_reset' => '1'));
	}

}
<?php

define('ACCESS_TABLE', 'user_access_token');

class Oauths {
	protected $CI;
	
	public function __construct() {
		$this->CI = &get_instance();
	}
	
	public function validate($user_id, $access_token) {
		if ($access_token == '')
			return false;
		$token = $this->getAccessToken($user_id, $access_token);
		if ($token == '')
			return false;
		$this->update($user_id, $token);
		return true;
	}
	
	public function success($user_id, $device_id) {
		$token = $this->getDeviceId($user_id, $device_id);
		if ($token == '')
			return $this->create($user_id, $device_id);
		$this->update($user_id, $token);
		return $token;
	}
	
	public function remove($user_id, $access_token) {
		$this->CI->db->where('user_id', $user_id);
		$this->CI->db->where('access_token', $access_token);
		$query = $this->CI->db->get(ACCESS_TABLE);
		if ($query->num_rows() > 0) {
			$item =  $query->first_row();
			$this->CI->db->where('device_id', $item->device_id);
			$this->CI->db->where('user_id', $user_id);
			$this->CI->db->update('device_user', array('user_id' => 0));
			
			$this->CI->db->where('user_id', $user_id);
			$this->CI->db->where('access_token', $access_token);
			$this->CI->db->delete(ACCESS_TABLE);
		}
	}
	
	public function delete($user_id) {
		$this->CI->db->where('user_id', $user_id);
		$this->CI->db->delete(ACCESS_TABLE);
	}

	private function create($user_id, $device_id) {
		$time = time();
		$access_token = md5(rand(1000, 9999) . $user_id . $time);
		$params = array('user_id' => $user_id, 'device_id' => $device_id, 'access_token' => $access_token, 'auth' => base64_encode($user_id . '|' . $access_token),  'added' => $time, 'last_access' => $time);
		$this->CI->db->insert(ACCESS_TABLE, $params);
		return $access_token;
	}
	
	private function getDeviceId($user_id, $device_id) {
		$this->CI->db->where('user_id', $user_id);
		$this->CI->db->where('device_id', $device_id);
		$query = $this->CI->db->get(ACCESS_TABLE);
		if ($query->num_rows() > 0) {
			$item =  $query->first_row();
			return $item->access_token;
		}
		return '';
	}
	
	private function getAccessToken($user_id, $access_token) {
		$this->CI->db->where('user_id', $user_id);
		$this->CI->db->where('access_token', $access_token);
		$query = $this->CI->db->get(ACCESS_TABLE);
		if ($query->num_rows() > 0) {
			$item =  $query->first_row();
			return $item->access_token;
		}
		return '';
	}

	private function update($user_id, $access_token) {
		$this->CI->db->where('user_id', $user_id);
		$this->CI->db->where('access_token', $access_token);
		$this->CI->db->update(ACCESS_TABLE, array('last_access' => time()));
	}
}
?>
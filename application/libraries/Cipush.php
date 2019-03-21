<?php

define('GCM_URL', 'https://android.googleapis.com/gcm/send');

/**
 * Class Cipush
 * @author Ricky DOO created on Nov 6, 2015
 */
class Cipush {
	private $push_table;
	
	private $gcm_api_key;
	private $apns_server;
	private $apns_cert;
	private $apns_passphrase;
	
	private $_CI;
	
	public function __construct() {
		$this->_CI =& get_instance();
		$this->_CI->config->load('push', TRUE);
		$config = $this->_CI->config->item('push');
	
		foreach ($config as $key => $value) {
			$this->$key = $value;
		}
	}
	
	public function start() {
		for($i = 1; $i < 10; $i++) {
			$this->_CI->db->from($this->push_table);
			$this->_CI->db->where('time_sent <', 5);
			$this->_CI->db->order_by('message_id');
			$this->_CI->db->limit(20);
			$query = $this->_CI->db->get();
			$messages = $query->result_array();
			if ($messages != null && count($messages) > 0) {
				foreach ($messages as $message) {
					$success = true;
					if ($message['device_type'] == 1) { // Android
						$success = $this->sendAndroid($message['device_token'], $message['data']);
					} else {
						$success = $this->sendIOS($message['device_token'], $message['data']);
					}
					if ($success) {
						$this->_CI->db->where('message_id', $message['message_id']);
						$this->_CI->db->update($this->push_table, array('time_sent' => time()));
					} else {
						$this->_CI->db->where('message_id', $message['message_id']);
						$this->_CI->db->update($this->push_table, array('time_sent' => $message['time_sent'] + 1));
					}
				}
				unset($messages);
			}
			sleep(5);
		}
	}
	
	public function sendIOS($device_token, $payload) {
		$ctx = stream_context_create();
		if (!file_exists($this->apns_cert)) {
			return false;
		}
		stream_context_set_option($ctx, 'ssl', 'local_cert', $this->apns_cert);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $this->apns_passphrase);
		$this->fp = stream_socket_client( 'ssl://' . $this->apns_server, $err, $errstr, 120,
				STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		if (!$this->fp) {
			die("Failed to connect: $err $errstr");
			return false;
		}
		
		$msg = chr(0) . pack('n', 32) . pack('H*', $device_token) . pack('n', strlen($payload)) . $payload;
		
		$result = @fwrite($this->fp, $msg, strlen($msg));
		fclose($this->fp);
		if (!$result) {
			die('Message not delivered');
			return false;
		}
		return true;
	}
	
	public function sendAndroid($device_token, $data) {
		$fields = array (
				'registration_ids' => array($device_token),
				'data' => json_decode($data),
		);
		//print_r($fields);
		$headers = array (
				'Authorization: key=' . $this->gcm_api_key,
				'Content-Type: application/json'
		);
		// Open connection
		$ch = curl_init();
		
		// Set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, GCM_URL);
		
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		// Disabling SSL Certificate support temporarly
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		
		// Execute post
		$result = curl_exec($ch);
// 		print_r($result, false);
		if ($result === FALSE) {
			return false;
		}
		curl_close($ch);
		return true;
	}
	
	public function addAndroid($device_token, $data_send, $sendNow = false) {
// 		$data = array("price" => json_encode($data_send));
		$params = array('device_type' => 1, 'device_token' => $device_token, 
				'data' => json_encode($data_send), 'time_queued' => time());
		if ($sendNow) {
			if ($this->sendAndroid($device_token, json_encode($data_send)))
				$params['time_sent'] = time();// g0choozi@15
		}
		$this->_CI->db->insert($this->push_table, $params);
	}
	
	public function addIOS($device_token, $data_send, $sendNow = false) {
		$body['aps'] = $data_send;
		$payload = json_encode($body);
	
		$params = array('device_type' => 2, 'device_token' => $device_token, 
				'data' => $payload, 'time_queued' => time());
		if ($sendNow) {
			if ($this->sendIOS($device_token, $payload))
				$params['time_sent'] = time();
		}
		$this->_CI->db->insert($this->push_table, $params);
	}
}

/*
 CREATE TABLE `notification_queue` (
 `message_id` int(11) NOT NULL AUTO_INCREMENT,
 `device_type` tinyint(1) NOT NULL,
 `device_token` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
 `data` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
 `time_queued` int(11) NOT NULL,
 `time_sent` int(11) NOT NULL DEFAULT '0',
 PRIMARY KEY (`message_id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 */
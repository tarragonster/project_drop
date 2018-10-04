<?php


class Test extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
	}

	public function api(){
		$product_id = 14;
		$this->db->where('data LIKE \'%"product_id":"'.$product_id.'"%\'');
		$this->db->delete('user_notify');
	}

	public function notify(){
		$path_pem_file = APPPATH . ('libraries/crts/push_ss.pem');
		// this is the pass phrase you defined when creating the key
		$passphrase = '123';


		$payload['aps'] = array('alert' => 'minh test', 'sound' => 'default');
		$payload = json_encode($payload);


		// this is where you can customize your notification
		if (!file_exists($path_pem_file)) {
			die('error file');
		}
		// start to create connection
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $path_pem_file);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

		$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err,
			$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

		// Build the binary notification
		if (!$fp) {
			die("Failed to connect: $err $errstr");
			return false;
		}

		$msg = chr(0) . pack('n', 32) . pack('H*', '2af729e8b3bed73a07483824eef7fd9de40de30f74f0226d427b9071ac550185') . pack('n', strlen($payload)) . $payload;


		$result = @fwrite($fp, $msg, strlen($msg));
		fclose($fp);
		if (!$result)
			die('Message not delivered');
		else
			die('Message successfully delivered');
	}

	public function contact() {
		$this->load->library('contact_lib');
		$this->contact_lib->migration();

		$this->contact_lib->pushContact(CONTACT_TYPE_FACEBOOK, 'adsshaeiuweojkcjklk');
	}
}
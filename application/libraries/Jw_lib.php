<?php
require_once('vendor/autoload.php');

class Jw_lib {
	private $_CI;
	private $apiKey = 'ANEWlewk';
	private $appSecret = 'enbWD8DpuFk0PQBYajaUojiv';

	public function __construct() {
	}

	public function getVideo($video_key) {
		$string = 'api_format=json&api_key=' . $this->apiKey . '&api_nonce=' . rand(1000, 99999) . '&api_timestamp=' . time() . '&video_key=' . $video_key;

		$api_signature = sha1($string . $this->appSecret);

		$url = 'http://api.jwplatform.com/v1/videos/show?' . $string . '&api_signature=' . $api_signature;

		$data = $this->get($url);
		if ($data != null) {
			return $data['video'];
		}
		return null;
	}

	public function getVideos($video_keys = '', $updated_after = 0) {
		$string = 'api_format=json&api_key=' . $this->apiKey . '&api_nonce=' . rand(1000, 99999) . '&api_timestamp=' . time() . '&result_limit=1000&updated_after=' . $updated_after;
		if (is_array($video_keys)) {
			$video_keys = implode(',', $video_keys);
		}
		if (!empty($video_keys)) {
			$string .= '&video_keys_filter=' . $video_keys;
		}
		$api_signature = sha1($string . $this->appSecret);

		$url = 'http://api.jwplatform.com/v1/videos/list?' . $string . '&api_signature=' . $api_signature;

		$data = $this->get($url);
		if ($data != null) {
			return $data['videos'];
		}
		return [];
	}

	public function getVideoCaptions($video_key) {
		$string = 'api_format=json&api_key=' . $this->apiKey . '&api_nonce=' . rand(1000, 99999) . '&api_timestamp=' . time() . '&kinds_filter=captions' . '&result_limit=1000&video_key='.$video_key;
		$api_signature = sha1($string . $this->appSecret);

		$url = 'http://api.jwplatform.com/v1/videos/tracks/list?' . $string . '&api_signature=' . $api_signature;

		$data = $this->get($url);
		if ($data != null) {
			return $data['tracks'];
		}
		return [];
	}

	protected function get($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$headers = ['Accept: application/json'];
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);

		$response = curl_exec($ch);
		$result = json_decode($response, true);
		curl_close($ch);
		if (!empty($result) && isset($result['status']) && $result['status'] == 'ok') {
			return $result;
		} else {
			if (isset($result['message'])) {
				log_message('error', 'JW: ' . $result['message']);
			} else {
				log_message('error', 'JW: Undefined');
			}
		}

		return null;
	}

}
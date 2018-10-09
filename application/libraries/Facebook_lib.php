<?php
require_once __DIR__ . '/vendor/autoload.php';

class Facebook_lib {
	protected $CI;
	protected $fb;

	public function __construct() {
		$this->CI = &get_instance();

		$this->fb = new \Facebook\Facebook([
			'app_id' => '332125194192690', // 1930529963734108
			'app_secret' => '7e205a6f19d96402ec36e8314d5facc6', // 2f26c9730931fbd26e5b821a9eb39135
		]);
	}

	protected function success($data = null) {
		return ['success' => true, 'data' => $data];
	}

	protected function error($error_message = 'Resource not found', $data = null) {
		return ['success' => false, 'error_message' => $error_message, 'data' => $data];
	}

	public function me($accessToken) {
		try {
			$response = $this->fb->get('/me?fields=id,email,name,first_name,last_name', $accessToken);
		} catch(\Facebook\Exceptions\FacebookResponseException $e) {
			return $this->error($e->getMessage(), $e->getCode());
		} catch(\Facebook\Exceptions\FacebookSDKException $e) {
			return $this->error($e->getMessage(), $e->getCode());
		}

		$me = $response->getGraphUser();
		$params = [
			'id' => $me->getId(),
			'email' => $me->getEmail(),
			'name' => $me->getName(),
			'first_name' => $me->getFirstName(),
			'last_name' => $me->getLastName(),
		];
		return $this->success($params);
	}

	public function longLive($accessToken) {
		$client = $this->fb->getOAuth2Client();
		try {
			// Returns a long-lived access token
			$accessTokenLong = $client->getLongLivedAccessToken($accessToken);
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			return $this->error($e->getMessage(), $e->getCode());
		}
		$params = [
			'token' => $accessTokenLong->getValue(),
			'expired_at' => $accessTokenLong->getExpiresAt()->getTimestamp(),
		];
		return $this->success($params);
	}

	public function friends($accessToken) {
		try {
			$response = $this->fb->get('/me/friends?fields=id,email,name,first_name,last_name', $accessToken);
		} catch(\Facebook\Exceptions\FacebookResponseException $e) {
			return $this->error($e->getMessage(), $e->getCode());
		} catch(\Facebook\Exceptions\FacebookSDKException $e) {
			return $this->error($e->getMessage(), $e->getCode());
		}
		$data = $response->getDecodedBody();
		return $this->success(['friends' => $data['data']]);
	}
}
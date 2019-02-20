<?php
require_once('vendor/autoload.php');

class Stripe_lib {
	private $_CI;
	private $api_key;
	private $public_key;
	private $errors;

	public function __construct() {
		$this->_CI =& get_instance();
		$this->_CI->config->load('stripe', TRUE);
		$config = $this->_CI->config->item('stripe');
		$this->_CI->config->load('stripe_decline_codes', TRUE);
		$this->errors = $this->_CI->config->item('stripe_decline_codes');

		$this->api_key = $config['api_key'];
		\Stripe\Stripe::setApiKey($this->api_key);
	}

	public function getPublicKey() {
		return $this->public_key;
	}

	public function updateCustomerWithToken($user, $token) {
		try {
			$customer = \Stripe\Customer::retrieve($user['stripe_customer']);
			$card = $customer->sources->create(['source' => $token]);
			$customer->default_source = $card->id;
			$customer->save();
			return $this->success($card);
		} catch (Exception $e) {
			return $this->handleExp($e);
		}
	}

	public function createCustomerWithToken($user, $token) {
		try {
			$customer = \Stripe\Customer::create(array(
				"description" => "Customer for " . $user['email'],
				"email" => $user['email'],
				"source" => $token,
			));
			return $this->success($customer->sources->data[0]);
		} catch (Exception $e) {
			return $this->handleExp($e);
		}
	}

	public function createSubscription($stripe_customer) {
		try {
			$metadata = ['domain' => $_SERVER['HTTP_HOST']];

			$subscription = \Stripe\Subscription::create([
				"customer" => $stripe_customer,
				"items" => [
					[
						"plan" => "10-block-premium",
					],
				],
				"metadata" => $metadata,
			]);

			return $this->success($subscription);
		} catch (Exception $e) {
			return $this->handleExp($e);
		}
	}

	public function deleteCard($card) {
		try {
			$customer = \Stripe\Customer::retrieve($card['stripe_customer']);
			$customer->sources->retrieve($card['stripe_card'])->delete();
			return $this->success();
		} catch (Exception $e) {
			return $this->handleExp($e);
		}
	}

	public function getCustomer($user) {
		try {
			if (!empty($user['stripe_customer'])) {
				$customer = \Stripe\Customer::retrieve($user['stripe_customer'])->jsonSerialize();
				return $this->success($customer);
			}
		} catch (Exception $e) {
			return $this->handleExp($e);
		}
	}

	public function getSubscription($subscriptionId) {
		try {
			$subscription = \Stripe\Subscription::retrieve($subscriptionId);
			return $this->success($subscription);
		} catch (Exception $e) {
			return $this->handleExp($e);
		}
	}

	protected function success($data = null) {
		return ['success' => true, 'data' => $data];
	}

	protected function error($error_message = 'Resource not found', $data = null) {
		return ['success' => false, 'error_message' => $error_message, 'data' => $data];
	}

	private function handleExp($e) {
		log_message('error', 'Stripe: ' . $e->getMessage());
		if ($e instanceof Stripe\Error\Base && $e->getJsonBody() != null) {
			$body = $e->getJsonBody();
			if (isset($body['error'])) {
				return $this->error($this->getErrorMessage($body['error']['code']));
			}
		}
		return $this->error($e->getMessage());
	}

	private function getErrorMessage($code) {
		if (array_key_exists($code, $this->errors)) {
			return $this->errors[$code];
		}
		return 'Your request could not be processed now.';
	}
}
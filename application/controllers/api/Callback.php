<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class Callback extends BR_Controller {

	public function subscription_post() {
		$event = $this->post('data');
		if (empty($event) || !isset($event['object'])) {
			$this->create_error(-401, 'Empty event');
		}
		$data = $event['object'];
		if (empty($data) || !isset($data['customer']) || !isset($data['id'])) {
			$this->create_error(-401, 'Access denined');
		}
		$user = $this->user_model->getBy('stripe_customer', $data['customer']);
		if ($user == null) {
			$this->create_error(-404, 'Not found customer');
		}

		$this->load->library('stripe_lib');
		$stripeResponse = $this->stripe_lib->getSubscription($data['id']);

		if ($stripeResponse['success']) {
			$subscription = $stripeResponse['data'];
			if ($subscription['customer'] == $data['customer']) {
				// pre_print($subscription);
				$canceled_at = empty($subscription->canceled_at) ? 0 : ($subscription->cancel_at_period_end ? $subscription->cancel_at : $subscription->canceled_at);
				$this->user_model->update([
					'subscription_id' => $subscription->id,
					'current_period_start' => $subscription->current_period_start,
					'current_period_end' => $subscription->current_period_end,
					'canceled_at' => $canceled_at,
				], $user['user_id']);
				$this->create_success();
			} else {
				$this->create_error(-401, 'Customer not match');
			}
		} else {
			$this->user_model->update([
				'subscription_id' => null,
				'current_period_start' => 0,
				'current_period_end' => 0,
				'canceled_at' => 0,
			], $user['user_id']);
		}

		$this->create_error(-40, 'Unknow');
	}
}
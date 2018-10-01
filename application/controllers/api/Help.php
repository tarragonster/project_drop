<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class Help extends BR_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('help_model');
	}

	public function questions_get() {
		$questions = $this->help_model->getQuestions();

		$response = [
			'questions' => $questions
		];
		$this->create_success($response);
	}
}
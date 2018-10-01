<?php

require_once APPPATH . '/core/BaseModel.php';

class Help_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'questions';
		$this->id_name = 'question_id';
	}

	public function getQuestions($page = -1) {
		return $this->getList($page, 'priority', 'asc');
	}

}
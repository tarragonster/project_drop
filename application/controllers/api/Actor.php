<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class Actor extends BR_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('cast_model');
	}

	public function get_get($actor_id = -1) {
		$actor = $this->cast_model->getCastById($actor_id);
		$actor['films'] = $this->cast_model->getListFilms($actor_id);
		$this->create_success(array('actor' => $actor));
	}
}
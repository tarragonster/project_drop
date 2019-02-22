<?php

require_once APPPATH . '/core/Base_Controller.php';

class Featured extends Base_Controller {

	public function __construct() {
		parent::__construct();
		$this->verifyAdmin();

		$this->load->model("featured_model");
	}

	public function index() {
		$layoutParams = [];
		$layoutParams['users'] = $this->featured_model->getListUsers();
		$layoutParams['other_users'] = $this->featured_model->getOtherUsers();
		$layoutParams['max'] = $this->featured_model->getMaxProfile();

//		pre_print($layoutParams);
		$data = array();
		$data['parent_id'] = 2;
		$data['sub_id'] = 24;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/users/featured_list', $layoutParams, true);;
		$this->load->view('admin_main_layout', $data);
	}

	public function addProfile($user_id) {
		$product = $this->user_model->get($user_id);
		if ($product == null) {
			redirect('featured');
		}
		$this->featured_model->addProfile($user_id);
		redirect(make_url('admin/featured', ['active' => 'add']));
	}

	public function removeProfile($user_id) {
		$this->featured_model->removeProfile($user_id);
		redirect('featured');
	}

	public function upProfile($user_id) {
		$this->featured_model->upProfile($user_id);
		redirect('featured');
	}

	public function downProfile($user_id) {
		$this->featured_model->downProfile($user_id);
		redirect('featured');
	}
}
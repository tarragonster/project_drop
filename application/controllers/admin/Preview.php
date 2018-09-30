<?php

require_once APPPATH . '/core/Base_Controller.php';

class Preview extends Base_Controller {

	public function __construct() {
		parent::__construct();
		$this->verifyAdmin();


		$this->load->model("preview_model");
		$this->load->model("product_model");
	}

	public function index() {
		$layoutParams = [];
		$layoutParams['products'] = $this->preview_model->getListProducts();
		$layoutParams['others'] = $this->preview_model->getProductOthers();
		$layoutParams['max'] = $this->preview_model->getMaxFilm();
		$data = array();
		$data['parent_id'] = 10;
		$data['sub_id'] = 101;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/preview/preview_list', $layoutParams, true);;
		$this->load->view('admin_main_layout', $data);
	}

	public function addFilm($product_id) {
		$product = $this->product_model->get($product_id);
		if ($product == null) {
			redirect('admin/preview');
		}
		$this->preview_model->addFilm($product_id);
		redirect(make_url('admin/preview', ['active' => 'add']));
	}

	public function removeFilm($product_id) {
		$this->preview_model->removeFilm($product_id);
		redirect('admin/preview');
	}

	public function upFilm($product_id) {
		$this->preview_model->upFilm($product_id);
		redirect('admin/preview');
	}

	public function downFilm($product_id) {
		$this->preview_model->downFilm($product_id);
		redirect('admin/preview');
	}
}
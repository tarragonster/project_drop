<?php

require_once APPPATH . '/core/Base_Controller.php';

class Preview extends Base_Controller {

	public function __construct() {
		parent::__construct();
		$this->verifyAdmin();


		$this->load->model("collection_model");
	}

	public function index() {
		$collection = $this->collection_model->getCollectionById($collection_id);
		if ($collection == null) {
			redirect('admin/collection');
		}

		$this->load->model("product_model");
		$collection['products'] = $this->product_model->getListProductByCollection($collection_id);
		$collection['others'] = $this->collection_model->getProductOthers($collection_id);
		$collection['max'] = $this->collection_model->getMaxFilm($collection_id);
		$data = array();
		$data['parent_id'] = 4;
		$data['sub_id'] = 41;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/collection_films', $collection, true);;
		$this->load->view('admin_main_layout', $data);
	}

	public function addFilm($product_id, $collection_id) {
		$collection = $this->collection_model->getCollectionById($collection_id);
		if ($collection == null) {
			redirect('admin/collection');
		}
		$params = array();
		$params['collection_id'] = $collection_id;
		$params['product_id'] = $product_id;
		$params['priority'] = $this->collection_model->getMaxFilm($collection_id) + 1;
		$this->collection_model->addFilm($params);
		redirect(base_url('admin/collection/films/' . $collection_id));
	}

	public function removeFilm($collection_id, $product_id, $priority) {
		$collection = $this->collection_model->getCollectionById($collection_id);
		if ($collection == null) {
			redirect('admin/collection');
		}
		$this->collection_model->removeFilm($collection_id, $product_id, $priority);
		redirect(base_url('admin/collection/films/' . $collection_id));
	}

	public function upFilm($collection_id, $priority, $id) {
		$collection = $this->collection_model->getCollectionById($collection_id);
		if ($collection == null) {
			redirect('admin/collection');
		}
		$this->collection_model->upFilm($collection_id, $priority, $id);
		redirect(base_url('admin/collection/films/' . $collection_id));
	}

	public function downFilm($collection_id, $priority, $id) {
		$collection = $this->collection_model->getCollectionById($collection_id);
		if ($collection == null) {
			redirect('admin/collection');
		}
		$this->collection_model->downFilm($collection_id, $priority, $id);
		redirect(base_url('admin/collection/films/' . $collection_id));
	}
}
<?php

require_once APPPATH . '/core/Base_Controller.php';

class Collection extends Base_Controller {

	public function __construct() {
		parent::__construct();
		$this->verifyAdmin();
		$this->load->model("collection_model");
	}

	public function index() {
		$collections = $this->collection_model->getCollections();
		$content = $this->load->view('admin/collection_list', array('collections' => $collections), true);
		$data = array();
		$data['parent_id'] = 4;
		$data['sub_id'] = 41;
		$data['account'] = $this->account;
		$data['content'] = $content;
		$this->load->view('admin_main_layout', $data);
	}

	public function add() {
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$params = array();
			$params['name'] = $this->input->post('shop_name');
			$params['short_bio'] = $this->input->post('short_bio');
			$params['priority'] = $this->collection_model->getMax() + 1;
			$params['created'] = time();
			$this->collection_model->insert($params);
			redirect(base_url('admin/collection'));
		}

		$data = array();
		$data['parent_id'] = 4;
		$data['sub_id'] = 41;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/collection_add', array(), true);
		$this->load->view('admin_main_layout', $data);
	}

	public function edit($collection_id) {
		$collection = $this->collection_model->getCollectionById($collection_id);
		if ($collection == null) {
			redirect('admin/collection');
		}
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$params = array();
			$params['name'] = $this->input->post('name');
			$params['short_bio'] = $this->input->post('short_bio');
			$this->collection_model->update($params, $collection_id);
			redirect(base_url('admin/collection'));
		}
		$data = array();
		$data['parent_id'] = 4;
		$data['sub_id'] = 41;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/collection_edit', $collection, true);;
		$this->load->view('admin_main_layout', $data);
	}

	public function films($collection_id) {
		$collection = $this->collection_model->getCollectionById($collection_id);
		if ($collection == null) {
			redirect('admin/collection');
		}

		$this->load->model("product_model");
		$content_layout = '';
		if ($collection['collection_type'] == 1 || $collection['collection_type'] == 5) {
			$collection['products'] = $this->product_model->getListProductByCollection($collection_id);
			$collection['others'] = $this->collection_model->getProductOthers($collection_id);
			$collection['max'] = $this->collection_model->getMaxFilm($collection_id);
			$content_layout = 'collection_films';
		} else if ($collection['collection_type'] == 3) {
			$collection['products'] = [];
			$content_layout = 'collection_continue';
		} else {
			$collection['products'] = $this->user_model->getTopPicks();
			$content_layout = 'collection_top_picks';
		}

		$data = array();
		$data['customCss'] = array('assets/css/jquery-ui.css', 'assets/css/settings.css', 'assets/css/smoothness.jquery-ui.css');
		$data['customJs'] = array('assets/js/jquery-ui.js', 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js', 'assets/app/length.js', 'assets/app/feed_autocomplete.js');

		$data['parent_id'] = 4;
		$data['sub_id'] = 41;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/' . $content_layout, $collection, true);;
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

	public function addToCollection() {
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$collection_id = $this->input->post('collection_id') * 1;
			$product_id= $this->input->post('product_id') * 1;

			$collection = $this->collection_model->getCollectionById($collection_id);
			if ($collection == null) {
				redirect('admin/collection');
			}

			$this->load->model("product_model");
			$product = $this->product_model->getProductForAdmin($product_id);
			if ($product == null) {
				redirect('admin/collection');
			}

			$previewImg = isset($_FILES['preview_img']) ? $_FILES['preview_img'] : null;
			if ($previewImg != null && $previewImg['error'] == 0) {
				$this->load->model('file_model');
				$path = $this->file_model->createFileName($previewImg, 'media/feeds/', 'collection');
				$this->file_model->saveFile($previewImg, $path);
				$params = [
					'product_id' => $product_id,
					'collection_id' => $collection_id,
					'preview_img' => $path,
					'priority' => $this->collection_model->getMaxFilm($collection_id) + 1,
				];
				$this->collection_model->addFilm($params);
			} else {
				$this->session->set_flashdata('msg', 'Missing data field required: image');
			}
			redirect('admin/collection/films/' . $collection_id);
		}
		redirect('admin/collection');
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
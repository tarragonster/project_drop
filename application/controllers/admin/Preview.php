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
		$data['customCss'] = array('assets/css/jquery-ui.css', 'assets/css/settings.css', 'assets/css/smoothness.jquery-ui.css');
		$data['customJs'] = array('assets/js/jquery-ui.js', 'assets/app/edit_preview.js');

		$data['parent_id'] = 10;
		$data['sub_id'] = 101;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/preview/preview_list', $layoutParams, true);;
		$this->load->view('admin_main_layout', $data);
	}

	public function addFilm() {
		$product_id = $this->input->post('product_id') * 1;

		$this->load->model("product_model");
		$product = $this->product_model->getProductForAdmin($product_id);
		if ($product == null) {
			redirect('admin/preview');
		}

		$promoImage = isset($_FILES['promo_image']) ? $_FILES['promo_image'] : null;
		if ($promoImage != null && $promoImage['error'] == 0) {
			$this->load->model('file_model');
			$path = $this->file_model->createFileName($promoImage, 'media/feeds/', 'preview');
			$this->file_model->saveFile($promoImage, $path);
			$this->preview_model->addFilm($product_id, $path);
		}

		redirect('admin/preview');
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

	public function ajaxProduct() {
		$q = $this->input->get('q');
		$products = $this->preview_model->getProductOthers($q);
		$items = array();
		if (is_array($products)) {
			foreach ($products as $row) {
				$item = array('value' => $row['product_id'], 'label' => $row['name'],);
				$items[] = $item;
			}
		}
		header('Content-Type: application/json');
		echo json_encode($items);
	}

	public function editFilm($id) {
		$product = $this->preview_model->getProductPreview($id);
		if ($product == null) {
			redirect('admin/collection');
		}
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$product_id = $this->input->post('product_id') * 1;

			$this->load->model("product_model");
			$product = $this->product_model->getProductForAdmin($product_id);
			if ($product == null) {
				redirect('admin/collection');
			}

			$params = ['product_id' => $product_id];
			$promoImage = isset($_FILES['promo_image']) ? $_FILES['promo_image'] : null;
			if ($promoImage != null && $promoImage['error'] == 0) {
				$this->load->model('file_model');
				$path = $this->file_model->createFileName($promoImage, 'media/feeds/', 'preview');
				$this->file_model->saveFile($promoImage, $path);
				$params['promo_image'] = $path;
			}
			$this->preview_model->update($params, $id);
			redirect('admin/preview');
		}

		$content = $this->load->view('admin/preview/preview_edit', $product, true);
		$data = array();
		$data['parent_id'] = 10;
		$data['sub_id'] = 101;
		$data['account'] = $this->account;
		$data['content'] = $content;

		$data['customCss'] = array('assets/css/jquery-ui.css', 'assets/css/settings.css', 'assets/css/smoothness.jquery-ui.css');
		$data['customJs'] = array('assets/js/jquery-ui.js', 'assets/app/edit_preview.js');
		$this->load->view('admin_main_layout', $data);
	}
}
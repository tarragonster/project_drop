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
			$params['name'] = $this->input->post('name');
			$params['short_bio'] = $this->input->post('short_bio');
			$params['priority'] = $this->collection_model->getMax() + 1;
			$params['created'] = time();
			$this->collection_model->insert($params);
			redirect(base_url('collection'));
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
			redirect('collection');
		}
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$params = array();
			$params['name'] = $this->input->post('name');
			$params['short_bio'] = $this->input->post('short_bio');
			$this->collection_model->update($params, $collection_id);
			redirect(base_url('collection'));
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
			redirect('collection');
		}

		$this->load->model("product_model");
		$content_layout = '';
		if ($collection['collection_type'] == 3) {
			$collection['products'] = [];
			$content_layout = 'collection_continue';
		} else {
			if ($collection['collection_type'] == 4) {
				$collection['products'] = $this->user_model->getTopPicks();
				$content_layout = 'collection_top_picks';
			} else {
				$collection['products'] = $this->product_model->getListProductByCollection($collection_id);
				$collection['others'] = $this->collection_model->getProductOthers($collection_id);
				$collection['max'] = $this->collection_model->getMaxFilm($collection_id);
				$content_layout = 'collection_films';
			}
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

	public function addToCollection() {
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$collection_id = $this->input->post('collection_id') * 1;
			$product_id = $this->input->post('product_id') * 1;

			$collection = $this->collection_model->getCollectionById($collection_id);
			if ($collection == null) {
				redirect('collection');
			}

			$this->load->model("product_model");
			$product = $this->product_model->getProductForAdmin($product_id);
			if ($product == null) {
				redirect('collection');
			}

			$promoImage = isset($_FILES['promo_image']) ? $_FILES['promo_image'] : null;
			if ($promoImage != null && $promoImage['error'] == 0) {
				$this->load->model('file_model');
				$path = $this->file_model->createFileName($promoImage, 'media/feeds/', 'collection');
				$this->file_model->saveFile($promoImage, $path);
				$params = [
					'product_id' => $product_id,
					'collection_id' => $collection_id,
					'promo_image' => $path,
					'priority' => $this->collection_model->getMaxFilm($collection_id) + 1,
				];
				$this->collection_model->addFilm($params);
			} else {
				$this->session->set_flashdata('msg', 'Missing data field required: image');
			}
			redirect('collection/films/' . $collection_id);
		}
		redirect('collection');
	}

	public function editPromo($id) {
		$product = $this->collection_model->getProductCollection($id);
		if ($product == null) {
			redirect('collection');
		}
		$collection_id = $product['collection_id'];
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$product_id = $this->input->post('product_id') * 1;

			$this->load->model("product_model");
			$product = $this->product_model->getProductForAdmin($product_id);
			if ($product == null) {
				redirect('collection');
			}

			$params = ['product_id' => $product_id];
			$promoImage = isset($_FILES['promo_image']) ? $_FILES['promo_image'] : null;
			if ($promoImage != null && $promoImage['error'] == 0) {
				$this->load->model('file_model');
				$path = $this->file_model->createFileName($promoImage, 'media/feeds/', 'collection');
				$this->file_model->saveFile($promoImage, $path);
				$params['promo_image'] = $path;
			}
			$this->collection_model->updateFilm($params, $id);
			redirect('collection/films/' . $collection_id);
		}

		$content = $this->load->view('admin/collection_product_edit', $product, true);
		$data = array();
		$data['parent_id'] = 4;
		$data['sub_id'] = 41;
		$data['account'] = $this->account;
		$data['content'] = $content;

		$data['customCss'] = array('assets/css/jquery-ui.css', 'assets/css/settings.css', 'assets/css/smoothness.jquery-ui.css');
		$data['customJs'] = array('assets/js/jquery-ui.js', 'assets/app/feed_autocomplete.js');
		$this->load->view('admin_main_layout', $data);
	}

	public function removeFilm($collection_id, $product_id, $priority) {
		$collection = $this->collection_model->getCollectionById($collection_id);
		if ($collection == null) {
			redirect('collection');
		}
		$this->collection_model->removeFilm($collection_id, $product_id, $priority);
		redirect(base_url('collection/films/' . $collection_id));
	}

	public function upFilm($collection_id, $priority, $id) {
		$collection = $this->collection_model->getCollectionById($collection_id);
		if ($collection == null) {
			redirect('collection');
		}
		$this->collection_model->upFilm($collection_id, $priority, $id);
		redirect(base_url('collection/films/' . $collection_id));
	}

	public function downFilm($collection_id, $priority, $id) {
		$collection = $this->collection_model->getCollectionById($collection_id);
		if ($collection == null) {
			redirect('collection');
		}
		$this->collection_model->downFilm($collection_id, $priority, $id);
		redirect(base_url('collection/films/' . $collection_id));
	}

	public function ajaxProduct($collection_id = 0) {
		$q = $this->input->get('q');
		if (!empty($q)) {
			$this->db->like('p.name', $q, 'both');
		}
		$this->db->from('product p');
		if ($collection_id > 0) {
			$this->db->join("(select * from collection_product where collection_id = $collection_id) as cp", 'cp.product_id = p.product_id', 'left');
			$this->db->where('cp.id is null');
		}
		$this->db->select('p.product_id, p.name');
		$this->db->where('p.status', 1);
		$this->db->order_by('p.name', 'asc');
		$this->db->limit(15);

		$query = $this->db->get();
		$products = $query->result_array();
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
}
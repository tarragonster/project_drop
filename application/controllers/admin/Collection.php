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
		if ($collection['collection_type'] == COLLECTION_TYPE_CONTINUE_WATCHING) {
			$collection['products'] = [];
			$content_layout = 'collection_continue';
		} else {
			if (in_array($collection['collection_type'], [COLLECTION_TYPE_CONTINUE_WATCHING, COLLECTION_TYPE_TOP_PICKS, COLLECTION_TYPE_FRIEND_WATCHING, COLLECTION_TYPE_SUGGESTED_USERS])) {
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

			$params = [
				'product_id' => $product_id,
				'collection_id' => $collection_id,
				'priority' => $this->collection_model->getMaxFilm($collection_id) + 1,
			];
			$promoImage = isset($_FILES['promo_image']) ? $_FILES['promo_image'] : null;
			if ($promoImage != null && $promoImage['error'] == 0) {
				$this->load->model('file_model');
				$path = $this->file_model->createFileName($promoImage, 'media/feeds/', 'collection');
				$params['promo_image'] = $path;
				$this->file_model->saveFile($promoImage, $path);
			} else {
				$params['promo_image'] = $product['image'];
			}

			$this->collection_model->addFilm($params);
			if ($collection_id == 1) {
				$this->load->model('notify_model');
				$this->notify_model->sendToAllUser(59, ['story_name' => $params['name'], 'product_id' => $product_id]);
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

	//==================================================================================================================
	public function sortable($collection_id) {
		$collection = $this->collection_model->getCollectionById($collection_id);
		if ($collection == null) {
			redirect('collection');
		}

		header('Content-Type: application/json');
		$response = ['success' => false];
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$positions = $this->input->post('positions');

			$product_ids = array_keys($positions);
			foreach ($product_ids as $key => $product_id) {
				$this->collection_model->updatePriority($collection_id, $product_id, $key + 1);
			}
			$response['success'] = true;
		}
		echo json_encode($response);
	}

	public function carousel() {
		$this->load->model("product_model");
		$this->load->library('hash');
		$carousel_products = $this->product_model->getListProductByCollection(5);
		if ($carousel_products == null) {
			$params['page_index'] = 'empty_carousel';
		} else {
			$preview_ids = Hash::combine($carousel_products, '{n}.product_id', '{n}.product_id');

			$comments = $this->product_model->getAllProductComments($preview_ids);
			$comments = Hash::combine($comments, '{n}.comment_id', '{n}', '{n}.product_id');

			$likes = $this->product_model->getAllLike($preview_ids);
			$likes = Hash::combine($likes, '{n}.id', '{n}', '{n}.product_id');

			$picks = $this->product_model->getAllPick($preview_ids);
			$picks = Hash::combine($picks, '{n}.pick_id', '{n}', '{n}.product_id');

			foreach ($carousel_products as $key => $value) {
				$carousel_products[$key]['comments'] = !empty($comments[$value['product_id']]) ? $comments[$value['product_id']] : [];
				$carousel_products[$key]['total_cmt'] = count($carousel_products[$key]['comments']);

				$carousel_products[$key]['likes'] = !empty($likes[$value['product_id']]) ? $likes[$value['product_id']] : [];
				$carousel_products[$key]['total_like'] = count($carousel_products[$key]['likes']);

				$carousel_products[$key]['picks'] = !empty($picks[$value['product_id']]) ? $picks[$value['product_id']] : [];
				$carousel_products[$key]['total_pick'] = count($carousel_products[$key]['picks']);
			}
			$params = [
				'page_index' => 'collection_carousel',
				'page_base' => 'collection/carousel',
				'carousel_products' => $carousel_products
			];
		}
		$this->customCss[] = 'module/css/submenu.css';
		$this->customCss[] = 'module/css/genre.css';
		$this->customCss[] = 'module/css/explore.css';
		$this->customJs[] = 'module/js/coreTable.js';
		$this->customJs[] = 'module/js/collection_carousel.js';
		$this->render('/collection/collection_layout', $params, 4, 41);
	}

	public function trending() {
		$this->load->model("product_model");
		$this->load->library('hash');
		$trending_products =  $this->product_model->getListProductByCollection(1);
		if ($trending_products == null) {
			$params['page_index'] = 'empty_trending';
		} else {
			$preview_ids = Hash::combine($trending_products, '{n}.product_id', '{n}.product_id');

			$comments = $this->product_model->getAllProductComments($preview_ids);
			$comments = Hash::combine($comments, '{n}.comment_id', '{n}', '{n}.product_id');

			$likes = $this->product_model->getAllLike($preview_ids);
			$likes = Hash::combine($likes, '{n}.id', '{n}', '{n}.product_id');

			$picks = $this->product_model->getAllPick($preview_ids);
			$picks = Hash::combine($picks, '{n}.pick_id', '{n}', '{n}.product_id');

			foreach ($trending_products as $key => $value) {
				$trending_products[$key]['comments'] = !empty($comments[$value['product_id']]) ? $comments[$value['product_id']] : [];
				$trending_products[$key]['total_cmt'] = count($trending_products[$key]['comments']);

				$trending_products[$key]['likes'] = !empty($likes[$value['product_id']]) ? $likes[$value['product_id']] : [];
				$trending_products[$key]['total_like'] = count($trending_products[$key]['likes']);

				$trending_products[$key]['picks'] = !empty($picks[$value['product_id']]) ? $picks[$value['product_id']] : [];
				$trending_products[$key]['total_pick'] = count($trending_products[$key]['picks']);
			}
			$params = [
				'page_index' => 'collection_trending',
				'page_base' => 'collection/trending',
				'trending_products' => $trending_products
			];
		}
		$this->customCss[] = 'module/css/submenu.css';
		$this->customCss[] = 'module/css/genre.css';
		$this->customCss[] = 'module/css/explore.css';
		$this->customJs[] = 'module/js/coreTable.js';
		$this->customJs[] = 'module/js/collection_carousel.js';
		$this->render('/collection/collection_layout', $params, 4, 41);
	}
}
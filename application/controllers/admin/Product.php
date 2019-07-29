<?php

require_once APPPATH . '/core/Base_Controller.php';

class Product extends Base_Controller {
	public function __construct() {
		parent::__construct();
		$this->verifyAdmin();

		$this->load->model("product_model");
		$this->load->model("story_genres_model");
		$this->load->model("product_genres_model");
	}

	public function index($page = 1) {
		$this->load->library('pagination');
		$config['base_url'] = base_url('product');
		$config['total_rows'] = $this->product_model->countAll();
		$config['per_page'] = PERPAGE_ADMIN;
		$config['cur_page'] = $page;
		$config['use_page_numbers'] = TRUE;
		$config['add_query_string'] = TRUE;
		$this->pagination->initialize($config);

		$pinfo = array(
			'from' => PERPAGE_ADMIN * ($page - 1) + 1,
			'to' => min(array(PERPAGE_ADMIN * $page, $config['total_rows'])),
			'total' => $config['total_rows'],
		);

		$products = $this->product_model->getProductListForAdmin($page - 1);
		foreach ($products as $key => $value) {
			$products[$key]['genres'] = $this->product_genres_model->getAll($products[$key]['product_id']);
			$arrGenre = array();
			foreach ($products[$key]['genres'] as $keyG => $value) {
				$arrGenre[$keyG] = $products[$key]['genres'][$keyG]['genre_name'];
			}
			$products[$key]['genre'] = implode(', ', $arrGenre);
		}
		$data['parent_id'] = 3;
		$data['sub_id'] = 32;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/product_list', array('products' => $products,'pinfo' => $pinfo), true);
		$data['customJs'] = array('assets/plugins/sweetalert/dist/sweetalert.min.js','assets/app/delete-confirm.js', 'assets/js/settings.js', 'assets/app/search.js');
		$data['customCss'] = array('assets/plugins/sweetalert/dist/sweetalert.css', 'assets/css/settings.css');
		$this->load->view('admin_main_layout', $data);
	}

	public function add() {
		$this->load->model("collection_model");
		$this->load->model('preview_model');
		$cmd = $this->input->post('cmd');
		if ($cmd != '') {
			$params = array();
			$params['name'] = $this->input->post('name');
			$params['description'] = $this->input->post('description');
			$params['publish_year'] = $this->input->post('publish_year');
			$params['rate_id'] = $this->input->post('rate_id');
			$params['creators'] = $this->input->post('creators');
			$params['status'] = 1;
			$params['jw_media_id'] = trim($this->input->post('jw_media_id'));
			$genres = $this->input->post('genre_id');
			
			$jw_media_id = $this->input->post('jw_media_id');
			if (!empty($jw_media_id)) {
				$this->load->library('jw_lib');
				$video = $this->jw_lib->getVideo($jw_media_id);
				if ($video != null) {
					$params['total_time'] = $video['duration'];
				}

				$params['jw_media_id'] = $jw_media_id;
			}

			$params['priority'] = $this->product_model->getMaxPriority() + 1;
			$params['created'] = time();

			$image = isset($_FILES['poster_img']) ? $_FILES['poster_img'] : null;
			$this->load->model('file_model');
			if ($image != null && $image['error'] == 0) {
				$path = $this->file_model->createFileName($image, 'media/films/', 'film');
				$this->file_model->saveFile($image, $path);
				$params['image'] = $path;
			}

			$background_img = isset($_FILES['series_img']) ? $_FILES['series_img'] : null;
			if ($background_img != null && $background_img['error'] == 0) {
				$path = $this->file_model->createFileName($background_img, 'media/films/', 'background');
				$this->file_model->saveFile($background_img, $path);
				$params['background_img'] = $path;
			}

			$carousel_img = isset($_FILES['carousel_img']) ? $_FILES['carousel_img'] : null;
			if ($carousel_img != null && $carousel_img['error'] == 0) {
				$path = $this->file_model->createFileName($carousel_img, 'media/films/', 'carousel');
				$this->file_model->saveFile($carousel_img, $path);
				$params['trailler_image'] = $path;
			}
			$product_id = $this->product_model->insert($params);

			$this->load->model('notify_model');
			$this->notify_model->sendToAllUser(58, ['story_name' => $params['name'], 'product_id' => $product_id]);

			$preview_img = isset($_FILES['preview_img']) ? $_FILES['preview_img'] : null;
			if ($preview_img != null && $preview_img['error'] == 0) {
				$collection_id = 2; // Preview
				$path = $this->file_model->createFileName($preview_img, 'media/films/', 'preview');
				$this->file_model->saveFile($preview_img, $path);
				$data_collection = array(
					'collection_id' => $collection_id,
					'product_id' => $product_id,
					'promo_image' => $path,
					'priority' => $this->collection_model->getMaxFilm($collection_id) + 1
				);
				$this->collection_model->addFilmPreviews($data_collection);
			}

			$explore_img = isset($_FILES['explore_img']) ? $_FILES['explore_img'] : null;
			if ($explore_img != null && $explore_img['error'] == 0) {
				$path = $this->file_model->createFileName($explore_img, 'media/films/', 'explore');
				$this->file_model->saveFile($explore_img, $path);
				$this->preview_model->addFilm($product_id, $path);
			}
			foreach ($genres as $item) {
				$paramsGenre = array(
					'product_id' => $product_id,
					'genre_id' => $item,
					'added_at' => time()
				);
				$this->product_genres_model->insert($paramsGenre);
			}
			
			if ($cmd == 'Save') {
				$this->session->set_flashdata('msg', 'Add success!');
				redirect(base_url('product'));
			}
		}
		
		$rates = $this->product_model->getRates();
		$genres = $this->story_genres_model->getAll();
		$data['parent_id'] = 3;
		$data['sub_id'] = 31;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/product_add', array(
			'rates' => $rates,
			'genres' => $genres
		), true);
		$data['customJs'] = array('assets/js/settings.js', 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js', 'assets/app/length.js', 'assets/app/info_video.js');
		$data['customCss'] = array('assets/css/settings.css', 'assets/css/smoothness.jquery-ui.css');
		$this->load->view('admin_main_layout', $data);
	}

	public function edit($product_id) {
		$this->load->model("collection_model");
		$this->load->model('preview_model');
		$this->load->model('episode_model');
		$collection_id = 2;
		$explore_product = $this->preview_model->getFilm($product_id);
		$collection_product = $this->collection_model->getCollectionProducts($collection_id,$product_id);
		$product = $this->product_model->getProductForAdmin($product_id);
		if ($product == null) {
			redirect('product');
		}
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$params = array();
			if ($this->input->post('name') != '')
				$params['name'] = $this->input->post('name');
			if ($this->input->post('description') != '')
				$params['description'] = $this->input->post('description');
			if ($this->input->post('publish_year') != '')
				$params['publish_year'] = $this->input->post('publish_year');
			if ($this->input->post('creators') != '')
				$params['creators'] = $this->input->post('creators');

			$jw_media_id = trim($this->input->post('jw_media_id'));
			if (!empty($jw_media_id) && $jw_media_id != $product['jw_media_id']) {
				$this->load->library('jw_lib');
				$video = $this->jw_lib->getVideo($jw_media_id);
				if ($video != null) {
					$params['total_time'] = $video['duration'];
				}

				$params['jw_media_id'] = $jw_media_id;
			}
			if ($this->input->post('rate_id') != '')
				$params['rate_id'] = $this->input->post('rate_id');

			if ($this->input->post('paywall_episode') != '') {
				$params['paywall_episode'] = $this->input->post('paywall_episode') * 1;
			} else {
				$params['paywall_episode'] = 0;
			}
			$image = isset($_FILES['poster_img']) ? $_FILES['poster_img'] : null;
			$this->load->model('file_model');
			if ($image != null && $image['error'] == 0) {
				$image_path = $this->file_model->createFileName($image, 'media/films/', 'film');
				$this->file_model->saveFile($image, $image_path);
				$params['image'] = $image_path;
			}

			$background_img = isset($_FILES['series_img']) ? $_FILES['series_img'] : null;
			if ($background_img != null && $background_img['error'] == 0) {
				$background_path = $this->file_model->createFileName($background_img, 'media/films/', 'background');
				$this->file_model->saveFile($background_img, $background_path);
				$params['background_img'] = $background_path;
			}

			$carousel_img = isset($_FILES['carousel_img']) ? $_FILES['carousel_img'] : null;
			if ($carousel_img != null && $carousel_img['error'] == 0) {
				$carousel_path = $this->file_model->createFileName($carousel_img, 'media/films/', 'carousel');
				$this->file_model->saveFile($carousel_img, $carousel_path);
				$params['trailler_image'] = $carousel_path;
			}
			$preview_img = isset($_FILES['preview_img']) ? $_FILES['preview_img'] : null;
			if ($preview_img != null && $preview_img['error'] == 0) {
				$preview_path = $this->file_model->createFileName($preview_img, 'media/films/', 'preview');
				$this->file_model->saveFile($preview_img, $preview_path);
				if ($collection_product == null) {
					$data_collection = array(
						'collection_id' => $collection_id,
						'product_id' => $product_id,
						'promo_image' => $preview_path,
						'priority' => $this->collection_model->getMaxFilm($collection_id) + 1
					);
					$this->collection_model->addFilmPreviews($data_collection);
				} else {
					$this->collection_model->editPromo($collection_id, $product_id, $preview_path);
				}
			}

			$explore_img = isset($_FILES['explore_img']) ? $_FILES['explore_img'] : null;
			if ($explore_img != null && $explore_img['error'] == 0) {
				$explore_path = $this->file_model->createFileName($explore_img, 'media/films/', 'explore');
				$this->file_model->saveFile($explore_img, $explore_path);
				if($explore_product == null) {
					$this->preview_model->addFilm($product_id, $explore_path);
				} else {
					$this->preview_model->editPromo($product_id, $explore_path) ;
				}
			}
			$this->product_model->update($params, $product_id);
			$this->session->set_flashdata('msg', 'Edit success!');
			redirect(base_url('product/edit/' . $product_id));
		}
		$data = array();
		$data['parent_id'] = 3;
		$data['sub_id'] = 33;
		$data['account'] = $this->account;

		$rates = $this->product_model->getRates();
		$episodes = $this->product_model->getEpisodeSeasons($product_id);
		$product['rates'] = $rates;
		$product['episodes'] = $episodes;
		$product['explore_img'] = $explore_product['promo_image'];
		$product['preview_img'] = $collection_product['promo_image'];
		if ($product['paywall_episode'] != 0) {
			$episode = $this->episode_model->getEpisodeById($product['paywall_episode']);
			$product['position'] = $episode['position'];
			$product['paywall_episode_name'] = $episode['name'];
		}else {
			$product['paywall_episode_name'] = 'None';
		}

		$data['content'] = $this->load->view('admin/product_edit', $product, true);
		$data['customCss'] = array('assets/css/settings.css', 'assets/css/smoothness.jquery-ui.css');
		$data['customJs'] = array('assets/js/settings.js', 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js', 'assets/app/length.js', 'assets/app/info_video.js');
		$this->load->view('admin_main_layout', $data);
	}

	public function managerActor($product_id = 0) {
		$product = $this->product_model->getProductForAdmin($product_id);
		if ($product == null) {
			redirect('product');
		}
		$this->load->model('cast_model');
		if ($this->addCast(0)) {
			$this->session->set_flashdata('msg', 'Add success!');
			redirect(base_url('product/managerActor/' . $product_id));
		}
		$actors = $this->product_model->getListCast($product_id);
		$others = $this->cast_model->getOthers($product_id);
		$content = $this->load->view('admin/product_actor_list', array(
			'actors' => $actors,
			'others' => $others,
			'name' => $product['name'],
			'product_id' => $product['product_id']
		), true);
		$data = array();
		$data['parent_id'] = 3;
		$data['sub_id'] = 32;
		$data['account'] = $this->account;
		$data['content'] = $content;
		$data['customCss'] = array('assets/css/settings.css', 'assets/css/smoothness.jquery-ui.css');
		$data['customJs'] = array('assets/js/settings.js', 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js', 'assets/app/length.js', 'assets/app/getDescription.js', 'assets/app/face_book.js', 'assets/app/film_info.js');
		$this->load->view('admin_main_layout', $data);
	}

	public function addActor($cast_id = 0, $product_id = 0) {
		$this->load->model('cast_model');
		$cast = $this->cast_model->getCastById($cast_id);
		$product = $this->product_model->getProductForAdmin($product_id);
		if ($product == null || $cast == null) {
			redirect('product');
		}
		$this->db->insert('film_cast', array('product_id' => $product_id, 'cast_id' => $cast_id));
		$this->session->set_flashdata('msg', 'Add success!');
		redirect(base_url('product/managerActor/' . $product_id));
	}

	public function removeActor($cast_id = 0, $product_id = 0) {
		$this->load->model('cast_model');
		$cast = $this->cast_model->getCastById($cast_id);
		$product = $this->product_model->getProductForAdmin($product_id);
		if ($product == null || $cast == null) {
			redirect('product');
		}
		$this->db->where('product_id', $product_id);
		$this->db->where('cast_id', $cast_id);
		$this->db->delete('film_cast');
		$this->session->set_flashdata('msg', 'Remove success!');
		redirect(base_url('product/managerActor/' . $product_id));
	}

	public function managerMusic($product_id = 0) {
		$product = $this->product_model->getProductForAdmin($product_id);
		if ($product == null) {
			redirect('product');
		}
		$this->load->model('music_model');
		if ($this->addMusic(0)) {
			$this->session->set_flashdata('msg', 'Add success!');
			redirect(base_url('product/managerMusic/' . $product_id));
		}

		$musics = $this->product_model->getListMusic($product_id);
		$others = $this->music_model->getOthers();
		$content = $this->load->view('admin/product_music_list', array(
			'musics' => $musics,
			'others' => $others,
			'name' => $product['name'],
			'product_id' => $product['product_id']
		), true);
		$data = array();
		$data['parent_id'] = 3;
		$data['sub_id'] = 32;
		$data['account'] = $this->account;
		$data['content'] = $content;
		$data['customCss'] = array('assets/css/settings.css', 'assets/css/smoothness.jquery-ui.css');
		$this->load->view('admin_main_layout', $data);
	}

	public function putInMusic($music_id = 0, $product_id = 0) {
		$this->load->model('music_model');
		$music = $this->music_model->getMusicById($music_id);
		$product = $this->product_model->getProductForAdmin($product_id);
		if ($product == null || $music == null) {
			redirect('product');
		}
		$this->music_model->update(array('product_id' => $product_id), $music_id);
		$this->session->set_flashdata('msg', 'Add success!');
		redirect(base_url('product/managerMusic/' . $product_id));
	}

	public function removedMusic($music_id = 0, $product_id = 0) {
		$this->load->model('music_model');
		$music = $this->music_model->getMusicById($music_id);
		$product = $this->product_model->getProductForAdmin($product_id);
		if ($product == null || $music == null) {
			redirect('product');
		}
		$this->music_model->update(array('product_id' => 0), $music_id);
		$this->session->set_flashdata('msg', 'Remove success!');
		redirect(base_url('product/managerMusic/' . $product_id));
	}

	public function managerSeason($product_id) {
		$product = $this->product_model->getProductForAdmin($product_id);
		if ($product == null) {
			redirect('product');
		}
		$this->load->model('season_model');
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$params = array();
			$params['name'] = $this->input->post('name');
			$params['product_id'] = $product_id;
			$params['created'] = time();
			$this->season_model->insert($params);
			$this->session->set_flashdata('msg', 'Add success!');
			redirect(base_url('product/managerSeason/' . $product_id));
		}

		$seasons = $this->product_model->getListSeason($product_id);
		foreach ($seasons as $key => $item) {
			$seasons[$key]['num_episode'] = $this->season_model->getNumEpisode($item['season_id']);
		}
		$content = $this->load->view('admin/product_season_list', array('seasons' => $seasons, 'name' => $product['name'], 'product_id' => $product['product_id']), true);
		$data = array();
		$data['parent_id'] = 3;
		$data['sub_id'] = 32;
		$data['account'] = $this->account;
		$data['content'] = $content;
		$data['customCss'] = array('assets/css/settings.css', 'assets/css/smoothness.jquery-ui.css');
		$this->load->view('admin_main_layout', $data);
	}

	public function enable($product_id = 0) {
		$product_id = $this->input->get('product_id');
		$product = $this->product_model->getProductById($product_id);
		if ($product == null || $product['status'] != 0) {
			redirect(base_url('product'));
		} else {
			$this->session->set_flashdata('msg', 'Edit success!');
			$this->product_model->update(array('status' => 1), $product_id);
			return $this->redirect('product');
		}
	}

	public function disable($product_id = 0) {
		$product_id = $this->input->get('product_id');
		$product = $this->product_model->getProductById($product_id);
		if ($product == null || $product['status'] != 1) {
			redirect(base_url('product'));
		} else {
			$this->session->set_flashdata('msg', 'Edit success!');
			$this->product_model->update(array('status' => 0), $product_id);
			return $this->redirect('product');
		}
	}

	public function delete($product_id = 0) {
		$product_id = $this->input->get('product_id');
		$product = $this->product_model->getProductById($product_id);
		if ($product == '' || $product['status'] < 0) {
			$this->session->set_flashdata('error', 'This Film is not exists!');
			redirect(base_url('product'));
		} else {
			// Remove product from collections
			$this->load->model('collection_model');
			$products = $this->collection_model->getProductsInCollection($product_id);
			if (count($products)) {
				foreach ($products as $key => $product) {
					$this->collection_model->removeFilm($product['collection_id'], $product['product_id'], $product['priority']);
				}
			}
			// Remove reference notification
			$this->load->model('notify_model');
			$this->notify_model->deleteReference('product', $product_id);

			// Delete product
			$this->product_model->deleteProduct($product_id);

			$this->session->set_flashdata('msg', 'Delete success!');
			return redirect(base_url('product'));
		}
	}

	public function ajaxLoadData() {
		$query = isset($_GET["query"]) ? $_GET["query"] : '';
		$cast_id = isset($_GET["cast_id"]) ? $_GET["cast_id"] : '';
		$others = $this->product_model->getProductOthers($cast_id, $query);
		$html = $this->load->view('admin/ajax_product', array('others' => $others, 'cast_id' => $cast_id), true);
		die(json_encode($html));
	}

	public function getProductsByStatus() {
		$status = $this->input->get('status');
		if ($status == 2) {
			$products = $this->product_model->getAllProducts();
		}else
		{
			$products = $this->product_model-> getProductListByStatus($status);
		}
		foreach ($products as $key => $value) {
			$products[$key]['genres'] = $this->product_genres_model->getAll($products[$key]['product_id']);
			$arrGenre = array();
			foreach ($products[$key]['genres'] as $keyG => $value) {
				$arrGenre[$keyG] = $products[$key]['genres'][$keyG]['genre_name'];
			}
			$products[$key]['genre'] = implode(', ', $arrGenre);
		}
		$data = ['products' => $products];
		$html = $this->load->view('admin/product_table', $data, true);
		die(json_encode($html));
	}

	public function search() {
		$query = $this->input->get('query');
		$products = $this->product_model->getAllProducts($query);
		$data = ['products' => $products];
		$html = $this->load->view('admin/product_table', $data, true);
		die(json_encode($html));
	}
}

<?php

require_once APPPATH . '/core/Base_Controller.php';

class Product extends Base_Controller {
	public function __construct() {
		parent::__construct();
		$this->verifyAdmin();

		$this->load->model("product_model");
		$this->load->model("season_model");
		$this->load->model("episode_model");
		$this->load->model("story_genres_model");
		$this->load->model("product_genres_model");
		$this->load->library('hash');
		$this->load->library('pagination');
	}

	public function index($page = 1) {
		$conditions = array();
        parse_str($_SERVER['QUERY_STRING'], $conditions);

		$page = ($page <= 0) ? 1 : $page;

        if (!empty($conditions['per_page'])) {
            $per_page = $conditions['per_page'] * 1;
            if ($per_page < 50)
                $per_page = 25;
            if ($per_page > 100)
                $per_page = 100;
            $conditions['per_page'] = $per_page;
        } else {
            $per_page = 25;
        }
        // print_r($per_page);die();

		$config['base_url'] = base_url('product');
		$config['total_rows'] = $this->product_model->countAll();
		$config['per_page'] = $per_page;
		$config['cur_page'] = $page;
		$config['add_query_string'] = TRUE;
		$this->pagination->initialize($config);
		// print_r($this->pagination->create_links());die();
        $paging = array(
            'from' => $per_page * ($page - 1) + 1,
            'to' => min(array($per_page * $page, $config['total_rows'])),
            'total' => $config['total_rows'],
            'dropdown-size' => 125,
        );
		$headers = array(
            'img' => array('label' => '', 'sorting' => false),
            'product_id' => array('label' => 'ID', 'sorting' => true),
            'name' => array('label' => 'Story Name', 'sorting' => true),
            'total_block' => array('label' => '#&nbsp;of&nbsp;Blocks', 'sorting' => false),
            'paywall_block_name'=> array('label' => 'Paywall&nbsp;Block', 'sorting' => false),
            'genre' => array('label' => 'Genre', 'sorting' => false),
            'activity' => array('label' => 'Story&nbsp;Activity', 'sorting' => false),
            'created' => array('label' => 'Create&nbsp;Date', 'sorting' => true),
            'status' => array('label' => 'Status', 'sorting' => true),
            'Actions' => array('label' => 'Action')
        );

		$products = $this->product_model->getProductListForAdmin($page - 1, $conditions);
		$product_ids = Hash::combine($products,'{n}.product_id','{n}.product_id');
		if(!empty($product_ids)) {
	        //Get blocks by product_ids
			$blocks = $this->product_model->getAllBlock($product_ids);
	        $blocks= Hash::combine($blocks,'{n}.episode_id','{n}','{n}.product_id');

			//Get comments by product_ids
			$comments = $this->product_model->getAllComment($product_ids);
	        $comments= Hash::combine($comments,'{n}.comment_id','{n}','{n}.product_id');

	        //Get likes by product_ids
			$likes = $this->product_model->getAllLike($product_ids);
	        $likes = Hash::combine($likes,'{n}.id','{n}','{n}.product_id');

	        //Get picks by product_ids
			$picks = $this->product_model->getAllPick($product_ids);
	        $picks= Hash::combine($picks,'{n}.pick_id','{n}','{n}.product_id');
		}


		foreach ($products as $key => $value) {
			$products[$key]['comments'] = !empty($comments[$value['product_id']]) ? $comments[$value['product_id']] : [];
			$products[$key]['total_cmt'] = count($products[$key]['comments']);

			$products[$key]['likes'] = !empty($likes[$value['product_id']]) ? $likes[$value['product_id']] : [];
			$products[$key]['total_like'] = count($products[$key]['likes']);

			$products[$key]['picks'] = !empty($picks[$value['product_id']]) ? $picks[$value['product_id']] : [];
			$products[$key]['total_pick'] = count($products[$key]['picks']);

			$products[$key]['blocks'] = !empty($blocks[$value['product_id']]) ? $blocks[$value['product_id']] : [];
			$products[$key]['total_block'] = count($products[$key]['blocks']);

			

			$products[$key]['genres'] = $this->product_genres_model->getAll($products[$key]['product_id']);
			$arrGenre = array();
			foreach ($products[$key]['genres'] as $keyG => $value) {
				$arrGenre[$keyG] = $products[$key]['genres'][$keyG]['genre_name'];
			}
			$products[$key]['genre'] = implode(', ', $arrGenre);
		}
		$params = array(
			'headers' => $headers,
			'paging' => $paging,
			'products' => $products,
			'conditions' => $conditions
		);
		$data['parent_id'] = 3;
		$data['sub_id'] = 31;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/products/product_list', $params, true);
		$data['customJs'] = array('assets/plugins/sweetalert/dist/sweetalert.min.js','assets/app/delete-confirm.js', 'module/js/product.js', 'assets/app/search.js', 'assets/app/core-table/coreTable.js');
		$data['customCss'] = array('assets/plugins/sweetalert/dist/sweetalert.css', 'assets/css/settings.css', 'module/css/product.css');
		$this->load->view('admin_main_layout', $data);
	}

	public function add() {
		$this->load->model("collection_model");
		$this->load->model('preview_model');
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$params = array();
			$params['name'] = $this->input->post('name');
			$params['description'] = $this->input->post('description');
			$params['creators'] = $this->input->post('creators');
			$params['status'] = $this->input->post('status');
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
			if(!empty($genres)) {
				foreach ($genres as $item) {
					$paramsGenre = array(
						'product_id' => $product_id,
						'genre_id' => $item,
						'added_at' => time()
					);
					$this->product_genres_model->insert($paramsGenre);
				}
			}

			$carousel_img = isset($_FILES['carousel_img']) ? $_FILES['carousel_img'] : null;
			if ($carousel_img != null && $carousel_img['error'] == 0) {
				$collection_id = 5; //Carousel
				$path = $this->file_model->createFileName($carousel_img, 'media/films/', 'carousel');
				$this->file_model->saveFile($carousel_img, $path);
				$data_collection = [
					'product_id' => $product_id,
					'collection_id' => $collection_id,
					'priority' => $this->collection_model->getMaxFilm($collection_id) + 1,
					'promo_image' => $path,
					'added_at' => time()
				];
				$this->collection_model->addFilm($data_collection);
			}
			redirect(base_url('product'));
		}
		
		$rates = $this->product_model->getRates();
		$genres = $this->story_genres_model->getAll();
		$data['parent_id'] = 3;
		$data['sub_id'] = 31;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/products/product_add', array(
			'rates' => $rates,
			'genres' => $genres
		), true);
		$data['customJs'] = array('assets/js/settings.js', 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js', 'assets/app/length.js', 'assets/app/info_video.js','assets/plugins/multiselect/js/jquery.multiselect.js','module/js/product.js');
		$data['customCss'] = array('assets/css/settings.css', 'assets/css/smoothness.jquery-ui.css', 'assets/plugins/multiselect/css/jquery.multiselect.css','module/css/product.css');
		$this->load->view('admin_main_layout', $data);
	}

	public function edit($product_id = 0) {
		$this->session->set_userdata('product_id', $product_id);

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
			if ($this->input->post('status') != '')
				$params['status'] = $this->input->post('status');
			if ($this->input->post('genre_id') != '')
				$genres = $this->input->post('genre_id');
				if(!empty($genres)) {
					foreach ($genres as $item) {
						$check = $this->product_genres_model->checkIfExist($product_id, $item);
						if($check == 0) {
							$paramsGenre = array(
								'product_id' => $product_id,
								'genre_id' => $item,
								'added_at' => time()
							);
							$this->product_genres_model->insert($paramsGenre);
						}
					}
				}
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

			$carousel_img = isset($_FILES['carousel_img']) ? $_FILES['carousel_img'] : null;
			if ($carousel_img != null && $carousel_img['error'] == 0) {
				$collection_id = 5;
				$carousel_path = $this->file_model->createFileName($carousel_img, 'media/films/', 'carousel');
				$this->file_model->saveFile($carousel_img, $carousel_path);
				$collection_product = $this->collection_model->getCollectionProducts($collection_id,$product_id);
				if ($collection_product == null) {
					$data_collection = array(
						'collection_id' => $collection_id,
						'product_id' => $product_id,
						'promo_image' => $carousel_path,
						'priority' => $this->collection_model->getMaxFilm($collection_id) + 1
					);
					$this->collection_model->addFilm($data_collection);
				} else {
					$this->collection_model->editPromo($collection_id, $product_id, $carousel_path);
				}
			}
			$this->product_model->update($params, $product_id);
			// $this->session->set_flashdata('msg', 'Edit success!');
			redirect(base_url('product/edit/' . $product_id));
		}

		$product['rates'] = $this->product_model->getRates();
		$product['episodes'] = $this->product_model->getEpisodeSeasons($product_id);
		$product['explore_img'] = $explore_product['promo_image'];
		$product['preview_img'] = $collection_product['promo_image'];
		$product['carousel_img'] = $this->collection_model->getCollectionProducts(5,$product_id)['promo_image'];
		if ($product['paywall_episode'] != 0) {
			$episode = $this->episode_model->getEpisodeById($product['paywall_episode']);
			$product['position'] = $episode['position'];
			$product['paywall_episode_name'] = $episode['name'];
		}else {
			$product['paywall_episode_name'] = 'None';
		}
		// Get genres
		$product['genres'] = $this->story_genres_model->getAll();
		$product['selected_genres'] = $this->product_genres_model->getAll($product_id);
		if (!empty($this->product_genres_model->getAll($product_id))) {
			$selected_genres = array();
			foreach ($product['selected_genres'] as $key => $value) {
				$selected_genres[] = $value['genre_id'];
			}
			$product['deselect_genres'] = $this->story_genres_model->getDeselectGenre($product_id, $selected_genres);
		}
		$params = array(
			'page_index' => 'product_edit',
			'page_base' => 'product',
			'product' => $product
		);
		$this->customCss[] = 'assets/css/settings.css';
		$this->customCss[] = 'assets/css/smoothness.jquery-ui.css';
		$this->customCss[] = 'module/css/submenu.css';
		$this->customCss[] = 'assets/plugins/multiselect/css/jquery.multiselect.css';
		$this->customCss[] = 'module/css/product.css';
		$this->customJs[] = 'assets/js/settings.js';
		$this->customJs[] = 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js';
		$this->customJs[] = 'assets/app/length.js';
		$this->customJs[] = 'module/js/product.js';
		$this->render('/products/product_manage', $params, 3, 32);
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

	public function enable() {
		$product_id = $this->input->get('product_id');
		$product = $this->product_model->getProductById($product_id);
		if ($product == null || $product['status'] != 0) {
			$this->redirect('product');
		} else {
			$this->session->set_flashdata('msg', 'Edit success!');
			$this->product_model->update(array('status' => 1), $product_id);
			return $this->redirect('product');
		}
	}

	public function disable() {
		$product_id = $this->input->get('product_id');
		$product = $this->product_model->getProductById($product_id);
		if ($product == null || $product['status'] != 1) {
			$this->redirect('product');
		} else {
			$this->session->set_flashdata('msg', 'Edit success!');
			$this->product_model->update(array('status' => 0), $product_id);
			return $this->redirect('product');
		}
	}

	public function delete() {
		$product_id = $this->input->get('product_id');
		$product = $this->product_model->getProductById($product_id);
		if ($product == '' || $product['status'] < 0) {
			$this->session->set_flashdata('error', 'This Film is not exists!');
			$this->redirect('product');
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
			return $this->redirect('product');
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

	public function manageReview($product_id = 0) {
		$product = $this->product_model->checkProduct($product_id);
		
		$conditions = array();
        parse_str($_SERVER['QUERY_STRING'], $conditions);
        $reviews = $this->product_model->getProductReviewsForAdmin($product_id, $conditions);
        if(empty($reviews)) {
        	$params['page_index'] = 'empty_review';
        }else {
	        $headers = array(
	            'icon' => array('label' => '', 'sorting' => false),
	            'avatar' => array('label' => '', 'sorting' => false),
	            'full_name' => array('label' => 'Username', 'sorting' => true),
	            'name' => array('label' => 'Story Name', 'sorting' => true),
	            'quote' => array('label' => 'Reviews', 'sorting' => false),
	            'status' => array('label' => 'Status', 'sorting' => true),
	            'Actions' => array('label' => 'Actions')
	        );

	        $params = array(
				'page_index' => 'manage_review',
				'page_base' => 'product/manageReview/' . $product_id,
				'headers' => $headers,
				'product' => $product,
				'reviews' => $reviews,
				'conditions' => $conditions
			);
	    }
		$this->customCss[] = 'module/css/submenu.css';
		$this->customCss[] = 'module/css/product.css';
		$this->customJs[] = 'module/js/coreTable.js';
		$this->customJs[] = 'module/js/product.js';
		$this->render('/products/product_manage', $params, 3, 33);
	}

	public function sortable() {
		header('Content-Type: application/json');
		$response = ['success' => false];
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$dragging_id = $this->input->post('dragging');
			$positions = $this->input->post('positions');

			$ids = array_keys($positions);
			foreach ($ids as $key => $id) {
				$this->product_model->updatePriority(['priority' => $key + 1], $id);
			}
			$response['success'] = true;
		}
		echo json_encode($response);
	}

	public function disableReview() {
		$pick_id = $this->input->get('pick_id');
		$product_id = $this->input->get('product_id');

		$pick = $this->product_model->getPick($pick_id);
		if ($pick == null) {
			return $this->manageReview($product_id);
		} else {
			$this->session->set_flashdata('msg', 'Edit success!');
			$this->product_model->updatePriority(['is_hidden' => 1], $pick_id);
		}
	}

	public function enableReview() {
		$pick_id = $this->input->get('pick_id');
		$product_id = $this->input->get('product_id');

		$pick = $this->product_model->getPick($pick_id);
		if ($pick == null) {
			return $this->manageReview($product_id);
		} else {
			$this->session->set_flashdata('msg', 'Edit success!');
			$this->product_model->updatePriority(['is_hidden' => 0], $pick_id);
		}
	}

	public function deleteReview() {
		$pick_id = $this->input->get('pick_id');
		$product_id = $this->input->get('product_id');

		$pick = $this->product_model->getPick($pick_id);
		if ($pick == null) {
			return $this->manageReview($product_id);
		} else {
			$this->session->set_flashdata('msg', 'Edit success!');
			$this->product_model->deletePick($pick_id);
		}
	}

	public function manageSeason($product_id = 0) {
		$conditions = array();
        parse_str($_SERVER['QUERY_STRING'], $conditions);

		$product = $this->product_model->checkProduct($product_id);
		// Get seasons by product
        $seasons = $this->season_model->getSeasonByProduct($product_id);
        if(empty($seasons)) {
        	$params['page_index'] = 'create_season';
        }else {
	        $seasons = Hash::combine($seasons,'{n}.season_id','{n}');
			$season_ids = Hash::combine($seasons,'{n}.season_id','{n}');
	        $season_ids = array_keys($season_ids);

	        // Get episodes by seasons
	        $episodes = $this->episode_model->getEpisodesBySeason($season_ids, $conditions);
	        if(empty($episodes)) {
	        	$params = array(
					'page_index' => 'empty_episode',
					'page_base' => 'product/manageSeason/' . $product_id,
					'seasons' => $seasons,	
					'episodes' => $episodes	
				);
	        }else {
	        	$headers = array(
		            'icon' => array('label' => '', 'sorting' => false),
		            'episode_id' => array('label' => 'Block ID', 'sorting' => false),
		            'position' => array('label' => 'Block #', 'sorting' => false),
		            'name' => array('label' => 'Block Name', 'sorting' => false),
		            'activity' => array('label' => 'Block Activity', 'sorting' => false),
		            'created' => array('label' => 'Create Date', 'sorting' => false),
		            'status' => array('label' => 'Status', 'sorting' => true),
		            'Actions' => array('label' => 'Actions')
		        );
		        $episode_ids = Hash::combine($episodes,'{n}.episode_id','{n}');
		        $episode_ids = array_keys($episode_ids);

		        //Get comments by episodes
				$comments = $this->episode_model->getAllComment($episode_ids);
		        $comments= Hash::combine($comments,'{n}.comment_id','{n}','{n}.episode_id');

		        //Get comments by episodes
				$likes = $this->episode_model->getAllLike($episode_ids);
		        $likes = Hash::combine($likes,'{n}.id','{n}','{n}.episode_id');

		        foreach ($episodes as $key => $value) {
					$episodes[$key]['comments'] = !empty($comments[$value['episode_id']]) ? $comments[$value['episode_id']] : [];
					$episodes[$key]['total_cmt'] = count($episodes[$key]['comments']);

					$episodes[$key]['likes'] = !empty($likes[$value['episode_id']]) ? $likes[$value['episode_id']] : [];
					$episodes[$key]['total_like'] = count($episodes[$key]['likes']);
				}
		        $seasons = Hash::combine($seasons,'{n}.season_id','{n}.name');
				$episodes = Hash::combine($episodes,'{n}.episode_id','{n}','{n}.season_id');

				$season_have_block = $this->episode_model->getSeasonHavingBlock();
				$season_ids = Hash::combine($season_have_block,'{n}.season_id','{n}');
				$season_ids = array_keys($season_ids);
				$new_seasons = $this->season_model->getSeasonWithoutBlock($product_id, $season_ids);

		        $params = array(
					'page_index' => 'manage_season',
					'page_base' => 'product/manageSeason/' . $product_id,
					'episodes' => $episodes,
					'seasons' => $seasons,
					'headers' => $headers,
					'new_seasons' => $new_seasons,
					'product_id' => $product_id,
					'conditions' => $conditions
				);
		    }
		}
		$this->customCss[] = 'module/css/submenu.css';
		$this->customCss[] = 'module/css/product.css';
		$this->customCss[] = 'module/css/season.css';
		$this->customJs[] = 'module/js/coreTable.js';
		// $this->customJs[] = 'module/js/product.js';
		$this->customJs[] = 'module/js/season.js';
		$this->render('/products/product_manage', $params, 3, 34);
	}

	public function sortableSeason($season_id = 0) {
		header('Content-Type: application/json');
		$response = ['success' => false];
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$dragging_id = $this->input->post('dragging');
			$positions = $this->input->post('positions');
			// $episodes = $this->episode_model->getEpisodesOfSeason($season_id);
			$ids = array_keys($positions);
			foreach ($ids as $key => $id) {
				$this->episode_model->update(['position' => $key + 1], $id);
			}
			$response['success'] = true;
		}
		echo json_encode($response);
	}

	public function ajaxSeason() {
		$this->load->view('admin/products/sub_page/create_season');
	}

	public function createSeason() {
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
    		$season_name = $this->input->post('season_name');
    		$product_id = $this->session->userdata('product_id');
    		$paramSeason = array(
    			'name' => $season_name,
    			'product_id' => $product_id,
    			'created' => time(),
    			'status' => 1
    		);
    		$this->season_model->insert($paramSeason);
    		$this->redirect('product/manageSeason/' . $product_id);
    	}
	}

	public function disableEpisode() {
		$episode_id = $this->input->get('episode_id');
		$product_id = $this->input->get('product_id');

		$episode = $this->episode_model->getEpisodeById($episode_id);
		if(empty($episode)) {
			return $this->manageSeason($product_id);
		}else {
			$this->episode_model->update(['status' => 0], $episode_id);
		}
	}

	public function enableEpisode() {
		$episode_id = $this->input->get('episode_id');
		$product_id = $this->input->get('product_id');

		$episode = $this->episode_model->getEpisodeById($episode_id);
		if(empty($episode)) {
			return $this->manageSeason($product_id);
		}else {
			$this->episode_model->update(['status' => 1], $episode_id);
		}
	}

	public function deleteEpisode() {
		$episode_id = $this->input->get('episode_id');
		$product_id = $this->input->get('product_id');

		$episode = $this->episode_model->getEpisodeById($episode_id);
		if(empty($episode)) {
			return $this->manageSeason($product_id);
		}else {
			$this->episode_model->delete($episode_id);
		}
	}

	public function ajaxProduct() {
		$key = $this->input->post('key');
		$product_id = $this->input->post('product_id');
		$episode_id = !empty($this->input->post('episode_id')) ? $this->input->post('episode_id') : null;
	
		$data['product'] = $this->product_model->getProductById($product_id);		
		$data['seasons'] = $this->season_model->getSeasonByProduct($product_id);
		$data['episode'] = $this->episode_model->getEpisodeDetail($episode_id);
		$data['product_id'] = $product_id;

		if ($key == 'add-block') {
			$this->load->view('admin/products/sub_page/add_episode', $data);
		}else {
			$this->load->view('admin/products/sub_page/edit_episode', $data);
		}
	}

	public function addEpisode() {
		$product_id = $this->input->post('product_id');
        $episode_name = $this->input->post('episode_name');
        $season_id = $this->input->post('season_id');
        $jw_media_id = $this->input->post('jw_media_id');
        $description = $this->input->post('description');

        $image = isset($_FILES['block_img']) ? $_FILES['block_img'] : null;
        $this->load->model('file_model');
        if ($image != null && $image['error'] == 0) {
            $path = $this->file_model->createFileName($image, 'media/films/', 'film');
            $this->file_model->saveFile($image, $path);
            $episode_image = $path;
        }
        $max_position = $this->episode_model->getPosition($season_id);
        $params = array(
            'name' => $episode_name,
            'season_id' => $season_id,
            'jw_media_id' => $jw_media_id,
            'position' => $max_position + 1,
            'image' => $episode_image,
            'created' => time(),
            'description' => $description
        );
        $this->episode_model->insert($params);
        $this->redirect('product/manageSeason/' . $product_id);
    }

    public function editEpisode() {
    	$product_id = $this->input->post('product_id');
    	$episode_id = $this->input->post('episode_id');
    	$episode = $this->episode_model->getEpisodeDetail($episode_id);
        if ($episode == null) {
            redirect('product');
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') 
        {
            $params['name'] = !empty($this->input->post('episode_name')) ? $this->input->post('episode_name') : null;
            $params['season_id'] = !empty($this->input->post('season_id')) ? $this->input->post('season_id') : null;
            $params['jw_media_id'] = !empty($this->input->post('jw_media_id')) ? $this->input->post('jw_media_id') : null;
            $params['description'] = !empty($this->input->post('description')) ? trim($this->input->post('description')) : null;  

            if(!empty($_FILES['block_img'])) {
	            $image = isset($_FILES['block_img']) ? $_FILES['block_img'] : null;
		        $this->load->model('file_model');
		        if ($image != null && $image['error'] == 0) {
		            $path = $this->file_model->createFileName($image, 'media/films/', 'film');
		            $this->file_model->saveFile($image, $path);
		            $params['image'] = $path;
		        }
		    }
            $this->episode_model->update($params, $episode_id);
            $this->redirect('product/manageSeason/' . $product_id);
        }
    }
}

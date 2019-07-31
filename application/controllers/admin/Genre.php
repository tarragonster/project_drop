<?php

require_once APPPATH . '/core/Base_Controller.php';

class Genre extends My_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->model("story_genres_model");
		$this->load->model('file_model');
	}

	public function index() {
		$conditions = array();
		parse_str($_SERVER['QUERY_STRING'], $conditions);
		$genres = $this->story_genres_model->getAll($conditions);
		print_r($genres);die();
		$headers = array(
			'image' => array('label' => '', 'sorting' => false),
			'id' => array('label' => 'Genre ID', 'sorting' => false),
			'name' => array('label' => 'Genre Name', 'sorting' => false),
			'num_stories' => array('label' => '# of Stories', 'sorting' => false),
			'created_at' => array('label' => 'Create Date', 'sorting' => false),
			'status' => array('label' => 'Status', 'sorting' => true),
			'actions' => 'Action');

		if ($genres == null) {
			$params['page_index'] = 'empty_genre';
		}else {

			$params = [
				'page_index' => 'genre_settings',
				'page_base' => 'genre',
				'genres' => $genres,
				'headers' => $headers,
				'conditions' => $conditions,
			];
		}
		$this->customCss[] = 'module/css/submenu.css';
		$this->customCss[] = 'module/css/genre.css';
		$this->customJs[] = 'module/js/coreTable.js';
		$this->render('/setting/setting_page', $params, 5, 51);
	}

	public function ajaxAddGenre() {
		$this->load->view('admin/setting/sub_page/add_genre');
	}

	public function addGenre() {
		$params['name'] = $this->input->post('genre_name');
		$genre_image = $this->input->post('genre_image');
		if ($genre_image != null) {
			$path = $this->file_model->createFileName($genre_image, 'media/genres/', 'genre');
			$this->file_model->saveFile($genre_image, $path);
			$params['image'] = $path;
		}
		$params['created_at'] = time();
		$this->story_genres_model->insert($params);
	}
}
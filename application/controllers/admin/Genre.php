<?php

require_once APPPATH . '/core/Base_Controller.php';

class Genre extends My_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->model("story_genres_model");
	}

	public function index() {
		$genres = $this->story_genres_model->getAll();
		if ($genres == null) {
			$params['page_index'] = 'empty_genre';
		}else {
			$params = [
				'page_index' => 'genre_settings',
				'genres' => $genres
			];
		}
		$this->customCss[] = 'module/css/submenu.css';
		$this->customCss[] = 'module/css/genre.css';
		$this->render('/setting/setting_page', $params, 5, 51);
	}
}
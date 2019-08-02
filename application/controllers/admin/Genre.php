<?php

require_once APPPATH . '/core/Base_Controller.php';

class Genre extends My_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->model("story_genres_model");
		$this->load->model("product_genres_model");
		$this->load->model('file_model');
	}

	public function index() {
		$conditions = array();
		parse_str($_SERVER['QUERY_STRING'], $conditions);
		$genres = $this->story_genres_model->getAll($conditions);
		foreach ($genres as $key => $value) {
			$genres[$key]['num_stories'] = count($this->product_genres_model->countStoryByGenre($genres[$key]['id']));
		}
		$headers = array(
			'icon' => array('label' => '', 'sorting' => false),
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
		$this->customCss[] = 'module/css/genre-sorting.css';
		$this->customJs[] = 'module/js/coreTable.js';
		$this->customJs[] = 'module/js/setting-action.js';
		$this->render('/setting/setting_page', $params, 5, 51);
	}

	public function ajaxGenre() {
		$key = $this->input->post('key');
		$genre_id = !empty($this->input->post('genre_id')) ? $this->input->post('genre_id') : '';
		$data['genre'] = $this->story_genres_model->get($genre_id);

		if ($key == 'add') {
			$this->load->view('admin/setting/sub_page/add_genre');
		}else {
			$this->load->view('admin/setting/sub_page/edit_genre', $data);
		}
	}

	public function addGenre() {
		$params['name'] = $this->input->post('genre_name');
		$image = isset($_FILES['genre_img']) ? $_FILES['genre_img'] : null;
		if ($image != null && $image['error'] == 0) {
			$path = $this->file_model->createFileName($image, 'media/genres/', 'genre');
			$this->file_model->saveFile($image, $path);
			$params['image'] = $path;
		}
		$params['created_at'] = time();
		$this->story_genres_model->insert($params);
		$this->redirect('genre');
	}

	public function editGenre() {
		$genre_id = $this->input->post('genre_id');
		$params['name'] = $this->input->post('genre_name');
		$image = isset($_FILES['genre_img']) ? $_FILES['genre_img'] : null;
		if ($image != null && $image['error'] == 0) {
			$path = $this->file_model->createFileName($image, 'media/genres/', 'genre');
			$this->file_model->saveFile($image, $path);
			$params['image'] = $path;
		}
		$this->story_genres_model->update($params, $genre_id);
		$this->redirect('genre');
	}

	public function disable($genre_id = 0) {
		$genre_id = $this->input->get('genre_id');
		$genre = $this->story_genres_model->get($genre_id);
		if ($genre == null) {
			$this->redirect('genre');
		} else {
			$this->story_genres_model->update(array('status' => 0), $genre_id);
			return $this->redirect('genre');
		}
	}

	public function enable($genre_id = 0) {
		$genre_id = $this->input->get('genre_id');
		$genre = $this->story_genres_model->get($genre_id);
		if ($genre == null) {
			$this->redirect('genre');
		}else {
			$this->story_genres_model->update(array('status' => 1), $genre_id);
			return $this->redirect('genre');
		}
	}

	public function delete($genre_id = 0) {
		$genre_id = $this->input->get('genre_id');
		$genre = $this->story_genres_model->get($genre_id);
		if ($genre == null) {
			$this->redirect('genre');
		}
		//Delete product_genres
		$this->product_genres_model->deleteByGenre($genre_id);
		//Delete story_genres
		$this->story_genres_model->delete($genre_id);
		$this->redirect('genre');
	}

	public function sortable() {
		header('Content-Type: application/json');
		$response = ['success' => false];
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$dragging_id = $this->input->post('dragging');
			$positions = $this->input->post('positions');
			$genres = $this->story_genres_model->getAll();
			if (is_array($positions) && count($positions) > 1
				&& is_array($genres) && count($genres) > 1
			) {
				$newPositions = [];
				foreach ($genres as $key => $genre) {
					if ($genre['id'] != $dragging_id) {
						$newPositions[] = $genre['id'];
					}
				}
				$new_index = -1;
				$aboveItemId = -1;
				foreach ($positions as $key => $genre_id) {
					if ($genre_id == $dragging_id) {
						if ($key == 0) {
							$new_index = $key;
						} else {
							$aboveItemId = $positions[$key - 1];
						}
						break;
					}
				}
				if ($aboveItemId > 0) {
					foreach ($newPositions as $key => $genre_id) {
						if ($aboveItemId == $genre_id) {
							$new_index = $key + 1;
						}
					}
				} else if ($new_index == -1) {
					$new_index = count($newPositions);
				}
				array_splice($newPositions, $new_index, 0, [$dragging_id]);
				foreach ($newPositions as $priority => $genre_id) {
					$this->story_genres_model->update(['priority' => $priority], $genre_id);
				}
				$response['success'] = true;
			}
		}
		echo json_encode($response);
	}
}
<?php

require_once APPPATH . '/core/Base_Controller.php';

class Explore extends Base_Controller {

	public function __construct() {
		parent::__construct();
		$this->verifyAdmin();

		$this->load->model("featured_model");
		$this->load->model("preview_model");
		$this->load->model("product_model");
		$this->load->library('hash');
	}

	public function index() {
		$featured_users = $this->featured_model->getUsers();
		if ($featured_users == null) {
			$params['page_index'] = 'empty_featured_users';
		}else {
			$user_ids = Hash::combine($featured_users,'{n}.user_id','{n}.user_id');

			$picks = $this->user_model->getAllPick($user_ids);
			$picks = Hash::combine($picks,'{n}.pick_id','{n}','{n}.user_id');

			$comments = $this->user_model->getAllComment($user_ids);
	        $comments= Hash::combine($comments,'{n}.comment_id','{n}','{n}.user_id');

			$likes = $this->user_model->getAllLike($user_ids);
	        $likes = Hash::combine($likes,'{n}.id','{n}','{n}.user_id');

	        foreach ($featured_users as $key => $value) {
				$featured_users[$key]['picks'] = !empty($picks[$value['user_id']]) ? $picks[$value['user_id']] : [];
				$featured_users[$key]['total_pick'] = count($featured_users[$key]['picks']);

				$featured_users[$key]['comments'] = !empty($comments[$value['user_id']]) ? $comments[$value['user_id']] : [];
				$featured_users[$key]['total_cmt'] = count($featured_users[$key]['comments']);

				$featured_users[$key]['likes'] = !empty($likes[$value['user_id']]) ? $likes[$value['user_id']] : [];
				$featured_users[$key]['total_like'] = count($featured_users[$key]['likes']);

				$featured_users[$key]['followers'] = $this->user_model->countFollowing($value['user_id']);
				$featured_users[$key]['following'] = $this->user_model->countFollowers($value['user_id']);
			}
			$params = [
				'page_index' => 'featured_user',
				'page_base' => 'explore',
				'featured_users' => $featured_users,
			];
		}
		$this->customCss[] = 'module/css/submenu.css';
		$this->customCss[] = 'module/css/genre.css';
		$this->customCss[] = 'module/css/explore.css';
		$this->customJs[] = 'module/js/coreTable.js';
		$this->customJs[] = 'module/js/explore.js';
		$this->render('/explore/explore_page', $params, 10, 11);
	}

	public function ajaxExplore() {
		$key = $this->input->post('key');
		if ($key == 'add-user') {
			$this->load->view('admin/explore/sub_page/add_featured_user');
		}else {
			$this->load->view('admin/explore/sub_page/add_preview_story');
		}
	}

	public function searchOtherUser() {
		$key = $this->input->post('key');
		$featured_users = $this->featured_model->getUsers();
		$user_ids = Hash::combine($featured_users,'{n}.user_id','{n}.user_id');
		$other_users = $this->featured_model->searchOtherUsers($key, $user_ids);
		echo json_encode($other_users);
	}

	public function addFeaturedUser() {
		$user_id = $this->input->post('user_id');
		$max_priority = $this->featured_model->getMaxProfile();
		$params = array(
			'user_id' => $user_id,
			'priority' => $max_priority + 1,
			'added_at' => time(),
			'status' => 1
		);
		$this->featured_model->insert($params);
		$this->redirect('explore');
	}

	public function sortFeaturedUser() {
		header('Content-Type: application/json');
		$response = ['success' => false];
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$dragging_id = $this->input->post('dragging');
			$positions = $this->input->post('positions');

			$ids = array_keys($positions);
			foreach ($ids as $key => $id) {
				$this->featured_model->updatePriority(['priority' => $key + 1], $id);
			}
			$response['success'] = true;
		}
		echo json_encode($response);
	}

	public function disableFeaturedUser() {
		$user_id = $this->input->get('user_id');
		$user = $this->featured_model->getProfile($user_id);
		if ($user == null) {
			$this->redirect('explore');
		} else {
			$this->featured_model->update(array('status' => 0), $user['id']);
		}
	}

	public function enableFeaturedUser() {
		$user_id = $this->input->get('user_id');
		$user = $this->featured_model->getProfile($user_id);
		if ($user == null) {
			$this->redirect('explore');
		} else {
			$this->featured_model->update(array('status' => 1), $user['id']);
		}
	}

	public function removeFeaturedUser() {
		$user_id = $this->input->get('user_id');
		$user = $this->featured_model->getProfile($user_id);
		if ($user == null) {
			$this->redirect('explore');
		} else {
			$this->featured_model->delete($user['id']);
		}
	}

	public function managePreviews() {
		$previews = $this->preview_model->getPreviews();
		if($previews == null) {
			$params['page_index'] = 'empty_preview';
		}else {
			$preview_ids = Hash::combine($previews,'{n}.product_id','{n}.product_id');

			$comments = $this->product_model->getAllComment($preview_ids);
	        $comments= Hash::combine($comments,'{n}.comment_id','{n}','{n}.product_id');

			$likes = $this->product_model->getAllLike($preview_ids);
	        $likes = Hash::combine($likes,'{n}.id','{n}','{n}.product_id');

			$picks = $this->product_model->getAllPick($preview_ids);
	        $picks= Hash::combine($picks,'{n}.pick_id','{n}','{n}.product_id');

	        foreach ($previews as $key => $value) {
				$previews[$key]['comments'] = !empty($comments[$value['product_id']]) ? $comments[$value['product_id']] : [];
				$previews[$key]['total_cmt'] = count($previews[$key]['comments']);

				$previews[$key]['likes'] = !empty($likes[$value['product_id']]) ? $likes[$value['product_id']] : [];
				$previews[$key]['total_like'] = count($previews[$key]['likes']);

				$previews[$key]['picks'] = !empty($picks[$value['product_id']]) ? $picks[$value['product_id']] : [];
				$previews[$key]['total_pick'] = count($previews[$key]['picks']);
			}
			$params = [
				'page_index' => 'preview_list',
				'page_base' => 'explore',
				'previews' => $previews
			];
		}
		$this->customCss[] = 'module/css/submenu.css';
		$this->customCss[] = 'module/css/genre.css';
		$this->customCss[] = 'module/css/explore.css';
		$this->customJs[] = 'module/js/coreTable.js';
		$this->customJs[] = 'module/js/explore.js';
		$this->render('/explore/explore_page', $params, 10, 12);
	}

	public function searchOtherProduct() {
		$key = $this->input->post('key');
		$previews = $this->preview_model->getPreviews();
		$preview_ids = Hash::combine($previews,'{n}.product_id','{n}.product_id');
		$other_products = $this->preview_model->searchOtherProducts($key, $preview_ids);
		echo json_encode($other_products);
	}

	public function sortPreviewStory() {
		header('Content-Type: application/json');
		$response = ['success' => false];
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$dragging_id = $this->input->post('dragging');
			$positions = $this->input->post('positions');

			$ids = array_keys($positions);
			foreach ($ids as $key => $id) {
				$this->preview_model->updatePriority(['priority' => $key + 1], $id);
			}
			$response['success'] = true;
		}
		echo json_encode($response);
	}

	public function disablePreview() {
		$product_id = $this->input->get('product_id');
		$product = $this->preview_model->getFilm($product_id);
		if ($product == null) {
			$this->redirect('explore');
		} else {
			$this->preview_model->update(array('status' => 0), $product['id']);
		}
	}

	public function enablePreview() {
		$product_id = $this->input->get('product_id');
		$product = $this->preview_model->getFilm($product_id);
		if ($product == null) {
			$this->redirect('explore');
		} else {
			$this->preview_model->update(array('status' => 1), $product['id']);
		}
	}

	public function removePreview() {
		$product_id = $this->input->get('product_id');
		$product = $this->preview_model->getFilm($product_id);
		if ($product == null) {
			$this->redirect('explore');
		} else {
			$this->preview_model->delete($product['id']);
		}
	}


}
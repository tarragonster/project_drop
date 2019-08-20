<?php

require_once APPPATH . '/core/Base_Controller.php';

class Explore extends Base_Controller {

	public function __construct() {
		parent::__construct();
		$this->verifyAdmin();

		$this->load->model("featured_model");
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
			$data['other_users'] = $this->featured_model->getOtherUsers();
			$this->load->view('admin/explore/sub_page/add_featured_user', $data);
		}
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
			return $this->redirect('explore');
		}
	}

	public function enableFeaturedUser() {
		$user_id = $this->input->get('user_id');
		$user = $this->featured_model->getProfile($user_id);
		if ($user == null) {
			$this->redirect('explore');
		} else {
			$this->featured_model->update(array('status' => 1), $user['id']);
			return $this->redirect('explore');
		}
	}

	public function removeFeaturedUser() {
		$user_id = $this->input->get('user_id');
		$user = $this->featured_model->getProfile($user_id);
		if ($user == null) {
			$this->redirect('explore');
		} else {
			$this->featured_model->delete($user['id']);
			return $this->redirect('explore');
		}
	}
}
<?php

require_once APPPATH . '/core/Base_Controller.php';

class User extends Base_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model("admin_model");
		$this->load->model("user_model");
		$admin = $this->session->userdata('admin');
		if ($admin == null) {
			redirect(base_url('admin/login'));
		}
		$lockdata = $this->session->userdata('lockdata');
		if ($lockdata != null) {
			redirect(base_url('admin/lockscreen'));
		}
		$this->account = $this->admin_model->getAdminAccountByEmail($admin['email']);
		$this->load->helper(array('form'));
	}

	public function index($page = 1) {

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$submited = $this->input->post('search');
			$search = array();
			$post = $this->input->post(NULL, TRUE);
			if ($submited) {
				$this->handle_post('admin/user');
			} else {
				$submited = $this->input->post('export');
				if ($submited) {
					$search = $this->handle_post('admin/user', true);
					$this->exportEmail($search);
				}
			}
		}

		$condition = array();

		$this->load->library('pagination');

		$page = ($page <= 0) ? 1 : $page;

		$config['base_url'] = base_url('admin/user');

		$config['total_rows'] = $this->user_model->getNumOfUser(1);
		$config['per_page'] = PERPAGE_ADMIN;
		$config['cur_page'] = $page;
		$config['add_query_string'] = TRUE;
		$this->pagination->initialize($config);
		$pinfo = array(
			'from' => PERPAGE_ADMIN * ($page - 1) + 1,
			'to' => min(array(PERPAGE_ADMIN * $page, $config['total_rows'])),
			'total' => $config['total_rows'],
		);
		$users = $this->user_model->getUsersForAdmin($page - 1, 1);

		$content = $this->load->view('admin/users_list', array('users' => $users, 'pinfo' => $pinfo), true);

		$data = array();
		$data['parent_id'] = 2;
		$data['sub_id'] = 21;
		$data['account'] = $this->account;
		$data['content'] = $content;
		$this->load->view('admin_main_layout', $data);

	}

	public function blocked($page = 1) {

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$submited = $this->input->post('search');
			$search = array();
			$post = $this->input->post(NULL, TRUE);
			if ($submited) {
				$this->handle_post('admin/user/blocked');
			} else {
				$submited = $this->input->post('export');
				if ($submited) {
					$search = $this->handle_post('admin/user/blocked', true);
					$this->exportEmail($search);
				}
			}
		}
		$this->load->library('pagination');

		$page = ($page <= 0) ? 1 : $page;
		$config['base_url'] = base_url('admin/user/blocked');
		$config['total_rows'] = $this->user_model->getNumOfUser(0);
		$config['per_page'] = PERPAGE_ADMIN;
		$config['cur_page'] = $page;
		$this->pagination->initialize($config);
		$pinfo = array(
			'from' => PERPAGE_ADMIN * ($page - 1) + 1,
			'to' => min(array(PERPAGE_ADMIN * $page, $config['total_rows'])),
			'total' => $config['total_rows'],
		);
		$users = $this->user_model->getUsersForAdmin($page - 1, 0);

		$content = $this->load->view('admin/users_list', array('users' => $users, 'pinfo' => $pinfo), true);

		$data = array();
		$data['parent_id'] = 2;
		$data['sub_id'] = 22;
		$data['account'] = $this->account;
		$data['content'] = $content;

		$this->load->view('admin_main_layout', $data);
	}

	public function block($user_id = '') {

		$user = $this->user_model->getUserForAdmin($user_id);

		if ($user != null) {
			$params = array('status' => 1 - $user['status']);
			$this->user_model->update($params, $user['user_id']);
			$this->load->library('oauths');
			$this->oauths->delete($user['user_id']);
			$this->user_model->clearData($user['user_id']);
		}
		$this->redirect('admin/user');

	}

	public function delete($user_id = '') {

		$user = $this->user_model->getUserForAdmin($user_id);

		if ($user != null) {
			$this->db->where('user_id', $user_id);
			$this->db->delete('watch_list');

			$this->db->where('user_id', $user_id);
			$this->db->delete('user_watch');

			$this->db->where('user_id', $user_id);
			$this->db->delete('user_notify');

			$this->db->where('user_id', $user_id);
			$this->db->or_where('follower_id', $user_id);
			$this->db->delete('user_follow');

			$this->db->where('user_id', $user_id);
			$this->db->delete('user_access_token');

			$this->db->where('user_id', $user_id);
			$this->db->delete('replies_like');

			$this->db->where('user_id', $user_id);
			$this->db->delete('log_login');

			$this->db->where('user_id', $user_id);
			$this->db->delete('episode_replies');

			$this->db->where('user_id', $user_id);
			$this->db->delete('episode_like');

			$this->db->where('user_id', $user_id);
			$this->db->delete('episode_comment');

			$this->db->where('user_id', $user_id);
			$this->db->delete('comment_like');

			$this->db->where('user_id', $user_id);
			$this->db->delete('user');
		}
		$this->redirect('admin/user');
	}

	public function edit($user_id = '') {
		$user = $this->user_model->getUserForAdmin($user_id);
		if ($user == null) {
			redirect(base_url('admin/user'));
		}
		$cmd = $this->input->post('cmd');

		if ($cmd != '') {
			$params = array();
			$params['email'] = $this->input->post('email');
			$params['user_name'] = $this->input->post('user_name');
			$params['full_name'] = $this->input->post('full_name');

			$avatar = isset($_FILES['avatar']) ? $_FILES['avatar'] : null;
			if ($avatar != null) {
				$this->load->model('file_model');
				if ($this->file_model->checkFileImage($avatar)) {
					$path = $this->file_model->createPathAvatar($user_id, $avatar);
					$this->file_model->saveFile($avatar, $path);
					$params['avatar'] = $path;
					$path_thumb = $this->file_model->createThumbnailName($path);
					$this->file_model->cropAndResizeThumbNail($path, $path_thumb);
				}
			}
			$this->user_model->update($params, $user_id);
			if ($cmd == 'Save') {
				redirect(base_url('admin/user'));
			} else if ($cmd == 'SaveContinue') {
				redirect(base_url('admin/edit/' . $user_id));
			}
		} else {
			$user = $this->user_model->getUserForAdmin($user_id);
			if ($user != null) {
				$content = $this->load->view('admin/user_edit', $user, true);
			} else {
				$content = 'Not exists user';

			}
			$data = array();
			$data['parent_id'] = 2;
			$data['sub_id'] = 21;
			$data['account'] = $this->account;
			$data['content'] = $content;

			$this->load->view('admin_main_layout', $data);
		}
	}

	public function profile($user_id = '') {
		$user = $this->user_model->getUserForAdmin($user_id);
		if ($user == null) {
			redirect(base_url('admin/user'));
		}

		$data = array();
		$data['parent_id'] = 2;
		$data['sub_id'] = 21;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/user_profile', array('user' => $user), true);
		$this->load->view('admin_main_layout', $data);
	}
}
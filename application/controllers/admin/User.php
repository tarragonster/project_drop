<?php

require_once APPPATH . '/core/Base_Controller.php';

class User extends Base_Controller {

	public function __construct() {
		parent::__construct();
		$this->verifyAdmin();
	}

	public function index($page = 1) {
		$this->load->library('pagination');

		$page = ($page <= 0) ? 1 : $page;

		$config['base_url'] = base_url('user');

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
		$users = $this->user_model->getAllUsers();
		$layoutParams = [
			'title' => 'Active Users',
			'users' => $users,
			'pinfo' => $pinfo
		];
		$content = $this->load->view('admin/users_list', $layoutParams, true);

		$data = array();
		$data['customCss'] = array('assets/css/settings.css');
		$data['customJs'] = array('assets/js/settings.js', 'assets/app/search.js');
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
				$this->handle_post('user/blocked');
			} else {
				$submited = $this->input->post('export');
				if ($submited) {
					$search = $this->handle_post('user/blocked', true);
					$this->exportEmail($search);
				}
			}
		}
		$this->load->library('pagination');

		$page = ($page <= 0) ? 1 : $page;

		$config['base_url'] = base_url('user');

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
		$users = $this->user_model->getUsersForAdmin(0);
		$layoutParams = [
			'title' => 'Active Users',
			'users' => $users,
			'pinfo' => $pinfo
		];
		$content = $this->load->view('admin/users_list', $layoutParams, true);

		$data = array();
		$data['customCss'] = array('assets/css/settings.css');
		$data['customJs'] = array('assets/js/settings.js');
		$data['parent_id'] = 2;
		$data['sub_id'] = 21;
		$data['account'] = $this->account;
		$data['content'] = $content;
		$this->load->view('admin_main_layout', $data);
	}

	public function signups($page = 1) {

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$submited = $this->input->post('search');
			if ($submited) {
				$this->handle_post('user/signups');
			} else {
				$submited = $this->input->post('export');
				if ($submited) {
					$search = $this->handle_post('user/blocked', true);
					$this->exportEmail($search);
				}
			}
		}
		$this->load->library('pagination');

		$page = ($page <= 0) ? 1 : $page;
		$config['base_url'] = base_url('user/signups');
		$config['total_rows'] = $this->user_model->getNumOfSignups();
		$config['per_page'] = PERPAGE_ADMIN;
		$config['cur_page'] = $page;
		$this->pagination->initialize($config);
		$pinfo = array(
			'from' => PERPAGE_ADMIN * ($page - 1) + 1,
			'to' => min(array(PERPAGE_ADMIN * $page, $config['total_rows'])),
			'total' => $config['total_rows'],
		);
		$users = $this->user_model->getSignups($page - 1);
		$layoutParams = [
			'title' => 'Newsletter Signups',
			'users' => $users,
			'pinfo' => $pinfo
		];

		$content = $this->load->view('admin/signup_list', $layoutParams, true);

		$data = array();
		$data['parent_id'] = 2;
		$data['sub_id'] = 25;
		$data['account'] = $this->account;
		$data['content'] = $content;

		$this->load->view('admin_main_layout', $data);
	}

	public function deleteSignUp($id = '') {
		$this->db->where('id', $id);
		$this->db->delete('newsletter_signups');
		$this->redirect('user/signups');
	}

	public function exportSignUp() {
		$users = $this->user_model->getSignups(-1);
		$data = array();
		if (isset($users) && is_array($users)) {
			foreach ($users as $row) {
				$item = array();
				$item[] = $row['id'];
				$item[] = $row['email'];
				$item[] = $row['full_name'];
				$item[] = date('m/d/Y h:i:a', $row['added_at']);
				$data[] = $item;
			}
		}

		$headers = array(
			array('width' => 15, 'align' => 'C', 'label' => 'ID'),
			array('width' => 55, 'align' => 'L', 'label' => 'E-Mail'),
			array('width' => 35, 'align' => 'L', 'label' => 'Name'),
			array('width' => 20, 'align' => 'C', 'label' => 'Date Submitted'));
		$this->load->library('exporter');
		$this->exporter->exportCsv($headers, $data, 'newsletter_signups_' . date('YmdHi', time()) . '.csv');
	}

	public function exportUsers() {
		$users = $this->user_model->getAllUsers();
		$data = array();
		if (isset($users) && is_array($users)) {
			foreach ($users as $row) {
				$item = array();
				$item[] = $row['user_id'];
				$item[] = $row['user_name'];
				$item[] = $row['email'];
				$item[] = $row['total_comment'];
				$item[] = $row['total_like'];
				$item[] = $row['total_pick'];
				$item[] = $row['device_name'];
				$item[] = date('m/d/Y h:iA', $row['joined']);
				$data[] = $item;
			}
		}

		$headers = array(
			array('width' => 15, 'align' => 'C', 'label' => 'ID'),
			array('width' => 35, 'align' => 'L', 'label' => 'Name'),
			array('width' => 55, 'align' => 'L', 'label' => 'E-Mail'),
			array('width' => 55, 'align' => 'C', 'label' => 'Comment Total'),
			array('width' => 55, 'align' => 'C', 'label' => 'Like Total'),
			array('width' => 55, 'align' => 'C', 'label' => 'Pick Total'),
			array('width' => 55, 'align' => 'C', 'label' => 'Device Name'),
			array('width' => 20, 'align' => 'C', 'label' => 'Created'));
		$this->load->library('exporter');
		$this->exporter->exportCSV($headers, $data, 'users_list_' . date('YmdHi', time()) . '.csv');
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
		$this->redirect('user');

	}

	public function unBlock($user_id = '') {

		$user = $this->user_model->getUserForAdmin($user_id);

		if ($user != null) {
			$params = array('status' => 1 + $user['status']);
			$this->user_model->update($params, $user['user_id']);
			$this->load->library('oauths');
			$this->oauths->delete($user['user_id']);
			$this->user_model->clearData($user['user_id']);
		}
		$this->redirect('user/blocked');

	}

	public function delete($user_id = '') {
		$user = $this->user_model->getUserForAdmin($user_id);
		if ($user != null) {
			$this->user_model->delete($user_id);
		}
		$this->redirect('user');
	}

	public function edit($user_id = '') {
		$user = $this->user_model->getUserForAdmin($user_id);
		if ($user == null) {
			redirect('user');
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$params = array();
			$params['email'] = $this->input->post('email');
			$params['user_name'] = $this->input->post('user_name');
			$params['full_name'] = $this->input->post('full_name');
			$params['user_type'] = $this->input->post('user_type');

			$userEmail = $this->user_model->getByEmail($params['email']);
			if ($userEmail != null && $userEmail['user_id'] != $user_id) {
				$this->session->set_flashdata('error_message', 'Sorry, this email is already linked to an existing account');
				redirect(base_url('user/edit/' . $user_id));
			}

			$userX = $this->user_model->getByUsername($params['user_name']);
			if ($userX != null && $userX['user_id'] != $user_id) {
				$this->session->set_flashdata('error_message', 'Sorry, this username is already linked to an existing account');
				redirect(base_url('user/edit/' . $user_id));
			}

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

			$this->load->library('contact_lib');
			if ($params['email'] != $user['email']) {
				$this->contact_lib->updateContact(CONTACT_TYPE_EMAIL, $user['email'], 0);
				$this->contact_lib->updateContact(CONTACT_TYPE_EMAIL, $params['email'], $user_id);
			}

			redirect(base_url('user/edit/' . $user_id));
		}

		$data = array();
		$data['parent_id'] = 2;
		$data['sub_id'] = $user != null && $user['status'] == 1 ? 21 : 22;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/user_edit', $user, true);

		$this->load->view('admin_main_layout', $data);
	}

	public function profile($user_id = '') {
		$user = $this->user_model->getUserForAdmin($user_id);
		if ($user == null) {
			redirect(base_url('user'));
		}

		$this->load->model('product_model');
		$layoutParams = [
			'user' => $user
		];
		$layoutParams['your_picks'] = $this->user_model->getUserPicks($user_id, -1);
		$layoutParams['watch_list'] = $this->user_model->getListWatching($user_id, -1);
		$layoutParams['thumbs_up'] = $this->user_model->getProductThumbUpList($user_id, -1);

		$data = array();
		$data['customCss'] = array('assets/plugins/sweetalert/dist/sweetalert.css');
		$data['customJs'] = array('assets/plugins/sweetalert/dist/sweetalert.min.js', 'assets/app/sweet-alerts.js');

		$data['parent_id'] = 2;
		$data['sub_id'] = $user != null && $user['status'] == 1 ? 21 : 22;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/user_profile', $layoutParams, true);
		$this->load->view('admin_main_layout', $data);
	}

	public function reports($page = 1) {
		$this->load->library('pagination');

		$page = ($page <= 0) ? 1 : $page;

		$config['base_url'] = base_url('user/reports');

		$config['total_rows'] = $this->user_model->getNumReports();
		$config['per_page'] = PERPAGE_ADMIN;
		$config['cur_page'] = $page;
		$config['add_query_string'] = TRUE;
		$this->pagination->initialize($config);
		$pinfo = array(
			'from' => PERPAGE_ADMIN * ($page - 1) + 1,
			'to' => min(array(PERPAGE_ADMIN * $page, $config['total_rows'])),
			'total' => $config['total_rows'],
		);
		$reports = $this->user_model->getReports($page - 1);

		$content = $this->load->view('admin/users/report', array('reports' => $reports, 'pinfo' => $pinfo), true);

		$data = array();
		$data['parent_id'] = 2;
		$data['sub_id'] = 23;
		$data['account'] = $this->account;
		$data['content'] = $content;
		$this->load->view('admin_main_layout', $data);
	}

	public function deleteReport($report_id) {
		$this->db->where('report_id', $report_id);
		$this->db->delete('user_reports');

		$this->redirect('user/reports');
	}

	public function removePick($pick_id) {
		$pick = $this->user_model->getPick($pick_id);
		if ($pick == null) {
			$this->redirect();
		}
		$this->user_model->removePick($pick_id);
		$this->redirect();
	}

	public function removeWatch($id) {
		$watch = $this->user_model->getWatch($id);
		if ($watch == null) {
			$this->redirect();
		}
		$this->user_model->removeWatch($id);
		$this->redirect();
	}

	public function removeLike($id) {
		$like = $this->user_model->getLike($id);
		if ($like == null) {
			$this->redirect();
		}
		$this->user_model->removeLike($id);
		$this->redirect();
	}

	public function removeProductLike($id) {
		$this->user_model->removeProductLike($id);
		$this->redirect();
	}

	public function editPick($pick_id) {
		$pick = $this->user_model->getPick($pick_id);
		if ($pick == null) {
			$this->redirect();
		}
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$quote = $this->input->post('quote');
			$this->user_model->updatePick(['quote' => $quote], $pick_id);

			$this->redirect(make_url('user/profile/' . $pick['user_id'], ['active' => 'your-picks']));
		}

		$user = $this->user_model->get($pick['user_id']);
		$data = array();
		$data['parent_id'] = 2;
		$data['sub_id'] = $user != null && $user['status'] == 1 ? 21 : 22;
		$data['account'] = $this->account;
		$data['content'] = $this->load->view('admin/users/edit_pick', $pick, true);
		$this->load->view('admin_main_layout', $data);
	}

	public function getUsersByStatus()
	{
		$status = $this->input->get('status');
		if ($status == 0 || $status == 1) {
			$users = $this->user_model->getUsersForAdmin($status);
		}else
		{
			$users = $this->user_model->getAllUsers();
		}
		$data = ['users' => $users];
		$this->load->view('admin/users_table', $data);
	}

	public function search()
	{
		$query = $this->input->get('query');
		$users = $this->user_model->getAllUsers($query);
		$data = ['users' => $users];
		$this->load->view('admin/users_table', $data);
	}
}
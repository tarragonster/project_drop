<?php

require_once APPPATH . '/core/Base_Controller.php';

class Comment extends Base_Controller {

	public function __construct() {
		parent::__construct();
		$this->verifyAdmin();

		$this->load->model("comment_model");
	}

	public function index() {
		$content = $this->load->view('admin/comment_list', array(), true);
		$data = array();
		$data['parent_id'] = 9;
		$data['sub_id'] = 91;
		$data['account'] = $this->account;
		$data['content'] = $content;
		$data['customCss'] = array('assets/css/jquery-ui.css', 'assets/css/settings.css', 'assets/css/smoothness.jquery-ui.css', 'assets/plugins/sweetalert/dist/sweetalert.css');

		$data['customJs'] = array('assets/js/jquery-ui.js', 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js', 'assets/app/length.js', 'assets/app/comment_autocomplete.js', 'assets/plugins/sweetalert/dist/sweetalert.min.js', 'assets/app/delete-comment.js');
		$this->load->view('admin_main_layout', $data);
	}

	public function ajaxComment() {
		$episode_id = isset($_GET["episode_id"]) ? $_GET["episode_id"] * 1 : '';
		if ($episode_id != '') {
			$comments = $this->comment_model->getCommentForAdmin($episode_id);
			foreach ($comments as $key => $comment) {
				$comments[$key]['num_rep'] = $this->comment_model->countReplies($comment['comment_id']);
			}
			$html = $this->load->view('admin/ajax_comment', array('comments' => $comments, 'episode_id' => $episode_id), true);
		} else {
			$html = '';
		}
		die(json_encode($html));
	}

	public function ajaxReplies() {
		$comment_id = isset($_GET["comment_id"]) ? $_GET["comment_id"] * 1 : '';
		if ($comment_id != '') {
			$replies = $this->comment_model->getRepliesForAdmin($comment_id);
			$html = $this->load->view('admin/ajax_replies', array('replies' => $replies, 'comment_id' => $comment_id), true);
		} else {
			$html = '';
		}
		die(json_encode($html));
	}

	public function ajaxEpisode() {
		$q = $this->input->get('q');
		if (!empty($q)) {
			$this->db->like('name', $q, 'both');
		}
		$this->db->where('status', 1);
		$this->db->order_by('name', 'asc');
		$this->db->limit(15);

		$query = $this->db->get('episode');
		$products = $query->result_array();
		$items = array();
		if (is_array($products)) {
			foreach ($products as $row) {
				$item = array('value' => $row['episode_id'], 'label' => $row['name'],);
				$items[] = $item;
			}
		}
		header('Content-Type: application/json');
		echo json_encode($items);
	}

	public function delete($comment_id) {
		$this->comment_model->delete($comment_id);
		$this->ajaxComment();
	}

	public function deleteReplies($replies_id) {
		$this->comment_model->deleteReplies($replies_id);
		$this->ajaxReplies();
	}

	public function reports($page = 1) {
		$this->load->library('pagination');

		$page = ($page <= 0) ? 1 : $page;

		$config['base_url'] = base_url('admin/comment/reports');

		$config['total_rows'] = $this->comment_model->getNumReports();
		$config['per_page'] = PERPAGE_ADMIN;
		$config['cur_page'] = $page;
		$config['add_query_string'] = TRUE;
		$this->pagination->initialize($config);
		$pinfo = array(
			'from' => PERPAGE_ADMIN * ($page - 1) + 1,
			'to' => min(array(PERPAGE_ADMIN * $page, $config['total_rows'])),
			'total' => $config['total_rows'],
		);
		$reports = $this->comment_model->getReports($page - 1);

		$content = $this->load->view('admin/comments/report', array('reports' => $reports, 'pinfo' => $pinfo), true);

		$data = array();
		$data['parent_id'] = 9;
		$data['sub_id'] = 92;
		$data['account'] = $this->account;
		$data['content'] = $content;
		$this->load->view('admin_main_layout', $data);
	}

	public function deleteReport($report_id) {
		$this->db->where('report_id', $report_id);
		$this->db->delete('comment_reports');

		$this->redirect('admin/comment/reports');
	}
}
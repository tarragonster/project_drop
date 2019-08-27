<?php

require_once APPPATH . '/core/Base_Controller.php';

class Comment extends Base_Controller {

	public function __construct() {
		parent::__construct();
		$this->verifyAdmin();
        $this->load->library('hash');

        $this->load->model("comment_model");
	}

	public function stories($page = 1) {
        $conditions = array();
        parse_str($_SERVER['QUERY_STRING'], $conditions);

        $this->load->library('pagination');

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
        $config['base_url'] = base_url('comment/stories');
        $config['total_rows'] = $this->comment_model->countAllProduct($conditions);
        $config['per_page'] = $per_page;
        $config['cur_page'] = $page;
        $config['add_query_string'] = TRUE;
        $this->pagination->initialize($config);

        $paging = array(
            'from' => $per_page * ($page - 1) + 1,
            'to' => min(array($per_page * $page, $config['total_rows'])),
            'total' => $config['total_rows'],
            'dropdown-size' => 125,
        );

        $headers = array(
            'img' => array('label' => '', 'sorting' => false),
            'product_id' => array('label' => 'Story ID', 'sorting' => true),
            'name' => array('label' => 'Story Name', 'sorting' => true),
            'total_episodes' => array('label' => '# of Blocks', 'sorting' => true),
            'total_comments'=> array('label' => '# of Comments', 'sorting' => true),
            'pv_status' => array('label' => 'Status', 'sorting' => true),
            'Actions' => array('label' => 'Actions', 'sorting' => false));

        $pinfo = array(
            'from' => $per_page * ($page - 1) + 1,
            'to' => min(array($per_page * $page, $config['total_rows'])),
            'total' => $config['total_rows'],
        );

        $products = $this->comment_model->getAllProducts($conditions, $page - 1);

        $params['sub_id'] = 91;
        $params['headers'] = $headers;
        $params['conditions'] = $conditions;
        $params['paging'] = $paging;
        $params['pinfo'] = $pinfo;
        $params['products'] = $products;

		$content = $this->load->view('admin/product_list', $params, true);

		$data = array();
		$data['parent_id'] = 9;
		$data['sub_id'] = 91;
		$data['account'] = $this->account;
		$data['content'] = $content;
		$data['customCss'] = array('assets/css/jquery-ui.css', 'assets/css/settings.css', 'assets/css/smoothness.jquery-ui.css', 'assets/plugins/sweetalert/dist/sweetalert.css','module/css/comment.css');

		$data['customJs'] = array('assets/js/jquery-ui.js', 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js',
            'assets/app/length.js', 'assets/app/comment_autocomplete.js', 'assets/plugins/sweetalert/dist/sweetalert.min.js',
            'assets/app/delete-comment.js','assets/app/core-table/coreTable.js');
		$this->load->view('admin_main_layout', $data);
	}

	public function blocks($product_id,$page = 1){
        $conditions = array();
        parse_str($_SERVER['QUERY_STRING'], $conditions);

        $this->load->library('pagination');

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

        $seasons = $this->comment_model->getAllSeasons($product_id);
        $season_ids = Hash::combine($seasons,'{n}.season_id','{n}.season_id');

        if(!empty($seasons)) {
            $blocks = $this->comment_model->getAllBlocks($season_ids,$product_id,$conditions,$page - 1);
            $blocks= Hash::combine($blocks,'{n}.episode_id','{n}','{n}.season_id');
        }

        foreach ($seasons as $key=>$value) {
            $seasons[$key]['blocks'] = !empty($blocks[$value['season_id']])?$blocks[$value['season_id']]:[];
        }

        $config['base_url'] = base_url('comment/block/'.$product_id);
        $config['total_rows'] = $this->comment_model->countAllBlock($season_ids,$product_id,$conditions);
        $config['per_page'] = $per_page;
        $config['cur_page'] = $page;
        $config['add_query_string'] = TRUE;
        $this->pagination->initialize($config);

        $paging = array(
            'from' => $per_page * ($page - 1) + 1,
            'to' => min(array($per_page * $page, $config['total_rows'])),
            'total' => $config['total_rows'],
            'dropdown-size' => 125,
        );

        $headers = array(
            'episode_id' => array('label' => 'Block ID', 'sorting' => true),
            'position' => array('label' => 'Block #', 'sorting' => true),
            'ep_name' => array('label' => 'Block Name', 'sorting' => true),
            'total_comments'=> array('label' => '# of Comments', 'sorting' => true),
            'e_status' => array('label' => 'Status', 'sorting' => true),
            'Actions' => array('label' => 'Actions', 'sorting' => false));

        $pinfo = array(
            'from' => $per_page * ($page - 1) + 1,
            'to' => min(array($per_page * $page, $config['total_rows'])),
            'total' => $config['total_rows'],
        );

//        pre_print($seasons);

        $params['headers'] = $headers;
        $params['conditions'] = $conditions;
        $params['paging'] = $paging;
        $params['pinfo'] = $pinfo;
        $params['title'] = $this->comment_model->getStory($product_id);
        $params['sub_id'] = 91;
        $params['seasons'] = $seasons;

        $content = $this->load->view('admin/comments/block_list', $params, true);

        $data = array();
        $data['parent_id'] = 9;
        $data['sub_id'] = 91;
        $data['account'] = $this->account;
        $data['content'] = $content;
        $data['customCss'] = array('assets/css/jquery-ui.css', 'assets/css/settings.css', 'assets/css/smoothness.jquery-ui.css', 'assets/plugins/sweetalert/dist/sweetalert.css','module/css/comment.css');

        $data['customJs'] = array('assets/js/jquery-ui.js', 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js', 'assets/app/length.js', 'assets/app/comment_autocomplete.js', 'assets/plugins/sweetalert/dist/sweetalert.min.js', 'assets/app/delete-comment.js','assets/app/core-table/coreTable.js');
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
		$this->load->model('notify_model');
		$this->notify_model->deleteReference('comment', $comment_id);
		$this->ajaxComment();
	}

	public function deleteReplies($replies_id) {
		$this->comment_model->deleteReplies($replies_id);
		$this->ajaxReplies();
	}

	public function reports($page = 1) {
//	    $this->output->enable_profiler(true);
        $conditions = array();
        parse_str($_SERVER['QUERY_STRING'], $conditions);
        $this->load->library('pagination');

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

        $config['base_url'] = base_url('comment/reports');
        $config['total_rows'] = $this->comment_model->countAllCommentReports($conditions);
        $config['per_page'] = $per_page;
        $config['cur_page'] = $page;
        $config['add_query_string'] = TRUE;
        $this->pagination->initialize($config);

        $paging = array(
            'from' => $per_page * ($page - 1) + 1,
            'to' => min(array($per_page * $page, $config['total_rows'])),
            'total' => $config['total_rows'],
            'dropdown-size' => 125,
        );

        $headers = array(
            'report_id' => array('label' => 'Comment Report ID', 'sorting' => true),
            'reported_name' => array('label' => 'Username', 'sorting' => true),
            'content'=> array('label' => 'Content', 'sorting' => true),
            'reporter_name'=> array('label' => 'Reporter Name', 'sorting' => true),
            'created_at'=> array('label' => 'Report Date', 'sorting' => true),
            'status'=> array('label' => 'Status', 'sorting' => true),
            'Actions' => array('label' => 'Action', 'sorting' => false));

        $pinfo = array(
            'from' => $per_page * ($page - 1) + 1,
            'to' => min(array($per_page * $page, $config['total_rows'])),
            'total' => $config['total_rows'],
        );

        $reports = $this->comment_model->getReports($conditions,$page - 1);

        $params['headers'] = $headers;
        $params['conditions'] = $conditions;
        $params['sub_id'] = 92;
        $params['paging'] = $paging;
        $params['pinfo'] = $pinfo;
        $params['reports'] = $reports;


        $content = $this->load->view('admin/comments/report', $params, true);

		$data = array();
		$data['parent_id'] = 9;
		$data['sub_id'] = 92;
		$data['account'] = $this->account;
		$data['content'] = $content;

        $data['customCss'] = array('assets/css/jquery-ui.css', 'assets/css/settings.css', 'assets/css/smoothness.jquery-ui.css', 'assets/plugins/sweetalert/dist/sweetalert.css','module/css/comment.css','module/css/user.css');
        $data['customJs'] = array('assets/js/jquery-ui.js', 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js',
            'assets/app/length.js', 'assets/app/comment_autocomplete.js', 'assets/plugins/sweetalert/dist/sweetalert.min.js',
            'assets/app/delete-comment.js','assets/app/core-table/coreTable.js','module/js/comment.js','module/js/user.js','assets/js/jquery.validate.js');

        $this->load->view('admin_main_layout', $data);
	}

	public function deleteReport($report_id) {
		$this->db->where('report_id', $report_id);
		$this->db->delete('comment_reports');

		$this->redirect('comment/reports');
	}

	public function comments($episode_id,$page = 1){
        $conditions = array();
        parse_str($_SERVER['QUERY_STRING'], $conditions);
        $this->load->library('pagination');

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

        $config['base_url'] = base_url('comment/comments/'.$episode_id);
        $config['total_rows'] = $this->comment_model->countAllComments($episode_id,$conditions);
        $config['per_page'] = $per_page;
        $config['cur_page'] = $page;
        $config['add_query_string'] = TRUE;
        $this->pagination->initialize($config);

        $paging = array(
            'from' => $per_page * ($page - 1) + 1,
            'to' => min(array($per_page * $page, $config['total_rows'])),
            'total' => $config['total_rows'],
            'dropdown-size' => 125,
        );

        $headers = array(
            'comment_id' => array('label' => 'Comment ID', 'sorting' => true),
            'full_name' => array('label' => 'Username', 'sorting' => true),
            'content'=> array('label' => 'Content', 'sorting' => true),
            'total_like'=> array('label' => 'Likes', 'sorting' => true),
            'total_reply'=> array('label' => 'Replies', 'sorting' => true),
            'timestamp'=> array('label' => 'Create Date', 'sorting' => true),
            'status' => array('label' => 'Status', 'sorting' => true),
            'Actions' => array('label' => 'Actions', 'sorting' => false));

        $pinfo = array(
            'from' => $per_page * ($page - 1) + 1,
            'to' => min(array($per_page * $page, $config['total_rows'])),
            'total' => $config['total_rows'],
        );

        $title_product = $this->comment_model->getStoryName($episode_id);
        $title_episode = $this->comment_model->getEpisodeName($episode_id);

        $comments = $this->comment_model->getComments($episode_id,$conditions,$page - 1);


        $params['headers'] = $headers;
        $params['conditions'] = $conditions;
        $params['sub_id'] = 91;
        $params['paging'] = $paging;
        $params['pinfo'] = $pinfo;
        $params['title_product'] = $title_product;
        $params['title_episode'] = $title_episode;
        $params['comments'] = $comments;

        $content = $this->load->view('admin/comments/comment_list', $params, true);

        $data = array();
        $data['parent_id'] = 9;
        $data['sub_id'] = 91;
        $data['account'] = $this->account;
        $data['content'] = $content;
        $data['customCss'] = array('assets/css/jquery-ui.css', 'assets/css/settings.css', 'assets/css/smoothness.jquery-ui.css', 'assets/plugins/sweetalert/dist/sweetalert.css','module/css/comment.css','module/css/user.css');

        $data['customJs'] = array('assets/js/jquery-ui.js', 'assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js',
            'assets/app/length.js', 'assets/app/comment_autocomplete.js', 'assets/plugins/sweetalert/dist/sweetalert.min.js',
            'assets/app/delete-comment.js','assets/app/core-table/coreTable.js','module/js/comment.js','module/js/user.js','assets/js/jquery.validate.js');
        $this->load->view('admin_main_layout', $data);
    }

    public function disableComment($comment_id){
	    $params['test'] = 1;
	    $this->comment_model->disableComment($comment_id);
        $this->ajaxSuccess($params);
    }

    public function enableComment($comment_id){
        $params['test'] = 1;
        $this->comment_model->enableComment($comment_id);
        $this->ajaxSuccess($params);
    }

    public function firstModalDelete(){
        $param =[];
        $data = [];
        $data['success'] = '1';
        $data['content'] = $this->load->view('admin/comments/deleteComment_firstModal',$param,true);
        $this->ajaxSuccess($data);
    }

    public function secondModalDelete(){
        $param =[];
        $data = [];
        $data['success'] = '1';
        $data['content'] = $this->load->view('admin/comments/deleteComment_secondModal',$param,true);
        $this->ajaxSuccess($data);
    }

    public function deleteComment($comment_id){
	    $this->comment_model->deleteComment($comment_id);
	    $this->ajaxSuccess();
    }

    public function showCommentReplies($comment_id){
	    $title = $this->comment_model->getTitleReplies($comment_id);
        $mainComment = $this->comment_model->getMainComment($comment_id);
        $commentLike = $this->comment_model->getLikeMainComment($comment_id);
        $total_commentLike = count($commentLike) > 0 ? count($commentLike):'0';

        $replies = $this->comment_model->getReplies($comment_id);
        $reply_ids = Hash::combine($replies,'{n}.replies_id','{n}.replies_id');

        if(!empty($replies)) {
            $reply_likes = $this->comment_model->getReplyLikes($reply_ids);
            $reply_likes = Hash::combine($reply_likes,'{n}.id','{n}','{n}.replies_id');
        }

        foreach($replies as $key=>$value){
            $replies[$key]['reply_likes'] = !empty($reply_likes[$value['replies_id']])?$reply_likes[$value['replies_id']]:[];
            $replies[$key]['total_replyLike'] = count($replies[$key]['reply_likes']) > 0 ? count($replies[$key]['reply_likes']):'0';

        }

        $params['mainComment'] = $mainComment;
	    $params['replies'] = $replies;
	    $params['total_commentLike'] = $total_commentLike;
	    $params['replies'] = $replies;
	    $params['title'] = $title;

        $data['content'] = $this->load->view('admin/comments/comment_replies_ajax',$params,true);
        $this->ajaxSuccess($data);
    }

    public function disableReply($replies_id){
        $params['test'] = 1;
        $this->comment_model->disableReply($replies_id);
        $this->ajaxSuccess($params);
    }
    public function enableReply($replies_id){
        $params['test'] = 1;
        $this->comment_model->enableReply($replies_id);
        $this->ajaxSuccess($params);
    }

    public function showFirstDeleteReply(){
        $param =[];
        $data = [];
        $data['success'] = '1';
        $data['content'] = $this->load->view('admin/comments/deleteReply_firstModal',$param,true);
        $this->ajaxSuccess($data);
    }

    public function showSecondDeleteReply(){
        $param =[];
        $data = [];
        $data['success'] = '1';
        $data['content'] = $this->load->view('admin/comments/deleteReply_secondModal',$param,true);
        $this->ajaxSuccess($data);
    }

    public function confirmDeleteReply($replies_id){
        $this->comment_model->confirmDeleteReply($replies_id);
        $this->ajaxSuccess();
    }

    public function showNote($report_id){
        $param['report_id'] = $report_id;
        $data['code'] = 200;
        $data['content'] = $this->load->view('admin/comments/comment_report_ajax',$param,true);

        $this->ajaxSuccess($data);
    }

    public function saveNote($report_id){
        $param = [];
        $note = $this->input->post('note');
        $this->comment_model->updateCommentReportNote($report_id,$note);
        $report = $this->comment_model->getCommentReportNote($report_id);
        $param['report'] = $report;
        $data['code'] = 200;
        $data['confirmContent'] = $this->load->view('admin/comments/report_confirm_note',$param,true);

        $this->ajaxSuccess($data);
    }

    public function editNote($report_id){
        $param = [];
        $report = $this->comment_model->getCommentReportNote($report_id);
        $param['report_id'] = $report_id;
        $param['report'] = $report;
        $data['code'] = 200;
        $data['content'] = $this->load->view('admin/comments/edit_report_ajax',$param,true);

        $this->ajaxSuccess($data);
    }

    public function disableCommentReported($report_id){
        $this->comment_model->disableCommentReported($report_id);
        $this->ajaxSuccess();
    }

    public function enableCommentReported($report_id){
        $this->comment_model->enableCommentReported($report_id);
        $this->ajaxSuccess();
    }

    public function showFirstDeleteReportedComment($report_id){
        $param =[];
        $data = [];
        $data['test'] = '1';
        $data['content'] = $this->load->view('admin/comments/deleteReportedComment_firstModal',$param,true);
        $this->ajaxSuccess($data);
    }

    public function showSecondDeleteReportedComment($report_id){
        $param =[];
        $data = [];
        $data['test'] = '1';
        $data['content'] = $this->load->view('admin/comments/deleteReportedComment_secondModal',$param,true);
        $this->ajaxSuccess($data);
    }

    public function confirmDeleteReportedComment($report_id){
	    $this->comment_model->confirmDeleteReportedComment($report_id);
        $this->ajaxSuccess();
    }

    public function confirmNote($report_id){
        $param = [];
        $report = $this->comment_model->getConfirmReportNote($report_id);
        $param['report_id'] = $report_id;
        $param['report'] = $report;
        $data['code'] = 200;
        $data['content'] = $this->load->view('admin/comments/report_confirm_note',$param,true);

        $this->ajaxSuccess($data);
    }
}
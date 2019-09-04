<?php

require_once APPPATH . '/core/BaseModel.php';

class Comment_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'comments';
		$this->id_name = 'comment_id';
	}

	public function getCommentForAdmin($episode_id) {
		$this->db->select('c.comment_id, c.user_id, c.content, c.timestamp, u.user_name, u.full_name, u.avatar, u.user_id');
		$this->db->from('comments c');
		$this->db->join('user u', 'u.user_id = c.user_id');
		$this->db->where('c.episode_id', $episode_id);
		$this->db->order_by('c.comment_id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getRepliesForAdmin($comment_id) {
		$this->db->select('r.replies_id, r.user_id, r.content, r.timestamp, u.user_name, u.full_name, u.avatar, u.user_id');
		$this->db->from('comment_replies r');
		$this->db->join('user u', 'u.user_id = r.user_id');
		$this->db->where('r.comment_id', $comment_id);
		$this->db->order_by('r.replies_id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function countReplies($comment_id) {
		$this->db->from('comment_replies r');
		$this->db->where('r.comment_id', $comment_id);
		return $this->db->count_all_results();
	}

	public function getCommentById($comment_id) {
		$this->db->select('c.comment_id, c.user_id, c.content, c.timestamp, u.user_name, u.full_name, u.avatar, u.user_id');
		$this->db->from('comments c');
		$this->db->join('user u', 'u.user_id = c.user_id');
		$this->db->where('c.comment_id', $comment_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function checkComment($comment_id) {
		$this->db->select('c.comment_id, c.user_id, c.episode_id');
		$this->db->from('comments c');
		$this->db->where('c.comment_id', $comment_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getReplyById($replies_id) {
		$this->db->select('r.replies_id, r.user_id, r.content, r.timestamp, u.user_name, u.full_name, u.avatar, u.user_id');
		$this->db->from('comment_replies r');
		$this->db->join('user u', 'u.user_id = r.user_id');
		$this->db->where('r.replies_id', $replies_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function checkRepliesById($replies_id) {
		$this->db->from('comment_replies r');
		$this->db->where('r.replies_id', $replies_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function insertReplies($params) {
		$this->db->insert('comment_replies', $params);
		return $this->db->insert_id();
	}

	public function updateReply($params, $id) {
		$this->db->where('replies_id', $id);
		$this->db->update('comment_replies', $params);
	}

	public function insertCommentLike($params) {
		$this->db->insert('comment_like', $params);
		return $this->db->insert_id();
	}

	public function insertRepliesLike($params) {
		$this->db->insert('replies_like', $params);
		return $this->db->insert_id();
	}

	public function deleteReplies($replies_id) {
		$this->db->where('replies_id', $replies_id);
		$this->db->delete('comment_replies');
	}

	public function removeCommentLike($user_id, $comment_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('comment_id', $comment_id);
		$this->db->delete('comment_like');
	}

	public function removeRepliesLike($user_id, $replies_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('replies_id', $replies_id);
		$this->db->delete('replies_like');
	}

	public function checkLikeComment($user_id, $comment_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('comment_id', $comment_id);
		$query = $this->db->get('comment_like');
		return $query->num_rows() > 0 ? 1 : 0;
	}

	public function checkLikeReplies($user_id, $replies_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('replies_id', $replies_id);
		$query = $this->db->get('replies_like');
		return $query->num_rows() > 0 ? 1 : 0;
	}

	public function findReport($comment_id, $reporter_id) {
		$this->db->where('comment_id', $comment_id);
		$this->db->where('reporter_id', $reporter_id);

		return $this->db->get('comment_reports')->first_row('array');
	}

	public function insertReport($comment_id, $reporter_id) {
		if ($this->findReport($comment_id, $reporter_id) != null) {
			return;
		}
		$this->db->insert('comment_reports', [
			'comment_id' => $comment_id,
			'reporter_id' => $reporter_id,
			'created_at' => time(),
		]);
	}

	public function getNumReports() {
		$this->db->from('comment_reports cr');
		$this->db->join('comments c', 'c.comment_id = cr.comment_id');
		$this->db->join('user u2', 'u2.user_id = cr.reporter_id');
		$this->db->select('cr.report_id, u1.full_name, u2.full_name as reporter_name, cr.created_at');
		return $this->db->count_all_results();
	}

	public function hasLikeComment($comment_id, $user_id) {
		$this->db->where('comment_id', $comment_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('comment_like');
		return $query->num_rows() > 0 ? 1 : 0;
	}

	public function hasLikeReplies($replies_id, $user_id) {
		$this->db->where('replies_id', $replies_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('replies_like');
		return $query->num_rows() > 0 ? 1 : 0;
	}

	public function countCommentLike($comment_id) {
		$this->db->from('comment_like');
		$this->db->where('comment_id', $comment_id);
		return $this->db->count_all_results();
	}

	public function countRepliesLike($replies_id) {
		$this->db->from('replies_like');
		$this->db->where('replies_id', $replies_id);
		return $this->db->count_all_results();
	}

	public function getAllProducts($conditions = array(), $page = 0){
        $this->makeQuery($conditions);
        if (!empty($conditions['sort_by']) && in_array($conditions['sort_by'], array('product_id','name','pv_status','total_episodes','total_comments'))) {
            if (!empty($conditions['inverse']) && $conditions['inverse'] == 1) {
                $this->db->order_by($conditions['sort_by'], 'desc');
            } else {
                $this->db->order_by($conditions['sort_by'], 'asc');
            }
        }else{
            $this->db->order_by('pv.product_id', 'desc');
        }
        if (!empty($conditions['per_page'])) {
            $per_page = $conditions['per_page'] * 1;
        } else {
            $per_page = 25;
        }
        if ($page >= 0){
            $this->db->limit($per_page, $page * $per_page);
        }

        return $this->db->get()->result_array();
    }

    public function countAllProduct($conditions = array()){
        $this->makeQuery($conditions);
        if (!empty($conditions['sort_by']) && in_array($conditions['sort_by'], array('product_id','name','pv_status','total_episodes','total_comments'))) {
            if (!empty($conditions['inverse']) && $conditions['inverse'] == 1) {
                $this->db->order_by($conditions['sort_by'], 'desc');
            } else {
                $this->db->order_by($conditions['sort_by'], 'asc');
            }
        }else{
            $this->db->order_by('pv.product_id', 'desc');
        }
        return $this->db->count_all_results();

    }

    protected function makeQuery($conditions = array()){
	    $this->db->select('pv.*, pv.status as pv_status, if(ec.total_episodes is null, 0, ec.total_episodes) as total_episodes, if(cc.total_comments is null, 0, cc.total_comments) as total_comments');
        $this->db->from('product_view pv');
        $this->db->join('(select s.product_id, count(*) as total_episodes from episode e inner join season s on s.season_id = e.season_id group by s.product_id) as ec', 'ec.product_id = pv.product_id', 'left');
        $this->db->join('(select s.product_id, count(*) as total_comments from comments c inner join episode e on c.episode_id = e.episode_id 
                          inner join season s on s.season_id = e.season_id group by s.product_id) as cc', 'cc.product_id = pv.product_id', 'left');

        if (!empty($conditions['key'])) {
            $this->makeSearchQuery(['lower(pv.product_id)','lower(pv.name)','lower(pv.status)'], strtolower($conditions['key']));
        }
    }

    protected function makeSearchQuery($searchBy = array(), $keyword) {
        $keyword = str_replace('+', ' ', $keyword);
        if (is_array($searchBy) && count($searchBy) > 0) {
            $sql = $searchBy[0] . ' like "%' . $keyword . '%"';
            for ($i = 1; $i < count($searchBy); $i++) {
                $sql .= ' or ' . $searchBy[$i] . ' like "%' . $keyword . '%"';
            }
            $this->db->where('(' . $sql . ')');
        }
    }

    public function getAllEpisode($product_ids){
	    $this->db->select('s.*,e.*');
	    $this->db->where_in('s.product_id',$product_ids);
	    $this->db->from('season s');
	    $this->db->join('episode e','e.season_id = s.season_id');
        $data = $this->db->get()->result_array();
        return $data;
    }

    public function getStory($product_id){
	    $this->db->select('pv.*');
	    $this->db->where('pv.product_id',$product_id);
	    $this->db->from('product_view pv');

        $data = $this->db->get()->result_array();
        return $data;
    }

    public function getAllSeasons($product_id){
	    $this->db->select();
	    $this->db->where('product_id',$product_id);
	    $this->db->from('season');

	    return $this->db->get()->result_array();
    }

    public function countAllBlock($season_ids,$product_id,$conditions){
        $this->makeQueryBlock($season_ids,$product_id);
        if (!empty($conditions['sort_by']) && in_array($conditions['sort_by'], array('episode_id','position', 'ep_name','e_status,','total_comments'))) {
            if (!empty($conditions['inverse']) && $conditions['inverse'] == 1) {
                $this->db->order_by($conditions['sort_by'], 'desc');
            }else {
                $this->db->order_by($conditions['sort_by'], 'asc');
            }
        }else{
            $this->db->order_by('e.episode_id', 'desc');
        }

        return $this->db->count_all_results();
    }

    public function makeQueryBlock($season_ids,$product_id){
	    $this->db->select('s.*,e.*,e.status as e_status,e.name as ep_name,if(cc.total_comments is null, 0, cc.total_comments) as total_comments');
	    $this->db->where('s.product_id',$product_id);
	    $this->db->where_in('e.season_id',$season_ids);
	    $this->db->from('season s');
	    $this->db->join('episode e','e.season_id=s.season_id');
        $this->db->join('(select e.episode_id, count(*) as total_comments from comments c inner join episode e on c.episode_id = e.episode_id group by e.episode_id) as cc', 'cc.episode_id = e.episode_id', 'left');
    }

    public function getAllBlocks($season_ids,$product_id,$conditions = array(), $page = 0){
        $this->makeQueryBlock($season_ids,$product_id);
        if (!empty($conditions['sort_by']) && in_array($conditions['sort_by'], array('episode_id','position', 'ep_name','e_status'))) {
            if (!empty($conditions['inverse']) && $conditions['inverse'] == 1) {
                $this->db->order_by($conditions['sort_by'], 'desc');
            }else {
                $this->db->order_by($conditions['sort_by'], 'asc');
            }
        }else{
            $this->db->order_by('e.position');
            $this->db->order_by('e.episode_id', 'desc');
        }
        if (!empty($conditions['per_page'])) {
            $per_page = $conditions['per_page'] * 1;
        } else {
            $per_page = 25;
        }
        if ($page >= 0){
            $this->db->limit($per_page, $page * $per_page);
        }

        return $this->db->get()->result_array();
    }

    public function getStoryName($episode_id){
	    $this->db->select('pv.name');
	    $this->db->where('e.episode_id',$episode_id);
	    $this->db->from('episode e');
	    $this->db->join('season s','s.season_id=e.season_id');
	    $this->db->join('product_view pv','s.product_id=pv.product_id');

        return $this->db->get()->result_array();
    }

    public function getEpisodeName($episode_id){
	    $this->db->select('e.*,s.product_id');
	    $this->db->where('e.episode_id',$episode_id);
	    $this->db->from('episode e');
	    $this->db->join('season s','s.season_id=e.season_id');

        return $this->db->get()->result_array();
    }

    public function countAllComments($episode_id, $conditions){
        $this->makeQueryComment($episode_id);
        if (!empty($conditions['sort_by']) && in_array($conditions['sort_by'], array('comment_id','full_name', 'content','timestamp','status'))) {
            if (!empty($conditions['inverse']) && $conditions['inverse'] == 1) {
                $this->db->order_by($conditions['sort_by'], 'desc');
            }else {
                $this->db->order_by($conditions['sort_by'], 'asc');
            }
        }else{
            $this->db->order_by('c.comment_id', 'desc');
        }

        return $this->db->count_all_results();
    }

    public function getComments($episode_id,$conditions = array(), $page = 0){
        $this->makeQueryComment($episode_id);
        if (!empty($conditions['sort_by']) && in_array($conditions['sort_by'], array('comment_id','full_name', 'content','timestamp','status','total_like','total_reply',))) {
            if (!empty($conditions['inverse']) && $conditions['inverse'] == 1) {
                $this->db->order_by($conditions['sort_by'], 'desc');
            }else {
                $this->db->order_by($conditions['sort_by'], 'asc');
            }
        }else{
            $this->db->order_by('total_like', 'desc');
            $this->db->order_by('c.comment_id', 'desc');
        }
        if (!empty($conditions['per_page'])) {
            $per_page = $conditions['per_page'] * 1;
        } else {
            $per_page = 25;
        }
        if ($page >= 0){
            $this->db->limit($per_page, $page * $per_page);
        }

        $data = $this->db->get()->result_array();

        return $data;
    }

    public function makeQueryComment($episode_id){
	    $this->db->select('c.*,u.full_name,u.user_name,if(cls.total_like is null, 0, cls.total_like) as total_like,if(crs.total_reply is null, 0, crs.total_reply) as total_reply');
	    $this->db->where('c.episode_id',$episode_id);
	    $this->db->from('comments c');
	    $this->db->where('c.is_deleted', 0);
	    $this->db->join('user u','c.user_id=u.user_id','LEFT');
        $this->db->join('(select c.comment_id, count(*) as total_like from comment_like cl inner join comments c on cl.comment_id = c.comment_id group by c.comment_id) as cls', 'cls.comment_id = c.comment_id', 'left');
        $this->db->join('(select c.comment_id, count(*) as total_reply from comment_replies cr inner join comments c on cr.comment_id = c.comment_id group by c.comment_id) as crs', 'crs.comment_id = c.comment_id', 'left');

	}

    public function getCommentLikes($comment_ids){
        $this->db->select('cl.*');
        $this->db->where_in('cl.comment_id',$comment_ids);
        $this->db->from('comment_like cl');

        return $this->db->get()->result_array();
    }

    public function disableComment($comment_id){
	    $this->db->where('comment_id',$comment_id);
	    $this->db->update('comments',['status' => 0]);
    }

    public function enableComment($comment_id){
        $this->db->where('comment_id',$comment_id);
        $this->db->update('comments',['status' => 1]);
    }
    public function deleteComment($comment_id){
        $this->db->where('comment_id',$comment_id);
        $this->db->delete('comments');
    }

    public function getTitleReplies($comment_id){
	    $this->db->select('e.name as episode_name,pv.name as film_name');
	    $this->db->where('c.comment_id',$comment_id);
	    $this->db->from('comments c');
	    $this->db->join('episode e','e.episode_id=c.episode_id');
	    $this->db->join('season s','e.season_id=s.season_id');
	    $this->db->join('product_view pv','s.product_id=pv.product_id');

        return $this->db->get()->result_array();
    }

    public function getMainComment($comment_id){
	    $this->db->select('c.timestamp,c.comment_id,c.user_id,c.content,u.user_name,c.status');
	    $this->db->where('c.comment_id',$comment_id);
	    $this->db->from('comments c');
	    $this->db->join('user u','c.user_id=u.user_id');

        return $this->db->get()->result_array();

    }

    public function getLikeMainComment($comment_id){
	    $this->db->select('cl.*');
	    $this->db->where('cl.comment_id',$comment_id);
	    $this->db->from('comment_like cl');

        return $this->db->get()->result_array();
    }

    public function getReplies($comment_id){
	    $this->db->select('cr.replies_id,cr.user_id,cr.status,cr.content,cr.timestamp,u.user_name as replies_username');
	    $this->db->where('comment_id',$comment_id);
	    $this->db->from('comment_replies cr');
	    $this->db->join('user u','u.user_id=cr.user_id');

        return $this->db->get()->result_array();
    }

    public function getReplyLikes($reply_ids){
	    $this->db->select('rl.*');
	    $this->db->where_in('rl.replies_id',$reply_ids);
        $this->db->from('replies_like rl');

        return $this->db->get()->result_array();
    }

    public function disableReply($replies_id){
	    $this->db->where('replies_id',$replies_id);
	    $this->db->update('comment_replies',['status'=>0]);
    }

    public function enableReply($replies_id){
        $this->db->where('replies_id',$replies_id);
        $this->db->update('comment_replies',['status'=>1]);
    }

    /**
     * @param $replies_id
     */
    public function confirmDeleteReply($replies_id){
	    $this->db->where('replies_id',$replies_id);
	    $this->db->delete('comment_replies');
    }

    public function countAllCommentReports($conditions){
        $this->makeQueryReportComment($conditions);
        if (!empty($conditions['sort_by']) && in_array($conditions['sort_by'], array('report_id','reported_name', 'content','reporter_name','status'))) {
            if (!empty($conditions['inverse']) && $conditions['inverse'] == 1) {
                $this->db->order_by($conditions['sort_by'], 'desc');
            }else {
                $this->db->order_by($conditions['sort_by'], 'asc');
            }
        }else{
            $this->db->order_by('c.report_id', 'desc');
        }
        if (!empty($conditions['per_page'])) {
            $per_page = $conditions['per_page'] * 1;
        } else {
            $per_page = 25;
        }
        return $this->db->count_all_results();
    }

    public function getReports($conditions = array(),$page = 0) {
        $this->makeQueryReportComment($conditions);
        if (!empty($conditions['sort_by']) && in_array($conditions['sort_by'], array('report_id','reported_name', 'content','reporter_name','created_at','status'))) {
            if (!empty($conditions['inverse']) && $conditions['inverse'] == 1) {
                $this->db->order_by($conditions['sort_by'], 'desc');
            }else {
                $this->db->order_by($conditions['sort_by'], 'asc');
            }
        }else{
            $this->db->order_by('crp.report_id', 'desc');
        }
        if (!empty($conditions['per_page'])) {
            $per_page = $conditions['per_page'] * 1;
        } else {
            $per_page = 25;
        }
        if ($page >= 0){
            $this->db->limit($per_page, $page * $per_page);
        }

        return $this->db->get()->result_array();
    }

    public function makeQueryReportComment($conditions = array()){
        $this->db->select('crp.report_id,c.content as comment_content,c.status as comment_status,
         crp.content,crp.created_at,crp.status as report_status,u1.user_name as reported_short,
         u1.user_id,u1.full_name as reported_name,u2.user_name as reporter_short,
         u2.full_name as reporter_name,c.is_deleted,crp.comment_id');
        $this->db->from('comment_reports crp');
        $this->db->join('comments c','crp.comment_id=c.comment_id');
        $this->db->join('user u1','c.user_id=u1.user_id');
        $this->db->join('user u2','crp.reporter_id=u2.user_id');

    }

    public function updateCommentReportNote($report_id,$note){
        $this->db->where('report_id',$report_id);
        $this->db->update('comment_reports',['content'=>$note]);
    }

    public function getCommentReportNote($report_id){
        $this->db->select();
        $this->db->where('report_id',$report_id);
        $this->db->from('comment_reports');

        return $this->db->get()->result_array();
    }

    public function disableCommentReported($comment_id){
        $this->db->where('comment_id',$comment_id);
        $this->db->update('comments',['status'=>0]);
    }

    public function enableCommentReported($comment_id){
        $this->db->where('comment_id',$comment_id);
        $this->db->update('comments',['status'=>1]);
    }

    public function confirmDeleteReportedComment($comment_id){
        $this->db->where('comment_id',$comment_id);
        $this->db->update('comments',['is_deleted'=>1]);
    }

    public function getConfirmReportNote($report_id){
        $this->db->select('cr.content');
        $this->db->where('report_id',$report_id);
        $this->db->from('comment_reports cr');

        return $this->db->get()->result_array();
    }

	public function getUserCommentReplies($comment_id){
		$this->db->select('c.*,e.*,p.name as film_name');
		$this->db->from('comments c');
		$this->db->where('comment_id', $comment_id);
		$this->db->join('episode e', 'c.episode_id = e.episode_id');
		$this->db->join('season s', 'e.season_id = e.season_id');
		$this->db->join('product p', 's.product_id = p.product_id');

		return $this->db->get()->result_array();
	}

	public function countBlockComments($episode_id) {
		$this->db->from('comments c');
		$this->db->where('c.episode_id', $episode_id);
		$this->db->where('c.is_deleted', 0);
		$this->db->where('c.status', 1);
		return $this->db->count_all_results();
	}

	public function countAllBlockReplies($episode_id) {
		$this->db->from('comment_replies er');
		$this->db->join('comments ec', 'ec.comment_id = er.comment_id');
		$this->db->where('er.status', 1);
		$this->db->where('ec.episode_id', $episode_id);
		return $this->db->count_all_results();
	}

	public function getBlockComments($episode_id, $type, $page = -1) {
		$this->db->select('c.comment_id, c.user_id, c.content, c.timestamp, u.user_name, u.full_name, u.avatar, u.user_id, if(l.num_of_likes is null, 0, l.num_of_likes) as num_like');
		$this->db->from('comments c');
		$this->db->join('user u', 'u.user_id = c.user_id');
		$subQuery = 'select comment_id, count(*) as num_of_likes from comment_like group by comment_id';
		$this->db->join("($subQuery) l", 'l.comment_id = c.comment_id', 'left');
		$this->db->where('c.episode_id', $episode_id);
		$this->db->where('c.is_deleted', 0);
		$this->db->where('c.status', 1);
		$this->db->group_by('c.comment_id');
		$this->db->order_by('num_like', 'desc');
		$this->db->order_by('c.comment_id', 'asc');
		if ($page >= 0)
			$this->db->limit(10, 10 * $page);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getCommentReplies($comment_id) {
		$this->db->select('r.replies_id, r.user_id, r.content, r.timestamp, u.user_name, u.full_name, u.avatar, u.user_id, if(rl.num_of_likes is null, 0, rl.num_of_likes) as num_like');
		$this->db->from('comment_replies r');
		$this->db->join('user u', 'u.user_id = r.user_id');
		$subQuery = 'select replies_id, count(*) as num_of_likes from replies_like group by replies_id';
		$this->db->join("($subQuery) rl", 'rl.replies_id = r.replies_id', 'left');
		$this->db->where('r.comment_id', $comment_id);
		$this->db->where('r.status', 1);
		$this->db->order_by('num_like', 'desc');
		$this->db->order_by('r.replies_id', 'asc');
		$query = $this->db->get();
		return $query->result_array();
	}
}
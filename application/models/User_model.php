<?php

require_once APPPATH . '/core/BaseModel.php';

class User_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'user';
		$this->id_name = 'user_id';
	}

	public function getUserById($user_id) {
		return $this->getObjectById($user_id);
	}

	public function insert($params) {
		$user_id = parent::insert($params);
		$this->db->insert("user_notification_setting", ['user_id' => $user_id]);
		return $user_id;
	}

	public function getUserForAdmin($user_id) {
	    $this->db->select('u.*,fp.id as feature_id');
	    $this->db->from('user u');
		$this->db->where('u.user_id', $user_id);
		$this->db->join('featured_profiles fp','u.user_id = fp.user_id','LEFT');
        $query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getProfileUser($user_id) {
		$this->db->where('status', 1);
		$this->db->where($this->id_name, $user_id);
		$query = $this->db->get('user_view');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getByUsername($user_name) {
		$this->db->where('status', 1);
		$this->db->where('user_name', $user_name);
		$query = $this->db->get('user_view');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getByEmail($email) {
		$this->db->where('status', 1);
		$this->db->where('email', $email);
		$query = $this->db->get('user_view');
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getUserByFacebookId($facebook_id) {
		$this->db->from('user');
		$this->db->where('facebook_id', $facebook_id);
		$user = $this->db->get()->first_row('array');
		return $user;
	}

	public function getUserByGoogleId($google_id) {
		$this->db->from('user');
		$this->db->where('google_id', $google_id);
		$user = $this->db->get()->first_row('array');
		return $user;
	}

	public function searchUser($user_name, $user_id = -1, $user_type = -1) {
		$this->db->from('user u');
		$this->db->join('user_follow uf', 'uf.follower_id = u.user_id', 'left');
		$this->db->select('u.user_id, user_name, user_type, avatar, full_name, count(if(uf.user_id is not null, 1, 0)) as followers');
		if ($user_id != -1) {
			$this->db->where('u.user_id !=', $user_id);
		}
		if ($user_type != -1) {
			$this->db->where('u.user_type', $user_type);
		}
		$this->db->where('u.status', 1);
		$this->db->where('(user_name like "%' . $user_name . '%" or full_name like "%' . $user_name . '%")');
		$this->db->group_by('u.user_id');
		$this->db->order_by('followers', 'desc');
		$this->db->order_by('user_name');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getUsers($user_id = -1, $user_type = -1) {
		$this->db->from('user u');
		$this->db->join('user_follow uf', 'uf.follower_id = u.user_id', 'left');
		$this->db->select('u.user_id, user_name, user_type, avatar, full_name, count(if(uf.user_id is not null, 1, 0)) as followers');
		if ($user_id != -1) {
			$this->db->where('u.user_id !=', $user_id);
		}
		if ($user_type != -1) {
			$this->db->where('u.user_type', $user_type);
		}
		$this->db->where('u.status', 1);
		$this->db->group_by('u.user_id');
		$this->db->order_by('followers', 'desc');
		$this->db->order_by('user_name');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function checkInWatchList($product_id, $user_id) {
		$this->db->where('product_id', $product_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('watch_list');

		return $query->first_row('array');
	}

	public function addWatch($params) {
		$this->db->insert('watch_list', $params);
		return $this->db->insert_id();
	}

	public function addFollow($params) {
		$this->db->insert('user_follow', $params);
		return $this->db->insert_id();
	}

	public function removeFollow($user_id, $follower_id) {
		$this->db->where('follower_id', $follower_id);
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_follow');
	}

	public function checkWatchList($user_id, $product_id) {
		$this->db->select('id');
		$this->db->where('product_id', $product_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('watch_list');
		return $query->num_rows() > 0;
	}

	public function getWatch($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('user_watch');
		return $query->first_row('array');
	}

	public function removeWatchList($user_id, $product_id) {
		if ($product_id != 0) {
			$this->db->where('product_id', $product_id);
		}
		$this->db->where('user_id', $user_id);
		$this->db->delete('watch_list');
	}

	public function removeWatch($id) {
		$this->db->where('id', $id);
		$this->db->delete('user_watch');
	}

	public function checkFollower($user_id, $follower_id) {
		$this->db->select('follow_id');
		$this->db->where('follower_id', $follower_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('user_follow');
		return $query->num_rows() > 0;
	}

	public function checkEpisodeExits($user_id, $episode_id) {
		$this->db->select('status');
		$this->db->where('episode_id', $episode_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('episode_like');
		if ($query->num_rows() > 0) {
			$item = $query->first_row('array');
			return $item['status'];
		} else {
			return -1;
		}
	}

	public function getAvatarUser($user_id) {
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('user');
		if ($query->num_rows() > 0) {
			$item = $query->first_row('array');
			return $item['avatar'];
		} else {
			return '';
		}
	}

	public function addLike($params) {
		$this->db->insert('episode_like', $params);
		return $this->db->insert_id();
	}

	public function updateLike($episode_id, $user_id, $status) {
		$this->db->where('episode_id', $episode_id);
		$this->db->where('user_id', $user_id);
		$this->db->update('episode_like', array('status' => $status));
	}

	public function getLike($id) {
		$this->db->where('id', $id);
		$query = $this->db->get('episode_like');
		return $query->first_row('array');
	}

	public function removeLike($id) {
		$this->db->where('id', $id);
		$this->db->update('episode_like', array('status' => 0));
	}

	public function removeProductLike($id) {
		$this->db->where('id', $id);
		$this->db->delete('product_likes');
	}

	public function deleteLike($episode_id, $user_id) {
		$this->db->where('episode_id', $episode_id);
		$this->db->where('user_id', $user_id);
		$this->db->delete('episode_like');
	}

	public function getFollowing($user_id, $page = -1) {
		$this->db->select('u.user_id, uf.follower_id, u.user_name, u.full_name, u.avatar');
		$this->db->from('user_follow uf');
		$this->db->join('user u', 'u.user_id = uf.follower_id');
		$this->db->where('uf.user_id', $user_id);
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getFollowers($follower_id, $page = -1) {
		$this->db->select('u.user_id, uf.follower_id, u.user_name, u.full_name, u.avatar');
		$this->db->from('user_follow uf');
		$this->db->join('user u', 'u.user_id = uf.user_id');
		$this->db->where('uf.follower_id', $follower_id);
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getFollowersInList($follower_id, $user_ids) {
		$this->db->select('u.user_id, u.user_name, u.full_name, u.avatar');
		$this->db->from('user_follow uf');
		$this->db->join('user u', 'u.user_id = uf.user_id');
		$this->db->where('uf.follower_id', $follower_id);
		$this->db->where_in('uf.user_id', $user_ids);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getUserListByIds($user_id, $user_ids) {
		$this->db->select('u.user_id, u.user_name, u.full_name, u.avatar');
		$this->db->from('user u');
		$this->db->where('u.user_id <>', $user_id);
		$this->db->where_in('u.user_id', $user_ids);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get10BlockUsers($user_id, $page = -1, $limit = 12) {
//		$this->db->select('u.user_id, u.user_name, u.full_name, u.avatar, uf1.user_id as follower_id, uf2.follower_id as following_id , cf.contact_id, fp.user_id as featured_user_id');
		$this->db->select('u.user_id, u.user_name, u.full_name, u.avatar');
		$this->db->from('user u');
		// Followers user
		$this->db->join('(select user_id from user_follow where follower_id = ' . $user_id . ') as uf1', 'uf1.user_id = u.user_id', 'left');
		// Following users
		$this->db->join('(select follower_id from user_follow where user_id = ' . $user_id . ') as uf2', 'uf2.follower_id = u.user_id', 'left');
		$this->db->join('(select contact_id from contact_friends where user_id = ' . $user_id . ') as cf ', 'cf.contact_id = u.user_id', 'left');
		$this->db->join('(select user_id, priority as fp_priority from featured_profiles where status = 1) as fp ', 'fp.user_id = u.user_id', 'left');
		$this->db->order_by('uf1.user_id', 'desc');
		$this->db->order_by('uf2.follower_id', 'desc');
		$this->db->order_by('cf.contact_id', 'desc');
		$this->db->order_by('fp.user_id', 'desc');
		$this->db->order_by('u.user_name');
		if ($page >= 0) {
			$this->db->limit($limit, $limit * $page);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function countFollowing($user_id) {
		$this->db->from('user_follow');
		$this->db->where('user_id', $user_id);
		return $this->db->count_all_results();
	}

	public function countFollowers($follower_id) {
		$this->db->from('user_follow');
		$this->db->where('follower_id', $follower_id);
		return $this->db->count_all_results();
	}

	public function getTerms() {
		$this->db->from('user_terms');
		$result = $this->db->get();
		if ($result->num_rows() > 0) {
			return $result->first_row('array');
		} else {
			return null;
		}
	}

	public function getUidByAccount($account, $password) {
		$sql = 'select user_id from user '
			. "where (upper(email) = upper('$account') or upper(user_name) = upper('$account')) "
			. "and password=md5('$password') and status = 1";
		$result = $this->db->query($sql);

		if ($result->num_rows() > 0) {
			$item = $result->first_row('array');
			return $item['user_id'];
		} else {
			return -1;
		}
	}

	public function updateDeviceUser($user_id, $device_id) {
		if ($this->checkDeviceId($device_id)) {
			$this->db->where('device_id', $device_id);
			$this->db->update('device_user', [
				'user_id' => $user_id,
				'last_activity' => time()
			]);
		} else {
			$this->db->insert('device_user', [
				'user_id' => $user_id,
				'dtype_id' => strlen($device_id) == 36 ? 2 : 1,
				'regtime' => time(),
				'device_id' => $device_id,
				'last_activity' => time()]);
		}
	}

	public function logoutDevice($user_id, $device_id) {
		$this->db->where('device_id', $device_id);
		$this->db->where('user_id', $user_id);
		$this->db->update('device_user', array('user_id' => 0));
	}

	public function checkDeviceId($device_id) {
		$this->db->where('device_id', $device_id);
		$query = $this->db->get('device_user');
		return $query->num_rows() > 0;
	}

	public function checkEmail($email) {
		$sql = "select user_id, full_name from user where upper(email) = upper('$email')";
		$result = $this->db->query($sql);
		return $result->num_rows() > 0 ? $result->first_row('array') : null;
	}

	public function checkUserName($user_name) {
		$sql = "select user_id from user where upper(user_name) = upper('$user_name')";
		$result = $this->db->query($sql);
		if ($result->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function checkUid($user_id) {
		$sql = "select user_id from user where user_id ='$user_id'";
		$result = $this->db->query($sql);
		return $result->num_rows() > 0;
	}

	public function insertTimeUse($params) {
		$this->db->insert('user_timeuse', $params);
	}

	public function getNumOfUser() {
		$this->db->select();
		$this->db->from('user');
		return $this->db->count_all_results();
	}

	public function getUsersForAdmin($status) {
		$sql = "select ul.*, count(up.pick_id) as total_pick 
					from user_picks as up right join (select uc.*, count(el.episode_id) as total_like 
						from episode_like as el right join (select u.*, count(c.comment_id) as total_comment, dt.name as device_name
				    		from (((user as u 
							    left join comments as c on u.user_id = c.user_id)
							    left join device_user as du on u.user_id = du.user_id)
							    left join device_type as dt on du.dtype_id = dt.dtype_id)
							    where u.status = '$status'
				    			group by user_id) uc on uc.user_id = el.user_id
				    		group by user_id) ul on ul.user_id = up.user_id
				    	group by ul.user_id
				    	order by user_id desc";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getAllUsers($conditions = array(), $page = 0) {
        $this->makeQuery($conditions);
        if (!empty($conditions['sort_by']) && in_array($conditions['sort_by'], array('user_id','full_name', 'email', 'joined','status'))) {
            if (!empty($conditions['inverse']) && $conditions['inverse'] == 1) {
                $this->db->order_by($conditions['sort_by'], 'desc');
            }else {
                $this->db->order_by($conditions['sort_by'], 'asc');
            }
        }else{
            $this->db->order_by('u.user_id', 'desc');
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

	public function countAllUser($conditions = array()){
        $this->makeQuery($conditions);
        if (!empty($conditions['sort_by']) && in_array($conditions['sort_by'], array('user_id','full_name', 'email', 'joined','status'))) {
            if (!empty($conditions['inverse']) && $conditions['inverse'] == 1) {
                $this->db->order_by($conditions['sort_by'], 'desc');
            }else {
                $this->db->order_by($conditions['sort_by'], 'asc');
            }
        }else{
            $this->db->order_by('u.user_id', 'desc');
        }
        return $this->db->count_all_results();
    }

    protected function makeQuery($conditions = array()) {
	    $this->db->select('u.*, u.status as user_status');
        $this->db->where('u.is_deleted',0);

        if (!empty($conditions['get_status']) && $conditions['get_status'] == 'enabled') {
            $this->db->where('u.status',1);
        }

        if (!empty($conditions['get_status']) && $conditions['get_status'] == 'disabled') {
            $this->db->where('u.status',0);
        }

	    $this->db->from('user u');

        if (!empty($conditions['key'])) {
            $this->makeSearchQuery(['lower(u.user_name)','lower(u.full_name)','lower(u.user_id)','lower(u.email)'], strtolower($conditions['key']));
        }
    }

    public function getVersion($user_ids){

	    $this->db->select('du.*,dt.*');
        $this->db->where_in('du.user_id', $user_ids);
        $this->db->from('device_user du');
        $this->db->join('device_type dt','du.dtype_id = dt.dtype_id');
        $data = $this->db->get()->result_array();
        return $data;
    }

    public function getAllPick($user_ids){
        $this->db->select('up.*');
        $this->db->where_in('up.user_id', $user_ids);
        $this->db->from('user_picks up');
        $data = $this->db->get()->result_array();
        return $data;

    }

    public function getAllLike($user_ids){
        $this->db->select('el.*');
        $this->db->where_in('el.user_id',$user_ids);
        $this->db->from('episode_like el');
        $data = $this->db->get()->result_array();
        return $data;
    }

    public function getProductLike($user_ids){
	    $this->db->select('pl.*');
	    $this->db->where_in('pl.user_id',$user_ids);
	    $this->db->from('product_likes pl');
        $data = $this->db->get()->result_array();
        return $data;
    }

    public function getCommentLike($user_ids){
        $this->db->select('cl.*');
//        $this->db->where_in('cl.user_id',$user_ids);
        $this->db->from('comment_like cl');
        $data = $this->db->get()->result_array();
        return $data;
    }

    public function getAllComment($user_ids){
        $this->db->select('c.*');
        $this->db->where_in('c.user_id',$user_ids);
        $this->db->from('comments c');
        $data = $this->db->get()->result_array();
        return $data;

    }

	public function clearData($user_id) {
		$this->db->trans_start();
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_watch');
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_follow');
		$this->db->where('follower_id', $user_id);
		$this->db->delete('user_follow');
		$this->db->where('user_id', $user_id);
		$this->db->delete('episode_like');
		$this->db->where('user_id', $user_id);
		$this->db->delete('comments');
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_notify');
		$this->db->like('data', '"user_id":' . $user_id);
		$this->db->delete('user_notify');
		$this->db->trans_complete();
	}

	public function insertCodeResetPassword($params) {
		$this->db->insert("code_reset_password", $params);
		return $this->db->insert_id();
	}

	public function updateCodeResetPassword($user_id, $code) {
		$this->db->where('user_id', $user_id);
		$this->db->where('code', $code);
		$this->db->update("code_reset_password", array('has_reset' => 1, 'created' => time()));
	}

	public function checkCode($user_id, $code) {
		if ($code == '') {
			return 1;
		}
		$sql = "select * from code_reset_password where code = '$code' and user_id='$user_id'";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			$row = $query->first_row('array');
			if ($row['has_reset'] == 1) {
				return 2;
			} else {
				if ($row['created'] + 86400 < time()) {
					return 3;
				}
			}
			return 0;
		} else {
			return 1;
		}
	}
	// public function getCodeResetPassword($code)
	// {
	// 	$sql = "select * from code_reset_password where code = '$code' and collation_name = 'latin1_general_ci'";
	// 	$query = $this->db->query($sql);
	// 	return $query->num_rows();
	// }

	public function getUserByAccount($account) {
		$this->db->from('user');
		$this->db->where("((upper(email) = upper('$account') or upper(user_name) = upper('$account')))");
		$this->db->where("status", 1);
		$user = $this->db->get()->first_row('array');
		return $user;
	}

	public function getFeaturedProfiles($page = -1, $limit = 24) {
		$this->db->from('featured_profiles fp');
		$this->db->join('user u', 'fp.user_id = u.user_id');
		$this->db->select('u.user_id, user_name, user_type, full_name, email, avatar, level, joined');
		$this->db->where('u.status', 1);
		$this->db->where('fp.status', 1);
		$this->db->group_by('u.user_id');
		$this->db->order_by('fp.priority');
		$this->db->order_by('user_name');
		if ($page >= 0) {
			$this->db->limit($limit, $limit * $page);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getSuggestedProfiles($user_id, $page = -1, $limit = 24) {
		$this->db->from('(select follower_id from user_follow where user_id = ' . $user_id . ') uf1');
		$this->db->join('user_follow uf2', 'uf2.user_id = uf1.follower_id');
		$this->db->join('user u', 'uf2.follower_id = u.user_id');
		$this->db->join('(select follower_id from user_follow where user_id = ' . $user_id . ') uf3', 'uf3.follower_id = uf2.follower_id', 'left');

		$this->db->join('(select user_id, count(*) as num_of_reviews from user_picks group by user_id) as ur', 'ur.user_id = uf2.follower_id', 'left');
		$this->db->join('(select user_id, count(*) as num_of_comments from comments group by user_id) as uc', 'uc.user_id = uf2.follower_id', 'left');
		$this->db->join('(select follower_id, count(*) as num_of_followers from user_follow group by follower_id) as uf4', 'uf4.follower_id = uf2.follower_id', 'left');

		$this->db->select('uf2.follower_id, u.user_id, u.user_name, u.user_type, u.full_name, u.email, u.avatar, u.level, u.joined, num_of_reviews, num_of_comments, num_of_followers');

		$this->db->where('uf3.follower_id is null');
		$this->db->where('u.status', 1);
		$this->db->group_by('uf2.follower_id');
		$this->db->order_by('ur.num_of_reviews', 'desc');
		$this->db->order_by('uc.num_of_comments', 'desc');
		$this->db->order_by('uf4.num_of_followers', 'desc');
		$this->db->order_by('user_name');
		if ($page >= 0) {
			$this->db->limit($limit, $limit * $page);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getUserPick($user_id, $product_id) {
		$this->db->where('product_id', $product_id);
		$this->db->where('user_id', $user_id);
		return $this->db->get('user_picks')->first_row('array');
	}

	public function getPick($pick_id) {
		$this->db->select('p.*,u.*, up.user_id, up.pick_id, up.quote');
		$this->db->from('user_picks up');
		$this->db->where('up.pick_id', $pick_id);
		$this->db->join('product_view p', 'p.product_id = up.product_id');
		$this->db->join('user u','u.user_id = up.user_id');
		return $this->db->get()->first_row('array');
	}

	public function insertPick($params) {
		$this->db->insert('user_picks', $params);
	}

	public function updatePick($params, $pick_id) {
		$this->db->where('pick_id', $pick_id);
		$this->db->update('user_picks', $params);
	}

	public function removePick($pick_id) {
		$this->db->where('pick_id', $pick_id);
		$this->db->delete('user_picks');
	}

	public function getProfileConfigs($user_id) {
		$this->db->where('user_id', $user_id);
		$item = $this->db->get('user_profile_configs')->first_row('array');
		if ($item != null) {
			return $item;
		}
		return [
			'user_id' => $user_id,
			'picks_enabled' => 1,
			'continue_enabled' => 1,
			'watch_enabled' => 1,
			'thumbs_up_enabled' => 1,
		];
	}

	public function updateProfileConfigs($configs, $user_id) {
		$this->db->where('user_id', $user_id);
		$item = $this->db->get('user_profile_configs')->first_row('array');
		if ($item != null) {
			$this->db->update('user_profile_configs', $configs);
		} else {
			$configs['user_id'] = $user_id;
			$this->db->insert('user_profile_configs', $configs);
		}
	}

	public function getTopPicks() {
		$this->db->select('up.pick_id, up.user_id, p.*, up.quote, u.user_name, u.full_name, u.avatar');
		$this->db->from('user_picks up');
		$this->db->join('product_view p', 'p.product_id = up.product_id');
		$this->db->join('user u', 'u.user_id = up.user_id');
		$this->db->group_by('up.pick_id');
		$this->db->order_by('up.pick_id', 'desc');
		$this->db->limit(25);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getFriendsTopPicks($user_id) {
		$this->db->select('up.pick_id, up.user_id, p.*, up.quote, u.user_name, u.full_name, u.avatar, uf.follower_id');
		$this->db->from('user_picks up');
		$this->db->join('product_view p', 'p.product_id = up.product_id');
		$this->db->join('(select * from user_follow where user_id = ' . $user_id . ') uf', 'uf.follower_id = up.user_id');
		$this->db->join('user u', 'u.user_id = up.user_id');
		$this->db->group_by('up.pick_id');
		$this->db->order_by('up.pick_id', 'desc');
//		$this->db->limit(25);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function findReport($user_id, $reporter_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('reporter_id', $reporter_id);

		return $this->db->get('user_reports')->first_row('array');
	}

	public function insertReport($user_id, $reporter_id) {
		if ($this->findReport($user_id, $reporter_id) != null) {
			return;
		}
		$this->db->insert('user_reports', [
			'user_id' => $user_id,
			'reporter_id' => $reporter_id,
			'created_at' => time(),
		]);
	}

	public function getNumReports() {
        $this->db->select();
        $this->db->from('user_reports ur');
		$this->db->join('user u1', 'u1.user_id = ur.user_id');
		$this->db->join('user u2', 'u2.user_id = ur.reporter_id');
		$this->db->select('ur.report_id, u1.full_name, u2.full_name as reporter_name, ur.created_at');
		return $this->db->count_all_results();
	}

	public function getAllReports($conditions = array()){
        $this->db->from('user_reports up');
        $this->db->join('user ut1', 'ut1.user_id = up.user_id');
        $this->db->join('user ut2', 'ut2.user_id = up.reporter_id');
        $this->db->select('up.report_id,up.report_note, ut1.*, ut2.full_name as reporter_name,ut2.user_name as reporter_user_name, up.created_at,up.status as report_status');

        if (!empty($conditions['key'])) {
            $this->makeSearchQuery(['lower(up.report_id)','lower(ut1.full_name)','lower(ut2.full_name)','lower(up.created_at)'], strtolower($conditions['key']));
        }

        if (!empty($conditions['sort_by']) && in_array($conditions['sort_by'], array('report_id', 'full_name', 'reporter_name','status'))) {
            if (!empty($conditions['inverse']) && $conditions['inverse'] == 1) {
                $this->db->order_by($conditions['sort_by'], 'desc');
            }else {
                $this->db->order_by($conditions['sort_by'], 'asc');
            }
        }else{
            $this->db->order_by('up.report_id', 'desc');
        }
    }

	public function getReports($conditions = array(), $page = 0) {
		$this->db->from('user_reports ur');
		$this->db->join('user u1', 'u1.user_id = ur.user_id');
		$this->db->join('user u2', 'u2.user_id = ur.reporter_id');
		$this->db->select('ur.report_id,ur.report_note, u1.*, u2.full_name as reporter_name,u2.user_name as reporter_user_name, ur.created_at,ur.status as report_status');

        if (!empty($conditions['key'])) {
            $this->makeSearchQuery(['lower(ur.report_id)','lower(u1.full_name)','lower(u2.full_name)','lower(ur.created_at)'], strtolower($conditions['key']));
        }

        if (!empty($conditions['sort_by']) && in_array($conditions['sort_by'], array('report_id', 'full_name', 'reporter_name','status'))) {
            if (!empty($conditions['inverse']) && $conditions['inverse'] == 1) {
                $this->db->order_by($conditions['sort_by'], 'desc');
            }else {
                $this->db->order_by($conditions['sort_by'], 'asc');
            }
        }else{
            $this->db->order_by('ur.report_id', 'desc');
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

	public function updateReportNote($report_id,$note){
        $this->db->where('report_id', $report_id);
        $this->db->update('user_reports', ['report_note' => $note]);

    }

    public function getReportNote($report_id){
	    $this->db->select();
        $this->db->where('report_id', $report_id);
        $this->db->from('user_reports');
        return $this->db->get()->result_array();
    }

	public function countContactFriends($user_id) {
		$this->db->from('contact_friends cf');
		$this->db->join('contact_contacts cc', 'cc.contact_id = cf.contact_id');
		$this->db->join('user u', 'u.user_id = cc.reference_id');

		$this->db->where('cf.user_id', $user_id);
		$this->db->where('(cc.contact_type = ' . CONTACT_TYPE_EMAIL . ' or cc.contact_type = ' . CONTACT_TYPE_PHONE . ')');
		return $this->db->count_all_results();
	}

	public function getContactFriends($user_id, $page = -1) {
		$this->db->select('u.user_id, u.user_name, u.full_name, u.avatar, u.user_type, u.email, u.phone_number, if(uf.follower_id is null, 0, 1) as is_follow');
		$this->db->from('contact_friends cf');
		$this->db->join('contact_contacts cc', 'cc.contact_id = cf.contact_id');
		$this->db->join('user u', 'u.user_id = cc.reference_id');
		$this->db->join('(select * from user_follow where user_id = ' . $user_id . ') uf', 'uf.follower_id = cc.reference_id', 'left');

		$this->db->where('cf.user_id', $user_id);
		$this->db->where('(cc.contact_type = ' . CONTACT_TYPE_EMAIL . ' or cc.contact_type = ' . CONTACT_TYPE_PHONE . ')');
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$this->db->order_by('is_follow');
		$this->db->order_by('full_name');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function countFacebookFriends($user_id) {
		$this->db->from('contact_friends cf');
		$this->db->join('contact_contacts cc', 'cc.contact_id = cf.contact_id');
		$this->db->join('user u', 'u.user_id = cc.reference_id');

		$this->db->where('cf.user_id', $user_id);
		$this->db->where('cc.contact_type = ' . CONTACT_TYPE_FACEBOOK);
		return $this->db->count_all_results();
	}

	public function getFacebookFriends($user_id, $page = -1) {
		$this->db->select('u.user_id, u.user_name, u.full_name, u.avatar, u.user_type, u.email, u.phone_number, if(uf.follower_id is null, 0, 1) as is_follow');
		$this->db->from('contact_friends cf');
		$this->db->join('contact_contacts cc', 'cc.contact_id = cf.contact_id');
		$this->db->join('user u', 'u.user_id = cc.reference_id');
		$this->db->join('(select * from user_follow where user_id = ' . $user_id . ') uf', 'uf.follower_id = cc.reference_id', 'left');

		$this->db->where('cf.user_id', $user_id);
		$this->db->where('cc.contact_type = ' . CONTACT_TYPE_FACEBOOK);
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$this->db->order_by('is_follow');
		$this->db->order_by('full_name');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getUnFollowContactFriends($user_id) {
		$this->db->select('u.user_id, u.user_name, u.full_name, u.avatar, u.user_type, u.email, u.phone_number');
		$this->db->from('contact_friends cf');
		$this->db->join('contact_contacts cc', 'cc.contact_id = cf.contact_id');
		$this->db->join('user u', 'u.user_id = cc.reference_id');
		$this->db->join('(select * from user_follow where user_id = ' . $user_id . ') uf', 'uf.follower_id = cc.reference_id', 'left');

		$this->db->where('cf.user_id', $user_id);
		$this->db->where('(cc.contact_type = ' . CONTACT_TYPE_EMAIL . ' or cc.contact_type = ' . CONTACT_TYPE_PHONE . ')');
		$this->db->where('uf.follower_id is null');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getUnFollowFacebookFriends($user_id) {
		$this->db->select('u.user_id, u.user_name, u.full_name, u.avatar, u.user_type, u.email, u.phone_number');
		$this->db->from('contact_friends cf');
		$this->db->join('contact_contacts cc', 'cc.contact_id = cf.contact_id');
		$this->db->join('user u', 'u.user_id = cc.reference_id');
		$this->db->join('(select * from user_follow where user_id = ' . $user_id . ') uf', 'uf.follower_id = cc.reference_id', 'left');

		$this->db->where('cf.user_id', $user_id);
		$this->db->where('cc.contact_type = ' . CONTACT_TYPE_FACEBOOK);
		$this->db->where('uf.follower_id is null');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getUserPicks($user_id, $isMe = true) {
		$this->db->select('up.pick_id as id,up.product_id, up.created_at, up.pick_id, p.*, up.quote, up.is_hidden, up.status as up_status');
		$this->db->from('user_picks up');
		$this->db->where('up.user_id', $user_id);
		if (!$isMe) {
			$this->db->where('up.is_hidden', 0);
		}
		$this->db->group_by('up.pick_id');
		$this->db->order_by('up.pick_id desc');
		$this->db->join('product_view p', 'p.product_id = up.product_id');

		return $this->db->get()->result_array();
	}

    public function getUserLikes($user_id, $isMe = true) {
        $this->db->select();
        $this->db->from('episode_like el');
        $this->db->where('el.user_id', $user_id);
        if (!$isMe) {
            $this->db->where('up.is_hidden', 0);
        }

        return $this->db->get()->result_array();
    }

    public function getUserComments($user_id, $isMe = true) {
        $this->db->select('c.*,e.*,s.product_id,pv.name as film_name, c.status as comment_status,cr.report_id as comment_reportId');
        $this->db->from('comments c');
        $this->db->where('c.user_id', $user_id);
        $this->db->join('comment_reports cr','c.comment_id = cr.comment_id','LEFT');
        $this->db->join('episode e','c.episode_id = e.episode_id','LEFT');
        $this->db->join('season s','e.season_id = s.season_id','LEFT');
        $this->db->join('product_view pv','s.product_id = pv.product_id','LEFT');
        $this->db->group_by('c.comment_id');

        return $this->db->get()->result_array();
    }

	public function getListContinue($user_id, $page = -1, $isMe = true) {
		$this->db->select('w.id, w.user_id, p.*, w.start_time, w.episode_id, w.is_hidden');
		$this->db->from('user_watch w');
		$this->db->join('product p', 'p.product_id = w.product_id');
		$this->db->where('w.user_id', $user_id);
		$this->db->where('w.episode_id !=', 0);
		$this->db->where("w.start_time != 'nan'");
		if (!$isMe) {
			$this->db->where("w.is_hidden", 0);
		}
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$this->db->order_by('w.id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getListWatching($user_id, $page = -1, $isMe = true) {
		$this->db->select('w.id, w.user_id, p.*, w.is_hidden,w.update_time');
		$this->db->from('user_watch w');
		$this->db->join('product_view p', 'p.product_id = w.product_id');
		$this->db->where('w.user_id', $user_id);
		if (!$isMe) {
			$this->db->where('w.is_hidden', 0);
		}
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$this->db->order_by('w.id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getThumbUpList($user_id, $page = -1, $isMe = true) {
		$this->db->select('el.id, e.*, e.status as episode_status, el.is_hidden');
		$this->db->from('episode_like el');
		$this->db->join('episode e', 'e.episode_id = el.episode_id');
		$this->db->join('season s', 'e.season_id = s.season_id');
		$this->db->join('product p', 's.product_id = p.product_id');
		$this->db->where('el.user_id', $user_id);
		$this->db->where('el.status', 1);
		if (!$isMe) {
			$this->db->where('el.is_hidden', 0);
		}
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getProductThumbUpList($user_id, $page = -1, $isMe = true) {
		$this->db->select('pl.id, pl.added_at, pl.user_id, p.*, pl.is_hidden');
		$this->db->from('product_likes pl');
		$this->db->join('product_view p', 'pl.product_id = p.product_id');
		$this->db->where('pl.user_id', $user_id);
		if (!$isMe) {
			$this->db->where('pl.is_hidden', 0);
		}
//		if ($page >= 0) {
//			$this->db->limit(10, 10 * $page);
//		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getEpisodeThumbUpList($user_id, $page = -1, $isMe = true){
	    $this->db->select('e.*,e.season_id,el.added_at,el.id as el_id,el.status, e.name as episode_name,pv.name as film_name,s.product_id');
	    $this->db->from('episode_like el');
	    $this->db->join('episode e','el.episode_id = e.episode_id','LEFT');
	    $this->db->join('season s','e.season_id = s.season_id','LEFT');
	    $this->db->join('product_view pv','s.product_id = pv.product_id','LEFT');
        $this->db->where('el.user_id', $user_id);
        $this->db->where_in('el.status', [0,1]);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getCommentThumbUpList($user_id, $page = -1, $isMe = true){
        $this->db->select('c.episode_id,e.season_id,pv.name as film_name,s.product_id,e.name as episode_name,cl.added_at,cl.id as cl_id');
        $this->db->from('comment_like cl');
        $this->db->join('comments c', 'cl.comment_id = c.comment_id','LEFT');
        $this->db->join('episode e','c.episode_id = e.episode_id','LEFT');
        $this->db->join('season s','e.season_id = s.season_id','LEFT');
        $this->db->join('product_view pv','s.product_id = pv.product_id','LEFT');
        $this->db->where('cl.user_id',$user_id);

        $query = $this->db->get();
        return $query->result_array();
	}

	public function hiddenYourPick($pick_id, $is_hidden) {
		$this->db->where('pick_id', $pick_id);
		$this->db->update('user_picks', ['is_hidden' => $is_hidden]);
	}

	public function hiddenContinueWatching($id, $is_hidden) {
		$this->db->where('id', $id);
		$this->db->update('user_watch', ['is_hidden' => $is_hidden]);
	}

	public function hiddenWatchList($id, $is_hidden) {
		$this->db->where('id', $id);
		$this->db->update('watch_list', ['is_hidden' => $is_hidden]);
	}

	public function hiddenThumbsUp($id, $is_hidden) {
		$this->db->where('id', $id);
		$this->db->update('episode_like', ['is_hidden' => $is_hidden]);
	}

	public function hiddenProductThumbsUp($id, $is_hidden) {
		$this->db->where('id', $id);
		$this->db->update('product_likes', ['is_hidden' => $is_hidden]);
	}

	public function insertSignUp($params) {
		$this->db->insert('newsletter_signups', $params);
	}

	public function getNumOfSignups() {
		$this->db->from('newsletter_signups');
		return $this->db->count_all_results();
	}

	public function getSignups($page = 0) {
		$this->db->select('u.*');
		$this->db->from('newsletter_signups u');
		$this->db->order_by('id', 'desc');
		if ($page > -1) {
			$this->db->limit(PERPAGE_ADMIN, $page * PERPAGE_ADMIN);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function delete($user_id) {
		$this->db->where('user_id', $user_id);
		$this->db->delete('watch_list');

		$this->db->where('user_id', $user_id);
		$this->db->delete('user_watch');

		$this->db->where('user_id', $user_id);
		$this->db->delete('watched');

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
		$this->db->delete('comment_replies');

		$this->db->where('user_id', $user_id);
		$this->db->delete('episode_like');

		$this->db->where('user_id', $user_id);
		$this->db->delete('comment_like');

		$this->db->where('reporter_id', $user_id);
		$this->db->delete('comment_reports');

		$this->db->where('user_id', $user_id);
		$this->db->delete('comments');

		$this->db->where('user_id', $user_id);
		$this->db->delete('featured_profiles');

		$this->db->where('user_id', $user_id);
		$this->db->delete('product_likes');

		$this->db->where('user_id', $user_id);
		$this->db->delete('device_user');

		$this->db->where('user_id', $user_id);
		$this->db->delete('user_access_token');

		$this->db->where('user_id', $user_id);
		$this->db->delete('user_picks');

		$this->db->where('user_id', $user_id);
		$this->db->delete('user_profile_configs');

		$this->db->where('user_id', $user_id);
		$this->db->or_where('reporter_id', $user_id);
		$this->db->delete('user_reports');

		$this->db->or_where('reference_id', $user_id);
		$this->db->delete('contact_contacts');

        $this->db->or_where('user_id', $user_id);
        $this->db->delete('user_notification_setting');

		$this->db->where('user_id', $user_id);
        $this->db->delete('user');

		$this->db->like('data', '"user_id":' . $user_id, 'both');
		$this->db->delete('user_notify');


		return parent::delete($user_id);
	}

	public function getNotificationSetting($user_id) {
		$this->db->from('user_notification_setting');
		$this->db->where('user_id', $user_id);

		return $this->db->get()->first_row('array');
	}

	public function isEnableNotification($user_id, $key) {
		$settings = $this->getNotificationSetting($user_id);
		if ($settings == null) {
			return true;
		}
		if (isset($settings[$key])) {
			return $settings[$key] == 1;
		}
		return false;
	}

    public function removeComment($id) {
        $this->db->where('comment_id', $id);
        $this->db->delete('comments');
    }

    public function getCommentReplies($comment_id){
	    $this->db->select('c.*,e.*,p.name as film_name');
	    $this->db->from('comments c');
	    $this->db->where('comment_id', $comment_id);
	    $this->db->join('episode e', 'c.episode_id = e.episode_id');
	    $this->db->join('season s', 'e.season_id = e.season_id');
	    $this->db->join('product p', 's.product_id = p.product_id');

	    return $this->db->get()->result_array();
    }

    public function disableReported($report_id){
	    $this->db->where('report_id',$report_id);
	    $this->db->update('user_reports', array('status'=>'disable'));
    }

    public function enableReported($report_id){
        $this->db->where('report_id',$report_id);
        $this->db->update('user_reports', array('status'=>'rejected'));

    }

    public function deleteUserStatus($user_id){
        $this->db->where('user_id',$user_id);
        $this->db->update('user',array('is_deleted'=>1));

        $this->db->where('user_id',$user_id);
        $this->db->update('user_reports',array('status'=>'deleted'));
	}

	public function addVerify($user_id){
	    $this->db->where('user_id',$user_id);
	    $this->db->update('user',array('user_type'=>1));
    }

    public function addCurator($user_id){
        $this->db->where('user_id',$user_id);
        $this->db->update('user',array('user_type'=>2));
    }

    public function removeTag($user_id){
        $this->db->where('user_id',$user_id);
        $this->db->update('user',array('user_type'=>0));

    }

    public function updateFeature($user_id){
        $this->db->select_max('priority');
        $result = $this->db->get('featured_profiles')->row();
        $max_value = $result->priority + 1;

        $data = array(
            'user_id' => $user_id,
            'priority' => $max_value,
            'status' => 1
        );
        $this->db->insert('featured_profiles', $data);
    }

    public function deleteFeature($user_id){
	    $this->db->where('user_id', $user_id);
	    $this->db->delete('featured_profiles');
    }

    public function deleteEpisodeLike($episodeLike_id){
	    $this->db->where('id',$episodeLike_id);
        $this->db->delete('episode_like');
    }

    public function deleteProductLike($productLike_id){
        $this->db->where('id',$productLike_id);
        $this->db->delete('product_likes');
    }
    public function deleteCommentLike($commentLike_id){
        $this->db->where('id',$commentLike_id);
        $this->db->delete('comment_like');
    }

    // public function getFollowersOfUser($user_ids) {
    // 	$this->db->where_in('user_id', $user_ids);
    // 	return $this->db->count_all_results();
    // }
}
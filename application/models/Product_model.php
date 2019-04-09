<?php

require_once APPPATH . '/core/BaseModel.php';

class Product_model extends BaseModel {
	public function __construct() {
		parent::__construct();
		$this->table = 'product';
		$this->id_name = 'product_id';
	}

	public function getProducts($page = -1) {
		$this->db->where('status', 1);
		return $this->getList($page, 'priority', 'asc');
	}

	public function getProductById($product_id) {
		return $this->getObjectById($product_id);
	}

	public function check_watchlist_added($user_id, $product_id) {
		$this->db->select('*');
		$this->db->from('watch_list');
		$this->db->where('user_id', $user_id);
		$this->db->where('product_id', $product_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? 1 : 0;
	}

	public function getProductContinue($user_id, $product_id) {
		$this->db->select('*');
		$this->db->from('user_watch');
		$this->db->where('user_id', $user_id);
		$this->db->where('product_id', $product_id);
		$this->db->where('episode_id', 0);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array')['start_time'] : 0;
	}

	public function checkProduct($product_id) {
		$this->db->select('product_id');
		$this->db->where('product_id', $product_id);
		$this->db->where('status', 1);
		$query = $this->db->get('product');
		return $query->num_rows() > 0;
	}

	public function deleteProduct($product_id) {
		$this->db->trans_start();

		$this->db->select('season_id');
		$this->db->from('season');
		$this->db->where('product_id', $product_id);
		$this->db->where('status', 1);
		$query = $this->db->get();

		$results = $query->result_array();
		foreach ($results as $result) {
			$this->db->where('season_id', $result['season_id']);
			$this->db->update('season', array('status' => 0));
			$this->db->where('season_id', $result['season_id']);
			$this->db->update('episode', array('status' => 0));
		}
		$this->db->where('data LIKE \'%"product_id":"' . $product_id . '"%\'');
		$this->db->delete('user_notify');

		$this->db->where('product_id', $product_id);
		$this->db->delete('explore_previews');

		$this->db->where('product_id', $product_id);
		$this->db->delete('collection_product');

		$this->db->where('product_id', $product_id);
		$this->db->delete('film_cast');

		$this->db->where($this->id_name, $product_id);
		$this->db->delete($this->table);
		$this->db->trans_complete();
	}

	public function getProductListForAdmin($page = -1) {
		$this->db->select('p.*');
		$this->db->from('product_view p');
		$this->db->where('p.status', 1);
		if ($page >= 0) {
			$this->db->limit(PERPAGE_ADMIN, PERPAGE_ADMIN * $page);
		}
		$this->db->order_by('product_id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getProductForAdmin($product_id) {
		$this->db->select('p.*');
		$this->db->from('product_view p');
		$this->db->where('p.product_id', $product_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getProductDetail($product_id) {
		$this->db->select('p.*');
		$this->db->from('product_view p');
		$this->db->where('p.product_id', $product_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getProductByName($product_name) {
		return $this->getObjectByName('name', $product_name);
	}

	public function getListProductByCategory($category_id, $page = -1) {
		$this->db->select('p.*');
		$this->db->from('product_view p');
		$this->db->where('p.category_id', $category_id);
		$this->db->order_by('priority', 'asc');
		if ($page >= 0) {
			$this->db->limit(10, 10 * $page);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getListProductByCollection($collection_id, $page = -1, $limit = 10) {
		$this->db->select('p.*, cp.priority as priority_collection, cp.id, cp.promo_image');
		$this->db->from('collection_product cp');
		$this->db->join('product_view p', 'p.product_id = cp.product_id');
		$this->db->where('cp.collection_id', $collection_id);
		$this->db->where('p.status', 1);
		$this->db->order_by('cp.priority', 'asc');
		if ($page >= 0) {
			$this->db->limit($limit, $limit * $page);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getListMusic($product_id) {
		$this->db->where('product_id', $product_id);
		$this->db->order_by('priority', 'asc');
		$query = $this->db->get('music');
		return $query->result_array();
	}

	public function getListCast($product_id) {
		$this->db->select('c.*');
		$this->db->from('film_cast fc');
		$this->db->join('cast c', 'fc.cast_id = c.cast_id');
		$this->db->where('fc.product_id', $product_id);
		$this->db->where('c.status', 1);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getListSeason($product_id) {
		$this->db->select('*');
		$this->db->from('season');
		$this->db->where('product_id', $product_id);
		$this->db->where('status', 1);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getEpisodeSeasons($product_id) {
		$this->db->select('e.*, s.name as season_name');
		$this->db->from('episode e');
		$this->db->join('season s', 's.season_id = e.season_id');
		$this->db->where('product_id', $product_id);
		$this->db->where('s.status', 1);
		$this->db->where('e.status', 1);

		$this->db->order_by('e.season_id');
		$this->db->order_by('e.position', 'asc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function countSeason($product_id) {
		$this->db->from('season');
		$this->db->where('product_id', $product_id);
		$this->db->where('status', 1);
		return $this->count_all_results();
	}

	public function getRates() {
		$this->db->from('film_rate');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getMaxPriority() {
		$this->db->order_by('priority', 'desc');
		$query = $this->db->get($this->table);
		$this->db->limit(1);
		return $query->num_rows() > 0 ? $query->first_row()->priority : 0;
	}

	public function getProductOthers($cast_id, $name) {
		$sql = "SELECT * FROM product WHERE status = 1 AND name LIKE '%" . $name . "%' AND product_id NOT IN (SELECT product_id FROM film_cast WHERE cast_id = '$cast_id' GROUP BY product_id) ORDER BY product_id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function checkWatchTrailler($user_id, $product_id) {
		$this->db->select('id');
		$this->db->where('episode_id', 0);
		$this->db->where('product_id', $product_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('user_watch');
		return $query->num_rows() > 0;
	}

	public function addWatchTrailler($params) {
		$this->db->insert('user_watch', $params);
		return $this->db->insert_id();
	}

	public function removeWatchTrailler($product_id, $user_id) {
		$this->db->where('episode_id', 0);
		$this->db->where('product_id', $product_id);
		$this->db->where('user_id', $user_id);
		$this->db->delete('user_watch');
	}

	public function updateWatchTrailler($product_id, $user_id, $params) {
		$this->db->where('episode_id', 0);
		$this->db->where('product_id', $product_id);
		$this->db->where('user_id', $user_id);
		$this->db->update('user_watch', $params);
	}

	public function getFirstEpisode($product_id) {
		$query = $this->db->query('select * from episode where season_id = (SELECT season_id FROM season WHERE product_id=' . $product_id . ' and status = 1 order by season_id LIMIT 0, 1) and status = 1 order by position limit 0, 1');

		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function countUserWatching($product_id) {
		$this->db->select('product_id');
		$this->db->from('user_watch');
		$this->db->where('episode_id !=', 0);
		$this->db->where('product_id', $product_id);
		return $this->db->count_all_results();
	}

	public function getProductWatching($product_id, $user_id = -1) {
		$this->db->select('w.*, u.user_id, u.user_name, u.full_name, u.avatar');
		$this->db->from('user_watch w');
		$this->db->join('user u', 'u.user_id = w.user_id');
		$this->db->where('w.episode_id !=', 0);
		$this->db->where('w.product_id', $product_id);
		// $this->db->where('(w.update_time >= '.strtotime("-3 minutes").')');
		// if($user_id != -1)
		// 	$this->db->where('w.user_id !=', $user_id);
		$this->db->group_by('w.user_id');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getContinueWatching($user_id) {
		$this->db->select('p.*, w.start_time, w.episode_id');
		$this->db->from('user_watch w');
		$this->db->join('product p', 'p.product_id = w.product_id');
		$this->db->where('w.user_id =', $user_id);
		$this->db->where('w.start_time >=', 0);
		$this->db->where('p.status', 1);
		$this->db->order_by('update_time', 'desc');
		$this->db->order_by('w.id', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getEpisode($episode_id) {
		$this->db->from('episode');
		$this->db->where('episode_id', $episode_id);
		$query = $this->db->get();
		return $query->num_rows() > 0 ? $query->first_row('array') : null;
	}

	public function getExplorePreviews() {
		$this->db->select('p.*, ep.id, ep.promo_image');
		$this->db->from('explore_previews ep');
		$this->db->join('product_view p', 'p.product_id = ep.product_id');
		$this->db->where('p.status', 1);
		$this->db->where('ep.status', 1);
		$this->db->order_by('ep.priority');
		return $this->db->get()->result_array();
	}

	public function getRecentlyWatched($user_id) {
		$this->db->select('p.*');
		$this->db->from('watched w');
		$this->db->join('product p', 'p.product_id = w.product_id');
		$this->db->where('w.user_id =', $user_id);
		$this->db->order_by('w.updated_at', 'desc');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getProductWatchers($product_id) {
		$this->db->select('w.*, u.user_id, u.user_name, u.full_name, u.avatar');
		$this->db->from('user_watch w');
		$this->db->join('user u', 'u.user_id = w.user_id');
		$this->db->where('w.episode_id !=', 0);
		$this->db->where('w.product_id', $product_id);
		$this->db->group_by('w.user_id');
		$this->db->limit(3);
		$query = $this->db->get();
		return $query->result_array();
	}

	public function getUserProductLike($user_id, $product_id) {
		$this->db->from('product_likes');
		$this->db->where('user_id', $user_id);
		$this->db->where('product_id', $product_id);

		$query = $this->db->get();
		return $query->first_row('array');
	}

	public function addProductLike($user_id, $product_id) {
		$this->db->insert('product_likes', [
			'user_id' => $user_id,
			'product_id' => $product_id,
			'added_at' => time(),
		]);
	}

	public function removeProductLike($user_id, $product_id) {
		$this->db->where('user_id', $user_id);
		$this->db->where('product_id', $product_id);
		$this->db->delete('product_likes');
	}

	public function countProductLikes($product_id) {
		$this->db->where('product_id', $product_id);
		$this->db->from('product_likes');
		return $this->db->count_all_results();
	}

	public function getProductListByStatus($status) {
		$sql = "select * from 
				    (select s1.*, count(up.pick_id) as total_pick
				    from (select p.*,fr.name as rate_name,count(pl.user_id) as total_like, e.episode_id as paywall_episode_id, e.name as paywall_episode_name
				         from ((product as p 
				                left join product_likes as pl on p.product_id = pl.product_id)
				                join film_rate as fr on p.rate_id = fr.rate_id)
				          		left join episode as e on p.paywall_episode = e.episode_id
				          	where p.status = '$status'
				            group by product_id) s1
				    left join user_picks as up on s1.product_id = up.product_id 
				    group by s1.product_id) s2
				join 
				    (select s3.product_id, s3.total_epi, s4.total_cmt from 
				        (select p.product_id, count(e.episode_id) as total_epi
				        from ((product as p 
				            left join season as s on p.product_id = s.product_id)
				            left join episode as e on s.season_id = e.season_id)
				        group by p.product_id) s3
				    join 
				        (select p.product_id, count(c.comment_id) as total_cmt
				        from ((product as p 
				            left join season as s on p.product_id = s.product_id)
				            left join episode as e on s.season_id = e.season_id)
				            left join comments as c on e.episode_id = c.episode_id
				        group by p.product_id) s4
				    on s3.product_id = s4.product_id) s5
				on s2.product_id = s5.product_id
				order by s2.product_id desc";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getAllProducts($query = '') {
		$sql = "select * from 
				    (select s1.*, count(up.pick_id) as total_pick
				    from (select p.*,fr.name as rate_name,count(pl.user_id) as total_like,e.episode_id as paywall_episode_id,e.name as paywall_episode_name
				         from ((product as p 
				                left join product_likes as pl on p.product_id = pl.product_id)
				                join film_rate as fr on p.rate_id = fr.rate_id)
				          		left join episode as e on p.paywall_episode = e.episode_id
				          	where p.name like '%" . $query ."%'
				            group by product_id) s1
				    left join user_picks as up on s1.product_id = up.product_id 
				    group by s1.product_id) s2
				join 
				    (select s3.product_id, s3.total_epi, s4.total_cmt from 
				        (select p.product_id, count(e.episode_id) as total_epi
				        from ((product as p 
				            left join season as s on p.product_id = s.product_id)
				            left join episode as e on s.season_id = e.season_id)
				        group by p.product_id) s3
				    join 
				        (select p.product_id, count(c.comment_id) as total_cmt
				        from ((product as p 
				            left join season as s on p.product_id = s.product_id)
				            left join episode as e on s.season_id = e.season_id)
				            left join comments as c on e.episode_id = c.episode_id
				        group by p.product_id) s4
				    on s3.product_id = s4.product_id) s5
				on s2.product_id = s5.product_id
				order by s2.product_id desc";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

}
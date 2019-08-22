<?php

class Dashboard_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	public function countUsers($from = 0, $to = 0) {
		$this->db->from('user');
		if ($from > 0)
			$this->db->where('joined >=', $from);
		if ($to > 0)
			$this->db->where('joined <', $to);
		return $this->db->count_all_results();
	}

	public function countBlocksWatched($from = 0, $to = 0) {
		$this->db->from('user_watch');
		if ($from > 0)
			$this->db->where('update_time >=', $from);
		if ($to > 0)
			$this->db->where('update_time <', $to);
		return $this->db->count_all_results();
	}

	public function countBlocks($from = 0, $to = 0) {
		$this->db->from('episode');
		$this->db->where('status', 1);
		if ($from > 0)
			$this->db->where('created >=', $from);
		if ($to > 0)
			$this->db->where('created <', $to);
		return $this->db->count_all_results();
	}

	public function countStories($from = 0, $to = 0) {
		$this->db->from('product');
		$this->db->where('status', 1);
		if ($from > 0)
			$this->db->where('created >=', $from);
		if ($to > 0)
			$this->db->where('created <', $to);
		return $this->db->count_all_results();
	}

	public function countComments($from = 0, $to = 0) {
		$this->db->from('comments');
		$this->db->where('status', 1);
		if ($from > 0)
			$this->db->where('timestamp >=', $from);
		if ($to > 0)
			$this->db->where('timestamp <', $to);
		return $this->db->count_all_results();
	}

	public function countReviews($from = 0, $to = 0) {
		$this->db->from('user_picks');
		$this->db->where('status', 1);
		if ($from > 0)
			$this->db->where('created_at >=', $from);
		if ($to > 0)
			$this->db->where('created_at <', $to);
		return $this->db->count_all_results();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function getNextStep($time, $i, $steps) {
		if ($i == 0)
			return $time;
		if ($steps == -1) {
			return strtotime(date('Y-m', strtotime($i . ' months', $time)), '-1');
		} else {
			return $time + $i * $steps;
		}
	}

	public function formatLabel($time, $steps, $sampleYear = false) {
		if ($steps == -1) {
			return $sampleYear ? date('M', $time) : date('M Y', $time);
		} else if ($steps == 86400) {
			return date('M d', $time);
		} else {
			return date('hA', $time);
		}
	}

	public function formatRange($startDate, $endDate) {
		$diff = $endDate - $startDate;
		if ($diff <= 86400) {
			// by hours
			return date('M d, Y', $startDate);
		} else if ($diff <= 31 * 86400) {
			// by day
			if (is_sample_years($startDate, $endDate - 86400)) {
				return date('M d', $startDate) . ' - ' . date('M d', $endDate - 86400);
			} else {
				return date('M d', $startDate) . ' - ' . date('M d, Y', $endDate - 86400);
			}
		} else {
			// by month
			if (is_sample_years($startDate, $endDate - 86400)) {
				return date('M', $startDate) . ' - ' . date('M Y', $endDate - 86400);
			} else {
				return date('M Y', $startDate) . ' - ' . date('M Y', $endDate - 86400);
			}
		}
	}

	public function expandResource(&$items, $results, $field, $mapField = '', $newField = '', $merge = false) {
		if (!is_array($items) || count($items) == 0 || !is_array($results) || count($results) == 0) {
			return;
		}

		$ref = empty($newField) ? str_replace('_id', '', $field) : $newField;
		$map = [];
		foreach ($results as $result) {
			$map[$result[$mapField]] = $result;
		}
		foreach ($items as $key => $item) {
			if (isset($map[$item[$field]])) {
				if ($merge) {
					$items[$key] = array_merge($items[$key], $map[$item[$field]]);
				} else {
					$items[$key][$ref] = $map[$item[$field]];
				}
			}
		}
	}

	public function getDashBoard($startDate, $endDate, $secondFromDate, $secondToDate, $is_required = true, $unix_timestamp = 0) {
		$diff = $endDate - $startDate;
		if (empty($secondFromDate) || empty($secondToDate)) {
			$secondStart = $startDate - $diff;
			$secondEnd = $startDate;
		} else {
			$secondStart = strtotime($secondFromDate);
			$secondEnd = strtotime($secondToDate) + 86400;
		}
		$sampleYear = is_sample_years($startDate, $endDate - 86400);
		if ($diff <= 86400) {
			// by hours
			$steps = 3600;
		} else if ($diff <= 31 * 86400) {
			// by day
			$steps = 86400;
		} else {
			// by month
			$steps = -1;
		}

		$params = ['unix_timestamp' => time()];
		$params['startDate'] = $startDate;
		$params['endDate'] = $endDate;
		$params['secondStart'] = $secondStart;
		$params['secondEnd'] = $secondEnd;

		// collect users
		$user_chart = [
			'first' => [
				'label' => $this->formatRange($startDate, $endDate),
				'value' => $this->countUsers($startDate, $endDate),
			],
			'second' => [
				'label' => $this->formatRange($secondStart, $secondEnd),
				'value' => $this->countUsers($secondStart, $secondEnd),
			],
		];
		$user_chart['percent'] = getPercent($user_chart['first']['value'], $user_chart['second']['value']);

		$data = [];
		$temp = $startDate;
		$i = 0;
		while ($temp < $endDate) {
			$item = [
				"label" => $this->formatLabel($temp, $steps, $sampleYear),
				"first" => $this->countUsers($temp, min($endDate, $this->getNextStep($startDate, $i + 1, $steps))),
				"second" => $this->countUsers($this->getNextStep($secondStart, $i, $steps), min($secondEnd, $this->getNextStep($secondStart, $i + 1, $steps))),
			];
			$data[] = $item;

			$i = $i + 1;
			$temp = $this->getNextStep($startDate, $i, $steps);
		}
		$user_chart['data'] = $data;
		$params['user_chart'] = $user_chart;

		$comment_chart = [
			'first' => [
				'label' => $this->formatRange($startDate, $endDate),
				'value' => $this->countComments($startDate, $endDate),
			],
			'second' => [
				'label' => $this->formatRange($secondStart, $secondEnd),
				'value' => $this->countComments($secondStart, $secondEnd),
			],
		];
		$comment_chart['percent'] = getPercent($comment_chart['first']['value'], $comment_chart['second']['value']);

		$data = [];
		$temp = $startDate;
		$i = 0;
		while ($temp < $endDate) {
			$item = [
				"label" => $this->formatLabel($temp, $steps, $sampleYear),
				"first" => $this->countComments($temp, min($endDate, $this->getNextStep($startDate, $i + 1, $steps))),
				"second" => $this->countComments($this->getNextStep($secondStart, $i, $steps), min($secondEnd, $this->getNextStep($secondStart, $i + 1, $steps))),
			];
			$data[] = $item;

			$i = $i + 1;
			$temp = $this->getNextStep($startDate, $i, $steps);
		}
		$comment_chart['data'] = $data;
		$params['comment_chart'] = $comment_chart;

		$review_chart = [
			'first' => [
				'label' => $this->formatRange($startDate, $endDate),
				'value' => $this->countReviews($startDate, $endDate),
			],
			'second' => [
				'label' => $this->formatRange($secondStart, $secondEnd),
				'value' => $this->countReviews($secondStart, $secondEnd),
			],
		];
		$review_chart['percent'] = getPercent($review_chart['first']['value'], $review_chart['second']['value']);

		$data = [];
		$temp = $startDate;
		$i = 0;
		while ($temp < $endDate) {
			$item = [
				"label" => $this->formatLabel($temp, $steps, $sampleYear),
				"first" => $this->countReviews($temp, min($endDate, $this->getNextStep($startDate, $i + 1, $steps))),
				"second" => $this->countReviews($this->getNextStep($secondStart, $i, $steps), min($secondEnd, $this->getNextStep($secondStart, $i + 1, $steps))),
			];
			$data[] = $item;

			$i = $i + 1;
			$temp = $this->getNextStep($startDate, $i, $steps);
		}
		$review_chart['data'] = $data;
		$params['review_chart'] = $review_chart;

		$most_followers = $this->getMostFollower($secondStart, $endDate);
		$params['most_followers'] = $most_followers;

		$most_watched_blocks = $this->getWatchedBlocks($secondStart, $endDate);
		$params['most_watched_blocks'] = $most_watched_blocks;

		$most_review_stories = $this->getMostReviewStories($secondStart, $endDate);
		$params['most_review_stories'] = $most_review_stories;

		$most_liked_blocks = $this->getMostLikedBlocks($secondStart, $endDate);
		$params['most_liked_blocks'] = $most_liked_blocks;

		$params['most_shared_stories'] = [];

		$most_commented_blocks = $this->getMostCommentedBlocks($secondStart, $endDate);
		$params['most_commented_blocks'] = $most_commented_blocks;

		$most_commented_stories = $this->getMostCommentedStories($secondStart, $endDate);
		$params['most_commented_stories'] = $most_commented_stories;

		return $params;
	}

	protected function expandBlockStory($items) {
		if (count($items) > 0) {
			$season_ids = [];
			foreach ($items as $key => $item) {
				if ($item['season_id'] > 0 && !in_array($item['season_id'], $season_ids)) {
					$season_ids[] = $item['season_id'];
				}
			}
			if (count($season_ids) == 0) {
				return $items;
			}

			$this->db->from('season s');
			$this->db->join('product p', 'p.product_id = s.product_id');
			$this->db->select('s.season_id, s.name as season_name, p.name as story_name');
			$this->db->where_in('s.season_id', $season_ids);
			$results = $this->db->get()->result_array();
			$this->expandResource($items, $results, 'season_id', 'season_id', '', true);
		}
		return $items;
	}

	public function getWatchedBlocks($from, $to) {
		$this->db->select('uw.episode_id, e.season_id, e.name, count(*) as watched');
		$this->db->from('user_watch uw');
		$this->db->join('episode e', 'e.episode_id = uw.episode_id');
		if ($from > 0)
			$this->db->where('update_time >=', $from);
		if ($to > 0)
			$this->db->where('update_time <', $to);
		$this->db->group_by('uw.episode_id');
		$this->db->order_by('watched desc');
		$this->db->order_by('episode_id desc');
		$this->db->limit(5);
		$items = $this->db->get()->result_array();
		return $this->expandBlockStory($items);
	}

	public function getMostLikedBlocks($from, $to) {
		$this->db->select('el.episode_id, e.season_id, e.name, count(*) as num_of_like');
		$this->db->from('episode_like el');
		$this->db->join('episode e', 'e.episode_id = el.episode_id');
		if ($from > 0)
			$this->db->where('el.added_at >=', $from);
		if ($to > 0)
			$this->db->where('el.added_at <', $to);
		$this->db->where('el.status', 1);
		$this->db->group_by('el.episode_id');
		$this->db->order_by('num_of_like desc');
		$this->db->order_by('el.episode_id desc');
		$this->db->limit(5);
		$items = $this->db->get()->result_array();
		return $this->expandBlockStory($items);
	}

	public function getMostCommentedBlocks($from, $to) {
		$this->db->select('c.episode_id, e.season_id, e.name, count(*) as num_of_comments');
		$this->db->from('comments c');
		$this->db->join('episode e', 'e.episode_id = c.episode_id');
		if ($from > 0)
			$this->db->where('c.timestamp >=', $from);
		if ($to > 0)
			$this->db->where('c.timestamp <', $to);
		$this->db->where('c.status', 1);
		$this->db->group_by('c.episode_id');
		$this->db->order_by('num_of_comments desc');
		$this->db->order_by('c.episode_id desc');
		$this->db->limit(5);
		$items = $this->db->get()->result_array();
		return $this->expandBlockStory($items);
	}

	public function getMostCommentedStories($from, $to) {
		$this->db->select('s.product_id, p.name, count(*) as num_of_comments');
		$this->db->from('comments c');
		$this->db->join('episode e', 'e.episode_id = c.episode_id');
		$this->db->join('season s', 's.season_id = e.season_id');
		$this->db->join('product p', 'p.product_id = s.product_id');
		if ($from > 0)
			$this->db->where('c.timestamp >=', $from);
		if ($to > 0)
			$this->db->where('c.timestamp <', $to);
		$this->db->where('c.status', 1);
		$this->db->group_by('s.product_id');
		$this->db->order_by('num_of_comments desc');
		$this->db->order_by('s.product_id desc');
		$this->db->limit(5);
		$items = $this->db->get()->result_array();
		return $items;
	}

	public function getMostReviewStories($from, $to) {
		$this->db->select('up.product_id, p.name, count(*) as num_of_reviewed');
		$this->db->from('user_picks up');
		$this->db->join('product p', 'p.product_id = up.product_id');
		$this->db->where('up.status', 1);
		if ($from > 0)
			$this->db->where('up.created_at >=', $from);
		if ($to > 0)
			$this->db->where('up.created_at <', $to);
		$this->db->group_by('up.product_id');
		$this->db->order_by('num_of_reviewed desc');
		$this->db->order_by('product_id desc');
		$this->db->limit(5);
		$items = $this->db->get()->result_array();
		return $items;
	}

	public function getMostFollower($from, $to) {
		$this->db->select('u.user_id, u.full_name, u.user_name, uf.num_of_followers, uf2.new_followers');
		$this->db->from('user u');
		$this->db->join('(select follower_id, count(*) as num_of_followers from user_follow group by follower_id) as uf', 'u.user_id = uf.follower_id');
		$sub_query = 'select follower_id, count(*) as new_followers from user_follow';
		$sub_query .= ' where ';
		$sub_query .= '1488799243 >= ' . $from . ' and 1488799243 < ' . $to;
		$sub_query .= ' group by follower_id';
		$this->db->join('('.$sub_query.') as uf2', 'u.user_id = uf2.follower_id', 'left');
		$this->db->order_by('new_followers desc');
		$this->db->order_by('num_of_followers desc');
		$this->db->order_by('user_id desc');
		$this->db->limit(10);
		return $this->db->get()->result_array();
	}
}
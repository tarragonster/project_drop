<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function jwTotalTime() {
		$this->load->library('jw_lib');
		$videos = $this->jw_lib->getVideos();

		foreach ($videos as $video) {
			$this->db->where('jw_media_id', $video['key']);
			$this->db->update('product', ['total_time' => $video['duration']]);

			$this->db->where('jw_media_id', $video['key']);
			$this->db->update('episode', ['total_time' => $video['duration']]);
		}

		log_message('trace', 'Number of videos: ' . count($videos));
	}

	public function deleteUsers() {
		$users_id = [49, 77, 122, 123, 128, 163, [339, 425], 430, 431, 433, 434, 448, [451 , 460], [465, 466], [473, 477],
		[479, 539], 576];
		foreach ($users_id as $user_id) {
			if (is_array($users_id)) {
				for ($id = $user_id[0]; $id <= $user_id[1]; $id++) {
					$this->deleteUser($id);
				}
			} else {
				$this->deleteUser($users_id);
			}
		}
	}

	public function deleteBlocked() {
		$this->db->from('user');
		$this->db->where('status <>', 1);
		$users = $this->db->get()->result_array();
		foreach ($users as $user) {
			$this->deleteUser($user['user_id']);
		}
	}

	public function deleteUser($user_id) {
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

		$this->db->where('user_id', $user_id);
		$this->db->delete('user');

		$this->db->like('data', '"user_id":'.$user_id, 'both');
		$this->db->delete('user_notify');
	}

	function changeEngine() {
		$query = $this->db->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'vm18_seconds' AND ENGINE <> 'InnoDB'");
		$items = $query->result_array();
		foreach ($items as $item) {
			$this->db->query("ALTER TABLE `{$item['TABLE_NAME']}` ENGINE=InnoDB");
		}
	}
}
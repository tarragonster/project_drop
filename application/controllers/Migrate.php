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

	public function deleteBlocked() {
		$this->db->from('user');
		$this->db->where('status <>', 1);
		$users = $this->db->get()->result_array();
		foreach ($users as $user) {
			$this->user_model->delete($user['user_id']);
		}
	}

	public function changeEngine() {
		$query = $this->db->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'vm18_seconds' AND ENGINE <> 'InnoDB'");
		$items = $query->result_array();
		foreach ($items as $item) {
			$this->db->query("ALTER TABLE `{$item['TABLE_NAME']}` ENGINE=InnoDB");
		}
	}

	public function notification() {
		$this->db->from('user');
		$this->db->select('user_id');
		$users = $this->db->get()->result_array();
		foreach ($users as $user) {
			$this->db->insert('user_notification_setting', ['user_id' => $user['user_id']]);
		}
	}
}
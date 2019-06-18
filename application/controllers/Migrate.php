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
		$this->load->model('notify_model');
		$this->db->from('user');
		$this->db->where('status <>', 1);
		$users = $this->db->get()->result_array();
		foreach ($users as $user) {
			$this->user_model->delete($user['user_id']);
			$this->notify_model->deleteReference('user', $user['user_id']);
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

	public function notificationReference() {
		$this->db->select('max(notify_id) max_notify_id');
		$this->db->from('notification_references');
		$item = $this->db->get()->first_row();
		$notify_id = $item != null ? ($item->max_notify_id) : 0;

		$this->db->select('notify_id, data');
		$this->db->from('user_notify');
		$this->db->where('notify_id >', $notify_id);
		$this->db->limit(1024);
		$notifies = $this->db->get()->result_array();

		foreach ($notifies as $notify) {
			$data = json_decode($notify['data'], TRUE);
			if ($data == null || empty($data)) {
				return;
			}
			$reference_types = ['user' => 'user', 'product' => 'product', 'episode' => 'episode', 'comment' => 'comment', 'follower' => 'user', 'uid' => 'user'];
			foreach ($data as $key => $value) {
				foreach ($reference_types as $type => $mappedType) {
					if (startsWith($key, $type)) {
						$this->db->insert('notification_references', [
							'refer_type' => $mappedType,
							'notify_id' => $notify['notify_id'],
							'refer_id' => $value,
						]);
					}
				}
			}
		}
		echo 'Number of notifies: ' . count($notifies);
	}
}
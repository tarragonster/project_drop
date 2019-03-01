<?php
require APPPATH . '/core/Base_Controller.php';

class Cronjobs extends Base_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function push() {
		$this->load->library('cipush');
		$this->cipush->start();
	}

	public function jwTotalTime() {
		$this->load->library('jw_lib');
		$videos = $this->jw_lib->getVideos(null, time() - 86400);

		foreach ($videos as $video) {
			$this->db->where('jw_media_id', $video['key']);
			$this->db->update('product', ['total_time' => $video['duration']]);

			$this->db->where('jw_media_id', $video['key']);
			$this->db->update('episode', ['total_time' => $video['duration']]);
		}

		log_message('trace', 'Number of videos: ' . count($videos));
	}

}
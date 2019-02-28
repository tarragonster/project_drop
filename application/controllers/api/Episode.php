<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/core/BR_Controller.php';

class Episode extends BR_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function captions_get($episode_id) {
		$this->load->model('episode_model');
		$episode = $this->episode_model->getEpisode($episode_id);
		if ($episode == null) {
			$this->create_error(-77);
		}
		if (empty($episode['jw_media_id'])) {
			$this->create_error(-85);
		}
		$this->load->library('jw_lib');
		$captions = $this->jw_lib->getVideoCaptions($episode['jw_media_id']);
		$this->create_success(['captions' => $captions]);
	}
}
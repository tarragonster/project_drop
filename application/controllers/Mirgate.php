<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mirgate extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function totalTime() {
		$this->db->from('product');
		$this->db->where('jw_media_id <>', '');
		$this->db->where('jw_media_id is not null');
		$products = $this->db->get()->result_array();
		$this->load->library('jw_lib');
		foreach ($products as $product) {
			$video = $this->jw_lib->getVideo($product['jw_media_id']);
			if ($video != null) {
				$this->db->where('product_id', $product['product_id']);
				$this->db->update('product', ['total_time' => $video['duration']]);
			} else {
				echo 'Not found: ' . $product['jw_media_id'] . '(' . $product['product_id'] .') <br/>';
			}
		}

		$this->db->from('episode');
		$this->db->where('jw_media_id <>', '');
		$this->db->where('jw_media_id is not null');
		$episodes = $this->db->get()->result_array();
		foreach ($episodes as $episode) {
			$video = $this->jw_lib->getVideo($episode['jw_media_id']);
			if ($video != null) {
				$this->db->where('episode_id', $episode['episode_id']);
				$this->db->update('episode', ['total_time' => $video['duration']]);
			} else {
				echo 'Not found episode: ' . $episode['jw_media_id'] . '(' . $episode['episode_id'] .') <br/>';
			}
		}
	}

	public function totalTimeVideos() {
		$this->load->library('jw_lib');
		$videos = $this->jw_lib->getVideos();

		foreach ($videos as $video) {
			$this->db->where('jw_media_id', $video['key']);
			$this->db->update('product', ['total_time' => $video['duration']]);

			$this->db->where('episode_id', $video['key']);
			$this->db->update('episode', ['total_time' => $video['duration']]);
		}
	}
}
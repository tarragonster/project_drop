<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testing extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function stripe() {
		$this->load->view('stripe');
	}

	public function jw($video_key = 'MGpDad5P') {
		$this->load->library('jw_lib');
		$video = $this->jw_lib->getVideo($video_key);
		if ($video != null) {
			pre_print($video);
			pre_print($video['key'] . ': ' . $video['duration']);
		}
	}

	public function jwList() {
		$this->load->library('jw_lib');
//		$videos = $this->jw_lib->getVideos(['6LG6L9dP', 'r1HYzLqR', 'qD3bG7CZ', 'DW0ua90f', 'TG8uwCES']);
		$videos = $this->jw_lib->getVideos();
//		$videos = $this->jw_lib->getVideos('6LG6L9dP,r1HYzLqR,qD3bG7CZ');

		pre_print($videos);
		foreach ($videos as $video) {
			echo $video['key'] . ': ' . $video['duration'] . '<br/>';
		}
	}

	public function jwTracks($video_key = 'qD3bG7CZ') {
		$this->load->library('jw_lib');
		$tracks = $this->jw_lib->getVideoCaptions($video_key);
		echo json_encode($tracks);
	}

	public function testFace($token) {
		$this->load->library('facebook_lib');
		$response = $this->facebook_lib->friends($token);
		pre_print($response);
	}
}
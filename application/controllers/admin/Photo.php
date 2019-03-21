<?php

class Photo extends CI_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function thumbnail($pathEncode) {
		$filename = base64_decode($pathEncode);
		$size = $this->input->get('size') * 1;
		if ($size > 512)
			$size = 512;
		if ($size < 40)
			$size = 40;

		if (!file_exists($filename) || !is_file($filename) || !getimagesize($filename)) {
			$filename = 'assets/images/placeholder.png';
		}

		$this->load->model('file_model');
		$thumbFile = $this->file_model->createThumbV2($pathEncode, $size);
		if (!file_exists($thumbFile) || !is_file($thumbFile) || !getimagesize($thumbFile)) {
			// Not an image, or file doesn't exist. Redirect user
			header("Location: " . base_url($filename));
			exit;
		}
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment; filename=" . basename($filename) . ";");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: " . filesize($thumbFile));

		readfile($thumbFile);
	}
}
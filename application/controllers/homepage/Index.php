<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->load->view('sites/homepage/index', []);
	}

	public function support() {
		$this->load->view('sites/homepage/support', []);
	}

	public function privacypolicy() {
		$this->load->view('sites/homepage/privacy-policy', []);
	}
}

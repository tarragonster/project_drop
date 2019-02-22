<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testing extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function stripe() {
		$this->load->view('stripe');
	}
}